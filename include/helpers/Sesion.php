<?php

/**
 * Clase Sesión.
 * Contiene todas las funciones necesarias para gestionar
 * la variable $_SESSION mediante abstracción.
 */
class Sesion {
    
    /**
     * Iniciar sesión.
     * Crea una sesión o se adjunta a una ya creada.
     */
    public static function iniciar()
    {
        if(!isset($_SESSION))
        {
            session_start();
        }
    }

    /**
     * Leer un dato.
     * Lee el valor dada la clave en la variable $_SESSION.
     * $dato: La clave de la cual queremos sacar el valor.
     */
    public static function leer($dato)
    {
        return(isset($_SESSION[$dato]))?$_SESSION[$dato]:null;
    }

    /**
     * Escribir un dato.
     * Escribe un valor en una clave, exista o no exista.
     * $clave: La clave donde queremos escribir el valor.
     * $valor: El valor que queremos escribir en la clave.
     */
    public static function escribir($clave, $valor)
    {
        $_SESSION[$clave] = $valor;
    }

    /**
     * Existencia de un dato.
     * Devuelve true si la clave ha sido creada y por lo tanto posee un valor, o false si no existe.
     * $dato: La clave de la cual queremos comprobar su existencia.
     */
    public static function existe($dato)
    {
        return (isset($_SESSION[$dato]))?true:false;
    }

    /**
     * Eliminar dato.
     * Borra un dato dado contenido en la variable $_SESSION.
     * $dato: El dato que queremos eliminar.
     */
    public static function eliminar($dato)
    {
        unset($_SESSION[$dato]);
    }

    /**
     * Ver contenido.
     * Función para depuración.
     * Imprime el contenido de la variable $_SESSION.
     */
    public static function verContenido()
    {
        var_dump($_SESSION);
    }

    /**
     * Cerrar sesión.
     * Cierra la sesión actual si existe.
     */
    public static function cerrar()
    {
        if(isset($_SESSION))
        {
            session_destroy();
        }
    }
}