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
        return "/mvcr/site.php";
    }

    function getAnimalCreationURL()
    {
        return "?action=newAnimal";
    }
    function getAnimalSaveURL()
    {
        return "?action=saveAnimal";
    }

    function getAnimalUpdateURL($id)
    {
        return "?action=updateAnimal&id=" . $id;
    }
    function getUpdatedURL($id)
    {
        return "?action=updated&id=" . $id;
    }

    function getAnimalDeleteURL($id)
    {
        return "?action=deleteAnimal&id=" . $id;
    }

    function getDeleteConfirmURL($id)
    {
        return "?action=deleteConfirmed&id=" . $id;
    }




    /**
     * une fonction qui redirige vers un url donne avec un message feedback et un flag
     * pour preciser la nature du message. 
     * @param string $message 
     * @param string $url 
     * @param string $url 
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

            if (key_exists('id', $_GET)) {
                $id = View::htmlesc($_GET['id']);
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
                $action = View::htmlesc($_GET['action']);
                //var_dump($_POST);
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
                            //$id = htmlspecialchars($_POST['id']);
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

                    default:
                        $controller->view->prepareSomethingWentWrongPage();
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