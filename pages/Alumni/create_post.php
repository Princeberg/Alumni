Il y a **deux problèmes distincts** dans votre fichier `create_post.php` actuel qui expliquent les mauvaises redirections et les bugs potentiels :

1. **Faute de frappe sur le nom de fichier** : Dans vos redirections d'erreur et par défaut, vous avez écrit `Location:publications.php` (avec un **s**) au lieu de `publication.php` (sans **s**).
2. **Variable indéfinie** : La ligne `header("Location: " . $redirect_page );` utilise `$redirect_page` qui n'est définie nulle part au-dessus dans votre code.

Puisque `create_post.php` et `publication.php` sont situés dans le même dossier, il faut utiliser directement `publication.php`.

Voici le code `create_post.php` entièrement corrigé :

```php
<?php
session_start();
require_once '../../functions/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_post'])) {
    
    $user_id     = $_SESSION['user_id'] ?? null; 
    $title       = trim($_POST['title'] ?? '');
    $type        = trim($_POST['type'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $lieu        = !empty(trim($_POST['lieu'] ?? ''))  ? trim($_POST['lieu'])  : null;
    $lien        = !empty(trim($_POST['lien'] ?? ''))  ? trim($_POST['lien'])  : null;
    $date        = !empty(trim($_POST['date'] ?? ''))  ? trim($_POST['date'])  : null;
    $heure       = !empty(trim($_POST['heure'] ?? '')) ? trim($_POST['heure']) : null;
    
    $errors = [];
    
    if (empty($user_id)) {
        $errors[] = "Vous devez être connecté pour pouvoir publier.";
    }

    if (empty($title)) {
        $errors[] = "Le titre est obligatoire.";
    }
    
    if (empty($type)) {
        $errors[] = "Le type est obligatoire.";
    }
    
    if (empty($description)) {
        $errors[] = "La description est obligatoire.";
    }
    
    if ($lien !== null) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $lien)) {
            $lien = "https://" . $lien;
        }

        if (!filter_var($lien, FILTER_VALIDATE_URL)) {
            $errors[] = "Le lien fourni n'est pas une URL valide.";
        }
    }
    
    if (empty($errors)) {
        
        $sql = "INSERT INTO posts (user_id, title, type, description, date, heure, lieu, lien, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            
            $stmt->bind_param("isssssss", $user_id, $title, $type, $description, $date, $heure, $lieu, $lien);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Publication ajoutée avec succès !";
                header("Location: index.php");
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
        header("Location: index.php");
        exit();
    }
    
} else {
    header("Location: index.php");
    exit();
}
?>

```