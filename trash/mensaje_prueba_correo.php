<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/proyectos/autoescuela/include/helpers/BD.php");
    $usuario = "Guillermo";
    $enlace = "https://www.lmgtfy.es/?q=MD5+encryption+php";

    echo "RÁBANOS MORENOS";
    echo "<br />";
    
    $pass = "A.k.a.D3mia";

    echo md5($pass);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header style="overflow: auto; background-color: #9B2705";><h1><img src="../media/img/small-logo.jpg" height=100 style="float: left; margin-bottom: 10px; margin-left: 10px;" /><em>&nbsp;Autoescuela Las Fuentezuelas</em></h1></header>
    <div style="background-color: lightblue; padding: 2%;">
        <p>Hola <?php echo $usuario; ?>,</p>
        <p>Tú o alguien que tiene acceso a tu cuenta ha solicitado recuperar la contraseña de la autoescuela. Si has sido tú, aquí tienes el enlace. Pínchalo (o cópialo en tu barra de direcciones) y sigue las instrucciones que allí encuentres. Si no has sido tú, puedes ignorar tranquilamente este mensaje.</p>
        <p>Enlace para recuperar contraseña: </p>
        <p><a href="<?php echo $enlace ?>"><?php echo $enlace ?></a></p>
    </div>
    <footer style="overflow: auto; background-color: #9B2705";><p>Este es un mensaje automático enviado por el servicio de recuperación de contraseñas de la <a href="localhost/proyectos/autoescuela/">Autoescuela Las Fuentezuelas.</a></footer>
</body>
</html>