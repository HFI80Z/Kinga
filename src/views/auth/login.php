<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Se connecter – Kinga</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
  <?php include __DIR__ . '/../partials/lang_toggle.php'; ?>
  <div class="bg-white shadow-lg rounded-lg w-full max-w-md p-8">
    <!-- Logo + titre -->
    <div class="text-center mb-6">
      <a href="/">
        <img src="/assets/img/logo_kinga.png" alt="Kinga Logo" class="h-12 mx-auto">
      </a>
      <h1 class="mt-4 text-2xl font-bold text-gray-800">Se connecter</h1>
    </div>

    <!-- Erreur -->
    <?php if (!empty($error)): ?>
      <div class="bg-red-100 text-red-700 border border-red-300 rounded p-3 mb-4">
        <?= htmlspecialchars($error, ENT_QUOTES) ?>
      </div>
    <?php endif; ?>

    <!-- Formulaire -->
    <form action="/login" method="post" class="space-y-4">
      <div>
        <label class="block text-gray-700 mb-1">Email</label>
        <input type="email" name="email" required autofocus
               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-400">
      </div>
      <div>
        <label class="block text-gray-700 mb-1">Mot de passe</label>
        <input type="password" name="password" required
               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-400">
      </div>
      <button type="submit"
              class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded">
        Se connecter
      </button>
    </form>

    <p class="mt-4 text-center text-gray-600 text-sm">
      <a href="/" class="hover:underline">← Retour à la liste publique</a>
    </p>
  </div>
</body>
</html>
