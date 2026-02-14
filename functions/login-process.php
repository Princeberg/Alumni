<?php
session_start();
require_once 'db_connect.php';

/* ========= Popup SweetAlert ========= */
function showPopup($type, $message, $redirect) {
    echo "
    <!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <title>Connexion</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: '$type',
                title: 'Connexion',
                html: `$message`,
                confirmButtonText: 'OK',
                confirmButtonColor: '#012587',
                backdrop: 'rgba(0,0,0,0.45)'
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
    showPopup('error', 'Accès non autorisé.', '../pages/login.php');
}

/* ========= Données ========= */
$email    = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    showPopup('error', 'Tous les champs sont obligatoires.', '../pages/login.php');
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
    showPopup('error', 'Email ou mot de passe incorrect.', '../pages/login.php');
}

$user = $result->fetch_assoc();

/* ========= Vérification mot de passe ========= */
if (!password_verify($password, $user['password'])) {
    showPopup('error', 'Email ou mot de passe incorrect.', '../pages/login.php');
}

/* ========= Vérification statut ========= */
switch ($user['statut_id']) {
    case 1:
        showPopup(
            'warning',
            '⏳ Votre compte est en attente de validation.<br>Veuillez patienter.',
            '../pages/login.php'
        );
        break;

    case 3:
        showPopup(
            'error',
            '❌ Votre demande de création de compte a été rejetée.',
            '../pages/login.php'
        );
        break;

    case 4:
        showPopup(
            'error',
            '🚫 Votre compte a été bloqué.<br>Contactez l’administration.',
            '../pages/login.php'
        );
        break;

    case 2:
        // OK
        break;

    default:
        showPopup('error', 'Statut de compte inconnu.', '../pages/login.php');
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
        showPopup('error', 'Type de compte invalide.', '../pages/login.php');
}


showPopup(
    'success',
    'Bienvenue <b>' . htmlspecialchars($user['fullname']) . '</b> 👋',
    $redirect
);

$stmt->close();
$conn->close();
