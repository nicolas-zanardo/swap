<?php

require_once __DIR__ . "/../../database/models/NotesDB.php";

$noteBD = new NotesDB();

if (!empty($_POST['validate'])) {



    $_POST = filter_input_array(INPUT_POST, [
        'flexRadioDefault' => FILTER_SANITIZE_NUMBER_INT,
        'idMembre' => FILTER_SANITIZE_NUMBER_INT
    ]);



    $noteBD->createNote([
        'note' => $_POST['flexRadioDefault'],
        'id_membre' => $_POST['idMembre']
    ]);

    if ($noteBD !== "") {
        $_POST['validate'] = "true";

    }
    echo  $_POST['validate'];

}