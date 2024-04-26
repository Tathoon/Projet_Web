<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tickets - Commercial</title>
  <link rel="icon" href="../../images/Logo_onglet.png" type="image/x-icon">
  <link rel="stylesheet" href="../../styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
</head>
<body>
  <?php
    session_start();
    echo $_SESSION['nom'];

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
      <a href="#" class="active"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
      <a href="../autres/notifications.php"><i class="fa-solid fa-bell"></i><span>Notifications</span></a>
      <a href="../autres/settings.php"><i class="fas fa-sliders-h"></i><span>Settings</span></a>
      <a href="../../index.php?logout=true" ><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
    </div>
  </div>

  <div class="sidebar">
    <div class="profile_info">
      <img src="../../images/user-icon.png" class="profile_image" alt="">
      <h4><?php echo ucfirst($_SESSION['nom']) . " " . ucfirst($_SESSION['prenom']) ; ?></h4>
    </div>
    <a href="#" class="active"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
    <a href="../autres/notifications.php"><i class="fa-solid fa-bell"></i><span>Notifications</span></a>
    <a href="../autres/settings.php"><i class="fas fa-sliders-h"></i><span>Settings</span></a>
    <a href="../../index.php?logout=true" class="logout-commercial" ><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
  </div>

  

  <h1 class="title">Vos notes de frais</h1>

  <div class="ticket-container">
    <div class="card-ticket">
      <h1 class="title">Formulaire de dépôt de note de frais</h1>
      <form action="tickets_commercial.php" method="post" enctype="multipart/form-data" class="form-user ticket-card form-container">

        <div class="mb-3">
          <label for="categorie">Type de frais<span style="color: red;">*</span> <span> :</span></label>
          <div class="form-type-frais">
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

        <div class="mb-3">
          <label for="cout">Coût du frais<span style="color: red;">*</span> <span> :</span></label>
          <input name="cout" type="text" id="cout" required>
        </div>

        <div class="mb-3">
          <label for="description">Description du frais<span style="color: red;">*</span> <span> :</span></label>
          <textarea name="description" type="text" id="description" required></textarea>
        </div>

        <div class="mb-3">
          <label for="lieu">Lieu du frais<span style="color: red;">*</span> <span> :</span></label>
          <input name="lieu" type="text" id="lieu" required>
        </div>

        <div class="mb-3">
          <label for="justificatif">Justificatif : <span> (Optionnel)</span></label>
          <div id="file-info" style="display: flex; align-items: center;">
            <div id="file-name" style="flex: 1;"></div>
            <i class='fa-solid fa-trash' id="delete-justificatif" style="display: none;"></i>
          </div>
          <div class="button-row">
            <label class="file-label" for="justificatif">Ajouter un fichier</label>
            <input type="file" name="justificatif" id="justificatif">
            <button type="submit" class="submit-button">Ajouter la note</button>
          </div>
        </div>

        <script>
          $(document).ready(function () {
            // Lorsque le bouton de sélection de fichier change
            $('#justificatif').on('change', function(e){
              // Récupérer le nom du fichier sélectionné
              var fileName = e.target.files[0].name;
              // Mettre à jour le texte de l'élément pour afficher le nom du fichier
              $('#file-name').text(fileName);
            });
          });

          $(document).ready(function () {
            // Lorsque le bouton de sélection de fichier change
            $('#justificatif').on('change', function(e){
              // Récupérer le nom du fichier sélectionné
              var fileName = e.target.files[0].name;
              // Mettre à jour le texte de l'élément pour afficher le nom du fichier
              $('#file-name').text(fileName);
              
              // Afficher le bouton de suppression si un justificatif est sélectionné
              if(fileName !== ''){
                $('#delete-justificatif').show();
              } else {
                $('#delete-justificatif').hide();
              }
            });

            // Lorsque le bouton de suppression de justificatif est cliqué
            $('#delete-justificatif').on('click', function(){
              // Réinitialiser l'élément de sélection de fichier
              $('#justificatif').val('');
              // Réinitialiser le texte affichant le nom du fichier
              $('#file-name').text('');
              // Masquer le bouton de suppression
              $(this).hide();
            });
          });
        </script>
              
        <?php
        if (isset($_POST['categorie']) && isset($_POST['cout']) && isset($_POST['description']) && isset($_POST['lieu']) && isset($_FILES['justificatif'])) {
            $categorie = $_POST['categorie'];
            $cout = $_POST['cout'];
            $description = $_POST['description'];
            $lieu = $_POST['lieu'];

            // Vérifie si les informations de nom et prénom de l'utilisateur sont disponibles dans la session
            if (isset($_SESSION['nom']) && isset($_SESSION['prenom'])) {
                $nom = $_SESSION['nom'];
                $prenom = $_SESSION['prenom'];

                // Connexion à la base de données
                $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');

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

                $id_status = 3;

                // Insère les informations du ticket dans la base de données
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

                $target_dir = "../../images/justificatifs/";
                $extension = pathinfo($_FILES["justificatif"]["name"], PATHINFO_EXTENSION);
                $nouveau_nom_image = "justificatif$id_ticket.$extension";
                $target_file = $target_dir . $nouveau_nom_image;

                if (move_uploaded_file($_FILES["justificatif"]["tmp_name"], $target_file)) {
                    $stmt_image = $db->prepare("UPDATE ticket SET justificatif = :justificatif WHERE id_ticket = :id_ticket");
                    $stmt_image->bindParam(':justificatif', $nouveau_nom_image);
                    $stmt_image->bindParam(':id_ticket', $id_ticket);

                    $stmt_image->execute();

                echo '<div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
                      <strong> La note de frais a bien été ajoutée.</strong>
                      </div>';
                echo '<script type="text/javascript">
                      setTimeout(function() {
                          var element = document.getElementById("success-alert");
                          element.parentNode.removeChild(element);
                      }, 3000);
                      </script>';
            } else {
                echo '<div role="alert">
                      <strong> Erreur lors de l\'envois du formulaire. Veuillez vous connecter.</strong>
                      </div>';
                echo '<script type="text/javascript">
                      setTimeout(function() {
                          var element = document.getElementById("success-alert");
                          element.parentNode.removeChild(element);
                      }, 5000);
                      </script>';
            }
          }
        }
        ?>
      </form>
    </div>
  </div>

    <div class="ticket-pending">
      <main>
        <div class="bottom_data">
          <div class="orders">
            <div class="header">
              <h3>Tickets en attente</h3>
            </div>
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
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');

                  if (isset($_SESSION['nom']) && isset($_SESSION['prenom'])) {
                    $nom = $_SESSION['nom'];
                    $prenom = $_SESSION['prenom'];
    
                    // Connexion à la base de données
                    $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');
    
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
                        WHERE t.utilisateur = :id_utilisateur AND ts.nom_status = 'En attente'
                    ");
                    $pending_tickets->bindParam(':id_utilisateur', $id_utilisateur); 
                    $pending_tickets->execute();
                    $pending_data = $pending_tickets->fetchAll();

                    foreach ($pending_data as $row) {
                      $justificatifIcon = '';
                      if (!empty($row['justificatif'])) {
                        $justificatifIcon = "<a href='../../images/justificatifs/".$row['justificatif']."' target='_blank'><i class='fa-solid fa-arrow-up-right-from-square no-link-style'></i></a>";
                      }
                      echo "<tr>
                              <td>".$row['id_ticket']."</td>
                              <td>".$row['date']."</td>
                              <td>".$row['lieu']."</td>
                              <td>".$row['categorie']."</td>
                              <td>".$row['prix']."</td>
                              <td>".$row['description']."</td>
                              <td>".$row['justificatif']." ".$justificatifIcon."</td>
                              <td><span class='status pending'>".$row['status']."</span></td>
                              <td><a href='delete_ticket.php?id=".$row['id_ticket']."' class='btn-delete'>Supprimer</a></td> <!-- Bouton Supprimer -->
                            </tr>";
                    }

                    if(isset($_GET['id'])) {
                        $id_ticket_to_delete = $_GET['id'];
                        
                        // Connexion à la base de données
                        $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');
                    
                        // Supprimer le ticket de la base de données
                        $stmt_delete = $db->prepare("DELETE FROM ticket WHERE id_ticket = :id_ticket");
                        $stmt_delete->bindParam(':id_ticket', $id_ticket_to_delete);
                        $stmt_delete->execute();
                        
                        // Rediriger vers la page précédente ou une autre page après la suppression
                        header('Location: previous_page.php');
                        exit();
                    }

                    $rowCount = count($pending_data);

                    if ($rowCount < 9) {
                      $emptyRows = 9 - $rowCount;

                      for ($i = 0; $i < $emptyRows; $i++) {
                        echo "<tr><td colspan='8'>&nbsp;</td></tr>";
                      }
                    }

                    foreach ($pending_data as $row) {
                    }
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>

  <div class="ticket-table-container">                
    <div class="ticket-other">
      <main>
        <div class="bottom_data">
          <div class="orders">
            <div class="header">
              <h3>Autres tickets</h3>
            </div>
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
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');

                  if (isset($_SESSION['nom']) && isset($_SESSION['prenom'])) {
                    $nom = $_SESSION['nom'];
                    $prenom = $_SESSION['prenom'];
    
                    // Connexion à la base de données
                    $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');
    
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
                    
                    $other_tickets = $db->prepare("
                        SELECT t.*, u.nom, u.mail, tc.nom_categorie AS categorie, ts.nom_status AS status
                        FROM ticket AS t
                        INNER JOIN utilisateur AS u ON t.utilisateur = u.id_utilisateur
                        INNER JOIN ticket_categorie AS tc ON t.categorie = tc.id_category
                        INNER JOIN ticket_status AS ts ON t.status = ts.id_status
                        WHERE t.utilisateur = :id_utilisateur AND ts.nom_status != 'En attente'
                    ");
                    $other_tickets->bindParam(':id_utilisateur', $id_utilisateur); 
                    $other_tickets->execute();
                    $other_data = $pending_tickets->fetchAll();
                  
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
                              <td>".$row['date']."</td>
                              <td>".$row['lieu']."</td>
                              <td>".$row['categorie']."</td>
                              <td>".$row['prix']."</td>
                              <td>".$row['description']."</td>
                              <td>".$row['justificatif']." ".$justificatifIcon."</td>
                              <td><span class='status completed processing".$statusClass."'>".$row['status']."</span></td>
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
      $('#pendingTable').DataTable();
      $('#otherTable').DataTable();
    });
    
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