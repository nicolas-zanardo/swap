<?php
if (!isAdmin()) {
    header('location:/');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>SWAP - admin</title>
    <link href="css/styles.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/datatable.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"
            crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="index.php">SWAP</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <!--            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />-->
            <!--            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>-->
        </div>
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
               aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="/"><i class="fas fa-globe-americas me-1"></i>Web Site</a></li>
                <li>
                    <hr class="dropdown-divider"/>
                </li>
                <li><button type="button" class="dropdown-item" href="#" id="logout">Logout</button></li>
            </ul>
        </li>
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Core</div>
                    <a class="nav-link" href="index.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    <div class="sb-sidenav-menu-heading">USER</div>
                    <a class="nav-link" href="users.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        Users
                    </a>
                    <div class="sb-sidenav-menu-heading">Annonces</div>
                    <a class="nav-link" href="admin-annonces.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-bullhorn"></i></div>
                        Annonces
                    </a>
                    <a class="nav-link" href="admin-annonces-category.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-list-ol"></i></div>
                        Catégories
                    </a>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as:</div>
                <?php if ($_SESSION['user']) : ?>
                    <?= $_SESSION['user']['pseudo'] ?>
                <?php else : ?>
                    Vous devez vous connecter
                <?php endif; ?>
            </div>
        </nav>
    </div>

