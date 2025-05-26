<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Admin – Véhicules – Kinga</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen overflow-hidden font-sans">

  <!-- SIDEBAR -->
  <aside class="w-64 bg-gray-800 text-gray-100 flex-shrink-0 flex flex-col">
    <div class="px-6 py-4 flex items-center">
      <a href="/"><img src="/assets/img/logo_kinga.png" alt="Kinga Logo" class="h-8"></a>
    </div>
    <nav class="flex-1 px-2 space-y-1">
      <a href="/admin" class="block px-4 py-2 rounded hover:bg-gray-700">&bull; Liste Véhicules</a>
      <a href="/admin/orders" class="block px-4 py-2 rounded hover:bg-gray-700">&bull; Commandes</a>
      <a href="/admin/users" class="block px-4 py-2 rounded hover:bg-gray-700">&bull; Utilisateurs</a>
    </nav>
    <div class="px-6 py-4 border-t border-gray-700">
      <a href="/logout" class="block text-red-400 hover:text-red-300">Déconnexion</a>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="flex-1 bg-gray-100 overflow-auto p-6">

    <!-- ENTÊTE + FORMULAIRE D'AJOUT -->
    <section class="space-y-6">
      <h1 class="text-2xl font-bold text-gray-800">Panneau Administrateur – Véhicules</h1>

      <form id="addVehicleForm" action="/vehicle/store" method="post"
            class="bg-white p-6 rounded-lg shadow grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">

        <div>
          <label class="block text-sm font-medium text-gray-700">Immatriculation *</label>
          <input name="immatriculation" required
                 class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Type</label>
          <input name="type"
                 class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Fabricant</label>
          <input name="fabricant"
                 class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Modèle</label>
          <input name="modele"
                 class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Couleur</label>
          <input name="couleur"
                 class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Nb sièges</label>
          <input type="number" name="nb_sieges" min="1"
                 class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Kilométrage</label>
          <input type="number" name="km" min="0"
                 class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
        </div>

        <div class="md:col-span-3 lg:col-span-1 flex items-end">
          <button type="submit"
                  class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded">
            Ajouter
          </button>
        </div>
      </form>
    </section>

    <!-- TABLEAU + PAGINATION -->
    <section class="mt-8">
      <div class="bg-white rounded-lg shadow flex flex-col overflow-hidden" style="min-height:30rem;">

        <!-- Tableau (prend tout l'espace restant) -->
        <div class="overflow-x-auto flex-1">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Immat.</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fabricant</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Modèle</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Couleur</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sièges</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">KM</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php $cpt = 0; ?>
              <?php foreach ($vehicles as $v): $cpt++; ?>
              <tr>
                <td class="px-6 py-4"><?= $offset + $cpt ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($v['immatriculation'], ENT_QUOTES) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($v['type'], ENT_QUOTES) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($v['fabricant'], ENT_QUOTES) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($v['modele'], ENT_QUOTES) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($v['couleur'], ENT_QUOTES) ?></td>
                <td class="px-6 py-4"><?= (int)$v['nb_sieges'] ?></td>
                <td class="px-6 py-4"><?= number_format((int)$v['km'],0,'',' ') ?></td>
                <td class="px-6 py-4 space-x-2">
                  <a href="/vehicle/edit?id=<?= (int)$v['id'] ?>"
                     class="text-blue-600 hover:underline">Modifier</a>
                  <a href="/vehicle/delete?id=<?= (int)$v['id'] ?>"
                     onclick="return confirm('Supprimer ce véhicule ?');"
                     class="text-red-600 hover:underline">Supprimer</a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination (collée en bas) -->
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
    </section>

  </main>
</body>
</html>
