<?php
require_once __DIR__ . "/include/init.php";
require_once __DIR__ . "/database/models/AnnounceDB.php";
require_once __DIR__ . "/database/models/QuestionDB.php";
require_once __DIR__ . "/database/models/ResponseDB.php";
require_once __DIR__ . "/database/models/NotesDB.php";

$announces = new AnnounceDB();
$questionDB = new QuestionDB();
$noteDB = new NotesDB();
$responseDB = new ResponseDB();

if (isset($_GET['announce'])) {
    $announceGET = $announces->editArticle($_GET['announce']);
    $questionGET = $questionDB->fetchAllQuestionByIDAnnounce($_GET['announce']);
    $responseGET = $responseDB->findOneByID($announceGET['id_annonce']);
} else {
    $announceGET = null;
    $questionGET = null;
    $responseGET = null;
}

$getIdCategory = false;
$getIdUser = false;
$getPrice = false;


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['id_categorie']) && ($_GET['id_categorie'] !== 'false')) {
        $getIdCategory = $_GET['id_categorie'];
    }

    if (isset($_GET['id_membre']) && ($_GET['id_membre'] !== 'false')) {
        $getIdUser = $_GET['id_membre'];
    }

    if (isset($_GET['price']) && ($_GET['price'] !== 'false')) {
        $getPrice = $_GET['price'];
    }
}


/**
 *  Init Variable
 */
$title = "Accueil";
$eltCSS = ["search.css", "modal-auth.css", "datatable.css", "index.css"];
$eltJS = ["index.js", "search.js", "auth.js", "script.js", 'datatable.js'];

require_once __DIR__ . "/include/header.php";
require_once __DIR__ . "/include/component/navbar-public.php";

?>
    <div id="all-announces" class="<?= isset($_GET['announce']) ? "d-none" : "" ?>">
        <?php
        require_once __DIR__ . "/include/component/search.php";
        ?>
        <table id="datatable-announces" class="table d-flex justify-content-center flex-column w-100">
            <thead class="w-100">
            <tr class="w-100 d-flex justify-content-center flex-wrap">
                <th class="category-th">
                    <div class="px-4">Categorie</div>
                </th>
                <th class="titre-th">
                    <div class="px-4">Titre</div>
                </th>
                <th class="prix-th">
                    <div class="px-4">Prix</div>
                </th>
            </tr>
            </thead>
            <tbody class="d-flex justify-content-center w-100 flex-wrap">
            <?php foreach ($announces->requestAnnounce($getIdCategory, $getIdUser, $getPrice) as $announce) : ?>
                <tr type="button" id="<?= $announce['id_annonce'] ?>"
                    onclick="getAnnounce(<?= $announce['id_annonce'] ?>)"
                    class="card d-flex justify-content-between card rounded m-3 announces-card shadow ">
                    <td class="td-photo">
                        <div class="d-none"><?= $announce['categorie_titre'] ?></div>
                        <div style="
                                width: 100%;
                                height:100%;
                                background-image: url('<?= 'public/images/annonces/' . $announce['id_membre'] . '/' . $announce['id_annonce'] . '-' . '1' . '-' . $announce['photo'] ?>');
                                background-size: cover;
                                ">
                        </div>

                    </td>
                    <td class="td-info">
                        <div class="rounded bg-secondary text-white p-2">
                            <strong><?= $announce['titre'] ?></strong>
                        </div>
                        <div>
                            <small><?= $announce['categorie_titre'] ?></small>
                        </div>
                        <hr>
                        <div class="description"><?= $announce['description_courte'] ?></div>
                        <div class="d-none"><?= $announce['categorie_keyword'] ?></div>
                    </td>
                    <td class="p-0 m-0">
                        <div class="d-none"><?= $announce['prix'] ?></div>
                    </td>
                    <td class="td-price">
                        <div><?= $fmt->formatCurrency($announce['prix'], "EUR") ?></div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <div id="announce" class="m-3 <?= isset($_GET['announce']) ? "" : "d-none" ?>">
        <button id="close-announce" type="button" class="btn btn-secondary mb-3"><i
                    class="fas fa-arrow-circle-left me-1"></i> Retour aux annonces
        </button>

        <div class="container-fluid m-5">
            <div class="d-flex justify-content-between">
                <h1 id="title"><?= $announceGET['titre'] ?? '' ?></h1>
                <div id="edit-btn">
                    <?php if (isset($_GET['announce'], $_SESSION['user']['id_membre']) && ($announceGET['id_membre'] === $_SESSION['user']['id_membre'])): ?>
                        <a href="annonces.php?action=edit&id=<?=$announce['id_annonce']?>"
                           class="btn btn-warning d-flex align-items-center justify-content-center p-2">
                            <i class="fas fa-edit fa-2x text-white"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <hr>
            <div class="d-flex">
                ville: <strong id="city-top" class="me-1 ps-1"><?= $announceGET['ville'] ?? '' ?></strong>code postal:
                <strong id="cp-top" class="me-1 ps-1"><?= $announceGET['cp'] ?? '' ?></strong>
            </div>
            <div id="block-header" class="row">
                <div id="block-img my-3" class="col-xl-12 col-xxl-6">
                    <div id="img-principal" class="border roundedmb-3 bg-light d-flex justify-content-center">
                        <img src="
                        <?php if (isset($_GET['announce'])): ?>
                            public/images/annonces/<?= $announceGET['id_membre'] ?>/<?= $announceGET['id_annonce'] ?>-1-<?= $announceGET['photo'] ?>
                        <?php endif; ?>
                        " alt="<?= $announceGET['titre'] ?? '' ?>" class="img-fluid">
                    </div>
                    <?php if (isset($_GET['announce'])): ?>
                    <div id="tiles" class="d-flex">
                        <div id="photo1" class="ajaxImg"><img src="
                        <?php if ($announceGET['photo2'] !== ""): ?>
                            public/images/annonces/<?= $announceGET['id_membre'] ?>/<?= $announceGET['id_annonce'] ?>-1-<?= $announceGET['photo1'] ?>
                        <?php endif; ?>
                        " class="img-fluid"></div>
                        <div id="photo2" class="ajaxImg"><img src="
                        <?php if ($announceGET['photo3'] !== ""): ?>
                            public/images/annonces/<?= $announceGET['id_membre'] ?>/<?= $announceGET['id_annonce'] ?>-2-<?= $announceGET['photo2'] ?>
                        <?php endif; ?>
                        " class="img-fluid"></div>
                        <div id="photo3" class="ajaxImg"><img src="
                        <?php if ($announceGET['photo3'] !== ""): ?>
                            public/images/annonces/<?= $announceGET['id_membre'] ?>/<?= $announceGET['id_annonce'] ?>-3-<?= $announceGET['photo3'] ?>
                        <?php endif; ?>
                        " class="img-fluid"></div>
                        <div id="photo4" class="ajaxImg"><img src="
                         <?php if ($announceGET['photo4'] !== ""): ?>
                            public/images/annonces/<?= $announceGET['id_membre'] ?>/<?= $announceGET['id_annonce'] ?>-4-<?= $announceGET['photo4'] ?>
                        <?php endif; ?>
                        " class="img-fluid"></div>
                        <div id="photo5" class="ajaxImg"><img src="
                         <?php if ($announceGET['photo5'] !== "") : ?>
                            public/images/annonces/<?= $announceGET['id_membre'] ?>/<?= $announceGET['id_annonce'] ?>-5-<?= $announceGET['photo5'] ?>
                        <?php endif; ?>
                        " class="img-fluid"></div>
                    </div>
                    <?php endif; ?>
                </div>
                <div id="blockInfo" class="col-xl-12 col-xxl-6 mb-3">
                    <!--Info-->
                    <h3 id="price"
                        class="bg-dark rounded text-white p-2"><?= $announceGET['prix'] . ' €' ?? '' ?></h3>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div>
                                <div>Date de Création :
                                    <strong
                                            id="createDate"><?= $announceGET['date_enregistrement'] ?? '' ?>
                                    </strong>
                                </div>

                                <div class="mt-1">Nombre de vote : <span
                                            id="nbrVote"><?= $noteDB->countNoteByUser($announceGET['id_membre']) ?? "<strong>Aucun vote</strong>" ?></span>
                                </div>
                                <div class="progress " id="progress">
                                    <div class="progress-bar bg-warning" role="progressbar"

                                        <?php
                                        /**
                                         * CALCUL DE LA MOYENNE DES NOTES
                                         */
                                        if( $noteDB->sumNoteByIDMembre($announceGET['id_membre']) === 0) {
                                            $noteMoyenne = "0";
                                        }  elseif ($noteDB->countNoteByUser($announceGET['id_membre']) === 0 ) {
                                            $noteMoyenne = "0";
                                        } else {
                                            $noteMoyenne = round(($noteDB->sumNoteByIDMembre($announceGET['id_membre']) / $noteDB->countNoteByUser($announceGET['id_membre']) * 10 * 2) , 2);
                                        }
                                        ?>
                                         style="width: <?= $noteMoyenne  ?>%"
                                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="5">
                                    </div>
                                </div>
                                <button class="btn btn-primary my-2" type="button" data-bs-toggle="modal" data-bs-target="#modal">Votez</button>
                                <!-- Modal -->
                                <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalLabel">Donnez une note</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="post" class="d-flex justify-content-center" id="formNote">
                                                    <input type="hidden" name="idMembre" value=<?= $announceGET['id_membre'] ?? "" ?>>
                                                    <div class="p-1">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                           1
                                                        </label>
                                                    </div>
                                                    <div class="p-1">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" value="2">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            2
                                                        </label>
                                                    </div>
                                                    <div class="p-1">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault3" value="3" checked>
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            3
                                                        </label>
                                                    </div>
                                                    <div class="p-1">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault4" value="4">
                                                        <label class="form-check-label" for="flexRadioDefault4">
                                                            4
                                                        </label>
                                                    </div>
                                                    <div class="p-1">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault5" value="5">
                                                        <label class="form-check-label" for="flexRadioDefault5">
                                                            5
                                                        </label>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">X</button>
                                                <button type="button" class="btn btn-primary" id="btnNote" >Validez votre note</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btn-group col-12 col-lg-6">
                            <div>
                                Auteur : <strong id="author"><?= $announceGET['pseudo'] ?? '' ?></strong>
                                <button class="btn btn-secondary btn-secondary w-100" type="button" id="displayInfoContact">
                                    <i class="fas fa-phone mx-3"></i> Contacter le vendeur
                                </button>
                                <div class="p-2 d-none" id="blocInfoContact">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <span id="phone"><?= $announceGET['telephone'] ?? '' ?></span>
                                        <i class="fas fa-mobile w-25 text-center"></i>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-end">
                                        <span id="email"><?= $announceGET['email'] ?? '' ?></span>
                                        <i class="fas fa-envelope-open w-25 text-center"></i>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Long description-->
                    <h3 class="mt-3">Description</h3>
                    <hr>
                    <p id="long-description"
                       class="mt3 article-content"><?= $announceGET['description_longue'] ?? '' ?></p>
                </div>
            </div>
            <!-- Map-->
            <div class="street-map">
                <div id="map" class="map"></div>
                <div class="info-city-map card p-3 m-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <i class="fas fa-city mb-1"></i><h5 id="bottom-city"
                                                            class="mx-1"><?= $announceGET['ville'] ?? '' ?></h5> -
                        <h6 id="bottom-cp" class="mx-1"><?= $announceGET['cp'] ?? '' ?></h6>
                    </div>

                    <div class="mt-2">
                        <strong><i class="fas fa-map-marker-alt me-1 fa-lg"></i></strong>
                        <span id="latitude-gps" class="me-1"><?= $announceGET['latlng_log'] ?? '' ?></span> |
                        <span id="longitude-gps" class="me-1"><?= $announceGET['latlng_lat'] ?? '' ?></span>
                    </div>
                </div>
            </div>
            <div class="p-3 bg-secondary mt-3 rounded text-white">
                <div class="row d-flex justify-content-between align-items-center">
                    <h5 class="col-lg-3 col-ml-12">Questions :</h5>
                    <?php if (!isset($_SESSION['user']['id_membre'])): ?>
                        <a class="col-lg-9 col-ml-12 ps-2 btn btn-primary" id="connection-question"
                           data-bs-toggle="modal" href="#modalAuth" role="button">
                            Connectez-vous
                        </a>
                    <?php endif; ?>
                    <div class="col-lg-9 col-ml-12 ps-2" id="ask-question">
                        <?php if (isset($_SESSION['user']['id_membre']) && ($_SESSION['user']['id_membre'] !== $announceGET['id_membre'])): ?>
                            <button class="btn btn-primary w-100 " type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseMessage" aria-expanded="false"
                                    aria-controls="collapseMessage">
                                Posez une question
                            </button>
                        <?php endif; ?>
                        <div class="collapse " id="collapseMessage">
                            <div class="card card-body">
                                <div>
                                    <form id="form-question">
                                        <input type="hidden" name="member-id-question"
                                               value="<?= $announceGET['id_membre'] ?? "" ?>">
                                        <input type="hidden" name="announce-id-question"
                                               value="<?= $announceGET['id_annonce'] ?? "" ?>">
                                        <textarea class="form-control" name="question"
                                                  id="exampleFormControlTextarea1" rows="6"></textarea>
                                        <div class="d-flex justify-content-end mt-2">
                                            <button type="button" class="btn btn-secondary" id="valid-question"><i
                                                        class="fas fa-check"></i></button>
                                        </div>
                                    </form>
                                    <div id="message-send-ok"
                                         class="text-dark d-flex justify-content-center align-items-center d-none">
                                        Message envoyé <i class="fas fa-check-circle mx-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div id="block-questions">
                    <div id="questions">
                        <?php foreach ($questionGET as $question): ?>
                            <div>
                                <div class="card text-dark p-2 m-1 bg-light">
                                    <strong>QUESTION : </strong>
                                    <div>
                                        <?= $question['question'] ?>
                                    </div>
                                    <?php if ($question['reponse']): ?>
                                        <hr>
                                        <div class="card">
                                            <div class="d-flex justify-content-between p-2 ">
                                                <strong>Réponse : </strong>
                                                <div>
                                                    <?= $question['reponse'] ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.8.1/build/ol.js"></script>
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList"></script>

<?php
require_once __DIR__ . "/include/footer.php";



