<!-- Section Alumni -->
<section class="alumni-section py-5" id="alumni-section">
  <div class="container py-4">

    <?php
    require_once '../../functions/db_connect.php';

    if (!isset($conn) || $conn->connect_error):
    ?>
      <!-- ERREUR BASE DE DONNÉES -->
      <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
          <div class="card border-0 rounded-4 shadow-sm p-4">
            <i class="fa-solid fa-triangle-exclamation fa-3x mb-3" style="color: var(--primary);"></i>
            <h5 class="fw-bold mb-1" style="color: var(--primary);">Service temporairement indisponible</h5>
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
        <h2 class="display-5 fw-bold mb-2" style="color: var(--primary); font-weight: 700; text-transform: uppercase;">Annuaire des Alumnis</h2>
        <p class="fs-6 mb-4" style="color: var(--primary);">
          Connectez-vous et échangez avec les anciens membres de la communauté.
        </p>

        <!-- Formulaire de Recherche -->
        <form method="GET" action="#alumni-section" class="d-flex justify-content-center">
          <div class="input-group search-wrapper shadow-sm bg-white p-1" style="max-width: 600px; border: 1px solid rgba(0,0,0,0.1);">
            <span class="input-group-text bg-transparent border-0 ps-3">
              <i class="fa-solid fa-magnifying-glass" style="color: var(--primary);"></i>
            </span>
            <input type="text" 
                   name="search_alumni" 
                   class="form-control border-0 bg-transparent shadow-none" 
                   placeholder="Rechercher par nom, faculté ou email..." 
                   value="<?= htmlspecialchars($search_value); ?>">
            
            <?php if(!empty($search_value)): ?>
              <a href="?" class="btn btn-link align-self-center px-2 text-decoration-none" style="color: var(--secondary);">
                <i class="fa-solid fa-xmark fs-5"></i>
              </a>
            <?php endif; ?>

            <button type="submit" class="btn btn-search-custom px-4 ms-1 fw-semibold">
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
          
          // Nettoyage WhatsApp
          $whatsapp_link = '';
          if(!empty($row['whatsapp'])) {
            $whatsapp = preg_replace('/[^0-9]/', '', $row['whatsapp']);
            $whatsapp_link = 'https://wa.me/' . $whatsapp;
          }
        ?>
        
        <!-- Carte Alumni -->
        <div class="col-lg-4 col-md-6 fade-up">
          <div class="card h-100 p-4 border-0 shadow-sm pub-card">
            <div class="card-body p-0 d-flex flex-column text-center">

              <!-- Nom complet -->
              <h3 class="h5 fw-bold mb-3" style="color: var(--secondary);">
                <?= htmlspecialchars($row['fullname']); ?>
              </h3>

              <!-- Bloc Informations (Faculté & Email) -->
              <div class="alumni-info-box rounded-3 p-3 mb-4 text-start flex-grow-1">
                <div class="d-flex align-items-center mb-2 fs-7">
                  <i class="fa-solid fa-graduation-cap me-2" style="color: var(--primary); width: 20px;"></i>
                  <span class="fw-medium">
                    <?= htmlspecialchars($row['faculty'] ?? 'Faculté non spécifiée'); ?>
                  </span>
                </div>
                
                <div class="d-flex align-items-center fs-7">
                  <i class="fa-regular fa-envelope me-2" style="color: var(--primary); width: 20px;"></i>
                  <span class="text-truncate" style="max-width: 220px;">
                    <?= htmlspecialchars($row['email']); ?>
                  </span>
                </div>
              </div>

              <!-- Bouton d'action WhatsApp -->
              <div class="mt-auto">
                <?php if(!empty($whatsapp_link)): ?>
                  <a href="<?= $whatsapp_link; ?>" target="_blank" rel="noopener" class="btn btn-w w-100 py-2 d-inline-flex align-items-center justify-content-center gap-2 fs-7 fw-semibold">
                    </i> Contacter sur WhatsApp
                  </a>
                <?php else: ?>
                  <button class="btn btn-light w-100 py-2 fs-7 border-0" disabled style="opacity: 0.6;">
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
            <i class="fa-solid fa-user-graduate fa-3x mb-3" style="color: var(--primary);"></i>
            <h4 class="fw-bold mb-2" style="color: var(--primary);">Aucun alumni trouvé</h4>
            <p class="mb-0">
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