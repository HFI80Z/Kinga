<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Véhicules</title>
    <img src="/assets/img/user.png" alt="Avatar">
    <style>
      /* styles rapide pour le modal */
      #overlay {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 999;
      }
      #modal {
        display: none;
        position: fixed;
        top: 20%; left: 50%;
        transform: translateX(-50%);
        background: #fff;
        padding: 20px;
        border: 1px solid #333;
        z-index: 1000;
        min-width: 300px;
      }
      #modal h2 { margin-top: 0; }
      #modal button { margin-right: 8px; }

      header { position: relative; padding-bottom: 1em; }
      header h1 { margin: 0; }

      .status-bar {
        background: #f5f5f5;
        padding: 0.5em;
        margin-bottom: 1em;
        font-size: 0.9em;
      }
      .status-bar span { font-weight: bold; }

      .user-info {
        position: absolute;
        top: 0;
        right: 0;
        display: flex;
        align-items: center;
      }
      .user-info img {
        width: 32px; height: 32px;
        margin-right: 0.5em;
      }

      .auth-buttons { margin: 1em 0; }
      .auth-buttons a { margin-right: 0.5em; }
    </style>
</head>
<body>
    <?php
    use App\Core\Auth;
    // Session déjà lancée en public/index.php
    $user = $_SESSION['user'] ?? null;
    ?>

    <!-- barre de statut -->
    <div class="status-bar">
      <?php if ($user): ?>
        Connecté en tant que <span><?= htmlspecialchars($user['user_firstname'] . ' ' . $user['user_name'], ENT_QUOTES) ?></span>
      <?php else: ?>
        <span>Non connecté</span>
      <?php endif; ?>
    </div>

    <header>
      <?php if ($user): ?>
        <div class="user-info">
          <img src="/assets/img/user.png" alt="Avatar">
          <span><?= htmlspecialchars($user['user_firstname'] . ' ' . $user['user_name'], ENT_QUOTES) ?></span>
        </div>
      <?php endif; ?>
      <h1>Véhicules</h1>
    </header>

    <div class="auth-buttons">
      <?php if (Auth::check()): ?>
        <a href="/logout">Déconnexion</a>
      <?php else: ?>
        <a href="/login">Se connecter</a>
        <a href="/register">S'inscrire</a>
      <?php endif; ?>
    </div>

    <?php
    // garantit un tableau de véhicules
    $vehicles = $vehicles ?? [];
    // marche de secours si aucun véhicule
    if (count($vehicles) === 0) {
        $vehicles = [[
            'id'            => 1,
            'vehicle_plate' => 'XYZ-999',
            'vehicle_type'  => 'BERLINE',
            'vehicle_maker' => 'Toyota',
            'vehicle_model' => 'Corolla',
            'color'         => 'Bleu',
            'nb_seats'      => 5,
            'vehicle_km'    => 45000,
        ]];
    }
    ?>

    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th><th>Immatriculation</th><th>Type</th>
                <th>Fabricant</th><th>Modèle</th><th>Couleur</th>
                <th>Nb sièges</th><th>KM</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vehicles as $v): ?>
                <tr>
                    <td><?= (int)$v['id'] ?></td>
                    <td><?= htmlspecialchars($v['vehicle_plate'], ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($v['vehicle_type'], ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($v['vehicle_maker'], ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($v['vehicle_model'], ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($v['color'], ENT_QUOTES) ?></td>
                    <td><?= (int)$v['nb_seats'] ?></td>
                    <td><?= number_format((int)$v['vehicle_km'], 0, '', ' ') ?></td>
                    <td>
                      <button class="btn-detail"
                          data-id="<?= (int)$v['id'] ?>"
                          data-plate="<?= htmlspecialchars($v['vehicle_plate'], ENT_QUOTES) ?>"
                          data-type="<?= htmlspecialchars($v['vehicle_type'], ENT_QUOTES) ?>"
                          data-maker="<?= htmlspecialchars($v['vehicle_maker'], ENT_QUOTES) ?>"
                          data-model="<?= htmlspecialchars($v['vehicle_model'], ENT_QUOTES) ?>"
                          data-color="<?= htmlspecialchars($v['color'], ENT_QUOTES) ?>"
                          data-seats="<?= (int)$v['nb_seats'] ?>"
                          data-km="<?= (int)$v['vehicle_km'] ?>">
                        Détails
                      </button>

                      <?php if (Auth::check()): ?>
                        <button class="btn-edit" data-id="<?= (int)$v['id'] ?>">Modifier</button>
                        <a href="/vehicle/delete?id=<?= (int)$v['id'] ?>"
                           onclick="return confirm('Vraiment supprimer ce véhicule ?');">
                          Supprimer
                        </a>
                      <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Overlay & Modal -->
    <div id="overlay"></div>
    <div id="modal">
      <h2>Détails du véhicule</h2>
      <dl id="modal-content"></dl>
      <button id="modal-close">Fermer</button>
      <button id="modal-action">Modifier</button>
      <?php if (Auth::check()): ?>
        <button id="modal-delete">Supprimer</button>
      <?php endif; ?>
    </div>

    <script>
      const overlay = document.getElementById('overlay'),
            modal   = document.getElementById('modal'),
            content = document.getElementById('modal-content'),
            close   = document.getElementById('modal-close'),
            action  = document.getElementById('modal-action'),
            delBtn  = document.getElementById('modal-delete');

      function openModal(d) {
        content.innerHTML = `
          <dt>ID:</dt><dd>${d.id}</dd>
          <dt>Immatriculation:</dt><dd>${d.plate}</dd>
          <dt>Type:</dt><dd>${d.type}</dd>
          <dt>Fabricant:</dt><dd>${d.maker}</dd>
          <dt>Modèle:</dt><dd>${d.model}</dd>
          <dt>Couleur:</dt><dd>${d.color}</dd>
          <dt>Nb sièges:</dt><dd>${d.seats}</dd>
          <dt>KM:</dt><dd>${d.km}</dd>
        `;
        overlay.style.display = modal.style.display = 'block';

        action.onclick = () => alert('Modifier le véhicule ID ' + d.id);
        if (delBtn) {
          delBtn.onclick = () => {
            if (confirm('Confirmer la suppression du véhicule ID ' + d.id + ' ?')) {
              window.location.href = '/vehicle/delete?id=' + d.id;
            }
          };
        }
      }

      function closeModal() {
        overlay.style.display = modal.style.display = 'none';
      }

      close.addEventListener('click', closeModal);
      overlay.addEventListener('click', closeModal);

      document.querySelectorAll('button.btn-detail').forEach(btn =>
        btn.addEventListener('click', () => openModal({
          id:    btn.dataset.id,
          plate: btn.dataset.plate,
          type:  btn.dataset.type,
          maker: btn.dataset.maker,
          model: btn.dataset.model,
          color: btn.dataset.color,
          seats: btn.dataset.seats,
          km:    btn.dataset.km,
        }))
      );
    </script>
</body>
</html>
