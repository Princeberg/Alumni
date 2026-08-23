<?php
require '../functions/db_connect.php';

$error = '';
$token = $_GET['token'] ?? null;

if ($token) {
    // Vérification du jeton dans la base de données
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['confirm_password'] ?? '';

            if (empty($password) || empty($confirm)) {
                $error = "Veuillez remplir tous les champs.";
            } elseif ($password !== $confirm) {
                $error = "Les mots de passe ne correspondent pas.";
            } else {
                $newPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE reset_token=?");
                $stmt->bind_param("ss", $newPassword, $token);
                $stmt->execute();

                echo "<script>
                        alert('Mot de passe mis à jour avec succès.');
                        window.location.href = 'login.html';
                      </script>";
                exit();
            }
        }
    } else {
        $error = "Lien invalide ou expiré.";
    }
} else {
    $error = "Aucun jeton fourni.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/x-icon" href="src/logo.png">  
  <title>Nouveau mot de passe</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="site-wrap">
  <section class="login-section fade-up">
    <!-- Flèche pour retourner à la connexion -->
    <a href="login.html" class="back-arrow" aria-label="Retour à la page de connexion">
      <i class="fa-solid fa-arrow-left"></i>
    </a>

    <div class="hero-box">

      <!-- GAUCHE : TEXTE EXPLICATIF -->
      <div class="hero-left">
        <h1>Nouveau mot de passe</h1>
        <p>Saisissez votre nouveau mot de passe ci-contre. Assurez-vous d'utiliser un mot de passe robuste pour sécuriser votre compte.</p>
      </div>

      <!-- DROITE : FORMULAIRE -->
      <div class="hero-right">
        <form id="reset-card" class="login-card" method="POST">

          <div class="form-header">
            <h2>Changement de mot de passe</h2>
          </div>

          <!-- Affichage de l'erreur si le token est invalide ou les données incorrectes -->
          <?php if (!empty($error)) : ?>
            <div class="alert alert-danger" style="margin-bottom: 20px; padding: 12px; border-radius: 8px; background-color: #f8d7da; color: #721c24; font-size: 0.9rem;">
              <?= htmlspecialchars($error); ?>
            </div>
          <?php endif; ?>

          <!-- Si le jeton est valide, on affiche le formulaire -->
          <?php if ($token && empty($result->num_rows === 0)) : ?>

            <!-- CHAMP 1 : NOUVEAU MOT DE PASSE -->
            <div class="form-group">
              <label for="password">Nouveau mot de passe</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-lock input-icon"></i>
                <input type="password" id="password" name="password" placeholder="Votre nouveau mot de passe" required>
                <i class="fa-regular fa-eye toggle-password" id="togglePassword" aria-label="Afficher ou masquer"></i>
              </div>
            </div>

            <!-- CHAMP 2 : CONFIRMATION -->
            <div class="form-group">
              <label for="confirm_password">Confirmer le mot de passe</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-lock input-icon"></i>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmez le mot de passe" required>
                <i class="fa-regular fa-eye toggle-password" id="toggleConfirmPassword" aria-label="Afficher ou masquer"></i>
              </div>
            </div>

            <button type="submit" class="btn-submit">
              <span>Changer le mot de passe</span>
              <i class="fa-solid fa-check"></i>
            </button>

          <?php else: ?>
            <div class="login-footer" style="text-align: center; margin-top: 15px;">
              <a href="forgot_password.php" class="signup-link">Demander un nouveau lien</a>
            </div>
          <?php endif; ?>

          <div class="login-footer">
            <p>Vous avez retrouvé vos accès ? <a href="login.html" class="signup-link">Se connecter</a></p>
          </div>

        </form>
      </div>

    </div>
  </section>
</div>

<!-- Script d'affichage/masquage des mots de passe -->
<script>
  function setupTogglePassword(toggleId, inputId) {
    const toggleBtn = document.querySelector(toggleId);
    const inputField = document.querySelector(inputId);

    if (toggleBtn && inputField) {
      toggleBtn.addEventListener('click', function () {
        const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
        inputField.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
      });
    }
  }

  setupTogglePassword('#togglePassword', '#password');
  setupTogglePassword('#toggleConfirmPassword', '#confirm_password');
</script>

</body>
</html>