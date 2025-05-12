<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Panneau Admin – Véhicules</title>
  <style>
    form { margin-bottom: 2em; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #333; padding: 0.5em; }
  </style>
</head>
<body>
  <h1>Panneau Administrateur</h1>
  <p><a href="/">← Retour à la liste publique</a></p>

  <h2>Ajouter un véhicule</h2>
  <form action="/vehicle/store" method="post">
    <label>Immatriculation <input name="immatriculation" required></label><br>
    <label>Type <input name="type"></label><br>
    <label>Fabricant <input name="fabricant"></label><br>
    <label>Modèle <input name="modele"></label><br>
    <label>Couleur <input name="couleur"></label><br>
    <label>Nombre de sièges <input type="number" name="nb_sieges" min="1"></label><br>
    <label>Kilométrage <input type="number" name="km" min="0"></label><br><br>
    <button type="submit">Ajouter</button>
  </form>

  <h2>Liste complète des véhicules</h2>
  <table>
    <thead>
      <tr>
        <th>Immat.</th>
        <th>Type</th>
        <th>Fabricant</th>
        <th>Modèle</th>
        <th>Couleur</th>
        <th>Sièges</th>
        <th>KM</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($vehicles as $v): ?>
        <tr>
          <td><?= htmlspecialchars($v['immatriculation'], ENT_QUOTES) ?></td>
          <td><?= htmlspecialchars($v['type'],            ENT_QUOTES) ?></td>
          <td><?= htmlspecialchars($v['fabricant'],       ENT_QUOTES) ?></td>
          <td><?= htmlspecialchars($v['modele'],          ENT_QUOTES) ?></td>
          <td><?= htmlspecialchars($v['couleur'],         ENT_QUOTES) ?></td>
          <td><?= (int)$v['nb_sieges'] ?></td>
          <td><?= number_format((int)$v['km'],0,'',' ') ?></td>
          <td>
            <a href="/vehicle/edit?id=<?= (int)$v['id'] ?>">Modifier</a>
            |
            <a href="/vehicle/delete?id=<?= (int)$v['id'] ?>"
               onclick="return confirm('Supprimer ce véhicule ?');">
              Supprimer
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
