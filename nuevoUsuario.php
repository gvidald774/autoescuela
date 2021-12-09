<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    BD::conectar();

    if(!BD::existeAltaPendiente($_GET["token"]))
    {
        echo "El código introducido no existe o ha caducado. Por favor, comuníquese con la persona que le ha dado el alta para que le proporcione un nuevo enlace o compruebe que el que ha introducido es correcto.";
        // AUTODESTRUCSIÓN
    }
    else
    {

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
        <div><label for="email">Correo:</label><input type="text" name="email" /><?php echo $errorcillos["email"]; ?></div> <!--Desactivar-->
        <div><label for="nombre">Nombre:</label><input type="text" name="nombre" /><?php echo $errorcillos["nombre"]; ?></div>
        <div><label for="apellidos">Apellidos:</label><input type="text" name="apellidos" /><?php echo $errorcillos["apellidos"]; ?></div>
        <div><label for="password1">Contraseña:</label><input type="password" name="password1" /><?php echo $errorcillos["password1"]; ?></div>
        <div><label for="password2">Repita contraseña:</label><input type="password" name="password2" /></div>
        <div><label for="fecha">Fecha de nacimiento:</label><input type="date" name="f_nac" /><?php echo $errorcillos["f_nac"]; ?></div>
        <div><!--Quitar esto porque por defecto sería alumno-->
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
</body>
</html>