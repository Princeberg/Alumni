<?php
session_start();
require_once 'db_connect.php';

function showPopup($type, $message, $redirect) {
    // Mapping des couleurs des icônes selon le type d'alerte
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
        <title>Inscription</title>
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
                title: 'Notification',
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

/* ========= Sécurité méthode ========= */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    showPopup('error', 'Accès non autorisé.', '../signup.html');
}

/* ========= Récupération ========= */
$fullname     = trim($_POST['fullname'] ?? '');

// Gestion de la date si envoyée via les 3 champs (Jour, Mois, Année) ou via l'input date classique
if (isset($_POST['birth_year'], $_POST['birth_month'], $_POST['birth_day'])) {
    $birthdate = sprintf('%04d-%02d-%02d', $_POST['birth_year'], $_POST['birth_month'], $_POST['birth_day']);
} else {
    $birthdate = trim($_POST['birthdate'] ?? '');
}

$gender       = trim($_POST['gender'] ?? '');
$whatsapp     = trim($_POST['whatsapp'] ?? '');
$faculty      = trim($_POST['faculty'] ?? '');
$account_type = trim($_POST['account_type'] ?? '');
$email        = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$password     = $_POST['password'] ?? '';

$created_at = date('Y-m-d H:i:s');
$statut_id  = 1;

$errors = [];

/* ========= Validations ========= */
if (
    empty($fullname) || empty($birthdate) || empty($gender) ||
    empty($whatsapp) || empty($faculty) || empty($account_type) ||
    empty($email) || empty($password)
) {
    $errors[] = "Tous les champs sont obligatoires.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Adresse email invalide.";
}

if (!in_array($gender, ['male', 'female'])) {
    $errors[] = "Genre invalide.";
}

if (!in_array($account_type, ['student', 'alumni'])) {
    $errors[] = "Type de compte invalide.";
}

/* ========= Validation âge (minimum 18 ans) ========= */
if (!empty($birthdate)) {
    try {
        $birthDateObj = new DateTime($birthdate);
        $today = new DateTime();
        $age = $today->diff($birthDateObj)->y;

        if ($age < 18) {
            $errors[] = "Vous devez avoir au moins 18 ans pour créer un compte.";
        }
    } catch (Exception $e) {
        $errors[] = "Date de naissance invalide.";
    }
}

if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
    $errors[] = "Le mot de passe doit contenir au moins 8 caractères, incluant une majuscule, une minuscule, un chiffre et un caractère spécial.";
}

if (!empty($errors)) {
    showPopup('error', implode('<br>', $errors), '../signup.html');
}

/* ========= Vérification doublon email ========= */
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    showPopup('warning', 'Cet email est déjà utilisé.', '../signup.html');
}
$check->close();

/* ========= Insertion de l'utilisateur ========= */
$password_hash = password_hash($password, PASSWORD_DEFAULT);

$insert = $conn->prepare("
    INSERT INTO users 
    (fullname, birthdate, gender, whatsapp, faculty, account_type, email, password, created_at, statut_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$insert->bind_param(
    "sssssssssi",
    $fullname,
    $birthdate,
    $gender,
    $whatsapp,
    $faculty,
    $account_type,
    $email,
    $password_hash,
    $created_at,
    $statut_id
);

if ($insert->execute()) {
    showPopup('success', 'Votre compte a été créé avec succès !', '../login.html');
} else {
    error_log($insert->error);
    showPopup('error', 'Erreur lors de la création du compte.', '../signup.html');
}

$insert.close();
$conn->close();