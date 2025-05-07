<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Se connecter</title>
  <style>
    .error { 
      background: #fdd; 
      color: #900; 
      padding: 0.5em; 
      margin-bottom: 1em; 
      border: 1px solid #900;
    }
  </style>
</head>
<body>
  <h1>Se connecter</h1>

  <?php if (!empty($error)): ?>
    <div class="error"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
  <?php endif; ?>

  <form action="/login" method="post">
    <label>Email:<br>
      <input type="email" name="email" required autofocus>
    </label><br><br>

    <label>Mot de passe:<br>
      <input type="password" name="password" required>
    </label><br><br>

    <button type="submit">Se connecter</button>
  </form>

  <p><a href="/">← Retour à la liste</a></p>
</body>
</html>
