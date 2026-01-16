<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/x-icon" href="../src/logo.png">  
    
  <title> Inscription </title>

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
  <link rel="stylesheet" href="../css/signup-student.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="site-wrap">
  <div class="signup-container" data-aos="fade-up" data-aos-delay="300">
    
    <!-- Flèche de retour -->
    <a href="../index.php" class="back-arrow" aria-label="Retour à la page d'accueil">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M19 12H5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <path d="M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </a>

    <!-- En-tête du formulaire -->
    <div class="signup-header">
      <h2>Nouveau Compte</h2>
      <p>Alumnis de l'Université de N'Djamena</p>
    </div>
    
    <!-- Indicateur de progression -->
    <div class="step-indicator">
      <div class="step active" id="step1-indicator">
        <div class="step-circle">1</div>
        <div class="step-label">Informations<br>Personnelles</div>
      </div>
      <div class="step-line"></div>
      <div class="step" id="step2-indicator">
        <div class="step-circle">2</div>
        <div class="step-label">Informations<br>Académiques</div>
      </div>
      <div class="step-line"></div>
      <div class="step" id="step3-indicator">
        <div class="step-circle">3</div>
        <div class="step-label">Création<br>Compte</div>
      </div>
    </div>
    
    <!-- Formulaire en étapes -->
    <form class="signup-form" id="signup-form" action="../functions/signup-process.php" method="POST">
      
      <!-- Étape 1: Informations Personnelles -->
      <div class="form-step active" id="step1">
        <h3>Étape 1: Informations Personnelles</h3>
        
        <div class="form-row">
          <div class="form-group">
            <label for="lastname">Nom(s) et Prénom(s) *</label>
            <input type="text" id="fullname" name="fullname" placeholder="Mouhamed Jean " required>
            <div class="error-message" id="lastname-error"></div>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group" >
            <label for="birthdate">Date de Naissance *</label>
            <input type="date" id="birthdate" name="birthdate" required>
            <div class="error-message" id="birthdate-error"></div>
          </div>
          
          <div class="form-group">
            <label>Genre *</label>
            <div class="radio-group" >
              <label class="radio-option">
                <input type="radio" name="gender" value="male" required> Masculin
              </label>
              <label class="radio-option">
                <input type="radio" name="gender" value="female"> Féminin
              </label>
            </div>
            <div class="error-message" id="gender-error"></div>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group full-width">
            <label for="whatsapp">Contact WhatsApp *</label>
            <input type="text" id="whatsapp" name="whatsapp" placeholder="Ex: +235 XX XX XX XX" required>
            <div class="error-message" id="whatsapp-error"></div>
          </div>
        </div>
        
        <div class="form-actions">
          <div></div>
          <button type="button" class="btn btn-next" id="next-to-step2">Suivant</button>
        </div>
      </div>
      
      <!-- Étape 2: Informations Académiques -->
      <div class="form-step" id="step2">
  <h3>Étape 2: Informations Académiques</h3>
  
  <div class="form-row">
    <div class="form-group full-width">
      <label for="faculty">Faculté *</label>
      <select id="faculty" name="faculty" style="background-color: #012587;" required>
        <option value="Faculté des Sciences Juridiques et Politiques">Faculté des Sciences Juridiques et Politiques</option>
        <option value="Faculté des Sciences Economiques et de Gestion">Faculté des Sciences Economiques et de Gestion</option>
        <option value="Faculté des Sciences de la Santé Humaine">Faculté des Sciences de la Santé Humaine</option>
        <option value="Faculté des Sciences Exactes et Appliquées">Faculté des Sciences Exactes et Appliquées</option>
        <option value="Faculté des Sciences Humaines et Sociales">Faculté des Sciences Humaines et Sociales</option>
        <option value="Faculté des Sciences de l’Education">Faculté des Sciences de l’Education</option>
        <option value="Faculté des Langues, Lettres, Arts et Communication">Faculté des Langues, Lettres, Arts et Communication</option>
      </select>
      <div class="error-message" id="faculty-error"></div>
    </div>
  </div>
  
  <div class="form-row">
    <div class="form-group">
      <label>Type de compte *</label>
      <div class="radio-group">
        <label class="radio-option">
          <input type="radio" name="account_type" value="student" required> Étudiant
        </label>
        <label class="radio-option">
          <input type="radio" name="account_type" value="alumni" required> Alumni
        </label>
      </div>
      <div class="error-message" id="account-type-error"></div>
    </div>
  </div>
  
  <div class="form-actions">
    <button type="button" class="btn btn-prev" id="prev-to-step1">Précédent</button>
    <button type="button" class="btn btn-next" id="next-to-step3">Suivant</button>
  </div>
</div>

      <!-- Étape 3: Création du Compte -->
      <div class="form-step" id="step3">
        <h3>Étape 3: Création du Compte</h3>
        
        <div class="form-row">
          <div class="form-group full-width">
            <label for="email">Adresse Email *</label>
            <input type="email" id="email" name="email" placeholder="ex: etudiant@univ-ndjamena.td" required>
            <div class="error-message" id="email-error"></div>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="password">Mot de passe *</label>
            <input type="password" id="password" name="password" placeholder="********" required>
            <div class="error-message" id="password-error"></div>
          </div>
        </div>
        
        <div class="error-message" id="terms-error"></div>
        
        <div class="form-actions">
          <button type="button" class="btn btn-prev" id="prev-to-step2">Précédent</button>
          <button type="submit" class="btn btn-submit" id="submit-form">S'inscrire</button>
        </div>
      </div>
    </form>
    
    <div class="login-link">
      Déjà inscrit ? <a href="login.php">Connectez-vous ici</a>
    </div>
  </div>
</div>

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
<script src="../js/signup.js" type="module"></script>
</body>
</html>