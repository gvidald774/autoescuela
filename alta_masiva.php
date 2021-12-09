<?php
    use PHPMailer\PHPMailer\PHPMailer;
    require "vendor/autoload.php";
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    function mandaCorreoActivacion($correo, $token)
    {
        $mail = new PHPMailer();
        $mail->IsSMTP();

        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;

        $mail->Username = "schobzax@gmail.com"; // Cambiar por autoescuelafuentezuelas o algo así
        $mail->Password = "W0rld of Pain";
        $mail->SetFrom('administrador@autoescuela.es', 'Autoescuela Las Fuentezuelas');
        $mail->Subject = "Correo de activación - Autoescuela Las Fuentezuelas";
        $enlace = "localhost/proyectos/autoescuela/nuevoUsuario.php?token=".$token;

        $mail->AddEmbeddedImage("media/img/small-logo.jpg", "mifoto", "logo.jpg");
        $mail->MsgHTML("<header style=\"overflow: auto; background-color: #9B2705\";><h1><img src=cid:mifoto height=100 style=\"float: left; margin-bottom: 10px; margin-left: 10px;\" /><em>&nbsp;Autoescuela Las Fuentezuelas</em></h1></header>
        <div style=\"background-color: lightblue; padding: 2%;\">
            <p>Hola,</p>
            <p>Pulse el siguiente enlace para activar su acceso al Sistema de la Autoescuela de las Fuentezuelas: </p>
            <p><a href=".$enlace.">$enlace</a></p>
        </div>
        <footer style=\"overflow: auto; background-color: #9B2705\";><p>Este es un mensaje automático enviado por el servicio de activación de usuarios de la Autoescuela Las Fuentezuelas. Si usted no se ha dado de alta o no ha solicitado darse de alta en esta autoescuela, puede ignorar este mensaje tranquilamente.</p></footer>"
        );

        $mail->AddAddress($correo, $correo);

        $resul = $mail->Send();

    }

    function pintaPagina($correoEnvio = "")
    {
        echo "<!DOCTYPE html>
            <html lang=\"en\">
            <head>
                <meta charset=\"UTF-8\">
                <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>Document</title>
                <link rel=\"stylesheet\" href=\"css/main.css\" />
                <script src=\"js/alta_masiva.js\"></script>
            </head>
            <body>
                <header></header>
                    <nav>
                        <ul>
                            <li class=\"dropdown\">
                                <a href=\"tablaUsuarios.php\" class=\"dropbtn\">Usuarios</a>
                                <div class=\"dropdown-content\">
                                    <a href=\"alta_usuario.php\">Alta de usuario</a>
                                    <a href=\"alta_masiva.php\">Alta masiva</a>
                                </div>
                            </li>
                            <li class=\"dropdown\">
                                <a href=\"tablaTematicas.php\" class=\"dropbtn\">Temáticas</a>
                                <div class=\"dropdown-content\">
                                    <a href=\"altaTematica.php\">Alta temática</a>
                                </div>
                            </li>
                            <li class=\"dropdown\">
                                <a href=\"tablaPreguntas.php\" class=\"dropbtn\">Preguntas</a>
                                <div class=\"dropdown-content\">
                                    <a href=\"altaPregunta.php\">Alta pregunta</a>
                                    <a href=\"alta_masiva_preguntas.php\">Alta masiva</a>
                                </div>
                            </li>
                            <li class=\"dropdown\">
                                <a href=\"tablaExamenes.php\" class=\"dropbtn\">Exámenes</a>
                                <div class=\"dropdown-content\">
                                    <a href=\"creaExamen.php\">Alta examen</a>
                                    <a href=\"historicoExamenes.php\">Histórico</a>
                                </div>
                            </li>
                        </ul>
                    </nav>
                <main>
                    <h1>Alta masiva de usuarios</h1>
                    <p>Por favor, introduzca los correos que desea dar de alta, uno por línea.</p>
                    <form action=\"\" method=\"POST\">
                        <textarea id=\"csv\" name=\"csv\"></textarea>
                        <div id=\"error\">$correoEnvio</div>
                        <input type=\"submit\" id=\"botonAltaMasiva\" name=\"botonAltaMasiva\" value=\"Enviar\" />
                    </form>
                </main>
                <footer></footer>
            </body>
            </html>";
    }

    Sesion::iniciar();
    BD::conectar();

    if(!Sesion::existe("usuario"))
    {
        header("Location: login.php");
    }
    else if(Sesion::leer("rol")!="Admin") // Cambiar por profesor
    {
        echo "No tiene permiso para acceder a estos contenidos.";
        header("Refresh: 5, URL=login.php");
    }
    else
    {
        if(isset($_POST["botonAltaMasiva"]))
        {
            $textoCompleto = $_POST["csv"];
            $array = preg_split("/\r\n|\n|\r/", $textoCompleto);

            // Para no mandar dos correos al mismo correo
            $array = array_unique($array);

            for($i = 0; $i < count($array); $i++)
            {
                $token = md5(rand(0,5000000).date(DATE_RFC2822));
                $correoEnvio = mandaCorreoActivacion($array[$i],$token);
                BD::nuevaPendienteActivacion($array[$i], $token);
            }
            pintaPagina($correoEnvio);
        }
        else
        {
            pintaPagina();
        }
    }

?>