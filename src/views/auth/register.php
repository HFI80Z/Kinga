<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>S'inscrire</title>
</head>
<body>
  <h1>S'inscrire</h1>
  <form action="/register" method="post">
    <label>Nom:<br><input type="text" name="user_name" required></label><br><br>
    <label>Prénom:<br><input type="text" name="user_firstname" required></label><br><br>
    <label>Email:<br><input type="email" name="email" required></label><br><br>
    <label>Mot de passe:<br><input type="password" name="password" required></label><br><br>
    <button type="submit">S'inscrire</button>
  </form>
  <p><a href="/">← Retour à la liste</a></p>
</body>
</html>
