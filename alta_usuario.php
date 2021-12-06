<?php
    require("include/cargadores/carga_helpers.php");
    require_once("include/entities/Usuario.php");

    $validador = new Validator(); // Comprobar el var_dump anda

    BD::conectar();
    Sesion::iniciar();

    if(!Sesion::existe("usuario"))
    {
        header("Location: login.php");
    }

    // Aquí se cogerían las opciones de la Base de Datos.
    $options = array();
    $options = BD::cogeRoles();

    if(!empty($_POST))
    {
        var_dump($_POST);
    
        // Validaciones
        if($validador->existe("email"))
        {
            $validador->email("email");
        }
        $validador->existe("nombre");
        $validador->existe("apellidos");
        if($validador->existe("password1"))
        {
            $validador->coincidencia("password1","password2"); // Estaría bien añadirle una comprobación en javascript.
        }
        $validador->existe("f_nac");

        if($validador->correcto())
        {
            $email = $_POST["email"];
            $nombre = $_POST["nombre"];
            $apellidos = $_POST["apellidos"];
            $contraseña = $_POST["password1"];
            $contraseña = $_POST["password2"];
            $f_nac = $_POST["f_nac"];
            $rol = $_POST["rol"];

            $ultimoId = BD::cogeUltimoId("usuario");
            echo "Último ID: ".$ultimoId;
            $usuario_a_insertar = new Usuario(++$ultimoId,$email,$nombre,$apellidos,$contraseña,$f_nac,$rol);

            BD::insertaUsuario($usuario_a_insertar);
        }
        else
        {
            echo "Hay errores";
            var_dump($validador);
        }

    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/main.css" />
</head>
<body>
    <form action="" method="POST">
        <!--Campos a incluir: email, nombre, apellidos, contraseña, repetir contraseña, fechaNacimiento, rol (select). ¿Localidad? -->
        <div><label for="email">Correo:</label><input type="text" name="email" /></div>
        <div><label for="nombre">Nombre:</label><input type="text" name="nombre" /></div>
        <div><label for="apellidos">Apellidos:</label><input type="text" name="apellidos" /></div>
        <div><label for="password1">Contraseña:</label><input type="password" name="password1" /></div>
        <div><label for="password2">Repita contraseña:</label><input type="password" name="password2" /></div>
        <div><label for="fecha">Fecha de nacimiento:</label><input type="date" name="f_nac" /></div>
        <div>
            <label for="rol">Rol: </label><select name="rol">
                <?php
                    for($i = 0; $i < count($options); $i++)
                    {
                        $id = $options[$i]->id;
                        $descripcion = $options[$i]->descripcion;
                        echo "<option value=\"$id\">$descripcion</option>";
                    }
                ?>
            </select>
        </div>
        <div><input type="submit" name="Enviar" value="Sign up" /></div>
    </form>
    <a href="logoff.php">Logoff</a>
</body>
</html>