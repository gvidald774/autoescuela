<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    BD::conectar();
    Sesion::iniciar();

    $result = "";
    $id = 0;
    $desc = "";

    // HAY QUE PREGUNTAR POR EL BOTÓN
    if(isset($_POST["Enviar"]))
    {
        // Hacé cosas de que se tienen que hacer después.
        // Como sigue ocurriendo puedo trampear si eso.
    }
    else
    {
        // Acabamos de llegar a la página, así que tenemos que pintar las cosas y tal y pascual.
    }

    // Esto es si no venimos de nuevas
    if(isset($_GET["id"]))
    {
        $id = $_GET["id"];
        $desc = BD::getTematica($id);
    }

    if(isset($_POST["id"]) && $_POST["id"]!=0)
    {
        $descNueva = $_POST["nombre"];
        $result = BD::cambiaTematica($id,$descNueva);
        if($result == true)
        {
            $result = "Valor cambiado con éxito.";
        }
        unset($_GET["desc"]);
        $_GET["desc"] = $descNueva;
    }
    else
    {
        // Esto es si venimos de nuevas
        if(isset($_POST["nombre"]))
        {
            $descNueva = $_POST["nombre"];
            if(BD::existeTematica($descNueva))
            {
                $result = "Valor ya existente.";
            }
            else
            {
                BD::insertaTematica($descNueva);
                $result = "Valor insertado con éxito.";
            }
        }    
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta Temática</title>
    <link rel="stylesheet" href="css/main.css" />
</head>
<body>
    <header></header>
    <h1>Temática</h1>
    <main>
        <form action="" method="POST">
            <div class="oculto"><input type="number" name="id" value="<?php echo $id?>" /></div>
            <div><label for="tematica-nombre">Descripción:</label><input type="text" name="nombre" required value="<?php echo $desc ?>"/></div>
            <div><input type="submit" name="Enviar" value="Enviar" /></div>
            <div><?php echo $result; ?></div>
        </form>
        <a href="tablaTematicas.php">Volver</a>
    </main>
    <footer></footer>
</body>
</html>