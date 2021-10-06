<?php
require_once __DIR__ . "/../../database/models/UserDB.php";
require_once __DIR__ . "/../init.php";

$users = new UserDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // INIT ERROR
    $errors = 0;
    // Sanitize
    $_POST = filter_input_array(INPUT_POST, [
        'signup-pseudo' => FILTER_SANITIZE_STRING,
        'signup-phone' => FILTER_SANITIZE_STRING,
        'signup-email' => FILTER_SANITIZE_EMAIL,
        'signup-confirm-email' => FILTER_SANITIZE_EMAIL,
        'signup-password' => FILTER_SANITIZE_STRING,
        'signup-password-confirm' => FILTER_SANITIZE_STRING
    ]);

    if (empty($_POST['signup-pseudo'])) {
        $errors++;
    } elseif (mb_strlen($_POST['signup-pseudo']) < 5) {
        $errors++;
    } else {
        $pseudo = $users->fetchOne("pseudo", $_POST["signup-pseudo"]);
        if ($pseudo) {
            $errors++;
        }
    }

    if (empty($_POST['signup-phone'])) {
        $errors++;
    } else {
        $pattern = '#^((\+)?[0-9]{10,20})$#';
        if (!preg_match($pattern, $_POST['signup-phone'])) {
            $errors++;
        }
    }

    if (empty($_POST['signup-email'])) {
        $errors++;
    } else {
        $pattern = '#^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,4}$#';
        if (!preg_match($pattern, $_POST['signup-email'])) {
            $errors++;
        }
    }

    if (empty($_POST['signup-confirm-email'])) {
        $errors++;
    } else {
        if (!empty($_POST['signup-email']) && $_POST['signup-confirm-email'] !== $_POST['signup-email']) {
            $errors++;
        }
    }


    if (empty($_POST['signup-password'])) {
        $errors++;
    } else {
        $pattern = '#^((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)[a-zA-Z0-9_$ \W]{8,20})$#';
        if (!preg_match($pattern, $_POST['signup-password'])) {
            $errors++;
        }
    }

    if (empty($_POST['signup-password-confirm'])) {
        $errors++;
    } else {
        if (!empty($_POST['signup-password']) && $_POST['signup-password-confirm'] !== $_POST['signup-password']) {
            $errors++;
        }
    }

    $returnResponseSubmit = [];
    if ($errors === 0) {
        $users->createOne([
            'pseudo' => $_POST['signup-pseudo'],
            'mdp' => $_POST['signup-password'],
            'telephone' => $_POST['signup-phone'],
            'email' => $_POST['signup-email'],
        ]);

        $newUser = $users->fetchOne('email', $_POST['signup-email']);

        if (!file_exists(__DIR__ . '/../../public/images/annonces/' . $newUser['id_membre']) &&
            !mkdir($concurrentDirectory = __DIR__ . '/../../public/images/annonces/' . $newUser['id_membre']) &&
            !is_dir($concurrentDirectory))
        {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        $returnResponseSubmit['responseSubmit'] = true;
    } else {
        $returnResponseSubmit['responseSubmit'] = false;
    }

    echo json_encode($returnResponseSubmit);

}