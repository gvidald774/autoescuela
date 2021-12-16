<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    Sesion::iniciar();
    BD::conectar();

    $resultado = "";
    
    if(isset($_POST["enviar"]))
    {
        $resultado = "";
        $validador = new Validator();

        if($validador->email("usuario"))
        {
            $usuario = $_POST["usuario"];
        }

        $pass = $_POST["password"];
        $pass_encrypt = md5($pass); // Esto hay que encriptarlo.

        if($validador->correcto())
        {

            if(Login::identificar($usuario, $pass_encrypt))
            {
                $rol = Login::getRol($usuario);
                Sesion::escribir("usuario",$usuario);
                Sesion::escribir("rol",$rol);
                if(isset($_POST["recuerdame"]))
                {
                    setcookie("usuario-cookie", $usuario, time()+60*60*24);
                    setcookie("pass-cookie", $pass, time()+60*60*24);
                }
    
                header("Location: historicoExamenes.php");
            }
            else
            {
                $resultado = "Credenciales inválidas.";
            }
        }
        else
        {
            $resultado = "Credenciales inválidas.";
        }
    }

    $pintaUsuarioCookie = "";
    $pintaPassCookie = "";

    if(isset($_COOKIE["usuario-cookie"]) && isset($_COOKIE["pass-cookie"]))
    {
        $pintaUsuarioCookie = $_COOKIE["usuario-cookie"];
        $pintaPassCookie = $_COOKIE["pass-cookie"];
    }

    function pintarError($resultado = "")
    {
        echo $resultado;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de ingreso</title>
    <link rel="stylesheet" href="css/main.css" />
</head>
<body>
    <main>
        <img src="media/img/big_logo.webp" />
        <form action="" method="POST">
            <div id="mensaje-error"><?php echo $resultado; ?></div>
            <div><label for="usuario">Usuario: </label><input type="text" id="form_login_usuario" name="usuario" value="<?php echo "$pintaUsuarioCookie" ?>" required /></div>
            <div><label for="password">Contraseña: </label><input type="password" id="form_login_pass" name="password" value="<?php echo "$pintaPassCookie" ?>" required /></div>
            <div><label><input type="checkbox" id="form_login_checkbox" name="recuerdame" />Recuérdame</label></div>
            <div><input type="submit" id="form_login_boton" name="enviar" value="Login" /></div>
        </form>
        <div><a href="olvido.php">¿Olvidaste tu contraseña?</a></div>
    </main>
<?php Pintor::footer(); ?>