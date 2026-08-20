<?php
session_start();
require_once 'db_connect.php';

/* ========= Popup SweetAlert ========= */
function showPopup($type, $message, $redirect) {
    // Association des couleurs d'icônes
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
        <title>Connexion</title>
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
                title: 'Connexion',
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

/* ========= Sécurité ========= */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    showPopup('error', 'Accès non autorisé.', '../login.html');
}

/* ========= Données ========= */
$email    = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    showPopup('error', 'Tous les champs sont obligatoires.', '../login.html');
}

/* ========= Vérification email ========= */
$stmt = $conn->prepare("
    SELECT id, fullname, password, statut_id, account_type 
    FROM users 
    WHERE email = ?
");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    showPopup('error', 'Email ou mot de passe incorrect.', '../login.html');
}

$user = $result->fetch_assoc();
if (!password_verify($password, $user['password'])) {
    showPopup('error', 'Email ou mot de passe incorrect.', '../login.html');
}

switch ($user['statut_id']) {
    case 1:
        showPopup(
            'warning',
            'Votre compte est en attente de validation.<br>Veuillez patienter.',
            '../login.html'
        );
        break;

    case 3:
        showPopup(
            'error',
            'Votre demande de création de compte a été rejetée.',
            '../login.html'
        );
        break;

    case 4:
        showPopup(
            'error',
            'Votre compte a été bloqué.<br>Contactez l’administration.',
            '../login.html'
        );
        break;

    case 2:
        // OK
        break;

    default:
        showPopup('error', 'Statut de compte inconnu.', '../login.html');
}

$_SESSION['user_id']      = $user['id'];
$_SESSION['account_type'] = $user['account_type'];

switch ($user['account_type']) {
    case 'student':
        $redirect = '../pages/Student/index.php';
        break;

    case 'alumni':
        $redirect = '../pages/Alumni/index.php';
        break;

    case 'admin':
        $redirect = '../pages/Admin/index.php';
        break;

    default:
        showPopup('error', 'Type de compte invalide.', '../login.html');
}

showPopup(
    'success',
    'Bienvenue <b>' . htmlspecialchars($user['fullname']) . '</b>',
    $redirect
);

$stmt->close();
$conn->close();