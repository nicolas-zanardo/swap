<?php
require_once __DIR__ . "/../../database/models/UserDB.php";

$users = new UserDB();

if(isset($_POST['checkPseudo'])) {
    $pseudo = $users->fetchOne("pseudo", $_POST['checkPseudo']);
    if($pseudo){
        $returnResponsePseudo = 'true';
    } else {
        $returnResponsePseudo = 'false';
    }
    echo $returnResponsePseudo;
}

if(isset($_POST['checkEmail'])) {
    $email = $users->fetchOne("email", $_POST['checkEmail']);
    if($email){
        $returnResponseEmail = 'true';
    } else {
        $returnResponseEmail = 'false';
    }
    echo $returnResponseEmail;
}
