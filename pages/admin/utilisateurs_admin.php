<?php
    session_start();

    if (!isset($_SESSION['role']) || ($_SESSION['role'] != 1 )) {
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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Utilisateurs - Admin</title>
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

  <label class="switch" for="dark-mode-toggle">
      <input type="checkbox" id="dark-mode-toggle">
      <span class="slider round">
        <i class="far fa-sun sun-icon darkmodetitleSUN"></i>
        <i class="far fa-moon moon-icon darkmodetitleMOON"></i>
      </span>
    </label>

  <div class="mobile_nav">
    <div class="nav_bar">
      <img src="../../images/user-icon.png" class="mobile_profile_image" alt="">
      <h4 class="user-mobile"><?php echo ucfirst($_SESSION['nom']) . " " . ucfirst($_SESSION['prenom']) ; ?></h4>
      <i class="fa fa-bars nav_btn"></i>
    </div>
    <div class="mobile_nav_items">
      <a href="dashboard_admin.php"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
      <a href="tickets_admin.php"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
      <a href="#" class="active"><i class="fas fa-table"></i><span>Utilisateurs</span></a>
      <a href="../autres/notifications.php"><i class="fa-solid fa-bell"></i><span>Notifications</span></a>
      <a href="../autres/settings.php"><i class="fas fa-sliders-h"></i><span>Paramètres</span></a>
      <a href="../../index.php?logout=true" ><i class="fa-solid fa-right-from-bracket"></i><span>Déconnexion</span></a>
    </div>
  </div>

  <div class="sidebar">
    <div class="profile_info">
      <img src="../../images/user-icon.png" class="profile_image" alt="">
      <h4><?php echo ucfirst($_SESSION['nom']) . " " . ucfirst($_SESSION['prenom']) ; ?></h4>
    </div>
    <a href="dashboard_admin.php"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
    <a href="tickets_admin.php"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
    <a href="#" class="active"><i class="fas fa-table"></i><span>Utilisateurs</span></a>
    <a href="../autres/notifications.php"><i class="fa-solid fa-bell"></i><span>Notifications</span></a>
    <a href="../autres/settings.php"><i class="fas fa-sliders-h"></i><span>Paramètres</span></a>
    <a href="../../index.php?logout=true" class="logout" ><i class="fa-solid fa-right-from-bracket"></i><span>Déconnexion</span></a>
  </div>

   <div class="title">
      <h1>Gestion d'utilisateurs</h1>
   </div>

  <div class="box-general">

  <form action="utilisateurs_admin.php" method="post" class="form-user form-card form-container">

    <div>
      <label for="nom" class="">Nom</label>
      <input type="text" name="nom" class="" id="nom" required>
    </div>
  
    <div>
      <label for="prenom" class="">Prénom</label>
      <input name="prenom" type="text" class="" id="prenom" required>
    </div>

    <div>
      <label for="mail" class="">Email</label>
      <input name="mail" type="email" class="" id="mail" aria-describedby="emailHelp" required>
    </div>

    <div>
      <label for="mdp" class="">Mot de passe</label>
      <input name="mdp" type="password" class="" id="mdp" required>
    </div>

    <div>
    <label for="role" class="">Role</label>
    <select name="role" class="" id="role">
     <?php
        $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');
        $role = $db->query("SELECT * FROM role")->fetchAll();
        foreach ($role as $row) {
          echo "<option value=".$row['id_role'].">".$row['nom_role']."</option>";
        }
     ?>
    </select>
    </div>
    
    
    <div style="margin-bottom: 10px;"></div>

    <input type="submit" class="" value="Enregistrer">

    <div style="margin-bottom: 5px;"></div>

    </form>

    <?php

      if (isset($_POST['mail']) && isset($_POST['mdp']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['role'])) {
        $mail = $_POST['mail'];
        $mdp = $_POST['mdp'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $role = $_POST['role'];

      $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');

      $stmt = $db->prepare("INSERT INTO utilisateur ( mail, mdp, nom, prenom, role) VALUES (:mail, :mdp, :nom, :prenom, :role)");
      $stmt->bindParam(':mail', $mail);
      $stmt->bindParam(':mdp', $mdp);
      $stmt->bindParam(':prenom', $prenom);
      $stmt->bindParam(':nom', $nom);
      $stmt->bindParam(':role', $role);

      $stmt->execute();

      echo '<div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
      <strong> L\'utilisateur a bien été ajouté</strong>
      </div>';
      echo '<script type="text/javascript">
            setTimeout(function() {
                var element = document.getElementById("success-alert");
                element.parentNode.removeChild(element);
            }, 3000);
          </script>';
    }
    
    ?>
    <div class=" table-container">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">id</th>
          <th scope="col">Mail</th>
          <th scope="col">Mot de Passe</th>
          <th scope="col">Nom</th>
          <th scope="col">Prénom</th>
          <th scope="col">Role</th>
          <th scope="col">Supprimer</th>
        </tr>
      </thead>
      <tbody>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var deleteButtons = document.querySelectorAll('.delete-button');
                deleteButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        var id = this.getAttribute('data-id');
                        window.location.href = '?id_utilisateur=' + id;
                    });
                });
            });
        </script>

        <?php

          $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!'); 

          $data = $db->query("SELECT utilisateur.*, role.nom_role FROM utilisateur INNER JOIN role ON utilisateur.role = role.id_role ORDER BY utilisateur.id_utilisateur ASC")->fetchAll();

          foreach ($data as $row) {
            echo "<tr id='row-".$row['id_utilisateur']."'><td>".$row['id_utilisateur']."</td><td>".$row['mail']."</td><td>".$row['mdp']."</td><td>".$row['nom']."</td><td>".$row['prenom']."</td><td>".$row['nom_role']."</td><td><input class='delete-button' type='button' value='Supprimer' data-id='".$row['id_utilisateur']."'></td></tr>";
        }
        
        if (isset($_GET['id_utilisateur'])) {  //supprime un utilisateur
          $id = $_GET['id_utilisateur'];
          try {
              $db->beginTransaction();
      
              $stmt = $db->prepare("DELETE FROM utilisateur WHERE id_utilisateur = :id_utilisateur");
              $stmt->bindParam(':id_utilisateur', $id);
              $stmt->execute();
      
              $db->commit();
      
              // Si la requête est une requête AJAX, renvoyer une réponse HTTP appropriée
              if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                  echo "Utilisateur supprimé";
                  exit;
              }
          } catch (Exception $e) {
              $db->rollBack();
              echo "Erreur : " . $e->getMessage();
          }
      }
        
        // permet de supprimer un utilisateur sans recharger la page (l'utilisateur est supprimé de la base de données et du tableau sans avoir à la recharger)
        echo "<script type='text/javascript'> 
        var deleteButtons = document.querySelectorAll('.delete-button');
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                fetch('?id_utilisateur=' + id, {
                    method: 'GET'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('HTTP error ' + response.status);
                    }
                    document.getElementById('row-' + id).remove();
                })
                .catch(error => {
                    console.log(error);
                });
            });
        });
        </script>";
        ?>

      </tbody>
    </table>
  </div></div>

<script>
  
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