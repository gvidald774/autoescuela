<?php
 // Señor que no tiene usted acceso
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta pregunta</title>
    <link rel="stylesheet" href="css/main.css" />
</head>
<body>
    <header>placeholder</header>
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
        <h1>Pregunta</h1>
        <form>
            <select></select>
            <textarea></textarea>
            <table class="invisible">
                <tr>
                    <td><label for="respuesta1">Opción 1</label><input type="text" value="A" name="respuesta1" /></td>
                    <td><input type="radio" value="Correcta" name="radio1" /></td>
                </tr>
            </table>
        </form>
    </main>
    <footer>placeholder</footer>
</body>
</html>