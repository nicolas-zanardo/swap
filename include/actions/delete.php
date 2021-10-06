<?php
require_once __DIR__ . "/../init.php";
require_once __DIR__ . "/../../database/models/UserDB.php";
require_once __DIR__ . "/../../database/models/PhotoDB.php";
require_once __DIR__ . "/../../database/models/AnnounceDB.php";

$usersDB = new UserDB();
$photoDB = new PhotoDB();
$annouceDB = new AnnounceDB();

function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
                    rrmdir($dir. DIRECTORY_SEPARATOR .$object);
                else
                    unlink($dir. DIRECTORY_SEPARATOR .$object);
            }
        }
        rmdir($dir);
    }
}

if (isset($_POST['deleteUserBySESSION_ID']) && is_numeric($_POST['deleteUserBySESSION_ID'])) {
    $user = $usersDB->fetchOne('id_membre', $_POST['deleteUserBySESSION_ID']);
    if($user) {
        // DELETE PHOTO IN DATABASE
        foreach($annouceDB->findAllPhotoByIdUser($user['id_membre']) as $userPhoto) {
            $photoDB->deletePhoto($userPhoto['id_photo']);
        }
        // DELETE FILE PHOTO
        rrmdir(__DIR__ . '/../../public/images/annonces/'.$user['id_membre']);
        // DELETE ACCOUNT
        $usersDB->deleteOne($user['id_membre']);
        // DELETE SESSION
        unset($_SESSION['user']);
        add_flash("L'utilisateur ". $user['pseudo'] . " a été supprimé", "success");
        $infoDeleteAccount['delete-account'] = true;
    } else {
        add_flash('Utilisateur introuvable', 'danger');
        $infoDeleteAccount['delete-account'] = false;
    }
    echo json_encode($infoDeleteAccount);
}



if (isset($_POST['deleteAnnounce']) && is_numeric($_POST['deleteAnnounce'])) {
    $announce = $annouceDB->findAnnounce('id_annonce', $_POST['deleteAnnounce']);

    $path = '/../../public/images/annonces/'. $announce['id_membre'];

    $scan = scandir(__DIR__ . $path);
    $regex = '/^' . $_POST['deleteAnnounce'] . '-/';
    $listAllFile = [];
    foreach ($scan as $file) {
        $isMatches = preg_match($regex,$file);
        if($isMatches) {
            $listAllFile[] = $file;
        }
    }

    foreach($listAllFile as $file) {
        echo $path. '/' . $file;
        if(file_exists(__DIR__ . $path. '/' . $file)) {
            unlink(__DIR__ .$path. '/' . $file);
        }
    }

    $photoDB->deletePhoto($announce['id_photo']);
}
