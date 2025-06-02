<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Panneau Admin – Véhicules – Kinga</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen overflow-hidden font-sans">

  <!-- SIDEBAR -->
  <aside class="w-64 bg-gray-800 text-gray-100 flex-shrink-0 flex flex-col">
    <div class="px-6 py-4 flex items-center">
      <a href="/"><img src="/assets/img/logo_kinga.png" alt="Kinga Logo" class="h-8"></a>
    </div>
    <nav class="flex-1 px-2 space-y-1">
      <a href="/admin" class="block px-4 py-2 rounded hover:bg-gray-700">Liste Véhicules</a>
      <a href="/admin/maintenance" class="block px-4 py-2 rounded hover:bg-gray-700">Maintenances</a>
      <a href="/admin/repairers" class="block px-4 py-2 rounded hover:bg-gray-700">Réparateurs</a>
      <!-- Onglets supprimés : Commandes et Utilisateurs -->
      <!-- <a href="/admin/orders" class="block px-4 py-2 rounded hover:bg-gray-700">Commandes</a> -->
      <!-- <a href="/admin/users" class="block px-4 py-2 rounded hover:bg-gray-700">Utilisateurs</a> -->
    </nav>
    <div class="px-6 py-4 border-t border-gray-700">
      <a href="/logout" class="block text-red-400 hover:text-red-300">Déconnexion</a>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="flex-1 bg-gray-100 overflow-auto p-6">

    <!-- TITRE + AJOUTER UN VÉHICULE -->
    <section class="space-y-6">
      <h1 class="text-2xl font-bold text-gray-800">Panneau Administrateur – Véhicules</h1>

      <form action="/vehicle/store" method="post"
            class="bg-white p-6 rounded-lg shadow grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">

        <!-- Immatriculation -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Immatriculation *</label>
          <input name="immatriculation" required
                 class="mt-1 w-full px-3 py-2 bg-white border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Type -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Type</label>
          <select name="type"
                  class="mt-1 w-full px-3 py-2 bg-white border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Sélectionner --</option>
            <option value="Moto">Moto</option>
            <option value="Berline">Berline</option>
            <option value="Pick up">Pick up</option>
          </select>
        </div>

        <!-- Fabricant -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Fabricant</label>
          <select name="fabricant"
                  class="mt-1 w-full px-3 py-2 bg-white border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Sélectionner --</option>
            <option value="Honda">Honda</option>
            <option value="TVS">TVS</option>
          </select>
        </div>

        <!-- Modèle -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Modèle</label>
          <input name="modele"
                 class="mt-1 w-full px-3 py-2 bg-white border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Couleur -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Couleur</label>
          <select name="couleur"
                  class="mt-1 w-full px-3 py-2 bg-white border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Sélectionner --</option>
            <option value="Noir">Noir</option>
            <option value="Bleu">Bleu</option>
            <option value="Rouge">Rouge</option>
            <option value="Blanc">Blanc</option>
            <option value="Gris">Gris</option>
            <option value="Vert">Vert</option>
            <option value="Jaune">Jaune</option>
          </select>
        </div>

        <!-- Nb sièges -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Nb sièges</label>
          <input type="number" name="nb_sieges" min="1"
                 class="mt-1 w-full px-3 py-2 bg-white border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Kilométrage -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Kilométrage</label>
          <input type="number" name="km" min="0"
                 class="mt-1 w-full px-3 py-2 bg-white border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Bouton Ajouter -->
        <div class="md:col-span-3 lg:col-span-1 flex items-end">
          <button type="submit"
                  class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-lg">
            Ajouter
          </button>
        </div>

      </form>
    </section>

    <!-- TABLEAU + PAGINATION -->
    <section class="mt-8">
      <div class="bg-white rounded-lg shadow flex flex-col overflow-hidden">

        <!-- Tableau -->
        <div class="overflow-x-auto relative">
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
              <?php $i = 0; foreach ($vehicles as $v): $i++; ?>
                <?php $vid = (int)$v['id']; ?>
                <tr class="relative">
                  <td class="px-6 py-4"><?= $offset + $i ?></td>
                  <td class="px-6 py-4"><?= htmlspecialchars($v['immatriculation'], ENT_QUOTES) ?></td>
                  <td class="px-6 py-4"><?= htmlspecialchars($v['type'], ENT_QUOTES) ?></td>
                  <td class="px-6 py-4"><?= htmlspecialchars($v['fabricant'], ENT_QUOTES) ?></td>
                  <td class="px-6 py-4"><?= htmlspecialchars($v['modele'], ENT_QUOTES) ?></td>
                  <td class="px-6 py-4"><?= htmlspecialchars($v['couleur'], ENT_QUOTES) ?></td>
                  <td class="px-6 py-4 text-center"><?= (int)$v['nb_sieges'] ?></td>
                  <td class="px-6 py-4"><?= number_format((int)$v['km'], 0, ' ', ' ') ?></td>
                  <td class="px-6 py-4 space-x-2">
                    <!-- Modifier -->
                    <a href="/vehicle/edit?id=<?= $vid ?>"
                       class="inline-block bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-lg">
                      Modifier
                    </a>
                    <!-- Supprimer -->
                    <a href="/vehicle/delete?id=<?= $vid ?>"
                       onclick="return confirm('Supprimer ce véhicule ?');"
                       class="inline-block bg-yellow-400 hover:bg-yellow-500 text-white font-semibold px-4 py-2 rounded-lg">
                      Supprimer
                    </a>
                    <!-- Mettre en maintenance si pas déjà en maintenance -->
                    <?php if (!isset($inMaintenance[$vid])): ?>
                      <a href="/admin/maintenance/form?vehicle_id=<?= $vid ?>"
                         class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg">
                        Mettre en maintenance
                      </a>
                    <?php endif; ?>
                  </td>

                  <?php if (isset($inMaintenance[$vid])): ?>
                    <!-- Overlay “En réparation” -->
                    <td class="absolute inset-0 p-0 m-0 bg-gray-300 bg-opacity-75 flex items-center justify-center" colspan="9">
                      <span class="text-red-600 font-bold text-lg">En réparation</span>
                    </td>
                  <?php endif; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4 p-4">
          <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
              ← Précédent
            </a>
          <?php else: ?>
            <span class="w-24"></span>
          <?php endif; ?>

          <span>Page <?= $page ?> sur <?= $totalPages ?></span>

          <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
              Suivant → 
            </a>
          <?php else: ?>
            <span class="w-24"></span>
          <?php endif; ?>
        </div>

      </div>
    </section>

  </main>
</body>
</html>
