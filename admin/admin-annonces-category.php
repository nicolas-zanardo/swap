<?php
require_once __DIR__ . "/../include/init.php";
require_once __DIR__ . "/../database/models/AnnouncesCategoriesDB.php";

$announceCategoryDB = new AnnouncesCategoriesDB();
$categories = $announceCategoryDB->fetchAll();

if (!empty($_POST['title-category']) && !empty($_POST['keyword-category'])) {
    $errors = 0;
    $_POST = filter_input_array(INPUT_POST, [
        'title-category' => FILTER_SANITIZE_STRING,
        'keyword-category' => FILTER_SANITIZE_STRING
    ]);

    if (empty($_POST['title-category'])) {
        $errors++;
        add_flash('Merci de choisir un titre', 'danger');
    }

    if (empty($_POST['keyword-category'])) {
        $errors++;
        add_flash('Merci de choisir un mot clé', 'danger');
    }

    if ($errors === 0) {
        $announceCategoryDB->createOne([
            'titre' => $_POST['title-category'],
            'motscle' => $_POST['keyword-category']
        ]);
        add_flash("la catégorie " . $_POST['title-category'] . " a bien été Ajouté", "success");
        header('location:' . $_SERVER['PHP_SELF']);
        exit();
    }
}
require_once __DIR__ . "/include/header.php";
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Annonces Catégories</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="admin-annonces.php">Annonces</a></li>
                <li class="breadcrumb-item active">Catégories</li>
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
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="announces nav-link active" id="pills-announces-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-announces" type="button" role="tab" aria-controls="pills-announces"
                            aria-selected="true">Catégories
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="announces nav-link" id="pills-add-announce-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-add-announce" type="button" role="tab"
                            aria-controls="pills-add-announce"
                            aria-selected="false">Ajouter une Catégorie
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <!--        TAB ANNOUNCES-->
                <div class="tab-pane fade show active" id="pills-announces" role="tabpanel"
                     aria-labelledby="pills-announces-tab">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Utilisateurs
                        </div>
                        <div class="card-body">
                            <table id="datatablesUsers">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>titre</th>
                                    <th>mots clé</th>
                                    <th>action</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>id</th>
                                    <th>titre</th>
                                    <th>mots clé</th>
                                    <th>action</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                <?php foreach ($categories as $category) : ?>
                                    <tr>
                                        <td><?= $category['id_categorie'] ?></td>
                                        <td>
                                            <textarea disabled name="keyword-category"
                                                      data-annonceCategory="title-<?= $category['id_categorie'] ?>"
                                                      class="form-control" id="keyword" rows="3"
                                                      placeholder="voiture, maison, vacance ...."><?= $category['titre'] ?></textarea>
                                        </td>
                                        <td>
                                            <textarea disabled name="keyword-category"
                                                      data-annonceCategory="keyword-<?= $category['id_categorie'] ?>"
                                                      class="form-control" id="keyword" rows="3"
                                                      placeholder="voiture, maison, vacance ...."><?= $category['motscles'] ?></textarea>

                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end align-content-stretch">
                                                <button class="btn btn-success mx-1 text-white d-none"
                                                        onclick="validEditCategory(<?= $category['id_categorie'] ?>)"
                                                        data-annonceCategory="valid-<?= $category['id_categorie'] ?>">
                                                    <i class="fas fa-check-square"></i></button>
                                                <button class="btn btn-info mx-1 text-white"
                                                        onclick="editCategory(<?= $category['id_categorie'] ?>)"
                                                        data-annonceCategory="edit-<?= $category['id_categorie'] ?>">
                                                    <i class="fas fa-user-edit"></i></button>
                                                <button class="btn btn-danger mx-1"
                                                        onclick="delCategory(<?= $category['id_categorie'] ?>)"><i
                                                            class="fas fa-trash-alt"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--        ADD ANNOUNCE-->
                <div class="tab-pane fade" id="pills-add-announce" role="tabpanel" aria-labelledby="pills-add-announce">
                    <form method="post">
                        <div class="mb-3">
                            <label for="title" class="form-label">titre</label>
                            <input name="title-category" type="text" class="form-control" id="title"
                                   aria-describedby="emailHelp">
                            <div id="info" class="text-danger"></div>
                        </div>
                        <div class="mb-3">
                            <label for="keyword" class="form-label">Mot clé</label>
                            <div>Les mots clé doivent être séparé par une virgule</div>
                            <textarea name="keyword-category" class="form-control" id="keyword" rows="3"
                                      placeholder="voiture, maison, vacance ...."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>


            <?php
            require_once __DIR__ . "/include/footer.php";

            ?>
