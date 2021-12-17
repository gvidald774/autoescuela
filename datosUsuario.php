<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    Sesion::iniciar();
    BD::conectar();

    $correo = Sesion::leer("usuario");
    $rol = Sesion::leer("rol");

    $usuario = BD::getUsuarioFromCorreo($correo);

    $errorcillos = array();
    $errorcillos["nombre"] = "";
    $errorcillos["apellidos"] = "";
    $errorcillos["password1"] = "";
    $errorcillos["f_nac"] = "";
    $errorcillos["localidad"] = "";

    if(isset($_POST["Enviar"]))
    {
        $validador = new Validator();

        $validador->existe("nombre");
        $validador->existe("apellidos");
        if($validador->existe("password1"))
        {
            $validador->coincidencia("password1", "password2");
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

            $rolete = ($rol == "Admin")?1:2;
            $ultimoID = BD::cogeUltimoID("usuario");
            $usuario_a_insertar = new Usuario($usuario->id, $correo, $nombre, $apellidos, $contraseña, $f_nac, $rolete, $localidad);

            BD::editaUsuario($usuario_a_insertar);
            header("Location: ".$_SERVER['REQUEST_URI']);
            echo "<div class='mensaje_acierto'>Usuario modificado con éxito.</div>";
        }
        else
        {
            // Validación adicional.
        }
    }

    Pintor::header("Editar usuario",["js/validator.js","js/nuevoUsuario.js"]);
    ($rol == "Admin")?Pintor::nav_admin():Pintor::nav_alumno();

?>
<form action="" method="POST">
    <div><label for="email">Correo:</label><input type="text" name="email" value="<?php echo $correo ?>" disabled/></div>
    <div><label for="nombre">Nombre:</label><input type="text" name="nombre" value="<?php echo $usuario->nombre ?>" required /><div id="error_nombre"><?php echo $errorcillos["nombre"] ?></div></div>
    <div><label for="apellidos">Apellidos:</label><input type="text" name="apellidos" value="<?php echo $usuario->apellidos ?>"required /><div id="error_apellidos"><?php echo $errorcillos["apellidos"] ?></div></div>
    <div><label for="password1">Contraseña:</label><input type="password" name="password1" required /><div id="error_password"><?php echo $errorcillos["password1"] ?></div></div>
    <div><label for="password2">Repita contraseña:</label><input type="password" name="password2" required /></div>
    <div><label for="fecha">Fecha de nacimiento:</label><input type="date" name="f_nac" value="<?php echo $usuario->fechaNacimiento ?>" required /><div id="error_fecha"><?php echo $errorcillos["f_nac"] ?></div></div>
    <div><label for="localidad">Localidad:</label><input type="text" name="localidad" value="<?php echo $usuario->localidad ?>" required /><div id="error_localidad"><?php echo $errorcillos["localidad"] ?></div></div>
    <div><input type="submit" name="Enviar" id="boton_nuevoUsuario" value="Sign up" /></div>
</form>
<?php
Pintor::footer();
?>