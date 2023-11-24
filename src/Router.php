<?php
require_once 'view/View.php';
require_once 'control/Controller.php';
require_once 'model/AnimalStorageStub.php';
final class Router
{

    function getAnimalURL($id)
    {
        return '?id=' . $id;
    }

    function getAnimalsURL()
    {
        return '?animals';
    }

    function getHomeURL()
    {
        return "";
    }

    function getAnimalCreationURL()
    {
        return "?action=newAnimal";
    }
    function getAnimalSaveURL()
    {
        return "?action=saveAnimal";
    }




    function main(AnimalStorage $animalStorage)
    {
        $view = new View($this, '', '');
        $controller = new Controller($view, $animalStorage);

        $pathInfo = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : '';
        //var_dump($pathInfo);

        try {
            //var_dump($_GET);
            //var_dump($_SESSION);

            if (key_exists('id', $_GET)) {
                $id = htmlspecialchars($_GET['id']);
                $controller->showInformation($id);

            } else if (key_exists('animals', $_GET)) {
                $controller->showList();

            } else if (empty($pathInfo)) {

                $controller->view->preparePageAcueil();
            } else {
                $id = basename($pathInfo);
                $controller->showInformation($id);

            }

            if (key_exists("action", $_GET)) {
                $action = $_GET['action'];
                //echo $action;
                switch ($action) {
                    case 'newAnimal':
                        $controller->view->prepareAnimalCreationPage(null);
                        break;
                    case 'saveAnimal':
                        $controller->saveNewAnimal($_POST);
                        break;

                    default:
                        $controller->view->prepareDebugPage($_POST);
                        break;
                }

            }



        } catch (\Throwable $th) {
            echo "unexpected error " . $th;
        }

        $controller->view->render();


    }
}


?>