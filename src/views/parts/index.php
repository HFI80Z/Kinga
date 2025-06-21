<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Inventaire Pièces – Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen font-sans">
  <aside class="w-64 bg-gray-800 text-gray-100 p-4 space-y-2">
    <h2 class="text-lg font-bold">Admin</h2>
    <a href="/admin/maintenance" class="block hover:underline">Maintenances</a>
    <a href="/admin/maintenance/history" class="block hover:underline">Historique</a>
    <a href="/admin/parts" class="block font-semibold underline">Inventaire Pièces</a>
  </aside>

  <main class="flex-1 p-6 bg-gray-100 overflow-auto">
    <h1 class="text-2xl font-bold mb-4">Inventaire des Pièces</h1>
    <table class="w-full bg-white rounded shadow overflow-hidden">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2 text-left">Nom</th>
          <th class="px-4 py-2 text-left">Quantité</th>
          <th class="px-4 py-2"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($parts as $part): ?>
        <tr class="border-t">
          <td class="px-4 py-2"><?= htmlspecialchars($part['name']) ?></td>
          <td class="px-4 py-2">
            <form action="/admin/parts/update-stock" method="post" class="flex space-x-2 items-center">
              <input type="hidden" name="id" value="<?= (int)$part['id'] ?>">
              <input type="number" name="stock" value="<?= (int)$part['stock'] ?>" min="0"
                     class="w-24 px-2 py-1 border rounded">
              <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Maj</button>
            </form>
          </td>
          <td class="px-4 py-2"></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </main>
</body>
</html>
