<?php
    require_once("../cargadores/carga_entities.php");
    require_once("../cargadores/carga_helpers.php");

BD::conectar();

class Login {
    // Por añadir: cookies de recuérdame.
    public static function identificar(string $usuario, string $pass)
    {
        $respuesta = false;
        if(self::existeUsuario($usuario, $pass))
        {
            Sesion::iniciar();
            Sesion::escribir("usuario",$usuario);
            $respuesta = true;
        }
        return $respuesta;
    }

    public static function existeUsuario($usuario, $pass)
    {
        return BD::login($usuario, $pass);
    }

    public static function getRol($usuario)
    {
        return BD::getRol();
    }
}