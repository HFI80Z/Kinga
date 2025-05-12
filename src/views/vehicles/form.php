<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>
    <?= isset($vehicle) ? 'Modifier un véhicule' : 'Ajouter un véhicule' ?>
  </title>
  <style>
    form { margin: 1em 0; }
    label { display: block; margin-bottom: .5em; }
    input[type="text"],
    input[type="number"] { width: 200px; }
    button { margin-top: 1em; }
    .back-link { margin-bottom: 1em; display: inline-block; }
  </style>
</head>
<body>
  <h1>
    <?= isset($vehicle) ? 'Modifier ce véhicule' : 'Ajouter un nouveau véhicule' ?>
  </h1>
  <p class="back-link">
    <a href="<?= isset($vehicle) ? '/admin' : '/admin' ?>">← Retour au panneau admin</a>
  </p>

  <form
    action="<?= isset($vehicle) ? '/vehicle/update' : '/vehicle/store' ?>"
    method="post"
  >
    <?php if (isset($vehicle)): ?>
      <input type="hidden" name="id" value="<?= (int)$vehicle['id'] ?>">
    <?php endif; ?>

    <label>
      Immatriculation :
      <input
        type="text"
        name="immatriculation"
        required
        value="<?= htmlspecialchars($vehicle['immatriculation'] ?? '', ENT_QUOTES) ?>"
      >
    </label>

    <label>
      Type :
      <input
        type="text"
        name="type"
        value="<?= htmlspecialchars($vehicle['type'] ?? '', ENT_QUOTES) ?>"
      >
    </label>

    <label>
      Fabricant :
      <input
        type="text"
        name="fabricant"
        value="<?= htmlspecialchars($vehicle['fabricant'] ?? '', ENT_QUOTES) ?>"
      >
    </label>

    <label>
      Modèle :
      <input
        type="text"
        name="modele"
        value="<?= htmlspecialchars($vehicle['modele'] ?? '', ENT_QUOTES) ?>"
      >
    </label>

    <label>
      Couleur :
      <input
        type="text"
        name="couleur"
        value="<?= htmlspecialchars($vehicle['couleur'] ?? '', ENT_QUOTES) ?>"
      >
    </label>

    <label>
      Nombre de sièges :
      <input
        type="number"
        name="nb_sieges"
        min="1"
        value="<?= isset($vehicle) ? (int)$vehicle['nb_sieges'] : '' ?>"
      >
    </label>

    <label>
      Kilométrage :
      <input
        type="number"
        name="km"
        min="0"
        value="<?= isset($vehicle) ? (int)$vehicle['km'] : '' ?>"
      >
    </label>

    <button type="submit">
      <?= isset($vehicle) ? 'Mettre à jour' : 'Ajouter' ?>
    </button>
  </form>
</body>
</html>
