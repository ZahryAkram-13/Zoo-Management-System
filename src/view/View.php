<?php
final class View
{

    public $title;
    public $content;
    public $router;
    public $menu;

    public function __construct($router, $title = '', $content = '')
    {
        $this->title = $title;
        $this->content = $content;
        $this->router = $router;
        $this->menu = array(
            'Home' => $this->router->getHomeURL(),
            'Animals' => $this->router->getAnimalsURL(),
            'newAnimal' => $this->router->getAnimalCreationURL(),
        );
    }

    /* Une fonction pour échapper les caractères spéciaux de HTML,
     * car celle de PHP nécessite trop d'options. */
    public static function htmlesc($str)
    {
        return htmlspecialchars(
            $str,
            /* on échappe guillemets _et_ apostrophes : */
            ENT_QUOTES
            /* les séquences UTF-8 invalides sont
             * remplacées par le caractère �
             * au lieu de renvoyer la chaîne vide…) */
            | ENT_SUBSTITUTE
            /* on utilise les entités HTML5 (en particulier &apos;) */
            | ENT_HTML5,
            'UTF-8'
        );
    }



    function prepareTestPage()
    {
        $this->content = 'kurru';
        $this->title = 'kurru';
    }

    function prepareAnimalPage(Animal $Animal)
    {
        $this->title = 'Page sur ' . $Animal->getName();
        $this->content = $Animal->getName() .
            " est un Animal trés adorable,\n il est de l'espece " .
            $Animal->getEspece() . " et il a " .
            $Animal->getAge() . ' ans ou peut etre jours qui sais !!';
    }

    function prepareUnknownAnimalPage()
    {
        $this->title = '404';
        $this->content = 'Animal Inconnu';
    }

    function preparePageAcueil()
    {
        $this->title = 'Acueil';
        $this->content = '';
    }

    function prepareListPage($Animals)
    {
        $this->title = 'all Animals';
        $this->content = '';
        foreach ($Animals as $key => $Animal) {
            $dist = $this->router->getAnimalURL($key);
            $this->content .= '<a href="' .
                $dist .
                '" target="_blank" rel="noopener noreferrer"> ' .
                $Animal->getName() . '</a><br>';

        }
        $this->content;

    }

    public function prepareDebugPage($variable)
    {
        $this->title = 'Debug';
        $this->content = '<pre>' . htmlspecialchars(var_export($variable, true)) . '</pre>';
    }
    function prepareAnimalCreationPage(AnimalBuilder $builder)
    {
        $errors = $builder->getErrors();
        $data = $builder->getData();
        //var_dump($errors);

        $name = key_exists(AnimalBuilder::NAME_REF, $data) ? $data[AnimalBuilder::NAME_REF] : '';
        $espece = key_exists(AnimalBuilder::ESPECE_REF, $data) ? $data[AnimalBuilder::ESPECE_REF] : '';
        $age = key_exists(AnimalBuilder::AGE_REF, $data) ? $data[AnimalBuilder::AGE_REF] : '';

        $name_err = key_exists(AnimalBuilder::NAME_REF, $errors) ? $errors[AnimalBuilder::NAME_REF] : '';
        $espece_err = key_exists(AnimalBuilder::ESPECE_REF, $errors) ? $errors[AnimalBuilder::ESPECE_REF] : '';
        $age_err = key_exists(AnimalBuilder::AGE_REF, $errors) ? $errors[AnimalBuilder::AGE_REF] : '';



        $this->title = 'Add Animal';
        $this->content = <<<HTML
              
            
                <form class="box" action={$this->router->getAnimalSaveURL()} method="POST">
                <label class="label" for="name">Name</label>
                <div class="control">
                    <input name="name" class="input" type="text" value="{$name}" placeholder="name" required>
                    <p class="help is-danger">
                    {$name_err}

                    </p>
                </div>
                <label class="label" for="espece">Espece</label>
                <div class="control">
                    <input name="espece" class="input" type="text" value="{$espece}"placeholder="espece" required>
                    <p class="help is-danger">
                    {$espece_err}

                    </p>
                </div>
                <label for="age" class="label">Age</label>
                <div class="control">
                    <input name="age" class="input" type="number" value="{$age}" placeholder="age" required>
                    <p class="help is-danger">
                    {$age_err}

                    </p>
                </div>

                <input class="button is-primary" type="submit" value="Submit">
                </form>
                       
        HTML;

    }

    public function prepareSomthingWentWrongPage()
    {
        $this->title = 'Somthing Went Wrong';
        $this->content = 'Please try later';
    }

    function render()
    {
        include 'templateBulma.html';
        // echo '<!DOCTYPE html>
        //      <html lang="en">
        //     <head>
        //         <meta charset="UTF-8">
        //         <meta name="viewport" content="width=device-width, initial-scale=1.0">
        //         <title>' . $this->title . '</title>
        //     </head>
        //     <body>' .
        //         '<h1>' . $this->title . '</h1>
        //         <p>' . $this->content . '</p>

        //     </body>
        //     </html>';
    }
}

?>