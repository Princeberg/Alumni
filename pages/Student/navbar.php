<!-- ========================= -->
<!-- NAVBAR MOBILE FIRST -->
<!-- ========================= -->

<div class="container-fluid mobile-nav d-lg-none">
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
  <a href="#publications" onclick="closeMenu()">Publication</a>
  <a href="#mentors" onclick="closeMenu()">Mentorat</a>
  <a href="#don-section" onclick="closeMenu()">Faire un Don</a>

  <a href="javascript:void(0);" onclick="openProfile(); closeMenu();">
    <i class="fa-solid fa-circle-user"></i> Profil
  </a>
</div>

<div class="container-fluid desktop-nav d-none d-lg-block">
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
        <li><a href="#publications" class="nav-link">Publication</a></li>
        <li><a href="#mentors" class="nav-link">Mentorat</a></li>
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

  <a href="logout.php" class="btn btn-danger btn-sm mt-3 w-100">
    <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
  </a>

</div>

<!-- ========================= -->
<!-- STYLE (TON STYLE ORIGINAL) -->
<!-- ========================= -->

<style>

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
  background: #012587;
  display: flex;
  flex-direction: column;
  padding: 80px 25px;
  transition: 0.3s ease;
  z-index: 2000;
}

.mobile-menu.active {
  right: 0;
}

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

.menu-overlay.active {
  display: block;
}

/* DESKTOP */
.desktop-nav {
  padding: 15px 0;
}

.desktop-nav .nav-link {
  color: white;
}

.desktop-nav .nav-link:hover {
  color: #ffcc00;
}

/* PROFILE OVERLAY */
.profile-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.6);
  backdrop-filter: blur(5px);
  display: none;
  z-index: 999999;
}

.profile-overlay.active {
  display: block;
}

/* PROFILE MODAL */
.profile-modal {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%) scale(0.8);
  width: 90%;
  max-width: 400px;
  background: #012587;
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
  color: #ffcc00;
  margin-bottom: 15px;
}

.profile-modal h5 {
  color: #ffcc00;
  font-weight: 600;
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
  