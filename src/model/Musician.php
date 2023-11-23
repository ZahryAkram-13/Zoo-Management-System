<?php


final class Musician{
    private $name;
    private $instrument;
    private $age;

    function getName(){return $this->name;}
    function getInstrument(){return $this->instrument;}
    function getAge(){return $this->age;}

    function __construct($name, $instrument, $age){
        $this->name = $name;
        $this->instrument = $instrument;
        $this->age = $age;
    }
}

?>