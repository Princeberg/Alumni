<?php
include '../../functions/db_connect.php';

// Sécuriser l'id
$user_id = intval($user_id);

// Récupérer le statut actuel
$stmt = $conn->prepare("SELECT mentorat, fullname FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$current_status = $row['mentorat'] ?? 0;
$fullname = $row['fullname'] ?? 'Utilisateur';

// Mise à jour du statut
if (isset($_POST['update_status'])) {
    $new_status = isset($_POST['mentorat_status']) ? 1 : 0;
    $stmt = $conn->prepare("UPDATE users SET mentorat = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_status, $user_id);
    if ($stmt->execute()) {
        $current_status = $new_status;
        $success = "Statut mis à jour avec succès.";
    }
}
?>

<!-- ========================= -->
<!-- NAVBAR MOBILE FIRST -->
<!-- ========================= -->
<div class="container-fluid mobile-nav d-lg-none" >
  <div class="d-flex align-items-center justify-content-between">

    <!-- LOGO -->
    <div>
      <a href="index.php">
        <img src="../../src/logo.png" alt="Logo" width="70">
      </a>
    </div>

    <!-- MENU TOGGLE -->
    <div>
      <a href="javascript:void(0);" onclick="toggleMenu()" class="text-white">
        <span class="icon-menu h3"></span>
      </a>
    </div>

  </div>
</div>

<!-- MENU OVERLAY -->
<div id="menuOverlay" class="menu-overlay" onclick="closeMenu()"></div>

<!-- MENU MOBILE -->
<div id="mobileMenu" class="mobile-menu">
  <a href="#home" onclick="closeMenu()">Accueil</a>
  <a href="#alumnis" onclick="closeMenu()">Alumnis</a>
  <a href="#publications" onclick="closeMenu()">Publications</a>
  <a href="#don-section" onclick="closeMenu()">Faire un Don</a>
  <a href="javascript:void(0);" onclick="openProfile(); closeMenu();">
    <i class="fa-solid fa-circle-user"></i> Profil
  </a>
</div>

<!-- ========================= -->
<!-- NAVBAR DESKTOP -->
<!-- ========================= -->
<div class="container-fluid desktop-nav d-none d-lg-block" >
  <div class="d-flex align-items-center">

    <!-- LOGO -->
    <div class="site-logo mr-auto w-25">
      <a href="index.php">
        <img src="../../src/logo.png" alt="Logo" width="70">
      </a>
    </div>

    <!-- NAVIGATION -->
    <div class="mx-auto text-center">
      <ul class="site-menu main-menu mx-auto m-0 p-0">
        <li><a href="#home" class="nav-link">Accueil</a></li>
       <li><a href="#alumnis" class="nav-link" onclick="closeMenu()">Alumnis</a> </li> 
        <li><a href="#publications" class="nav-link">Publications</a></li>
        <li><a href="#don-section" class="nav-link">Faire un Don</a></li>
        <li class="cta">
          <a href="javascript:void(0);" class="nav-link" onclick="openProfile()">
            <i class="fa-solid fa-circle-user fa-lg"></i>
          </a>
        </li>
      </ul>
    </div>

  </div>
</div>

<!-- ========================= -->
<!-- PROFILE MODAL -->
<!-- ========================= -->
<div id="profileOverlay" class="profile-overlay" onclick="closeProfile()"></div>

<div id="profileModal" class="profile-modal text-center">

  <div class="profile-avatar">
    <i class="fa-solid fa-circle-user"></i>
  </div>

  <h5><?php echo htmlspecialchars($fullname); ?></h5>

  <!-- Statut Mentorat -->
  <div class="mt-3 mb-3">
    <span class="badge badge-pill status-badge <?= $current_status == 1 ? 'badge-active' : 'badge-inactive' ?>">
      <i class="fas fa-circle mr-1"></i>
      <?= $current_status == 1 ? 'MENTOR ACTIF' : 'MENTOR INACTIF' ?>
    </span>
  </div>

  <!-- Formulaire pour changer le statut -->
  <form method="POST" class="mb-3">
    <input type="hidden" name="update_status" value="1">

    <div class="custom-control custom-switch d-flex justify-content-center align-items-center">
      <input type="checkbox" 
             class="custom-control-input" 
             id="mentoratStatusModal" 
             name="mentorat_status" 
             value="1"
             <?= $current_status == 1 ? 'checked' : '' ?>>
      <label class="custom-control-label font-weight-bold ml-2" for="mentoratStatusModal">
        <?= $current_status == 1 ? 'Désactiver' : 'Activer' ?> le mentorat
      </label>
    </div>

    <button type="submit" class="btn btn-primary-custom btn-block mt-3">
      <i class="fas fa-save mr-1"></i> Enregistrer
    </button>
  </form>

  <a href="logout.php" class="btn btn-danger btn-sm w-100 mt-2">
    <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
  </a>

</div>

<!-- ========================= -->
<!-- STYLE -->
<!-- ========================= -->
<style>
:root {
    --primary-color: #012587;
    --secondary-color: #ffcc00;
}

body {
    background-color: #012587;
}

/* NAV MOBILE */
.mobile-nav {
    padding: 15px;
    z-index: 1000;
}

/* MENU MOBILE */
.mobile-menu {
    position: fixed;
    top: 0;
    right: -100%;
    width: 75%;
    height: 100vh;
    background: var(--primary-color);
    display: flex;
    flex-direction: column;
    padding: 80px 25px;
    transition: 0.3s ease;
    z-index: 2000;
}
.mobile-menu.active { right: 0; }
.mobile-menu a {
    color: white;
    padding: 15px 0;
    text-decoration: none;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

/* MENU OVERLAY */
.menu-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    display: none;
    z-index: 1500;
}
.menu-overlay.active { display: block; }

/* DESKTOP */
.desktop-nav {
    padding: 15px 0;
}
.desktop-nav .nav-link { color: white; }
.desktop-nav .nav-link:hover { color: var(--primary-color); }

/* PROFILE OVERLAY */
.profile-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(5px);
    display: none;
    z-index: 999999;
}
.profile-overlay.active { display: block; }

/* PROFILE MODAL */
.profile-modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0.8);
    width: 90%;
    max-width: 400px;
    background: var(--primary-color);
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.4);
    padding: 25px;
    opacity: 0;
    z-index: 1000000;
    transition: all 0.3s ease;
}
.profile-modal.active {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
}

.profile-avatar {
    font-size: 70px;
    color: var(--secondary-color);
    margin-bottom: 15px;
}
.profile-modal h5 { color: var(--secondary-color); font-weight: 600; }

/* STATUS BADGE */
.status-badge {
    font-size: 1.1rem;
    padding: 8px 20px;
    border-radius: 30px;
}
.badge-active { background-color: var(--secondary-color); color: var(--primary-color); }
.badge-inactive { background-color: #dc3545; color: white; }

/* BUTTON */
.btn-primary-custom {
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: 30px;
    padding: 10px;
    font-weight: bold;
    transition: 0.3s ease;
}
.btn-primary-custom:hover {
    background-color: var(--secondary-color);
    color: var(--primary-color);
}

/* SWITCH */
.custom-control-input:checked ~ .custom-control-label::before {
    background-color: var(--secondary-color);
    border-color: var(--secondary-color);
}
.custom-control-label::before {
    border-radius: 20px;
}
</style>

<!-- ========================= -->
<!-- SCRIPT -->
<!-- ========================= -->
<script>
function toggleMenu() {
    document.getElementById("mobileMenu").classList.toggle("active");
    document.getElementById("menuOverlay").classList.toggle("active");
    document.body.style.overflow = "hidden";
}
function closeMenu() {
    document.getElementById("mobileMenu").classList.remove("active");
    document.getElementById("menuOverlay").classList.remove("active");
    document.body.style.overflow = "auto";
}
function openProfile() {
    document.getElementById("profileModal").classList.add("active");
    document.getElementById("profileOverlay").classList.add("active");
    document.body.style.overflow = "hidden";
}
function closeProfile() {
    document.getElementById("profileModal").classList.remove("active");
    document.getElementById("profileOverlay").classList.remove("active");
    document.body.style.overflow = "auto";
}
</script>
