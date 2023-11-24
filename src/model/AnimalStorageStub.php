<?php
require_once 'model/AnimalStorage.php';

final class AnimalStorageStub implements AnimalStorage{
    private $musicianTab;

    function read($id){
        return key_exists($id, $this->musicianTab) ? $this->musicianTab[$id] : null;
    }
    function readAll(){
        return $this->musicianTab;
    }

    function create(Animal $musician){
        throw new Exception("create not implimented", 1);
    }
    function delete($id){
        throw new Exception("delete not implimented", 1);
    }
    function update($id, Animal $musician){
        throw new Exception("update not implimented", 1);
    }


    
    function __construct(){
        $this->musicianTab = array(
            'vivaldi' => new Animal('vivaldi', 'violon', 66),
            'beethoven' => new Animal('beethoven', 'piano', 10),
            'chopin' => new Animal('chopin', 'piano', 52000),
        );
    }

    function getAnimalTab(){
        return $this->musicianTab;
    }
}

?>