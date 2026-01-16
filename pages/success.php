<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/x-icon" href="src/logo.png">  
    
  <title>Inscription Réussie</title>

  <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/jquery-ui.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">
  <link rel="stylesheet" href="css/jquery.fancybox.min.css">
  <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
  <link rel="stylesheet" href="css/aos.css">
  <link rel="stylesheet" href="css/style.css"> <style>
      body {
          background-color: #f8f9fa;
          height: 100vh;
          display: flex;
          align-items: center;
          justify-content: center;
      }
      .success-card {
          background: white;
          padding: 50px 30px;
          border-radius: 10px;
          box-shadow: 0 10px 30px rgba(0,0,0,0.1);
          text-align: center;
          max-width: 600px;
          width: 90%;
      }
      .icon-box {
          margin-bottom: 30px;
      }
      .icon-box i {
          font-size: 80px;
          color: #28a745; /* Vert succès */
      }
      .btn-primary-custom {
          background-color: #012587;
          border-color: #012587;
          color: white;
          padding: 12px 30px;
          border-radius: 5px;
          text-decoration: none;
          transition: all 0.3s ease;
      }
      .btn-primary-custom:hover {
          background-color: #00195c;
          border-color: #00195c;
          color: white;
      }
      h2 {
          color: #012587;
          font-weight: 900;
      }
  </style>
</head>
<body>

  <div class="success-card" data-aos="fade-up" data-aos-duration="1000">
    <div class="icon-box">
      <i class="fas fa-check-circle"></i>
    </div>
    
    <h2>Félicitations !</h2>
    <p class="lead mt-3">Votre compte a été créé avec succès.</p>
    
    <hr class="my-4">
    
    <p class="text-muted mb-4">
      Vous recevrez une notification de confirmation sur votre <strong>WhatsApp</strong> ou par <strong>Email</strong> très prochainement.
      <br><br>
      <span style="color: #e63946;">⚠️ Une fois la confirmation reçue, vous pourrez vous connecter.</span>
    </p>

    <div class="mt-5">
      <a href="login.php" class="btn-primary-custom">
        <i class="fas fa-sign-in-alt mr-2"></i> Retour à la connexion
      </a>
      <br><br>
      <a href="index.php" style="color: #6c757d; font-size: 14px;">Retour à l'accueil</a>
    </div>
  </div>

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/aos.js"></script>
  <script>
    AOS.init();
  </script>
</body>
</html>