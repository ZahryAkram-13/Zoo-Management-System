<?php

require_once 'view/View.php';

final class Controller{
    public $view;

    public static $animalsTab = array(
        'medor' => array('Médor', 'chien'),
        'felix' => array('Félix', 'chat'),
        'denver' => array('Denver', 'dinosaure'),
    );


    function __construct($view){
        $this->view = $view;
    }

    public function showInformation($id) {
        if( key_exists($id, Controller::$animalsTab)){
            $infos = Controller::$animalsTab[$id];
            $this->view->prepareAnimalPage($infos[0], $infos[1]); 
            
        }
        else{
            $this->view->prepareUnknownAnimalPage();
        }
        $this->view->render();
        
    }
    
}

?>