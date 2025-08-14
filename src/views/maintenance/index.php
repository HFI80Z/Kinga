<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Panneau Admin – Maintenances – Kinga</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen overflow-hidden font-sans">
  <?php include __DIR__ . '/../partials/lang_toggle.php'; ?>

  <!-- Sidebar -->
  <aside class="w-64 bg-gray-800 text-gray-100 flex-shrink-0 flex flex-col">
    <div class="px-6 py-4 flex items-center">
      <a href="/"><img src="/assets/img/logo_kinga.png" alt="Kinga Logo" class="h-8"></a>
    </div>
    <nav class="flex-1 px-2 space-y-1">
      <a href="/admin" class="block px-4 py-2 rounded hover:bg-gray-700">Liste Véhicules</a>
      <a href="/admin/maintenance" class="block px-4 py-2 rounded bg-gray-700">Maintenances</a>
      <a href="/admin/maintenance/history" class="block px-4 py-2 rounded hover:bg-gray-700">Historique</a>
      <a href="/admin/repairers" class="block px-4 py-2 rounded hover:bg-gray-700">Réparateurs</a>
    </nav>
    <div class="px-6 py-4 border-t border-gray-700">
      <a href="/logout" class="block text-red-400 hover:text-red-300">Déconnexion</a>
    </div>
  </aside>

  <!-- Main -->
  <main class="flex-1 bg-gray-100 overflow-auto p-6">

    <section class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Panneau Admin – Maintenances</h1>
      <!--
      <a href="/admin/maintenance/form"
         class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-lg">
        + Nouvelle maintenance
      </a>
      -->
    </section>

    <!-- Tableau -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Véhicule (Immat.)</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Réparateur</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Raison</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date de début</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php if (empty($maintenances)): ?>
            <tr>
              <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                Aucune maintenance en cours.
              </td>
            </tr>
          <?php else: ?>
            <?php
              $i = 0;
              $startIndex = max(0, ((int)$page - 1) * 5);
              foreach ($maintenances as $m):
                $i++;

                // Sécurisation des champs utilisés par la vue
                $immatriculation = htmlspecialchars($m['immatriculation'] ?? '', ENT_QUOTES);
                $type            = htmlspecialchars($m['type'] ?? '', ENT_QUOTES);
                $repairerName    = htmlspecialchars($m['repairer_name'] ?? '', ENT_QUOTES);
                $reasonLabel     = nl2br(htmlspecialchars($m['reason_label'] ?? '', ENT_QUOTES));
                $createdAtRaw    = $m['created_at'] ?? null;

                // Gestion de la date (évite exception si format invalide)
                $createdAt = '—';
                if (!empty($createdAtRaw)) {
                  try { $createdAt = (new DateTime($createdAtRaw))->format('d/m/Y H:i'); }
                  catch (Exception $e) { $createdAt = htmlspecialchars($createdAtRaw, ENT_QUOTES); }
                }

                // ID maintenance : évite notices + injection d'un "0" dans le HTML
                $maintenanceId = isset($m['maintenance_id']) ? (int)$m['maintenance_id'] : 0;

                // Pièces : assure un tableau JSON valide dans l'attribut data-parts
                $partsArray = [];
                if (!empty($m['parts'])) {
                  // $m['parts'] peut déjà être un tableau OU une chaîne JSON
                  if (is_array($m['parts'])) {
                    $partsArray = $m['parts'];
                  } elseif (is_string($m['parts'])) {
                    $decoded = json_decode($m['parts'], true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                      $partsArray = $decoded;
                    }
                  }
                }
                $partsJson = htmlspecialchars(json_encode($partsArray, JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT), ENT_QUOTES);
            ?>
              <tr>
                <td class="px-6 py-4"><?= $startIndex + $i ?></td>
                <td class="px-6 py-4"><?= $immatriculation ?></td>
                <td class="px-6 py-4"><?= $type ?></td>
                <td class="px-6 py-4"><?= $repairerName ?></td>
                <td class="px-6 py-4"><?= $reasonLabel ?></td>
                <td class="px-6 py-4"><?= $createdAt ?></td>
                <td class="px-6 py-4 space-x-2">
                  <!-- Détails -->
                  <button type="button"
                          class="btn-detail inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg"
                          data-parts='<?= $partsJson ?>'>
                    Détails
                  </button>

                  <!-- Sortir de maintenance (affiché seulement si l'ID existe) -->
                  <?php if ($maintenanceId > 0): ?>
                    <a href="/maintenance/close?id=<?= $maintenanceId ?>"
                       onclick="return confirm('Sortir ce véhicule de la maintenance ?');"
                       class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded-lg">
                      Sortir de maintenance
                    </a>
                  <?php else: ?>
                    <span class="inline-block align-middle text-gray-400 italic">
                      ID maintenance manquant
                    </span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
      <?php if ($page > 1): ?>
        <a href="?page=<?= (int)$page - 1 ?>"
           class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
          ← Précédent
        </a>
      <?php else: ?>
        <span class="w-24"></span>
      <?php endif; ?>

      <span class="text-gray-600">Page <?= (int)$page ?> sur <?= (int)$totalPages ?></span>

      <?php if ($page < $totalPages): ?>
        <a href="?page=<?= (int)$page + 1 ?>"
           class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
          Suivant →
        </a>
      <?php else: ?>
        <span class="w-24"></span>
      <?php endif; ?>
    </div>

  </main>

  <!-- Modal -->
  <div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>
  <div id="modal" class="hidden fixed inset-0 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-lg">
      <h2 class="text-xl font-semibold mb-4">Pièces nécessaires</h2>
      <div id="modalContent" class="space-y-2 text-gray-700"></div>
      <div class="mt-6 flex justify-end">
        <button id="modalClose" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">Fermer</button>
      </div>
    </div>
  </div>

  <script>
    const overlayM = document.getElementById('overlay');
    const modalM   = document.getElementById('modal');
    const contentM = document.getElementById('modalContent');
    const btnClose = document.getElementById('modalClose');

    document.querySelectorAll('button.btn-detail').forEach(btn => {
      btn.addEventListener('click', () => {
        let parts = [];
        try {
          const raw = btn.getAttribute('data-parts') || '[]';
          parts = JSON.parse(raw);
          if (!Array.isArray(parts)) parts = [];
        } catch (e) {
          parts = [];
        }

        if (!parts.length) {
          contentM.innerHTML = '<p class="text-gray-500">Aucune pièce enregistrée.</p>';
        } else {
          let html = '<ul class="list-disc list-inside">';
          parts.forEach(p => {
            const name = (p && p.part_name) ? p.part_name : 'Pièce';
            const qty  = (p && p.quantity) ? p.quantity : 1;
            html += `<li>${name} — Quantité : ${qty}</li>`;
          });
          html += '</ul>';
          contentM.innerHTML = html;
        }

        overlayM.classList.remove('hidden');
        modalM.classList.remove('hidden');
      });
    });

    btnClose.onclick = overlayM.onclick = () => {
      overlayM.classList.add('hidden');
      modalM.classList.add('hidden');
    };
  </script>

</body>
</html>
