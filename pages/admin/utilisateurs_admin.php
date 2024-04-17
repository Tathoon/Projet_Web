<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Utilisateurs - Admin</title>
  <link rel="stylesheet" href="../../styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
</head>
<body>
  
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
      <img src="../../images/Logo-Web.png" class="mobile_profile_image" alt="">
      <i class="fa fa-bars nav_btn"></i>
    </div>
    <div class="mobile_nav_items">
      <a href="dashboard_admin.php"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
      <a href="tickets_admin.php"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
      <a href="#" class="active"><i class="fas fa-table"></i><span>Utilisateurs</span></a>
      <a href="../autres/notifications.php"><i class="fas fa-info-circle"></i><span>Notifications</span></a>
      <a href="../autres/settings.php"><i class="fas fa-sliders-h"></i><span>Settings</span></a>
      <a href="../../index.php?logout=true" ><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
    </div>
  </div>

  <div class="sidebar">
    <div class="profile_info">
      <img src="../../images/user-icon.png" class="profile_image" alt="">
      <h4><?php echo ucfirst($_SESSION['nom']); ?></h4>
    </div>
    <a href="dashboard_admin.php"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
    <a href="tickets_admin.php"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
    <a href="#" class="active"><i class="fas fa-table"></i><span>Utilisateurs</span></a>
    <a href="../autres/notifications.php"><i class="fas fa-info-circle"></i><span>Notifications</span></a>
    <a href="../autres/settings.php"><i class="fas fa-sliders-h"></i><span>Settings</span></a>
    <a href="../../index.php?logout=true" class="logout" ><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
  </div>

  <form action="utilisateurs_admin.php" method="post">

    <div class="mb-3">
      <label for="nom" class="">Nom</label>
      <input type="text" name="nom" class="" id="nom">
    </div>
  
    <div class="mb-3">
      <label for="prenom" class="">Prénom</label>
      <input name="prenom" type="text" class="" id="prenom">
    </div>

    <div class="mb-3">
      <label for="mail" class="">Email</label>
      <input name="mail" type="text" class="" id="mail" aria-describedby="emailHelp">
    </div>

    <div class="mb-3">
      <label for="mdp" class="">Mot de passe</label>
      <input name="mdp" type="password" class="" id="mdp">
    </div>

    <div class="mb-3">
    <label for="role" class="">Role</label>
    <select name="role" class="" id="role">
     <?php
        $db = new PDO('mysql:host=localhost;dbname=e11event_bdd;charset=utf8mb4', 'root', '');
        $role = $db->query("SELECT * FROM role")->fetchAll();
        foreach ($role as $row) {
          echo "<option value=".$row['id_role'].">".$row['nom_role']."</option>";
        }
     ?>
    </select>
    
    <div style="margin-bottom: 30px;"></div>

    <input type="submit" class="" value="Enregistrer">

    <div style="margin-bottom: 30px;"></div>

    <?php

      if (isset($_POST['mail']) && isset($_POST['mdp']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['role'])) {
        $mail = $_POST['mail'];
        $mdp = $_POST['mdp'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $role = $_POST['role'];

      $db = new PDO('mysql:host=localhost;dbname=e11event_bdd;charset=utf8mb4', 'root', '');

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

        <?php

          $db = new PDO('mysql:host=localhost;dbname=e11event_bdd;charset=utf8mb4', 'root', ''); 

          $data = $db->query("SELECT * FROM utilisateur")->fetchAll();

          $data = $db->query("SELECT utilisateur.*, role.nom_role FROM utilisateur INNER JOIN role ON utilisateur.role = role.id_role ORDER BY utilisateur.id_utilisateur ASC")->fetchAll();

          foreach ($data as $row) {
            echo "<tr id='row-".$row['id_utilisateur']."'><td>".$row['id_utilisateur']."</td><td>".$row['mail']."</td><td>".$row['mdp']."</td><td>".$row['nom']."</td><td>".$row['prenom']."</td><td>".$row['nom_role']."</td><td><input class='delete-button' type='button' value='Supprimer' data-id='".$row['id_utilisateur']."'></td></tr>";
        }
        
        if (isset($_GET['id_utilisateur'])) {  //supprime un utilisateur
            $id = $_GET['id_utilisateur'];
            $stmt = $db->prepare("DELETE FROM utilisateur WHERE id_utilisateur = :id_utilisateur");
            $stmt->bindParam(':id_utilisateur', $id);
            $stmt->execute();
        }
        
        // permet de supprimer un utilisateur sans recharger la page (l'utilisateur est supprimé de la base de données et du tableau sans avoir à la recharger)
        echo '<script type="text/javascript"> 
            var deleteButtons = document.getElementsByClassName("delete-button");
            for (var i = 0; i < deleteButtons.length; i++) {
                deleteButtons[i].addEventListener("click", function() {
                    var id = this.getAttribute("data-id");
                    fetch("utilisateurs_admin.php?id_utilisateur=" + id, {
                        method: "GET"
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("HTTP error " + response.status);
                        }
                        document.getElementById("row-" + id).remove();
                    })
                    .catch(error => {
                        console.log(error);
                    });
                });
            }
        </script>';

        ?>

      </tbody>
    </table>

  </div>
  </div>
<script type="text/javascript" src="../../index.js"></script>
</body>
</html>