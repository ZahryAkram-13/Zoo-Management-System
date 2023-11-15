<?php
require 'view/View.php';
require 'control/Controller.php';
final class Router{
    function main(){
        $view = new View('', '');
        $controller = new Controller($view);

        if ($_SERVER["REQUEST_METHOD"] == "GET"){
            if(key_exists('id', $_GET)){
                $id = htmlspecialchars($_GET['id']);
                $controller->showInformation($id);
            }
            else{
                $controller->view->preparePageAcueil();
                $controller->view->render();
            
            }
        }
       

    }
}


?>