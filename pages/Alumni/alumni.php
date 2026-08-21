<!-- Section Alumni -->
<section class="alumni-section py-5 bg-light-custom" id="alumni-section">
  <div class="container py-4">

    <?php
    require_once '../../functions/db_connect.php';

    if (!isset($conn) || $conn->connect_error):
    ?>
      <!-- ERREUR BASE DE DONNÉES -->
      <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
          <div class="card border-0 rounded-4 shadow-sm p-4">
            <i class="fa-solid fa-triangle-exclamation fa-3x text-warning mb-3"></i>
            <h5 class="fw-bold mb-1">Service temporairement indisponible</h5>
            <p class="text-muted mb-0">La base de données est indisponible pour le moment.</p>
          </div>
        </div>
      </div>
    <?php else: 

      // Inclusion de la session utilisateur
      require_once 'session.php';
      
      $search_value = isset($_GET['search_alumni']) ? trim($_GET['search_alumni']) : '';
    ?>

    <!-- EN-TÊTE ET BARRE DE RECHERCHE -->
    <div class="row justify-content-center mb-5 text-center">
      <div class="col-lg-8 fade-up">
        <h2 class="display-5 fw-bold mb-2">Annuaire des Alumni</h2>
        <p class="fs-6 mb-4 text-muted">
          Connectez-vous et échangez avec les anciens membres de la communauté.
        </p>

        <!-- Formulaire de Recherche -->
        <form method="GET" action="#alumni-section" class="d-flex justify-content-center">
          <div class="input-group search-wrapper shadow-sm rounded-pill bg-white p-1" style="max-width: 600px; border: 1px solid #e0e0e0;">
            <span class="input-group-text bg-transparent border-0 ps-3">
              <i class="fa-solid fa-magnifying-glass text-muted"></i>
            </span>
            <input type="text" 
                   name="search_alumni" 
                   class="form-control border-0 bg-transparent shadow-none" 
                   placeholder="Rechercher par nom, faculté ou email..." 
                   value="<?= htmlspecialchars($search_value); ?>">
            
            <?php if(!empty($search_value)): ?>
              <a href="?" class="btn btn-link text-muted align-self-center px-2 text-decoration-none">
                <i class="fa-solid fa-xmark fs-5"></i>
              </a>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary rounded-pill px-4 ms-1 fw-semibold">
              Rechercher
            </button>
          </div>
        </form>
      </div>
    </div>

    <?php
    // Construction de la requête SQL sécurisée
    $conditions = ["account_type = 'alumni'", "statut_id = 2"];
    
    // Exclusion de l'utilisateur connecté si présent
    if (isset($user_id) && !empty($user_id)) {
        $conditions[] = "id != " . intval($user_id);
    }
    
    if(!empty($search_value)) {
        $search_term = $conn->real_escape_string($search_value);
        $conditions[] = "(fullname LIKE '%$search_term%' OR faculty LIKE '%$search_term%' OR email LIKE '%$search_term%')";
    }
    
    $where_clause = implode(" AND ", $conditions);
    $sql = "SELECT * FROM users WHERE $where_clause ORDER BY fullname ASC";
    $result = $conn->query($sql);
    ?>

    <!-- LISTE DES ALUMNI -->
    <?php if ($result && $result->num_rows > 0): ?>

      <div class="row g-4">
        <?php while($row = $result->fetch_assoc()): 
          
          // Badge genre & Couleurs
          $gender = $row['gender'] ?? 'other';
          $gender_config = match($gender) {
            'male'   => ['label' => 'Homme', 'icon' => 'fa-mars', 'bg' => 'bg-primary-subtle', 'text' => 'text-primary'],
            'female' => ['label' => 'Femme', 'icon' => 'fa-venus', 'bg' => 'bg-danger-subtle', 'text' => 'text-danger'],
            default  => ['label' => 'Non spécifié', 'icon' => 'fa-genderless', 'bg' => 'bg-secondary-subtle', 'text' => 'text-secondary']
          };

          // Nettoyage WhatsApp
          $whatsapp_link = '';
          if(!empty($row['whatsapp'])) {
            $whatsapp = preg_replace('/[^0-9]/', '', $row['whatsapp']);
            $whatsapp_link = 'https://wa.me/' . $whatsapp;
          }
        ?>
        
        <!-- Carte Alumni -->
        <div class="col-lg-4 col-md-6 fade-up">
          <div class="card h-100 p-4 rounded-4 bg-white border-0 shadow-sm pub-card-outline">
            <div class="card-body p-0 d-flex flex-column text-center">

              <!-- Badge Genre -->
              <div class="mb-3">
                <span class="badge rounded-pill <?= $gender_config['bg']; ?> <?= $gender_config['text']; ?> px-3 py-2 fw-medium">
                  <i class="fa-solid <?= $gender_config['icon']; ?> me-1"></i> <?= $gender_config['label']; ?>
                </span>
              </div>

              <!-- Nom complet -->
              <h3 class="h5 fw-bold mb-3 text-dark">
                <?= htmlspecialchars($row['fullname']); ?>
              </h3>

              <!-- Bloc Informations (Faculté & Email) -->
              <div class="bg-light rounded-3 p-3 mb-4 text-start flex-grow-1">
                <div class="d-flex align-items-center mb-2 fs-7 text-secondary">
                  <i class="fa-solid fa-graduation-cap me-2 text-primary" style="width: 20px;"></i>
                  <span class="fw-medium text-dark">
                    <?= htmlspecialchars($row['faculty'] ?? 'Faculté non spécifiée'); ?>
                  </span>
                </div>
                
                <div class="d-flex align-items-center fs-7 text-secondary">
                  <i class="fa-regular fa-envelope me-2 text-primary" style="width: 20px;"></i>
                  <span class="text-truncate" style="max-width: 220px;">
                    <?= htmlspecialchars($row['email']); ?>
                  </span>
                </div>
              </div>

              <!-- Bouton d'action WhatsApp -->
              <div class="mt-auto">
                <?php if(!empty($whatsapp_link)): ?>
                  <a href="<?= $whatsapp_link; ?>" target="_blank" rel="noopener" class="btn btn-whatsapp w-100 py-2 d-inline-flex align-items-center justify-content-center gap-2 fs-7 fw-semibold">
                    <i class="fa-brands fa-whatsapp fs-5"></i> Contacter sur WhatsApp
                  </a>
                <?php else: ?>
                  <button class="btn btn-light text-muted w-100 py-2 fs-7 border-0" disabled>
                    <i class="fa-brands fa-whatsapp me-1"></i> Non disponible
                  </button>
                <?php endif; ?>
              </div>

            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>

    <?php elseif ($result && $result->num_rows == 0): ?>

      <!-- AUCUN RÉSULTAT -->
      <div class="row justify-content-center py-5">
        <div class="col-lg-6 text-center">
          <div class="card border-0 rounded-4 shadow-sm p-5 bg-white">
            <i class="fa-solid fa-user-graduate fa-3x text-muted mb-3"></i>
            <h4 class="fw-bold mb-2">Aucun alumni trouvé</h4>
            <p class="text-muted mb-0">
              <?php if(!empty($search_value)): ?>
                Aucun résultat ne correspond à la recherche "<strong><?= htmlspecialchars($search_value); ?></strong>".
              <?php else: ?>
                Aucun membre alumni n'est disponible actuellement.
              <?php endif; ?>
            </p>
          </div>
        </div>
      </div>

    <?php else: ?>

      <!-- ERREUR REQUÊTE -->
      <div class="alert alert-danger rounded-4 text-center p-4" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i>
        Erreur lors du chargement des profils alumni.
      </div>

    <?php 
        endif;
      endif;
    ?>

  </div>
</section>

<!-- STYLES DÉDIÉS COMPATIBLES -->
<style>
.pub-card-outline {
  border: 1px solid rgba(0, 0, 0, 0.08) !important;
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.pub-card-outline:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08) !important;
}

/* Bouton WhatsApp sur mesure */
.btn-whatsapp {
  background-color: #25D366;
  color: #ffffff;
  border: none;
  border-radius: 10px;
  transition: background-color 0.2s ease, transform 0.2s ease;
}

.btn-whatsapp:hover {
  background-color: #1eb957;
  color: #ffffff;
  transform: translateY(-1px);
}

.fs-7 {
  font-size: 0.875rem;
}
</style>