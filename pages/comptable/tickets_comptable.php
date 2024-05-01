<?php
session_start();

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 3 && $_SESSION['role'] != 1)) {
  header('Location: ../../index.php');
  session_destroy();
  exit();
}

    if(isset($_GET['logout'])) {
      session_destroy();
      header('Location: ../../index.php');
      exit();
     }

try {
  $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');
  // Set the PDO error mode to exception
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tickets - Comptable</title>
  <link rel="icon" href="../../images/Logo_onglet.png" type="image/x-icon">
  <link rel="stylesheet" href="../../styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
</head>
<body>

  <input type="checkbox" id="check">
  <header>
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
      <a href="dashboard_comptable.php"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
      <a href="#" class="active"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
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
    <a href="dashboard_comptable.php"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
    <a href="#" class="active"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
    <a href="../autres/notifications.php"><i class="fa-solid fa-bell"></i><span>Notifications</span></a>
    <a href="../autres/settings.php"><i class="fas fa-sliders-h"></i><span>Paramètres</span></a>
    <a href="../../index.php?logout=true" class="logout-comptable" ><i class="fa-solid fa-right-from-bracket"></i><span>Déconnexion</span></a>
  </div>

  <div class="content">
    <main>
      <div class="header">
        <h1>Gestion de tickets</h1>
      </div>
      <div class="bottom_data">
        <div class="orders">
          <div class="header">
            <h3>Recent Orders</h3>
          </div>
          <table id="pending">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Date</th>
                <th>Lieu</th>
                <th>Catégorie</th>
                <th>Prix</th>
                <th>Description</th>
                <th>Justificatif</th>
                <th id="status">Status</th>
                <th>Validation</th>
              </tr>
            </thead>
            <tbody>
              
              <?php
              // Initialiser la connexion à la base de données
$db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id']) && isset($_POST['status'])) {
    $ticket_id = $_POST['ticket_id'];
    $statusMap = [
        'status1' => 1,
        'status2' => 2,
        // etc.
    ];

    if (array_key_exists($_POST['status'], $statusMap)) {
        $status = $statusMap[$_POST['status']];

        // Préparer la requête SQL pour mettre à jour le statut du ticket
        $stmt = $db->prepare("UPDATE ticket SET status = ? WHERE id_ticket = ?");
        $stmt->execute([$status, $ticket_id]);

        // Optionnel: Ajouter un message de confirmation
        $_SESSION['message'] = "Statut du ticket mis à jour avec succès.";
    }

    // Rediriger pour éviter le rechargement du formulaire
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

              if (isset($_SESSION['nom']) && isset($_SESSION['prenom'])) {
                $nom = $_SESSION['nom'];
                $prenom = $_SESSION['prenom'];
              }

              // update_ticket_status.php
            if (isset($_POST['ticket_id'])) {
              $ticket_id = $_POST['ticket_id'];
            } else {
            }

            if (isset($_POST['status'])) {
              $status = $_POST['status'];
            } else {
                // Handle the case where 'status' is not set
            }
                // Vérifie si l'ID du ticket est défini et s'il est numérique
                if(isset($_GET['id']) && is_numeric($_GET['id'])) {
                  $id_ticket_to_delete = $_GET['id'];
                  
                  // Connexion à la base de données
                  $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');
                  
                  // Supprimer le ticket de la base de données
                  $stmt_delete = $db->prepare("DELETE FROM ticket WHERE id_ticket = :id_ticket");
                  $stmt_delete->bindParam(':id_ticket', $id_ticket_to_delete);
                  $stmt_delete->execute();
                  
                  // Renvoyer une réponse pour indiquer que la suppression a réussi
                  echo "Ticket supprimé avec succès";
                }

                // Récupère le nom de l'utilisateur à partir de la base de données
                $stmt_nom = $db->prepare("SELECT nom FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                $stmt_nom->bindParam(':nom', $nom);
                $stmt_nom->bindParam(':prenom', $prenom);
                $stmt_nom->execute();
                $nom = $stmt_nom->fetch()['nom'];

                // Récupère l'adresse mail de l'utilisateur à partir de la base de données
                $stmt_mail = $db->prepare("SELECT mail FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                $stmt_mail->bindParam(':nom', $nom);
                $stmt_mail->bindParam(':prenom', $prenom);
                $stmt_mail->execute();
                $mail = $stmt_mail->fetch()['mail'];

                // Récupère l'ID de l'utilisateur à partir de la base de données
                $stmt_user = $db->prepare("SELECT id_utilisateur FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                $stmt_user->bindParam(':nom', $nom);
                $stmt_user->bindParam(':prenom', $prenom);
                $stmt_user->execute();
                $id_utilisateur = $stmt_user->fetch()['id_utilisateur'];

                $pending_tickets = $db->prepare("
                SELECT t.*, u.nom, u.mail, tc.nom_categorie AS categorie, ts.nom_status AS status
                FROM ticket AS t
                INNER JOIN utilisateur AS u ON t.utilisateur = u.id_utilisateur
                INNER JOIN ticket_categorie AS tc ON t.categorie = tc.id_category
                INNER JOIN ticket_status AS ts ON t.status = ts.id_status
                WHERE ts.nom_status = 'En attente'
            ");
            $pending_tickets->execute();
            $pending_data = $pending_tickets->fetchAll();

            foreach ($pending_data as $row) {
              $justificatifIcon = '';
              if (!empty($row['justificatif'])) {
                $justificatifIcon = "<a href='../../images/justificatifs/".$row['justificatif']."' target='_blank'><i class='fa-solid fa-arrow-up-right-from-square no-link-style'></i></a>";
              }

              $statusClass = '';
              if ($row['status'] == 'Refusé') {
                $statusClass = 'status processing';
              } elseif ($row['status'] == 'Accepté') {
                $statusClass = 'status completed';
              }

            $statusMap = [
                'status1' => 1,
                'status2' => 2,
                // etc.
            ];
            
            if (isset($_POST['status']) && array_key_exists($_POST['status'], $statusMap)) {
              $status = $statusMap[$_POST['status']];
              
              // Préparez la requête SQL
              $stmt = $db->prepare("UPDATE ticket SET status = ? WHERE id_ticket = ?");
              
              // Exécutez la requête avec les paramètres appropriés
              $stmt->execute([$status, $_POST['ticket_id']]);
          } else {
              // code à exécuter si le formulaire n'a pas été soumis ou si le statut soumis n'est pas valide
          }

            $status = null; // Initialisez la variable avant le bloc if

              echo "<tr>
                      <td>".$row['id_ticket']."</td>
                      <td>".$row['nom']."</td>
                      <td>".$row['mail']."</td>
                      <td>".$row['date']."</td>
                      <td>".$row['lieu']."</td>
                      <td>".$row['categorie']."</td>
                      <td>".$row['prix']."</td>
                      <td>".$row['description']."</td>
                      <td>".$row['justificatif']." ".$justificatifIcon."</td>
                      <td>
                      <form action='tickets_comptable.php' method='post'>
                      <select name='status'>
                          <option value='status1'>Accepter</option>
                          <option value='status2'>Refuser</option>
                      </select>
                      </td>
                      <td>
                      <input type='hidden' name='ticket_id' value='".$row['id_ticket']."'/>
                      <input class='bouton-ticket-status' type='submit' value='Valider'/>
                      </td>
                  </form>
                          </tr>";
            }

                              // Connect to the database
              $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');

              $ticket_id = null; // Initialisez la variable avant de l'utiliser

              // Check if form was submitted
              if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                  // Update the ticket status
                  $update = $db->prepare("UPDATE ticket SET status = ? WHERE id_ticket = ?");
                  $update->execute([$status, $ticket_id]);  
              }

            // Connexion à la base de données
            $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');
                  
            // Récupérer le nom du fichier justificatif avant de supprimer le ticket
            $stmt = $db->prepare("SELECT justificatif FROM ticket WHERE id_ticket = :id_ticket");
            $stmt->bindParam(':id_ticket', $id_ticket_to_delete);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $justificatif_filename = $row ? $row['justificatif'] : null;

              // Close the connection
              $db = null;
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>

  <div class="content">
      <main>
        <div class="bottom_data">
          <div class="orders">
            <div class="header">
              <h3>Historique des tickets</h3>
            </div>
            <table id="other">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nom</th>
                  <th>Email</th>
                  <th>Date</th>
                  <th>Lieu</th>
                  <th>Catégorie</th>
                  <th>Prix</th>
                  <th>Description</th>
                  <th>Justificatif</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php

                  $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');

                  if (isset($_SESSION['nom']) && isset($_SESSION['prenom'])) {
                    $nom = $_SESSION['nom'];
                    $prenom = $_SESSION['prenom'];
    
                    // Récupère le nom de l'utilisateur à partir de la base de données
                    $stmt_nom = $db->prepare("SELECT nom FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                    $stmt_nom->bindParam(':nom', $nom);
                    $stmt_nom->bindParam(':prenom', $prenom);
                    $stmt_nom->execute();
                    $nom = $stmt_nom->fetch()['nom'];
    
                    // Récupère l'adresse mail de l'utilisateur à partir de la base de données
                    $stmt_mail = $db->prepare("SELECT mail FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                    $stmt_mail->bindParam(':nom', $nom);
                    $stmt_mail->bindParam(':prenom', $prenom);
                    $stmt_mail->execute();
                    $mail = $stmt_mail->fetch()['mail'];
    
                    // Récupère l'ID de l'utilisateur à partir de la base de données
                    $stmt_user = $db->prepare("SELECT id_utilisateur FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                    $stmt_user->bindParam(':nom', $nom);
                    $stmt_user->bindParam(':prenom', $prenom);
                    $stmt_user->execute();
                    $id_utilisateur = $stmt_user->fetch()['id_utilisateur'];

                    // Récupère le rôle de l'utilisateur à partir de la base de données
                    $stmt_user = $db->prepare("SELECT role FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                    $stmt_user->bindParam(':nom', $nom);
                    $stmt_user->bindParam(':prenom', $prenom);
                    $stmt_user->execute();
 
                    $role = $stmt_user->fetch()['role']; 

                        $other_tickets = $db->prepare("
                              SELECT t.*, u.nom, u.mail, tc.nom_categorie AS categorie, ts.nom_status AS status
                              FROM ticket AS t
                              INNER JOIN utilisateur AS u ON t.utilisateur = u.id_utilisateur
                              INNER JOIN ticket_categorie AS tc ON t.categorie = tc.id_category
                              INNER JOIN ticket_status AS ts ON t.status = ts.id_status
                              WHERE ts.nom_status != 'En attente'
                          ");
                    } else {
                        $other_tickets = $db->prepare("
                              SELECT t.*, u.nom, u.mail, tc.nom_categorie AS categorie, ts.nom_status AS status
                              FROM ticket AS t
                              INNER JOIN utilisateur AS u ON t.utilisateur = u.id_utilisateur
                              INNER JOIN ticket_categorie AS tc ON t.categorie = tc.id_category
                              INNER JOIN ticket_status AS ts ON t.status = ts.id_status
                              WHERE t.utilisateur = :id_utilisateur AND ts.nom_status != 'En attente'
                          ");
                        $other_tickets->bindParam(':id_utilisateur', $id_utilisateur);
                    }
                      $other_tickets->execute();
                      $other_data = $other_tickets->fetchAll();
                  
                    foreach ($other_data as $row) {
                      $justificatifIcon = '';
                      if (!empty($row['justificatif'])) {
                        $justificatifIcon = "<a href='../../images/justificatifs/".$row['justificatif']."' target='_blank'><i class='fa-solid fa-arrow-up-right-from-square no-link-style'></i></a>";
                      }
                    
                      $statusClass = '';
                      if ($row['status'] == 'Refusé') {
                        $statusClass = 'status processing';
                      } elseif ($row['status'] == 'Accepté') {
                        $statusClass = 'status completed';
                      }
                    
                    
                      echo "<tr>
                              <td>".$row['id_ticket']."</td>
                              <td>".$row['nom']."</td>
                              <td>".$row['mail']."</td>
                              <td>".$row['date']."</td>
                              <td>".$row['lieu']."</td>
                              <td>".$row['categorie']."</td>
                              <td>".$row['prix']."</td>
                              <td>".$row['description']."</td>
                              <td>".$row['justificatif']." ".$justificatifIcon."</td>
                              <td class='center-content'><span class='status completed processing".$statusClass."'>".$row['status']."</span></td>
                            </tr>";
                    }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script>
  $(document).ready(function () {
      $('#pending, #other').DataTable({
          "language": {
              "url": "../../Json/French.json"
          },
          "order": [[0, "desc"]]
      });
    });
    
    var mobileProfileImage = document.querySelector('.mobile_profile_image');
    var profileImage = document.querySelector('.profile_image');

    var selectedAvatar = localStorage.getItem('selectedAvatar');
    if (selectedAvatar) {
        mobileProfileImage.src = selectedAvatar;
        profileImage.src = selectedAvatar;
    }
  </script>
  <script type="text/javascript" src="../../index.js"></script>
</body>
</html>
