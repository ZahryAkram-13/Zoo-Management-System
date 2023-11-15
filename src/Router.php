<?php
require 'view/View.php';
final class Router{
    function main(){
        $view = new View('', '');
        $view->prepareAnimalPage('medore', 'chien');
        $view->render();
    }
}


?>