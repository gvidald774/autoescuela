<?php

/**
 * Clase Validator.
 * Contiene un array con los errores del formulario validado.
 */
class Validator {
    
    private $errores;

    /**
     * Constructor de la clase Validator.
     * Crea un array de errores.
     */
    public function __construct()
    {
        $this->errores=array();
    }

    /**
     * Comprueba si un campo está rellenado.
     * Útil para campos que deben estar rellenados.
     */
    public function existe($campo)
    {
        $respuesta = false;
        if(isset($_POST[$campo]) && $_POST[$campo] != "")
        {
            $respuesta = true;
        }
        else
        {
            $this->errores[$campo] = "No puede estar vacío";
        }
        return $respuesta;
    }

    /**
     * Comprueba si el contenido de dos campos es igual.
     * Útil para campos cuyo contenido debe coincidir.
     */
    public function coincidencia($campo1,$campo2)
    {
        $respuesta = true;
        if($_POST[$campo1] != $_POST[$campo2])
        {
            $this->errores[$campo1] = $campo1." y ".$campo2." no coinciden";
            $respuesta = false;
        }
        return $respuesta;
    }

    /**
     * Comprueba si el contenido de un campo es un correo válido.
     */
    public function email($campo)
    {
        $respuesta = false;
        if(!filter_var($_POST[$campo],FILTER_VALIDATE_EMAIL))
        {
            $this->errores[$campo] = "Debe ser un email válido";
        }
        else
        {
            $respuesta = true;
        }
        return $respuesta;
    }

    /**
     * Comprueba si el formulario no tiene ningún error.
     * Esto da verdadero si el array de errores está vacío.
     */
    public function correcto()
    {
        $respuesta = false;
        if(count($this->errores)==0)
        {
            $respuesta = true;
        }
        return $respuesta;
    }

    /**
     * Imprime los errores.
     */
    public function imprimeError($campo)
    {
        Pintor::imprimeError($this->errores[$campo], $campo);
    }

}