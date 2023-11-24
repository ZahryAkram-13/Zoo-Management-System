<?php

require_once 'view/View.php';
require_once 'model/Animal.php';
require_once 'model/AnimalStorage.php';
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

    public function newAnimal(AnimalBuilder $builder)
    {
        $this->view->prepareAnimalCreationPage($builder);
    }

    public function saveNewAnimal(array $data, array $errors)
    {
        $builder = new AnimalBuilder($data, $errors);
        $animal = $builder->createAnimal();
        if (!is_null($animal)) {
            $id = $this->animalStorage->create($animal);
            $this->view->displayAnimalCreationSuccess($id);
        } else {
            $this->newAnimal($builder);
        }


    }

}

?>