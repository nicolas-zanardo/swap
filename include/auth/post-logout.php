<?php
require_once __DIR__ . "/../init.php";

if(isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['user']);
    add_flash('Vous vous êtes déconnecté', 'success');
    $tab["logout"] = true;
} else {
    $tab["logout"] = false;
}

echo json_encode($tab);