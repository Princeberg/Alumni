<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/x-icon" href="../src/logo.png">  
    
  <title> Connexion – Université de N'Djamena </title>

  <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">

    <link rel="stylesheet" href="css/jquery.fancybox.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">

    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">

    <link rel="stylesheet" href="../css/aos.css">
  <link rel="stylesheet" href="../css/signup.css">
</head>
<body>


<div class="site-wrap">
<section class="intro-section hero-center login-section" data-aos="fade-left" data-aos-delay="300">

  <!-- Flèche de retour -->
  <a href="../index.php" class="back-arrow" aria-label="Retour à la page précédente">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
      xmlns="http://www.w3.org/2000/svg">
      <path d="M19 12H5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <path d="M12 19L5 12L12 5" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </a>

  <div class="hero-box">

    <!-- LEFT : TEXTE -->
    <div class="hero-left">
      <h1>Connexion à votre compte</h1>
      <p>Connectez-vous avec votre email et mot de passe pour accéder à votre espace étudiant ou Alumni.</p>

      <div class="hero-stats">
        <div class="hero-stat">
          <strong>Étudiants</strong>
          <span>Espace académique</span>
        </div>
        <div class="hero-stat">
          <strong>Alumnis</strong>
          <span>Réseau professionnel</span>
        </div>
      </div>
    </div>

    <!-- RIGHT : FORMULAIRE LOGIN -->
    <div class="hero-right">
      <form id="login-card" class="login-card" action="../functions/login-process.php" method="POST">

        <div class="form-group">
          <label for="email">Adresse électronique</label>
          <input type="email" id="email" name="email" placeholder="Entrez votre Email" required>
        </div>

        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
        </div>

        <button type="submit" class="btn-main btn-full" style="background-color: #012587; color: #ffcc00">
          Connexion
        </button>

        <div class="login-links">
          <a href="forgot-password.html" >Mot de passe oublié ?</a>
         <p> <a href="signup.php" style="text-decoration: underline; color: rgb(206, 5, 5)">Créer un compte </a> </p>
        </div>

      </form>
    </div>

  </div>
</section>
</div>


</body>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
 <script src="../js/jquery-3.3.1.min.js"></script>
  <script src="../js/jquery-migrate-3.0.1.min.js"></script>
  <script src="../js/jquery-ui.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/owl.carousel.min.js"></script>
  <script src="../js/jquery.stellar.min.js"></script>
  <script src="../js/jquery.countdown.min.js"></script>
  <script src="../js/bootstrap-datepicker.min.js"></script>
  <script src="../js/jquery.easing.1.3.js"></script>
  <script src="../js/aos.js"></script>
  <script src="../js/jquery.fancybox.min.js"></script>
  <script src="../js/jquery.sticky.js"></script>
  <script src="../js/main.js"></script>
</html>
