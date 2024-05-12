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
            <h3>Tickets en attente</h3>
          </div>
          <table id="pending">
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
                <th id="status" class="center-content">Status</th>
                <th class="center-content">Validation</th>
              </tr>
            </thead>
            <tbody>
              <?php
              require_once '../../vendor/autoload.php'; 

              use MicrosoftAzure\Storage\Blob\BlobRestProxy;
              use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
              use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
              use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

              $connectionString = "DefaultEndpointsProtocol=https;AccountName=e11event;AccountKey=OVp/sacfyyfrlCyj0SEAl/k8jS6r5G+wQ86UeD5oR6W9i2d395JqqmUEi7ZwVrDU6BYkqh5t6OPW+ASttYtsEg==;EndpointSuffix=core.windows.net";
              $blobClient = BlobRestProxy::createBlobService($connectionString);
              $containerName = "justificatifs"; 
              $justificatifs = "justificatifs";
              $accountName = "e11event";
              
                $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id']) && isset($_POST['status'])) {
                  $ticket_id = $_POST['ticket_id'];
                  $statusMap = [
                      'status1' => 1,
                      'status2' => 2,
                      'status3' => 3,
                  ];
              
                  if (array_key_exists($_POST['status'], $statusMap)) {
                      $status = $statusMap[$_POST['status']];
              
                      $stmt = $db->prepare("UPDATE ticket SET status = ? WHERE id_ticket = ?");
                      $stmt->execute([$status, $ticket_id]);
              
                      $_SESSION['message'] = "Statut du ticket mis à jour avec succès.";
                  }
              
                  echo "<script>setTimeout(function(){ window.location.href = window.location.href; }, 250);</script>";
                  exit;
              }
            if (isset($_POST['ticket_id'])) {
              $ticket_id = $_POST['ticket_id'];
            }

            if (isset($_POST['status'])) {
              $status = $_POST['status'];
            }

                $pending_tickets = $db->prepare("
                SELECT t.*, t.nom, t.mail, tc.nom_categorie AS categorie, ts.nom_status AS status
                FROM ticket AS t
                INNER JOIN ticket_categorie AS tc ON t.categorie = tc.id_category
                INNER JOIN ticket_status AS ts ON t.status = ts.id_status
                WHERE ts.nom_status = 'En attente'
            ");
            $pending_tickets->execute();
            $pending_data = $pending_tickets->fetchAll();

            foreach ($pending_data as $row) {
              $justificatifIcon = '';
              if (!empty($row['justificatif'])) {
                $justificatifIcon = "<a href='https://$accountName.blob.core.windows.net/$justificatifs/".$row['justificatif']."' target='_blank'><i class='fa-solid fa-arrow-up-right-from-square no-link-style'></i></a>";
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
                'status3' => 3,
            ];
            
            if (isset($_POST['status']) && array_key_exists($_POST['status'], $statusMap)) {
              $status = $statusMap[$_POST['status']];
              $stmt = $db->prepare("UPDATE ticket SET status = ? WHERE id_ticket = ?");
              $stmt->execute([$status, $_POST['ticket_id']]);
          }

            $status = null;

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
                      <td class='center-content'>
                      <input type='hidden' name='ticket_id' value='".$row['id_ticket']."'/>
                      <input class='bouton-ticket-status' type='submit' value='Valider'/>
                      </td>
                  </form>
                          </tr>";
            }

              $ticket_id = null;

              if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                  $update = $db->prepare("UPDATE ticket SET status = ? WHERE id_ticket = ?");
                  $update->execute([$status, $ticket_id]);  
              }
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
                  <th class="center-content">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $connectionString = "DefaultEndpointsProtocol=https;AccountName=e11event;AccountKey=OVp/sacfyyfrlCyj0SEAl/k8jS6r5G+wQ86UeD5oR6W9i2d395JqqmUEi7ZwVrDU6BYkqh5t6OPW+ASttYtsEg==;EndpointSuffix=core.windows.net";
                $blobClient = BlobRestProxy::createBlobService($connectionString);
                $containerName = "justificatifs"; 
                $justificatifs = "justificatifs";
                $accountName = "e11event";

                  $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');

                        $other_tickets = $db->prepare("
                              SELECT t.*, t.nom, t.mail, tc.nom_categorie AS categorie, ts.nom_status AS status
                              FROM ticket AS t
                              INNER JOIN ticket_categorie AS tc ON t.categorie = tc.id_category
                              INNER JOIN ticket_status AS ts ON t.status = ts.id_status
                              WHERE ts.nom_status != 'En attente'
                          ");
                    
                      $other_tickets->execute();
                      $other_data = $other_tickets->fetchAll();
                  
                    foreach ($other_data as $row) {
                      $justificatifIcon = '';
                      if (!empty($row['justificatif'])) {
                        $justificatifIcon = "<a href='https://$accountName.blob.core.windows.net/$justificatifs/".$row['justificatif']."' target='_blank'><i class='fa-solid fa-arrow-up-right-from-square no-link-style'></i></a>";
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
