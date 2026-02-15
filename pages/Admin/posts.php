<?php 
include 'header.php'; 
include 'session.php'; 
include '../../functions/db_connect.php'; 

// Récupérer tous les posts avec les informations des utilisateurs
$query = "SELECT p.*, u.fullname, u.email 
          FROM posts p 
          LEFT JOIN users u ON p.user_id = u.id 
          ORDER BY p.created_at DESC";
$result = mysqli_query($conn, $query);

// Traitement des actions (suppression, modification)
if(isset($_GET['action'])) {
    // Suppression d'un événement
    if($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        mysqli_query($conn, "DELETE FROM posts WHERE id = '$id'");
        header("Location: posts.php?msg=deleted");
        exit();
    }
    
    // Modification d'un événement
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
            header("Location: posts.php?msg=updated");
            exit();
        }
    }
}

// Fonction pour tronquer la description
function truncateDescription($description, $words = 50) {
    $words_array = explode(' ', $description);
    if (count($words_array) > $words) {
        return implode(' ', array_slice($words_array, 0, $words)) . '...';
    }
    return $description;
}
?>

<div class="posts-container">
    <!-- Header avec navigation -->
    <div class="dashboard-header">
        <div class="header-left">
            <a href="index.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
            <div class="header-content">
                <h1 class="dashboard-title">Gestion des Événements</h1>
                <p class="dashboard-subtitle">Liste complète des événements publiés</p>
            </div>
        </div>
    </div>

    <!-- Messages de notification -->
    <?php if(isset($_GET['msg'])): ?>
        <div class="notification-message <?php echo $_GET['msg'] == 'deleted' ? 'error' : 'success'; ?>">
            <?php if($_GET['msg'] == 'deleted'): ?>
                <i class="fas fa-check-circle"></i> Événement supprimé avec succès
            <?php elseif($_GET['msg'] == 'updated'): ?>
                <i class="fas fa-check-circle"></i> Événement mis à jour avec succès
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Tableau des événements -->
    <div class="table-wrapper">
        <table class="posts-table">
            <thead>
                <tr>
                    <th>Auteur</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Lieu</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Lien</th>
                    <th>Date publication</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($post = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td data-label="Auteur"><?php echo htmlspecialchars($post['fullname']); ?></td>
                    <td data-label="Titre"><?php echo htmlspecialchars($post['title']); ?></td>
                    <td data-label="Description" class="description-cell">
                        <?php echo htmlspecialchars(truncateDescription($post['description'], 50)); ?>
                    </td>
                    <td data-label="Type">
                        <span class="type-badge type-<?php echo strtolower($post['type']); ?>">
                            <?php echo htmlspecialchars($post['type']); ?>
                        </span>
                    </td>
                    <td data-label="Lieu"><?php echo htmlspecialchars($post['lieu']); ?></td>
                    <td data-label="Date"><?php echo date('d/m/Y', strtotime($post['date'])); ?></td>
                    <td data-label="Heure"><?php echo htmlspecialchars($post['heure']); ?></td>
                    <td data-label="Lien">
                        <?php if(!empty($post['lien'])): ?>
                            <a href="<?php echo htmlspecialchars($post['lien']); ?>" target="_blank" class="link-badge">
                                <i class="fas fa-external-link-alt"></i> Voir
                            </a>
                        <?php else: ?>
                            <span class="no-link">-</span>
                        <?php endif; ?>
                    </td>
                    <td data-label="Date publication"><?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></td>
                    <td data-label="Actions">
                        <div class="action-buttons">
                            <button class="edit-btn" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($post)); ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="?action=delete&id=<?php echo $post['id']; ?>" 
                               class="delete-btn"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de modification -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Modifier l'événement</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <form method="POST" action="?action=update">
                <input type="hidden" name="post_id" id="edit_post_id">
                
                <div class="form-group">
                    <label>Auteur</label>
                    <input type="text" id="edit_author" readonly disabled>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Lieu</label>
                        <input type="text" name="lieu" id="edit_lieu" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" id="edit_date" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Heure</label>
                        <input type="time" name="heure" id="edit_heure" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Lien (optionnel)</label>
                    <input type="url" name="lien" id="edit_lien" placeholder="https://...">
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="cancel-btn" onclick="closeModal()">Annuler</button>
                    <button type="submit" class="save-btn">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(post) {
    document.getElementById('edit_post_id').value = post.id;
    document.getElementById('edit_author').value = post.fullname;
    document.getElementById('edit_lieu').value = post.lieu;
    document.getElementById('edit_date').value = post.date;
    document.getElementById('edit_heure').value = post.heure;
    document.getElementById('edit_lien').value = post.lien || '';
    
    document.getElementById('editModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Fermer le modal si on clique en dehors
window.onclick = function(event) {
    var modal = document.getElementById('editModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

<style>
/* Import de la police */
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: #012587;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
}

.posts-container {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 2rem;
}

/* Header styles */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1rem;
    border-radius: 20px;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.back-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.2rem;
    background: #ffcc00;
    border-radius: 50px;
    color: #012587;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
}

.back-btn:hover {
    transform: translateX(-2px);
    box-shadow: 0 4px 12px rgba(255, 204, 0, 0.3);
}

.header-content {
    display: flex;
    flex-direction: column;
}

.dashboard-title {
    font-size: 1.8rem;
    font-weight: 600;
    color: #ffcc00;
    margin-bottom: 0.25rem;
}

.dashboard-subtitle {
    color: #ffcc00;
    font-size: 0.95rem;
}

/* Notification message */
.notification-message {
    padding: 1rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    animation: slideDown 0.3s ease;
}

.notification-message.success {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 2px solid #10b981;
}

.notification-message.error {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 2px solid #ef4444;
}

/* Table wrapper */
.table-wrapper {
    background: transparent;
    border-radius: 20px;
    overflow-x: auto;
    margin-top: 2rem;
}

/* Table styles */
.posts-table {
    width: 100%;
    border-collapse: collapse;
    background: transparent;
    border: 2px solid #ffcc00;
    border-radius: 20px;
    overflow: hidden;
}

.posts-table thead tr {
    background: #ffcc00;
    color: #012587;
}

.posts-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.posts-table td {
    padding: 1rem;
    border-bottom: 1px solid rgba(255, 204, 0, 0.3);
    color: #ffcc00;
    font-size: 0.95rem;
}

.posts-table tbody tr:hover {
    background: rgba(255, 204, 0, 0.1);
}

.description-cell {
    max-width: 250px;
    white-space: normal;
    word-wrap: break-word;
}

/* Type badges */
.type-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-block;
    color: white;
}

.type-conférence, .type-conference {
    background: #10b981;
}

.type-atelier {
    background: #f59e0b;
}

.type-séminaire, .type-seminaire {
    background: #3b82f6;
}

.type-webinar {
    background: #8b5cf6;
}

.type-rencontre {
    background: #ec4899;
}

.type-formation {
    background: #14b8a6;
}

.type-autre {
    background: #6b7280;
}

/* Link badge */
.link-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    background: rgba(1, 37, 135, 0.2);
    border: 1px solid #ffcc00;
    border-radius: 50px;
    color: #ffcc00;
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.2s ease;
}

.link-badge:hover {
    background: #ffcc00;
    color: #012587;
}

.no-link {
    color: #6b7280;
}

/* Action buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.edit-btn, .delete-btn {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.edit-btn {
    background: rgba(1, 37, 135, 0.2);
    color: #ffcc00;
    border: 1px solid #ffcc00;
}

.edit-btn:hover {
    background: #ffcc00;
    color: #012587;
}

.delete-btn {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid #ef4444;
}

.delete-btn:hover {
    background: #ef4444;
    color: white;
}

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    animation: fadeIn 0.3s ease;
}

.modal-content {
    background: #012587;
    margin: 3% auto;
    padding: 2rem;
    border: 2px solid #ffcc00;
    border-radius: 24px;
    width: 90%;
    max-width: 600px;
    position: relative;
    animation: slideIn 0.3s ease;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #ffcc00;
    position: sticky;
    top: 0;
    background: #012587;
    z-index: 1;
}

.modal-header h2 {
    color: #ffcc00;
    font-size: 1.5rem;
    margin: 0;
}

.close {
    color: #ffcc00;
    font-size: 2rem;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.2s ease;
}

.close:hover {
    color: #ffffff;
}

/* Form styles */
.form-group {
    margin-bottom: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    color: #ffcc00;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    background: transparent;
    border: 2px solid #ffcc00;
    border-radius: 12px;
    color: #ffcc00;
    font-size: 1rem;
    transition: all 0.2s ease;
    font-family: inherit;
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(255, 204, 0, 0.3);
}

.form-group input[readonly],
.form-group textarea[readonly],
.form-group input:disabled,
.form-group select:disabled {
    background: rgba(255, 204, 0, 0.1);
    cursor: not-allowed;
    opacity: 0.7;
}

/* Modal actions */
.modal-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    position: sticky;
    bottom: 0;
    background: #012587;
    padding-top: 1rem;
    border-top: 2px solid #ffcc00;
}

.cancel-btn, .save-btn {
    flex: 1;
    padding: 0.75rem;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    font-size: 1rem;
}

.cancel-btn {
    background: transparent;
    border: 2px solid #ffcc00;
    color: #ffcc00;
}

.cancel-btn:hover {
    background: #ffcc00;
    color: #012587;
}

.save-btn {
    background: #ffcc00;
    color: #012587;
    border: 2px solid #ffcc00;
}

.save-btn:hover {
    background: transparent;
    color: #ffcc00;
}

/* Placeholder color */
::placeholder {
    color: rgba(255, 204, 0, 0.5);
    opacity: 1;
}

:-ms-input-placeholder {
    color: rgba(255, 204, 0, 0.5);
}

::-ms-input-placeholder {
    color: rgba(255, 204, 0, 0.5);
}

/* Responsive */
@media (max-width: 1200px) {
    .posts-table {
        font-size: 0.9rem;
    }
    
    .posts-table th,
    .posts-table td {
        padding: 0.75rem;
    }
}

@media (max-width: 992px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .header-left {
        flex-direction: column;
        text-align: center;
    }
    
    .posts-table {
        border: 0;
    }
    
    .posts-table thead {
        display: none;
    }
    
    .posts-table tr {
        display: block;
        margin-bottom: 1rem;
        border: 2px solid #ffcc00;
        border-radius: 12px;
        padding: 0.5rem;
    }
    
    .posts-table td {
        display: block;
        text-align: right;
        padding: 0.75rem 0.5rem;
        border: none;
        border-bottom: 1px solid rgba(255, 204, 0, 0.2);
    }
    
    .posts-table td:last-child {
        border-bottom: none;
    }
    
    .posts-table td::before {
        content: attr(data-label);
        float: left;
        font-weight: 600;
        color: #ffcc00;
    }
    
    .description-cell {
        max-width: none;
    }
    
    .modal-content {
        width: 95%;
        margin: 5% auto;
        padding: 1.5rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
}

@media (max-width: 480px) {
    .posts-container {
        padding: 0 1rem;
    }
    
    .modal-actions {
        flex-direction: column;
    }
    
    .cancel-btn, .save-btn {
        width: 100%;
    }
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes slideDown {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Scrollbar styling */
.modal-content::-webkit-scrollbar {
    width: 8px;
}

.modal-content::-webkit-scrollbar-track {
    background: rgba(255, 204, 0, 0.1);
    border-radius: 4px;
}

.modal-content::-webkit-scrollbar-thumb {
    background: #ffcc00;
    border-radius: 4px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
    background: #ffd11a;
}
</style>