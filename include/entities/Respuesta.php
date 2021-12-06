<?php

class Respuesta {
    protected $id;
    protected $enunciado;
    protected $pregunta;

    function __construct(int $i, string $e, Pregunta $p)
    {
        $this->id = $i;
        $this->enunciado = $e;
        $this->pregunta = $p;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getEnunciado()
    {
        return $this->enunciado;
    }

    public function getPregunta()
    {
        return $this->pregunta;
    }

    public function setID($i)
    {
        $this->id = $i;
    }

    public function setEnunciado($e)
    {
        $this->enunciado = $e;
    }

    public function setPregunta($p)
    {
        $this->pregunta = $p;
    }
}