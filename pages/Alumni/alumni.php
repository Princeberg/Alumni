<!-- Section Alumni -->
<section class="alumni-section" style="background-color: #ffcc00; padding: 80px 0;" data-aos="fade-up">
  <div class="container">

    <?php
    require_once '../../functions/db_connect.php';
    
    if (!isset($conn) || $conn->connect_error):
    ?>
      <!-- ERREUR BASE DE DONNÉES -->
      <div class="alert-message text-center">
        <div class="alert-card" style="background: white; border-radius: 15px; padding: 40px; border: 2px solid #012587;">
          <i class="fas fa-exclamation-triangle fa-3x mb-3" style="color: #012587;"></i>
          <p style="color: #012587; font-size: 1.1rem;">Base de données non disponible pour le moment.</p>
        </div>
      </div>
    <?php else: 
    
    // Inclure la session pour obtenir l'ID de l'utilisateur connecté
    require_once 'session.php';
    
    ?>
    
    <!-- BARRE DE RECHERCHE SIMPLE -->
    <div class="search-section mb-5" id="search-form">
      <div class="row justify-content-center" >
        <div class="col-lg-8 col-md-10">
          
          <!-- Formulaire de recherche -->
          <form method="GET" action="" class="search-form" >
            <div class="search-wrapper" style="background: white; border-radius: 60px; padding: 5px; box-shadow: 0 10px 30px rgba(1,37,135,0.1); display: flex; align-items: center; margin-top: 50px;">
              
              <!-- Champ de recherche -->
              <div class="search-input-wrapper" style="flex: 1; display: flex; align-items: center; padding: 0 20px;">
                <i class="fas fa-search" style="color: #012587; font-size: 1.1rem;"></i>
                <input type="text" 
                       name="search_alumni" 
                       class="search-input" 
                       placeholder="Rechercher des alumnis" 
                       value="<?php echo isset($_GET['search_alumni']) ? htmlspecialchars($_GET['search_alumni']) : ''; ?>"
                       style="flex: 1; border: none; padding: 15px 15px; margin-left: 10px; font-size: 1rem; outline: none; background: transparent;">
              </div>
              
              <!-- Bouton recherche -->
              <button type="submit" class="btn-search" style="background: #012587; color: #ffcc00; border: none; border-radius: 50px; padding: 15px 35px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                <i class="fas fa-search mr-2"></i> Rechercher
              </button>
              
              <!-- Bouton reset (visible seulement si recherche active) -->
              <?php if(isset($_GET['search_alumni']) && !empty($_GET['search_alumni'])): ?>
                <a href="?reset=1" class="btn-reset" style="color: #012587; margin-left: 15px; margin-right: 10px; font-size: 1.2rem;">
                  <i class="fas fa-times-circle"></i>
                </a>
              <?php endif; ?>
            </div>
          </form>
        
        </div>
      </div>
    </div>
    
    <?php
    
    // MODIFICATION ICI : Exclure l'utilisateur connecté
    $conditions = ["account_type = 'alumni'", "statut_id = 2", "id != $user_id"];
    
    if(isset($_GET['search_alumni']) && !empty($_GET['search_alumni'])) {
        $search_term = $conn->real_escape_string($_GET['search_alumni']);
        $conditions[] = "(fullname LIKE '%$search_term%' OR faculty LIKE '%$search_term%' OR email LIKE '%$search_term%')";
    }
    
    // Requête finale
    $where_clause = implode(" AND ", $conditions);
    $sql = "SELECT * 
            FROM users 
            WHERE $where_clause 
            ORDER BY fullname ASC";
    
    $result = $conn->query($sql);
    ?>
    
    <!-- LISTE DES ALUMNI -->
    <?php if ($result && $result->num_rows > 0): ?>
    
    <div class="alumni-grid">
      <div class="row">
        <?php while($row = $result->fetch_assoc()): 
          
          $initials = '';
          $name_parts = explode(' ', trim($row['fullname']));
          foreach($name_parts as $part) {
            if(!empty($part)) $initials .= strtoupper(substr($part, 0, 1));
          }
          $initials = substr($initials, 0, 2);
          
          $avatar_color = match($row['gender']) {
            'male' => '#012587',
            'female' => '#8b0000',
            default => '#4b0082'
          };
          
          // Nettoyage et formatage WhatsApp
          $whatsapp_link = '';
          if(!empty($row['whatsapp'])) {
            $whatsapp = preg_replace('/[^0-9]/', '', $row['whatsapp']);
            $whatsapp_link = 'https://wa.me/' . $whatsapp;
          }
          
          // Libellé genre
          $gender_label = [
            'male' => 'Homme',
            'female' => 'Femme',
            'other' => 'Autre'
          ];
          $gender_text = $gender_label[$row['gender']] ?? 'Non spécifié';
          $gender_icon = match($row['gender']) {
            'male' => 'fa-mars',
            'female' => 'fa-venus',
            default => 'fa-genderless'
          };
          
        ?>
        
        <!-- Carte Alumni -->
        <div class="col-lg-4 col-md-6 mb-4" id="alumni">
          <div class="alumni-card" style="background: white; border-radius: 15px; border-bottom: 4px solid #ffcc00; box-shadow: 0 10px 30px rgba(0,0,0,0.1); height: 100%; transition: all 0.3s ease;">
            
            <div class="alumni-card-body" style="padding: 30px 25px; text-align: center;">
              
              <!-- Identité -->
              <h3 class="alumni-name mb-2" style="color: #012587; font-weight: 700; font-size: 1.3rem;">
                <?php echo htmlspecialchars($row['fullname']); ?>
              </h3>
              
              <!-- Badge genre -->
              <div class="alumni-gender mb-3">
                <span class="gender-badge" style="background: <?php echo $avatar_color; ?>15; color: <?php echo $avatar_color; ?>; padding: 5px 18px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; display: inline-block;">
                  <i class="fas <?php echo $gender_icon; ?> mr-1"></i> <?php echo $gender_text; ?>
                </span>
              </div>
              
              <!-- Informations académiques -->
              <div class="alumni-info" style="background: #f8f9fa; border-radius: 12px; padding: 18px; margin: 20px 0; text-align: left;">
                
                <div class="info-item d-flex align-items-center mb-2">
                  <i class="fas fa-university" style="color: <?php echo $avatar_color; ?>; width: 25px; text-align: center;"></i>
                  <span style="color: #012587; font-weight: 600; margin-left: 10px; font-size: 15px">
                    <?php echo htmlspecialchars($row['faculty'] ?? 'Faculté non spécifiée'); ?>
                  </span>
                </div>
                
                <div class="info-item d-flex align-items-center">
                  <i class="fas fa-envelope" style="color: <?php echo $avatar_color; ?>; width: 25px; text-align: center;"></i>
                  <span style="color: #666; margin-left: 10px; font-size: 0.9rem; word-break: break-all;">
                    <?php echo htmlspecialchars($row['email']); ?>
                  </span>
                </div>
                
              </div>
              
              <!-- Bouton WhatsApp -->
              <div class="alumni-action">
                <?php if(!empty($whatsapp_link)): ?>
                  <a href="<?php echo $whatsapp_link; ?>" target="_blank" class="btn-whatsapp" 
                     style="display: block; background: #25D366; color: white; border-radius: 50px; padding: 14px 20px; font-weight: 600; text-decoration: none; transition: all 0.3s ease; border: none; width: 100%;">
                    <i class="fab fa-whatsapp mr-2"></i> Contacter
                  </a>
                <?php else: ?>
                  <div class="btn-whatsapp-disabled" 
                       style="display: block; background: #e9ecef; color: #6c757d; border-radius: 50px; padding: 14px 20px; font-weight: 600; text-align: center; cursor: not-allowed;">
                    <i class="fab fa-whatsapp mr-2"></i> WhatsApp non disponible
                  </div>
                <?php endif; ?>
              </div>
              
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
    
    <?php elseif ($result && $result->num_rows == 0): ?>
    
    <!-- AUCUN RÉSULTAT -->
    <div class="no-results text-center">
      <div class="alert-card" style="background: white; border-radius: 20px; padding: 60px 30px; border: 2px solid #012587;">
        <i class="fas fa-user-graduate fa-4x mb-4" style="color: #012587;"></i>
        <h3 style="color: #012587; font-weight: 700; margin-bottom: 15px;">Aucun alumni trouvé</h3>
        <p style="color: #666; margin-bottom: 25px; font-size: 1.1rem;">
          <?php if(isset($_GET['search_alumni']) && !empty($_GET['search_alumni'])): ?>
            Aucun résultat pour "<?php echo htmlspecialchars($_GET['search_alumni']); ?>"
          <?php else: ?>
            Aucun alumni disponible pour le moment.
          <?php endif; ?>
        </p>
      </div>
    </div>
    
    <?php else: ?>
    
    <!-- ERREUR REQUÊTE -->
    <div class="error-message text-center">
      <div class="alert-card" style="background: white; border-radius: 15px; padding: 40px; border: 2px solid #dc3545;">
        <i class="fas fa-exclamation-circle fa-3x mb-3" style="color: #dc3545;"></i>
        <p style="color: #dc3545; font-size: 1.1rem;">Erreur lors du chargement des alumni.</p>
      </div>
    </div>
    
    <?php 
        endif;
      endif;
    ?>
    
  </div>
</section>

<!-- STYLES DÉDIÉS -->
<style>
/* Animation cartes */
.alumni-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.alumni-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 40px rgba(1,37,135,0.15) !important;
}

/* Style barre recherche */
.search-wrapper {
  transition: all 0.3s ease;
  border: 2px solid transparent;
}
.search-wrapper:focus-within {
  border-color: #012587;
  box-shadow: 0 15px 35px rgba(1,37,135,0.2);
}
.btn-search:hover {
  background: #001a5c !important;
  transform: scale(1.02);
}
.btn-reset {
  text-decoration: none;
  transition: color 0.2s ease;
}
.btn-reset:hover {
  color: #001a5c !important;
}

/* Responsive */
@media (max-width: 768px) {
  .search-wrapper {
    flex-direction: column;
    padding: 20px !important;
    border-radius: 30px !important;
  }
  
  .search-input-wrapper {
    width: 100%;
    padding: 0 !important;
    margin-bottom: 15px;
  }
  
  .btn-search {
    width: 100%;
    margin-left: 0 !important;
  }
  
  .btn-reset {
    position: absolute;
    top: 20px;
    right: 20px;
  }
  
  .alumni-card-body {
    padding: 25px 20px !important;
  }
}

/* Animation au scroll */
.alumni-card {
  opacity: 0;
  transform: translateY(30px);
  animation: fadeInUp 0.6s ease forwards;
}

@keyframes fadeInUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Délais animation */
.alumni-card:nth-child(3n+1) { animation-delay: 0.1s; }
.alumni-card:nth-child(3n+2) { animation-delay: 0.2s; }
.alumni-card:nth-child(3n+3) { animation-delay: 0.3s; }
</style>

<!-- SCRIPT RESET -->
<script>
// Nettoyage URL si paramètre reset
document.addEventListener('DOMContentLoaded', function() {
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has('reset')) {
    window.location.href = window.location.pathname;
  }
});
</script>