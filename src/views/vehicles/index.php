<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Liste des Véhicules – Kinga</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
<?php
  use App\Core\Auth;
  $user   = $_SESSION['user'] ?? null;
  $filter = [
    'type'      => $_GET['type']      ?? '',
    'fabricant' => $_GET['fabricant'] ?? '',
    'model'     => $_GET['model']     ?? '',
    'color'     => $_GET['color']     ?? '',
    'seats'     => (int)($_GET['seats']   ?? 0),
    'km_max'    => (int)($_GET['km_max'] ?? 0),
  ];
?>
<!-- HEADER -->
<header class="bg-white shadow-sm">
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
    <div class="flex items-center space-x-4">
      <button id="openFilters" class="p-2 rounded-md hover:bg-gray-100 focus:outline-none">
        <svg class="h-6 w-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      <a href="/" class="flex items-center space-x-2">
        <img src="/assets/img/logo_kinga.png" alt="Kinga Logo" class="h-8">
        <h1 class="text-2xl font-bold text-gray-800">Véhicules</h1>
      </a>
    </div>
    <div class="flex items-center space-x-4 text-gray-700">
      <?php if ($user): ?>
        <img src="/assets/img/user.png" alt="Avatar" class="h-8 w-8 rounded-full">
        <span><?= htmlspecialchars("{$user['user_firstname']} {$user['user_name']}", ENT_QUOTES) ?></span>
        <a href="/logout" class="hover:text-red-500">Déconnexion</a>
        <?php if (Auth::check()): ?>
          <a href="/admin" class="hover:text-red-500">Panel Admin</a>
        <?php endif; ?>
      <?php else: ?>
        <a href="/login" class="hover:text-red-500">Se connecter</a>
        <a href="/register" class="hover:text-red-500">S'inscrire</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<!-- OVERLAY & SIDEBAR FILTRES -->
<div id="filterOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-20"></div>
<aside id="filterSidebar"
       class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-30">
  <div class="flex items-center justify-between px-4 py-3 border-b">
    <h2 class="text-lg font-semibold text-gray-800">Filtres</h2>
    <button id="closeFilters" class="p-1 rounded hover:bg-gray-100 focus:outline-none">
      <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>
  <form method="get" action="" class="p-4 space-y-5 overflow-y-auto h-full">
    <div>
      <label class="block text-sm text-gray-700">Type</label>
      <input type="text" name="type" value="<?= htmlspecialchars($filter['type'], ENT_QUOTES) ?>"
             class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
    </div>
    <div>
      <label class="block text-sm text-gray-700">Fabricant</label>
      <input type="text" name="fabricant" value="<?= htmlspecialchars($filter['fabricant'], ENT_QUOTES) ?>"
             class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
    </div>
    <div>
      <label class="block text-sm text-gray-700">Modèle</label>
      <input type="text" name="model" value="<?= htmlspecialchars($filter['model'], ENT_QUOTES) ?>"
             class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
    </div>
    <div>
      <label class="block text-sm text-gray-700">Couleur</label>
      <input type="text" name="color" value="<?= htmlspecialchars($filter['color'], ENT_QUOTES) ?>"
             class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
    </div>
    <div>
      <label class="block text-sm text-gray-700">Nb sièges</label>
      <input type="number" name="seats" min="1" value="<?= $filter['seats'] ?>"
             class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
    </div>
    <div>
      <label class="block text-sm text-gray-700">
        KM ≤ <span id="kmValue"><?= $filter['km_max'] ?></span>
      </label>
      <input type="range" name="km_max"
             min="0" max="250000" step="50000"
             value="<?= $filter['km_max'] ?>"
             class="mt-1 w-full accent-red-500"
             oninput="kmValue.innerText = this.value">
      <div class="flex justify-between text-xs text-gray-500 mt-1 px-1">
        <span>0</span><span>50k</span><span>100k</span><span>150k</span><span>200k</span><span>250k</span>
      </div>
    </div>
    <div class="pt-4 border-t space-y-2">
      <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg">
        Rechercher
      </button>
      <a href="/"
         class="w-full block bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold text-center py-2 rounded-lg">
        Réinitialiser
      </a>
    </div>
  </form>
</aside>

<!-- MAIN CONTENT -->
<main class="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col">

  <?php if (empty($vehicles)): ?>
    <p class="text-center text-gray-600">Aucun véhicule trouvé.</p>
  <?php else: ?>
    <div class="bg-white rounded-lg shadow flex flex-col overflow-hidden">

      <!-- TABLE (flex-1 pour pousser la pagination en bas) -->
      <div class="overflow-x-auto flex-1">
        <table class="min-w-full">
          <thead class="bg-gray-100">
            <tr>
              <?php foreach (['Immatriculation','Type','Fabricant','Modèle','Couleur','Nb sièges','KM','Actions'] as $h): ?>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700"><?= $h ?></th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($vehicles as $v): ?>
            <tr class="border-t">
              <td class="px-6 py-4"><?= htmlspecialchars($v['immatriculation'],ENT_QUOTES) ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($v['type'],ENT_QUOTES) ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($v['fabricant'],ENT_QUOTES) ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($v['modele'],ENT_QUOTES) ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($v['couleur'],ENT_QUOTES) ?></td>
              <td class="px-6 py-4 text-center"><?= (int)$v['nb_sieges'] ?></td>
              <td class="px-6 py-4"><?= number_format((int)$v['km'],0,' ',' ') ?></td>
              <td class="px-6 py-4">
                <button
                  class="btn-detail bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg"
                  data-id="<?= (int)$v['id'] ?>"
                  data-imm="<?= htmlspecialchars($v['immatriculation'],ENT_QUOTES) ?>"
                  data-type="<?= htmlspecialchars($v['type'],ENT_QUOTES) ?>"
                  data-fab="<?= htmlspecialchars($v['fabricant'],ENT_QUOTES) ?>"
                  data-mod="<?= htmlspecialchars($v['modele'],ENT_QUOTES) ?>"
                  data-cou="<?= htmlspecialchars($v['couleur'],ENT_QUOTES) ?>"
                  data-seg="<?= (int)$v['nb_sieges'] ?>"
                  data-km="<?= (int)$v['km'] ?>">
                  Détails
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- pagination (sticky, avec un mt-8 pour plus d'espace entre table et pagination) -->
      <div class="mt-8 sticky bottom-4 bg-white flex justify-between items-center px-6 py-4">
        <?php if ($page > 1): ?>
          <a href="?page=<?= $page - 1 ?>"
             class="w-24 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
            ← 
          </a>
        <?php else: ?>
          <span class="w-24"></span>
        <?php endif; ?>

        <div class="text-gray-600">
          Page <?= $page ?> sur <?= $totalPages ?>
        </div>

        <?php if ($page < $totalPages): ?>
          <a href="?page=<?= $page + 1 ?>"
             class="w-24 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
             →
          </a>
        <?php else: ?>
          <span class="w-24"></span>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>

</main>

<!-- MODAL -->
<div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>
<div id="modal"
     class="hidden fixed top-1/4 left-1/2 transform -translate-x-1/2 bg-white p-6 rounded-lg shadow-lg z-50 w-11/12 max-w-md">
  <h2 class="text-xl font-semibold mb-4">Détails du véhicule</h2>
  <dl id="modalContent" class="mb-4 text-gray-700"></dl>
  <div class="flex justify-end space-x-2">
    <button id="modalClose" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">Fermer</button>
    <?php if ($user && $user['role']==='admin'): ?>
      <button id="modalEdit" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg">Modifier</button>
      <button id="modalDelete" class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white rounded-lg">Supprimer</button>
    <?php endif; ?>
  </div>
</div>

<script>
  // Sidebar toggle
  const side   = document.getElementById('filterSidebar'),
        ovF    = document.getElementById('filterOverlay');
  document.getElementById('openFilters').onclick = () => {
    side.classList.remove('-translate-x-full');
    ovF.classList.remove('hidden');
  };
  document.getElementById('closeFilters').onclick = () => {
    side.classList.add('-translate-x-full');
    ovF.classList.add('hidden');
  };
  ovF.onclick = () => {
    side.classList.add('-translate-x-full');
    ovF.classList.add('hidden');
  };

  // Modal logic
  const overlayM = document.getElementById('overlay'),
        modalM   = document.getElementById('modal'),
        contentM = document.getElementById('modalContent'),
        btnClose = document.getElementById('modalClose'),
        btnEdit  = document.getElementById('modalEdit'),
        btnDel   = document.getElementById('modalDelete');

  document.querySelectorAll('button.btn-detail').forEach(btn =>
    btn.addEventListener('click', () => {
      const d = btn.dataset;
      contentM.innerHTML = `
        <dt class="font-semibold">Immatriculation:</dt><dd>${d.imm}</dd>
        <dt class="font-semibold">Type:</dt><dd>${d.type}</dd>
        <dt class="font-semibold">Fabricant:</dt><dd>${d.fab}</dd>
        <dt class="font-semibold">Modèle:</dt><dd>${d.mod}</dd>
        <dt class="font-semibold">Couleur:</dt><dd>${d.cou}</dd>
        <dt class="font-semibold">Nb sièges:</dt><dd>${d.seg}</dd>
        <dt class="font-semibold">KM:</dt><dd>${d.km}</dd>
      `;
      overlayM.classList.remove('hidden');
      modalM.classList.remove('hidden');
      if (btnEdit) btnEdit.onclick = () => location.href = `/vehicle/edit?id=${d.id}`;
      if (btnDel)  btnDel.onclick  = () => {
        if (confirm(`Supprimer le véhicule ${d.imm} ?`)) {
          location.href = `/vehicle/delete?id=${d.id}`;
        }
      };
    }))
  ;
  btnClose.onclick = () => {
    overlayM.classList.add('hidden');
    modalM.classList.add('hidden');
  };
  overlayM.onclick = btnClose.onclick;
</script>
</body>
</html>