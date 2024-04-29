<?php
    session_start();
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

     $role = $_SESSION['role'];
?>

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
      <a href="#" class="active"><i class="fas fa-sliders-h"></i><span>Paramètres</span></a>
      <a href="javascript:history.go(-1)"><i class="fa-solid fa-arrow-left"></i></i><span>Retour</span></a>
      <a href="../../index.php?logout=true" ><i class="fa-solid fa-right-from-bracket"></i><span>Déconnexion</span></a>
    </div>
  </div>

  <div class="sidebar">
    <div class="profile_info">
      <img src="../../images/user-icon.png" class="profile_image" alt="">
      <h4><?php echo ucfirst($_SESSION['nom']) . " " . ucfirst($_SESSION['prenom']) ; ?></h4>
    </div>
    <a href="notifications.php"><i class="fa-solid fa-bell"></i><span>Notifications</span></a>
    <a href="#" class="active"><i class="fas fa-sliders-h"></i><span>Paramètres</span></a>
    <a href="javascript:goBack()" class="back"><i class="fa-solid fa-arrow-left"></i><span>Retour</span></a>
    <a href="../../index.php?logout=true" ><i class="fa-solid fa-right-from-bracket"></i><span>Déconnexion</span></a>

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
        <div class="header">
          <h1>Changez votre avatar</h1>
        </div>
        <div class="avatar_options" id="avatarContainer">
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

          <button id="showMoreAvatars">Surprise !</button>

          <div id="hiddenAvatars" class="avatar_options" style="display: none;">
            <label for="avatar6">
              <img src="../../images/avatar/dancing-toothless-tothless.gif" alt="Avatar 6">
              <input type="radio" id="avatar6" name="avatar" value="avatar6.jpg">
            </label>
            <label for="avatar7">
              <img src="../../images/avatar/Donald-Duck.gif" alt="Avatar 7">
              <input type="radio" id="avatar7" name="avatar" value="avatar7.jpg">
            </label>
            <label for="avatar8">
              <img src="../../images/avatar/Pedro.gif" alt="Avatar 8">
              <input type="radio" id="avatar8" name="avatar" value="avatar8.jpg">
            </label>
            <label for="avatar9">
              <img src="../../images/avatar/PowerRanger.gif" alt="Avatar 9">
              <input type="radio" id="avatar9" name="avatar" value="avatar9.jpg">
            </label>
            <label for="avatar10">
              <img src="../../images/avatar/angry-cat.gif" alt="Avatar 10">
              <input type="radio" id="avatar10" name="avatar" value="avatar10.jpg">
            </label>
            <label for="avatar11">
              <img src="../../images/avatar/quokka.gif" alt="Avatar 11">
              <input type="radio" id="avatar11" name="avatar" value="avatar11.jpg">
            </label>
          </div>
        </div>
      </div>
      <label class="switch" for="dark-mode-toggle">
        <input type="checkbox" id="dark-mode-toggle">
        <span class="slider round"></span>
      </label>
      <a href="#" id="dark-mode-toggle"><i class="far fa-moon"></i><span>Nightmode</span></a>
    </main>
  </div>


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

  </script>
  <script type="text/javascript" src="../../index.js"></script>
</body>
</html>