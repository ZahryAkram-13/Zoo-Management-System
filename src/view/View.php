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
            " est un Animal trés fameux, il jou sur  " .
            $Animal->getEspece() . "il a " .
            $Animal->getAge();
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
    function prepareAnimalCreationPage($errors)
    {
        //var_dump($errors);
        $form_name = '';
        $form_espece = '';
        $form_age = '';
        if (key_exists('form', $_SESSION)) {
            $form = $_SESSION['form'];
            $form_name = $form['name'];
            $form_espece = $form['espece'];
            $form_age = $form['age'];
        }
        $message = '';
        if (!is_null($errors)) {
            foreach ($errors as $key => $err) {
                $message .= '<p>' . $err . '</p>';

            }
        }


        $this->title = 'Add Animal';
        $this->content = <<<HTML
                <article class="message">
                <div class="message-header">
                <p>{$message}</p> 
                </div>
                </article>  
                

                <form class="box" action={$this->router->getAnimalSaveURL()} method="POST">
                <label class="label" for="name">Name</label>
                <div class="control">
                    <input name="name" class="input" type="text" value="{$form_name}" placeholder="name" required>
                </div>
                <label class="label" for="espece">Espece</label>
                <div class="control">
                    <input name="espece" class="input" type="text" value="{$form_espece}"placeholder="espece" required>
                </div>
                <label for="age" class="label">Age</label>
                <div class="control">
                    <input name="age" class="input" type="number" value="{$form_age}" placeholder="age" required>
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