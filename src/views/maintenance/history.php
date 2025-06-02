<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Panneau Admin – Historique des maintenances – Kinga</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen overflow-hidden font-sans">

  <!-- SIDEBAR (identique aux autres vues admin) -->
  <aside class="w-64 bg-gray-800 text-gray-100 flex-shrink-0 flex flex-col">
    <!-- Logo -->
    <div class="px-6 py-4 flex items-center">
      <a href="/"><img src="/assets/img/logo_kinga.png" alt="Kinga Logo" class="h-8"></a>
    </div>
    <!-- Menu -->
    <nav class="flex-1 px-2 space-y-1">
      <a href="/admin"
         class="block px-4 py-2 rounded hover:bg-gray-700">
        Liste Véhicules
      </a>
      <a href="/admin/maintenance"
         class="block px-4 py-2 rounded hover:bg-gray-700">
        Maintenances
      </a>
      <a href="/admin/maintenance/history"
         class="block px-4 py-2 rounded bg-gray-700">
        Historique
      </a>
      <a href="/admin/repairers"
         class="block px-4 py-2 rounded hover:bg-gray-700">
        Réparateurs
      </a>
      <a href="/admin/orders"
         class="block px-4 py-2 rounded hover:bg-gray-700">
        Commandes
      </a>
      <a href="/admin/users"
         class="block px-4 py-2 rounded hover:bg-gray-700">
        Utilisateurs
      </a>
    </nav>
    <!-- Déconnexion -->
    <div class="px-6 py-4 border-t border-gray-700">
      <a href="/logout" class="block text-red-400 hover:text-red-300">
        Déconnexion
      </a>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="flex-1 bg-gray-100 overflow-auto p-6">

    <!-- TITRE + Bouton “Effacer historique” -->
    <section class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Historique des maintenances</h1>
      <form action="/admin/maintenance/history/clear" method="post"
            onsubmit="return confirm('Voulez-vous vraiment supprimer tout l’historique des maintenances ?');">
        <button type="submit"
                class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-lg">
          Effacer historique
        </button>
      </form>
    </section>

    <!-- TABLEAU HISTORIQUE -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Véhicule (Immat.)</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Réparateur</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Raison</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Début</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fin</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durée</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php if (empty($history)): ?>
            <tr>
              <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                Aucun historique à afficher.
              </td>
            </tr>
          <?php else: ?>
            <?php $i = 0; foreach ($history as $h): $i++; ?>
              <tr>
                <td class="px-6 py-4"><?= $i ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($h['immatriculation'], ENT_QUOTES) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($h['type'], ENT_QUOTES) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($h['repairer_name'], ENT_QUOTES) ?></td>
                <td class="px-6 py-4"><?= nl2br(htmlspecialchars($h['reason'], ENT_QUOTES)) ?></td>
                <td class="px-6 py-4"><?= (new DateTime($h['created_at']))->format('d/m/Y H:i') ?></td>
                <td class="px-6 py-4">
                  <?= 
                    $h['closed_at']
                      ? (new DateTime($h['closed_at']))->format('d/m/Y H:i') 
                      : '<span class="text-gray-400">En cours</span>' 
                  ?>
                </td>
                <td class="px-6 py-4"><?= htmlspecialchars($h['duration'], ENT_QUOTES) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- PAGINATION -->
    <div class="flex justify-between items-center mt-4">
      <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>"
           class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
          ← Précédent
        </a>
      <?php else: ?>
        <span class="w-24"></span>
      <?php endif; ?>

      <span class="text-gray-600">Page <?= $page ?> sur <?= $totalPages ?></span>

      <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>"
           class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
          Suivant →
        </a>
      <?php else: ?>
        <span class="w-24"></span>
      <?php endif; ?>
    </div>

  </main>

</body>
</html>
