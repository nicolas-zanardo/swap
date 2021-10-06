<?php
require_once __DIR__ . "/../include/init.php";
require_once __DIR__ . "/../database/models/UserDB.php";

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';
$usersDB = new UserDB();

if ($id) {
    $user = $usersDB->fetchOne('id_membre', $id);
}

if (!empty($_POST)) {

    $errors = 0;
    $_POST = filter_input_array(INPUT_POST, [
        'roleUser' => FILTER_SANITIZE_STRING
    ]);

    if ($$id != $_SESSION['user']['id_membre']) {
        $user['status'] = $_POST['roleUser'];
        $usersDB->updateOneROLE_USER($user);
        add_flash("Les droit d'utilisateur $user[pseudo] on bien été modifier", "success");
        header('location:' . $_SERVER['PHP_SELF'] . "?id=$id");
        exit();
    } else {
        add_flash("Impossible de modifier ses propre droit", 'danger');
    }


}

require_once __DIR__ . "/include/header.php";
?>
    <div id="layoutSidenav_content">
    <main>
    <div class="container-fluid px-4">
    <h1 class="mt-4">Utilisateur Info</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="users.php">Utilisateurs</a></li>
        <li class="breadcrumb-item active"><?= $user['pseudo'] ?? '' ?></li>
    </ol>
    <div class="message-flash">
        <?php if (!empty(show_flash())) : ?>
            <div class="row justify-content-center fade show">
                <div class="col">
                    <?php echo show_flash('reset'); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="card p-3">
        <?php if (isset($user)) : ?>
            <?php if ($user['prenom'] === null && $user['nom'] === null) : ?>
                <h1>ANONYME</h1>
            <?php else : ?>
                <h1><?= $user['prenom'] ?>  <?= $user['nom'] ?></h1>
            <?php endif; ?>
            <div><i class="fas fa-calendar-alt"></i><span
                        class="fw-light"> <?= date('d/m/Y à H:i:s', strtotime($user['date_enregistrement'])) ?></span>
            </div>
            <div>Pseudo : <span class="fw-bolder"><?= $user['pseudo'] ?></span></div>
            <div class="mt-5 row bg-secondary p-5 text-white">
                <div class="col-md-6 justify-content-center col-12 my-1 ">
                    <div class="d-flex align-items-center flex-wrap">
                        <i class="fas fa-phone me-1"></i> <span class="me-1">Téléphone : </span> <span
                                class="fw-bolder"><?= $user['telephone'] ?></span>
                    </div>
                </div>
                <div class="col-md-6 col-12 my-1">
                    <div class="d-flex align-items-center flex-wrap">
                        <i class="fas fa-envelope me-1"></i> <span class="me-1">Email : </span> <span
                                class="fw-bolder"><?= $user['email'] ?></span>
                    </div>
                </div>
            </div>
            <div class="row bg-light p-5">
                <div class="col-md-6 justify-content-center col-12 my-1 ">
                    <div class="d-flex align-items-center flex-wrap">
                        <i class="fas fa-venus-mars me-1"></i> <span class="me-1">Genre : </span>
                        <span class="fw-bolder">
                            <?php if ($user['civilite'] === null)  : ?>
                                ANONYME
                            <?php elseif ($user['civilite'] === "n") : ?>
                                ANONYME
                            <?php elseif ($user['civilite'] === "m") : ?>
                                Homme
                            <?php elseif ($user['civilite'] === "f") : ?>
                                Femme
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-6 col-12 my-1">
                    <div class="d-flex align-items-center flex-wrap">
                        <i class="fas fa-users-cog me-1"></i> <span class="me-1">ROLE_USER: </span>
                        <span class="fw-bolder">
                        <?php if ((int)$user['status'] === 0) : ?>
                            <span class="text-success">User</span>
                        <?php else  : ?>
                            <span class="text-warning">ADMIN</span>
                        <?php endif; ?>
                    </span>
                    </div>
                </div>
            </div>
            <?php if ($user['id_membre'] !== $_SESSION['user']['id_membre'])  : ?>
                <div class="row accordion mt-5 " id="accordionExample">
                    <div class="accordion-item bg-secondary p-2">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed bg-dark text-white" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <i class="fas fa-user-edit me-1"></i> Modifier les droits d'utilisateur
                            </button>
                        </h2>

                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                             data-bs-parent="#accordionExample">
                            <div class="accordion-body text-white">
                                <form method="post" novalidate>
                                    <label for="user_role" class="form-label">Attribuer un droit d'utilisateur
                                        : </label>
                                    <select class="form-select" name="roleUser" id="user_role">
                                        <option value="0" <?= ((int)$user['status'] === 0) ? 'selected' : '' ?>>User
                                        </option>
                                        <option value="1" <?= ((int)$user['status'] === 1) ? 'selected' : '' ?>>Admin
                                        </option>
                                    </select>
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-danger mt-3">Valider</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="alert alert-info mx-3" role="alert">
                Aucun utilisateur trouvé
            </div>
        <?php endif; ?>
    </div>


<?php
require_once __DIR__ . "/include/footer.php";

?>