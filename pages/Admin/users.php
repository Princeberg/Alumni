<?php 
include 'header.php'; 
include 'session.php'; 
include '../../functions/db_connect.php'; 

// Récupérer tous les utilisateurs avec leur statut
$query = "SELECT u.*, s.name as status_name 
          FROM users u 
          LEFT JOIN status s ON u.statut_id = s.id 
          ORDER BY u.created_at DESC";
$result = mysqli_query($conn, $query);

// Traitement des actions (suppression, modification)
if(isset($_GET['action'])) {
    if($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        mysqli_query($conn, "DELETE FROM users WHERE id = '$id'");
        header("Location: users.php?msg=deleted");
        exit();
    }
    
    if($_GET['action'] == 'update' && isset($_POST['user_id'])) {
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $statut_id = mysqli_real_escape_string($conn, $_POST['statut_id']);
        $account_type = mysqli_real_escape_string($conn, $_POST['account_type']);
        $faculty = mysqli_real_escape_string($conn, $_POST['faculty']);
        $mentorat = mysqli_real_escape_string($conn, $_POST['mentorat']);
        
        $update_query = "UPDATE users SET 
                         statut_id = '$statut_id',
                         mentorat = '$mentorat'
                         WHERE id = '$user_id'";
        
        if(mysqli_query($conn, $update_query)) {
            header("Location: users.php?msg=updated");
            exit();
        }
    }
}


$status_query = mysqli_query($conn, "SELECT * FROM status ORDER BY name");
$statuses = mysqli_fetch_all($status_query, MYSQLI_ASSOC);
?>

<div class="users-container">
    <!-- Header avec navigation -->
    <div class="dashboard-header">
        <div class="header-left">
            <a href="index.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
            <div class="header-content">
                <h1 class="dashboard-title">Gestion des Utilisateurs</h1>
                <p class="dashboard-subtitle">Liste complète des utilisateurs inscrits</p>
            </div>
        </div>
    </div>

    <!-- Messages de notification -->
    <?php if(isset($_GET['msg'])): ?>
        <div class="notification-message <?php echo $_GET['msg'] == 'deleted' ? 'error' : 'success'; ?>">
            <?php if($_GET['msg'] == 'deleted'): ?>
                <i class="fas fa-check-circle"></i> Utilisateur supprimé avec succès
            <?php elseif($_GET['msg'] == 'updated'): ?>
                <i class="fas fa-check-circle"></i> Utilisateur mis à jour avec succès
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Tableau des utilisateurs -->
    <div class="table-wrapper">
        <table class="users-table">
            <thead>
                <tr>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Date naissance</th>
                    <th>Genre</th>
                    <th>WhatsApp</th>
                    <th>Type compte</th>
                    <th>Faculté</th>
                    <th>Mentorat</th>
                    <th>Statut</th>
                    <th>Date inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td data-label="Nom complet"><?php echo htmlspecialchars($user['fullname']); ?></td>
                    <td data-label="Email"><?php echo htmlspecialchars($user['email']); ?></td>
                    <td data-label="Date naissance"><?php echo date('d/m/Y', strtotime($user['birthdate'])); ?></td>
                    <td data-label="Genre"><?php echo $user['gender'] == 'M' ? 'Masculin' : 'Féminin'; ?></td>
                    <td data-label="WhatsApp"><?php echo htmlspecialchars($user['whatsapp']); ?></td>
                    <td data-label="Type compte"><?php echo htmlspecialchars($user['account_type']); ?></td>
                    <td data-label="Faculté"><?php echo htmlspecialchars($user['faculty']); ?></td>
                    <td data-label="Mentorat">
                        <span class="badge <?php echo $user['mentorat'] == 1 ? 'badge-yes' : 'badge-no'; ?>">
                            <?php echo $user['mentorat'] == 1 ? 'Oui' : 'Non'; ?>
                        </span>
                    </td>
                    <td data-label="Statut">
                        <span class="status-badge" style="background: <?php echo getStatusColor($user['status_name']); ?>">
                            <?php echo htmlspecialchars($user['status_name'] ?? 'Non défini'); ?>
                        </span>
                    </td>
                    <td data-label="Date inscription"><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                    <td data-label="Actions">
                        <div class="action-buttons">
                            <button class="edit-btn" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($user)); ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="?action=delete&id=<?php echo $user['id']; ?>" 
                               class="delete-btn"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
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
                <h2>Modifier l'utilisateur</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <form method="POST" action="?action=update">
                <input type="hidden" name="user_id" id="edit_user_id">
                
                <div class="form-group">
                    <label>Nom complet</label>
                    <input type="text" id="edit_fullname" readonly disabled>
                </div>
                
                
                <div class="form-group">
                    <label>Mentorat</label>
                    <select name="mentorat" id="edit_mentorat" required>
                        <option value="1">Oui</option>
                        <option value="0">Non</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Statut</label>
                    <select name="statut_id" id="edit_statut_id" required>
                        <?php foreach($statuses as $status): ?>
                            <option value="<?php echo $status['id']; ?>"><?php echo $status['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
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
function openEditModal(user) {
    document.getElementById('edit_user_id').value = user.id;
    document.getElementById('edit_fullname').value = user.fullname;
    document.getElementById('edit_mentorat').value = user.mentorat;
    document.getElementById('edit_statut_id').value = user.statut_id;
    
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

<?php
function getStatusColor($status) {
    $colors = [
        'Actif' => '#10b981',
        'Inactif' => '#ef4444',
        'En attente' => '#f59e0b',
        'Bloqué' => '#dc2626'
    ];
    
    return $colors[$status] ?? '#6b7280';
}
?>

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

.users-container {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 2rem;
}

/* Header styles (réutilisés du dashboard) */
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

.admin-badge {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.6rem 1.2rem;
    background: #ffcc00;
    border-radius: 50px;
    color: #012587;
    font-weight: 500;
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

.header-right {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.date-display {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.6rem 1.5rem;
    background: #ffcc00;
    border-radius: 50px;
    color: #012587;
    font-weight: 500;
}

.logout-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.6rem 1.5rem;
    background: #fee2e2;
    border-radius: 50px;
    color: #dc2626;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
}

.logout-btn:hover {
    background: #dc2626;
    color: white;
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
.users-table {
    width: 100%;
    border-collapse: collapse;
    background: transparent;
    border: 2px solid #ffcc00;
    border-radius: 20px;
    overflow: hidden;
}

.users-table thead tr {
    background: #ffcc00;
    color: #012587;
}

.users-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.users-table td {
    padding: 1rem;
    border-bottom: 1px solid rgba(255, 204, 0, 0.3);
    color: #ffcc00;
    font-size: 0.95rem;
}

.users-table tbody tr:hover {
    background: rgba(255, 204, 0, 0.1);
}

/* Badges */
.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-block;
}

.badge-yes {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid #10b981;
}

.badge-no {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    border: 1px solid #ef4444;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 500;
    color: white;
    display: inline-block;
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
    max-width: 500px;
    position: relative;
    animation: slideIn 0.3s ease;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #ffcc00;
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

.form-group label {
    display: block;
    color: #ffcc00;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 0.75rem 1rem;
    background: transparent;
    border: 2px solid #ffcc00;
    border-radius: 12px;
    color: #ffcc00;
    font-size: 1rem;
    transition: all 0.2s ease;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(255, 204, 0, 0.3);
}

.form-group input[readonly] {
    background: rgba(255, 204, 0, 0.1);
    cursor: not-allowed;
}

/* Modal actions */
.modal-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
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

/* Responsive */
@media (max-width: 1024px) {
    .users-table {
        font-size: 0.9rem;
    }
    
    .users-table th,
    .users-table td {
        padding: 0.75rem;
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
    
    .header-right {
        flex-direction: column;
        width: 100%;
    }
    
    .date-display, .logout-btn {
        width: 100%;
        justify-content: center;
    }
    
    .users-table {
        border: 0;
    }
    
    .users-table thead {
        display: none;
    }
    
    .users-table tr {
        display: block;
        margin-bottom: 1rem;
        border: 2px solid #ffcc00;
        border-radius: 12px;
        padding: 0.5rem;
    }
    
    .users-table td {
        display: block;
        text-align: right;
        padding: 0.75rem 0.5rem;
        border: none;
        border-bottom: 1px solid rgba(255, 204, 0, 0.2);
    }
    
    .users-table td:last-child {
        border-bottom: none;
    }
    
    .users-table td::before {
        content: attr(data-label);
        float: left;
        font-weight: 600;
        color: #ffcc00;
    }
    
    .modal-content {
        width: 95%;
        margin: 10% auto;
        padding: 1.5rem;
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
</style>