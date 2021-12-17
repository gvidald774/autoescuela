<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    BD::conectar();
    Sesion::iniciar();

    $mensaje = "<div>El código introducido no existe o ha caducado. Por favor, comuníquese con la persona que le ha dado el alta para que le proporcione un nuevo enlace o compruebe que el que ha introducido es correcto.</div>";

    function errorAlta($mensaje)
    {
        echo $mensaje;
        // Aquí iba a poner un refresh pero no creo que haga falta
    }
    
    if(isset($_GET["token"]))
    {

        if(!BD::existeAltaPendiente($_GET["token"]))
        {
            errorAlta();
        }
        else
        {
            $errorcillos = array();
            $errorcillos["email"] = "";
            $errorcillos["nombre"] = "";
            $errorcillos["apellidos"] = "";
            $errorcillos["password1"] = "";
            $errorcillos["f_nac"] = "";
            $errorcillos["localidad"] = "";

            $email = BD::getCorreoFromToken($_GET["token"]);

            if(isset($_POST["Enviar"]))
            {
                $validador = new Validator();

                $validador->existe("nombre");
                $validador->existe("apellidos");
                if($validador->existe("password1"))
                {
                    $validador->coincidencia("password1","password2");
                }
                $validador->existe("f_nac");
                $validador->existe("localidad");

                if($validador->correcto())
                {
                    $nombre = $_POST["nombre"];
                    $apellidos = $_POST["apellidos"];
                    $contraseña = md5($_POST["password1"]);
                    $f_nac = $_POST["f_nac"];
                    $localidad = $_POST["localidad"];

                    $ultimoId = BD::cogeUltimoId("usuario");
                    $usuario_a_insertar = new Usuario(++$ultimoId,$email,$nombre,$apellidos,$contraseña,$f_nac,2,$localidad);

                    BD::insertaUsuario($usuario_a_insertar);
                    Sesion::escribir("usuario",$email);
                    Sesion::escribir("rol","Alumno");
                    header("Location: usuarioCreado.php");
                }
                else
                {
                    // Se puede buclear esto?
                    $errorcillos["nombre"] = "<div class='mensaje_error'>".$validador->imprimeError("nombre")."</div>";
                    $errorcillos["apellidos"] = "<div class='mensaje_error'>".$validador->imprimeError("apellidos")."</div>";
                    $errorcillos["password1"] = "<div class='mensaje_error'>".$validador->imprimeError("password1")."</div>";
                    $errorcillos["f_nac"] = "<div class='mensaje_error'>".$validador->imprimeError("f_nac")."</div>";

                }
            }
            Pintor::header("Nuevo usuario",["js/validator.js","js/nuevoUsuario.js"]);
            echo '
            <form action="" method="POST">
                <div><label for="email">Correo:</label><input type="text" name="email" value="'.$email.'" disabled/></div>
                <div><label for="nombre">Nombre:</label><input type="text" name="nombre" required /><div id="error_nombre">'.$errorcillos["nombre"].'</div></div>
                <div><label for="apellidos">Apellidos:</label><input type="text" name="apellidos" required /><div id="error_apellidos">'.$errorcillos["apellidos"].'</div></div>
                <div><label for="password1">Contraseña:</label><input type="password" name="password1" required /><div id="error_password">'.$errorcillos["password1"].'</div></div>
                <div><label for="password2">Repita contraseña:</label><input type="password" name="password2" required /></div>
                <div><label for="fecha">Fecha de nacimiento:</label><input type="date" name="f_nac" required /><div id="error_fecha">'.$errorcillos["f_nac"].'</div></div>
                <div><label for="localidad">Localidad:</label><input type="text" name="localidad" required /><div id="error_localidad">'.$errorcillos["localidad"].'</div></div>
                <div><input type="submit" name="Enviar" id="boton_nuevoUsuario" value="Sign up" /></div>
            </form>
            ';
            Pintor::footer();
        }
    }
    else
    {
        errorAlta();
    }
?>