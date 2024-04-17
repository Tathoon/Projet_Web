<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Utilisateurs - Admin</title>
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>

<?php
    session_start();

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