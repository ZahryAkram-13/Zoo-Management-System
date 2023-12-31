<?php
require_once 'view/View.php';
require_once 'control/Controller.php';
require_once 'model/AnimalStorageStub.php';
final class Router
{
    const BASE_URL = '/exoMVCR/site.php';
    function getAnimalURL($id)
    {
        return  Router::BASE_URL . "/" . $id;
    }

    function getAnimalsURL()
    {
        return Router::BASE_URL . '?animals';
    }

    function getHomeURL()
    {
        return Router::BASE_URL;
    }

    function getAnimalCreationURL()
    {
        return Router::BASE_URL . "?action=newAnimal";
    }
    function getAnimalSaveURL()
    {
        return Router::BASE_URL . "?action=saveAnimal";
    }

    function getAnimalUpdateURL($id)
    {
        return Router::BASE_URL . "?action=updateAnimal&id=" . $id;
    }
    function getUpdatedURL($id)
    {
        return Router::BASE_URL . "?action=updated&id=" . $id;
    }

    function getAnimalDeleteURL($id)
    {
        return Router::BASE_URL . "?action=deleteAnimal&id=" . $id;
    }

    function getDeleteConfirmURL($id)
    {
        return Router::BASE_URL . "?action=deleteConfirmed&id=" . $id;
    }

    // function getJsonURL($id)
    // {
    //     return "?action=json&id=" . $id;
    // }




    /**
     * une fonction qui redirige vers un url donne avec un message feedback et un flag
     * pour preciser la nature du message. 
     * @param string $message 
     * @param string $url 
     * @param string $flag 
     */
    function POSTredirect($url, $message, $flag)
    {
        ob_start();
        $_SESSION['feedback']['message'] = $message;
        $_SESSION['feedback']['flag'] = $flag;
        header("HTTP/1.1 303 See Other");
        header("Location:$url");
        ob_end_flush();
        exit();
    }




    function main(AnimalStorage $animalStorage)
    {
        $view = new View($this, '', '');
        $controller = new Controller($view, $animalStorage);

        $pathInfo = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : '';

        try {

            // if (key_exists('id', $_GET)) {
            //     $id = View::htmlesc($_GET['id']);
            //     $controller->showInformation($id);

            // } else 
            if (key_exists('animals', $_GET)) {
                $controller->showList();

            } else if (empty($pathInfo)) {
                $controller->view->preparePageAcueil();
            } else {
                $id = View::htmlesc(basename($pathInfo));
                $controller->showInformation($id);

            }

            if (key_exists("action", $_GET)) {
                $action = View::htmlesc($_GET['action']);
                switch ($action) {
                    case 'newAnimal':
                        $controller->newAnimal(new AnimalBuilder($_POST, array()));
                        break;
                    case 'updated':
                        if (key_exists('id', $_GET)) {
                            $id = key_exists('id', $_GET) ? View::htmlesc($_GET['id']) : -13;
                            $controller->saveUpdate($_POST, $id);
                        }
                        break;
                    case 'updateAnimal':
                        if (key_exists("id", $_POST) || key_exists('id', $_GET)) {
                            $id = key_exists('id', $_GET) ? View::htmlesc($_GET['id']) : -13;
                            $controller->updateAnimal($id);
                        }
                        break;
                    case 'saveAnimal':
                        $controller->saveNewAnimal($_POST, array());
                        break;

                    case 'deleteAnimal':
                        $id = key_exists('id', $_GET) ? View::htmlesc($_GET['id']) : -13;
                        $controller->deleteAnimal($id);
                        break;

                    case 'deleteConfirmed':
                        $id = key_exists('id', $_GET) ? View::htmlesc($_GET['id']) : -13;
                        $controller->deleteConfirmed($id);
                        break;
                    case 'json':
                        $id = key_exists('id', $_GET) ? View::htmlesc($_GET['id']) : -13;
                        $controller->setView(new ViewJSON());
                        $controller->showJSON($id);

                        break;

                    default:
                        $controller->view->prepareSomethingWentWrongPage();
                        break;
                }

            }



        } catch (\Throwable $th) {
            $controller->view->prepareSomethingWentWrongPage();
        }

        $controller->view->render();


    }
}


?>
