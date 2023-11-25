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

    public function updateAnimal($id)
    {
        $animal = $this->animalStorage->read($id);
        if (!is_null($animal)) {
            $data = array();
            $data[AnimalBuilder::NAME_REF] = $animal->getName();
            $data[AnimalBuilder::ESPECE_REF] = $animal->getEspece();
            $data[AnimalBuilder::AGE_REF] = $animal->getAge();

            $builder = new AnimalBuilder($data, array());
            $this->view->prepareAnimalUpdatePage($builder, $id);
        }
        else{
            $this->view->prepareUnknownAnimalPage();
        }

    }

    public function saveUpdate($data, $id)
    {
        $builder = new AnimalBuilder($data, array());
        $animal = $builder->updateAnimal();
        if (!is_null($animal)) {
            $updated = $this->animalStorage->update($id, $animal);
            if ($updated) {
                $this->view->displayAnimalUpdatedSuccess($id);
                return;
            }
        }
        $this->saveNewAnimal($data, array());
    }

    public function deleteAnimal($id)
    {
        $animal = $this->animalStorage->read($id);
        if (!is_null($animal)) {
            $this->view->prepareAnimalDeletePage($animal, $id);
        }
        else {
            $this->view->prepareUnknownAnimalPage();
        }
        
    }

    public function deleteConfirmed($id)
    {
        $deleted = $this->animalStorage->delete($id);
        if ($deleted) {
            $this->view->displayAnimalDeletedSuccess();
            return;
        }
        $this->view->couldNotDeletePage();

    }



}

?>