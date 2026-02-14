<!-- Section Mentors -->
<div class="site-section" style="background-color:  #ffcc00; padding: 60px;" data-aos="fade-up" id="mentors">
  <div class="container">
    <div class="row justify-content-center mb-4">
      <div class="col-md-8 text-center">
        <h2 class="section-title mb-3" style="font-weight:700; color: #012587; margin-top: 50px;">Nos Alumnis Mentor</h2>
        <p style="font-size:1.1rem; color: #555;">
         Nos anciens étudiants prêts à partager leurs connaissances et vous guider dans votre parcours.
        </p>
      </div>
    </div>

    <?php
    require_once '../../functions/db_connect.php';
    $sql = "SELECT * FROM users 
            WHERE account_type = 'alumni' 
            AND statut_id = 2 
            AND mentorat = 1 
            ORDER BY fullname ASC";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0):
    ?>
    
    <div class="row">
      <div class="col-12">
        <!-- Container swippable -->
        <div class="mentors-slider swiper-container" style="overflow: hidden; padding: 20px 0;"  >
          <div class="swiper-wrapper">
            <?php while($row = $result->fetch_assoc()): ?>
            <div class="swiper-slide" style="width: 300px; height: auto;">
              <div class="card mentor-card h-100 border-0 shadow-sm" data-aos="zoom-in" data-aos-delay="100">
                <div class="card-body text-center p-4">
                  <!-- Avatar avec initiales -->
                  <div class="mentor-avatar mb-3 mx-auto" 
                       style="width: 80px; height: 80px; background: linear-gradient(135deg, #012587, #ffcc00); 
                              border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 2rem; font-weight: bold; color: white;">
                      <?php 
                      // Afficher la première lettre du prénom
                      $names = explode(' ', $row['fullname']);
                      echo strtoupper(substr($names[0], 0, 1));
                      ?>
                    </span>
                  </div>
                  
                  <h5 class="card-title mb-2" style="color: #012587; font-weight: 600;">
                    <?php echo htmlspecialchars($row['fullname']); ?>
                  </h5>
                  
                  <p class="text-muted mb-1" style="font-size: 0.9rem;">
                    <i class="fas fa-graduation-cap"></i> 
                    <?php echo htmlspecialchars($row['faculty']); ?>
                  </p>
                  
                  <p class="text-muted mb-2" style="font-size: 0.85rem;">
                    <i class="fas fa-<?php echo ($row['gender'] == 'male') ? 'mars' : 'venus'; ?>"></i>
                    <?php echo ($row['gender'] == 'male') ? 'Homme' : 'Femme'; ?>
                  </p>
                  
                  <!-- Contacts -->
                  <div class="mentor-contacts mt-3">
                    <?php if($row['email']): ?>
                    <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" 
                       class="btn btn-sm btn-outline-primary mb-2" 
                       style="display: block; margin: 0 auto 5px; width: 80%;">
                      <i class="fas fa-envelope"></i> Email
                    </a>
                    <?php endif; ?>
                    
                    <?php if($row['whatsapp']): ?>
                    <a href="https://wa.me/<?php echo htmlspecialchars($row['whatsapp']); ?>" 
                       target="_blank" 
                       class="btn btn-sm btn-success mb-2" 
                       style="display: block; margin: 0 auto; width: 80%;">
                      <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <?php endwhile; ?>
          </div>
          
          <!-- Navigation buttons -->
          <div class="swiper-button-next" style="color: #012587;"></div>
          <div class="swiper-button-prev" style="color: #012587;"></div>
          
          <!-- Pagination dots -->
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </div>
    
    <?php else: ?>
    <div class="row justify-content-center">
      <div class="col-md-8 text-center">
        <div class="alert alert-info">
          <p class="mb-0">Aucun mentor disponible pour le moment. Revenez bientôt !</p>
        </div>
      </div>
    </div>
    <?php 
    endif;
    $conn->close();
    ?>

    <div class="row mt-4 justify-content-center">
      <div class="col-md-10 text-center">
        <p style="color:#666; font-size:0.95rem;">
          Nos mentors sont disponibles pour vous accompagner dans vos projets académiques et professionnels.
        </p>
      </div>
    </div>
  </div>
</div>

<!-- Styles additionnels pour le slider -->
<style>
.mentor-card {
  transition: transform 0.3s ease;
  border-radius: 15px;
}

.mentor-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
}

.swiper-container {
  width: 100%;
  height: 100%;
}

.swiper-slide {
  display: flex;
  justify-content: center;
  align-items: center;
}

.swiper-button-next, .swiper-button-prev {
  background-color: white;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.swiper-button-next:after, .swiper-button-prev:after {
  font-size: 20px;
}

.swiper-pagination-bullet-active {
  background-color: #012587;
}
</style>

<!-- Inclusion de Swiper JS et CSS -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<script>
// Initialisation du slider Swiper
document.addEventListener('DOMContentLoaded', function() {
  var swiper = new Swiper('.mentors-slider', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    breakpoints: {
      640: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 25,
      },
      1024: {
        slidesPerView: 4,
        spaceBetween: 30,
      },
    }
  });
});
</script>