<?php

class Examen {
    protected $id;
    protected $descripcion;
    protected $duracion;
    protected $nPreguntas;
    protected $preguntas;

    function __construct(int $i, string $de, int $du, int $n, $p)
    {
        $this->id = $i;
        $this->descripcion = $de;
        $this->duracion = $du;
        $this->nPreguntas = $n;
        $this->preguntas = $p;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getDuracion()
    {
        return $this->duracion;
    }

    public function getNPreguntas()
    {
        return $this->nPreguntas;
    }

    public function getPreguntas()
    {
        return $this->preguntas;
    }

    public function setID($i)
    {
        $this->id = $i;
    }

    public function setDescripcion($d)
    {
        $this->descripcion = $d;
    }

    public function setDuracion($d)
    {
        $this->duracion = $d;
    }

    public function setNPreguntas($n)
    {
        $this->duracion = $n;
    }

    public function setPreguntas($p)
    {
        $this->preguntas = $p;
    }

}