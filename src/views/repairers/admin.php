<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Panneau Admin – Réparateurs – Kinga</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen overflow-hidden font-sans">

  <!-- SIDEBAR (repris depuis vehicles/admin.php, sans “Commandes” / “Utilisateurs”) -->
  <aside class="w-64 bg-gray-800 text-gray-100 flex-shrink-0 flex flex-col">
    <div class="px-6 py-4 flex items-center">
      <a href="/"><img src="/assets/img/logo_kinga.png" alt="Kinga Logo" class="h-8"></a>
    </div>
    <nav class="flex-1 px-2 space-y-1">
      <!-- Lien vers la liste des véhicules -->
      <a href="/admin" class="block px-4 py-2 rounded hover:bg-gray-700">Liste Véhicules</a>
      <!-- Lien vers le module maintenance -->
      <a href="/admin/maintenance" class="block px-4 py-2 rounded hover:bg-gray-700">Maintenances</a>
      <!-- Lien vers le module réparateurs (actif) -->
      <a href="/admin/repairers" class="block px-4 py-2 rounded bg-gray-700">Réparateurs</a>
    </nav>
    <div class="px-6 py-4 border-t border-gray-700">
      <a href="/logout" class="block text-red-400 hover:text-red-300">Déconnexion</a>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="flex-1 bg-gray-100 overflow-auto p-6">

    <!-- TITRE + BOUTON “+ Ajouter un réparateur” -->
    <section class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Panneau Admin – Réparateurs</h1>
      <a href="/admin/repairers/form"
         class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-lg">
        + Ajouter un réparateur
      </a>
    </section>

    <!-- TABLEAU DES RÉPARATEURS -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php $i = 0; foreach ($repairers as $r): $i++; ?>
            <tr>
              <td class="px-6 py-4"><?= $i ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($r['name'], ENT_QUOTES) ?></td>
              <td class="px-6 py-4"><?= nl2br(htmlspecialchars($r['contact'], ENT_QUOTES)) ?></td>
              <td class="px-6 py-4 space-x-2">
                <!-- Modifier -->
                <a href="/admin/repairers/form?id=<?= (int)$r['id'] ?>"
                   class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg">
                  Modifier
                </a>
                <!-- Supprimer -->
                <a href="/repairer/delete?id=<?= (int)$r['id'] ?>"
                   onclick="return confirm('Supprimer ce réparateur ?');"
                   class="inline-block bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-lg">
                  Supprimer
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($repairers)): ?>
            <tr>
              <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                Aucun réparateur trouvé.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </main>
</body>
</html>
