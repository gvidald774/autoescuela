<?php

class Pregunta implements JSONSerializable {
    protected $id;
    protected $enunciado;
    protected $respuestaCorrecta;
    protected $recurso;
    protected $tematica;

    function __construct(int $i, string $e, string $t, string $re = null)
    {
        $this->id = $i;
        $this->enunciado = $e;
        $this->recurso = $re;
        $this->tematica = $t;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getEnunciado()
    {
        return $this->enunciado;
    }

    public function getRespuestaCorrecta()
    {
        return $this->respuestaCorrecta;
    }

    public function getRecurso()
    {
        return $this->recurso;
    }

    public function getTematica()
    {
        return $this->tematica;
    }

    public function setID($i)
    {
        $this->id = $i;
    }

    public function setEnunciado($e)
    {
        $this->enunciado = $e;
    }

    public function setRespuestaCorrecta($r)
    {
        $this->respuestaCorrecta = $r;
    }

    public function setRecurso($r)
    {
        $this->recurso = $r;
    }

    public function setTematica($t)
    {
        $this->tematica = $t;
    }

    public function jsonSerialize()
    {
        return
        [
            'id' => $this->id,
            'enunciado' => $this->enunciado,
            'respuestaCorrecta' => $this->respuestaCorrecta->getID(),
            'recurso' => $this->recurso,
            'tematica' => $this->tematica
        ];
    }
}