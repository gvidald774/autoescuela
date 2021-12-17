<?php
    require_once("include/cargadores/carga_helpers.php");

Sesion::iniciar();
Pintor::header("Guía de estilos");
(Sesion::leer("rol") == "Admin")?Pintor::nav_admin():Pintor::nav_alumno();
?>

<main class="guia_estilos">
    <h1>Guía de estilos</h1>
    <h2>Sumario</h2>
    <p>Autoescuela Las Fuentezuelas es un servicio web que ofrece la posibilidad a sus usuarios de realizar exámenes y recibir correcciones inmediatas sobre los mismos.</p>
    <section class="izquierdo">
        <h2>Colores utilizados</h2>
        <ul>
            <h3>General</h3>
            <li>Fondo: <span class="muestra_color" id="color_fondo">#F7EDE2</span></li>
            <li>Bordes: <span class="muestra_color" id="color_borde">#0B2027</span></li>
            <li>Header: <span class="muestra_color" id="color_header">#94778B</span></li>
            <li>Footer: <span class="muestra_color" id="color_header">#94778B</span></li>
            <h3>Componentes</h3>
            <h4>Botón</h4>
            <li>Fondo de botón: #0B2027<span class="muestra_color" id="color_fondo_boton"></span></li>
            <li>Letra de botón: #F7EDE2<span class="muestra_color" id="color_letra_boton"></span></li>
            <li>Fondo de botón - hover: #103446<span class="muestra_color" id="color_fondo_boton_hover"></span></li>
            <li>Letra de botón - hover: #CCF7C1<span class="muestra_color" id="color_letra_boton_hover"></span></li>
            <h4>Tabla</h4>
            <li>Fila de tabla: #94778B<span class="muestra_color" id="color_tabla_fila"></span></li>
            <li>Celda de tabla: #CCF7C1<span class="muestra_color" id="color_tabla_celda"></span></li>
            <h4>Botón radial</h4>
            <li>Botón radial - hover: #F18F01<span class="muestra_color" id="color_radio_hover"></span></li>
            <li>Botón radial - clic: #FF570A<span class="muestra_color" id="color_radio_clic"></span></li>
        </ul>
    </section>
    <section class="izquierdo">
        <h2>Tipografías utilizadas</h2>
        <ul>
            <li>Texto general: <span class="muestra_tipo" id="normal">Montserrat</span></li>
            <li>Textos especiales (botones, header/footer, etc.): <span style="font-family: 'Fjalla One'">Fjalla One</span></li>
    </section>
</main>

<?php Pintor::footer(); ?>