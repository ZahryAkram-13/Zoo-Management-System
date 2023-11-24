<?php


interface AnimalStorage{

    function read($id);
    function readAll();

    function create(Animal $animal);
    function delete($id);
    function update($id, Animal $animal);
}

?>