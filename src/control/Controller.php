<?php

require_once 'view/View.php';
require_once 'model/Musician.php';

final class Controller{
    public $view;
    public $musicianStorage;

    function __construct(View $view, MusicianStorage $musicianStorage){
        $this->view = $view;
        $this->musicianStorage = $musicianStorage;
        
    }

    public function showInformation($id) {
        $musicien = $this->musicianStorage->read($id);
        if($musicien != null ) {
            $this->view->prepareMusicianPage($musicien); 
        }
        else{
            $this->view->prepareUnknownMusicianPage();
        }       
    }

    public function showList(){
        $this->view->prepareListPage($this->musicianStorage->readAll());
    }

    public function newMusician(){
        $this->view->prepareMusicainCreationPage();
    }

    public function saveNewMusician(array $data){
        $name = $data['name'];
        $instrument = $data['instrument'];
        $age = $data['age'];
        $id = $this->musicianStorage->create(new Musician($name, $instrument, $age));
        if ($id != null) {
            $this->showInformation($id);
        }
        else{
            $this->view->prepareDebugPage($id);
        }
        
        
    }
    
}

?>