<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    Sesion::iniciar();
    BD::conectar();

    $correo = Sesion::leer("usuario");
    $rol = Sesion::leer("rol");

    Pintor::header("Editar usuario");
    ($rol == "Admin")?Pintor::nav_admin():Pintor::nav_alumno();

    if(isset($_POST["Enviar"]))
    {
        
    }

?>
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
<?php
Pintor::footer();
?>