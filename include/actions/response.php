<?php

require_once __DIR__ ."/../../database/models/ResponseDB.php";

$responseDB = new ResponseDB();

if(!empty($_POST['questionID'])) {
    $response = $responseDB->findOneByID($_POST['questionID']);
    echo json_encode($response['reponse']);
}
