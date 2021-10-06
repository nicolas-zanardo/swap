<?php
require_once __DIR__ . "/../../include/init.php";
require_once __DIR__ . "/../../database/models/AnnouncesCategoriesDB.php";


$announceCategoryDB = new AnnouncesCategoriesDB();
if (isset($_POST['inputTitle'], $_POST['inputKeyWord'], $_POST['id_categorie']) &&
    is_numeric($_POST['id_categorie'])) {
    $infoEditCategory['responseEditCategory'] = true;

    $announceCategoryDB->updateOne([
        'titre' => $_POST['inputTitle'],
        'motscles' => $_POST['inputKeyWord'],
        'id_categorie' => $_POST['id_categorie']
    ]);
} else {
    $infoEditCategory['responseEditCategory'] = false;
}

echo json_encode($infoEditCategory);