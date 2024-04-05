<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
    session_start();

    echo $_SESSION['nom'];

    if (!isset($_SESSION['role']) || ($_SESSION['role'] != 3 && $_SESSION['role'] != 1)) {
        header('Location: index.php');
        exit();
    }

    if(isset($_POST['logout'])) {
        session_destroy();
        header('Location: index.php');
        exit();
    }
?>
<form method="post">
    <button type="submit" name="logout" class="btn btn-warning" style="display: block; margin: auto;">Déconnecter</button>
</form>
    <h1>COMTPABLE</h1>
</body>
</html>