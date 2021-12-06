<?php

include_once("../include/helpers/BD.php");
BD::conectar();

$npag = BD::obtenCuantasPaginas($_GET['t'],"tematica");

$lista = BD::obtenDatosPaginados($_GET['p'],$_GET['t'],"tematica");
for($i = 0; $i < count($lista); $i++)
{
    echo $lista[$i]["Nombre"]."<br/>";
}

echo "<br/><br/>";

for ($i = 1; $i <= $npag; $i++)
{
    if($i == $_GET['p'])
    {
        echo "<strong>";
    }
    echo "<a href=?p=".$i."&t=5>".$i."</a>&nbsp;&nbsp;";
    if($i == $_GET['p'])
    {
        echo "</strong>";
    }
}

?>