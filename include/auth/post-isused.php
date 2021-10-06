<?php
require_once __DIR__ . "/../../database/models/UserDB.php";

$users = new UserDB();

if(isset($_POST['checkPseudo'])) {
    $pseudo = $users->fetchOne("pseudo", $_POST['checkPseudo']);
    if($pseudo){
        $returnResponsePseudo['responsePseudo'] = true;
    } else {
        $returnResponsePseudo['responsePseudo'] = false;
    }
    echo json_encode($returnResponsePseudo);
}

if(isset($_POST['checkEmail'])) {
    $email = $users->fetchOne("email", $_POST['checkEmail']);
    if($email){
        $returnResponseEmail['responseEmail'] = true;
    } else {
        $returnResponseEmail['responseEmail'] = false;
    }
    echo json_encode($returnResponseEmail);
}
