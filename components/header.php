<!DOCTYPE html>
<html lang="fr">
<head>
    <title>ALUMNI - Université de N'djaména</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/x-icon" href="src/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg py-3 fade-down">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="src/logo.png" alt="Logo ALUMNI" class="navbar-logo">
        </a>  

        <!-- Bouton Hamburger -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu Coulissant à Droite -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Bouton fermer visible uniquement sur mobile -->
            <div class="d-lg-none d-flex justify-content-end w-100">
                <button type="button" class="btn-close text-reset shadow-none" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Close"></button>
            </div>

            <ul class="navbar-nav mx-auto">
                <li class="nav-item px-2">
                    <a class="nav-link nav-link-custom" href="#courses-section">L'université</a>
                </li>
                <li class="nav-item px-2">
                    <a class="nav-link nav-link-custom " href="#programs-section">Nos Objectifs</a>
                </li>
                <li class="nav-item px-2">
                    <a class="nav-link nav-link-custom  "href="#publications">Actualités</a>
                </li>
                <li class="nav-item px-2">
                    <a class="nav-link nav-link-custom  "href="#testimony">Témoignages</a>
                </li>
                  <li class="nav-item px-2">
                    <a class="nav-link nav-link-custom  "href="#Donation">Faire un Don</a>
                </li>

            </ul>
            <div class="d-flex">
                <a href="login.html" class="btn-primary px-4 py-2" style="text-decoration: none;">
                    Se connecter
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- HERO SECTION -->
<section class="hero-section bg-light-custom py-5">
    <div class="container py-4">
        <!-- Text Grid avec Fade Up en décalé (Staggered) -->
        <div class="row align-items-center mb-5">
            <div class="col-lg-7 pe-lg-5 mb-4 mb-lg-0 fade-up fade-delay-1">
                <h1 class="hero-title display-3 mb-0">
                   ALUMNIS
                </h1>
            </div>
            <div class="col-lg-5 ps-lg-4">
                <p class="mb-4 hero-desc fade-left fade-delay-2 ">
                    Rejoignez le réseau officiel des alumnis. </br> <strong> Ensemble, construisons l'avenir. </strong>
                </p>
                <a href="signup.html" class="btn btn-secondary px-4 py-2-5 d-inline-flex align-items-center gap-2"> <i class="fa-solid fa-arrow-right"></i>
    Rejoindre 
</a>
            </div>
        </div>

        <!-- Banner Image avec Fade Up -->
        <div class="row fade-down fade-delay-3">
            <div class="col-12">
                <div class="hero-image-wrapper">
                    <img src="https://i.pinimg.com/1200x/90/06/5f/90065f36463d8ea4be342c38cc120d55.jpg" alt="Campus" class="img-fluid w-100 hero-banner-img">
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>