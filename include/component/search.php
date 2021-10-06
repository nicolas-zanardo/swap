<?php
require_once __DIR__ . "/../../database/models/AnnouncesCategoriesDB.php";
require_once __DIR__ . "/../../database/models/UserDB.php";
require_once __DIR__ . "/../../database/models/AnnounceDB.php";
// SQL
$categories = new AnnouncesCategoriesDB();
$users = new UserDB();
$announces = new AnnounceDB();

$getIdCategorySearch = false;
$getIdUserSearch = false;
$getPriceSearchSearch = false;


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['id_categorie']) && ($_GET['id_categorie'] !== 'false')) {
        $getIdCategorySearch = $_GET['id_categorie'];
    }

    if (isset($_GET['id_membre']) && ($_GET['id_membre'] !== 'false')) {
        $getIdUserSearch = $_GET['id_membre'];
    }

    if (isset($_GET['price']) && ($_GET['price'] !== 'false')) {
        $getPriceSearch = $_GET['price'];
    }
}

?>
<div class="card p-3 bck-search" id="bar-search">
    <form class="d-flex flex-column">
        <nav class="navbar navbar-expand-xl navbar-light bg-light d-flex ">
            <div class="container-fluid">
                <div class="navbar-brand text-color-sh my-2">Options :</div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
                        aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav w-100">
                        <div class="col-md mx-1">
                            <div class="form-floating">
                                <select class="form-select tall-sh-select my-2 w-100"
                                        name="id_categorie"
                                        id="floatingSelectGridCategories"
                                        aria-label="Floating label select example">
                                    <option value="false">-- Sélectionnez --</option>
                                    <?php foreach ($categories->fetchAll() as $category): ?>
                                        <?php if ($_GET['id_categorie']) : ?>
                                            <option value="<?= $category['id_categorie'] ?>" <?= ($_GET['id_categorie'] === $category['id_categorie']) ? 'selected' : "" ?>><?= $category['titre'] ?></option>
                                        <?php else: ?>
                                            <option value="<?= $category['id_categorie'] ?>"><?= $category['titre'] ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                <label for="floatingSelectGridCategories">Catégories</label>
                            </div>
                        </div>

                        <div class="col-md mx-1">
                            <div class="form-floating">
                                <select class="form-select tall-sh-select my-2 w-100"
                                        name="id_membre"
                                        id="floatingSelectGridMember"
                                        aria-label="Default select example">
                                    <option value="false">-- Sélectionnez --</option>
                                    <?php foreach ($users->fetchAll() as $user): ?>
                                        <?php if ($_GET['id_membre']): ?>
                                            <option value="<?= $user['id_membre'] ?>" <?= ($_GET['id_membre'] === $user['id_membre']) ? 'selected' : "" ?> ><?= $user['pseudo'] ?></option>
                                        <?php else: ?>
                                            <option value="<?= $user['id_membre'] ?>"><?= $user['pseudo'] ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                <label for="floatingSelectGridMember">Toutes les membres</label>
                            </div>
                        </div>
                        <div class="col-md mx-1 p-1">
                            <div class="tall-sh-select w-100">
                                <label id="labelCustomRange" for="customRange"
                                       class="form-label d-flex justify-content-between">
                                    <small>
                                    min <?= $announces->countMinPrice($getIdCategorySearch, $getIdUserSearch) ?? '0' ?>
                                    €
                                    </small>
                                    <small>
                                    max <?= $announces->countMaxPrice($getIdCategorySearch, $getIdUserSearch) ?? '0' ?>
                                    €
                                    </small>
                                </label>
                                <input name="price" type="range" class="form-range"
                                       value=<?php
                                       if (isset($_GET['price'])) {
                                           if ($_GET['price'] > $announces->countMaxPrice($getIdCategorySearch, $getIdUserSearch)) {
                                               echo (string)$announces->countMaxPrice($getIdCategorySearch, $getIdUserSearch);
                                           } elseif ($_GET['price'] < $announces->countMinPrice($getIdCategorySearch, $getIdUserSearch)) {
                                               echo (string)$announces->countMinPrice($getIdCategorySearch, $getIdUserSearch);
                                           } else {
                                               echo (string)$_GET['price'];
                                           }
                                       } else {
                                           echo $announces->countMaxPrice($getIdCategorySearch, $getIdUserSearch);
                                       } ?>

                                       min="<?= $announces->countMinPrice($getIdCategorySearch, $getIdUserSearch) ?? '0' ?>"
                                       max="<?= $announces->countMaxPrice($getIdCategorySearch, $getIdUserSearch) ?? '0' ?>"
                                       id="customRange">
                            </div>
                        </div>

                        <button class="btn btn-secondary" type="submit">Trouvez</button>

                    </div>
                </div>
            </div>
        </nav>
    </form>
</div>


