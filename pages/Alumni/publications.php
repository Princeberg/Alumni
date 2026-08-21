<section class="publications-section py-5 bg-light-custom" id="publications">
  <div class="container py-4">
    
    <!-- Alerts / Messages d'erreur et succès -->
    <?php if (isset($_SESSION['success_message'])): ?>
      <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_messages']) && !empty($_SESSION['error_messages'])): ?>
      <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i>
        <ul class="mb-0 ps-3">
          <?php foreach ($_SESSION['error_messages'] as $error): ?>
            <li><?= $error; ?></li>
          <?php endforeach; unset($_SESSION['error_messages']); ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <!-- En-tête avec bouton Ajouter -->
    <div class="row justify-content-center mb-5 text-center">
      <div class="col-lg-8 fade-up fade-delay-1">
        <h2 class="display-5 fw-bold mb-2">Publications Récentes</h2>
        <p class="fs-6 mb-3">
          Découvrez les dernières informations partagées par la communauté.
        </p>
        <button type="button" class="btn-add px-4 py-2 d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addPostModal">
          <i class="fa-solid fa-plus-circle"></i>
          <span>Ajouter une publication</span>
        </button>
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

      <div class="publications-carousel" id="pubCarousel">
        <?php
        require_once '../../functions/db_connect.php';

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

<?php 
  $formData = $_SESSION['form_data'] ?? [];
  unset($_SESSION['form_data']);
?>

<!-- Modal Ajouter une publication -->
<div class="modal fade" id="addPostModal" tabindex="-1" aria-labelledby="addPostModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 rounded-4 shadow">
      
      <div class="modal-header border-0 pb-0 px-4 pt-4">
        <h5 class="modal-title fw-bold fs-4" id="addPostModalLabel">
          Nouvelle publication
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="create_post.php" method="POST">
        <div class="modal-body p-4">
          <div class="row g-3">
            
            <!-- Titre -->
            <div class="col-md-8">
              <label for="postTitle" class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="postTitle" name="title" placeholder="Ex: Masterclass sur l'IA" value="<?= htmlspecialchars($formData['title'] ?? '') ?>" required>
            </div>

            <!-- Type -->
            <div class="col-md-4">
              <label for="postType" class="form-label fw-semibold">Type de poste <span class="text-danger">*</span></label>
              <select class="form-select" id="postType" name="type" required>
                <option value="" disabled <?= empty($formData['type']) ? 'selected' : '' ?>>Choisir un type...</option>
                <option value="Événement" <?= ($formData['type'] ?? '') === 'Événement' ? 'selected' : '' ?>>Événement</option>
                <option value="Offre d'emploi" <?= ($formData['type'] ?? '') === "Offre d'emploi" ? 'selected' : '' ?>>Offre d'emploi</option>
                <option value="Stage" <?= ($formData['type'] ?? '') === 'Stage' ? 'selected' : '' ?>>Offre de Stage</option>
                <option value="Recherche / Projet" <?= ($formData['type'] ?? '') === 'Recherche / Projet' ? 'selected' : '' ?>>Recherche / Projet</option>
                <option value="Annonce" <?= ($formData['type'] ?? '') === 'Annonce' ? 'selected' : '' ?>>Annonce générale</option>
                <option value="Article / Info" <?= ($formData['type'] ?? '') === 'Article / Info' ? 'selected' : '' ?>>Article / Information</option>
              </select>
            </div>

            <!-- Description -->
            <div class="col-12">
              <label for="postDescription" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
              <textarea class="form-control" id="postDescription" name="description" rows="4" placeholder="Décrivez votre publication..." required><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
            </div>

            <!-- Date -->
            <div class="col-md-4">
              <label for="postDate" class="form-label fw-semibold">Date de l'événement</label>
              <input type="date" class="form-control" id="postDate" name="date" value="<?= htmlspecialchars($formData['date'] ?? '') ?>">
            </div>

            <!-- Heure -->
            <div class="col-md-4">
              <label for="postHeure" class="form-label fw-semibold">Heure</label>
              <input type="time" class="form-control" id="postHeure" name="heure" value="<?= htmlspecialchars($formData['heure'] ?? '') ?>">
            </div>

            <!-- Lieu -->
            <div class="col-md-4">
              <label for="postLieu" class="form-label fw-semibold">Lieu</label>
              <input type="text" class="form-control" id="postLieu" name="lieu" placeholder="Ex: Amphithéâtre A" value="<?= htmlspecialchars($formData['lieu'] ?? '') ?>">
            </div>

            <!-- Lien -->
            <div class="col-12">
              <label for="postLien" class="form-label fw-semibold">Lien externe</label>
              <input type="text" class="form-control" id="postLien" name="lien" placeholder="exemple.com ou https://exemple.com" value="<?= htmlspecialchars($formData['lien'] ?? '') ?>">
            </div>

          </div>
        </div>

        <div class="modal-footer border-0 px-4 pb-4">
          <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" name="create_post" class="btn-add rounded-3 px-4 d-inline-flex align-items-center gap-2">
            <i class="fa-solid fa-paper-plane"></i> Publier
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- Scripts JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script> 
document.addEventListener('DOMContentLoaded', () => {
    // Navigation Carrousel
    const carousel = document.getElementById('pubCarousel');
    const prevBtn = document.getElementById('pubPrevBtn');
    const nextBtn = document.getElementById('pubNextBtn');

    if (carousel && prevBtn && nextBtn) {
        const scrollAmount = 340; // Largeur de carte + espace (gap)

        nextBtn.addEventListener('click', () => {
            carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });

        prevBtn.addEventListener('click', () => {
            carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });
    }

    // Réouverture du Modal en cas d'erreur de formulaire
    <?php if (isset($_SESSION['error_messages'])): ?>
      const modalElement = document.getElementById('addPostModal');
      if (modalElement && typeof bootstrap !== 'undefined') {
        const addModal = new bootstrap.Modal(modalElement);
        addModal.show();
      }
    <?php endif; ?>
});
</script>