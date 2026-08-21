<?php
// Inclusion du fichier de connexion à la base de données
include '../../functions/db_connect.php';

// Récupération et sécurisation des informations de l'utilisateur
$user_id = intval($_SESSION['user_id'] ?? 0);

$stmt = $conn->prepare("SELECT fullname, whatsapp, mentorat FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

$fullname       = $user_data['fullname'] ?? 'Alumni';
$whatsapp       = $user_data['whatsapp'] ?? '';
$current_status = intval($user_data['mentorat'] ?? 0);
$stmt->close();
?>

<!-- =============================================
   1. NAVBAR MOBILE
   ============================================= -->
<header class="site-header-mobile d-lg-none">
  <div class="container-fluid d-flex align-items-center justify-content-between px-3 py-2">
    <a href="index.php" class="brand-logo">
      <img src="../../src/logo.png" alt="Logo" height="45">
    </a>

    <button type="button" class="btn-menu-toggle" onclick="toggleMenu()" aria-label="Menu">
      <i class="fa-solid fa-bars"></i>
    </button>
  </div>
</header>

<!-- OVERLAY & DRAWER MOBILE -->
<div id="menuOverlay" class="menu-overlay" onclick="closeMenu()"></div>

<aside id="mobileMenu" class="mobile-drawer">
  <div class="drawer-header d-flex justify-content-between align-items-center">
    <img src="../../src/logo.png" alt="Logo" height="35">
    <button type="button" class="btn-close-drawer" onclick="closeMenu()">
      <i class="fa-solid fa-xmark"></i>
    </button>
  </div>

  <nav class="drawer-nav">
    <a href="#home" onclick="closeMenu()"><i class="fa-solid fa-house"></i> Accueil</a>
    <a href="#alumnis" onclick="closeMenu()"><i class="fa-solid fa-user-graduate"></i> Alumnis</a>
    <a href="#publications" onclick="closeMenu()"><i class="fa-solid fa-newspaper"></i> Publications</a>
    <a href="#don-section" onclick="closeMenu()"><i class="fa-solid fa-hand-holding-dollar"></i> Faire un Don</a>
    
    <div class="drawer-divider"></div>
    
    <a href="javascript:void(0);" onclick="openProfile(); closeMenu();" class="profile-drawer-btn">
      <i class="fa-solid fa-circle-user"></i> Mon Profil Alumni
    </a>
  </nav>
</aside>


<!-- =============================================
   2. NAVBAR DESKTOP
   ============================================= -->
<header class="site-header-desktop d-none d-lg-block">
  <div class="container d-flex align-items-center justify-content-between py-3">
    
    <div class="brand-logo">
      <a href="index.php">
        <img src="../../src/logo.png" alt="Logo" height="50">
      </a>
    </div>

    <nav class="desktop-nav-links">
      <ul class="d-flex align-items-center m-0 p-0">
        <li><a href="#home" class="nav-item-link">Accueil</a></li>
        <li><a href="#alumnis" class="nav-item-link">Alumnis</a></li>
        <li><a href="#publications" class="nav-item-link">Publications</a></li>
        <li><a href="#don-section" class="nav-item-link">Faire un Don</a></li>
      </ul>
    </nav>

    <div class="desktop-user-action">
      <button type="button" class="btn-profile-trigger" onclick="openProfile()">
        <i class="fa-solid fa-circle-user"></i>
        <span>Mon Profil</span>
      </button>
    </div>

  </div>
</header>


<!-- =============================================
   3. SIDEBAR ALUMNI (PANNEAU PROFIL SLIDE-IN)
   ============================================= -->
<div id="profileOverlay" class="profile-overlay" onclick="closeProfile()"></div>

<aside id="profileSidebar" class="profile-sidebar">
  
  <!-- En-tête Sidebar -->
  <div class="sidebar-header d-flex align-items-center justify-content-between p-3">
    <h5 class="m-0 font-weight-bold text-white">
      <i class="fa-solid fa-user-gear mr-2"></i>Espace Alumni
    </h5>
    <button type="button" class="btn-close-sidebar" onclick="closeProfile()">
      <i class="fa-solid fa-xmark"></i>
    </button>
  </div>

  <!-- Corps Sidebar -->
  <div class="sidebar-body p-4">
    
    <!-- Informations Visuelles de l'Utilisateur -->
    <div class="text-center mb-4">
      <div class="profile-avatar mb-2">
        <i class="fa-solid fa-circle-user"></i>
      </div>
      <span class="profile-badge mb-2">Compte Alumni</span>

      <!-- Badge Dynamique du Statut Mentorat -->
      <div class="mt-2">
        <span class="badge status-badge <?= $current_status === 1 ? 'badge-active' : 'badge-inactive' ?>">
          <i class="fa-solid fa-circle mr-1"></i>
          <?= $current_status === 1 ? 'MENTOR ACTIF' : 'MENTOR INACTIF' ?>
        </span>
      </div>
    </div>

    <!-- Formulaire d'Édition du Profil -->
    <form action="../../functions/update-alumni.php" method="POST" id="profileForm">
      
      <!-- Sélection du Statut Mentorat sous forme de Liste -->
      <div class="mentor-selection-card mb-4">
        <label class="form-label-custom mb-2">
          <i class="fa-solid fa-chalkboard-user mr-1"></i> Statut du Mentorat
        </label>
        
        <div class="mentor-options-list">
          <!-- Option Actif -->
          <label class="mentor-option-item <?= $current_status === 1 ? 'selected active-border' : '' ?>">
            <input 
              type="radio" 
              name="mentorat_status" 
              value="1" 
              <?= $current_status === 1 ? 'checked' : '' ?>
              onchange="updateMentorSelection(this)"
            >
            <div class="option-icon icon-active">
              <i class="fa-solid fa-user-check"></i>
            </div>
            <div class="option-content">
              <span class="option-title">Disponible (Mentor actif)</span>
              <small class="option-desc">Visible dans l'annuaire des mentors pour aider les étudiants.</small>
            </div>
          </label>

          <!-- Option Inactif -->
          <label class="mentor-option-item <?= $current_status === 0 ? 'selected inactive-border' : '' ?>">
            <input 
              type="radio" 
              name="mentorat_status" 
              value="0" 
              <?= $current_status === 0 ? 'checked' : '' ?>
              onchange="updateMentorSelection(this)"
            >
            <div class="option-icon icon-inactive">
              <i class="fa-solid fa-user-slash"></i>
            </div>
            <div class="option-content">
              <span class="option-title">Indisponible (Mentor inactif)</span>
              <small class="option-desc">Masqué de la liste des mentors. Vous n'êtes pas sollicité.</small>
            </div>
          </label>
        </div>
      </div>

      <!-- Champ Nom Complet -->
      <div class="form-group mb-3 text-left">
        <label class="form-label-custom">
          <i class="fa-solid fa-user mr-1"></i> Nom complet
        </label>
        <input 
          type="text" 
          name="fullname" 
          class="form-control form-input-custom" 
          value="<?php echo htmlspecialchars($fullname); ?>" 
          required
        >
      </div>

      <!-- Champ WhatsApp -->
      <div class="form-group mb-4 text-left">
        <label class="form-label-custom">
          <i class="fa-brands fa-whatsapp mr-1"></i> Numéro WhatsApp
        </label>
        <input 
          type="tel" 
          name="whatsapp" 
          class="form-control form-input-custom" 
          placeholder="+237 600000000"
          value="<?php echo htmlspecialchars($whatsapp); ?>" 
          required
        >
      </div>

      <!-- Bouton de Soumission -->
      <button type="submit" class="btn-save-profile mb-3">
        <i class="fa-solid fa-floppy-disk mr-1"></i> Enregistrer
      </button>
    </form>

    <div class="sidebar-divider my-4"></div>

    <!-- Déconnexion -->
    <a href="../../functions/logout.php" class="btn-logout">
      <i class="fa-solid fa-right-from-bracket mr-1"></i> Se déconnecter
    </a>
  </div>
</aside>


<!-- =============================================
   5. SCRIPTS JAVASCRIPT
   ============================================= -->
<script>
// Gestion dynamique du changement de statut visuel
function updateMentorSelection(radio) {
  document.querySelectorAll('.mentor-option-item').forEach(item => {
    item.classList.remove('selected', 'active-border', 'inactive-border');
  });

  const parentLabel = radio.closest('.mentor-option-item');
  parentLabel.classList.add('selected');
  
  if (radio.value === "1") {
    parentLabel.classList.add('active-border');
  } else {
    parentLabel.classList.add('inactive-border');
  }
}

// Gestion du menu drawer mobile
function toggleMenu() {
  const mobileMenu = document.getElementById("mobileMenu");
  const menuOverlay = document.getElementById("menuOverlay");
  
  mobileMenu.classList.toggle("active");
  menuOverlay.classList.toggle("active");
  document.body.style.overflow = mobileMenu.classList.contains("active") ? "hidden" : "auto";
}

function closeMenu() {
  document.getElementById("mobileMenu").classList.remove("active");
  document.getElementById("menuOverlay").classList.remove("active");
  document.body.style.overflow = "auto";
}

// Gestion de la sidebar du profil
function openProfile() {
  document.getElementById("profileSidebar").classList.add("active");
  document.getElementById("profileOverlay").classList.add("active");
  document.body.style.overflow = "hidden";
}

function closeProfile() {
  document.getElementById("profileSidebar").classList.remove("active");
  document.getElementById("profileOverlay").classList.remove("active");
  document.body.style.overflow = "auto";
}
</script>