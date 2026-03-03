<?php
require '../functions/db_connect.php';
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
date_default_timezone_set('Europe/Moscow'); 

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'])) {

    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse email invalide.";
    } else {

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if ($stmt === false) {
            $error = "Erreur serveur.";
        } else {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $token = bin2hex(random_bytes(32));
                $expiresDate = new DateTime();     
                $expires = $expiresDate->format('Y-m-d H:i:s'); 
                $stmtUpdate = $conn->prepare(
                    "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?"
                );
                if ($stmtUpdate === false) {
                    $error = "Erreur lors de la mise à jour du token.";
                } else {
                    $stmtUpdate->bind_param("sss", $token, $expires, $email);
                    $stmtUpdate->execute();

                    $mail = new PHPMailer(true);

                    try {

                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'princebergborja@gmail.com';
                        $mail->Password   = 'cdtw rgwk rgec guem'; 
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = 587;
                        $mail->setFrom('alumniNdjamena@gmail.com', 'Alumni Ndjamena');
                        $mail->addAddress($email);
                        $mail->isHTML(true);
                        $mail->Subject = "Nouveau mot de passe";
                        $resetLink = "http://localhost/Alumni/pages/reset_password.php?token=" . urlencode($token);

                        $mail->Body = "
                            <h3>Réinitialisation du mot de passe</h3>
                            <p>Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien ci-dessous :</p>
                            <p>
                                <a href='$resetLink'
                                   style=\"display:inline-block;padding:10px 20px;
                                          background-color:#007bff;color:#ffffff;
                                          text-decoration:none;border-radius:5px;\">
                                    Cliquez ici pour réinitialiser votre mot de passe
                                </a>
                            </p>
                            <p><strong>Note :</strong> Ce lien expire dans 1 heure.</p>
                        ";

                        $mail->AltBody = "Pour réinitialiser votre mot de passe, copiez/collez ce lien dans votre navigateur : $resetLink\n\nCe lien expire dans 1 heure.";

                        $mail->send();
                        $success = "Email envoyé avec succès.";

                    } catch (Exception $e) {
                        $error = "Erreur lors de l'envoi de l'email.";
                    }
                }

            } else {
                $error = "Aucun compte trouvé avec cet email.";
            }
        }
    }
}

include '../pages/forgot_password.php';
