<?php 
include 'header.php'; 
include '../../functions/db_connect.php'; 

$limit = 10; 
$page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;
$total_query = "SELECT COUNT(*) as total FROM users";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_users = $total_row['total'];
$total_pages = ceil($total_users / $limit);
$query = "SELECT u.*, s.name as status_name 
          FROM users u 
          LEFT JOIN status s ON u.statut_id = s.id 
          ORDER BY u.created_at DESC 
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Traitement des actions (suppression, modification)
if(isset($_GET['action'])) {
    if($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        mysqli_query($conn, "DELETE FROM users WHERE id = '$id'");
        header("Location: users.php?msg=deleted&p=" . $page);
        exit();
    }
    
    if($_GET['action'] == 'update' && isset($_POST['user_id'])) {
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $statut_id = mysqli_real_escape_string($conn, $_POST['statut_id']);
        $mentorat = mysqli_real_escape_string($conn, $_POST['mentorat']);
        
        $update_query = "UPDATE users SET 
                         statut_id = '$statut_id',
                         mentorat = '$mentorat'
                         WHERE id = '$user_id'";
        
        if(mysqli_query($conn, $update_query)) {
            header("Location: users.php?msg=updated&p=" . $page);
            exit();
        }
    }
}

$status_query = mysqli_query($conn, "SELECT * FROM status ORDER BY name");
$statuses = mysqli_fetch_all($status_query, MYSQLI_ASSOC);
?>

<main class="py-5">
    <div class="container py-4">

        <!-- EN-TÊTE PAGE -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 pb-3 border-bottom border-dark fade-down">
            <div>
                <h1 class="hero-title display-4 mb-2" style="font-size: 48px;">Gestion des Utilisateurs</h1>
                <p class="hero-desc mb-0">Page <?php echo $page; ?> sur <?php echo max(1, $total_pages); ?></p>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="badge pub-badge-outline fs-6 px-3 py-2">
                    Total : <?php echo $total_users; ?> membre(s)
                </span>
            </div>
        </div>
        <?php if(isset($_GET['msg'])): ?>
            <div class="alert alert-dark border border-dark rounded-3 mb-4 p-3 fade-down">
                <?php if($_GET['msg'] == 'deleted'): ?>
                    Utilisateur supprimé avec succès.
                <?php elseif($_GET['msg'] == 'updated'): ?>
                    Utilisateur mis à jour avec succès.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- TABLEAU DES UTILISATEURS -->
        <div class="pub-card-outline p-4 fade-up mb-4">
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead>
                        <tr class="border-bottom border-dark">
                            <th class="pb-3 fw-bold">Nom complet</th>
                            <th class="pb-3 fw-bold">Email</th>
                            <th class="pb-3 fw-bold">Genre</th>
                            <th class="pb-3 fw-bold">WhatsApp</th>
                            <th class="pb-3 fw-bold">Faculté</th>
                            <th class="pb-3 fw-bold">Mentorat</th>
                            <th class="pb-3 fw-bold">Statut</th>
                            <th class="pb-3 fw-bold">Inscription</th>
                            <th class="pb-3 fw-bold text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result && mysqli_num_rows($result) > 0): ?>
                            <?php while($user = mysqli_fetch_assoc($result)): ?>
                            <tr class="border-bottom border-secondary">
                                <td class="py-3 fw-bold"><?php echo htmlspecialchars($user['fullname']); ?></td>
                                <td class="py-3"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="py-3"><?php echo $user['gender'] == 'M' ? 'Masculin' : 'Féminin'; ?></td>
                                <td class="py-3"><?php echo htmlspecialchars($user['whatsapp']); ?></td>
                                <td class="py-3"><?php echo htmlspecialchars($user['faculty']); ?></td>
                                <td class="py-3">
                                    <span class="badge <?php echo $user['mentorat'] == 1 ? 'pub-badge' : 'pub-badge-outline'; ?>">
                                        <?php echo $user['mentorat'] == 1 ? 'Oui' : 'Non'; ?>
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="badge pub-badge-outline">
                                        <?php echo htmlspecialchars($user['status_name'] ?? 'Non défini'); ?>
                                    </span>
                                </td>
                                <td class="py-3 fs-7"><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                <td class="py-3 text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <button type="button" class="btn btn-secondary btn-sm px-3" onclick='openEditModal(<?php echo json_encode($user, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
                                            Éditer
                                        </button>
                                        <a href="?action=delete&id=<?php echo $user['id']; ?>&p=<?php echo $page; ?>" 
                                           class="btn btn-primary btn-sm px-3"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                            Supprimer
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="py-4 text-center">Aucun utilisateur trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

     
        <?php if($total_pages > 1): ?>
        <nav aria-label="Navigation des pages" class="d-flex justify-content-center mt-4">
            <ul class="pagination gap-1">
                <!-- Bouton Précédent -->
                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link btn btn-secondary btn-sm rounded-1" href="?p=<?php echo $page - 1; ?>">Précédent</a>
                </li>

                <!-- Numéros de pages -->
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item">
                        <a class="page-link btn btn-sm rounded-1 <?php echo ($i == $page) ? 'btn-primary' : 'btn-secondary'; ?>" href="?p=<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <!-- Bouton Suivant -->
                <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link btn btn-secondary btn-sm rounded-1" href="?p=<?php echo $page + 1; ?>">Suivant</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>

    </div>
</main>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: 2px solid var(--primary); background-color: var(--secondary);">
            <div class="modal-header border-bottom border-dark">
                <h5 class="about-header h5 mb-0">Modifier l'utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form method="POST" action="?action=update&p=<?php echo $page; ?>">
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nom complet</label>
                        <input type="text" id="edit_fullname" class="form-control" readonly style="background-color: rgba(0,0,0,0.05);">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mentorat</label>
                        <select name="mentorat" id="edit_mentorat" class="form-select" required>
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Statut</label>
                        <select name="statut_id" id="edit_statut_id" class="form-select" required>
                            <?php foreach($statuses as $status): ?>
                                <option value="<?php echo $status['id']; ?>"><?php echo htmlspecialchars($status['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
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

function openEditModal(user) {
    document.getElementById('edit_user_id').value = user.id;
    document.getElementById('edit_fullname').value = user.fullname;
    document.getElementById('edit_mentorat').value = user.mentorat;
    document.getElementById('edit_statut_id').value = user.statut_id;
    
    if (editModalInstance) {
        editModalInstance.show();
    }
}
</script>
</body>
</html>