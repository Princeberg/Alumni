<?php
session_start();
require_once 'db_connect.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_SESSION['user_id']);
    $fullname = trim($_POST['fullname'] ?? '');
    $whatsapp = trim($_POST['whatsapp'] ?? '');
    $mentorat_status = isset($_POST['mentorat_status']) && $_POST['mentorat_status'] == 1 ? 1 : 0;
    if (!empty($fullname) && $user_id > 0) {
        
        // Préparation de la requête UPDATE
        $sql = "UPDATE users 
                SET fullname = ?, 
                    whatsapp = ?, 
                    mentorat = ? 
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("ssii", $fullname, $whatsapp, $mentorat_status, $user_id);
            
            if ($stmt->execute()) {
                // Mise à jour éventuelle des variables de session
                $_SESSION['fullname'] = $fullname;
                $_SESSION['success_msg'] = "Profil mis à jour avec succès.";
            } else {
                $_SESSION['error_msg'] = "Erreur lors de la mise à jour.";
            }
            $stmt->close();
        } else {
            $_SESSION['error_msg'] = "Erreur de préparation de la requête SQL.";
        }
    } else {
        $_SESSION['error_msg'] = "Veuillez remplir tous les champs obligatoires.";
    }

    $conn->close();
    
    // Redirection vers la page précédente
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    // Si accès direct sans POST
    header("Location: index.php");
    exit();
}