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
            'Musicians' => $this->router->getMusiciansURL(),
            'newMusician' => $this->router->getMusicianCreationURL(),
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

    function prepareMusicianPage(Musician $musician)
    {
        $this->title = 'Page sur ' . $musician->getName();
        $this->content = $musician->getName() .
            " est un musician trés fameux, il jou sur  " .
            $musician->getInstrument() . "il a " .
            $musician->getAge();
    }

    function prepareUnknownMusicianPage()
    {
        $this->title = '404';
        $this->content = 'Musician Inconnu';
    }

    function preparePageAcueil()
    {
        $this->title = 'Acueil';
        $this->content = '';
    }

    function prepareListPage($musicians)
    {
        $this->title = 'all Musicians';
        $this->content = '';
        foreach ($musicians as $key => $musician) {
            $dist = $this->router->getMusicianURL($key);
            $this->content .= '<a href="' .
                $dist .
                '" target="_blank" rel="noopener noreferrer"> ' .
                $musician->getName() . '</a><br>';

        }
        $this->content;

    }

    public function prepareDebugPage($variable)
    {
        $this->title = 'Debug';
        $this->content = '<pre>' . htmlspecialchars(var_export($variable, true)) . '</pre>';
    }
    function prepareMusicainCreationPage($errors)
    {
        var_dump($errors);
        $form_name = '';
        $form_instrument = '';
        $form_age = '';
        if (key_exists('form', $_SESSION)) {
            $form = $_SESSION['form'];
            $form_name = $form['name'];
            $form_instrument = $form['instrument'];
            $form_age = $form['age'];
        }
        $message = '';
        if (!is_null($errors)) {
            foreach ($errors as $key => $err) {
                $message .= '<p>' . $err . '</p>';

            }
        }


        $this->title = 'Add Musician';
        $this->content = <<<HTML
                <article class="message">
                <div class="message-header">
                <p>{$message}</p> 
                </div>
                </article>  
                

                <form class="box" action={$this->router->getMusicianSaveURL()} method="POST">
                <label class="label" for="name">Name</label>
                <div class="control">
                    <input name="name" class="input" type="text" value="{$form_name}" placeholder="name" required>
                </div>
                <label class="label" for="instrument">Instrument</label>
                <div class="control">
                    <input name="instrument" class="input" type="text" value="{$form_instrument}"placeholder="instrument" required>
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