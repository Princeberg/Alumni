<!-- =============================================
   SECTION MENTORAT - DESIGN HARMONISÉ
   ============================================= -->

<section id="mentors" class="mentors-section section-padding">
  <div class="container">
    
    <!-- En-tête de la section -->
    <div class="row justify-content-center mb-5">
      <div class="col-lg-8 text-center" data-aos="fade-up">
        <h2 class="mentors-section-title mb-3">Nos Alumnis Mentors</h2>
        <div class="title-underline mx-auto mb-3"></div>
        <p class="mentors-section-subtitle">
          Nos anciens étudiants prêts à partager leurs connaissances et à vous guider dans votre parcours académique et professionnel.
        </p>
      </div>
    </div>

    <?php
    require_once '../../functions/db_connect.php';
    
    $sql = "SELECT * FROM users 
            WHERE account_type = 'alumni' 
            AND statut_id = 2 
            AND mentorat IS NOT NULL
            AND mentorat = 1   
            ORDER BY fullname ASC";
    
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0):
    ?>
    
    <div class="row" data-aos="fade-up" data-aos-delay="100">
      <div class="col-12">
        <div class="mentors-slider swiper-container">
          <div class="swiper-wrapper py-3">
            
            <?php while($row = $result->fetch_assoc()): 
              
              $words = explode(' ', trim($row['fullname']));
              $initials = mb_substr($words[0] ?? '', 0, 1) . mb_substr($words[1] ?? '', 0, 1);
              $initials = strtoupper($initials);
            ?>
            <div class="swiper-slide">
              <div class="card mentor-card h-100 border-0">
                <div class="card-body text-center p-4 d-flex flex-column align-items-center justify-content-between">
                  
                  <div class="w-100">
                    
                    <!-- Nom du mentor -->
                    <h5 class="mentor-name mb-2">
                      <?php echo htmlspecialchars($row['fullname']); ?>
                    </h5>
                    
                    <!-- Faculté / Spécialité -->
                    <div class="mentor-faculty mb-3">
                      <i class="fa-solid fa-graduation-cap mr-1"></i> 
                      <span><?php echo htmlspecialchars($row['faculty']); ?></span>
                    </div>
                  </div>

                  <!-- Actions de contact -->
                  <div class="mentor-contacts w-100 mt-2">
                    <?php if(!empty($row['email'])): ?>
                    <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="btn-contact btn-email mb-2">
                      <i class="fa-solid fa-envelope"></i> Email
                    </a>
                    <?php endif; ?>
                    
                    <?php if(!empty($row['whatsapp'])): 
                      // Nettoyage du numéro pour WhatsApp
                      $clean_phone = preg_replace('/[^0-9]/', '', $row['whatsapp']);
                    ?>
                    <a href="https://wa.me/<?php echo htmlspecialchars($clean_phone); ?>" target="_blank" class="btn-contact btn-whatsapp">
                      <i class="fa-brands fa-whatsapp"></i> WhatsApp
                    </a>
                    <?php endif; ?>
                  </div>

                </div>
              </div>
            </div>
            <?php endwhile; ?>

          </div>
          
          <!-- Commandes de navigation Swiper -->
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </div>
    
    <?php else: ?>
    <!-- Message si aucun mentor n'est disponible -->
    <div class="row justify-content-center">
      <div class="col-md-8 text-center">
        <div class="no-mentors-alert p-4 rounded-lg">
          <i class="fa-solid fa-user-clock mb-2 fa-2x" style="color: #012587;"></i>
          <p class="m-0 font-weight-bold" style="color: #012587;">Aucun mentor disponible pour le moment. Revenez bientôt !</p>
        </div>
      </div>
    </div>
    <?php 
    endif;
    $conn->close();
    ?>

    <!-- Note d'information globale -->
    <div class="row mt-4 justify-content-center">
      <div class="col-md-10 text-center">
        <p class="mentors-footer-text m-0">
          <i class="fa-solid fa-circle-info mr-1"></i> Nos mentors sont disponibles pour vous accompagner gracieusement dans vos projets académiques et professionnels.
        </p>
      </div>
    </div>

  </div>
</section>
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  if (document.querySelector('.mentors-slider')) {
    new Swiper('.mentors-slider', {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: false,
      autoplay: {
        delay: 4000,
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
        576: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        992: {
          slidesPerView: 3,
          spaceBetween: 25,
        },
        1200: {
          slidesPerView: 4,
          spaceBetween: 25,
        }
      }
    });
  }
});
</script>