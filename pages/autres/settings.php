<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Paramètres</title>
  <link rel="icon" href="../../images/Logo_onglet.png" type="image/x-icon">
  <link rel="stylesheet" href="../../styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
</head>
<body>
  <?php
    session_start();
    echo $_SESSION['nom'];

    if (!isset($_SESSION['role']) || ($_SESSION['role'] != 1 && $_SESSION['role'] != 3 && $_SESSION['role'] != 2)) {
      header('Location: ../../index.php');
      session_destroy();
      exit();
    }

    if(isset($_GET['logout'])) {
      session_destroy();
      header('Location: ../../index.php');
      exit();
     }
  ?>

  <input type="checkbox" id="check">
  <header>
    <label for="check">
      <i class="fas fa-bars" id="sidebar_btn"></i>
    </label>
    <div class="left_area">
      <h3>E11<span>event</span></h3>
    </div>
  </header>

  <div class="mobile_nav">
    <div class="nav_bar">
      <img src="../../images/user-icon.png" class="mobile_profile_image" alt="">
      <h4 class="user-mobile"><?php echo ucfirst($_SESSION['nom']) . " " . ucfirst($_SESSION['prenom']) ; ?></h4>
      <i class="fa fa-bars nav_btn"></i>
    </div>
    <div class="mobile_nav_items">
      <a href="notifications.php"><i class="fa-solid fa-bell"></i><span>Notifications</span></a>
      <a href="#" class="active"><i class="fas fa-sliders-h"></i><span>Settings</span></a>
      <a href="javascript:history.go(-1)"><i class="fa-solid fa-arrow-left"></i></i><span>Retour</span></a>
      <a href="../../index.php?logout=true" ><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
    </div>
  </div>

  <div class="sidebar">
    <div class="profile_info">
      <img src="../../images/user-icon.png" class="profile_image" alt="">
      <h4><?php echo ucfirst($_SESSION['nom']) . " " . ucfirst($_SESSION['prenom']) ; ?></h4>
    </div>
    <a href="notifications.php"><i class="fa-solid fa-bell"></i><span>Notifications</span></a>
    <a href="#" class="active"><i class="fas fa-sliders-h"></i><span>Settings</span></a>
    <a href="javascript:goBack()" class="back"><i class="fa-solid fa-arrow-left"></i><span>Retour</span></a>
    <a href="../../index.php?logout=true" ><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>

    <?php
      $role = $_SESSION['role'];
    ?>

    <script>
    function goBack() {
      switch (<?php echo $role; ?>) {
        case 1:
          window.location.href = '../admin/dashboard_admin.php';
          break;
        case 2:
          window.location.href = '../commercial/tickets_commercial.php';
          break;
        case 3:
          window.location.href = '../comptable/dashboard_comptable.php';
          break;
        default:
          window.location.href = '../../index.php';
          break;
      }
    }
    </script>
  </div>
  
  <script type="text/javascript" src="../../index.js"></script>
</body>
</html>