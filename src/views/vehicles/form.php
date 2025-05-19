<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>
    <?= isset($vehicle) ? 'Modifier un véhicule' : 'Ajouter un véhicule' ?> – Kinga
  </title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
  <!-- Header -->
  <header class="bg-white shadow">
    <div class="container mx-auto px-6 py-4 flex items-center">
      <a href="/" class="mr-4">
        <img src="/assets/img/logo_kinga.png" alt="Kinga Logo" class="h-10">
      </a>
      <h1 class="text-2xl font-bold text-gray-800">
        <?= isset($vehicle) ? 'Modifier ce véhicule' : 'Ajouter un nouveau véhicule' ?>
      </h1>
    </div>
  </header>

  <div class="container mx-auto px-6 py-8">
    <p class="mb-6">
      <a href="/admin" class="text-red-500 hover:underline">&larr; Retour au panneau admin</a>
    </p>

    <div class="bg-white p-6 rounded-lg shadow max-w-lg mx-auto">
      <form action="<?= isset($vehicle) ? '/vehicle/update' : '/vehicle/store' ?>"
            method="post" class="space-y-4">
        <?php if (isset($vehicle)): ?>
          <input type="hidden" name="id" value="<?= (int)$vehicle['id'] ?>">
        <?php endif; ?>

        <div>
          <label class="block text-gray-700 mb-1">Immatriculation <span class="text-red-500">*</span></label>
          <input type="text" name="immatriculation" required
                 value="<?= htmlspecialchars($vehicle['immatriculation'] ?? '', ENT_QUOTES) ?>"
                 class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-400">
        </div>

        <div>
          <label class="block text-gray-700 mb-1">Type</label>
          <input type="text" name="type"
                 value="<?= htmlspecialchars($vehicle['type'] ?? '', ENT_QUOTES) ?>"
                 class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-400">
        </div>

        <div>
          <label class="block text-gray-700 mb-1">Fabricant</label>
          <input type="text" name="fabricant"
                 value="<?= htmlspecialchars($vehicle['fabricant'] ?? '', ENT_QUOTES) ?>"
                 class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-400">
        </div>

        <div>
          <label class="block text-gray-700 mb-1">Modèle</label>
          <input type="text" name="modele"
                 value="<?= htmlspecialchars($vehicle['modele'] ?? '', ENT_QUOTES) ?>"
                 class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-400">
        </div>

        <div>
          <label class="block text-gray-700 mb-1">Couleur</label>
          <input type="text" name="couleur"
                 value="<?= htmlspecialchars($vehicle['couleur'] ?? '', ENT_QUOTES) ?>"
                 class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-400">
        </div>

        <div>
          <label class="block text-gray-700 mb-1">Nombre de sièges</label>
          <input type="number" name="nb_sieges" min="1"
                 value="<?= isset($vehicle) ? (int)$vehicle['nb_sieges'] : '' ?>"
                 class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-400">
        </div>

        <div>
          <label class="block text-gray-700 mb-1">Kilométrage</label>
          <input type="number" name="km" min="0"
                 value="<?= isset($vehicle) ? (int)$vehicle['km'] : '' ?>"
                 class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-400">
        </div>

        <div class="text-right">
          <button type="submit"
                  class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-2 rounded">
            <?= isset($vehicle) ? 'Mettre à jour' : 'Ajouter' ?>
          </button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
