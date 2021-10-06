<?php
require_once __DIR__ . "/include/init.php";
require_once __DIR__ . "/database/models/QuestionDB.php";
require_once __DIR__ . "/database/models/ResponseDB.php";

$questionDB = new QuestionDB();
$responseDB = new ResponseDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//    $name = "response" . $_POST['id_question'];
    $countIndex = $questionDB->countAllQuestionByUser($_SESSION['user']['id_membre']);
    $errors = 0;

    for ($key = 0; $key < $countIndex; $key++) {
        $questionID = "idQuestion" . $key;
        $responseKey = "response" . $key;
        if (isset($_POST[$questionID]) && isset($_POST[$responseKey])) {
            $_POST[$questionID] = filter_var($_POST[$questionID], FILTER_SANITIZE_STRING);
            $_POST[$responseKey] = filter_var($_POST[$responseKey], FILTER_SANITIZE_STRING);

            if (empty($_POST[$questionID]) || empty($_POST[$responseKey])) {
                $errors++;
                add_flash("Vous devez écrire un message", 'warning');
            }

            if ($errors === 0) {
                $responseDB->createResponse([
                    'reponse' => $_POST[$responseKey],
                    'id_membre' => $_SESSION['user']['id_membre'],
                    'id_question' => $_POST[$questionID]
                ], $_POST[$questionID]);
            }
        }


    }


}

/**
 *  Init Variable
 */
$title = "messages";
$eltCSS = ["datatable.css", "messages.css"];
$eltJS = ["script.js", "messages.js", 'datatable.js'];

require_once __DIR__ . "/include/header.php";
require_once __DIR__ . "/include/component/navbar-public.php";

?>

    <div>
        <h2>Question en attente</h2>
        <hr>
        <table class="table">
            <thead class="mt-3">
            <tr class="d-flex justify-content-between mt-3">
                <th class="card w-25 me-1">Date</th>
                <th class="card w-25 ms-1">répondu</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($questionDB->findQuestionByUser($_SESSION['user']['id_membre']) as $key => $question) : ?>
                <tr class="card mt-3 w-100">
                    <td class="w-100">
                        <div>
                            <div class="p-2 rounded <?= $question['pending'] ? "bg-danger text-white" : "bg-success text-white" ?>"><?= $question['date'] ?></div>
                            <div class="w-100 my-2 mx-2">
                                <div class="d-flex w-100">
                                    <img src="/public/images/annonces/<?= $_SESSION['user']['id_membre'] ?>/<?= $question['id_annonce'] ?>-1-<?= $question['photo'] ?>"
                                         alt="<?= $question['titre'] ?>" height="150">
                                    <div class="mx-3 w-100">
                                        <h4>Question</h4>
                                        <hr>
                                        <div>
                                            <?= $question['question'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card me-3 my-3 p-2">
                                    <?php if ($question['pending']): ?>
                                        <form method="post" class="w-100">
                                            <input type="hidden" name="idQuestion<?= $key ?>"
                                                   value="<?= $question['id_question'] ?>">
                                            <label class="w-100" for="response">Réponse</label>
                                            <textarea class="w-100" name="response<?= $key ?>" id="response<?= $key ?>"
                                                      cols="30" rows="5"></textarea>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-primary">Répondre</button>
                                            </div>
                                        </form>
                                    <?php else : ?>
                                    <?php $reponse = $responseDB->findOneByID($question['id_question']) ?>
                                        <div>
                                            <h6>Réponse :</h6>
                                            <p><?= $reponse['reponse'] ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>

                    </td>
                    <td class="d-none"><?= $question['pending'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php
require_once __DIR__ . "/include/footer.php";