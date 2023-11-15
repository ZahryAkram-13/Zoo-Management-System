<?php
require 'view/View.php';
require 'control/Controller.php';
final class Router{
    function main(){
        $view = new View('', '');
        $controller = new Controller($view);

        $controller->showInformation('felix');
    }
}


?>