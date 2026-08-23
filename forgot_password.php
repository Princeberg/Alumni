<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/x-icon" href="src/logo.png">  
  <title>Mot de passe oublié</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="site-wrap">
  <section class="login-section fade-up">
    <!-- Flèche pour retourner au login -->
    <a href="login.html" class="back-arrow" aria-label="Retour à la page de connexion">
      <i class="fa-solid fa-arrow-left"></i>
    </a>

    <div class="hero-box">

      <!-- GAUCHE : TEXTE D'EXPLICATION -->
      <div class="hero-left">
        <h1>Mot de passe oublié ?</h1>
        <p>Ne vous inquiétez pas, saisissez votre adresse email et nous vous enverrons un lien sécurisé pour réinitialiser votre accès.</p>
      </div>

      <!-- DROITE : FORMULAIRE -->
      <div class="hero-right">
        <form id="forgot-card" class="login-card" action="components/forgot_process.php" method="POST">

          <div class="form-header">
            <h2>Réinitialisation</h2>
          </div>

          <!-- Messages d'alerte PHP -->
          <?php if(isset($success) && $success): ?>
            <div class="alert alert-success" style="margin-bottom: 20px; padding: 12px; border-radius: 8px; background-color: #d4edda; color: #155724; font-size: 0.9rem;">
              <?= htmlspecialchars($success) ?>
            </div>
          <?php endif; ?>

          <?php if(isset($error) && $error): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px; padding: 12px; border-radius: 8px; background-color: #f8d7da; color: #721c24; font-size: 0.9rem;">
              <?= htmlspecialchars($error) ?>
            </div>
          <?php endif; ?>

          <div class="form-group">
            <label for="email">Adresse électronique</label>
            <div class="input-wrapper">
              <i class="fa-regular fa-envelope input-icon"></i>
              <input type="email" id="email" name="email" placeholder="votre.email@domaine.com" required>
            </div>
          </div>

          <button type="submit" class="btn-submit">
            <span> Réinitialiser </span>
            <i class="fa-solid fa-paper-plane"></i>
          </button>

          <div class="login-footer">
            <p>Vous vous en souvenez ? <a href="login.html" class="signup-link">Se connecter</a></p>
          </div>

        </form>
      </div>

    </div>
  </section>
</div>

</body>
</html>