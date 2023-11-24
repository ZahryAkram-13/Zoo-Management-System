<?php


final class Animal{
    private $name;
    private $espece;
    private $age;

    function getName(){return $this->name;}
    function getEspece(){return $this->espece;}
    function getAge(){return $this->age;}

    function __construct($name, $espece, $age){
        $this->name = $name;
        $this->espece = $espece;
        $this->age = $age;
    }
}

?>