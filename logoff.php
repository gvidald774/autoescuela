<?php
    require_once("include/helpers/Sesion.php");
    Sesion::iniciar();
    
    Sesion::eliminar("usuario");

    Sesion::cerrar();

    header("Location: login.php");