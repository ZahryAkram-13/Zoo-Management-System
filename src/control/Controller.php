<?php

require_once 'view/View.php';
require_once 'model/Animal.php';
require_once 'model/AnimalStorage.php';
require_once 'model/AnimalBuilder.php';
require_once 'view/ViewJson.php';

final class Controller
{
    public $view;
    public $animalStorage;

    public function setView($view){
        $this->view = $view;
    }

    function __construct(View $view, AnimalStorage $animalStorage)
    {
        $this->view = $view;
        $this->animalStorage = $animalStorage;

    }

    /**
     * une fonction qui affiche les infos dun animal
     * @param string $id 
     */
    public function showInformation($id)
    {
        $animal = $this->animalStorage->read($id);
        if ($animal != null) {
            $this->view->prepareAnimalPage($animal);
        } else {
            $this->view->prepareUnknownAnimalPage();
        }
    }
    /**
     * une fonction qui affiche les animaux.
     */
    public function showList()
    {
        $this->view->prepareListPage($this->animalStorage->readAll());
    }

    /**
     * une fonction qui renvoie un formulaire pour creation dun animal.
     * @param AnimalBuilder $builder
     */
    public function newAnimal(AnimalBuilder $builder)
    {
        $this->view->prepareAnimalCreationPage($builder);
    }

    /**
     * une fonction qui va sauvgarder un animal cree si les infos sont valides
     * @param array $data,les champs du form
     * @param array $errors les erreurs
     */
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
    /**
     * une fonction qui renvoie un formulaire pour modification dun animal.
     * @param string $id
     */
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
        } else {
            $this->view->prepareUnknownAnimalPage();
        }

    }
    /**
     * une fonction qui va sauvgarder un animal modifie si les infos sont valides
     * @param array $data,les champs du form
     * @param string $id 
     */
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
    /**
     * une fonction qui renvoie un formulaire pour supression dun animal (un button).
     * @param string $id les erreurs
     */
    public function deleteAnimal($id)
    {
        $animal = $this->animalStorage->read($id);
        if (!is_null($animal)) {
            $this->view->prepareAnimalDeletePage($animal, $id);
        } else {
            $this->view->prepareUnknownAnimalPage();
        }

    }
    /**
     * une fonction qui va suprimer un animal et renvoie un feedback du supression
     * et redirige vers la page des animaux. 
     * @param string $id 
     */
    public function deleteConfirmed($id)
    {
        $deleted = $this->animalStorage->delete($id);
        if ($deleted) {
            $this->view->displayAnimalDeletedSuccess();
            return;
        }
        $this->view->couldNotDeletePage();

    }

    public function showJSON($id){
        if($this->view instanceof ViewJSON){
            $animal = $this->animalStorage->read($id);
        if (!is_null($animal)) {
            ViewJSON::renderJSON($animal);
            exit;
        }
        ViewJSON::inkownJSON();
        exit;
        }
        else{
            $this->view->prepareSomethingWentWrongPage();
        }
        
        
    }



}

?>