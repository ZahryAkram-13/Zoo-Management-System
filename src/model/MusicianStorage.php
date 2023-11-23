<?php


interface MusicianStorage{

    function read($id);
    function readAll();

    function create(Musician $musician);
    function delete($id);
    function update($id, Musician $musician);
}

?>