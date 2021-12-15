<?php

class Usuario {
    protected $id;
    protected $email;
    protected $nombre;
    protected $apellidos;
    protected $pass;
    protected $fechaNacimiento;
    protected $rol;
    protected $foto;
    protected $localidad;

    function __construct($i,$e,$n,$a,$p,$fn,$r,$l,$fo = "")
    {
        $this->id = $i;
        $this->email = $e;
        $this->nombre = $n;
        $this->apellidos = $a;
        $this->pass = $p;
        $this->fechaNacimiento = $fn;
        $this->rol = $r;
        $this->localidad = $l;
        $this->foto = $fo;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    public function getRol()
    {
        return $this->rol;
    }

    public function getFoto()
    {
        return $this->foto;
    }

    public function getLocalidad()
    {
        return $this->localidad;
    }

    public function setID($i)
    {
        $this->id = $i;
    }

    public function setEmail($e)
    {
        $this->email = $e;
    }

    public function setNombre($n)
    {
        $this->nombre = $n;
    }

    public function setApellidos($a)
    {
        $this->apellidos = $a;
    }

    public function setPass($p)
    {
        $this->pass = $p;
    }

    public function setFechaNacimiento($f)
    {
        $this->fechaNacimiento = $f;
    }

    public function setRol($r)
    {
        $this->rol = $r;
    }

    public function setFoto($f)
    {
        $this->foto = $f;
    }

    public function setLocalidad($l)
    {
        $this->localidad = $l;
    }
}