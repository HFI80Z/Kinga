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
      <a href="/">
        <img src="/assets/img/logo_kinga.png" alt="Kinga Logo" class="h-8">
      </a>
    </div>
    <nav class="flex-1 px-2 space-y-1">
      <a href="/admin" class="block px-4 py-2 rounded hover:bg-gray-700">
        &bull; Liste Véhicules
      </a>
      <!-- Autres liens si besoin -->
      <a href="/admin/orders" class="block px-4 py-2 rounded hover:bg-gray-700">
        &bull; Commandes
      </a>
      <a href="/admin/users" class="block px-4 py-2 rounded hover:bg-gray-700">
        &bull; Utilisateurs
      </a>
    </nav>
    <div class="px-6 py-4 border-t border-gray-700">
      <a href="/logout" class="block text-red-400 hover:text-red-300">Déconnexion</a>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="flex-1 bg-gray-100 overflow-auto p-6">

    <!-- ENTÊTE + FILTRES / FORMULAIRE D'AJOUT -->
    <section class="space-y-6">
      <h1 class="text-2xl font-bold text-gray-800">Panneau Administrateur – Véhicules</h1>
      <div class="bg-white p-6 rounded-lg shadow grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">

        <!-- Immatriculation -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Immatriculation *</label>
          <input name="immatriculation" required
                 class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
        </div>

        <!-- Type -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Type</label>
          <input name="type"
                 class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
        </div>

        <!-- Fabricant -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Fabricant</label>
          <input name="fabricant"
                 class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
        </div>

        <!-- Modèle -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Modèle</label>
          <input name="modele"
                 class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
        </div>

        <!-- Couleur -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Couleur</label>
          <input name="couleur"
                 class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
        </div>

        <!-- Nb sièges -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Nb sièges</label>
          <input type="number" name="nb_sieges" min="1"
                 class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
        </div>

        <!-- KM -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Kilométrage</label>
          <input type="number" name="km" min="0"
                 class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
        </div>

        <!-- Bouton Ajouter -->
        <div class="md:col-span-3 lg:col-span-1 flex items-end">
          <button type="submit" form="addVehicleForm"
                  class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded">
            Ajouter
          </button>
        </div>
      </div>
    </section>

    <!-- TABLEAU -->
    <section class="mt-8">
      <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Immat.</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fabricant</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modèle</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Couleur</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sièges</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">KM</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($vehicles as $v): ?>
            <tr>
              <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($v['immatriculation'], ENT_QUOTES) ?></td>
              <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($v['type'], ENT_QUOTES) ?></td>
              <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($v['fabricant'], ENT_QUOTES) ?></td>
              <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($v['modele'], ENT_QUOTES) ?></td>
              <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($v['couleur'], ENT_QUOTES) ?></td>
              <td class="px-6 py-4 whitespace-nowrap"><?= (int)$v['nb_sieges'] ?></td>
              <td class="px-6 py-4 whitespace-nowrap"><?= number_format((int)$v['km'],0,'',' ') ?></td>
              <td class="px-6 py-4 whitespace-nowrap space-x-2">
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
    </section>

  </main>
</body>
</html>
