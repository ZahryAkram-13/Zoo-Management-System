<?php

require_once 'view/View.php';
require_once 'model/Animal.php';

final class Controller{
    public $view;

    public $animalsTab;

    function __construct($view){
        $this->view = $view;
        $this->animalsTab = array(
            'medor' => new Animal('Médor', 'chien', 13),
            'felix' => new Animal('Félix', 'chat', 10),
            'denver' => new Animal('Denver', 'dinosaure', 52000),
        );
    
    }

    public function showInformation($id) {
        if( key_exists($id, $this->animalsTab)){
            $animal = $this->animalsTab[$id];
            $this->view->prepareAnimalPage($animal); 
            
        }
        else{
            $this->view->prepareUnknownAnimalPage();
        }
        $this->view->render();
        
    }
    
}

?>