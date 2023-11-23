<?php

require_once 'view/View.php';
require_once 'model/Musician.php';

final class Controller
{
    public $view;
    public $musicianStorage;

    function __construct(View $view, MusicianStorage $musicianStorage)
    {
        $this->view = $view;
        $this->musicianStorage = $musicianStorage;

    }

    public function showInformation($id)
    {
        $musicien = $this->musicianStorage->read($id);
        if ($musicien != null) {
            $this->view->prepareMusicianPage($musicien);
        } else {
            $this->view->prepareUnknownMusicianPage();
        }
    }

    public function showList()
    {
        $this->view->prepareListPage($this->musicianStorage->readAll());
    }

    public function newMusician($errors)
    {
        $this->view->prepareMusicainCreationPage($errors);
    }

    public function saveNewMusician(array $data)
    {
        var_dump($data);
        if (!empty($data)) {
            $errors = array(
                'name' => '',
                'instrument' => '',
                'age' => ''
        );
            $_SESSION['form'] = $data;
            $name = !empty($data['name']) ? $this->view->htmlesc($data['name']) : null;
            $instrument = !empty($data['instrument']) ?  $this->view->htmlesc($data['instrument']) : null;
            $verify_age = !empty($data['age']) && is_numeric($data['age']) && !($data['age'] <=0);

            $age =  $verify_age ? $this->view->htmlesc($data['age']) : null;
            if(is_null($age)){$errors['age'] = 'age is not correct';}

            $all_ok = !is_null($age);
            var_dump($name, $instrument, $age, $all_ok, $errors);
            
            if ($all_ok) {
                unset($_SESSION['form']);
                $id = $this->musicianStorage->create(new Musician($name, $instrument, $age));
                $this->showInformation($id);
            } else {
                $this->view->prepareMusicainCreationPage($errors);
            }
        }
        else{
            $this->view->prepareSomthingWentWrongPage();
        }



    }

}

?>