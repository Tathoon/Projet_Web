<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <link rel="stylesheet" href="../../styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>

</head>
<body>
<?php
    session_start();

    echo $_SESSION['nom'];

    if (!isset($_SESSION['role']) || ($_SESSION['role'] != 1 )) {
        header('Location: index.php');
        exit();
    }

    if(isset($_POST['logout'])) {
        session_destroy();
        header('Location: ../../index.php');
        exit();
    }
?>

<header class="page-header">
  <nav>
    <img src="../../images/Logo-Web.png" alt="" style="height: 200px;" class="logo">
    <ul class="admin-menu">
      <li class="menu-heading">
        <h3>Admin</h3>
      </li>
      <li>
        <a href="#0">
          <span><i class="fas" style="font-family:Arial, FontAwesome">&#xf201;</i>Dashboard</span>
        </a>
      </li>
      <li>
        <a href="#0">
          <span><i class="fas" style="font-family:Arial, FontAwesome">&#xf0c0;</i>Utilisateurs</span>
        </a>
      </li>
      <li>
        <a href="#0">
          <span><i class="fas" style="font-family:Arial, FontAwesome">&#xf145;</i>Tickets</span>
        </a>
      </li>
      <li class="menu-heading">
        <h3>Settings</h3>
      </li>
      <li>
        <a href="#0">
          <span><i class="fas" style="font-family:Arial, FontAwesome">&#xf013;</i>Paramètre</span>
        </a>
      </li>
      <li>
        <a href="#0">
          <span><i class="fas" style="font-family:Arial, FontAwesome">&#xf0f3;</i>Notifications</span>
        </a>
      </li>
      <li>
        <a href="#0">
          <span><i class="fas" style="font-family:Arial, FontAwesome">&#xf08b;</i>Déconnexion</span>
        </a>
      </li>
      <li>
        <div class="switch">
          <label for="mode">
          </label>
        </div>
      </li>
    </ul>
  </nav>
</header>

<form method="post">
    <button type="submit" name="logout" class="btn btn-warning" style="display: block; margin: auto;">Déconnecter</button>
</form>
    <h1 class="red">ADMIN</h1>
    <script type="text/javascript" src="../../index.js"></script>
</body>
</html>