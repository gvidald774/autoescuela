<?php

class Pintor {
    public static function header($title, $ruta_js = "", $css = "css/main.css")
    {
        echo "<!DOCTYPE html>
        <html lang=\"en\">
        <head>
            <meta charset=\"UTF-8\">
            <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
            <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
            <title>".$title."</title>
            <link rel=\"stylesheet\" href=\"".$css."\" />
            <script src=\"".$ruta_js."\"></script>
        </head>
        <body>
        <header>
        <div class='izquierdo'>Autoescuela Las Fuentezuelas</div>
        <div class='derecho'><a href='logoff.php'>Logoff</a></div>
        </header>";
    }

    public static function nav_admin()
    {
        echo "<nav>
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
    </nav>";
    }

    public static function nav_alumno()
    {
        echo "<nav>
        <ul>
            <li><a href=\"historicoExamenes.php\">Histórico de exámenes</a></li>
            <li><a href=\"tablaExamenes.php\">Examen predefinido</a></li>
            <li><a href=\"examenAleatorio.php\">Examen aleatorio</a></li>
        </ul>
    </nav>";
    }

    public static function footer()
    {
        echo "<footer>
            <section class=\"footer-left\">
                <div>
                    <div><a href=#>Guía de estilo</a></div>
                    <div><a href=#>Mapa web del sitio</a></div>
                </div>
            </section>
            <section class=\"footer-right\">
                <div>Contacto: 
                    <div>Teléfono: 953111222</div>
                    <div>email: info@examinator.es</div>
                    <div>Redes sociales</div>
                </div>
            </section>
            <section class=\"footer-center\">
                <div>Enlaces relacionados:
                <div><a href=#>DGT</a></div>
                <div><a href=#>Solicitud oficial de examen</a></div>
                <div><a href=#>Normativa de examen</a></div>
                </div>
            </section>
        </footer>
        </body>
        </html>";
    }
}