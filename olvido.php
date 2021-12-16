<?php
    use PHPMailer\PHPMailer\PHPMailer;
    require "vendor/autoload.php";
    require "credenciales.php";

    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    Sesion::iniciar();

    BD::conectar();

    $mensajes = array();
    $mensajes["correcto"] = "Le hemos enviado una solicitud para recuperar su contraseña al correo introducido. Si no aparece, compruebe su carpeta de spam o espere unos minutos e inténtelo de nuevo.";
    $mensajes["noexiste"] = "El correo introducido no existe en nuestro sistema. Compruebe si se ha equivocado al introducirlo o pruebe con otro.";

    $resultado = "";

    // Por aquí más o menos puedo hacer lo del botón. Al fin y al cabo no es necesario un validador antes de tiempo, no?

    $validador = new Validator();

    if((isset($_POST["email-olvidado"]) && $_POST["email-olvidado"] != ""))
    {
        if($validador->email("email-olvidado"))
        {
           if (BD::existeCorreo($_POST["email-olvidado"]))
           {
               $correo = $_POST["email-olvidado"];
               $resultado = $mensajes["correcto"];
                $mail = new PHPMailer();
                $mail->IsSMTP();
                // cambiar a 0 para no ver mensajes de error
                $mail->SMTPDebug  = 0;                          
                $mail->SMTPAuth   = true;
                $mail->SMTPSecure = "tls";                 
                $mail->Host       = "smtp.gmail.com";    
                $mail->Port       = 587;                 
                // introducir usuario de google
                $mail->Username   = Credenciales::$usuario;
                // introducir clave
                $mail->Password   = Credenciales::$password;
                $mail->SetFrom('administrador@autoescuela.es', 'Autoescuela Las Fuentezuelas');
                // asunto
                $mail->Subject    = "Recuperar contraseña";
                // Generación del enlace olvidado
                $valor = rand(0,500000);
                $fecha = date(DATE_RFC2822);
                $token = md5($valor.$fecha);
                $enlace = $_SERVER['DOCUMENT_ROOT'].'/proyectos/autoescuela/nuevoUsuario.php?token='.$token;

                // Esto no es, pero bueno
                BD::nuevaPendienteActivacion($token);
                // cuerpo
                // datos del cuerpo
                $mail->AddEmbeddedImage("media/img/small-logo.jpg", "mifoto", "descarga.jpg");
                $usuario = BD::getNombreFromCorreo($correo);
                $mail->MsgHTML("<header style=\"overflow: auto; background-color: #9B2705\";><h1><img src=cid:mifoto height=100 style=\"float: left; margin-bottom: 10px; margin-left: 10px;\" /><em>&nbsp;Autoescuela Las Fuentezuelas</em></h1></header>
                <div style=\"background-color: lightblue; padding: 2%;\">
                    <p>Hola $usuario,</p>
                    <p>Tú o alguien que tiene acceso a tu cuenta ha solicitado recuperar la contraseña de la autoescuela. Si has sido tú, aquí tienes el enlace. Pínchalo (o cópialo en tu barra de direcciones) y sigue las instrucciones que allí encuentres. Si no has sido tú, puedes ignorar tranquilamente este mensaje.</p>
                    <p>Enlace para recuperar contraseña: </p>
                    <p><a href=$enlace>$enlace</a></p>
                </div>
                <footer style=\"overflow: auto; background-color: #9B2705\";><p>Este es un mensaje automático enviado por el servicio de recuperación de contraseñas de la <a href=\"localhost/proyectos/autoescuela/\">Autoescuela Las Fuentezuelas.</a></footer>");
                // destinatario
                $address = $correo;
                $mail->AddAddress($address, $usuario);
                // enviar
                $resul = $mail->Send();
           }
           else
           {
               $resultado = $mensajes["noexiste"];
           }
        }
        else
        {
            $resultado = $mensajes["noexiste"];
        }
    }

    Pintor::header("Olvido de contraseña");
?>
    <form action="" method="POST">
        <p>Si has olvidado tu contraseña, pon tu correo aquí y si tenemos un usuario con ese correo, te llegará un enlace para cambiar tu contraseña.</p>
        <input type="text" id="olvido-form-email" name="email-olvidado" required />
        <input type="submit" id="olvido-form-boton" name="boton-email-olvidado" value="Enviar" />
        <div id="mensaje-error"><?php echo $resultado; ?></div>
    </form>
    <p><a href="login.php">Volver</a></p>
<?php Pintor::footer(); ?>