<?php   
    use PHPMailer\PHPMailer\PHPMailer;
    require "../vendor/autoload.php";
    $mail = new PHPMailer();
    $mail->IsSMTP();
    // cambiar a 0 para no ver mensajes de error
    $mail->SMTPDebug  = 0;                          
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = "tls";                 
    $mail->Host       = "smtp.gmail.com";    
    $mail->Port       = 587;                 
    // introducir usuario de google
    $mail->Username   = "schobzax@gmail.com"; 
    // introducir clave
    $mail->Password   = "Thunder_Busters!";       
    $mail->SetFrom('gvidald774@g.educaand.es', 'Guillermo Vidal');
    // asunto
    $mail->Subject    = "Mensaje de Guillermo Vidal - Base64";
    // cuerpo
    $mail->AddEmbeddedImage("../media/img/descarga.jpg", "mifoto", "descarga.jpg");
    $img = file_get_contents("../media/img/descarga.jpg");
    $imgblob = base64_encode($img);
    $mail->MsgHTML('<div style="background-color: lightblue"><h1>Te mando una imagen embebida</h1><p>Aquí la imagen: <img src="cid:mifoto" style="border:dashed 5px black" alt="Imagen embebida. ¡Más fácil!" /></p>
    </div>
    <div style="background-color: lightgoldenrod"><h1>Te mando una imagen en base64</h1><p>Aquí la imagen: <img src="data:image/jpg;base64,'.$imgblob.'" alt="Imagen en Base 64" /></p></div>');
    // adjuntos
    // $mail->addAttachment("enterkey.webp");
    // destinatario
    $address = "jve@ieslasfuentezuelas.com";
    $mail->AddAddress($address, "G");
    // enviar
    $resul = $mail->Send();
    if(!$resul) {
      echo "Error" . $mail->ErrorInfo;
    } else {
      echo "Enviado";
    }