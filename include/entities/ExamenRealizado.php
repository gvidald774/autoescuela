<?php

class ExamenRealizado {
    protected $id;
    protected $examen;
    protected $alumno;
    protected $fecha;
    protected $calificacion;

    function __construct(int $i, Examen $e, Alumno $a, Date $f, int $c)
    {
        $this->id = $i;
        $this->examen = $e;
        $this->alumno = $a;
        $this->fecha = $f;
        $this->calificacion = $c;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getExamen()
    {
        return $this->examen;
    }

    public function getAlumno()
    {
        return $this->alumno;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function getCalificacion()
    {
        return $this->calificacion;
    }

    public function setID($i)
    {
        $this->id = $i;
    }

    public function setExamen($e)
    {
        $this->examen = $e;
    }

    public function setAlumno($a)
    {
        $this->alumno = $a;
    }

    public function setFecha($f)
    {
        $this->alumno = $f;
    }

    public function setCalificacion($c)
    {
        $this->calificacion = $c;
    }
}