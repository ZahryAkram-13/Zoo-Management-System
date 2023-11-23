<?php
require_once 'view/View.php';
require_once 'control/Controller.php';
require_once 'model/MusicianStorageStub.php';
final class Router
{

    function getMusicianURL($id)
    {
        return '?id=' . $id;
    }

    function getMusiciansURL()
    {
        return '?musicians';
    }

    function getHomeURL()
    {
        return "";
    }

    function getMusicianCreationURL()
    {
        return "?action=newMusician";
    }
    function getMusicianSaveURL()
    {
        return "?action=saveMusician";
    }




    function main(MusicianStorage $musicianStorage)
    {
        $view = new View($this, '', '');
        $controller = new Controller($view, $musicianStorage);

        $pathInfo = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : '';
        //var_dump($pathInfo);

        try {
            //var_dump($_GET);
            //var_dump($_SESSION);

            if (key_exists('id', $_GET)) {
                $id = htmlspecialchars($_GET['id']);
                $controller->showInformation($id);

            } else if (key_exists('musicians', $_GET)) {
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
                    case 'newMusician':
                        $controller->view->prepareMusicainCreationPage(null);
                        break;
                    case 'saveMusician':
                        $controller->saveNewMusician($_POST);
                        break;

                    default:
                        $controller->view->prepareDebugPage($_GET);
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