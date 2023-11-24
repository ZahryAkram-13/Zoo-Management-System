<?php


final class MusicianBuilder{
    private $data;
    private $errors;
    

    function getData(){return $this->data;}
    function getErrors(){return $this->errors;}
    

    function __construct($data, $errors){
        $this->data = $data;
        $this->errors = $errors;
    }

    public function createMusician(){
        $data = $this->data;
        if(!empty($data)){

            $name = $data['name'];
            if(!$this->isValidName($name)){
                $this->errors;
            }
            $instrument = $data['$instrument'];
            $age = $data['age'];
            return new Musician($name, $instrument, $age);
        }
        return null;
    }

    private function isNotEmptyAndAlphanumerc($input){
        return !empty($input) && preg_match('/^[a-zA-Z0-9]+$/', $input);
    }

    private function isValidName($name){
        return strlen($name) >= 2 && $this->isNotEmptyAndAlphanumerc($name);
    } 

    private function isValidInstrument($instrument){
        return strlen($instrument) >= 2 && $this->isNotEmptyAndAlphanumerc($instrument);
    } 
    private function isValidAge($age){
        return $this->isNotEmptyAndAlphanumerc($age) && is_numeric($age) >= 2;

    } 





}

?>