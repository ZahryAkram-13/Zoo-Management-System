<?php
/*
 * On indique que les chemins des fichiers qu'on inclut
 * seront relatifs au répertoire src.
 */
set_include_path("./src");

/* Inclusion des classes utilisées dans ce fichier */
require_once "Router.php";
require_once 'model/AnimalStorageStub.php';
require_once 'model/AnimalStorageSession.php';

/*
 * Cette page est simplement le point d'arrivée de l'internaute
 * sur notre site. On se contente de créer un routeur
 * et de lancer son main.
 */

session_start();
//session_destroy();



//$animalStorage = new AnimalStorageStub();
$animalStorageSession = new AnimalStorageSession();
$router = new Router();
$router->main($animalStorageSession);

//echo "post";
//var_dump($_POST);
//echo "get";
//var_dump($_GET);
// var_dump($_SESSION);
?>
