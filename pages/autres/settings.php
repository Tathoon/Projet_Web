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
  <div class="content">
    <main>
    <div class="avatar_section">
      <h2>Changez votre avatar</h2>
      <div class="avatar_options">
        <label for="avatar1">
          <img src="../../images/avatar/avatar1.png" alt="Avatar 1">
          <input type="radio" id="avatar1" name="avatar" value="avatar1.jpg">
        </label>
        <label for="avatar2">
          <img src="../../images/avatar/avatar2.png" alt="Avatar 2">
          <input type="radio" id="avatar2" name="avatar" value="avatar2.jpg">
        </label>
        <label for="avatar3">
          <img src="../../images/avatar/avatar3.png" alt="Avatar 3">
          <input type="radio" id="avatar3" name="avatar" value="avatar3.jpg">
        </label>
        <label for="avatar4">
          <img src="../../images/avatar/avatar4.png" alt="Avatar 4">
          <input type="radio" id="avatar4" name="avatar" value="avatar4.jpg">
        </label>
        <label for="avatar5">
          <img src="../../images/avatar/avatar5.png" alt="Avatar 5">
          <input type="radio" id="avatar5" name="avatar" value="avatar5.jpg">
        </label>
      </div>
    </div>
  </main>

    <script>
      // Sélectionnez les boutons radio et les images
      var radios = document.querySelectorAll('input[type=radio][name="avatar"]');
      var mobileProfileImage = document.querySelector('.mobile_profile_image');
      var profileImage = document.querySelector('.profile_image');

      // Récupérez l'avatar sélectionné du stockage local, s'il existe
      var selectedAvatar = localStorage.getItem('selectedAvatar');
      if (selectedAvatar) {
          mobileProfileImage.src = selectedAvatar;
          profileImage.src = selectedAvatar;
      }

      // Ajoutez un écouteur d'événements à chaque bouton radio
      radios.forEach(function(radio) {
          radio.addEventListener('change', function() {
              // Modifiez les attributs src des images lorsque le bouton radio est sélectionné
              var avatarSrc;
              if (this.id === 'avatar1') {
                  avatarSrc = '../../images/Logo-Web.png';
              } else if (this.id === 'avatar2') {
                  avatarSrc = '../../images/Logo_onglet.png';
              }
              mobileProfileImage.src = avatarSrc;
              profileImage.src = avatarSrc;

              // Enregistrez l'avatar sélectionné dans le stockage local
              localStorage.setItem('selectedAvatar', avatarSrc);
          });
      });
  </script>
  <script type="text/javascript" src="../../index.js"></script>
</body>
</html>