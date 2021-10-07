<?php
require_once __DIR__ . "/../include/init.php";
require_once __DIR__ . "/include/header.php";
require_once __DIR__ . "/../database/models/NotesDB.php";
require_once __DIR__ . "/../database/models/QuestionDB.php";
require_once __DIR__ . "/../database/models/AnnounceDB.php";
require_once __DIR__ . "/../database/models/AnnouncesCategoriesDB.php";
$notesDB = new NotesDb();
$questionDB = new QuestionDB();
$announceDB = new AnnounceDB();
$categoryDB = new AnnouncesCategoriesDB();

?>
    <div id="layoutSidenav_content">
    <main>
    <div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="d-flex flex-wrap justify-content-center">
        <div class="bg-info m-3 p-3 rounded w-25 text-white shadow" style="min-width: 280px">
            <h2><i class="fas fa-bullhorn me-1"></i>Annonces</h2>
            <hr>
            <h5>Total (<?= $announceDB->countAnnounces() ?>)</h5>
            <div class="card  text-dark">
                <div class="d-flex justify-content-between m-2 align-items-center">
                    <div>Nombre de catégories:</div>
                    <strong><?= $categoryDB->countCategory() ?></strong>
                </div>
            </div>
        </div>
        <div class="bg-dark m-3 p-3 rounded w-25 text-white shadow" style="min-width: 280px">
            <h2><i class="fas fa-comments me-1"></i> Questions</h2>
            <hr>
            <h5>Total (<?= $questionDB->countAllQuestions() ?>)</h5>
            <div class="card  text-dark">
                <div class="d-flex justify-content-between m-2 align-items-center">
                    <div>Question en attente</div>
                    <strong><?= $questionDB->countAllQuestionsPending() ?></strong>
                </div>
            </div>
        </div>
        <div class="bg-secondary m-3 p-3 rounded w-25 text-white shadow" style="min-width: 280px">
            <h2><i class="fas fa-star me-1"></i>Notes</h2>
            <hr>
            <h5>Total (<?= $notesDB->countNote() ?? "0" ?>)</h5>
            <?php
            /**
             * CALCUL DE LA MOYENNE DES NOTES
             */
            if( $notesDB->sumNote() === 0) {
                $noteMoyenne = "0%";
            }  elseif ($notesDB->countNote() === 0 ) {
                $noteMoyenne = "Aucune Note";
            } else {
                $noteMoyenne = round(($notesDB->sumNote() / $notesDB->countNote() * 10 * 2) , 2) . "%";
            }
            ?>
            <div class="card  text-dark">
                <div class="d-flex justify-content-between m-2 align-items-center">
                    <div>Satisfaction user:</div>
                    <strong><?= $noteMoyenne ?></strong>
                </div>
            </div>
        </div>
    </div>

<?php
require_once __DIR__ . "/include/footer.php";

?>