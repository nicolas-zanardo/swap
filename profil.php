<?php
require_once __DIR__ . "/include/init.php";
if (!isConnected()) {
    // avant la function header, aucun echo, aucune balise html
    header('Location:/');
    // Stop le script php
    exit();
}

require_once __DIR__ . "/database/models/NotesDB.php";
require_once __DIR__ . "/database/models/UserDB.php";
require_once __DIR__ . "/database/models/AnnounceDB.php";
require_once __DIR__ . "/database/models/QuestionDB.php";
$usersDBProfile = new UserDB();
$announces = new AnnounceDB();
$questionDB = new QuestionDB();
$noteDB = new NotesDB();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = 0;
    $_POST = filter_input_array(INPUT_POST, [
        'pseudo' => FILTER_SANITIZE_STRING,
        'telephone' => FILTER_SANITIZE_STRING,
        'nom' => FILTER_SANITIZE_STRING,
        'prenom' => FILTER_SANITIZE_STRING,
        'email' => FILTER_SANITIZE_EMAIL,
        'civilite' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'mdp' => FILTER_SANITIZE_STRING,
        'mdpConfirm' => FILTER_SANITIZE_STRING,
    ]);


    // PSEUDO
    if (empty($_POST['pseudo'])) {
        $errors++;
        add_flash('Merci de choisir un pseudo', 'danger');
    }
    if ($_POST['pseudo'] !== $_SESSION['user']['pseudo']) {
        $user = $usersDBProfile->fetchOne("pseudo", $_POST['pseudo']);
        if ($user) {
            $errors++;
            add_flash('le pseudo choisi est indisponible. Merci d\'en choisir un autre', 'warning');
        }
        if (mb_strlen($_POST['pseudo']) < 5) {
            $errors++;
            add_flash('le pseudo choisi est trop court', 'danger');
        }
        if (mb_strlen($_POST['pseudo']) > 20) {
            $errors++;
            add_flash('le pseudo choisi est trop long', 'danger');
        }
    }

    // TELEPHONE
    if (empty($_POST['telephone'])) {
        $errors++;
        add_flash('Merci de renseignez le numéro de téléphone', 'danger');
    }
    if (!empty($_POST['telephone'])) {
        $pattern = '#^((\+)?[0-9]{10,20})$#';
        if (!preg_match($pattern, $_POST['telephone'])) {
            $errors++;
            add_flash('Le numéro de téléphone doit être composé de 10 a 20 chiffre', 'danger');
        }
    }

    // EMAIL
    if (empty($_POST['email'])) {
        $errors++;
        add_flash('Merci de renseignez le numéro de téléphone', 'danger');
    }
    if (!empty($_POST['email'])) {
        $pattern = '#^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,4}$#';
        if (!preg_match($pattern, $_POST['email'])) {
            $errors++;
            add_flash('L\'email renseigné n\'est pas valide', 'danger');
        }
    }


    // PASSWORD
    if (!empty($_POST['mdp'])) {
        $pattern = '#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[\S]{8,60}$#';
        if (!preg_match($pattern, $_POST['mdp'])) {
            $errors++;
            add_flash('Le mot de passse doit être composé de 8 a 60 caratères comprenant au moins une minuscule, une majuscule et un chiffre', 'danger');
        }
        if (empty($_POST['mdpConfirm'])) {
            $errors++;
            add_flash('Merci de confirmer votre mot de passe', 'danger');
        }
        if (!empty($_POST['mdp']) && $_POST['mdpConfirm'] !== $_POST['mdp']) {
            $errors++;
            add_flash('La confirmation ne concorde pas avec le mot de passe', 'danger');
        }
    }

    if ($errors === 0) {
        if (empty($_POST['mdp'])) {
            $usersDBProfile->updateOne([
                'pseudo' => $_POST['pseudo'],
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'telephone' => $_POST['telephone'],
                'email' => $_POST['email'],
                'civilite' => $_POST['civilite'],
                'id_membre' => $_SESSION['user']['id_membre']
            ]);
        } else {
            $usersDBProfile->updateUserWithPassword([
                'pseudo' => $_POST['pseudo'],
                'mdp' => $_POST['mdp'],
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'telephone' => $_POST['telephone'],
                'email' => $_POST['email'],
                'civilite' => $_POST['civilite'],
                'id_membre' => $_SESSION['user']['id_membre']
            ]);
        }
        $user = $usersDBProfile->fetchOne('id_membre', $_SESSION['user']['id_membre']);
        $_SESSION['user'] = $user;
        add_flash('Modification réussie', 'success');
        header('Location:' . $_SERVER['PHP_SELF']);
        exit();
    }

}


/**
 *  Init Variable
 */
$title = "Profil";
$eltCSS = ["profil.css"];
$eltJS = ["auth.js", "profil.js", "script.js"];

require_once __DIR__ . "/include/header.php";
require_once __DIR__ . "/include/component/navbar-public.php";
?>
    <div class="container">
        <h3>Bonjour, <span class="fw-bolder"><?= $_SESSION['user']['pseudo'] ?></span></h3>
        <hr>
        <div class="my-5 row d-flex px-3 my-1 justify-content-center justify-content-md-around flex-wrap">
            <a class="col-lg-3 my-1 col-12 bg-light rounded py-3 px-4 text-dark bubble-card" href="messages.php">
                <div class="d-flex flex-column">
                    <h4><i class="fas fa-comments me-1"></i> <span>Questions</span></h4>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex justify-content-between">
                            <span>En attente</span>
                            <span>(<?php echo $questionDB->countQuestionByUser($_SESSION['user']['id_membre']) ?>)</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Total</span>
                            <span>(<?php echo $questionDB->countAllQuestionByUser($_SESSION['user']['id_membre']) ?>)</span>
                        </div>
                    </div>

                </div>
                <hr>
                <div><i class="fas fa-info-circle me-1"></i>Voir les message</div>
            </a>
            <a class="col-lg-3 my-1 col-12 bg-secondary rounded text-white py-3 px-4 bubble-card" href="annonces.php">
                <h4 class="d-flex justify-content-between">
                    <span><i class="fas fa-bullhorn me-1"></i>Annonces</span>
                    <span>(<?php echo $announces->countAnnounceByUser($_SESSION['user']['id_membre']) ?>)</span>
                </h4>
                <hr>
                <div><i class="fas fa-info-circle me-1"></i>Voir les annonces</div>
            </a>
            <div class="col-lg-3 my-1 col-12 bg-dark text-white rounded py-3 px-4 bubble-card">
                <h4 class="d-flex justify-content-between">
                    <span><i class="fas fa-star me-1"></i>Notes </span>
                    <span>(<?= $noteDB->countNoteByIDMembre($_SESSION['user']['id_membre']) ?>)</span>
                </h4>
                <?php
                if( $noteDB->sumNoteByIDMembre($_SESSION['user']['id_membre']) === 0) {
                    $noteMoyenne = "0%";
                }  elseif ($noteDB->countNoteByUser($_SESSION['user']['id_membre']) === 0 ) {
                    $noteMoyenne = "Aucune evaluation";
                } else {
                    $noteMoyenne = round(($noteDB->sumNoteByIDMembre($_SESSION['user']['id_membre']) / $noteDB->countNoteByUser($_SESSION['user']['id_membre']) * 10 * 2) , 2) . "%";
                }
                ?>
                <hr>
                <div class="d-flex justify-content-between align-items-center h-25">
                    <div>Votre Score : <span><?= $noteMoyenne ?></span></div>
                </div>
            </div>
        </div>
        <div class="card info-profil p-3 mb-5">
            <h2 class="border-bottom w-25">Info :</h2>
            <form method="POST" id="formProfile" novalidate>
                <div class="input-group mb-3">
                    <div class="col-md-6 col-12 p-1 group-value">
                        <label for="pseudo-profile" class="form-label">Pseudo</label>
                        <div class="small">Veuillez renseigner entre 5 et 20 charactères</div>
                        <div class="input-group">
                            <input id="pseudo-profile" name="pseudo" type="text" class="form-control"
                                   aria-describedby="basic-addon3"
                                   value="<?php echo $_SESSION['user']['pseudo'] ?? '' ?>">
                        </div>
                        <div class="info pseudo text-danger" data-error="errorPseudo"></div>
                    </div>
                    <div class="col-md-6 col-12 p-1 group-value">
                        <label for="telephone-profile" class="form-label">Téléphone</label>
                        <div class="small">Veuillez renseigner entre 10 et 20 chiffres</div>
                        <div class="input-group">
                            <span class="input-group-text" id="phone"><i class="fas fa-phone"></i></span>
                            <input name="telephone" type="text" class="form-control" id="telephone-profile"
                                   aria-describedby="basic-addon3"
                                   value="<?php echo $_SESSION['user']['telephone'] ?? '' ?>">
                        </div>
                        <div class="info text-danger" data-error="errorTelephone"></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <div class="col-md-6 col-12 p-1 group-value">
                        <label for="last-name" class="form-label">Nom</label>
                        <div class="small">20 charactères maximum</div>
                        <div class="input-group">
                            <input name="nom" type="text" class="form-control" id="last-name"
                                   aria-describedby="basic-addon3" value="<?php echo $_SESSION['user']['nom'] ?? '' ?>">
                        </div>
                        <div class="info text-danger" data-error="errorNom"></div>
                    </div>
                    <div class="col-md-6 col-12 p-1 group-value">
                        <label for="first-name" class="form-label">Prenom</label>
                        <div class="small">20 charactères maximum</div>
                        <div class="input-group">
                            <input name="prenom" type="text" class="form-control" id="first-name"
                                   aria-describedby="basic-addon3"
                                   value="<?php echo $_SESSION['user']['prenom'] ?? '' ?>">
                        </div>
                        <div class="info text-danger" data-error="errorPrenom"></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <div class="col-md-6 col-12 p-1 group-value">
                        <label for="email-profile" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-envelope"></i></span>
                            <input name="email" type="text" class="form-control" id="email-profile"
                                   aria-describedby="basic-addon3"
                                   value="<?php echo $_SESSION['user']['email'] ?? '' ?>">
                        </div>
                        <div class="info text-danger" data-error="errorEmail"></div>
                    </div>
                    <div class="col-md-6 col-12 p-1">
                        <label for="civility" class="form-label">Civilité</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-venus-mars"></i></span>
                            <select name="civilite" class="form-select" aria-label="Default select example">
                                <option value="n" <?= ($_SESSION['user']['civilite'] === null || "n") ? 'selected' : '' ?>>
                                    -- Anonyme --
                                </option>
                                <option value="m" <?= ($_SESSION['user']['civilite'] === "m") ? 'selected' : '' ?>>
                                    Masculin
                                </option>
                                <option value="f" <?= ($_SESSION['user']['civilite'] === "f") ? 'selected' : '' ?>>
                                    Féminin
                                </option>
                            </select>
                        </div>
                        <div class="info text-danger" data-error="errorCivility"></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <div class="col-md-6 col-12 p-1 group-value">
                        <label for="password" class="form-label">Mot de passe</label>
                        <div class="small">8 caratères minimum et 60 maximum, 1 majuscule, 1 cartère spécial</div>
                        <div class="input-group elt-password">
                            <input name="mdp" type="password" class="form-control show-password" id="password"
                                   aria-describedby="basic-addon3">
                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-eye-slash"></i></span>
                        </div>
                        <div class="info text-danger" data-error="errorMdp"></div>
                    </div>
                    <div class="col-md-6 col-12 p-1 group-value">
                        <label for="confirm-password" class="form-label">Confirmer le mot de passe</label>
                        <div class="small">8 caratères minimum et 60 maximum, 1 majuscule, 1 cartère spécial</div>
                        <div class="input-group elt-password">
                            <input name="mdpConfirm" type="password" class="form-control show-password"
                                   id="confirm-password" aria-describedby="basic-addon3">
                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-eye-slash"></i></span>
                        </div>
                        <div class="info text-danger" data-error="errorConfirmMdp"></div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="validSubmitProfile">Valider</button>
                </div>
            </form>
            <!-- S'affiche seulement si c'est un USER -->
            <?php if ($_SESSION['user']['status'] !== '1') : ?>
                <div class="accordion mt-5" id="accordionDeleteProfile">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed text-danger" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                                    aria-controls="collapseTwo">
                                Supprimer votre compte
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                             data-bs-parent="#accordionDeleteProfile">
                            <div class="accordion-body">
<!--                                <form action="" novalidate>-->
                                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal"
                                            href="#deleteProfile" role="button">Supprimer
                                    </button>
<!--                                </form>-->
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="modal fade" id="deleteProfile" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
         tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="exampleModalToggleLabel">Suprimer le compte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Voulez vous supprimer votre compte définitivement ?
                </div>
                <div class="modal-footer ">
                    <button type="button" id="delete-account" class="btn btn-danger me-1"
                            onclick="delUser(<?= $_SESSION['user']['id_membre'] ?>)">supprimer
                    </button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Quitter</button>
                </div>
            </div>
        </div>
    </div>
<?php
require_once __DIR__ . "/include/footer.php";