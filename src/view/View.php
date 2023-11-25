<?php
final class View
{

    public $title;
    public $content;
    public $router;
    public $menu;
    public $feedback;

    public function __construct($router, $feedback, $title = '', $content = '')
    {
        $this->title = $title;
        $this->content = $content;
        $this->router = $router;
        $this->menu = array(
            'Home' => $this->router->getHomeURL(),
            'Animals' => $this->router->getAnimalsURL(),
            'newAnimal' => $this->router->getAnimalCreationURL(),
        );

        $this->feedback = $feedback;
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

    function getAnimalPageTemplate(Animal $animal, $id)
    {
        $template = <<<HTML
                        <div class="block">
                        <p class="subtitle is-5">
                            est un animal trés adorable ,c'est un <strong>{$animal->getEspece()}</strong> 
                            et il a <strong>{$animal->getAge()}</strong> ans.  <br> 
                            ces poils sont blancs tachés de noirs. Il a une jolie moustache qui lui cache la moitié de son visage à 
                            l'extrémité de ses pattes fines. <br>
                            il a des griffes pointues il les utilise pour se défendre, il est gentil et mignon il n'aime que jouer.
                        </p>
                
                        {$this->getUpdateDeleteTemplate($id)}  
                        </div>
        
        HTML;
        return $template;
    }

    function prepareAnimalPage(Animal $animal)
    {
        $id = key_exists('id', $_GET) ? View::htmlesc($_GET['id']) : '';

        $this->title = View::htmlesc($animal->getName());
        $this->content = $this->getAnimalPageTemplate($animal, $id);

    }

    function prepareUnknownAnimalPage()
    {
        $this->title = '404';
        $this->content = 'Animal Inconnu';
    }

    function preparePageAcueil()
    {
        $this->title = 'Accueil';
        $this->content = '';
    }

    function getMenuTemplate()
    {
        $menu = '<div id="navbarBasicExample" class="navbar-menu">
                  <div class="navbar-start">';
        foreach ($this->menu as $key => $url) {
            $menu .= <<<HTML
                        <a class="navbar-item" href="{$url}">{$key}</a>
                    HTML;
        }
        $menu .= '</div></div>';

        return $menu;
    }

    function getFeedbakMessage()
    {
        if (!is_null($_SESSION) && key_exists('feedback', $_SESSION)) {
            $feedback = $_SESSION['feedback'];
            $flag = key_exists('flag', $feedback) ? $feedback['flag'] : null;
            $message = key_exists('message', $feedback) ? $feedback['message'] : null;
            $template = <<<HTML
                        <article class="notification is-primary {$flag}">
                                <p>{$message}</p>
                        </article>
                        HTML;
        }

        return key_exists('feedback', $_SESSION) && !is_null($_SESSION) ? $template : '';
    }

    function getUpdateDeleteTemplate($id)
    {
        $updateURL = $this->router->getAnimalUpdateURL($id);
        $deleteURL = $this->router->getAnimalDeleteURL($id);
        $template = <<<HTML
                         <div class="buttons">
                            <form class="" action={$updateURL} method="post">
                            <input type="hidden" name="id" value="{$id}">
                            <button class="button is-warning m-1">Update</button>
                            </form>
                            <form class="" action={$deleteURL} method="post">
                            <input type="hidden" name="id" value="{$id}">
                            <button class="button is-danger m-1">Delete</button>
                            </form>
                        </div>    

                HTML;
        return $template;
    }


    function prepareListPage($animals)
    {
        $this->title = 'all Animals';
        $this->content = '';
        foreach ($animals as $key => $animal) {
            $dist = $this->router->getAnimalURL($key);
            $name = View::htmlesc($animal->getName());
            $this->content .= <<<HTML
                    <div class="box">
                        <a class="is-size-2" href="{$dist}">{$name}</a> 
                        {$this->getUpdateDeleteTemplate($key)}        
                    </div>
             HTML;

        }
        $this->content;

    }

    // public function prepareDebugPage($variable)
    // {
    //     $this->title = 'Debug';
    //     $this->content = '<pre>' . htmlspecialchars(var_export($variable, true)) . '</pre>';
    // }

    public function getFormTemplate($data, $errors, $url)
    {

        $name = key_exists(AnimalBuilder::NAME_REF, $data) ? $data[AnimalBuilder::NAME_REF] : '';
        $espece = key_exists(AnimalBuilder::ESPECE_REF, $data) ? $data[AnimalBuilder::ESPECE_REF] : '';
        $age = key_exists(AnimalBuilder::AGE_REF, $data) ? $data[AnimalBuilder::AGE_REF] : '';

        $name_err = key_exists(AnimalBuilder::NAME_REF, $errors) ? $errors[AnimalBuilder::NAME_REF] : '';
        $espece_err = key_exists(AnimalBuilder::ESPECE_REF, $errors) ? $errors[AnimalBuilder::ESPECE_REF] : '';
        $age_err = key_exists(AnimalBuilder::AGE_REF, $errors) ? $errors[AnimalBuilder::AGE_REF] : '';

        $form = <<<HTML
                <form class="box" action={$url} method="POST">
                <label class="label" for="name">Name</label>
                <div class="control">
                    <input name="name" class="input" type="text" value="{$name}" placeholder="name" required>
                    <p class="help is-danger">
                    {$name_err}
                    </p>
                </div>
                <label class="label" for="espece">Espece</label>
                <div class="control">
                    <input name="espece" class="input" type="text" value="{$espece}" placeholder="espece" required>
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

        return $form;


    }

    function getDeleteForm($url)
    {
        $template = <<<HTML
                    <form class="box" method='post' action='{$url}'>
                    <p class="subtitle is-4">
                        you gonna delete it for ever. <br>
                        are you sure you want to proceed this ? 
                    </p>
                    <input class="button is-danger" type="submit" value="Delete">
                    </form>
        HTML;
        return $template;
    }

    public function prepareAnimalDeletePage(Animal $animal, $id)
    {
        $url = $this->router->getDeleteConfirmURL($id);
        $name = View::htmlesc($animal->getName());
        $this->title = 'Delete Animal';
        $this->content = <<<HTML
                        <p class="subtitle is-4">
                        <strong> delete animal : {$name}</strong> 
                        </p>
                        {$this->getDeleteForm($url)}
        HTML;

    }

    public function prepareAnimalUpdatePage(AnimalBuilder $builder, $id)
    {
        $errors = $builder->getErrors();
        $data = $builder->getData();

        $updatedURL = $this->router->getUpdatedURL($id);
        $this->title = 'Update Animal';
        $this->content = $this->getFormTemplate($data, $errors, $updatedURL);

    }

    function prepareAnimalCreationPage(AnimalBuilder $builder)
    {
        $errors = $builder->getErrors();
        $data = $builder->getData();

        $saveAnimalURL = $this->router->getAnimalSaveURL();
        $this->title = 'Add Animal';
        $this->content = $this->getFormTemplate($data, $errors, $saveAnimalURL);

    }

    public function prepareSomethingWentWrongPage()
    {
        $this->title = 'Ooops Something Went Wrong';
        $this->content = 'Please try later';
    }

    public function couldNotDeletePage()
    {
        $this->title = 'Ooops we could not delete';
        $this->content = 'Please try later';
    }

    function displayAnimalCreationSuccess($id)
    {
        $flag = 'is-success';
        $url = $this->router->getAnimalURL($id);
        $message = 'Animal is created successfully';
        $this->router->POSTredirect($url, $message, $flag);

    }
    function displayAnimalUpdatedSuccess($id)
    {
        $flag = 'is-warning';
        $url = $this->router->getAnimalURL($id);
        $message = 'Animal is Updated successfully';
        $this->router->POSTredirect($url, $message, $flag);

    }
    function displayAnimalDeletedSuccess()
    {
        $flag = 'is-danger';
        $url = $this->router->getAnimalsURL();
        $message = 'Animal is deleted successfully';
        $this->router->POSTredirect($url, $message, $flag);

    }

    function render()
    {

        if (is_null($this->title) || is_null($this->content)) {
            $this->prepareSomethingWentWrongPage();
        } else {
            //include 'template.html';
            include 'templateBulma.html';

        }



    }
}

?>