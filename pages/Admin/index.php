<?php 
include 'header.php'; 
include 'session.php'; 
include '../../functions/db_connect.php'; 

// Total Users
$users_query = mysqli_query($conn, "SELECT COUNT(*) AS total_users FROM users");
$users_data = mysqli_fetch_assoc($users_query);
$total_users = $users_data['total_users'];

// Total Posts
$posts_query = mysqli_query($conn, "SELECT COUNT(*) AS total_posts FROM posts");
$posts_data = mysqli_fetch_assoc($posts_query);
$total_posts = $posts_data['total_posts'];
?>

<div class="dashboard-container">
    <!-- Header avec badge admin et déconnexion -->
    <div class="dashboard-header">
        <div class="header-left">
            <div class="admin-badge">
                <i class="fas fa-shield-alt" style="color: #012587"></i>
                <span>Administrateur</span>
            </div>
            <div class="header-content">
                <h1 class="dashboard-title">Tableau de Bord</h1>
                <p class="dashboard-subtitle">Aperçu des statistiques de la plateforme</p>
            </div>
        </div>
        
        <div class="header-right">
            <div class="date-display">
                <i class="fas fa-calendar-alt" style="color: #012587;"></i>
                <span><?php echo date('d/m/Y'); ?></span>
            </div>
            
            <a href="logout.php" class="logout-btn" onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?');">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </a>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="stats-grid">
        <!-- USERS CARD -->
        <div class="stat-card users-card">
            <div class="card-icon-wrapper" style="background: rgba(1, 37, 135, 0.1);">
                <i class="fas fa-users" style="color: #012587;"></i>
            </div>
            <div class="card-content">
                <h3 class="card-title">Utilisateurs</h3>
                <div class="card-value" style="color: #012587;"><?php echo $total_users; ?></div>
                <a href="users.php" class="card-link" style="color: #012587;">
                    Gérer <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- POSTS CARD -->
        <div class="stat-card posts-card">
            <div class="card-icon-wrapper" style="background: rgba(255, 204, 0, 0.15);">
                <i class="fas fa-file-alt" style="color: #012587"></i>
            </div>
            <div class="card-content">
                <h3 class="card-title">Publications</h3>
                <div class="card-value" style="color: #012587"><?php echo $total_posts; ?></div>
                <a href="posts.php" class="card-link" style="color: #012587">
                    Gérer <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Section Actions Rapides -->
    <!-- <div class="quick-actions-section">
        <h2 class="section-title">Actions rapides</h2>
        <div class="actions-grid">
            <a href="create_post.php" class="action-btn" style="border: 2px solid #ffcc20;">
                <i class="fas fa-plus-circle" style="color: #012587;"></i>
                <span>Nouvelle publication</span>
            </a>
        </div>
    </div> -->
</div>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: #012587;
}

.dashboard-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 2rem;
}

/* Dashboard Header */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3rem;
    padding: 1.5rem 2rem;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(1, 37, 135, 0.05);
}

.header-left {
    display: flex;
    align-items: center;
    gap: 2rem;
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
    font-size: 0.95rem;
    box-shadow: 0 4px 10px rgba(1, 37, 135, 0.2);
}

.admin-badge i {
    font-size: 1rem;
}

.header-content {
    display: flex;
    flex-direction: column;
}

.dashboard-title {
    font-size: 2rem;
    font-weight: 600;
    color: #ffcc00;
    margin-bottom: 0.25rem;
}

.dashboard-subtitle {
    color:#ffcc00;
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
    font-weight: 500;
    color: #012587;
    font-size: 0.95rem;
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
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.logout-btn:hover {
    background: #dc2626;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
}

.logout-btn i {
    font-size: 1rem;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

/* Stat Cards */
.stat-card {
    background: #ffcc00;
    border-radius: 24px;
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    box-shadow: 0 10px 30px rgba(1, 37, 135, 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(1, 37, 135, 0.15);
}

.card-icon-wrapper {
    width: 70px;
    height: 70px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    flex-shrink: 0;
}

.card-content {
    flex: 1;
}

.card-title {
    color: #012587;
    font-size: 1rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-value {
    font-size: 2.8rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.card-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
    transition: gap 0.2s ease;
}

.card-link:hover {
    gap: 0.75rem;
}

/* Quick Actions */
.quick-actions-section {
    background: #012587;
    border-radius: 24px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(1, 37, 135, 0.05);
}

.section-title {
    font-size: 1.25rem;
    color: #ffcc00;
    margin-bottom: 1.5rem;
    font-weight: 600;
    position: relative;
    padding-left: 1rem;
}

.section-title::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 20px;
    background: #ffcc20;
    border-radius: 4px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.action-btn {
    background: transparent;
    padding: 1.2rem;
    width: 500px;
    border-radius: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.8rem;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    color: #ffcc00;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(1, 37, 135, 0.1);
    color: #ffcc00;
}

.action-btn i {
    font-size: 1.2rem;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .header-left {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .header-right {
        flex-direction: column;
        gap: 1rem;
    }
    
    .date-display, .logout-btn {
        justify-content: center;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        padding: 1.5rem;
    }
    
    .card-icon-wrapper {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .card-value {
        font-size: 2.2rem;
    }
}

/* Animations */
.stat-card {
    animation: fadeIn 0.4s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Petite animation pour le badge admin */
.admin-badge {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>