<!-- views/parts/inventory.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Inventaire des pièces</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
  <h1 class="text-2xl font-bold mb-4">Inventaire des pièces</h1>

  <div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="min-w-full table-auto">
      <thead class="bg-gray-200">
        <tr>
          <th class="px-4 py-2">#</th>
          <th class="px-4 py-2">Nom</th>
          <th class="px-4 py-2">Quantité</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($parts as $index => $part): ?>
          <tr class="border-t">
            <td class="px-4 py-2"><?= $index + 1 ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($part['name']) ?></td>
            <td class="px-4 py-2"><?= (int)($part['stock'] ?? 0) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
