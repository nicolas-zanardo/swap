<?php

require_once __DIR__ . "/../../database/models/QuestionDB.php";
require_once __DIR__ . "/../../database/models/UserDB.php";
require_once __DIR__ . "/../../database/models/AnnounceDB.php";
$questionDB = new QuestionDB();
$announce = new AnnounceDB();
$user = new UserDB();

if (isset($_POST['announceID'])) {

    $_POST = filter_input_array(INPUT_POST, [
        'member-id-question' => FILTER_SANITIZE_NUMBER_INT,
        'announce-id-question' => FILTER_SANITIZE_NUMBER_INT,
        'question' => FILTER_FLAG_NO_ENCODE_QUOTES,
        'announceID' => FILTER_FLAG_NO_ENCODE_QUOTES
    ]);


    $idMessage = $questionDB->createQuestion([
        'id_membre' => $_POST['member-id-question'],
        'id_annonce' => $_POST['announce-id-question'],
        'question' => $_POST['question']
    ]);

    if ($idMessage !== "") {
        $_POST['announceID'] = "true";
    }
    echo $_POST['announceID'];
}

if (!empty($_POST['questionAnnounceID'])) {
    $resp = $questionDB->fetchAllQuestionByIDAnnounce($_POST['questionAnnounceID']);
    echo json_encode($resp);
}