<!-- SECTION PUBLICATIONS -->
<div class="site-section" id="publications" style="background-color: #012587; padding: 80px 0;" data-aos="fade-left"> 
  <div class="container">

    <div class="row justify-content-center mb-4">
      <div class="col-md-8 text-center">
        <h2 style="font-weight:700; color: #ffcc00">Publications Récentes</h2>
        <p style="color: white;">
          Découvrez les dernières offres et annonces partagées par la communauté.
        </p>
      </div>
    </div>

    <!-- Carrousel -->
    <div class="carousel-container position-relative">
      <!-- Flèche gauche -->
      <button class="carousel-arrow prev-arrow" id="prevBtn">
        <i class="fas fa-chevron-left"></i>
      </button>
      
      <!-- Flèche droite -->
      <button class="carousel-arrow next-arrow" id="nextBtn">
        <i class="fas fa-chevron-right"></i>
      </button>

      <!-- Conteneur des cartes -->
      <div class="cards-wrapper" id="cardsWrapper">
        <div class="cards-row" id="cardsRow">
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

              $short_desc = strlen($row['description']) > 150 
                ? substr($row['description'], 0, 150).'...' 
                : $row['description'];
          ?>
          
          <div class="card-item">
            <div class="card h-100 shadow-sm border-0 publication-card">
              <div class="card-body d-flex flex-column">

                <!-- Type -->
                <span class="badge mb-2"
                      style="background:#ffcc00; color:#012587; width:fit-content;">
                  <?= htmlspecialchars($row['type']); ?>
                </span>

                <!-- Titre -->
                <h5 style="color:#012587; font-weight:600;">
                  <?= htmlspecialchars($row['title']); ?>
                </h5>

                <!-- Description -->
                <p class="text-muted" style="font-size:0.9rem;">
                  <?= htmlspecialchars($short_desc); ?>
                </p>

                <!-- Infos supplémentaires -->
                <div class="mb-3" style="font-size:0.85rem;">

                  <?php if($event_date): ?>
                    <div><i class="far fa-calendar text-primary"></i> <?= $event_date; ?></div>
                  <?php endif; ?>

                  <?php if(!empty($row['heure'])): ?>
                    <div><i class="far fa-clock text-primary"></i> <?= htmlspecialchars($row['heure']); ?></div>
                  <?php endif; ?>

                  <?php if(!empty($row['lieu'])): ?>
                    <div><i class="fas fa-map-marker-alt text-primary"></i> <?= htmlspecialchars($row['lieu']); ?></div>
                  <?php endif; ?>

                </div>

                <!-- Footer -->
                <small class="text-muted mb-3">
                  Publié le <?= $created; ?>
                </small>

                <!-- Bouton En savoir plus -->
                <?php if(!empty($row['lien'])): ?>
                  <a href="<?= htmlspecialchars($row['lien']); ?>"
                     target="_blank"
                     class="btn mt-auto"
                     style="background:#012587; color:white; border-radius:30px;">
                    En savoir plus
                  </a>
                <?php endif; ?>

              </div>
            </div>
          </div>

          <?php endwhile; else: ?>
            <div class="col-12 text-center text-white">
              <p>Aucune publication disponible.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>
</div>

<style>
.publication-card {
  border-radius: 15px;
  transition: 0.3s ease;
  height: 100%;
}

.publication-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

/* Styles du carrousel */
.carousel-container {
  position: relative;
  width: 100%;
  overflow: hidden;
  padding: 10px 0;
}

.cards-wrapper {
  overflow-x: hidden;
  width: 100%;
}

.cards-row {
  display: flex;
  gap: 20px;
  transition: transform 0.5s ease;
  will-change: transform;
}

.card-item {
  flex: 0 0 300px; /* Largeur fixe de 300px par carte */
  max-width: 300px;
  min-width: 300px;
}

/* Style des flèches */
.carousel-arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: white;
  border: none;
  box-shadow: 0 2px 10px rgba(0,0,0,0.2);
  cursor: pointer;
  z-index: 10;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  color: #012587;
}

.carousel-arrow:hover {
  background: #ffcc00;
  transform: translateY(-50%) scale(1.1);
}

.prev-arrow {
  left: -20px;
}

.next-arrow {
  right: -20px;
}

/* Responsive */
@media (max-width: 768px) {
  .prev-arrow {
    left: -10px;
  }
  
  .next-arrow {
    right: -10px;
  }
  
  .card-item {
    flex: 0 0 260px;
    max-width: 260px;
    min-width: 260px;
  }
}

@media (max-width: 576px) {
  .carousel-arrow {
    width: 30px;
    height: 30px;
  }
  
  .card-item {
    flex: 0 0 220px;
    max-width: 220px;
    min-width: 220px;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const wrapper = document.getElementById('cardsWrapper');
  const row = document.getElementById('cardsRow');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  
  let currentPosition = 0;
  const cardItems = document.querySelectorAll('.card-item');
  const cardWidth = cardItems[0]?.offsetWidth || 300;
  const gap = 20; // Gap de 20px entre les cartes
  
  // Calculer la largeur totale d'une carte + gap
  const itemWidth = cardWidth + gap;
  
  // Nombre de cartes visibles basé sur la largeur du wrapper
  function getVisibleCards() {
    const wrapperWidth = wrapper.offsetWidth;
    return Math.floor(wrapperWidth / itemWidth);
  }
  
  // Largeur totale du conteneur
  const totalWidth = cardItems.length * itemWidth;
  
  // Mettre à jour l'affichage des flèches
  function updateArrows() {
    const maxPosition = Math.max(0, cardItems.length - getVisibleCards());
    
    prevBtn.style.display = currentPosition > 0 ? 'flex' : 'none';
    nextBtn.style.display = currentPosition < maxPosition ? 'flex' : 'none';
  }
  
  // Défiler vers la gauche
  function scrollPrev() {
    const visibleCards = getVisibleCards();
    if (currentPosition > 0) {
      currentPosition--;
      row.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
    }
    updateArrows();
  }
  
  // Défiler vers la droite
  function scrollNext() {
    const visibleCards = getVisibleCards();
    const maxPosition = Math.max(0, cardItems.length - visibleCards);
    
    if (currentPosition < maxPosition) {
      currentPosition++;
      row.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
    }
    updateArrows();
  }
  
  // Événements des flèches
  prevBtn.addEventListener('click', scrollPrev);
  nextBtn.addEventListener('click', scrollNext);
  
  // Mettre à jour lors du redimensionnement
  let resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
      // Réinitialiser la position si nécessaire
      const visibleCards = getVisibleCards();
      const maxPosition = Math.max(0, cardItems.length - visibleCards);
      
      if (currentPosition > maxPosition) {
        currentPosition = maxPosition;
        row.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
      }
      
      updateArrows();
    }, 250);
  });
  
  // Initialisation
  setTimeout(() => {
    updateArrows();
  }, 100);
});
</script>
