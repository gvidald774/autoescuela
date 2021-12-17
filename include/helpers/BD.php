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
        $consulta = self::$con->prepare("INSERT INTO usuario VALUES (:id, :email, :nombre, :apellidos, :pass, :fechaNacimiento, :rol, :foto, :localidad)");

        $id = $usuario->getID();
        $email = $usuario->getEmail();
        $nombre = $usuario->getNombre();
        $apellidos = $usuario->getApellidos();
        $pass = $usuario->getPass();
        $fechaNacimiento = $usuario->getFechaNacimiento();
        $rol = $usuario->getRol();
        $foto = $usuario->getFoto();
        $localidad = $usuario->getLocalidad();

        $consulta->bindParam(':id',$id);
        $consulta->bindParam(':email',$email);
        $consulta->bindParam(':nombre',$nombre);
        $consulta->bindParam(':apellidos',$apellidos);
        $consulta->bindParam(':pass',$pass);
        $consulta->bindParam(':fechaNacimiento',$fechaNacimiento);
        $consulta->bindParam(':rol',$rol);
        $consulta->bindParam(':foto',$foto);
        $consulta->bindParam('localidad',$localidad);

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
        $consulta = self::$con->prepare("SELECT max(id) FROM ".$tabla.";");

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

    public static function cogeTematicas()
    {
        $registros = array();
        $res = self::$con->query("SELECT * FROM tematica ORDER BY id ASC");
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
        else
        {
            $consulta = self::$con->prepare("SELECT usuario FROM altas_pendientes WHERE usuario=:usuario");
            $consulta->bindParam(':usuario',$correo);
            $consulta->execute();
            if($consulta->fetch(PDO::FETCH_NUM)[0])
            {
                $result = true;
            }
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

    public static function getPreguntaSola($idPregunta)
    {
        $id = intval($idPregunta);
        $getPregunta = self::$con->prepare("SELECT * FROM pregunta WHERE id=:id");
        $getPregunta->bindParam(':id',$id);
        $getPregunta->execute();
        $pregunta = $getPregunta->fetch(PDO::FETCH_OBJ);

        return $pregunta;
    }

    public static function getPregunta_y_Respuestas($idPregunta)
    {
        $id = intval($idPregunta);
        // Y lo que tengo que hacer aquí es cuidadosamente construir un JSON que se pueda mandar y parsear luego donde la pregunta y tal.
        $getPregunta = self::$con->prepare("SELECT * FROM pregunta WHERE id=:id");
        $getPregunta->bindParam(':id',$id);
        $getPregunta->execute();
        $pregunta = $getPregunta->fetch(PDO::FETCH_OBJ);

        $arrayRespuestas = array();
        $getRespuestas = self::$con->prepare("SELECT * FROM respuesta WHERE idPregunta=:id");
        $getRespuestas->bindParam(':id',$id);
        $getRespuestas->execute();
        $arrayRespuestas = $getRespuestas->fetchAll(PDO::FETCH_OBJ);

        $QandA = array();
        $QandA["pregunta"] = $pregunta;
        $QandA["respuestas"] = $arrayRespuestas;
        return $QandA;
    }

    public static function existeExamen($id)
    {
        $idExamen = intval($id);
        $consulta = self::$con->prepare("SELECT * FROM examen WHERE id=:id");
        $consulta->bindParam(':id',$idExamen);
        $consulta->execute();
        $result = false;
        if ($consulta->fetch(PDO::FETCH_NUM)[0])
        {
            $result = "true";
        }
        return $result;
    }

    public static function getExamen($id)
    {
        $idExamen = intval($id);
        $examenConPreguntas = new stdClass();
        
        $traeExamen = self::$con->prepare("SELECT * FROM examen WHERE id=:id");
        $traeExamen->bindParam(':id',$idExamen);
        $traeExamen->execute();

        $examenTraido = $traeExamen->fetch(PDO::FETCH_OBJ);
        $examenConPreguntas->codigoExamen = $examenTraido->id;
        $examenConPreguntas->enunciado = $examenTraido->descripcion;
        $examenConPreguntas->numPreguntas = $examenTraido->nPreguntas;
        $examenConPreguntas->duracion = $examenTraido->duracion;

        $getBancoPreguntas = self::$con->prepare("SELECT p.id 'id',t.nombre 'tematica',p.enunciado 'enunciado',p.recurso 'recurso' FROM pregunta AS p, tematica AS t WHERE p.id NOT IN (SELECT idPregunta FROM examen_pregunta WHERE idExamen = :id) AND p.tematica=t.id");

        $getBancoPreguntas->bindParam(':id',$idExamen);

        $getBancoPreguntas->execute();
        $bancoPreguntas = $getBancoPreguntas->fetchAll(PDO::FETCH_OBJ);

        $examenConPreguntas->bancoPreguntas = $bancoPreguntas;

        $getSeleccionadas = self::$con->prepare("SELECT p.id 'id',t.nombre 'tematica',p.enunciado 'enunciado',p.recurso 'recurso' FROM pregunta AS p, tematica AS t WHERE p.id IN (SELECT idPregunta FROM examen_pregunta WHERE idExamen = :id) AND p.tematica=t.id");

        $getSeleccionadas->bindParam(':id',$idExamen);

        $getSeleccionadas->execute();
        $seleccionadas = $getSeleccionadas->fetchAll(PDO::FETCH_OBJ);
        
        $examenConPreguntas->preguntasIncluidas = $seleccionadas;

        return $examenConPreguntas;

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
        try {
            $consulta = self::$con->prepare("DELETE FROM $tabla WHERE id=:id");
            $consulta->bindParam(':id',$id);
            $consulta->execute();
            return "true";
        }
        catch(PDOException $e)
        {
            return $consulta->errorInfo();
        }
    }

    public static function borraPregunta($id)
    {
        // Hay que revertir los pasos que hemos hecho al insertar
        try {
            self::$con->beginTransaction();
            // 3. Eliminar la respuesta correcta de la pregunta
            $quitaRespuestaCorrecta = self::$con->prepare("UPDATE pregunta SET respuestaCorrecta = NULL WHERE id=".$id.";");
            $quitaRespuestaCorrecta->execute();
            // 2. Borrar las respuestas asociadas a la pregunta
            $borraRespuesta = self::$con->prepare("DELETE FROM respuesta WHERE idPregunta = ".$id.";");
            $borraRespuesta->execute();
            // 1. Borrar la pregunta
            $borraPregunta = self::$con->prepare("DELETE FROM pregunta WHERE id = ".$id.";");
            $borraPregunta->execute();

            self::$con->commit();
            return "true";
        }
        catch (PDOException $e)
        {
            echo self::$con->errorInfo();
            self::$con->rollBack();
            return $e;
        }
    }

    public static function borraExamen($id)
    {
        try {
            self::$con->beginTransaction();

            // Eliminar todas las preguntas asociadas al examen
            $quitaPreguntas = self::$con->prepare("DELETE FROM examen_pregunta WHERE idExamen = ".$id.";");
            $quitaPreguntas->execute();

            // Eliminar el examen propiamente dicho
            $quitaExamen = self::$con->prepare("DELETE FROM examen WHERE id = ".$id.";");
            $quitaExamen->execute();

            self::$con->commit();
            return "true";
        }
        catch (PDOException $e)
        {
            echo self::$con->errorInfo();
            self::$con->rollBack();
            return $e;
        }
    }

    public static function obtenCuantasPaginasExamen(int $filas, string $idUsuario = "")
    {
        $registros = array();
        $res = self::$con->query("SELECT e_r.id, e_r.fecha, u.nombre, u.apellidos, e_r.calificacion FROM examen_realizado as e_r, usuario as u WHERE e_r.idAlumno=u.id".$idUsuario);
        $registros = $res->fetchAll();
        $total = count($registros);
        $paginas = ceil($total/$filas);

        return $paginas;
    }

    public static function obtenExamenesPaginados(int $pagina, int $filas, string $idUsuario = "")
    {
        $registros = array();
        $res = self::$con->query("SELECT e_r.id, e_r.fecha, u.nombre, u.apellidos, e_r.calificacion FROM examen_realizado as e_r, usuario as u WHERE e_r.idAlumno=u.id".$idUsuario);
        $registros = $res->fetchAll();
        $total = count($registros);
        $paginas = ceil($total/$filas);
        $registros = array();
        if($pagina<=$paginas)
        {
            $inicio = ($pagina-1)*$filas;
            $consulta = self::$con->query("SELECT e_r.id, e_r.fecha, u.nombre, u.apellidos, e_r.calificacion FROM examen_realizado as e_r, usuario as u WHERE e_r.idAlumno=u.id".$idUsuario."  ORDER BY e_r.fecha ASC LIMIT $inicio, $filas");
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

    public static function nuevaPendienteActivacion($correo, $token)
    {
        $fecha = date("Y-m-d H:m:s", strtotime('+24 hours'));
        if(!BD::existeCorreo($correo))
        {
            $consulta = self::$con->prepare("INSERT INTO altas_pendientes VALUES (:token, :correo, :fecha)");

            $consulta->bindParam(':token',$token);
            $consulta->bindParam(':correo',$correo);
            $consulta->bindParam(':fecha',$fecha);

            $consulta->execute();
            return true;
        }
        else
        {
            $consulta = self::$con->prepare("UPDATE altas_pendientes SET token=:token, fecha=:fecha WHERE usuario=:correo");

            $consulta->bindParam(':token',$token);
            $consulta->bindParam(':fecha',$fecha);
            $consulta->bindParam(':correo',$correo);
            
            $consulta->execute();
            return true;
        }
    }

    public static function existeAltaPendiente($token)
    {
        $consulta = self::$con->prepare("SELECT token FROM altas_pendientes WHERE token='".$token."'");
        $consulta->execute();
        $result = false;
        if ($consulta->fetch(PDO::FETCH_NUM)[0])
        {
            $result = true;
        }
        return $result;
    }

    // Hay que añadir los borrados específicos para las preguntas: hay que borrar las respuestas primero. Y bueno, todo eso.

    public static function insertaPregunta(Pregunta $pregunta, int $respuestaCorrecta, $respuestas)
    {
        try {
            self::$con->beginTransaction();

            $insertaPregunta = self::$con->prepare("INSERT INTO pregunta (id, enunciado, recurso, tematica, type) VALUES (:id, :enunciado, :recurso, :tematica, :tipo);");
            
            $idPregunta = $pregunta->getId();
            $enunciado = $pregunta->getEnunciado();
            $recurso = $pregunta->getRecurso();
            $tematica = intval($pregunta->getTematica());
            $tipo = $pregunta->getTipo();

            $insertaPregunta->bindParam(':id',$idPregunta);
            $insertaPregunta->bindParam(':enunciado',$enunciado);
            $insertaPregunta->bindParam(':recurso',$recurso);
            $insertaPregunta->bindParam(':tematica',$tematica);
            $insertaPregunta->bindParam(':tipo',$tipo);

            $insertaPregunta->execute();

            $insertaRespuesta = self::$con->prepare("INSERT INTO respuesta (id, enunciado, idPregunta) VALUES (:id, :enunciado, :idPregunta);");

            for ($i = 0; $i < count($respuestas); $i++)
            {
                $idR = $respuestas[$i]->getId();
                $enunciado = $respuestas[$i]->getEnunciado();
                $insertaRespuesta->bindParam(':id',$idR);
                $insertaRespuesta->bindParam(':enunciado',$enunciado);
                $insertaRespuesta->bindParam(':idPregunta',$idPregunta);
                
                $insertaRespuesta->execute();
            }

            $enunciadoRespuestaCorrecta = $respuestas[$respuestaCorrecta-1]->getEnunciado();
            $cogeIdRespuestaCorrecta = self::$con->query("SELECT max(id) FROM respuesta WHERE enunciado LIKE \"".$enunciadoRespuestaCorrecta."\";");
            $idRespuestaCorrecta = $cogeIdRespuestaCorrecta->fetch(PDO::FETCH_NUM)[0];

            $meteRespuestaCorrecta = self::$con->prepare("UPDATE pregunta SET respuestaCorrecta = ".$idRespuestaCorrecta." WHERE id=".$idPregunta.";");
            $meteRespuestaCorrecta->execute();

            self::$con->commit();
        }
        catch(PDOException $e)
        {
            echo self::$con->errorInfo();
            self::$con->rollBack();
        }
    }

    public static function modificaPregunta(Pregunta $pregunta, int $respuestaCorrecta, $respuestas)
    {
        try {
            self::$con->beginTransaction();

            // Solo podemos cambiar temática, enunciado, respuestaCorrecta y respuestas.
            // 1. Cambiar los enunciados de las respuestas
            $modifRespuesta = self::$con->prepare("UPDATE respuesta SET enunciado=:enunciado WHERE id=:id;");
            for ($i = 0; $i < count($respuestas); $i++)
            {
                $id = $respuestas[$i]->getId();
                $enunciado = $respuestas[$i]->getEnunciado();
                $modifRespuesta->bindParam(':enunciado',$enunciado);
                $modifRespuesta->bindParam(':id',$id);

                $modifRespuesta->execute();
            }
            // 2. Cambiar la respuesta correcta
            $enunciadoRespuestaCorrecta = $respuestas[$respuestaCorrecta-1]->getEnunciado();
            $cogeIdRespuestaCorrecta = self::$con->query("SELECT id FROM respuesta WHERE enunciado LIKE \"".$enunciadoRespuestaCorrecta."\" AND idPregunta=".$pregunta->getId().";");
            $idRespuestaCorrecta = $cogeIdRespuestaCorrecta->fetch(PDO::FETCH_NUM)[0];

            $meteRespuestaCorrecta = self::$con->prepare("UPDATE pregunta SET respuestaCorrecta = ".$idRespuestaCorrecta." WHERE id=".$pregunta->getId().";");
            $meteRespuestaCorrecta->execute();
            
            // 3. Cambiar el enunciado de la pregunta
            $cambiaEnunciadoPregunta = self::$con->prepare("UPDATE pregunta SET enunciado = :enunciado WHERE id=:id");
            $enunciadoP = $pregunta->getEnunciado();
            $idP = $pregunta->getId();
            $cambiaEnunciadoPregunta->bindParam(':enunciado',$enunciadoP);
            $cambiaEnunciadoPregunta->bindParam(':id',$idP);
            $cambiaEnunciadoPregunta->execute();

            // 4. Cambiar la temática de la pregunta
            $cambiaTematicaPregunta = self::$con->prepare("UPDATE pregunta SET tematica = ".intval($pregunta->getTematica())." WHERE id=".$idP.";");
            $cambiaTematicaPregunta->execute();

            // 5. Cambiar el recurso, supongo
            $cambiaRecursoPregunta = self::$con->prepare("UPDATE pregunta SET recurso=:recurso WHERE id=:id;");
            $recurso = $pregunta->getRecurso();
            
            $cambiaRecursoPregunta->bindParam(':recurso',$recurso);
            $cambiaRecursoPregunta->bindParam('id',$idP);
            $cambiaRecursoPregunta->execute();

            self::$con->commit();
        }
        catch(PDOException $e)
        {
            echo self::$con->errorInfo();
            self::$con->rollBack();
        }
    }

    public static function insertaExamen(Examen $examen)
    {
        try {
            self::$con->beginTransaction();

            $insertaExamenDatosGenerales = self::$con->prepare("INSERT INTO examen (id, descripcion, duracion, nPreguntas, activo) VALUES (:id, :descripcion, :duracion, :nPreguntas, 1)");

            $id = $examen->getID();
            $descripcion = $examen->getDescripcion();
            $duracion = $examen->getDuracion();
            $nPreguntas = $examen->getNPreguntas();

            $insertaExamenDatosGenerales->bindParam(':id',$id);
            $insertaExamenDatosGenerales->bindParam(':descripcion',$descripcion);
            $insertaExamenDatosGenerales->bindParam(':duracion',$duracion);
            $insertaExamenDatosGenerales->bindParam(':nPreguntas',$nPreguntas);

            $insertaExamenDatosGenerales->execute();

            $insertaPreguntasExamen = self::$con->prepare("INSERT INTO examen_pregunta VALUES (:idExamen, :idPregunta)");

            $insertaPreguntasExamen->bindParam(':idExamen',$id);
            for($i = 0; $i < count($examen->getPreguntas()); $i++)
            {
                $idPregunta = $examen->getPreguntas()[$i]->getID();
                $insertaPreguntasExamen->bindParam(':idPregunta',$idPregunta);
                $insertaPreguntasExamen->execute();
            }

            self::$con->commit();
        }
        catch(PDOException $e)
        {
            echo self::$con->errorInfo();
            self::$con->rollBack();
        }

    }

    public static function modificaExamen(Examen $examen)
    {
        try {
            self::$con->beginTransaction();

            // Para quitarnos de problemas, lo primero que hacemos es eliminar todas las preguntas asociadas y volverlas a insertar.
            $id = $examen->getID();
            $quitaPreguntas = self::$con->prepare("DELETE FROM examen_pregunta WHERE idExamen = ".$id.";");
            $quitaPreguntas->execute();

            $insertaPreguntas = self::$con->prepare("INSERT INTO examen_pregunta VALUES (:idExamen, :idPregunta)");

            $insertaPreguntas->bindParam(':idExamen',$id);
            for($i = 0; $i < count($examen->getPreguntas()); $i++)
            {
                $idPregunta = $examen->getPreguntas()[$i]->getID();
                $insertaPreguntas->bindParam(':idPregunta',$idPregunta);
                $insertaPreguntas->execute();
            }

            // Ahora se modifica el examen propiamente dicho.
            $modificaExamenDatosGenerales = self::$con->prepare("UPDATE examen SET descripcion=:enunciado, duracion=:duracion, nPreguntas=:nPreguntas WHERE id=:id");

            $modificaExamenDatosGenerales->bindParam(':id',$id);
            $enunciado = $examen->getDescripcion();
            $duracion = $examen->getDuracion();
            $nPreguntas = $examen->getNPreguntas();

            $modificaExamenDatosGenerales->bindParam(':enunciado',$enunciado);
            $modificaExamenDatosGenerales->bindParam(':duracion',$duracion);
            $modificaExamenDatosGenerales->bindParam(':nPreguntas',$nPreguntas);

            $modificaExamenDatosGenerales->execute();
            
            self::$con->commit();
        }
        catch(PDOException $e)
        {
            echo self::$con->errorInfo();
            self::$con->rollBack();
        }
    }

    public static function accesoExamenRealizado($idExamen, $correoAlumno)
    {
        // Lo primero es averiguar el id del usuario.
        $idUsuario = self::getIdAlumno($correoAlumno);

        // Lo segundo es ver si coinciden.
        $result = false;
        $consulta = self::$con->prepare("SELECT * FROM examen_realizado WHERE id=:idExamen AND idAlumno=:idAlumno");
        $consulta->bindParam(':idExamen',$idExamen);
        $consulta->bindParam(':idAlumno',$idUsuario);

        $consulta->execute();
        if($consulta->fetch(PDO::FETCH_NUM)[0])
        {
            $result = true;
        }
        return $result;

    }

    // Bueno, ya lo he hecho lo voy a dejar por si acaso pero no lo voy a usar.
    public static function getExamenRealizadoCompleto($id)
    {
        // Tenemos que hacer varios queries.
        // Lo primero es sacar los datos del examen.
        $consultaExamen = self::$con->prepare("SELECT e.* FROM examen AS e, examen_realizado AS e_r WHERE e_r.idExamen = e.id AND e_r.id=:id");
        $consultaExamen->bindParam(':id',$id);
        $consultaExamen->execute();
        $examen = $consultaExamen->fetch(PDO::FETCH_OBJ);
        // A continuación sacamos los datos de las preguntas
        $consultaPreguntas = self::$con->prepare("SELECT idPregunta FROM examen_pregunta WHERE idExamen=:id");
        $idExamen =$examen->id;
        $consultaPreguntas->bindParam(':id',$idExamen);
        $consultaPreguntas->execute();
        $idPreguntas = $consultaPreguntas->fetchAll(PDO::FETCH_NUM);

        $arrayPreguntas = array();
        for($i = 0; $i < count($idPreguntas); $i++)
        {
            $arrayPreguntas[] = BD::getPregunta_y_Respuestas($idPreguntas[$i]);
        }
        // Lo siguiente es sacar los datos del usuario que lo ha realizado.
        $consultaUsuario = self::$con->prepare("SELECT u.* FROM usuario AS u, examen_realizado as e_r WHERE e_r.idAlumno = u.id AND e_r.idAlumno = :id");
        $consultaUsuario->bindParam(':id',$id);
        $consultaUsuario->execute();
        $usuario = $consultaUsuario->fetch(PDO::FETCH_OBJ);

        // Lo siguiente ya es juntarlo todo.
        $examenRealizadoFull = array();
        $examenRealizadoFull["examen"] = $examen;
        $examenRealizadoFull["preguntas"] = $arrayPreguntas;
        $examenRealizadoFull["usuario"] = $usuario;
        
        // Por último cogemos la parte más importante. Nota: El resto de campos son importantes para temas de indexación (?)
        $getJSONdata = self::$con->prepare("SELECT json FROM examen_realizado WHERE id=:id");
        $getJSONdata->bindParam(':id',$id);
        $getJSONdata->execute();
        $jsonData = $getJSONdata->fetch(PDO::FETCH_OBJ);
        $examenRealizadoFull["json"] = $jsonData;
    }

    public static function getJSON_ExamenRealizado($id)
    {
        $getJSON = self::$con->prepare("SELECT json FROM examen_realizado WHERE id=:id");
        $getJSON->bindParam(':id',$id);
        $getJSON->execute();
        $json = $getJSON->fetch(PDO::FETCH_NUM)[0];
        return $json;
    }

    public static function getJSON_ExamenPorRealizar($idExamen)
    {
        $examen = BD::getExamen($idExamen);
        $arrayPR = array();
        for($i = 0; $i < count($examen->preguntasIncluidas); $i++)
        {
            $arrayPR[] = BD::getPregunta_y_Respuestas($examen->preguntasIncluidas[$i]->id);
        }

        $objeto = new stdClass();
        $objeto->codigoExamen = $examen->codigoExamen;
        $objeto->enunciado = $examen->enunciado;
        $objeto->numPreguntas = $examen->numPreguntas;
        $objeto->duracion = $examen->duracion;
        $objeto->preguntas = $arrayPR;

        return $objeto;
    }

    public static function guardaExamenRealizado($examen, $alumno, $calificacion)
    {
        $idTabla = BD::cogeUltimoID("examen_realizado")+1;
        $consulta = self::$con->prepare("INSERT INTO examen_realizado VALUES (:id, :idExamen, :idAlumno, NOW(), :calificacion, :jotason)");

        $idExamen = $examen->codigoExamen;
        $jotason = json_encode($examen);

        $consulta->bindParam(':id',$idTabla);
        $consulta->bindParam(':idExamen',$idExamen);
        $consulta->bindParam(':idAlumno',$alumno);
        $consulta->bindParam(':calificacion',$calificacion);
        $consulta->bindParam(':jotason',$jotason);

        $consulta->execute();
    }

    public static function getCuantosExamenes($idUsuario)
    {
        $consulta = self::$con->prepare("SELECT COUNT(*) FROM examen_realizado WHERE idAlumno=:id");
        $consulta->bindParam(':id',$idUsuario);
        $consulta->execute();
        $cantidad = $consulta->fetch(PDO::FETCH_NUM)[0];
        return $cantidad;
    }

    public static function obtenStatsExamen($idUsuario)
    {
        $consulta = self::$con->prepare("SELECT TRUNCATE(AVG(calificacion),2) 'avg', MAX(calificacion) 'max', COUNT(calificacion) 'count' FROM examen_realizado WHERE idAlumno=:id");
        $consulta->bindParam(':id',$idUsuario);
        $consulta->execute();
        $datos = $consulta->fetch(PDO::FETCH_OBJ);
        return $datos;
    }

    public static function getCorreoFromToken($token)
    {
        $consulta = self::$con->prepare("SELECT usuario FROM altas_pendientes WHERE token=:token");
        $consulta->bindParam(':token',$token);
        $consulta->execute();
        $correo = $consulta->fetch(PDO::FETCH_NUM)[0];
        return $correo;
    }

    public static function getUsuarioFromCorreo($correo)
    {
        $consulta = self::$con->prepare("SELECT * FROM usuario WHERE email=:correo");
        $consulta->bindParam(':correo',$correo);
        $consulta->execute();
        $usuario = $consulta->fetch(PDO::FETCH_OBJ);
        return $usuario;
    }

    public static function editaUsuario($usuario)
    {
        $consulta = self::$con->prepare("UPDATE usuario SET nombre=:nombre, apellidos=:apellidos, pass=:pass, fechaNacimiento=:fecha, foto=:foto, localidad=:localidad WHERE id=:id");

        $id = $usuario->getID();
        $nombre = $usuario->getNombre();
        $apellidos = $usuario->getApellidos();
        $pass = $usuario->getPass();
        $fecha = $usuario->getFechaNacimiento();
        $foto = $usuario->getFoto();
        $localidad = $usuario->getLocalidad();

        $consulta->bindParam(':id',$id);
        $consulta->bindParam(':nombre', $nombre);
        $consulta->bindParam(':apellidos', $apellidos);
        $consulta->bindParam(':pass', $pass);
        $consulta->bindParam(':fecha', $fecha);
        $consulta->bindParam(':foto', $foto);
        $consulta->bindParam(':localidad', $localidad);

        $consulta->execute();
    }
}