<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Nouvelle Maintenance – Kinga</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
  <?php include __DIR__ . '/../partials/lang_toggle.php'; ?>

  <!-- HEADER MINIMALISTE -->
  <header class="bg-white shadow">
    <div class="container mx-auto px-6 py-4 flex items-center justify-between">
      <h1 class="text-2xl font-bold text-gray-800">Nouvelle Maintenance</h1>
      <a href="/admin/maintenance" class="text-red-500 hover:underline">← Retour à la liste des maintenances</a>
    </div>
  </header>

  <main class="flex-1 container mx-auto px-6 py-8">
    <div class="bg-white p-6 rounded-lg shadow max-w-3xl mx-auto">
      <form action="/maintenance/store" method="post" class="space-y-6">

        <!-- Sélection du véhicule (si vehicle déjà passé en GET, on l’affiche en readonly) -->
        <?php if (isset($vehicle) && $vehicle): ?>
          <input type="hidden" name="vehicle_id" value="<?= (int)$vehicle['id'] ?>">
          <div>
            <label class="block text-gray-700 mb-1">Véhicule</label>
            <input type="text" readonly
                   value="<?= htmlspecialchars($vehicle['immatriculation'] . ' – ' . $vehicle['modele'], ENT_QUOTES) ?>"
                   class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none">
          </div>
        <?php else: ?>
          <div>
            <label class="block text-gray-700 mb-1">Véhicule <span class="text-red-500">*</span></label>
            <select name="vehicle_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">-- Sélectionner un véhicule --</option>
              <?php
                // On récupère la liste des véhicules non en maintenance (pour ne pas en rajouter plusieurs fois)
                // Pour cela, vous pouvez faire dans le contrôleur un appel à VehicleModel::getAvailableForMaintenance()
                // Ici, je suppose que le contrôleur a passé un tableau `$availableVehicles` dans la vue.
                foreach ($availableVehicles as $v):
              ?>
                <option value="<?= (int)$v['id'] ?>">
                  <?= htmlspecialchars($v['immatriculation'] . ' – ' . $v['modele'], ENT_QUOTES) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        <?php endif; ?>

        <!-- Sélection du réparateur -->
        <div>
          <label class="block text-gray-700 mb-1">Réparateur <span class="text-red-500">*</span></label>
          <select name="repairer_id" required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Sélectionner un réparateur --</option>
            <?php foreach ($repairers as $r): ?>
              <option value="<?= (int)$r['id'] ?>">
                <?= htmlspecialchars($r['name'], ENT_QUOTES) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Raison de la maintenance -->
        <div>
          <label class="block text-gray-700 mb-1">Raison <span class="text-red-500">*</span></label>
          <textarea name="reason" rows="3" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Ex : Vidange moteur, problème de freins…"></textarea>
        </div>

        <!-- Pièces nécessaires (on peut en ajouter plusieurs) -->
        <div id="parts-container" class="space-y-4">
          <label class="block text-gray-700 mb-1">Pièces nécessaires</label>
          <div class="space-y-2">
            <div class="grid grid-cols-3 gap-3 items-end">
              <div>
                <label class="block text-sm text-gray-600">Nom de la pièce</label>
                <input type="text" name="part_name[]" placeholder="Ex : Filtre à huile"
                       class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm text-gray-600">Quantité</label>
                <input type="number" name="quantity[]" min="1" value="1"
                       class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
              </div>
              <div class="text-right">
                <button type="button"
                        class="btn-remove-part inline-block text-red-500 hover:text-red-700 font-semibold px-3 py-1 rounded-lg">
                  Supprimer
                </button>
              </div>
            </div>
          </div>
        </div>
        <button type="button" id="btn-add-part"
                class="inline-block text-blue-600 hover:text-blue-800 font-semibold">
          + Ajouter une autre pièce
        </button>

        <!-- Boutons d’action -->
        <div class="flex justify-between pt-4 border-t border-gray-200">
          <a href="/admin/maintenance"
             class="inline-block px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg">
            Annuler
          </a>
          <button type="submit"
                  class="inline-block px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg">
            Enregistrer la maintenance
          </button>
        </div>
      </form>
    </div>
  </main>

  <script>
    // JS minimal pour cloner le bloc “pièce” quand on clique sur “+ Ajouter une autre pièce”
    document.getElementById('btn-add-part').addEventListener('click', function() {
      const container = document.getElementById('parts-container');
      // Récupérer le premier bloc existant (le template)
      const firstBlock = container.querySelector('div.space-y-2 > div');
      const clone = firstBlock.cloneNode(true);
      // Vider les champs textes dans le clone
      clone.querySelectorAll('input').forEach(i => {
        if (i.name === 'quantity[]') {
          i.value = '1';
        } else {
          i.value = '';
        }
      });
      container.appendChild(clone);
      // Re-attacher l’événement “Supprimer” sur le clone
      attachRemoveListeners();
    });

    function attachRemoveListeners() {
      document.querySelectorAll('.btn-remove-part').forEach(btn => {
        btn.addEventListener('click', function() {
          const row = btn.closest('div.grid');
          // On ne supprime pas le dernier bloc (au moins un bloc doit rester)
          const totalBlocks = document.querySelectorAll('#parts-container div.grid').length;
          if (totalBlocks > 1) {
            row.remove();
          } else {
            // Si on veut vider le premier
            row.querySelectorAll('input').forEach(i => i.value = '');
          }
        });
      });
    }
    // Attacher dès l’ouverture
    attachRemoveListeners();
  </script>
</body>
</html>
