<?php
require_once __DIR__ . "/include/init.php";
if (!isConnected()) {
    header('Location:/');
    exit();
}


require_once __DIR__ . "/database/models/AnnouncesCategoriesDB.php";
require_once __DIR__ . "/database/models/PhotoDB.php";
require_once __DIR__ . "/database/models/AnnounceDB.php";
// Import Class
$announceCategory = new AnnouncesCategoriesDB();
$announces = new AnnounceDB();
$announcePhoto = new PhotoDB();



/**
 * #########################
 * Edit Article
 * RETURN $editAnnounce is AUTHENTICATED IS FULLY
 */
if (isset($_GET['action']) && $_GET['action'] == 'edit' && !empty($_GET['id']) && is_numeric($_GET['id'])) {
    $announce = $announces->editArticle($_GET['id']);
    if ($announce && ($announce['id_membre'] === $_SESSION['user']['id_membre'])) {
        $editAnnounce = $announce;
    } else {
        add_flash("Vous ne pouvez pas modifier un article dans vous n'êtes pas l'auteur", 'danger');
        header('location:' . $_SERVER['PHP_SELF']);
    }
}

if (!empty($_POST)) {

    $errors = 0;

    $_POST = filter_input_array(INPUT_POST, [
        'name-announce-titre' => FILTER_FLAG_NO_ENCODE_QUOTES,
        'name-announce-description_courte' => FILTER_FLAG_NO_ENCODE_QUOTES,
        'name-announce-description_longue' => FILTER_FLAG_NO_ENCODE_QUOTES,
        'name-announce-prix' => FILTER_FLAG_ALLOW_FRACTION,
        'announce-id_categorie' => FILTER_SANITIZE_STRING,
        'announce-address' => FILTER_FLAG_NO_ENCODE_QUOTES,
        'announce-city' => FILTER_FLAG_NO_ENCODE_QUOTES,
        'announce-pays' => FILTER_FLAG_NO_ENCODE_QUOTES,
        'announce-cp' => FILTER_FLAG_NO_ENCODE_QUOTES,
        'announce-latlng_log' => FILTER_FLAG_ALLOW_FRACTION,
        'announce-latlng_lat' => FILTER_FLAG_ALLOW_FRACTION,

        'nom-img-1' => FILTER_SANITIZE_URL,
        'nom-img-2' => FILTER_SANITIZE_URL,
        'nom-img-3' => FILTER_SANITIZE_URL,
        'nom-img-4' => FILTER_SANITIZE_URL,
        'nom-img-5' => FILTER_SANITIZE_URL,

        'data-img-1' => FILTER_SANITIZE_STRING,
        'data-img-2' => FILTER_SANITIZE_STRING,
        'data-img-3' => FILTER_SANITIZE_STRING,
        'data-img-4' => FILTER_SANITIZE_STRING,
        'data-img-5' => FILTER_SANITIZE_STRING,

    ]);

    $arrayImgFile = [
        $_FILES['photo_1'],
        $_FILES['photo_2'],
        $_FILES['photo_3'],
        $_FILES['photo_4'],
        $_FILES['photo_5']
    ];

    $arrayImgPOST = [
        $_POST['nom-img-1'],
        $_POST['nom-img-2'],
        $_POST['nom-img-3'],
        $_POST['nom-img-4'],
        $_POST['nom-img-5']
    ];

    // Transforme price in decimal
    $_POST['name-announce-prix'] = bcdiv($_POST['name-announce-prix'], 1, 2);


    // Title
    if (empty($_POST["name-announce-titre"])) {
        $errors++;
        add_flash('Merci de choisir un titre', 'danger');
    } else if (mb_strlen($_POST["name-announce-titre"]) > 100 || mb_strlen($_POST["name-announce-titre"]) < 10) {
        $errors++;
        add_flash("Le titre doit être compris entre 10 et 100 lettres", 'warning');
    }

    // description_courte
    if (empty($_POST["name-announce-description_courte"])) {
        $errors++;
        add_flash('Une descripton courte est obligatoire', 'danger');
    } else if (mb_strlen($_POST["name-announce-description_courte"]) > 250 || mb_strlen($_POST["name-announce-description_courte"]) < 50) {
        $errors++;
        add_flash("La  description courte doit être compris entre 50 et 250 lettres", 'warning');
    }

    //description_longue
    if (empty($_POST["name-announce-description_longue"])) {
        $errors++;
        add_flash('La description longue est obligatoire', 'danger');
    } else if (mb_strlen($_POST["name-announce-description_longue"]) > 1000 || mb_strlen($_POST["name-announce-description_longue"]) < 100) {
        $errors++;
        add_flash("La description longue doit être compris entre 100 et 1000 lettres", 'warning');
    }

    //Prix
    if (empty($_POST["name-announce-prix"])) {
        $errors++;
        add_flash('Merci de choisir un prix', 'danger');
    } else if (mb_strlen($_POST["name-announce-prix"]) > 11) {
        $errors++;
        add_flash("Le prix doit être de 11 chiffre maximum", 'warning');
    }

    //Annonce
    if (empty($_POST["announce-id_categorie"])) {
        $errors++;
        add_flash('Merci de choisir une catégorie', 'danger');
    }
    //Photo-principal
    if (empty($_FILES['photo_1']['name']) && empty($_POST['nom-img-1'])) {
        $errors++;
        add_flash('Merci de choisir une photo', 'danger');
    }
    $ext_auto = ['image/jpeg', 'image/png'];
    if (!empty($_FILES['photo_1']['name']) && (!in_array($_FILES['photo_1']['type'], $ext_auto, true) || $_FILES['photo_1']['size'] > 250000)) {
        $errors++;
        add_flash('Image Principal Format autorisé: JPEG ou PNG dont la taille ne dépasse pas les 250kb', 'danger');
    }
    if (!empty($_FILES['photo_2']['name']) && !in_array($_FILES['photo_2']['type'], $ext_auto, true) && ($_FILES['photo_2']['size'] > 250000)) {
        $errors++;
        add_flash('Pour la photo 2 Format autorisé: JPEG ou PNG dont la taille ne dépasse pas les 250kb', 'danger');
    }
    if (!empty($_FILES['photo_3']['name']) && !in_array($_FILES['photo_3']['type'], $ext_auto, true) && ($_FILES['photo_3']['size'] > 250000)) {
        $errors++;
        add_flash('Pour la photo 3 Format autorisé: JPEG ou PNG dont la taille ne dépasse pas les 250kb', 'danger');
    }
    if (!empty($_FILES['photo_4']['name']) && !in_array($_FILES['photo_4']['type'], $ext_auto, true) && ($_FILES['photo_4']['size'] > 250000)) {
        $errors++;
        add_flash('Pour la photo 4 Format autorisé: JPEG ou PNG dont la taille ne dépasse pas les 250kb', 'danger');
    }
    if (!empty($_FILES['photo_5']['name']) && !in_array($_FILES['photo_5']['type'], $ext_auto, true) && ($_FILES['photo_5']['size'] > 250000)) {
        $errors++;
        add_flash('Pour la photo 5 Format autorisé: JPEG ou PNG dont la taille ne dépasse pas les 250kb', 'danger');
    }

    //Adresse
    if (empty($_POST['announce-address'])) {
        $errors++;
        add_flash('Merci de choisir une adresse', 'danger');
    }

    if ($errors === 0) {

        // path image $USER
        $path = $_SERVER['DOCUMENT_ROOT'] . '/public/images/annonces/' . $_SESSION['user']['id_membre'];

        if (isset($_GET['action']) && ($_GET['action'] == 'edit') && !empty($editAnnounce)) {

            // Update announcement and retrieved lastInsertID with $announceID
            $announces->updateAnnounce(
                [
                    'titre' => $_POST['name-announce-titre'],
                    'description_courte' => $_POST["name-announce-description_courte"],
                    'description_longue' => $_POST['name-announce-description_longue'],
                    'prix' => $_POST['name-announce-prix'],
                    'photo' => $_POST['nom-img-1'],
                    'adresse' => $_POST['announce-address'],
                    'pays' => $_POST['announce-pays'],
                    'ville' => $_POST['announce-city'],
                    'cp' => $_POST['announce-cp'],
                    'latlng_log' => $_POST['announce-latlng_log'],
                    'latlng_lat' => $_POST['announce-latlng_lat'],
                    'id_categorie' => $_POST['announce-id_categorie'],
                    'id_annonce' => $editAnnounce['id_annonce']

                ]
            );

            $announcePhoto->updateOne([
                "photo1" => $_POST['nom-img-1'],
                "photo2" => $_POST['nom-img-2'],
                "photo3" => $_POST['nom-img-3'],
                "photo4" => $_POST['nom-img-4'],
                "photo5" => $_POST['nom-img-5'],
            ], $editAnnounce['id_photo']);

            // Update File in POST image
            $incFile = 1;
            foreach ($arrayImgFile as $img) {
                $namePhotoFile = "photo$incFile";
                if ((file_exists("$path/$editAnnounce[id_annonce]-$incFile-$editAnnounce[$namePhotoFile]")) && ($img['tmp_name'] !== "")) {
                    unlink("$path/$editAnnounce[id_annonce]-$incFile-$editAnnounce[$namePhotoFile]");
                }
                if (!empty($img['name'])) {
                    move_uploaded_file($img['tmp_name'], "$path/$editAnnounce[id_annonce]-$incFile-$img[name]");
                }
                $incFile++;
            }
            // Update File in POST without image
            $incPOST = 1;
            foreach ($arrayImgPOST as $img) {
                $namePhotoPOST = "photo$incPOST";
                if (file_exists("$path/$editAnnounce[id_annonce]-$incPOST-$editAnnounce[$namePhotoPOST]") && ($editAnnounce[$namePhotoPOST] != $img)) {
                    unlink("$path/$editAnnounce[id_annonce]-$incPOST-$editAnnounce[$namePhotoPOST]");
                }
                $incPOST++;
            }


            add_flash("Votre annonces a bien été modifié", "success");
            header("location: /annonces.php?action=edit&id=$editAnnounce[id_annonce]");
            exit();
        } else {
            // Create a new announcement and retrieved lastInsertID with $announceID
            $announceID = $announces->createOne([
                'titre' => $_POST['name-announce-titre'],
                'description_courte' => $_POST["name-announce-description_courte"],
                'description_longue' => $_POST['name-announce-description_longue'],
                'prix' => $_POST['name-announce-prix'],
                'photo' => $_POST['nom-img-1'],
                'adresse' => $_POST['announce-address'],
                'pays' => $_POST['announce-pays'],
                'ville' => $_POST['announce-city'],
                'cp' => $_POST['announce-cp'],
                'latlng_log' => $_POST['announce-latlng_log'],
                'latlng_lat' => $_POST['announce-latlng_lat'],
                'id_categorie' => $_POST['announce-id_categorie'],
                'id_membre' => $_SESSION['user']['id_membre']
            ], [
                "photo1" => $_POST['nom-img-1'],
                "photo2" => $_POST['nom-img-2'],
                "photo3" => $_POST['nom-img-3'],
                "photo4" => $_POST['nom-img-4'],
                "photo5" => $_POST['nom-img-5'],
            ]);

            // iteration image files
            $iCreate = 1;
            foreach ($arrayImgFile as $img) {
                if (!empty($img['name'])) {
                    move_uploaded_file($img['tmp_name'], "$path/$announceID-$iCreate-$img[name]");
                    $iCreate++;
                }
            }

            add_flash("Votre annonces a bien été ajouté", "success");
            header('location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}


/**
 *  Init Variable
 */
$title = "Vos annonces";
$eltCSS = ["annouces.css", "datatable.css"];
$eltJS = ["auth.js", "script.js", "announces.js", "datatable.js"];

require_once __DIR__ . "/include/header.php";
require_once __DIR__ . "/include/component/navbar-public.php";
?>

<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    <li class="nav-item me-1" role="presentation">
        <button class="announces nav-link <?= (!empty($_GET['action']) || !empty($_POST)) ? '' : 'active show' ?>" id="pills-announces-tab" data-bs-toggle="pill" data-bs-target="#pills-announces" type="button" role="tab" aria-controls="pills-announces" aria-selected="true"><i class="fas fa-list me-1"></i>
            Vos annonces
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="announces nav-link <?= (!empty($_GET['action']) || !empty($_POST))  ? 'active show' : '' ?>" id="pills-add-announce-tab" data-bs-toggle="pill" <?php echo (isset($_GET['action']) && $_GET['action'] === 'edit') ? 'style="background-color: orange !important; color=white !important"' : "" ?> data-bs-target="#pills-add-announce" type="button" role="tab" aria-controls="pills-add-announce" aria-selected="false"><i class="fas fa-plus-square me-1"></i> <?php echo (isset($_GET['action']) && $_GET['action'] === 'edit') ? 'Mettre à jour ' : "Créer votre annonce" ?>
        </button>
    </li>
</ul>
<div class="tab-content" id="pills-tabContent">
    <!--
        ##################################
        # TAB ANNOUNCES
        -->
    <div class="tab-pane mb-5 fade <?= (!empty($_GET['action']) || !empty($_POST)) ? '' : 'active show' ?>" id="pills-announces" role="tabpanel" aria-labelledby="pills-announces-tab">

        <table class="table d-flex flex-column">
            <thead class="mb-2">
                <tr>
                    <th class="w-100">
                        <div class="m-1">Catégorie</div>
                    </th>
                    <th class="w-100">
                        <div class="m-1">Titre</div>
                    </th>
                </tr>
            </thead>
            <tbody class="d-flex flex-wrap justify-content-center">
                <?php foreach ($announces->findAllByUserID($_SESSION['user']['id_membre']) as $announce) : ?>
                    <tr class="card cardtab">
                        <td>
                            <h6 class="bg-secondary text-white p-1 rounded"> <?= $announce['categorie_titre'] ?></h6>
                            <div class="d-flex justify-content-center justify-content-md-between flex-wrap">
                                <img class="img-owncategory" src="<?= 'public/images/annonces/' . $announce['id_membre'] . '/' . $announce['id_annonce'] . '-' . '1' . '-' . $announce['photo'] ?>" alt="<?= $announce['categorie_titre'] ?>">
                                <div class="d-flex flex-md-column justify-content-center align-items-stretch">
                                    <a id="edit-annonce" href="?action=edit&id=<?php echo $announce['id_annonce'] ?>" class="btn btn-warning m-1"><i class="fas fa-edit text-light"></i></a>
                                    <button href="#deleteAnnounce<?= $announce['id_annonce'] ?>" data-bs-toggle="modal" role="button" class="btn btn-danger m-1 delete-announce"><i class="fas fa-trash text-light"></i></button>
                                    <a class="btn btn-secondary m-1" href="/?announce=<?= $announce['id_annonce'] ?>"><i class="fas fa-eye"></i></a>
                                </div>
                            </div>
                        </td>
                        <td class="border-bottom-0">
                            <div>
                                <h4><?= $announce['titre'] ?></h4>
                                <h6><?= $fmt->formatCurrency($announce['prix'], "EUR") ?></h6>
                            </div>
                            <hr>
                            <div class="w-100"><?= $announce['description_courte'] ?></div>
                        </td>
                    </tr>

                    <div class="modal fade" id="deleteAnnounce<?= $announce['id_annonce'] ?>" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="exampleModalToggleLabel">Suppresion d'article</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div>
                                        Voulez vous supprimer c'est article définitivement ?
                                    </div>

                                    <strong>
                                        <?= $announce['titre'] ?>
                                    </strong>
                                </div>
                                <div class="modal-footer ">
                                    <button type="button" class="btn btn-danger me-1" onclick="deleteAnnounce(<?= $announce['id_annonce'] ?>)">supprimer
                                    </button>
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Quitter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!--
        ##################################
        # ADD ANNOUNCE
        -->

    <div class="tab-pane fade mb-5 <?= (!empty($_GET['action']) || !empty($_POST)) ? 'active show' : '' ?>" id="pills-add-announce" role="tabpanel" aria-labelledby="pills-add-announce">
        <form id="formAnnounce" method="post" enctype="multipart/form-data">
            <div class="d-flex flex-wrap justify-content-between">
                <h1><i class="fas fa-plus-circle me-2"></i>Ajouter une annonce</h1>
                <?php if (!empty($_GET['action']) && !empty($_GET['id'])) : ?>
                    <a class="btn btn-secondary justify-content-center align-items-center mt-2" href="/?announce=<?= $_GET['id'] ?>"><i class="fas fa-eye fa-2x"></i></a>
                <?php endif; ?>
            </div>

            <hr>
            <div class="row">
                <div class="col-xl-6 col-12">
                    <div class="mb-3 group-value">
                        <label for="announce-titre" class="form-label">Titre</label>
                        <input name="name-announce-titre" type="text" class="form-control" id="announce-titre" placeholder="Ex: Maison de 120m² avec piscine proche des commerces" value="<?= $_POST['name-announce-titre'] ?? $editAnnounce['titre'] ?? '' ?>">
                        <div class="info text-danger announce-titre" data-error="errorTitre"></div>
                    </div>
                    <div class="mb-3 group-value">
                        <label for="announce-description_courte" class="form-label">Description courte</label>
                        <textarea name="name-announce-description_courte" class="form-control" id="announce-description_courte" rows="2" placeholder="Faites une description précise et courte de votre annonce"><?= $_POST['name-announce-description_courte'] ?? $editAnnounce['description_courte'] ?? '' ?></textarea>
                        <div class="info text-danger announce-description_courte" data-error="errorDescription_courte"></div>
                    </div>
                    <div class="mb-3 group-value">
                        <label for="announce-description_longue" class="form-label">Description longue</label>
                        <textarea name="name-announce-description_longue" class="form-control" id="announce-description_longue" rows="4" placeholder="Description détaillée de votre annonce"><?= $_POST['name-announce-description_longue'] ?? $editAnnounce['description_longue'] ?? '' ?></textarea>
                        <div class="info text-danger announce-description_longue" data-error="errorDescription_longue"></div>
                    </div>
                    <div class="mb-3 group-value">
                        <label for="announce-prix" class="form-label">Prix</label>
                        <div class="input-group">
                            <input name="name-announce-prix" type="number" class="form-control" id="announce-prix" value="<?= $_POST['name-announce-prix'] ?? $editAnnounce['prix'] ?? '' ?>">
                            <span class="input-group-text" id="phone"><i class="fas fa-euro-sign"></i></span>
                        </div>
                        <div class="info text-danger announce-prix" data-error="errorPrix"></div>
                    </div>
                    <div class="mb-3 group-value">
                        <label for="announce-id_categorie" class="form-label">Categorie</label>
                        <select name="announce-id_categorie" class="form-select" aria-label="Default select example">
                            <option disabled>-- Choisisez une categorie --</option>
                            <?php foreach ($announceCategory->fetchAll() as $category) : ?>
                                <option value="<?= $category['id_categorie'] ?>" <?php if (
                                                                                        (!empty($_POST['announce-id_categorie']) && $_POST['announce-id_categorie'] === $category['id_categorie']) ||
                                                                                        (!empty($editAnnounce['id_categorie']) && $editAnnounce['id_categorie'] === $category['id_categorie'])
                                                                                    ) {
                                                                                        echo 'selected';
                                                                                    } ?>>
                                    <?= $category['titre'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="info text-danger announce-category" data-error="errorCategory"></div>
                    </div>
                </div>
                <div class="col-xl-6 col-12">
                    <div class="row mb-3 justify-content-between py-2 border">
                        <div class="col-12 col-sm-6 d-flex align-items-center">
                            <div>
                                <h3>Photo principale:</h3>
                                <hr>
                                <span class="small">Cette photo sera affichée pour présenter votre annonce</span>
                            </div>
                        </div>
                        <div class="col-12 col-sm-5 photo-announce group-value required-img" data-img="img-1">
                            <div class="text-white text-center bg-warning m-0 p-0">principal</div>
                            <label for="photo_1" class="bg-white photo-display-principal p-0 m-0" <?php if (!empty($editAnnounce['photo1']) && ($editAnnounce['photo1'] !== "")) : ?> style='background: url(" <?= "public/images/annonces/" . $editAnnounce['id_membre'] . "/" . $editAnnounce['id_annonce'] . "-" . "1" . "-" . $editAnnounce['photo1'] ?> "); background-size: cover;' <?php elseif (!empty($_POST['data-img-1'])) : ?> style='background: url(" <?= $_POST['data-img-1'] ?> "); background-size: cover;' <?php endif; ?>>
                                <div class="h-100 d-flex align-items-center justify-content-center logo" data-img-logo="img-1">
                                    <i class="fas fa-camera fa-2x text-secondary"></i>
                                </div>
                            </label>
                            <input name="photo_1" id="photo_1" class="form-control " type="file" hidden accept="image/jpeg,image/png">
                            <input type="hidden" name="nom-img-1" id="nom_principal" class="input-img-name" value="<?= $_POST['nom-img-1'] ?? $editAnnounce['photo1'] ?? '' ?>">
                            <input type="hidden" name="data-img-1" class="input-img" value="<?= $_POST['data-img-1'] ?? '' ?>">
                            <div class="info text-danger announce-image" data-error="errorPhoto"></div>
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-around">
                        <div class="col-12 col-sm-6 col-md-3 justify-content-center photo-announce" data-img="img-2">
                            <div type="file" class="text-white text-center bg-secondary">photo 2</div>
                            <label for="photo_2" class="p-0 bg-white photo-display m-0 d-flex align-items-center justify-content-center" <?php if (!empty($editAnnounce['photo2']) && ($editAnnounce['photo2'] !== "")) : ?> style='background: url(" <?= "public/images/annonces/" . $editAnnounce['id_membre'] . "/" . $editAnnounce['id_annonce'] . "-" . "2" . "-" . $editAnnounce['photo2'] ?> "); background-size: cover;' <?php elseif (!empty($_POST['data-img-2'])) : ?> style='background: url(" <?= $_POST['data-img-2'] ?> "); background-size: cover;' <?php endif; ?>>
                                <div class="h-100 d-flex align-items-center justify-content-center logo" data-img-logo="img-2">
                                    <i class="fas fa-camera fa-2x text-secondary"></i>
                                </div>
                                <!--                                    // BTN DELETE-->
                                <button type="button" class="btn-delete-img d-none">Supprimer</button>
                            </label>
                            <input name="photo_2" id="photo_2" class="form-control" type="file" hidden accept="image/jpeg,image/png">
                            <input type="hidden" name="nom-img-2" id="nom-img-2" class="input-img-name" value="<?= $_POST['nom-img-2'] ?? $editAnnounce['photo2'] ?? '' ?>">
                            <input type="hidden" name="data-img-2" class="input-img" value="<?= $_POST['data-img-2'] ?? '' ?>">

                        </div>
                        <div class="col-12 col-sm-6 col-md-3 justify-content-center photo-announce" data-img="img-3">
                            <div class="text-white text-center bg-secondary">photo 3</div>
                            <label for="photo_3" class="p-0 bg-white photo-display m-0 d-flex align-items-center justify-content-center" <?php if (!empty($editAnnounce['photo3']) && ($editAnnounce['photo3'] !== "")) : ?> style='background: url(" <?= "public/images/annonces/" . $editAnnounce['id_membre'] . "/" . $editAnnounce['id_annonce'] . "-" . "3" . "-" . $editAnnounce['photo3'] ?> "); background-size: cover;' <?php elseif (!empty($_POST['data-img-3'])) : ?> style='background: url(" <?= $_POST['data-img-3'] ?> "); background-size: cover;' <?php endif; ?>>
                                <div class="h-100 d-flex align-items-center justify-content-center logo" data-img-logo="img-2">
                                    <i class="fas fa-camera fa-2x text-secondary"></i>
                                </div>
                                <!--                                    // BTN DELETE-->
                                <button type="button" class="btn-delete-img d-none">Supprimer</button>
                            </label>
                            <input name="photo_3" id="photo_3" class="form-control" type="file" accept="image/jpeg,image/png" hidden>
                            <input type="hidden" name="nom-img-3" id="nom-img-3" class="input-img-name" value="<?= $_POST['nom-img-3'] ?? $editAnnounce['photo3'] ?? '' ?>">
                            <input type="hidden" name="data-img-3" class="input-img" value="<?= $_POST['data-img-3'] ?? '' ?>">
                        </div>
                        <!--BRIDAGE AU NIVEAU DU SERVEUR MUTUALISE 3 photo seulement en meme temps-->
                        <div class="col-12 col-sm-6 col-md-3 justify-content-center photo-announce" data-img="img-4">
                            <div class="text-white text-center bg-secondary">photo 4</div>
                            <label for="photo_4" class="p-0 bg-white photo-display m-0 d-flex align-items-center justify-content-center" <?php if (!empty($editAnnounce['photo4']) && ($editAnnounce['photo4'] !== "")) : ?> style='background: url(" <?= "public/images/annonces/" . $editAnnounce['id_membre'] . "/" . $editAnnounce['id_annonce'] . "-" . "4" . "-" . $editAnnounce['photo4'] ?> "); background-size: cover;' <?php elseif (!empty($_POST['data-img-4'])) : ?> style='background: url(" <?= $_POST['data-img-4'] ?> "); background-size: cover;' <?php endif; ?>>
                                <div class="h-100 d-flex align-items-center justify-content-center logo" data-img-logo="img-4">
                                    <i class="fas fa-camera fa-2x text-secondary"></i>
                                </div>
                                <!--                                    // BTN DELETE-->
                                <button type="button" class="btn-delete-img d-none">Supprimer</button>
                            </label>
                            <input name="photo_4" id="photo_4" class="form-control" type="file" accept="image/jpeg,image/png" hidden>
                            <input type="hidden" name="nom-img-4" id="nom-img-4" class="input-img-name" value="<?= $_POST['nom-img-4'] ?? $editAnnounce['photo4'] ?? '' ?>">
                            <input type="hidden" name="data-img-4" class="input-img" value="<?= $_POST['data-img-4'] ?? '' ?>">
                        </div>

                        <div class="col-12 col-sm-6 col-md-3 justify-content-center photo-announce" data-img="img-5">
                            <div class="text-white text-center bg-secondary">photo 5</div>
                            <label for="photo_5" class="p-0 bg-white photo-display m-0 d-flex align-items-center justify-content-center" <?php if (!empty($editAnnounce['photo5']) && ($editAnnounce['photo5'] !== "")) : ?> style='background: url(" <?= "public/images/annonces/" . $editAnnounce['id_membre'] . "/" . $editAnnounce['id_annonce'] . "-" . "5" . "-" . $editAnnounce['photo5'] ?> "); background-size: cover;' <?php elseif (!empty($_POST['data-img-5'])) : ?> style='background: url(" <?= $_POST['data-img-5'] ?> "); background-size: cover;' <?php endif; ?>>
                                <div class="h-100 d-flex align-items-center justify-content-center logo" data-img-logo="img-5">
                                    <i class="fas fa-camera fa-2x text-secondary"></i>
                                </div>
                                <!--                                    // BTN DELETE-->
                                <button type="button" class="btn-delete-img d-none">Supprimer</button>
                            </label>
                            <input name="photo_5" id="photo_5" class="form-control" type="file" accept="image/jpeg,image/png" hidden>
                            <input type="hidden" name="nom-img-5" id="nom-img-5" class="input-img-name" value="<?= $_POST['nom-img-5'] ?? $editAnnounce['photo5'] ?? '' ?>">
                            <input type="hidden" name="data-img-5" class="input-img" value="<?= $_POST['data-img-5'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="mb-2 group-value">
                        <label for="form-address">Adresse</label>
                        <input name="announce-address" type="search" class="form-control" id="form-address" placeholder="Votre adresse complète ?" value="<?= $_POST['adresse'] ?? $editAnnounce['adresse'] ?? '' ?>">
                        <div class="info text-danger announce-adresse" data-error="errorAdresse"></div>
                    </div>
                    <div class="mb-3 group-value">
                        <label for="form-city" class="fw-bold">
                            Ville:
                            <span class="fw-light info-city"><?= $_POST['ville'] ?? $editAnnounce['ville'] ?? '' ?></span>
                        </label>
                        <input name="announce-city" type="hidden" id="form-city" class="input-info" placeholder="Votre ville" value="<?= $_POST['ville'] ?? $editAnnounce['ville'] ?? '' ?>">
                        <div class="info text-danger announce-ville d-none" data-error="errorVille"></div>
                    </div>
                    <div class="mb-3 group-value">
                        <label for="form-country" class="fw-bold">
                            Pays:
                            <span class="fw-light info-pays">
                                <?= $_POST['pays'] ?? $editAnnounce['pays'] ?? '' ?>
                            </span>
                        </label>
                        <input name="announce-pays" type="hidden" class="input-info" id="form-country" placeholder="Votre pays" value="<?= $_POST['pays'] ?? $editAnnounce['pays'] ?? '' ?>">
                        <div class="info text-danger announce-pays d-none" data-error="errorPays"></div>
                    </div>
                    <div class="mb-3 group-value">
                        <label for="form-zip" class="fw-bold">
                            Code Postal:
                            <span class="fw-light info-zip">
                                <?= $_POST['cp'] ?? $editAnnounce['cp'] ?? '' ?>
                            </span>
                        </label>
                        <div class="info text-danger announce-cp d-none" data-error="errorCp"></div>
                        <input name="announce-cp" type="hidden" class="input-info" id="form-zip" value="<?= $_POST['cp'] ?? $editAnnounce['cp'] ?? '' ?>">
                        <input name="announce-latlng_lat" type="hidden" class="input-info" id="latlngLat" value="<?= $_POST['latlng_lat'] ?? $editAnnounce['latlng_lat'] ?? '' ?>">
                        <input name="announce-latlng_log" type="hidden" class="input-info" id="latlngLog" value="<?= $_POST['latlng_log'] ?? $editAnnounce['latlng_log'] ?? '' ?>">
                    </div>

                </div>


            </div>
            <div class="col-12 d-flex justify-content-end">
                <?php if ((isset($_GET['action']) && ($_GET['action'] === 'edit')) || !empty($_POST)) : ?>
                    <a class="btn-secondary btn mx-1" href="<?php echo $_SERVER['PHP_SELF'] ?>">Annuler</a>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary" id="create-announce">
                    <?php echo (isset($_GET['action']) && $_GET['action'] === 'edit') ? 'Mettre à jour' : "Créer votre annonce" ?>
                </button>
            </div>
        </form>

    </div>
</div>


<?php
require_once __DIR__ . "/include/footer.php";
