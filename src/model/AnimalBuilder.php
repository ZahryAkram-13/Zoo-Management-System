<?php


final class AnimalBuilder{
    const NAME_REF = 'name';
    const ESPECE_REF = 'espece';
    const AGE_REF = 'age';

    private $data;
    private $errors;
    private $animal;
    

    function getData(){return $this->data;}
    function getErrors(){return $this->errors;}
    function getAnimal(){return $this->animal;}
    

    function __construct($data, $errors = null){
        $this->data = $data;
        $this->errors = $errors;
    }

    function isValid($data){
        if(!empty($data)){
            $this->errors = array(
                AnimalBuilder::NAME_REF => null,
                AnimalBuilder::ESPECE_REF => null,
                AnimalBuilder::AGE_REF => null,
            );
            $name = $data[AnimalBuilder::NAME_REF];
            if(!$this->isValidName($name)){
                $this->errors[AnimalBuilder::NAME_REF] = 'Invalid name, should be alphanumeric';
                return false;
            }
            $espece = $data[AnimalBuilder::ESPECE_REF];
            if(!$this->isValidEspece($espece)){
                $this->errors[AnimalBuilder::ESPECE_REF] = 'Invalid espece, should be alphanumeric';
                return null;
            }
            $age = $data[AnimalBuilder::AGE_REF];
            if(!$this->isValidAge($age)){
                $this->errors[AnimalBuilder::AGE_REF] = 'Invalid age, it meant to be reel age';
                return false;
            }
           
            return true;
        }
        return false;

    }

    public function updateAnimal(){
        return $this->createAnimal();
    }

    public function createAnimal(){
        $data = $this->data;
        $name = $data[AnimalBuilder::NAME_REF];
        $espece = $data[AnimalBuilder::ESPECE_REF];
        $age = $data[AnimalBuilder::AGE_REF];

        if($this->isValid($data)){
            $this->animal = new Animal($name, $espece, $age);
            return $this->animal;
        }
        return null;
    }

    private function isNotEmptyAndAlphanumeric($input){
        return !empty($input) && preg_match('/^[a-zA-Z0-9\s]+$/', $input);
    }

    private function isValidName($name){
        return strlen($name) >= 2 && $this->isNotEmptyAndAlphanumeric($name);
    } 

    private function isValidEspece($espece){
        return strlen($espece) >= 2 && $this->isNotEmptyAndAlphanumeric($espece);
    } 
    private function isValidAge($age){
        return $this->isNotEmptyAndAlphanumeric($age) && is_numeric($age) >= 2;

    } 





}

?>