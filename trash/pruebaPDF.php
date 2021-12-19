<?php
    use Dompdf\Dompdf;
    require "../vendor/autoload.php";

    $html = '
    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Pedazo de PDF</title>
    </head>
    <body>

    <h2>Ingredientes para aprobar DWES</h2>
    <p>Ingredientes</p>
    <dl>
    <dd>Perseverancia</dd>
    <dd>Constancia</dd>
    <dd>Optimismo</dd>
    <dd>Autoestima</dd>
    <dd>Trabajo en Equipo</dd>
    <dd>Jamón Pata Negra</dd>
    </dl>
    </body>
    </html>
    ';

    $mipdf = new Dompdf();
    # Definimos el tamaño y orientación del papel que queremos.
    # O por defecto cogerá el que está en el fichero de configuración.
    $mipdf->set_paper("A4","portrait");
    # Cargamos el contenido HTML
    $mipdf->load_html($html);

    # Renderizamos el documento PDF.
    $mipdf->render();

    # Creamos un fichero
    $pdf = $mipdf->output();
    $filename = "HeavenTicket.pdf";
    file_put_contents($filename, $pdf);

    # Enviamos el fichero PDF al navegador.
    $mipdf->stream($filename);

?>