<?php
require_once __DIR__ . "/../include/init.php";
require_once __DIR__ . "/../database/models/AnnounceDB.php";
require_once __DIR__ . "/../database/models/PhotoDB.php";
require_once __DIR__ . "/include/header.php";

$announces = new AnnounceDB();
$photoDB = new PhotoDB();


if (!empty($_POST)) {
    $announce = $announces->findAnnounce('id_annonce', $_POST['announceID']);

    $path = '/../public/images/annonces/'. $announce['id_membre'];

    $scan = scandir(__DIR__ . $path);
    $regex = '/^' . $_POST['announceID'] . '-/';

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

    add_flash("L'annonce a bien été suprimé", "success");
    header('location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Annonces</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Annonces</li>
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
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Annonces
                </div>
                <div class="card-body">
                    <table id="datatablesUsers">
                        <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Titre</th>
                            <th>Catégories</th>
                            <th>action</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Photo</th>
                            <th>Titre</th>
                            <th>Catégories</th>
                            <th>action</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        <?php foreach ($announces->findAll() as $announce) : ?>
                            <tr>
                                <td><img src="/../public/images/annonces/<?= $announce['id_membre'] ?>/<?= $announce['id_annonce'] ?>-1-<?= $announce['photo'] ?>" alt="<?= $announce['titre'] ?>" height="150"></td>
                                <td><?= $announce['titre'] ?></td>
                                <td>
                                    <?= $announce['categorie_titre'] ?>

                                </td>
                                <td>
                                    <form  method="post">
                                        <input type="hidden" name="announceID" value="<?= $announce['id_annonce'] ?>" >
                                        <button class="btn btn-danger w-100 my-1"><i class="fas fa-trash"></i></button>
                                    </form>
                                    <a class="btn btn-secondary w-100 my-1" href="/?announce=<?= $announce['id_annonce'] ?>"><i class="fas fa-eye text-white"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
<?php
require_once __DIR__ . "/include/footer.php";

?>
