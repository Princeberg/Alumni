<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Mot de passe oublié</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/forgot-password.css">

</head>
<body>

<button class="btn custom-btn shadow" data-bs-toggle="modal" data-bs-target="#forgotModal">
    🔐 Mot de passe oublié
</button>


<div class="modal fade show" id="forgotModal" tabindex="-1" style="display:block;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-0">

    
      <div class="modal-header custom-header">
        <h5 class="modal-title">Réinitialisation du mot de passe</h5>
      </div>

     
      <div class="modal-body custom-body p-4">

        <?php if(isset($success) && $success): ?>
            <div class="alert alert-light text-center"><?= $success ?></div>
        <?php endif; ?>

        <?php if(isset($error) && $error): ?>
            <div class="alert alert-light text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="../components/forgot_process.php">

          <div class="mb-3">
            <label>Adresse Email</label>
            <input type="email"
                   name="email"
                   class="form-control form-control-lg rounded-3"
                   placeholder="exemple@email.com"
                   required>
          </div>

          <button type="submit" class="btn custom-btn w-100 py-2 rounded-3">
            Envoyer le lien
          </button>

        </form>

      </div>

      <!-- FOOTER -->
      <div class="modal-footer custom-body border-0 text-center">
        <small style="color:#012587; width:100%;">
          Un lien sécurisé vous sera envoyé.
        </small>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
