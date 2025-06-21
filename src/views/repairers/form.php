<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>
    <?= isset($repairer) ? 'Modifier le réparateur' : 'Ajouter un réparateur' ?> – Kinga
  </title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
  <?php include __DIR__ . '/../partials/lang_toggle.php'; ?>

  <!-- HEADER MINIMALISTE -->
  <header class="bg-white shadow">
    <div class="container mx-auto px-6 py-4 flex items-center justify-between">
      <h1 class="text-2xl font-bold text-gray-800">
        <?= isset($repairer) ? 'Modifier le réparateur' : 'Ajouter un nouveau réparateur' ?>
      </h1>
      <a href="/admin/repairers"
         class="text-red-500 hover:underline">← Retour à la liste</a>
    </div>
  </header>

  <!-- FORMULAIRE -->
  <main class="flex-1 container mx-auto px-6 py-8">
    <div class="bg-white p-6 rounded-lg shadow max-w-2xl mx-auto">
      <form action="<?= isset($repairer) ? '/repairer/update' : '/repairer/store' ?>"
            method="post" class="space-y-6">
        <?php if (isset($repairer)): ?>
          <input type="hidden" name="id" value="<?= (int)$repairer['id'] ?>">
        <?php endif; ?>

        <!-- Nom du réparateur -->
        <div>
          <label class="block text-gray-700 mb-1">
            Nom <span class="text-red-500">*</span>
          </label>
          <input type="text" name="name" required
                 value="<?= htmlspecialchars($repairer['name'] ?? '', ENT_QUOTES) ?>"
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Contact (email/ téléphone / notes) -->
        <div>
          <label class="block text-gray-700 mb-1">Contact</label>
          <textarea name="contact" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Email, téléphone, adresse, notes…"><?= htmlspecialchars($repairer['contact'] ?? '', ENT_QUOTES) ?></textarea>
        </div>

        <!-- Boutons -->
        <div class="flex justify-between pt-4 border-t border-gray-200">
          <a href="/admin/repairers"
             class="inline-block px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg">
            Annuler
          </a>
          <?php if (isset($repairer)): ?>
            <a href="/repairer/delete?id=<?= (int)$repairer['id'] ?>"
               onclick="return confirm('Supprimer ce réparateur ?');"
               class="inline-block px-6 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg">
              Supprimer
            </a>
          <?php endif; ?>
          <button type="submit"
                  class="inline-block px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg">
            <?= isset($repairer) ? 'Mettre à jour' : 'Créer' ?>
          </button>
        </div>
      </form>
    </div>
  </main>

</body>
</html>
