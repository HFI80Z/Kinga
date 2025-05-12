<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Véhicules</title>
    <style>
      /* Header */
      header {
        position: relative;
        padding: 1em 0;
        border-bottom: 1px solid #ccc;
      }
      header h1 {
        margin: 0;
        display: inline-block;
        font-size: 1.5em;
      }
      .user-info,
      .auth-buttons {
        position: absolute;
        top: 50%;
        right: 0;
        transform: translateY(-50%);
      }
      .user-info img {
        width:32px; height:32px; margin-right:.5em;
        vertical-align: middle;
      }
      .user-info span {
        margin-right:1em;
        vertical-align: middle;
      }
      .user-info a,
      .auth-buttons a {
        margin-left: .5em;
        text-decoration: none;
        color: #3366cc;
        vertical-align: middle;
      }

      /* Table & modal */
      table { border-collapse: collapse; width: 100%; margin-top: 1em; }
      th, td { border: 1px solid #333; padding: 0.5em; text-align: left; }
      #overlay {
        display: none; position: fixed; top:0; left:0;
        width:100%; height:100%;
        background: rgba(0,0,0,0.5); z-index:999;
      }
      #modal {
        display: none; position: fixed; top:20%; left:50%;
        transform: translateX(-50%);
        background:#fff; padding:20px;
        border:1px solid #333; z-index:1000;
        min-width:300px;
      }
      #modal h2 { margin-top:0; }
      #modal button { margin-right:8px; }
    </style>
</head>
<body>
    <?php
      use App\Core\Auth;
      $user = $_SESSION['user'] ?? null;
    ?>

    <header>
      <h1>Véhicules</h1>

      <?php if ($user): // connecté ?>
        <div class="user-info">
          <img src="/assets/img/user.png" alt="Avatar">
          <span><?= htmlspecialchars("{$user['user_firstname']} {$user['user_name']}", ENT_QUOTES) ?></span>
          <a href="/logout">Déconnexion</a>
          <?php if (Auth::check()): ?>
            | <a href="/admin">Panel Admin</a>
          <?php endif; ?>
        </div>
      <?php else: // non connecté ?>
        <div class="auth-buttons">
          <a href="/login">Se connecter</a>
          <a href="/register">S'inscrire</a>
        </div>
      <?php endif; ?>
    </header>

    <?php if (empty($vehicles)): ?>
      <p>Aucun véhicule enregistré.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Immatriculation</th>
            <th>Type</th>
            <th>Fabricant</th>
            <th>Modèle</th>
            <th>Couleur</th>
            <th>Nb sièges</th>
            <th>KM</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($vehicles as $v): ?>
            <tr>
              <td><?= htmlspecialchars($v['immatriculation'], ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars($v['type'],            ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars($v['fabricant'],       ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars($v['modele'],          ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars($v['couleur'],         ENT_QUOTES) ?></td>
              <td><?= (int)$v['nb_sieges'] ?></td>
              <td><?= number_format((int)$v['km'],0,'',' ') ?></td>
              <td>
                <button class="btn-detail"
                  data-id="<?= (int)$v['id'] ?>"
                  data-imm="<?= htmlspecialchars($v['immatriculation'], ENT_QUOTES) ?>"
                  data-type="<?= htmlspecialchars($v['type'],            ENT_QUOTES) ?>"
                  data-fab="<?= htmlspecialchars($v['fabricant'],       ENT_QUOTES) ?>"
                  data-mod="<?= htmlspecialchars($v['modele'],          ENT_QUOTES) ?>"
                  data-cou="<?= htmlspecialchars($v['couleur'],         ENT_QUOTES) ?>"
                  data-seg="<?= (int)$v['nb_sieges'] ?>"
                  data-km="<?= (int)$v['km'] ?>">
                  Détails
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <div id="overlay"></div>
    <div id="modal">
      <h2>Détails du véhicule</h2>
      <dl id="modal-content"></dl>
      <button id="modal-close">Fermer</button>
      <?php if ($user && $user['role'] === 'admin'): ?>
        <button id="modal-edit">Modifier</button>
        <button id="modal-delete">Supprimer</button>
      <?php endif; ?>
    </div>

    <script>
      const overlay = document.getElementById('overlay'),
            modal   = document.getElementById('modal'),
            content = document.getElementById('modal-content'),
            btnClose= document.getElementById('modal-close'),
            btnEdit = document.getElementById('modal-edit'),
            btnDel  = document.getElementById('modal-delete');

      function openModal(data) {
        content.innerHTML = `
          <dt>Immatriculation:</dt><dd>${data.imm}</dd>
          <dt>Type:</dt><dd>${data.type}</dd>
          <dt>Fabricant:</dt><dd>${data.fab}</dd>
          <dt>Modèle:</dt><dd>${data.mod}</dd>
          <dt>Couleur:</dt><dd>${data.cou}</dd>
          <dt>Nb sièges:</dt><dd>${data.seg}</dd>
          <dt>KM:</dt><dd>${data.km}</dd>
        `;
        overlay.style.display = modal.style.display = 'block';

        if (btnEdit) {
          btnEdit.onclick = () => window.location.href = `/vehicle/edit?id=${data.id}`;
        }
        if (btnDel) {
          btnDel.onclick = () => {
            if (confirm(`Supprimer le véhicule ${data.imm} ?`)) {
              window.location.href = `/vehicle/delete?id=${data.id}`;
            }
          };
        }
      }

      function closeModal() {
        overlay.style.display = modal.style.display = 'none';
      }

      document.querySelectorAll('button.btn-detail').forEach(btn =>
        btn.addEventListener('click', () => openModal({
          id:   btn.dataset.id,
          imm:  btn.dataset.imm,
          type: btn.dataset.type,
          fab:  btn.dataset.fab,
          mod:  btn.dataset.mod,
          cou:  btn.dataset.cou,
          seg:  btn.dataset.seg,
          km:   btn.dataset.km,
        }))
      );

      btnClose.addEventListener('click', closeModal);
      overlay.addEventListener('click', closeModal);
    </script>
</body>
</html>
