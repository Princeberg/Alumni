<!-- SECTION PUBLICATIONS -->
<div class="site-section" id="publications" style="background-color: #012587; padding: 80px 0;">
  <div class="container">

    <!-- Messages de notification -->
    <?php
    if (isset($_SESSION['success_message'])): ?>
      <div class="row justify-content-center mb-4">
        <div class="col-md-8">
          <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px;">
            <i class="fas fa-check-circle mr-2"></i>
            <?= $_SESSION['success_message']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>
      </div>
      <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_messages'])): ?>
      <div class="row justify-content-center mb-4">
        <div class="col-md-8">
          <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px;">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>Erreur(s) :</strong>
            <ul class="mb-0 mt-2">
              <?php foreach ($_SESSION['error_messages'] as $error): ?>
                <li><?= $error; ?></li>
              <?php endforeach; ?>
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>
      </div>
      <?php unset($_SESSION['error_messages']); ?>
    <?php endif; ?>

    <div class="row justify-content-center mb-4">
      <div class="col-md-8 text-center">
        <h2 style="font-weight:700; color: #ffcc00;">Publications Récentes</h2>
        <p style="color: white;">
          Découvrez les dernières offres et annonces partagées par la communauté.
        </p>

        <!-- Bouton Ajouter -->
        <button class="btn btn-warning mt-3"
                data-toggle="modal"
                data-target="#addPostModal"
                style="background:#ffcc00; color:#012587; font-weight:bold; border-radius:30px; padding:10px 25px;">
          <i class="fas fa-plus"></i> Ajouter une publication
        </button>
      </div>
    </div>

    <!-- Carousel horizontal -->
    <div class="position-relative">
      <!-- Flèches de navigation -->
      <button class="carousel-control-prev" id="prevBtn" style="left: -30px; width: 40px; height: 40px; background: #ffcc00; border-radius: 50%; top: 50%; transform: translateY(-50%);">
        <i class="fas fa-chevron-left" style="color: #012587; font-size: 20px;"></i>
      </button>
      <button class="carousel-control-next" id="nextBtn" style="right: -30px; width: 40px; height: 40px; background: #ffcc00; border-radius: 50%; top: 50%; transform: translateY(-50%);">
        <i class="fas fa-chevron-right" style="color: #012587; font-size: 20px;"></i>
      </button>

      <!-- Conteneur des cartes avec défilement horizontal -->
      <div class="publications-slider" style="overflow-x: auto; scroll-behavior: smooth; padding: 10px 0; margin: 0 20px;" id="sliderContainer">
        <div class="d-flex flex-nowrap" style="gap: 20px;">
          <?php
          require_once '../../functions/db_connect.php';

          // Requête avec jointure pour récupérer les informations de l'utilisateur
          $sql = "SELECT p.*, u.id, u.fullname
                  FROM posts p 
                  LEFT JOIN users u ON p.user_id = u.id 
                  ORDER BY p.created_at DESC";

          $result = $conn->query($sql);

          if ($result && $result->num_rows > 0):
            while($row = $result->fetch_assoc()):

              $created = date("d/m/Y", strtotime($row['created_at']));
              $event_date = !empty($row['date']) ? date("d/m/Y", strtotime($row['date'])) : null;

              $short_desc = strlen($row['description']) > 150 
                ? substr($row['description'], 0, 150).'...' 
                : $row['description'];
              
          ?>
          
          <div class="card publication-card" style="min-width: 300px; max-width: 350px; flex: 0 0 auto;">
            <div class="card-body d-flex flex-column">

              <!-- Type et Utilisateur -->
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge" style="background:#ffcc00; color:#012587;">
                  <?= htmlspecialchars($row['type']); ?>
                </span>
                
              </div>

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

              <!-- Footer avec date de publication -->
              <small class="text-muted mb-3">
                <i class="far fa-clock"></i> Publié le <?= $created; ?>
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


<!-- MODAL CREATION POST -->
<div class="modal fade" id="addPostModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:15px; overflow:hidden;">

      <!-- Header -->
      <div class="modal-header" style="background:#012587;">
        <h5 class="modal-title text-white">
          <i class="fas fa-pen mr-2"></i> Nouvelle Publication
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          &times;
        </button>
      </div>

      <!-- Body -->
      <div class="modal-body p-4">

        <?php
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])): ?>
          <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            Vous devez être <a href="connexion.php" class="alert-link">connecté</a> pour publier une annonce.
          </div>
        <?php else:
          // Récupérer les données du formulaire précédent en cas d'erreur
          $form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
          if (isset($_SESSION['form_data'])) {
            unset($_SESSION['form_data']);
          }
        ?>

        <form method="POST" action="create_post.php">

          <!-- Titre -->
          <div class="form-group">
            <label class="font-weight-bold">Titre <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" 
                   value="<?= isset($form_data['title']) ? htmlspecialchars($form_data['title']) : '' ?>" 
                   required>
          </div>

          <!-- Type -->
          <div class="form-group">
            <label class="font-weight-bold">Type <span class="text-danger">*</span></label>
            <select name="type" class="form-control" required>
              <option value="">-- Sélectionner --</option>
              <option value="Offre" <?= (isset($form_data['type']) && $form_data['type'] == 'Offre') ? 'selected' : '' ?>>Offre</option>
              <option value="Annonce" <?= (isset($form_data['type']) && $form_data['type'] == 'Annonce') ? 'selected' : '' ?>>Annonce</option>
              <option value="Evenement" <?= (isset($form_data['type']) && $form_data['type'] == 'Evenement') ? 'selected' : '' ?>>Événement</option>
              <option value="Promotion" <?= (isset($form_data['type']) && $form_data['type'] == 'Promotion') ? 'selected' : '' ?>>Promotion</option>
            </select>
          </div>

          <!-- Date -->
          <div class="form-group">
            <label class="font-weight-bold">Date</label>
            <input type="date" name="date" class="form-control"
                   value="<?= isset($form_data['date']) ? htmlspecialchars($form_data['date']) : '' ?>">
          </div>

          <!-- Heure -->
          <div class="form-group">
            <label class="font-weight-bold">Heure</label>
            <input type="time" name="heure" class="form-control"
                   value="<?= isset($form_data['heure']) ? htmlspecialchars($form_data['heure']) : '' ?>">
          </div>

          <!-- Lieu -->
          <div class="form-group">
            <label class="font-weight-bold">Lieu</label>
            <input type="text" name="lieu" class="form-control" placeholder="Ex: Brazzaville, Salle X"
                   value="<?= isset($form_data['lieu']) ? htmlspecialchars($form_data['lieu']) : '' ?>">
          </div>

          <!-- Lien -->
          <div class="form-group">
            <label class="font-weight-bold">Lien externe</label>
            <input type="url" name="lien" class="form-control" placeholder="https://..."
                   value="<?= isset($form_data['lien']) ? htmlspecialchars($form_data['lien']) : '' ?>">
          </div>

          <!-- Description -->
          <div class="form-group">
            <label class="font-weight-bold">Description <span class="text-danger">*</span></label>
            <textarea name="description" rows="4" class="form-control" required><?= isset($form_data['description']) ? htmlspecialchars($form_data['description']) : '' ?></textarea>
          </div>

          <!-- Bouton -->
          <button type="submit"
                  name="create_post"
                  class="btn btn-block"
                  style="background:#012587; color:white; border-radius:30px;">
            Publier
          </button>

        </form>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>