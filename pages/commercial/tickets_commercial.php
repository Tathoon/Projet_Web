<?php
    session_start();

    if (!isset($_SESSION['role']) || ($_SESSION['role'] != 1 && $_SESSION['role'] != 2)) {
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
  <title>Tickets - Commercial</title>
  <link rel="icon" href="../../images/Logo_onglet.png" type="image/x-icon">
  <link rel="stylesheet" href="../../styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
</head>
<body>

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
    <a href="#" class="active"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
    <a href="../autres/settings.php"><i class="fas fa-sliders-h"></i><span>Paramètres</span></a>
    <a href="../../index.php?logout=true" class="logout-commercial" ><i class="fa-solid fa-right-from-bracket"></i><span>Déconnexion</span></a>
  </div>

  <h1 class="title">Vos notes de frais</h1>

  <div class="ticket-container">
    <div class="card-ticket">
      <h1 class="pending-ticket-title">Formulaire de dépôt de note de frais</h1>
      <form action="tickets_commercial.php" method="post" enctype="multipart/form-data" class="form-user ticket-card form-container">

        <div class="text-obligatoire">
          <p>Les champs marqués d'un * sont obligatoires.</p>
        </div>

        <div class="space form-control">
          <label for="categorie">Type de frais<span style="color: red;">*</span> <span> :</span></label>
          <div>
            <select name="categorie" id="categorie" required>
                <option value="" style="color: gray;">Renseignez le type de frais</option>
              <?php 
              $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');
              $ticket_categorie = $db->query("SELECT * FROM ticket_categorie")->fetchAll();
              foreach ($ticket_categorie as $row) {
                echo "<option value=".$row['id_category'].">".$row['nom_categorie']."</option>";
              }
              ?>
            </select>
          </div>
        </div>

        <div class="space form-control">
          <label for="cout">Coût du frais<span style="color: red;">*</span> <span> :</span></label>
          <input name="cout" type="text" id="cout" required>
        </div>

        <div class="space form-control">
          <label for="description">Description du frais<span style="color: red;">*</span> <span> :</span></label>
          <textarea name="description" type="text" id="description" required></textarea>
        </div>

        <div class="space form-control">
          <label for="lieu">Lieu du frais<span style="color: red;">*</span> <span> :</span></label>
          <input name="lieu" type="text" id="lieu" required>
        </div>

        <div>
          <div class="space">
            <label for="justificatif">Justificatif : <span> (Optionnel)</span></label>
            <div id="file-info" style="display: flex; align-items: center;">
              <div id="file-name" style="flex: 0;"></div>
              <i class='fa-solid fa-trash' id="delete-justificatif" style="display: none;"></i>
            </div>
          </div>
          <div class="space">
            <div class="button-row">
              <label class="file-label text-ticket" for="justificatif">Ajouter un fichier</label>
              <input type="file" name="justificatif" id="justificatif">
            </div>
          </div>
          <div class="space">
            <button type="submit" class="btn-ticket">Ajouter la note de frais</button>
          </div>
        </div>

        <script>
           $('#justificatif').on('change', function(e){
                var fileName = '';

                if (e.target.files.length > 0) {
                    fileName = e.target.files[0].name;
                    $('#file-name').text(fileName);
                }
                
                if(fileName !== ''){
                    $('#delete-justificatif').show();
                } else {
                    $('#delete-justificatif').hide();
                }
            });

            $('#delete-justificatif').on('click', function(){
                $('#justificatif').val('');
                $('#file-name').text('');
                $(this).hide();
            });
        </script>
              
        <?php
        require_once '../../vendor/autoload.php'; 

        use MicrosoftAzure\Storage\Blob\BlobRestProxy;
        use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
        use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
        use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

        $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');
        
        if (isset($_POST['categorie']) && isset($_POST['cout']) && isset($_POST['description']) && isset($_POST['lieu']) && isset($_FILES['justificatif'])) {
            $categorie = $_POST['categorie'];
            $cout = $_POST['cout'];
            $description = $_POST['description'];
            $lieu = $_POST['lieu'];
        
            if (isset($_SESSION['nom']) && isset($_SESSION['prenom'])) {
                $nom = $_SESSION['nom'];
                $prenom = $_SESSION['prenom'];
        
                $stmt_nom = $db->prepare("SELECT nom FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                $stmt_nom->bindParam(':nom', $nom);
                $stmt_nom->bindParam(':prenom', $prenom);
                $stmt_nom->execute();
                $nom = $stmt_nom->fetch()['nom'];
        
                $stmt_mail = $db->prepare("SELECT mail FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                $stmt_mail->bindParam(':nom', $nom);
                $stmt_mail->bindParam(':prenom', $prenom);
                $stmt_mail->execute();
                $mail = $stmt_mail->fetch()['mail'];
        
                $stmt_user = $db->prepare("SELECT id_utilisateur FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                $stmt_user->bindParam(':nom', $nom);
                $stmt_user->bindParam(':prenom', $prenom);
                $stmt_user->execute();
                $id_utilisateur = $stmt_user->fetch()['id_utilisateur'];
        
                $id_status = 3;
        
                $stmt_ticket = $db->prepare("INSERT INTO ticket (categorie, prix, description, lieu, status, date, utilisateur, nom, mail) VALUES (:categorie, :cout, :description, :lieu, :status, NOW(), :id_utilisateur, :nom, :mail)");
                $stmt_ticket->bindParam(':categorie', $categorie);
                $stmt_ticket->bindParam(':cout', $cout);
                $stmt_ticket->bindParam(':description', $description);
                $stmt_ticket->bindParam(':lieu', $lieu);
                $stmt_ticket->bindParam(':status', $id_status);
                $stmt_ticket->bindParam(':id_utilisateur', $id_utilisateur);
                $stmt_ticket->bindParam(':nom', $nom);
                $stmt_ticket->bindParam(':mail', $mail);
        
                $stmt_ticket->execute();
        
                $id_ticket = $db->lastInsertId();
        
                $connectionString = "DefaultEndpointsProtocol=https;AccountName=e11event;AccountKey=zsZOSpoagHKUPcRe/SVjKGVph9Sc5rA2OMbzRyn9OLFUWrp2kFR0e3lUAThxepBHHpVQBTKeuRPa+AStbzTSDA==;EndpointSuffix=core.windows.net";
                $containerName = "justificatifs"; 

                if ($_FILES['justificatif']['size'] > 0) {

                  $extension = pathinfo($_FILES["justificatif"]["name"], PATHINFO_EXTENSION);
                  $nouveau_nom_image = "justificatif$id_ticket.$extension";
                  
                  $blobClient = BlobRestProxy::createBlobService($connectionString);
                  $content = fopen($_FILES['justificatif']['tmp_name'], "r");
                  $blobClient->createBlockBlob($containerName, $nouveau_nom_image, $content);

                  try {
                      $stmt_image = $db->prepare("UPDATE ticket SET justificatif = :justificatif WHERE id_ticket = :id_ticket");
                      $stmt_image->bindParam(':justificatif', $nouveau_nom_image);
                      $stmt_image->bindParam(':id_ticket', $id_ticket);
                      $stmt_image->execute();
                  } catch(PDOException $e) {
                      echo "Erreur lors de la mise à jour du nom du justificatif dans la base de données : " . $e->getMessage();
                  }
                }

                echo '<div id="success-alert" class="alert alert-success alert-dismissible fade show" style="margin-top: 10px; justify-content: center; text-align: center; font-size: 13px;" role="alert">
                        <p>La note de frais a bien été ajoutée.</p>
                      </div>';
                echo '<script type="text/javascript">
                        setTimeout(function() {
                            var element = document.getElementById("success-alert");
                            element.parentNode.removeChild(element);
                        }, 3000);
                      </script>';
            }
          }
        ?>
      </form>
      <script>

          $("#ticketForm").on("submit", function(e) {
              e.preventDefault();

              $.ajax({
                  url: "tickets_commercial.php", 
                  type: "post",
                  data: new FormData(this),
                  contentType: false,
                  cache: false,
                  processData:false,
                  success: function(data){
                      $("#pendingTable tbody").append(data);

                      $("#ticketForm")[0].reset();
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                      console.error(textStatus, errorThrown);
                  }
              });
          });
      </script>
    </div>
  </div>

    <div class="ticket-pending">
      <main>
        <div class="bottom_data">
          <div class="orders">
            <h3 class="pending-ticket-title">Tickets en attente</h3>
            <table id="pendingTable">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Date</th>
                  <th>Lieu</th>
                  <th>Catégorie</th>
                  <th>Prix</th>
                  <th>Description</th>
                  <th>Justificatif</th>
                  <th id="status">Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php                  
                  $connectionString = "DefaultEndpointsProtocol=https;AccountName=e11event;AccountKey=OVp/sacfyyfrlCyj0SEAl/k8jS6r5G+wQ86UeD5oR6W9i2d395JqqmUEi7ZwVrDU6BYkqh5t6OPW+ASttYtsEg==;EndpointSuffix=core.windows.net";
                  $blobClient = BlobRestProxy::createBlobService($connectionString);
                  $containerName = "justificatifs"; 
                  $justificatifs = "justificatifs";
                  $accountName = "e11event";

                  error_reporting(E_ALL);
                  ini_set('display_errors', 1);
                  
                  $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');

                  if (isset($_SESSION['nom']) && isset($_SESSION['prenom'])) {
                    $nom = $_SESSION['nom'];
                    $prenom = $_SESSION['prenom'];

                    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
                      $id_ticket_to_delete = $_GET['id'];
                      
                      $stmt_delete = $db->prepare("DELETE FROM ticket WHERE id_ticket = :id_ticket");
                      $stmt_delete->bindParam(':id_ticket', $id_ticket_to_delete);
                      $stmt_delete->execute();
                      
                      echo "Ticket supprimé avec succès";
                    }
    
                    $stmt_nom = $db->prepare("SELECT nom FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                    $stmt_nom->bindParam(':nom', $nom);
                    $stmt_nom->bindParam(':prenom', $prenom);
                    $stmt_nom->execute();
                    $nom = $stmt_nom->fetch()['nom'];
    
                    $stmt_mail = $db->prepare("SELECT mail FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                    $stmt_mail->bindParam(':nom', $nom);
                    $stmt_mail->bindParam(':prenom', $prenom);
                    $stmt_mail->execute();
                    $mail = $stmt_mail->fetch()['mail'];
    
                    $stmt_user = $db->prepare("SELECT id_utilisateur FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                    $stmt_user->bindParam(':nom', $nom);
                    $stmt_user->bindParam(':prenom', $prenom);
                    $stmt_user->execute();
                    $id_utilisateur = $stmt_user->fetch()['id_utilisateur'];

                    $stmt_user = $db->prepare("SELECT role FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                    $stmt_user->bindParam(':nom', $nom);
                    $stmt_user->bindParam(':prenom', $prenom);
                    $stmt_user->execute();

                    $role = $stmt_user->fetch()['role']; 
                    
                    if ($role == '1') {
                        $pending_tickets = $db->prepare("
                            SELECT t.*, t.nom, t.mail, tc.nom_categorie AS categorie, ts.nom_status AS status
                            FROM ticket AS t
                            INNER JOIN ticket_categorie AS tc ON t.categorie = tc.id_category
                            INNER JOIN ticket_status AS ts ON t.status = ts.id_status
                            WHERE ts.nom_status = 'En attente'
                        ");
                    } else {
                        $pending_tickets = $db->prepare("
                            SELECT t.*, t.nom, t.mail, tc.nom_categorie AS categorie, ts.nom_status AS status
                            FROM ticket AS t
                            INNER JOIN ticket_categorie AS tc ON t.categorie = tc.id_category
                            INNER JOIN ticket_status AS ts ON t.status = ts.id_status
                            WHERE t.utilisateur = :id_utilisateur AND ts.nom_status = 'En attente'
                        ");
                        $pending_tickets->bindParam(':id_utilisateur', $id_utilisateur);
                    }
                    $pending_tickets->execute();

                    $pending_data = array_reverse($pending_tickets->fetchAll());

                    $blobList = $blobClient->listBlobs($containerName);
                    $justificatif_files = [];
                    foreach ($blobList->getBlobs() as $blob) {
                        $justificatif_files[] = $blob->getName();
                    }

                    foreach ($pending_data as $row) {
                      $justificatifIcon = '';
                      if (!empty($row['justificatif'])) {
                          if (in_array($row['justificatif'], $justificatif_files)) {
                              $justificatifIcon = "<a href='https://$accountName.blob.core.windows.net/$justificatifs/".$row['justificatif']."' target='_blank'><i class='fa-solid fa-arrow-up-right-from-square no-link-style'></i></a>";
                          }
                      }
                      echo "<tr>
                              <td>".$row['id_ticket']."</td>
                              <td>".$row['date']."</td>
                              <td>".$row['lieu']."</td>
                              <td>".$row['categorie']."</td>
                              <td>".$row['prix']."€</td>
                              <td>".$row['description']."</td>
                              <td>".$row['justificatif']." ".$justificatifIcon."</td>
                              <td id='status' class='center-content'><span class='status pending'>".$row['status']."</span></td>
                              <td class='center-content'><a href='tickets_commercial.php?id=".$row['id_ticket']."' class='btn-delete'><i class='fa-solid fa-trash'></i></a></td> 
                          </tr>";
                    }
        
                    if(isset($_GET['id'])) {
                      $id_ticket_to_delete = $_GET['id'];
   
                      $stmt = $db->prepare("SELECT justificatif FROM ticket WHERE id_ticket = :id_ticket");
                      $stmt->bindParam(':id_ticket', $id_ticket_to_delete);
                      $stmt->execute();
                      $row = $stmt->fetch(PDO::FETCH_ASSOC);
                      $justificatif_filename = $row ? $row['justificatif'] : null;
                  
                      $stmt_delete = $db->prepare("DELETE FROM ticket WHERE id_ticket = :id_ticket");
                      $stmt_delete->bindParam(':id_ticket', $id_ticket_to_delete);
                      $stmt_delete->execute();
                  
                      if (!empty($justificatif_filename)) {
                          $blobClient->deleteBlob($justificatifs, $justificatif_filename);
                      }
                    }
                  }
                ?>
              </tbody>
            </table>
            <script>
            $(document).ready(function() {
                $('.btn-delete').click(function(e) {
                    e.preventDefault(); 

                    var url = $(this).attr('href');

                    $.ajax({
                        type: 'POST',
                        url: url,
                        success: function(data) {
                            $(e.target).closest('tr').remove();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
            </script>
          </div>
        </div>
      </main>
    </div>

  <div class="ticket-table-container">                
    <div class="ticket-other">
      <main>
        <div class="bottom_data">
          <div class="orders">
            <h3 class="other-ticket-title">Historique des tickets</h3>
            <table id="otherTable">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Date</th>
                  <th>Lieu</th>
                  <th>Catégorie</th>
                  <th>Prix</th>
                  <th>Description</th>
                  <th>Justificatif</th>
                  <th id="status">Status</th>
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

                  if (isset($_SESSION['nom']) && isset($_SESSION['prenom'])) {
                    $nom = $_SESSION['nom'];
                    $prenom = $_SESSION['prenom'];
    
                    $stmt_nom = $db->prepare("SELECT nom FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                    $stmt_nom->bindParam(':nom', $nom);
                    $stmt_nom->bindParam(':prenom', $prenom);
                    $stmt_nom->execute();
                    $nom = $stmt_nom->fetch()['nom'];
    
                    $stmt_mail = $db->prepare("SELECT mail FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                    $stmt_mail->bindParam(':nom', $nom);
                    $stmt_mail->bindParam(':prenom', $prenom);
                    $stmt_mail->execute();
                    $mail = $stmt_mail->fetch()['mail'];
    
                    $stmt_user = $db->prepare("SELECT id_utilisateur FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                    $stmt_user->bindParam(':nom', $nom);
                    $stmt_user->bindParam(':prenom', $prenom);
                    $stmt_user->execute();
                    $id_utilisateur = $stmt_user->fetch()['id_utilisateur'];

                     $stmt_user = $db->prepare("SELECT role FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                     $stmt_user->bindParam(':nom', $nom);
                     $stmt_user->bindParam(':prenom', $prenom);
                     $stmt_user->execute();
 
                     $role = $stmt_user->fetch()['role']; 

                     if ($role == '1') {
                          $other_tickets = $db->prepare("
                              SELECT t.*, t.nom, t.mail, tc.nom_categorie AS categorie, ts.nom_status AS status
                              FROM ticket AS t
                              INNER JOIN ticket_categorie AS tc ON t.categorie = tc.id_category
                              INNER JOIN ticket_status AS ts ON t.status = ts.id_status
                              WHERE ts.nom_status != 'En attente'
                          ");
                      } else {
                          $other_tickets = $db->prepare("
                              SELECT t.*, t.nom, t.mail, tc.nom_categorie AS categorie, ts.nom_status AS status
                              FROM ticket AS t
                              INNER JOIN ticket_categorie AS tc ON t.categorie = tc.id_category
                              INNER JOIN ticket_status AS ts ON t.status = ts.id_status
                              WHERE t.utilisateur = :id_utilisateur AND ts.nom_status != 'En attente'
                          ");
                          $other_tickets->bindParam(':id_utilisateur', $id_utilisateur);
                      }
                      $other_tickets->execute();
                    
                      $other_data = array_reverse($other_tickets->fetchAll());
                  
                      foreach ($other_data as $row) {
                        $justificatifIcon = '';
                        if (!empty($row['justificatif'])) {
                            $justificatifIcon = "<a href='https://$accountName.blob.core.windows.net/$justificatifs/" . $row['justificatif'] . "' target='_blank'><i class='fa-solid fa-arrow-up-right-from-square no-link-style'></i></a>";
                        }
                    
                        $statusClass = '';
                        if ($row['status'] == 'Accepté') {
                            $statusClass = 'completed';
                        } elseif ($row['status'] == 'Refusé') {
                            $statusClass = 'processing';
                        }
                    
                        echo "<tr>
                                <td>".$row['id_ticket']."</td>
                                <td>".$row['date']."</td>
                                <td>".$row['lieu']."</td>
                                <td>".$row['categorie']."</td>
                                <td>".$row['prix']."€</td>
                                <td>".$row['description']."</td>
                                <td>".$row['justificatif']." ".$justificatifIcon."</td>
                                <td id='status'><span class='status ".$statusClass."'>" .$row['status']."</span></td>
                            </tr>";
                    }
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
      $('#otherTable, #pendingTable').DataTable({
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