<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/css.gg@2.0.0/icons/css/mail.css' rel='stylesheet'>
</head>
<body>
<img src="Logo web (1).png" alt="Logo" class="img-login"> <!-- Ajout de l'image -->
    <form action="#" method="post">
        <div class="form-group">
            <label for="email" hidden></label>
            <input type="email" id="email" name="email" placeholder="E-mail" required>
        </div>
        <div class="form-group">
            <label for="password" hidden></label> 
            <input type="password" id="password" name="password" placeholder="Mot de passe" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Login">
        </div>
    </form>
    <div class="target">
        <div class="center"></div>
        <div class="outer-circle"></div>
        <div class="inner-circle"></div>
    </div>
</body>
</html>
