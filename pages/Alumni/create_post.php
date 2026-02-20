<?php
session_start();
require_once '../../functions/db_connect.php';
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_messages'] = ["Vous devez être connecté pour publier une annonce."];
    header("Location: ../index.php?page=publications#publications");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_post'])) {
    
    $user_id = $_SESSION['user_id']; 
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $lieu = !empty($_POST['lieu']) ? mysqli_real_escape_string($conn, $_POST['lieu']) : null;
    $lien = !empty($_POST['lien']) ? mysqli_real_escape_string($conn, $_POST['lien']) : null;
    
    
    $date = !empty($_POST['date']) ? mysqli_real_escape_string($conn, $_POST['date']) : null;
    $heure = !empty($_POST['heure']) ? mysqli_real_escape_string($conn, $_POST['heure']) : null;
    
    $errors = [];
    
    if (empty($title)) {
        $errors[] = "Le titre est obligatoire";
    }
    
    if (empty($type)) {
        $errors[] = "Le type est obligatoire";
    }
    
    if (empty($description)) {
        $errors[] = "La description est obligatoire";
    }
    
    
    if (empty($errors)) {
        
        $sql = "INSERT INTO posts (user_id, title, type, description, date, heure, lieu, lien, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
        
            $stmt->bind_param("isssssss", $user_id, $title, $type, $description, $date, $heure, $lieu, $lien);
            
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Publication ajoutée avec succès !";
                header("Location: index.php?page=publications#publications");
                exit();
            } else {
             
                $errors[] = "Erreur lors de l'ajout : " . $stmt->error;
            }
            
            $stmt->close();
        } else {
          
            $errors[] = "Erreur de préparation de la requête : " . $conn->error;
        }
    }

    if (!empty($errors)) {
        $_SESSION['error_messages'] = $errors;
        $_SESSION['form_data'] = $_POST; 
        header("Location: index.php?page=publications#addPostModal");
        exit();
    }
    
} else {
    
    header("Location: index.php?page=publications");
    exit();
}
?>