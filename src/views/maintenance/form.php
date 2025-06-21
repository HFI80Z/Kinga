<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Nouvelle Maintenance – Kinga</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

  <header class="bg-white shadow">
    <div class="container mx-auto px-6 py-4 flex items-center justify-between">
      <h1 class="text-2xl font-bold text-gray-800">Nouvelle Maintenance</h1>
      <a href="/admin/maintenance" class="text-red-500 hover:underline">← Retour à la liste des maintenances</a>
    </div>
  </header>

  <main class="flex-1 container mx-auto px-6 py-8">
    <div class="bg-white p-6 rounded-lg shadow max-w-3xl mx-auto">
      <form action="/maintenance/store" method="post" class="space-y-6">

        <!-- Véhicule -->
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
              <?php foreach ($availableVehicles as $v): ?>
                <option value="<?= (int)$v['id'] ?>">
                  <?= htmlspecialchars($v['immatriculation'] . ' – ' . $v['modele'], ENT_QUOTES) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        <?php endif; ?>

        <!-- Réparateur -->
        <div>
          <label class="block text-gray-700 mb-1">Réparateur <span class="text-red-500">*</span></label>
          <select name="repairer_id" required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Sélectionner un réparateur --</option>
            <?php foreach ($repairers as $r): ?>
              <option value="<?= (int)$r['id'] ?>"><?= htmlspecialchars($r['name'], ENT_QUOTES) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Service -->
        <div>
          <label class="block text-gray-700 mb-1">Service <span class="text-red-500">*</span></label>
          <select name="service_id" id="service-select" required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
            <option value="">-- Choisir un service --</option>
            <?php foreach ($services as $s): ?>
              <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name'], ENT_QUOTES) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Raison -->
        <div>
          <label class="block text-gray-700 mb-1">Raison <span class="text-red-500">*</span></label>
          <select name="reason_id" id="reason-select" required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
            <option value="">-- Choisir une raison --</option>
          </select>
        </div>

        <!-- Pièces nécessaires -->
        <div id="parts-container" class="space-y-4">
          <label class="block text-gray-700 mb-1">Pièces nécessaires</label>
          <div class="space-y-2">
            <div class="grid grid-cols-3 gap-3 items-end">
              <div>
                <label class="block text-sm text-gray-600">Nom de la pièce</label>
                <select name="part_name[]" class="part-select mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg">
                  <option value="">-- Choisir une pièce --</option>
                  <?php foreach ($parts as $p): ?>
                    <option value="<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>" data-stock="<?= $p['stock'] ?>">
                      <?= htmlspecialchars($p['name'] . ' (' . $p['stock'] . ')', ENT_QUOTES) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label class="block text-sm text-gray-600">Quantité</label>
                <input type="number" name="quantity[]" min="1" value="1"
                       class="part-quantity mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg">
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

        <!-- Actions -->
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
    const services = <?= json_encode($services) ?>;
    const serviceSelect = document.getElementById('service-select');
    const reasonSelect = document.getElementById('reason-select');

    serviceSelect.addEventListener('change', function () {
      const selectedId = this.value;
      reasonSelect.innerHTML = '<option value="">-- Choisir une raison --</option>';
      const selectedService = services.find(s => s.id == selectedId);
      if (selectedService && selectedService.reasons) {
        selectedService.reasons.forEach(r => {
          const opt = document.createElement('option');
          opt.value = r.id;
          opt.textContent = r.label;
          reasonSelect.appendChild(opt);
        });
      }
    });

    function updateQuantityLimits() {
      document.querySelectorAll('.part-select').forEach(select => {
        select.addEventListener('change', () => {
          const stock = parseInt(select.options[select.selectedIndex].getAttribute('data-stock'), 10) || 1;
          const qtyInput = select.closest('.grid').querySelector('.part-quantity');
          qtyInput.max = stock;
          if (parseInt(qtyInput.value, 10) > stock) {
            qtyInput.value = stock;
          }
        });
      });
    }

    function attachRemoveListeners() {
      document.querySelectorAll('.btn-remove-part').forEach(btn => {
        btn.addEventListener('click', function () {
          const row = btn.closest('div.grid');
          const total = document.querySelectorAll('#parts-container div.grid').length;
          if (total > 1) row.remove();
          else row.querySelectorAll('select, input').forEach(i => i.value = '');
        });
      });
    }

    document.getElementById('btn-add-part').addEventListener('click', function () {
      const container = document.getElementById('parts-container');
      const firstBlock = container.querySelector('div.space-y-2 > div');
      const clone = firstBlock.cloneNode(true);
      clone.querySelectorAll('select, input').forEach(i => {
        if (i.tagName === 'INPUT') i.value = i.name === 'quantity[]' ? '1' : '';
        if (i.tagName === 'SELECT') i.selectedIndex = 0;
      });
      container.querySelector('.space-y-2').appendChild(clone);
      attachRemoveListeners();
      updateQuantityLimits();
    });

    attachRemoveListeners();
    updateQuantityLimits();
  </script>
</body>
</html>
