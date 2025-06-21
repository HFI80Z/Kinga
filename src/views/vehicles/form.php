<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title><?= isset($vehicle) ? 'Modifier un véhicule' : 'Ajouter un véhicule' ?> – Kinga</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
  <?php include __DIR__ . '/../partials/lang_toggle.php'; ?>
  <!-- Header minimaliste -->
  <header class="bg-white shadow">
    <div class="container mx-auto px-6 py-4 flex items-center">
      <h1 class="text-2xl font-bold text-gray-800">
        <?= isset($vehicle) ? 'Modifier ce véhicule' : 'Ajouter un nouveau véhicule' ?>
      </h1>
    </div>
  </header>

  <main class="flex-1 container mx-auto px-6 py-8">
    <div class="bg-white p-6 rounded-lg shadow max-w-3xl mx-auto">
      <form action="<?= isset($vehicle) ? '/vehicle/update' : '/vehicle/store' ?>"
            method="post" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php if (isset($vehicle)): ?>
          <input type="hidden" name="id" value="<?= (int)$vehicle['id'] ?>">
        <?php endif; ?>

        <!-- Immatriculation -->
        <div class="col-span-1">
          <label class="block text-gray-700 mb-1">
            Immatriculation <span class="text-red-500">*</span>
          </label>
          <input type="text" name="immatriculation" required
                 value="<?= htmlspecialchars($vehicle['immatriculation'] ?? '', ENT_QUOTES) ?>"
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Type -->
        <div class="col-span-1">
          <label class="block text-gray-700 mb-1">Type</label>
          <select name="type"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="" <?= empty($vehicle['type']) ? 'selected' : '' ?>>-- Sélectionner --</option>
            <option value="Moto" <?= (isset($vehicle) && $vehicle['type'] === 'Moto') ? 'selected' : '' ?>>Moto</option>
            <option value="Berline" <?= (isset($vehicle) && $vehicle['type'] === 'Berline') ? 'selected' : '' ?>>Berline</option>
            <option value="Pick up" <?= (isset($vehicle) && $vehicle['type'] === 'Pick up') ? 'selected' : '' ?>>Pick up</option>
          </select>
        </div>

        <!-- Fabricant -->
        <div class="col-span-1">
          <label class="block text-gray-700 mb-1">Fabricant</label>
          <select name="fabricant"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="" <?= empty($vehicle['fabricant']) ? 'selected' : '' ?>>-- Sélectionner --</option>
            <option value="Honda" <?= (isset($vehicle) && $vehicle['fabricant'] === 'Honda') ? 'selected' : '' ?>>Honda</option>
            <option value="TVS" <?= (isset($vehicle) && $vehicle['fabricant'] === 'TVS') ? 'selected' : '' ?>>TVS</option>
          </select>
        </div>

        <!-- Modèle -->
        <div class="col-span-1">
          <label class="block text-gray-700 mb-1">Modèle</label>
          <input type="text" name="modele"
                 value="<?= htmlspecialchars($vehicle['modele'] ?? '', ENT_QUOTES) ?>"
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Couleur -->
        <div class="col-span-1">
          <label class="block text-gray-700 mb-1">Couleur</label>
          <select name="couleur"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="" <?= empty($vehicle['couleur']) ? 'selected' : '' ?>>-- Sélectionner --</option>
            <option value="Noir"   <?= (isset($vehicle) && $vehicle['couleur'] === 'Noir')   ? 'selected' : '' ?>>Noir</option>
            <option value="Bleu"   <?= (isset($vehicle) && $vehicle['couleur'] === 'Bleu')   ? 'selected' : '' ?>>Bleu</option>
            <option value="Rouge"  <?= (isset($vehicle) && $vehicle['couleur'] === 'Rouge')  ? 'selected' : '' ?>>Rouge</option>
            <option value="Blanc"  <?= (isset($vehicle) && $vehicle['couleur'] === 'Blanc')  ? 'selected' : '' ?>>Blanc</option>
            <option value="Gris"   <?= (isset($vehicle) && $vehicle['couleur'] === 'Gris')   ? 'selected' : '' ?>>Gris</option>
            <option value="Vert"   <?= (isset($vehicle) && $vehicle['couleur'] === 'Vert')   ? 'selected' : '' ?>>Vert</option>
            <option value="Jaune"  <?= (isset($vehicle) && $vehicle['couleur'] === 'Jaune')  ? 'selected' : '' ?>>Jaune</option>
          </select>
        </div>

        <!-- Nb sièges -->
        <div class="col-span-1">
          <label class="block text-gray-700 mb-1">Nombre de sièges</label>
          <input type="number" name="nb_sieges" min="1"
                 value="<?= isset($vehicle) ? (int)$vehicle['nb_sieges'] : '' ?>"
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Kilométrage -->
        <div class="col-span-1">
          <label class="block text-gray-700 mb-1">Kilométrage</label>
          <input type="number" name="km" min="0"
                 value="<?= isset($vehicle) ? (int)$vehicle['km'] : '' ?>"
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Boutons d’action en pleine largeur des deux colonnes -->
        <div class="col-span-1 md:col-span-2 flex justify-between pt-6 border-t border-gray-200">
          <!-- Annuler -->
          <a href="/admin"
             class="inline-block px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg">
            Annuler
          </a>

          <?php if (isset($vehicle)): ?>
          <!-- Supprimer -->
          <a href="/vehicle/delete?id=<?= (int)$vehicle['id'] ?>"
             onclick="return confirm('Supprimer ce véhicule ?')"
             class="inline-block px-6 py-2 bg-yellow-400 hover:bg-yellow-500 text-white font-semibold rounded-lg">
            Supprimer
          </a>
          <?php endif; ?>

          <!-- Mettre à jour / Ajouter -->
          <button type="submit"
                  class="inline-block px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg">
            <?= isset($vehicle) ? 'Mettre à jour' : 'Ajouter' ?>
          </button>
        </div>

      </form>
    </div>
  </main>
</body>
</html>
