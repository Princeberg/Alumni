<?php
require '../functions/db_connect.php';

if (isset($_GET['token'])) {

    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $error = '';

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
                        window.location.href = 'login.php';
                      </script>";
                exit();
            }
        }
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="../css/new-pass.css">
            <title>Réinitialiser le mot de passe</title>
           
        </head>
        <body>
            <div class="container">
                <?php if (!empty($error)) : ?>
                    <div class="error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="password">Nouveau mot de passe</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmer le mot de passe</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn">Changer</button>
                </form>
            </div>
        </body>
        </html>
        <?php

    } else {
        echo "Lien invalide ou expiré.";
    }
}
?>
