<?php
if (!isset($conn)) {
    require_once '../../functions/db_connect.php';
}
require_once 'session.php';

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    
    <!-- Google Fonts Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/admin.css">
    
   
</head>
<body>

<nav class="navbar navbar-expand-lg admin-nav py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
            <span class="admin-brand-badge">Admin</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-2 my-3 my-lg-0">
                <li class="nav-item">
                    <a class="nav-link nav-link-custom <?= ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
                        Tableau de bord
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom <?= ($current_page == 'users.php') ? 'active' : ''; ?>" href="users.php">
                        Utilisateurs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom <?= ($current_page == 'posts.php') ? 'active' : ''; ?>" href="posts.php">
                        Publications
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2 ms-lg-4">
                <a href="../../functions/logout.php" class="btn btn-primary px-3 py-2-5" onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?');">
                    Déconnexion
                </a>
            </div>
        </div>
    </div>
</nav>