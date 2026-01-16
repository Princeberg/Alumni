<?php
session_start();
require_once 'db_connect.php';

/* ========= Fonction d'affichage popup ========= */
function showPopup($type, $message, $redirect) {
    echo "
    <!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <title>Inscription</title>
  <link rel='icon' type='image/x-icon' href='../src/logo.png'> 
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: '$type',
                title: 'Message',
                html: `$message`,
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6',
                backdrop: 'rgba(0,0,0,0.4)'
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
    showPopup('error', 'Accès non autorisé.', '../pages/signup.php');
}

/* ========= Récupération ========= */
$fullname     = trim($_POST['fullname'] ?? '');
$birthdate    = trim($_POST['birthdate'] ?? '');
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

if (!empty($errors)) {
    showPopup('error', implode('<br>', $errors), '../pages/signup.php');
}

/* ========= Vérification email ========= */
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    showPopup('warning', 'Cet email est déjà utilisé.', '../pages/signup.php');
}
$check->close();

/* ========= Insertion ========= */
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
    showPopup('success', 'Compte créé avec succès 🎉', '../pages/success.php');
} else {
    error_log($insert->error);
    showPopup('error', 'Erreur lors de la création du compte.', '../pages/signup.php');
}

$insert->close();
$conn->close();
