<!-- SECTION PUBLICATIONS -->
<div class="site-section" id="publications" style="background-color: #012587; padding: 80px 0;">
  <div class="container">

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

    <div class="row">
      <?php
      require_once '../../functions/db_connect.php';

      $sql = "SELECT id, title, description, type, created_at, date, lieu, heure, lien 
              FROM posts 
              ORDER BY created_at ";

      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0):
        while($row = $result->fetch_assoc()):

          $created = date("d/m/Y", strtotime($row['created_at']));
          $event_date = !empty($row['date']) ? date("d/m/Y", strtotime($row['date'])) : null;

          $short_desc = strlen($row['description']) > 150 
            ? substr($row['description'], 0, 150).'...' 
            : $row['description'];
      ?>
      
      <div class="col-md-4 mb-4">
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

        <form method="POST" action="create_post.php">

          <!-- Titre -->
          <div class="form-group">
            <label class="font-weight-bold">Titre</label>
            <input type="text" name="title" class="form-control" required>
          </div>

          <!-- Type -->
          <div class="form-group">
            <label class="font-weight-bold">Type</label>
            <select name="type" class="form-control" required>
              <option value="">-- Sélectionner --</option>
              <option value="Offre">Offre</option>
              <option value="Annonce">Annonce</option>
              <option value="Evenement">Evenement</option>
              <option value="Promotion">Promotion</option>
            </select>
          </div>

          <!-- Date -->
          <div class="form-group">
            <label class="font-weight-bold">Date</label>
            <input type="date" name="date" class="form-control">
          </div>

          <!-- Heure -->
          <div class="form-group">
            <label class="font-weight-bold">Heure</label>
            <input type="time" name="heure" class="form-control">
          </div>

          <!-- Lieu -->
          <div class="form-group">
            <label class="font-weight-bold">Lieu</label>
            <input type="text" name="lieu" class="form-control" placeholder="Ex: Brazzaville, Salle X">
          </div>

          <!-- Lien -->
          <div class="form-group">
            <label class="font-weight-bold">Lien externe</label>
            <input type="url" name="lien" class="form-control" placeholder="https://...">
          </div>

          <!-- Description -->
          <div class="form-group">
            <label class="font-weight-bold">Description</label>
            <textarea name="description" rows="4" class="form-control" required></textarea>
          </div>

          <!-- Bouton -->
          <button type="submit"
                  name="create_post"
                  class="btn btn-block"
                  style="background:#012587; color:white; border-radius:30px;">
            Publier
          </button>

        </form>

      </div>
    </div>
  </div>
</div>


<style>
.publication-card {
  border-radius: 15px;
  transition: 0.3s ease;
}

.publication-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
</style>
