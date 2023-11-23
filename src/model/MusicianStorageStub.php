<?php
require_once 'model/MusicianStorage.php';

final class MusicianStorageStub implements MusicianStorage{
    private $musicianTab;

    function read($id){
        return key_exists($id, $this->musicianTab) ? $this->musicianTab[$id] : null;
    }
    function readAll(){
        return $this->musicianTab;
    }

    function create(Musician $musician){
        throw new Exception("create not implimented", 1);
    }
    function delete($id){
        throw new Exception("delete not implimented", 1);
    }
    function update($id, Musician $musician){
        throw new Exception("update not implimented", 1);
    }


    
    function __construct(){
        $this->musicianTab = array(
            'vivaldi' => new Musician('vivaldi', 'violon', 66),
            'beethoven' => new Musician('beethoven', 'piano', 10),
            'chopin' => new Musician('chopin', 'piano', 52000),
        );
    }

    function getMusicianTab(){
        return $this->musicianTab;
    }
}

?>