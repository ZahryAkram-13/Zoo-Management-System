<?php

require_once 'view/View.php';
require_once 'model/Animal.php';
require_once 'model/AnimalBuilder.php';

final class Controller
{
    public $view;
    public $animalStorage;

    function __construct(View $view, AnimalStorage $animalStorage)
    {
        $this->view = $view;
        $this->animalStorage = $animalStorage;

    }

    public function showInformation($id)
    {
        $animal = $this->animalStorage->read($id);
        if ($animal != null) {
            $this->view->prepareAnimalPage($animal);
        } else {
            $this->view->prepareUnknownAnimalPage();
        }
    }

    public function showList()
    {
        $this->view->prepareListPage($this->animalStorage->readAll());
    }

    public function newAnimal($errors)
    {
        $this->view->prepareAnimalCreationPage($errors);
    }

    public function saveNewAnimal(array $data)
    {
        var_dump($data, $_SESSION);
        if (!empty($data)) {
            $errors = array(
                'name' => '',
                'espece' => '',
                'age' => ''
        );
            $_SESSION['form'] = $data;
            $name = !empty($data['name']) ? $this->view->htmlesc($data['name']) : null;
            $espece = !empty($data['espece']) ?  $this->view->htmlesc($data['espece']) : null;
            $verify_age = !empty($data['age']) && is_numeric($data['age']) && !($data['age'] <=0);

            $age =  $verify_age ? $this->view->htmlesc($data['age']) : null;
            if(is_null($age)){$errors['age'] = 'age is not correct';}

            $all_ok = !is_null($age);
            //var_dump($name, $espece, $age, $all_ok, $errors);
            
            if ($all_ok) {
                unset($_SESSION['form']);
                $id = $this->AnimalStorage->create(new Animal($name, $espece, $age));
                $this->showInformation($id);
            } else {
                $this->view->prepareAnimalCreationPage($errors);
                unset($_SESSION['form']);
            }
        }
        else{
            $this->view->prepareSomthingWentWrongPage();
        }



    }

}

?>