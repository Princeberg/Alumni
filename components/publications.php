<section class="publications-section py-5 bg-light-custom" id="publications">
  <div class="container py-4">
    <div class="row justify-content-center mb-5 text-center">
      <div class="col-lg-8 fade-up fade-delay-1">
        <h2 class="display-5 fw-bold mb-2">Publications Récentes</h2>
        <p class="fs-6 mb-0">
          Découvrez les dernières informations partagés par la communauté.
        </p>
      </div>
    </div>

    <!-- Carrousel Wrapper -->
    <div class="carousel-relative-container fade-up fade-delay-2">
      <!-- Flèches de navigation -->
      <button class="carousel-nav-btn prev-btn" id="pubPrevBtn" aria-label="Précédent">
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <button class="carousel-nav-btn next-btn" id="pubNextBtn" aria-label="Suivant">
        <i class="fa-solid fa-chevron-right"></i>
      </button>

      <!-- Conteneur des cartes (Scrollable) -->
      <div class="publications-carousel" id="pubCarousel">
        <?php
        require_once 'functions/db_connect.php';

        $sql = "SELECT id, title, description, type, created_at, date, lieu, heure, lien 
                FROM posts 
                ORDER BY created_at DESC";

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0):
          while($row = $result->fetch_assoc()):

            $created = date("d/m/Y", strtotime($row['created_at']));
            $event_date = !empty($row['date']) ? date("d/m/Y", strtotime($row['date'])) : null;

            $short_desc = strlen($row['description']) > 130 
              ? substr($row['description'], 0, 130).'...' 
              : $row['description'];
        ?>
        
        <!-- Item carte -->
        <!-- Item carte transparente Outline -->
<div class="pub-card-item">
  <div class="card h-100 p-4 rounded-4 bg-transparent pub-card-outline">
    <div class="card-body p-0 d-flex flex-column">

      <!-- Badge Type & Date -->
      <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="badge pub-badge px-3 py-2 fw-medium">
          <?= htmlspecialchars($row['type']); ?>
        </span>
        <small class="fs-7">
          <?= $created; ?>
        </small>
      </div>

      <!-- Titre -->
      <h3 class="h5 fw-bold mb-2 pub-title">
        <?= htmlspecialchars($row['title']); ?>
      </h3>

      <!-- Description -->
      <p class="fs-7 mb-4 flex-grow-1">
        <?= htmlspecialchars($short_desc); ?>
      </p>

      <!-- Métadonnées (Date, Heure, Lieu) -->
      <?php if($event_date || !empty($row['heure']) || !empty($row['lieu'])): ?>
        <div class="pub-meta-list d-flex flex-column gap-2 mb-4 pt-3">
          <?php if($event_date): ?>
            <div class="d-flex align-items-center gap-2 fs-7">
              <i class="fa-regular fa-calendar"></i> 
              <span><?= $event_date; ?></span>
            </div>
          <?php endif; ?>

          <?php if(!empty($row['heure'])): ?>
            <div class="d-flex align-items-center gap-2 fs-7">
              <i class="fa-regular fa-clock"></i> 
              <span><?= htmlspecialchars($row['heure']); ?></span>
            </div>
          <?php endif; ?>

          <?php if(!empty($row['lieu'])): ?>
            <div class="d-flex align-items-center gap-2 fs-7">
              <i class="fa-solid fa-location-dot"></i> 
              <span><?= htmlspecialchars($row['lieu']); ?></span>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <!-- Bouton d'action -->
      <?php if(!empty($row['lien'])): ?>
        <a href="<?= htmlspecialchars($row['lien']); ?>" target="_blank" rel="noopener" class="btn btn-primary w-100 py-2 mt-auto d-inline-flex align-items-center justify-content-center gap-2 fs-7">
          En savoir plus <i class="fa-solid fa-arrow-up-right-from-square"></i>
        </a>
      <?php endif; ?>

    </div>
  </div>
</div>

        <?php endwhile; else: ?>
          <div class="w-100 text-center py-5">
            <p class="mb-0">Aucune publication disponible pour le moment.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</section>
<script> 
document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.getElementById('pubCarousel');
    const prevBtn = document.getElementById('pubPrevBtn');
    const nextBtn = document.getElementById('pubNextBtn');

    if (carousel && prevBtn && nextBtn) {
        const scrollAmount = 340; // Largeur de carte (320px) + Gap (20px/1.5rem)

        nextBtn.addEventListener('click', () => {
            carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });

        prevBtn.addEventListener('click', () => {
            carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });
    }
});
</script> 