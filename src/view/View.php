<?php
final class View{
    
    public $title;
    public $content;
    public $router;
    public $menu;

    public function __construct($router, $title = '', $content = ''){
        $this->title = $title;
        $this->content = $content;
        $this->router = $router;
        $this->menu = array(
            'Home' => $this->router->getHomeURL(),
            'Musicians' => $this->router->getMusiciansURL(),
            'newMusician' => $this->router->getMusicianCreationURL(),
        );
    }

    

    function prepareTestPage(){
        $this->content = 'kurru';
        $this->title = 'kurru';
    }

    function prepareMusicianPage(Musician $musician){
        $this->title = 'Page sur ' . $musician->getName();
        $this->content =  $musician->getName() . 
        " est un musician trés famoux, il jou  " .
         $musician->getInstrument() . "il a " . 
         $musician->getAge();
    }
    
    function prepareUnknownMusicianPage(){
        $this->title = '404';
        $this->content = 'Musician Inconnu';
    }

    function preparePageAcueil(){
        $this->title = 'Acueil';
        $this->content = '';
    }

    function prepareListPage($musicians){
        $this->title = 'all Musicians';
        $this->content = '';
        foreach ($musicians as $key => $musician) {
            $dist = $this->router->getMusicianURL($key);
            $this->content .= '<a href="'. 
            $dist .
             '" target="_blank" rel="noopener noreferrer"> ' . 
            $musician->getName() . '</a><br>';
            
        }
        $this->content;
        
    }

    public function prepareDebugPage($variable) {
        $this->title = 'Debug';
        $this->content = '<pre>'.htmlspecialchars(var_export($variable, true)).'</pre>';
    }
    function prepareMusicainCreationPage(){
        $this->title = 'Add Musician';
        $this->content = 'form';
   
    }

    function render(){
        include 'template.html';
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
