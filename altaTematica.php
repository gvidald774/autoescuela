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
        $validador = new Validator();
        $validador->existe("nombre");
        if($validador->correcto())
        {
            $descNueva = $_POST["nombre"];
            if(isset($_GET["id"]))
            {
                $id = $_GET["id"];
                $result = BD::cambiaTematica($id, $descNueva);
                if($result == "true")
                {
                    $result = "<div class='mensaje_acierto'>Valor cambiado con éxito.</div>";
                    header("Location: ".$_SERVER['REQUEST_URI']);
                }
            }
            else
            {
                if(BD::existeTematica($descNueva))
                {
                    $result = "<div class='mensaje_error'>Valor ya existente.</div>";
                }
                else
                {
                    BD::insertaTematica($descNueva);
                    $result = "<div class=='mensaje_acierto'>Valor insertado con éxito.</div>";
                    header("Location: ".$_SERVER['REQUEST_URI']);
                }
            }
        }
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
            $result = "<div class='mensaje_acierto'>Valor cambiado con éxito.</div>";
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
                $result = "<div class='mensaje_error'>Valor ya existente.</div>";
            }
            else
            {
                BD::insertaTematica($descNueva);
                $result = "<div class='mensaje_acierto'>Valor insertado con éxito.</div>";
            }
        }    
    }

    if(isset($_GET["id"]))
    {
        Pintor::header("Editar temática");
    }
    else
    {
        Pintor::header("Crear temática");
    }
    Pintor::nav_admin();
?>
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
<?php Pintor::footer(); ?>