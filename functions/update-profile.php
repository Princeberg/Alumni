<?php
session_start();
require_once 'db_connect.php';

function showPopup($type, $message, $redirect) {
    $iconColors = [
        'success' => '#10b981',
        'error'   => '#ef4444',
        'warning' => '#f59e0b',
        'info'    => '#012587'
    ];
    $iconColor = $iconColors[$type] ?? '#012587';

    echo "
    <!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Mise à jour du profil</title>
        <link rel='icon' type='image/x-icon' href='../src/logo.png'> 
        
        <!-- Google Fonts & SweetAlert2 -->
        <link rel='preconnect' href='https://fonts.googleapis.com'>
        <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
        <link href='https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap' rel='stylesheet'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: #ffcc00;
            }
            .custom-swal-popup {
                border-radius: 20px !important;
                padding: 2rem !important;
                font-family: 'Plus Jakarta Sans', sans-serif !important;
                border: 2px solid rgba(1, 37, 135, 0.1);
            }
            .custom-swal-title {
                color: #012587 !important;
                font-weight: 800 !important;
                font-size: 1.5rem !important;
            }
            .custom-swal-html {
                color: #333333 !important;
                font-size: 0.95rem !important;
                font-weight: 500 !important;
                line-height: 1.5 !important;
            }
            .custom-swal-button {
                background-color: #012587 !important;
                color: #ffcc00 !important;
                font-weight: 700 !important;
                font-size: 0.9rem !important;
                text-transform: uppercase !important;
                border-radius: 8px !important;
                padding: 12px 30px !important;
                box-shadow: none !important;
            }
            .custom-swal-button:hover {
                background-color: #011f70 !important;
            }
        </style>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: '$type',
                iconColor: '$iconColor',
                title: 'Profil',
                html: `$message`,
                confirmButtonText: 'D\'ACCORD',
                backdrop: 'rgba(1, 37, 135, 0.4)',
                customClass: {
                    popup: 'custom-swal-popup',
                    title: 'custom-swal-title',
                    htmlContainer: 'custom-swal-html',
                    confirmButton: 'custom-swal-button'
                }
            }).then(() => {
                window.location.href = '$redirect';
            });
        </script>
    </body>
    </html>";
    exit();
}

/* ========= Sécurité méthode & authentification ========= */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    showPopup('error', 'Accès non autorisé.', '../pages/Student/index.php');
}

if (!isset($_SESSION['user_id'])) {
    showPopup('error', 'Veuillez vous connecter pour modifier votre profil.', '../pages/login.php');
}

$user_id  = $_SESSION['user_id'];
$fullname = trim($_POST['fullname'] ?? '');
$whatsapp = trim($_POST['whatsapp'] ?? '');

/* ========= Validations ========= */
if (empty($fullname) || empty($whatsapp)) {
    showPopup('warning', 'Tous les champs sont obligatoires.', '../pages/Student/index.php');
}

/* ========= Mise à jour dans la base de données ========= */
$stmt = $conn->prepare("UPDATE users SET fullname = ?, whatsapp = ? WHERE id = ?");
$stmt->bind_param("ssi", $fullname, $whatsapp, $user_id);

if ($stmt->execute()) {
    showPopup('success', 'Vos informations ont été mises à jour avec succès.', '../pages/Student/index.php');
} else {
    error_log($stmt->error);
    showPopup('error', 'Une erreur est survenue lors de la mise à jour.', '../pages/Student/index.php');
}

$stmt->close();
$conn->close();