<?php
final class ViewJSON
{
    public static function renderJSON($animal){
        header('Content-Type: application/json');
        $nom = View::htmlesc($animal->getName());
        $espece = View::htmlesc($animal->getEspece());
        $age = View::htmlesc($animal->getAge());

        echo json_encode([
            'nom' => $nom,
            'espece' => $espece,
            'age' => $age
        ]);
    }

    public static function inkownJSON(){
        header('Content-Type: application/json');
        echo json_encode([
            'unkown' => 'unkown',
        ]);
    }
}
?>