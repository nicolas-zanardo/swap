<?php
require_once __DIR__ . "/../../database/models/UserDB.php";
require_once __DIR__ . "/../init.php";

$users = new UserDB();



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // INIT ERROR
    $errors = 0;
    // Sanitize
    $_POST = filter_input_array(INPUT_POST, [
        'signin-email' => FILTER_SANITIZE_EMAIL,
        'signin-password' => FILTER_SANITIZE_STRING,
        'isAnnounce' => FILTER_SANITIZE_STRING
    ]);

    if (empty($_POST['signin-email'])) {
        $errors++;
    } else {
        $pattern = '#^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,4}$#';
        if (!preg_match($pattern, $_POST['signin-email'])) {
            $errors++;
        }
    }

    if (empty($_POST['signin-password'])) {
        $errors++;
    }


    $returnResponseAuth = [];
    if ($errors === 0) {
        $user = $users->fetchOne('email', $_POST['signin-email']);
        if($user && password_verify($_POST['signin-password'], $user['mdp'])) {
            $_SESSION['user'] = $user;
            if($_POST['isAnnounce'] === "false") {
                add_flash('Connexion réussie', 'success');
                $returnResponseAuth['isAnnounce'] = false;
            } else {
                $returnResponseAuth['isAnnounce'] = true;
            }
            $returnResponseAuth['responseAuth'] = true;
        } else {
            $returnResponseAuth['responseAuth'] = false;
        }
    } else {
        $returnResponseAuth['responseAuth'] = 'ERROR';
    }

    echo json_encode($returnResponseAuth);

}
