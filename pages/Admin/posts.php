<?php 
include 'header.php'; 
include '../../functions/db_connect.php'; 

if (isset($_POST['create_post'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $date = !empty($_POST['date']) ? "'".mysqli_real_escape_string($conn, $_POST['date'])."'" : "NULL";
    $heure = !empty($_POST['heure']) ? "'".mysqli_real_escape_string($conn, $_POST['heure'])."'" : "NULL";
    $lieu = !empty($_POST['lieu']) ? "'".mysqli_real_escape_string($conn, $_POST['lieu'])."'" : "NULL";
    $lien = !empty($_POST['lien']) ? "'".mysqli_real_escape_string($conn, $_POST['lien'])."'" : "NULL";
    
    // Récupération de l'ID utilisateur de la session (ajustez selon votre gestion de session)
    $user_id = $_SESSION['user_id'] ?? 1; 

    $insert_query = "INSERT INTO posts (user_id, title, type, description, date, heure, lieu, lien, created_at) 
                    VALUES ('$user_id', '$title', '$type', '$description', $date, $heure, $lieu, $lien, NOW())";

    if (mysqli_query($conn, $insert_query)) {
        header("Location: posts.php?msg=created");
        exit();
    }
}

$limit = 10; 
$page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Compter le nombre total d'événements
$total_query = "SELECT COUNT(*) as total FROM posts";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_posts = $total_row['total'];
$total_pages = ceil($total_posts / $limit);

// Récupérer les événements pour la page courante
$query = "SELECT p.*, u.fullname, u.email 
          FROM posts p 
          LEFT JOIN users u ON p.user_id = u.id 
          ORDER BY p.created_at DESC 
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Traitement des actions (suppression, modification)
if(isset($_GET['action'])) {
    if($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        mysqli_query($conn, "DELETE FROM posts WHERE id = '$id'");
        header("Location: posts.php?msg=deleted&p=" . $page);
        exit();
    }
    
    if($_GET['action'] == 'update' && isset($_POST['post_id'])) {
        $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
        $lieu = mysqli_real_escape_string($conn, $_POST['lieu']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);
        $heure = mysqli_real_escape_string($conn, $_POST['heure']);
        $lien = mysqli_real_escape_string($conn, $_POST['lien']);
        
        $update_query = "UPDATE posts SET 
                         lieu = '$lieu',
                         date = '$date',
                         heure = '$heure',
                         lien = '$lien'
                         WHERE id = '$post_id'";
        
        if(mysqli_query($conn, $update_query)) {
            header("Location: posts.php?msg=updated&p=" . $page);
            exit();
        }
    }
}

function truncateDescription($description, $words = 15) {
    $words_array = explode(' ', $description);
    if (count($words_array) > $words) {
        return implode(' ', array_slice($words_array, 0, $words)) . '...';
    }
    return $description;
}
?>

<main class="py-5">
    <div class="container py-4">

        <!-- EN-TÊTE PAGE AVEC BOUTON D'AJOUT -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 pb-3 border-bottom border-dark fade-down">
            <div>
                <h1 class="hero-title display-4 mb-2" style="font-size: 48px;">Gestion des publications</h1>
                <p class="hero-desc mb-0">Page <?php echo $page; ?> sur <?php echo max(1, $total_pages); ?></p>
            </div>
            <div class="mt-3 mt-md-0 d-flex gap-2 align-items-center">
                <span class="badge pub-badge-outline fs-6 px-3 py-2">
                    Total : <?php echo $total_posts; ?> événement(s)
                </span>
                <button type="button" class="btn btn-primary px-3 py-2" data-bs-toggle="modal" data-bs-target="#addPostModal">
                    + Ajouter une publication
                </button>
            </div>
        </div>

        <!-- NOTIFICATIONS -->
        <?php if(isset($_GET['msg'])): ?>
            <div class="alert alert-dark border border-dark rounded-3 mb-4 p-3 fade-down">
                <?php if($_GET['msg'] == 'created'): ?>
                    Publication ajoutée avec succès.
                <?php elseif($_GET['msg'] == 'deleted'): ?>
                    Événement supprimé avec succès.
                <?php elseif($_GET['msg'] == 'updated'): ?>
                    Événement mis à jour avec succès.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- TABLEAU DES ÉVÉNEMENTS -->
        <div class="pub-card-outline p-4 fade-up mb-4">
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead>
                        <tr class="border-bottom border-dark">
                            <th class="pb-3 fw-bold">Auteur</th>
                            <th class="pb-3 fw-bold">Titre</th>
                            <th class="pb-3 fw-bold">Description</th>
                            <th class="pb-3 fw-bold">Type</th>
                            <th class="pb-3 fw-bold">Lieu</th>
                            <th class="pb-3 fw-bold">Date & Heure</th>
                            <th class="pb-3 fw-bold">Lien</th>
                            <th class="pb-3 fw-bold">Publication</th>
                            <th class="pb-3 fw-bold text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result && mysqli_num_rows($result) > 0): ?>
                            <?php while($post = mysqli_fetch_assoc($result)): ?>
                            <tr class="border-bottom border-secondary">
                                <td class="py-3 fw-bold"><?php echo htmlspecialchars($post['fullname'] ?? 'Anonyme'); ?></td>
                                <td class="py-3"><?php echo htmlspecialchars($post['title']); ?></td>
                                <td class="py-3 text-muted fs-7" style="max-width: 200px;">
                                    <?php echo htmlspecialchars(truncateDescription($post['description'], 15)); ?>
                                </td>
                                <td class="py-3">
                                    <span class="badge pub-badge-outline">
                                        <?php echo htmlspecialchars($post['type']); ?>
                                    </span>
                                </td>
                                <td class="py-3"><?php echo htmlspecialchars($post['lieu'] ?? '-'); ?></td>
                                <td class="py-3 fs-7">
                                    <?php echo !empty($post['date']) ? date('d/m/Y', strtotime($post['date'])) : '-'; ?><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($post['heure'] ?? ''); ?></small>
                                </td>
                                <td class="py-3">
                                    <?php if(!empty($post['lien'])): ?>
                                        <a href="<?php echo htmlspecialchars($post['lien']); ?>" target="_blank" class="btn btn-secondary btn-sm py-0 px-2 fs-7">
                                            Voir
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 fs-7"><?php echo date('d/m/Y', strtotime($post['created_at'])); ?></td>
                                <td class="py-3 text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <button type="button" class="btn btn-secondary btn-sm px-3" onclick='openEditModal(<?php echo json_encode($post, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
                                            Éditer
                                        </button>
                                        <a href="?action=delete&id=<?php echo $post['id']; ?>&p=<?php echo $page; ?>" 
                                           class="btn btn-primary btn-sm px-3"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
                                            Supprimer
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="py-4 text-center">Aucun événement trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PAGINATION BOOTSTRAP -->
        <?php if($total_pages > 1): ?>
        <nav aria-label="Navigation des pages" class="d-flex justify-content-center mt-4">
            <ul class="pagination gap-1">
                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link btn btn-secondary btn-sm rounded-1" href="?p=<?php echo $page - 1; ?>">Précédent</a>
                </li>

                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item">
                        <a class="page-link btn btn-sm rounded-1 <?php echo ($i == $page) ? 'btn-primary' : 'btn-secondary'; ?>" href="?p=<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link btn btn-secondary btn-sm rounded-1" href="?p=<?php echo $page + 1; ?>">Suivant</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>

    </div>
</main>

<!-- MODALE DE CRÉATION DE PUBLICATION -->
<div class="modal fade" id="addPostModal" tabindex="-1" aria-labelledby="addPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border: 2px solid var(--primary); background-color: var(--secondary);">
            <div class="modal-header border-bottom border-dark">
                <h5 class="about-header h5 mb-0" id="addPostModalLabel">Nouvelle publication</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="posts.php" method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        
                        <!-- Titre -->
                        <div class="col-md-8">
                            <label for="postTitle" class="form-label fw-bold">Titre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="postTitle" name="title" placeholder="Ex: Masterclass sur l'IA" required>
                        </div>

                        <!-- Type -->
                        <div class="col-md-4">
                            <label for="postType" class="form-label fw-bold">Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="postType" name="type" required>
                                <option value="" disabled selected>Choisir un type...</option>
                                <option value="Événement">Événement</option>
                                <option value="Offre d'emploi">Offre d'emploi</option>
                                <option value="Stage">Offre de Stage</option>
                                <option value="Recherche / Projet">Recherche / Projet</option>
                                <option value="Annonce">Annonce générale</option>
                                <option value="Article / Info">Article / Information</option>
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label for="postDescription" class="form-label fw-bold">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="postDescription" name="description" rows="4" placeholder="Décrivez votre publication..." required></textarea>
                        </div>

                        <!-- Date -->
                        <div class="col-md-4">
                            <label for="postDate" class="form-label fw-bold">Date de l'événement</label>
                            <input type="date" class="form-control" id="postDate" name="date">
                        </div>

                        <!-- Heure -->
                        <div class="col-md-4">
                            <label for="postHeure" class="form-label fw-bold">Heure</label>
                            <input type="time" class="form-control" id="postHeure" name="heure">
                        </div>

                        <!-- Lieu -->
                        <div class="col-md-4">
                            <label for="postLieu" class="form-label fw-bold">Lieu</label>
                            <input type="text" class="form-control" id="postLieu" name="lieu" placeholder="Ex: Amphithéâtre A">
                        </div>

                        <!-- Lien -->
                        <div class="col-12">
                            <label for="postLien" class="form-label fw-bold">Lien externe <span class="text-muted fw-normal">(optionnel)</span></label>
                            <input type="url" class="form-control" id="postLien" name="lien" placeholder="https://exemple.com">
                        </div>

                    </div>
                </div>

                <div class="modal-footer border-top border-dark">
                    <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="create_post" class="btn btn-primary px-4 py-2">Publier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODALE DE MODIFICATION -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: 2px solid var(--primary); background-color: var(--secondary);">
            <div class="modal-header border-bottom border-dark">
                <h5 class="about-header h5 mb-0">Modifier l'événement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form method="POST" action="?action=update&p=<?php echo $page; ?>">
                <div class="modal-body">
                    <input type="hidden" name="post_id" id="edit_post_id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Auteur</label>
                        <input type="text" id="edit_author" class="form-control" readonly style="background-color: rgba(0,0,0,0.05);">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Lieu</label>
                        <input type="text" name="lieu" id="edit_lieu" class="form-control">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date</label>
                            <input type="date" name="date" id="edit_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Heure</label>
                            <input type="time" name="heure" id="edit_heure" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Lien (optionnel)</label>
                        <input type="url" name="lien" id="edit_lien" class="form-control" placeholder="https://...">
                    </div>
                </div>
                <div class="modal-footer border-top border-dark">
                    <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary px-4 py-2">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let editModalInstance = null;

document.addEventListener('DOMContentLoaded', function() {
    editModalInstance = new bootstrap.Modal(document.getElementById('editModal'));
});

function openEditModal(post) {
    document.getElementById('edit_post_id').value = post.id;
    document.getElementById('edit_author').value = post.fullname || 'Anonyme';
    document.getElementById('edit_lieu').value = post.lieu || '';
    document.getElementById('edit_date').value = post.date || '';
    document.getElementById('edit_heure').value = post.heure || '';
    document.getElementById('edit_lien').value = post.lien || '';
    
    if (editModalInstance) {
        editModalInstance.show();
    }
}
</script>
</body>
</html>