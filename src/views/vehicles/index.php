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
  $user = $_SESSION['user'] ?? null;

  // Récup des filtres pour préremplissage
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
          <span class="whitespace-nowrap"><?= htmlspecialchars("{$user['user_firstname']} {$user['user_name']}", ENT_QUOTES) ?></span>
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

  <!-- Overlay & Sidebar filtres -->
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
      <!-- Type -->
      <div>
        <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
        <input type="text" id="type" name="type"
               value="<?= htmlspecialchars($filter['type'], ENT_QUOTES) ?>"
               placeholder="ex. SUV"
               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
      </div>
      <!-- Fabricant -->
      <div>
        <label for="fabricant" class="block text-sm font-medium text-gray-700">Fabricant</label>
        <input type="text" id="fabricant" name="fabricant"
               value="<?= htmlspecialchars($filter['fabricant'], ENT_QUOTES) ?>"
               placeholder="ex. Toyota"
               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
      </div>
      <!-- Modèle -->
      <div>
        <label for="model" class="block text-sm font-medium text-gray-700">Modèle</label>
        <input type="text" id="model" name="model"
               value="<?= htmlspecialchars($filter['model'], ENT_QUOTES) ?>"
               placeholder="ex. Corolla"
               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
      </div>
      <!-- Couleur -->
      <div>
        <label for="color" class="block text-sm font-medium text-gray-700">Couleur</label>
        <input type="text" id="color" name="color"
               value="<?= htmlspecialchars($filter['color'], ENT_QUOTES) ?>"
               placeholder="ex. rouge"
               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
      </div>
      <!-- Nb sièges -->
      <div>
        <label for="seats" class="block text-sm font-medium text-gray-700">Nb sièges</label>
        <input type="number" id="seats" name="seats" min="1"
               value="<?= $filter['seats'] ?: '' ?>"
               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
      </div>
      <!-- KM slider -->
      <div>
        <label for="km_max" class="block text-sm font-medium text-gray-700">
          KM ≤ <span id="km-value" class="font-semibold"><?= $filter['km_max'] ?></span>
        </label>
        <input type="range" id="km_max" name="km_max"
               min="0" max="50000" step="10000"
               value="<?= $filter['km_max'] ?>"
               class="mt-2 w-full accent-red-500"
               oninput="document.getElementById('km-value').innerText = this.value">
        <div class="flex justify-between text-xs text-gray-500 mt-1 px-1">
          <span>0</span><span>10k</span><span>20k</span><span>30k</span><span>40k</span><span>50k</span>
        </div>
      </div>
      <!-- Boutons -->
      <div class="pt-4 border-t space-y-2">
        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg">
          Rechercher
        </button>
        <a href="/"
           class="w-full block text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 rounded-lg">
          Réinitialiser
        </a>
      </div>
    </form>
  </aside>

  <!-- CONTENU PRINCIPAL -->
  <main class="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <?php if (empty($vehicles)): ?>
      <p class="text-center text-gray-600">Aucun véhicule trouvé.</p>
    <?php else: ?>
      <div class="bg-white rounded-lg shadow flex flex-col overflow-hidden" style="min-height:30rem;">

        <!-- Tableau (flex-1 pour occuper tout l'espace) -->
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
                  <button class="btn-detail bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg"
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

        <!-- Pagination (toujours collée en bas) -->
        <div class="flex justify-between items-center mt-auto p-4">
          <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>"
               class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">←</a>
          <?php else: ?>
            <span class="px-4"></span>
          <?php endif; ?>

          <span>Page <?= $page ?> sur <?= $totalPages ?></span>

          <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>"
               class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">→</a>
          <?php else: ?>
            <span class="px-4"></span>
          <?php endif; ?>
        </div>

      </div>
    <?php endif; ?>
  </main>

  <!-- MODAL DÉTAIL (inchangé) -->
  <div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>
  <div id="modal"
       class="hidden fixed top-1/4 left-1/2 transform -translate-x-1/2 bg-white p-6 rounded-lg shadow-lg z-50 w-11/12 max-w-md">
    <!-- ... -->
  </div>

  <!-- SCRIPTS (inchangés) -->
  <script>/* your JS modal & sidebar */</script>
</body>
</html>
