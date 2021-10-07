<?php
require_once __DIR__ . "/../include/init.php";
require_once __DIR__ . "/../database/models/UserDB.php";
require_once __DIR__ . "/../database/models/NotesDB.php";
$noteDB = new NotesDB();
$usersDB = new UserDB();
$users = $usersDB->fetchAll();

require_once __DIR__ . "/include/header.php";
?>
    <div id="layoutSidenav_content">
    <main>
    <div class="container-fluid px-4">
    <h1 class="mt-4">Utilisateurs</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Utilisateurs</li>
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
            Utilisateurs
        </div>
        <div class="card-body">
            <table id="datatablesUsers">
                <thead>
                <tr>
                    <th>Pseudo</th>
                    <th>email</th>
                    <th>status</th>
                    <th><i class="fas fa-thumbs-up mx-2"></i>%</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Pseudo</th>
                    <th>email</th>
                    <th>status</th>
                    <th><i class="fas fa-thumbs-up mx-2"></i>%</th>
                </tr>
                </tfoot>
                <tbody>
                <?php foreach ($users as $user) : ?>

                <?php
                    if( $noteDB->sumNoteByIDMembre($user['id_membre']) === 0) {
                        $noteMoyenne = "0%";
                    }  elseif ($noteDB->countNoteByUser($user['id_membre']) === 0 ) {
                        $noteMoyenne = "Aucune evaluation";
                    } else {
                        $noteMoyenne = round(($noteDB->sumNoteByIDMembre($user['id_membre']) / $noteDB->countNoteByUser($user['id_membre']) * 10 * 2) , 2) . "%";
                    }
                    ?>
                    <tr>
                        <td><?= $user['pseudo'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td>
                            <?php if ((int)$user['status'] === 0) : ?>
                                <span class="text-success">User</span>
                            <?php else  : ?>
                                <span class="text-warning">ADMIN</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div>Votre Score : <span><?= $noteMoyenne ?></span></div>
                                </div>
                                <div class="d-flex">
                                    <a class="btn btn-info mx-1 text-white" href="user-edit.php?id=<?= $user['id_membre']?>"><i
                                                class="fas fa-user-edit"></i></a>
                                    <button type="button" class="btn btn-danger mx-1"
                                            onclick="delUser(<?= $user['id_membre'] ?>)"><i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
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