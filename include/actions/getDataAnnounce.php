<?php
require_once __DIR__ . "/../../database/models/AnnounceDB.php";
$announceDB = new AnnounceDB();

if (isset($_POST['announceID'])) {
    $announce = $announceDB->editArticle($_POST['announceID']);
    echo json_encode( $announce);
}