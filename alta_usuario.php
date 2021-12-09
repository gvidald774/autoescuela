<?php
    require_once("include/cargadores/carga_helpers.php");
    require_once("include/cargadores/carga_entities.php");

    BD::conectar();
    Sesion::iniciar();

    if(!Sesion::existe("usuario"))
    {
        header("Location: login.php");
    }
    else if(Sesion::leer("rol")!= "Admin") // Cambiar a "Profesor".
    {
        echo "No tiene permiso para acceder a estos contenidos.";
        header("Refresh: 5, URL=tablaExamenes.php");
    }
    else
    {
        $options = array();
        $options = BD::cogeRoles();

        $errorcillos = array();
        $errorcillos["email"] = "";
        $errorcillos["nombre"] = "";
        $errorcillos["apellidos"] = "";
        $errorcillos["password1"] = "";
        $errorcillos["f_nac"] = "";

        if(isset($_POST["Enviar"]))
        {
            $validador = new Validator();

            if($validador->existe("email"))
            {
                $validador->email("email");
            }
            $validador->existe("nombre");
            $validador->existe("apellidos");
            if($validador->existe("password1"))
            {
                $validador->coincidencia("password1","password2"); // Estaría bien añadirle una comprobación en JavaScript.
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
                // Se puede buclear esto?
                $errorcillos["email"] = $validador->imprimeError("email");
                $errorcillos["nombre"] = $validador->imprimeError("nombre");
                $errorcillos["apellidos"] = $validador->imprimeError("apellidos");
                $errorcillos["password1"] = $validador->imprimeError("password1");
                $errorcillos["f_nac"] = $validador->imprimeError("f_nac");

            }
        }
    }

    Pintor::header("Alta de usuario individual");
    Pintor::nav_admin();
?>
    <form action="" method="POST">
        <div><label for="email">Correo:</label><input type="text" name="email" /><?php echo $errorcillos["email"]; ?></div>
        <div><label for="nombre">Nombre:</label><input type="text" name="nombre" /><?php echo $errorcillos["nombre"]; ?></div>
        <div><label for="apellidos">Apellidos:</label><input type="text" name="apellidos" /><?php echo $errorcillos["apellidos"]; ?></div>
        <div><label for="password1">Contraseña:</label><input type="password" name="password1" /><?php echo $errorcillos["password1"]; ?></div>
        <div><label for="password2">Repita contraseña:</label><input type="password" name="password2" /></div>
        <div><label for="fecha">Fecha de nacimiento:</label><input type="date" name="f_nac" /><?php echo $errorcillos["f_nac"]; ?></div>
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
<?php Pintor::footer(); ?>