<?php
final class View{
    
    public $title = '';
    public $content = '';

    public function __construct($title = '', $content = ''){
        $this->title = $title;
        $this->content = $content;
    }

    function render(){
        echo '<!DOCTYPE html>
             <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>' . $this->title . '</title>
            </head>
            <body>
                <h1>' . $this->title . '</h1>
                <p>' . $this->content . '</p>
            
            </body>
            </html>';
    }

    function prepareTestPage(){
        $this->content = 'kurru';
        $this->title = 'kurru';
    }

    function prepareAnimalPage(Animal $animal){
        $this->title = 'Page sur ' . $animal->getName();
        $this->content =  $animal->getName() . 
        " est un animal de l'espèce " .
         $animal->getEspece() . "il a " . 
         $animal->getAge();
    }
    
    function prepareUnknownAnimalPage(){
        $this->title = '404';
        $this->content = 'Animal inconnu';
    }

    function preparePageAcueil(){
        $this->title = 'Animals';
        $this->content = 'Animals';
    }
}
 
?>

