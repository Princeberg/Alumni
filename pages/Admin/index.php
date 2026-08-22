<?php 
include 'header.php'; 

// --- REQUÊTES GLOBALES ---
$users_query = mysqli_query($conn, "SELECT COUNT(*) AS total_users FROM users");
$users_data = mysqli_fetch_assoc($users_query);
$total_users = $users_data['total_users'] ?? 0;

$posts_query = mysqli_query($conn, "SELECT COUNT(*) AS total_posts FROM posts");
$posts_data = mysqli_fetch_assoc($posts_query);
$total_posts = $posts_data['total_posts'] ?? 0;


$users_week_query = mysqli_query($conn, "SELECT COUNT(*) AS week_users FROM users WHERE WEEK(created_at) = WEEK(NOW()) AND YEAR(created_at) = YEAR(NOW())");
$users_week_data = mysqli_fetch_assoc($users_week_query);
$users_this_week = $users_week_data['week_users'] ?? 0;

$posts_week_query = mysqli_query($conn, "SELECT COUNT(*) AS week_posts FROM posts WHERE WEEK(created_at) = WEEK(NOW()) AND YEAR(created_at) = YEAR(NOW())");
$posts_week_data = mysqli_fetch_assoc($posts_week_query);
$posts_this_week = $posts_week_data['week_posts'] ?? 0;

// --- REQUÊTES AJOUTS RENTS ---
$recent_users = mysqli_query($conn, "SELECT fullname, created_at FROM users ORDER BY created_at DESC LIMIT 4");
$recent_posts = mysqli_query($conn, "SELECT title, type, created_at FROM posts ORDER BY created_at DESC LIMIT 4");
?>

<main class="py-5">
    <div class="container py-4">

        <!-- EN-TÊTE DASHBOARD -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 pb-3 border-bottom border-dark fade-down">
            <div>
                <h1 class="hero-title display-4 mb-2" style="font-size: 48px;">Tableau de Bord</h1>
                <p class="hero-desc mb-0">Aperçu des statistiques et activités récentes.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="badge pub-badge-outline fs-6 px-3 py-2">
                    Date : <?php echo date('d/m/Y'); ?>
                </span>
            </div>
        </div>

        <!-- CARTES DE STATISTIQUES GLOBALES -->
        <div class="row g-4 mb-4">
            <!-- TOTAL UTILISATEURS -->
            <div class="col-md-6 fade-up fade-delay-1">
                <div class="pub-card-outline p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <span class="badge pub-badge-outline mb-3">Membres</span>
                        <h2 class="about-header h5 mb-3">Utilisateurs Inscrits</h2>
                        <div class="display-3 fw-bold mb-3" style="color: var(--primary);">
                            <?php echo $total_users; ?>
                        </div>
                    </div>
                    <div>
                        <a href="users.php" class="btn btn-primary w-100 py-2-5 text-center">
                            Gérer les utilisateurs
                        </a>
                    </div>
                </div>
            </div>

            <!-- TOTAL PUBLICATIONS -->
            <div class="col-md-6 fade-up fade-delay-2">
                <div class="pub-card-outline p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <span class="badge pub-badge-outline mb-3">Contenu</span>
                        <h2 class="about-header h5 mb-3">Publications Totales</h2>
                        <div class="display-3 fw-bold mb-3" style="color: var(--primary);">
                            <?php echo $total_posts; ?>
                        </div>
                    </div>
                    <div>
                        <a href="posts.php" class="btn btn-primary w-100 py-2-5 text-center">
                            Gérer les publications
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- MÉTRIQUES HEBDOMADAIRES -->
        <div class="row g-4 mb-5 fade-up fade-delay-2">
            <div class="col-md-6">
                <div class="feature-item p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="badge pub-badge mb-1">Cette semaine</span>
                        <h5 class="mb-0">Nouveaux inscrits</h5>
                    </div>
                    <div class="fs-2 fw-bold" style="color: var(--primary);">
                        +<?php echo $users_this_week; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="feature-item p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="badge pub-badge mb-1">Cette semaine</span>
                        <h5 class="mb-0">Nouvelles publications</h5>
                    </div>
                    <div class="fs-2 fw-bold" style="color: var(--primary);">
                        +<?php echo $posts_this_week; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ACTIVITÉS RÉCENTES -->
        <div class="row g-4 fade-up fade-delay-3">
            
            <!-- DERNIERS INSCRITS -->
            <div class="col-md-6">
                <div class="pub-card-outline p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="about-header h5 mb-0">Derniers Utilisateurs</h3>
                        <a href="users.php" class="fs-7 fw-bold text-decoration-none" style="color: var(--primary);">Tout voir</a>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <?php if ($recent_users && mysqli_num_rows($recent_users) > 0): ?>
                            <?php while ($user = mysqli_fetch_assoc($recent_users)): ?>
                                <div class="p-3 border border-dark rounded d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($user['fullname']); ?></div>
                                        <small class="opacity-75 fs-7">Inscrit le <?php echo date('d/m/Y', strtotime($user['created_at'])); ?></small>
                                    </div>
                                    <span class="badge pub-badge-outline fs-7">Membre</span>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="fs-7 mb-0">Aucun utilisateur récent.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- DERNIÈRES PUBLICATIONS -->
            <div class="col-md-6">
                <div class="pub-card-outline p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="about-header h5 mb-0">Dernières Publications</h3>
                        <a href="posts.php" class="fs-7 fw-bold text-decoration-none" style="color: var(--primary);">Tout voir</a>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <?php if ($recent_posts && mysqli_num_rows($recent_posts) > 0): ?>
                            <?php while ($post = mysqli_fetch_assoc($recent_posts)): ?>
                                <div class="p-3 border border-dark rounded d-flex justify-content-between align-items-center">
                                    <div class="text-truncate me-2" style="max-width: 70%;">
                                        <div class="fw-bold text-truncate"><?php echo htmlspecialchars($post['title']); ?></div>
                                        <small class="opacity-75 fs-7">Publié le <?php echo date('d/m/Y', strtotime($post['created_at'])); ?></small>
                                    </div>
                                    <span class="badge pub-badge fs-7"><?php echo htmlspecialchars($post['type']); ?></span>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="fs-7 mb-0">Aucune publication récente.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>