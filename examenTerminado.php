<?php
Pintor::header("Examen terminado");
echo '<main>Examen terminado con éxito.</main>';
Pintor::footer();
header("Refresh: 3, url='historicoExamenes.php'");