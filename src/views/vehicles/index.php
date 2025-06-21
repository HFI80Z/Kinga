<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Liste des Véhicules – Kinga</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
  <?php include __DIR__ . '/../partials/lang_toggle.php'; ?>
<?php
  use App\Core\Auth;

  $filter = [
    'type'      => $_GET['type']      ?? [],
    'fabricant' => $_GET['fabricant'] ?? [],
    'model'     => $_GET['model']     ?? '',
    'color'     => $_GET['color']     ?? [],
    'seats'     => (int)($_GET['seats']   ?? 0),
    'km_max'    => (int)($_GET['km_max'] ?? 0),
  ];

  if (!is_array($filter['type'])) {
      $filter['type'] = $filter['type'] !== '' ? [ $filter['type'] ] : [];
  }
  if (!is_array($filter['fabricant'])) {
      $filter['fabricant'] = $filter['fabricant'] !== '' ? [ $filter['fabricant'] ] : [];
  }
  if (!is_array($filter['color'])) {
      $filter['color'] = $filter['color'] !== '' ? [ $filter['color'] ] : [];
  }
?>
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
      <?php if (isset($_SESSION['user'])): ?>
        <img src="/assets/img/user.png" alt="Avatar" class="h-8 w-8 rounded-full">
        <span><?= htmlspecialchars($_SESSION['user']['user_firstname'] . ' ' . $_SESSION['user']['user_name'], ENT_QUOTES) ?></span>
        <a href="/logout" class="hover:text-red-500">Déconnexion</a>
        <?php if (\App\Core\Auth::check()): ?>
          <a href="/admin" class="hover:text-red-500">Panel Admin</a>
        <?php endif; ?>
      <?php else: ?>
        <a href="/login" class="hover:text-red-500">Se connecter</a>
        <a href="/register" class="hover:text-red-500">S'inscrire</a>
      <?php endif; ?>
    </div>
  </div>
</header>

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
      <span class="block text-sm font-medium text-gray-700">Type</span>
      <div class="mt-2 space-y-1">
        <?php
          $typesPossibles = ['Moto', 'Berline', 'Pick up'];
          foreach ($typesPossibles as $t):
            $checked = in_array($t, $filter['type'], true) ? 'checked' : '';
        ?>
          <label class="inline-flex items-center">
            <input type="checkbox" name="type[]" value="<?= $t ?>" <?= $checked ?>
                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
            <span class="ml-2 text-gray-700"><?= $t ?></span>
          </label><br>
        <?php endforeach; ?>
      </div>
    </div>

    
    <div>
      <span class="block text-sm font-medium text-gray-700">Fabricant</span>
      <div class="mt-2 space-y-1">
        <?php
          $fabricantsPossibles = ['Honda', 'TVS'];
          foreach ($fabricantsPossibles as $f):
            $checked = in_array($f, $filter['fabricant'], true) ? 'checked' : '';
        ?>
          <label class="inline-flex items-center">
            <input type="checkbox" name="fabricant[]" value="<?= $f ?>" <?= $checked ?>
                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
            <span class="ml-2 text-gray-700"><?= $f ?></span>
          </label><br>
        <?php endforeach; ?>
      </div>
    </div>

    
    <div>
      <label class="block text-sm text-gray-700">Modèle</label>
      <input type="text" name="model" value="<?= htmlspecialchars($filter['model'], ENT_QUOTES) ?>"
             class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500"
             placeholder="ex. Corolla">
    </div>

    
    <div>
      <span class="block text-sm font-medium text-gray-700">Couleur</span>
      <div class="mt-2 space-y-1">
        <?php
          $couleursPossibles = ['Noir', 'Bleu', 'Rouge', 'Blanc', 'Gris', 'Vert', 'Jaune'];
          foreach ($couleursPossibles as $c):
            $checked = in_array($c, $filter['color'], true) ? 'checked' : '';
        ?>
          <label class="inline-flex items-center">
            <input type="checkbox" name="color[]" value="<?= $c ?>" <?= $checked ?>
                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
            <span class="ml-2 text-gray-700"><?= $c ?></span>
          </label><br>
        <?php endforeach; ?>
      </div>
    </div>

    
    <div>
      <label class="block text-sm text-gray-700">Nb sièges</label>
      <input type="number" name="seats" min="1" value="<?= $filter['seats'] ?>"
             class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
    </div>

    
    <div>
      <label class="block text-sm text-gray-700">
        KM ≤ <span id="kmValue" class="font-semibold"><?= $filter['km_max'] ?></span>
      </label>
      <input type="range" name="km_max"
             min="0" max="100000" step="20000"
             value="<?= $filter['km_max'] ?>"
             class="mt-2 w-full accent-red-500"
             oninput="kmValue.innerText = this.value">
      <div class="flex justify-between text-xs text-gray-500 mt-1 px-1">
        <span>0</span><span>20 k</span><span>40 k</span><span>60 k</span><span>80 k</span><span>100 k</span>
      </div>
    </div>

    
    <div class="pt-4 border-t space-y-2">
      <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg text-center">
        Rechercher
      </button>
      <a href="/"
         class="w-full block bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 rounded-lg text-center">
        Réinitialiser
      </a>
    </div>
  </form>
</aside>


<main class="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col">
  <?php if (empty($vehicles)): ?>
    <p class="text-center text-gray-600">Aucun véhicule trouvé.</p>
  <?php else: ?>
    <div class="bg-white rounded-lg shadow flex flex-col overflow-hidden">
      
      <div class="overflow-x-auto flex-1">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Immatriculation</th>
              <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Type</th>
              <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Fabricant</th>
              <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Modèle</th>
              <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Couleur</th>
              <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nb sièges</th>
              <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">KM</th>
              <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($vehicles as $v): ?>
              <tr>
                <td class="px-6 py-4"><?= htmlspecialchars($v['immatriculation'], ENT_QUOTES) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($v['type'], ENT_QUOTES) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($v['fabricant'], ENT_QUOTES) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($v['modele'], ENT_QUOTES) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($v['couleur'], ENT_QUOTES) ?></td>
                <td class="px-6 py-4 text-center"><?= (int)$v['nb_sieges'] ?></td>
                <td class="px-6 py-4"><?= number_format((int)$v['km'],0,' ',' ') ?></td>
                <td class="px-6 py-4">
                  <button type="button"
                          class="btn-detail bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg"
                          data-id="<?= (int)$v['id'] ?>"
                          data-imm="<?= htmlspecialchars($v['immatriculation'], ENT_QUOTES) ?>"
                          data-type="<?= htmlspecialchars($v['type'], ENT_QUOTES) ?>"
                          data-fab="<?= htmlspecialchars($v['fabricant'], ENT_QUOTES) ?>"
                          data-mod="<?= htmlspecialchars($v['modele'], ENT_QUOTES) ?>"
                          data-cou="<?= htmlspecialchars($v['couleur'], ENT_QUOTES) ?>"
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

      
      <div class="mt-6 sticky bottom-0 bg-white flex justify-between items-center px-6 py-4">
        <?php if ($page > 1): ?>
          <a href="?page=<?= $page - 1 ?>"
             class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">←</a>
        <?php else: ?>
          <span class="w-12"></span>
        <?php endif; ?>

        <span class="text-gray-600">Page <?= $page ?> sur <?= $totalPages ?></span>

        <?php if ($page < $totalPages): ?>
          <a href="?page=<?= $page + 1 ?>"
             class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">→</a>
        <?php else: ?>
          <span class="w-12"></span>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
</main>


<div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>
<div id="modal"
     class="hidden fixed inset-0 flex items-center justify-center z-50">
  <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-md">
    <h2 class="text-xl font-semibold mb-4">Détails du véhicule</h2>
    <div id="modalContent" class="grid grid-cols-2 gap-4 text-gray-700"></div>
    <div class="mt-6 flex justify-end">
      <button id="modalClose" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">Fermer</button>
    </div>
  </div>
</div>

<script>
  // Sidebar filtre
  const side   = document.getElementById('filterSidebar'),
        ovF    = document.getElementById('filterOverlay');
  document.getElementById('openFilters').onclick = () => {
    side.classList.remove('-translate-x-full');
    ovF.classList.remove('hidden');
  };
  document.getElementById('closeFilters').onclick =
  ovF.onclick = () => {
    side.classList.add('-translate-x-full');
    ovF.classList.add('hidden');
  };

  // Modale détails
  const overlayM = document.getElementById('overlay'),
        modalM   = document.getElementById('modal'),
        contentM = document.getElementById('modalContent'),
        btnClose = document.getElementById('modalClose');

  document.querySelectorAll('button.btn-detail').forEach(btn =>
    btn.addEventListener('click', () => {
      const d = btn.dataset;
      contentM.innerHTML = `
        <div class="text-center">
          <div class="font-semibold text-gray-800 mb-1">Immatriculation</div>
          <div class="text-gray-600">${d.imm}</div>
        </div>
        <div class="text-center">
          <div class="font-semibold text-gray-800 mb-1">Type</div>
          <div class="text-gray-600">${d.type}</div>
        </div>
        <div class="text-center">
          <div class="font-semibold text-gray-800 mb-1">Fabricant</div>
          <div class="text-gray-600">${d.fab}</div>
        </div>
        <div class="text-center">
          <div class="font-semibold text-gray-800 mb-1">Modèle</div>
          <div class="text-gray-600">${d.mod}</div>
        </div>
        <div class="text-center">
          <div class="font-semibold text-gray-800 mb-1">Couleur</div>
          <div class="text-gray-600">${d.cou}</div>
        </div>
        <div class="text-center">
          <div class="font-semibold text-gray-800 mb-1">Nb sièges</div>
          <div class="text-gray-600">${d.seg}</div>
        </div>
        <div class="text-center">
          <div class="font-semibold text-gray-800 mb-1">Kilométrage</div>
          <div class="text-gray-600">${parseInt(d.km).toLocaleString('fr-FR')} km</div>
        </div>
        <div></div>
      `;
      overlayM.classList.remove('hidden');
      modalM.classList.remove('hidden');
    })
  );

  btnClose.onclick = overlayM.onclick = () => {
    overlayM.classList.add('hidden');
    modalM.classList.add('hidden');
  };
</script>
</body>
</html>
