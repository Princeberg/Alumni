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

<!-- OVERLAY MENU MOBILE -->
<div id="menuOverlay" class="menu-overlay" onclick="closeMenu()"></div>

<!-- DRAWER MENU MOBILE -->
<aside id="mobileMenu" class="mobile-drawer">
  <div class="drawer-header d-flex justify-content-between align-items-center">
    <button type="button" class="btn-close-drawer" onclick="closeMenu()">
      <i class="fa-solid fa-xmark"></i>
    </button>
  </div>

  <nav class="drawer-nav">
    <a href="#publications" onclick="closeMenu()"><i class="fa-solid fa-newspaper"></i> Publications</a>
    <a href="#mentors" onclick="closeMenu()"><i class="fa-solid fa-user-graduate"></i> Mentorat</a>
    <a href="#don-section" onclick="closeMenu()"><i class="fa-solid fa-hand-holding-dollar"></i> Faire un Don</a>
    
    <div class="drawer-divider"></div>
    
    <a href="javascript:void(0);" onclick="openProfile(); closeMenu();" class="profile-drawer-btn">
      <i class="fa-solid fa-circle-user"></i> Mon Profil
    </a>
  </nav>
</aside>

<!-- HEADER DESKTOP -->
<header class="site-header-desktop d-none d-lg-block">
  <div class="container d-flex align-items-center justify-content-between py-3">
    
    <div class="brand-logo">
      <a href="index.php">
        <img src="../../src/logo.png" alt="Logo" height="50">
      </a>
    </div>

    <nav class="desktop-nav-links">
      <ul class="d-flex align-items-center m-0 p-0">
        <li><a href="#publications" class="nav-item-link">Publications</a></li>
        <li><a href="#mentors" class="nav-item-link">Mentorat</a></li>
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

<div id="profileOverlay" class="profile-overlay" onclick="closeProfile()"></div>

<aside id="profileSidebar" class="profile-sidebar">

  <div class="sidebar-body p-4">
    <div class="text-center mb-4">
      <div class="profile-avatar mb-2">
        <i class="fa-solid fa-circle-user"></i>
      </div>
      <span class="profile-badge">Espace Étudiant</span>
    </div>

    <form action="../../functions/update-profile.php" method="POST" id="profileForm">
      
      <div class="form-group mb-3 text-left">
        <label class="form-label-custom"><i class="fa-solid fa-user mr-1"></i> Nom complet</label>
        <input 
          type="text" 
          name="fullname" 
          class="form-control form-input-custom" 
          value="<?php echo htmlspecialchars($fullname ?? ''); ?>" 
          required
        >
      </div>

      <div class="form-group mb-4 text-left">
        <label class="form-label-custom"><i class="fa-brands fa-whatsapp mr-1"></i> Numéro WhatsApp</label>
        <input 
          type="tel" 
          name="whatsapp" 
          class="form-control form-input-custom" 
          placeholder="+237 600000000"
          value="<?php echo htmlspecialchars($whatsapp ?? ''); ?>" 
          required
        >
      </div>

      <button type="submit" class="btn-save-profile mb-3">
        <i class="fa-solid fa-floppy-disk mr-1"></i> Enregistrer les modifications
      </button>
    </form>

    <div class="sidebar-divider my-4"></div>

    <a href="../../functions/logout.php" class="btn-logout" style="text-decoration: none;">
      <i class="fa-solid fa-right-from-bracket mr-1"></i> Se déconnecter
    </a>
  </div>
</aside>

<script>
function toggleMenu() {
  document.getElementById("mobileMenu").classList.toggle("active");
  document.getElementById("menuOverlay").classList.toggle("active");
  document.body.style.overflow = document.getElementById("mobileMenu").classList.contains("active") ? "hidden" : "auto";
}

function closeMenu() {
  document.getElementById("mobileMenu").classList.remove("active");
  document.getElementById("menuOverlay").classList.remove("active");
  document.body.style.overflow = "auto";
}

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