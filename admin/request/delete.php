<?php
require_once __DIR__ . "/../../include/init.php";
require_once __DIR__ . "/../../database/models/UserDB.php";
require_once __DIR__ . "/../../database/models/AnnouncesCategoriesDB.php";
require_once __DIR__ . "/../../database/models/PhotoDB.php";
require_once __DIR__ . "/../../database/models/AnnounceDB.php";

$usersDB = new UserDB();
$photoDB = new PhotoDB();
$annouceDB = new AnnounceDB();


function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object))
                    rrmdir($dir . DIRECTORY_SEPARATOR . $object);
                else
                    unlink($dir . DIRECTORY_SEPARATOR . $object);
            }
        }
        rmdir($dir);
    }
}

if (isset($_POST['deleteUserById'])) {

    if ( $_SESSION['user']['id_membre'] !== $_POST['deleteUserById'] ) {

        $user = $usersDB->fetchOne('id_membre', $_POST['deleteUserById']);

        if ($user) {
            $usersDB->deleteOne($_POST['deleteUserById']);
            foreach ($annouceDB->findAllPhotoByIdUser($_POST['deleteUserById']) as $userPhoto) {
                $photoDB->deletePhoto($userPhoto['id_photo']);
            }
            rrmdir(__DIR__ . '/../../public/images/annonces/' . $user['id_membre']);
            add_flash("L'utilisateur " . $user['pseudo'] . " a été supprimé", "success");
            $infoDeleteAccountUser['deleteUserById'] = true;
        } else {
            add_flash('Utilisateur introuvable', 'danger');
            $infoDeleteAccountUser['deleteUserById'] = false;
        }

        echo json_encode($infoDeleteAccountUser, JSON_THROW_ON_ERROR);


    } else {


        add_flash("Vous ne pouvez pas supprimer votre compte", "danger");
    }


}


$announceCategoryDB = new AnnouncesCategoriesDB();
if (isset($_POST['deleteCategoryById']) && is_numeric($_POST['deleteCategoryById'])) {
    $category = $announceCategoryDB->fetchOne('id_categorie', $_POST['deleteCategoryById']);
    if ($category) {
        $announceCategoryDB->deleteOne($_POST['deleteCategoryById']);
        add_flash("La catégorie " . $category['titre'] . " a été supprimée", "success");
        $infoDeleteCategory['deleteCategoryById'] = true;
    } else {
        add_flash('Utilisateur introuvable', 'danger');
        $infoDeleteCategory['deleteCategoryById'] = false;
    }
    echo json_encode($infoDeleteCategory);
}
