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

    Pintor::header();
    Pintor::nav_admin();
?>
    <h1>Temática</h1>
    <nav>
        <ul>
            <li class="dropdown">
                <a href="tablaUsuarios.php" class="dropbtn">Usuarios</a>
                <div class="dropdown-content">
                    <a href="alta_usuario.php">Alta de usuario</a>
                    <a href="alta_masiva.php">Alta masiva</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="tablaTematicas.php" class="dropbtn">Temáticas</a>
                <div class="dropdown-content">
                    <a href="altaTematica.php">Alta temática</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="tablaPreguntas.php" class="dropbtn">Preguntas</a>
                <div class="dropdown-content">
                    <a href="altaPregunta.php">Alta pregunta</a>
                    <a href="alta_masiva_preguntas.php">Alta masiva</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="tablaExamenes.php" class="dropbtn">Exámenes</a>
                <div class="dropdown-content">
                    <a href="creaExamen.php">Alta examen</a>
                    <a href="historicoExamenes.php">Histórico</a>
                </div>
            </li>
        </ul>
    </nav>
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