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
            //'JSON' => $this->router->getJsonURL(),
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
    /**
     * une fonction qui retourne une maquette html representant une page contenant les informations d'un animal
     *
     * @param Animal $animal une instance d'un animal
     * @param string $id  id de l'animal
     * 
     */
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

    /**
     * une fonction qui prepare l'affichage des infos d'un animal.
     *
     * @param Animal $animal une instance d'un animal
     * 
     */
    function prepareAnimalPage(Animal $animal)
    {
        $id = key_exists('id', $_GET) ? View::htmlesc($_GET['id']) : '';

        $this->title = View::htmlesc($animal->getName());
        $this->content = $this->getAnimalPageTemplate($animal, $id);

    }
    /**
     * une fonction qui prepare l'affichage d'un animal inconnu.
     *
     */
    function prepareUnknownAnimalPage()
    {
        $this->title = '404';
        $this->content = 'Animal Inconnu';
    }

    /**
     * une fonction qui prepare l'affichage d'accueil.
     *
     */
    function preparePageAcueil()
    {
        $this->title = 'Accueil';
        $this->content = "welcome to the zooo, you' ll find here all kind of animals";
    }

    /**
     * une fonction qui retourne une maquette html representant un menu.
     */
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
    /**
     * une fonction qui retourne une maquette html representant un message de feedback.
     */
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
    /**
     * une fonction qui retourne une maquette html representant 2 button (update and delete)
     * selon id de l'animal.
     * @param string $id
     */
    function getUpdateDeleteTemplate($id)
    {
        $id = View::htmlesc($id); // deja fait dans le routeur mais cest tjr mieux d'etre mefiant 
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

    function getScriptTemplate()
    {
        $script = <<<HTML
       
        HTML;
        return $script;
    }

    /**
     * une fonction qui retourne une maquette html representant une liste des animaux 
     * @param array $animals une liste des animaux
     */
    function prepareListPage($animals)
    {
        $this->title = 'all Animals';
        $this->content = '';

        foreach ($animals as $key => $animal) {
            $dist = $this->router->getAnimalURL($key);
            $jsKey = json_encode($key);
            $name = View::htmlesc($animal->getName());
            $this->content .= <<<HTML
                            <div class="box">
                                <a class="is-size-2" href="{$dist}">{$name}</a> 
                                <p>
                                <button id="getDetails"  onclick='getDetails({$jsKey}, this)' class="button is-text">details</button>
                                <div class="notification is-link" id={$jsKey} style="display: none;">
                                </div>
                               
                                </p>
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

    /**
     * une fonction qui retourne une maquette html representant un formulaire soit remplis par des info soit vide
     * @param array $data les champs du formulaire
     * @param array $errors les erreurs qui puissent se produire
     * @param string $url action du formulaire
     */
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
    /**
     * une fonction qui retourne une maquette html representant un formulaire pour la supression d'un animal.
     * @param string $url action du formulaire
     */
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
    /**
     * une fonction qui prepare la page de la supression d'un animal
     * @param Animal $animal 
     * @param string $id id d'animal
     */
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
    /**
     * une fonction qui prepare la page de la modification d'un animal
     * @param AnimalBuilder $builder 
     * @param string $id id d'animal
     */
    public function prepareAnimalUpdatePage(AnimalBuilder $builder, $id)
    {
        $errors = $builder->getErrors();
        $data = $builder->getData();

        $updatedURL = $this->router->getUpdatedURL($id);
        $this->title = 'Update Animal';
        $this->content = $this->getFormTemplate($data, $errors, $updatedURL);

    }
    /**
     * une fonction qui prepare la page de la creation d'un animal
     * @param AnimalBuilder $builder 
     */
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

    /**
     * une fonction qui prepare un feedback creation et redirige vers la page d'animal
     * @param string $id id d'animal
     */
    function displayAnimalCreationSuccess($id)
    {
        $flag = 'is-success';
        $url = $this->router->getAnimalURL($id);
        $message = 'Animal is created successfully';
        $this->router->POSTredirect($url, $message, $flag);

    }
    /**
     * une fonction qui prepare un feedback modification et redirige vers la page d'animal
     * @param string $id id d'animal
     */
    function displayAnimalUpdatedSuccess($id)
    {
        $flag = 'is-warning';
        $url = $this->router->getAnimalURL($id);
        $message = 'Animal is Updated successfully';
        $this->router->POSTredirect($url, $message, $flag);

    }
    /**
     * une fonction qui prepare un feedback supression et redirige vers la page d'animaux
     */
    function displayAnimalDeletedSuccess()
    {
        $flag = 'is-danger';
        $url = $this->router->getAnimalsURL();
        $message = 'Animal is deleted successfully';
        $this->router->POSTredirect($url, $message, $flag);

    }

    /**
     * une fonction qui renvoie l'html 'templateBulma.html' apres avoir terminer la logic  
     */
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