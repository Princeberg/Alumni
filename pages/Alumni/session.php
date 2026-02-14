<?php
session_start();
require_once '../../functions/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Préparer la requête
$stmt = $conn->prepare("SELECT fullname FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

$fullname = $user['fullname'];
?>
