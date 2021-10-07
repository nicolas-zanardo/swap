<nav class="navbar navbar-expand navbar-dark bg-nav-public">
    <div class="container-fluid d-flex justify-content-between">
        <a class="navbar-brand" href="/">
            <img src="/public/images/site/logo-swap.png" alt="swap" width="40px">
            wap
        </a>
        <ul class="navbar-nav">
            <?php if (!isConnected()) : ?>
                <li class="nav-item">
                    <a class="btn-login" data-bs-toggle="modal" href="#modalAuth" role="button"><i class="fas fa-user"></i></a>
                </li>
            <?php endif; ?>
            <?php if (isAdmin()) : ?>
                <li class="nav-item">
                    <a class="nav-link d-flex flex-nowrap align-items-center active" aria-current="page" href="/admin/index.php">
                        <i class="fas fa-user-shield"></i>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (isConnected()) : ?>
                <li class="nav-item">
                    <a class="nav-link d-flex flex-nowrap align-items-center active" aria-current="page" href="/profil.php">
                        <i class="fas fa-user-circle"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex flex-nowrap align-items-center active" aria-current="page" id="logout">
                        <i class="fas fa-power-off"></i></a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container page">
    <div class="message-flash">
        <?php if (!empty(show_flash())) : ?>
            <div class="row justify-content-center fade show">
                <div class="col">
                    <?php echo show_flash('reset'); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>


