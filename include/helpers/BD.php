<?php
    require_once("C:\wamp64\www\proyectos\autoescuela\include\cargadores\carga_entities.php");
    require_once("C:\wamp64\www\proyectos\autoescuela\include\cargadores\carga_helpers.php");

class BD {
    private static $con;
    public static function conectar()
    {
        // Opciones por si acaso los nombres no salen bien codificados -borrar luego
        $opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4");
        self::$con = new PDO('mysql:host=localhost; dbname=autoescuela', 'root', '', $opciones); // Añadir $opciones al final si hiciese falta
    }

    // Funciones de usuario, login, etc.

    public static function cogeTodosUsuarios() : array
    {
        $arr = array();
        $registros = self::$con->query("SELECT * FROM usuario");
        while($registro = $registros->fetch(PDO::FETCH_OBJ))
        {
            $u = new Usuario($registro->id, $registro->email, $registro->nombre, $registro->apellidos, $registro->pass, $registro->fechaNacimiento, $registro->rol, $registro->foto, $registro->activo);
            $arr[] = $u;
        }

        return $arr;
    }

    public static function traePreguntas():array
    {
        $arr = array();
        $registros = self::$con->query("SELECT * FROM pregunta");
        while($registro = $registros->fetch(PDO::FETCH_OBJ))
        {
            $tematica = BD::getTematica($registro->tematica);
            $p = new Pregunta($registro->id, $registro->enunciado, $tematica, $registro->recurso);
            $respuestaCorrecta = BD::getRespuesta($registro->respuestaCorrecta, $p);
            $p->setRespuestaCorrecta($respuestaCorrecta);
            $arr[] = $p;
        }
        return $arr;
    }

    public static function getTematica(int $t): string
    {
        $consulta = self::$con->prepare("SELECT nombre FROM tematica WHERE id=:id");

        $consulta->bindParam(':id',$t);

        $consulta->execute();

        $registro = $consulta->fetch(PDO::FETCH_OBJ);
        return $registro->nombre;

    }

    public static function getRespuesta(int $id, Pregunta $p)
    {
        $consulta = self::$con->prepare("SELECT * FROM respuesta WHERE id=:id");

        $consulta->bindParam(':id',$id);

        $consulta->execute();

        $registro = $consulta->fetch(PDO::FETCH_OBJ);
        $respuesta = new Respuesta($registro->id, $registro->enunciado, $p);

        return $respuesta;
    }

    public static function insertaUsuario($usuario)
    {
        $consulta = self::$con->prepare("INSERT INTO usuario VALUES (:id, :email, :nombre, :apellidos, :pass, :fechaNacimiento, :rol, :foto)");

        $id = $usuario->getID();
        $email = $usuario->getEmail();
        $nombre = $usuario->getNombre();
        $apellidos = $usuario->getApellidos();
        $pass = $usuario->getPass();
        $fechaNacimiento = $usuario->getFechaNacimiento();
        $rol = $usuario->getRol();
        $foto = $usuario->getFoto();

        $consulta->bindParam(':id',$id);
        $consulta->bindParam(':email',$email);
        $consulta->bindParam(':nombre',$nombre);
        $consulta->bindParam(':apellidos',$apellidos);
        $consulta->bindParam(':pass',$pass);
        $consulta->bindParam(':fechaNacimiento',$fechaNacimiento);
        $consulta->bindParam(':rol',$rol);
        $consulta->bindParam(':foto',$foto);

        $consulta->execute();
    }

    public static function login($usuario, $pass)
    {
        $consulta = self::$con->prepare("SELECT * FROM usuario WHERE email=:user AND pass=:pass");

        $consulta->bindParam(':user',$usuario);
        $consulta->bindParam(':pass',$pass);

        $arr = array();
        $consulta->execute();
        while($registro = $consulta->fetch(PDO::FETCH_OBJ))
        {
            $u = new Usuario($registro->id, $registro->email, $registro->nombre, $registro->apellidos, $registro->pass, $registro->fechaNacimiento, $registro->rol, $registro->foto);
            $arr[] = $u;
        }

        return $arr;
    }

    // Función "Coge Último Id" de la tabla correspondiente.

    public static function cogeUltimoID($tabla) // Check for errors because sheesh
    {
        $consulta = self::$con->prepare("SELECT max(id) FROM $tabla");

        $consulta->execute();

        $idmax = $consulta->fetch(PDO::FETCH_NUM)[0];

        return $idmax;
    }

    public static function obtenDatosPaginados(int $pagina, int $filas, string $tabla)
    {
        $registros = array();
        $res = self::$con->query("SELECT * FROM $tabla");
        $registros = $res->fetchAll();
        $total = count($registros);
        $paginas = ceil($total/$filas);
        $registros = array();
        if($pagina<=$paginas)
        {
            $inicio = ($pagina-1)*$filas;
            $consulta = self::$con->query("SELECT * FROM $tabla  ORDER BY id ASC LIMIT $inicio, $filas");
            $consulta_registros = $consulta->fetchAll(PDO::FETCH_ASSOC);
        }
        return $consulta_registros;
    }

    public static function obtenCuantasPaginas(int $filas, string $tabla)
    {
        $registros = array();
        $res = self::$con->query("SELECT * FROM $tabla");
        $registros = $res->fetchAll();
        $total = count($registros);
        $paginas = ceil($total/$filas);

        return $paginas;
    }

    public static function getRol($correo)
    {
        $consulta = self::$con->prepare("SELECT roles.descripcion FROM roles, usuario WHERE roles.id=usuario.rol AND usuario.email='$correo'");

        $consulta->bindParam(':email',$correo);
        $consulta->execute();
        
        $rol = $consulta->fetch(PDO::FETCH_NUM)[0];
        return $rol;
    }

    public static function cogeRoles()
    {
        $registros = array();
        $res = self::$con->query("SELECT * FROM roles");
        $registros = $res->fetchAll(PDO::FETCH_OBJ);
        return $registros;
    }

    public static function existeCorreo($correo): bool
    {
        $consulta = self::$con->prepare("SELECT email FROM usuario WHERE email=:email");
        $consulta->bindParam(':email',$correo);
        $consulta->execute();
        $result = false;
        if ($consulta->fetch(PDO::FETCH_NUM)[0])
        {
            $result = true;
        }
        return $result;
    }

    public static function getNombreFromCorreo($correo)
    {
        $consulta = self::$con->prepare("SELECT nombre FROM usuario WHERE email=:email");
        $consulta->bindParam(':email',$correo);
        $consulta->execute();
        $nombre = $consulta->fetch(PDO::FETCH_NUM)[0];
        return $nombre;
    }

    public static function getPregunta_y_Respuestas($idPregunta)
    {
        // Y lo que tengo que hacer aquí es cuidadosamente construir un JSON que se pueda mandar y parsear luego donde la pregunta y tal.
    }

    public static function existeTematica($desc):bool
    {
        $consulta = self::$con->prepare("SELECT nombre FROM tematica WHERE nombre=:descripcion");
        $consulta->bindParam(':descripcion',$desc);
        $consulta->execute();
        $result = false;
        if ($consulta->fetch(PDO::FETCH_NUM)[0])
        {
            $result = true;
        }
        return $result;
    }

    public static function insertaTematica($desc)
    {
        $consulta = self::$con->prepare("INSERT INTO tematica (nombre) VALUES (:descripcion)");
        $consulta->bindParam(':descripcion',$desc);
        $consulta->execute();
        if(self::$con->errorInfo() == "")
        {
            return true;
        }
        else
        {
            return self::$con->errorInfo();
        }
    }

    public static function cambiaTematica($id,$desc)
    {
        $consulta = self::$con->prepare("UPDATE tematica SET nombre=:nombre WHERE id=:id");
        $consulta->bindParam(':nombre',$desc);
        $consulta->bindParam(':id',$id);
        $consulta->execute();
        if(self::$con->errorInfo() == "")
        {
            return true;
        }
        else
        {
            return self::$con->errorInfo();
        }
    }

    public static function borraDato($tabla, $id)
    {
        $consulta = self::$con->prepare("DELETE FROM $tabla WHERE id=:id");
        $consulta->bindParam(':id',$id);
        $consulta->execute();
        if(self::$con->errorInfo() == "")
        {
            return true;
        }
        else
        {
            return self::$con->errorInfo();
        }
    }

    public static function obtenCuantasPaginasExamen(int $filas, string $idUsuario = "")
    {
        $registros = array();
        $res = self::$con->query("SELECT e_r.fecha, u.nombre, u.apellidos, e_r.calificacion FROM examen_realizado as e_r, usuario as u WHERE e_r.idAlumno=u.id".$idUsuario);
        $registros = $res->fetchAll();
        $total = count($registros);
        $paginas = ceil($total/$filas);

        return $paginas;
    }

    public static function obtenExamenesPaginados(int $pagina, int $filas, string $idUsuario = "")
    {
        $registros = array();
        $res = self::$con->query("SELECT e_r.fecha, u.nombre, u.apellidos, e_r.calificacion FROM examen_realizado as e_r, usuario as u WHERE e_r.idAlumno=u.id".$idUsuario);
        $registros = $res->fetchAll();
        $total = count($registros);
        $paginas = ceil($total/$filas);
        $registros = array();
        if($pagina<=$paginas)
        {
            $inicio = ($pagina-1)*$filas;
            $consulta = self::$con->query("SELECT e_r.fecha, u.nombre, u.apellidos, e_r.calificacion FROM examen_realizado as e_r, usuario as u WHERE e_r.idAlumno=u.id".$idUsuario."  ORDER BY e_r.fecha ASC LIMIT $inicio, $filas");
            $consulta_registros = $consulta->fetchAll(PDO::FETCH_ASSOC);
        }
        return $consulta_registros;
    }

    public static function getIdAlumno(string $correo)
    {
        $consulta = self::$con->prepare("SELECT id FROM usuario WHERE email=:email");
        $consulta->bindParam(':email',$correo);
        $consulta->execute();
        $id = $consulta->fetch(PDO::FETCH_NUM)[0];
        return $id;
    }

    // Hay que añadir los borrados específicos para las preguntas: hay que borrar las respuestas primero. Y bueno, todo eso.
}