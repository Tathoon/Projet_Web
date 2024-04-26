<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tickets - Commercial</title>
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
          <select name="categorie" id="categorie" required>
              <option value="" style="color: gray;">Renseignez le type de frais</option>
            <?php 
            $db = new PDO('mysql:host=localhost;dbname=e11event_bdd;charset=utf8mb4', 'root', '');
            $ticket_categorie = $db->query("SELECT * FROM ticket_categorie")->fetchAll();
            foreach ($ticket_categorie as $row) {
              echo "<option value=".$row['id_category'].">".$row['nom_categorie']."</option>";
            }
            ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="cout">Coût du frais<span style="color: red;">*</span> <span> :</span></label>
          <input name="cout" type="text" id="cout" required>
        </div>

        <div class="mb-3">
          <label for="description">Description du frais<span style="color: red;">*</span> <span> :</span></label>
          <input name="description" type="text" id="description" required>
        </div>

        <div class="mb-3">
          <label for="lieu">Lieu du frais<span style="color: red;">*</span> <span> :</span></label>
          <input name="lieu" type="text" id="lieu" required>
        </div>

        <div class="mb-3">
          <label for="justificatif">justificatif<span> : </span><span>(facultatif)</span></label>
          <input type="file" name="justificatif" id="justificatif">
        </div>

        <div class="mb-3">
          <button type="submit">Ajouter la note de frais</button>
        </div>
      
        <?php
          if (isset($_POST['categorie']) && isset($_POST['cout']) && isset($_POST['description']) && isset($_POST['lieu']) && isset($_FILES['justificatif'])) {
              $categorie = $_POST['categorie'];
              $cout = $_POST['cout'];
              $description = $_POST['description'];
              $lieu = $_POST['lieu'];

              // Connexion à la base de données
              $db = new PDO('mysql:host=localhost;dbname=e11event_bdd;charset=utf8mb4', 'root', '');

              if (isset($_SESSION['nom']) && isset($_SESSION['prenom'])) {
                  $nom = $_SESSION['nom'];
                  $prenom = $_SESSION['prenom'];

                  $stmt_user = $db->prepare("SELECT id_utilisateur FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
                  $stmt_user->bindParam(':nom', $nom);
                  $stmt_user->bindParam(':prenom', $prenom);
                  $stmt_user->execute();
                  $id_utilisateur = $stmt_user->fetch()['id_utilisateur']; // Récupérez l'ID de l'utilisateur
              }

              // ID du statut à insérer (dans cet exemple, 3)
              $id_status = 3;

              // Préparation de la requête pour insérer le ticket
              $stmt_ticket = $db->prepare("INSERT INTO ticket (categorie, prix, description, lieu, status, date, utilisateur) VALUES (:categorie, :cout, :description, :lieu, :status, NOW(), :id_utilisateur)");
              $stmt_ticket->bindParam(':categorie', $categorie);
              $stmt_ticket->bindParam(':cout', $cout);
              $stmt_ticket->bindParam(':description', $description);
              $stmt_ticket->bindParam(':lieu', $lieu);
              $stmt_ticket->bindParam(':status', $id_status);
              $stmt_ticket->bindParam(':id_utilisateur', $id_utilisateur); // Utilisez l'ID de l'utilisateur récupéré

              // Exécution de la requête pour insérer le ticket
              $stmt_ticket->execute();

              // Récupération de l'ID du ticket inséré
              $id_ticket = $db->lastInsertId();

              // Traitement de l'image justificatif
              $target_dir = "../../images/justificatifs/";
              $extension = pathinfo($_FILES["justificatif"]["name"], PATHINFO_EXTENSION);
              $nouveau_nom_image = "justificatif$id_ticket.$extension";
              $target_file = $target_dir . $nouveau_nom_image;

              // Déplacer l'image vers le dossier "justificatifs"
              if (move_uploaded_file($_FILES["justificatif"]["tmp_name"], $target_file)) {
                  // Préparation de la requête pour mettre à jour le nom de l'image dans la base de données
                  $stmt_image = $db->prepare("UPDATE ticket SET justificatif = :justificatif WHERE id_ticket = :id_ticket");
                  $stmt_image->bindParam(':justificatif', $nouveau_nom_image);
                  $stmt_image->bindParam(':id_ticket', $id_ticket);

                  // Exécution de la requête pour mettre à jour le nom de l'image dans la base de données
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
                  <strong> Erreur lors de l envois du formulaire.</strong>
                  </div>';
                  echo '<script type="text/javascript">
                        setTimeout(function() {
                            var element = document.getElementById("success-alert");
                            element.parentNode.removeChild(element);
                        }, 5000);
                      </script>';
              }
          }
        ?>
      </form>
    </div>

  <div class="ticket-container">
    <!-- Tableau pour les tickets en attente -->
    <div class="ticket-pending">
      <main>
        <div class="bottom_data">
          <div class="orders">
            <div class="header">
              <h3>Tickets en attente</h3>
            </div>
            <table id="pendingTable">
              <!-- Entêtes de colonnes -->
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
              <!-- Corps du tableau -->
              <tbody>
                <?php
                  // Connexion à la base de données
                  $db = new PDO('mysql:host=localhost;dbname=e11event_bdd;charset=utf8mb4', 'root', '');

                  // Requête pour les tickets en attente
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
                    echo "<tr>
                            <td>".$row['id_ticket']."</td>
                            <td>".$row['date']."</td>
                            <td>".$row['lieu']."</td>
                            <td>".$row['categorie']."</td>
                            <td>".$row['prix']."</td>
                            <td>".$row['description']."</td>
                            <td>".$row['justificatif']." ".$justificatifIcon."</td>
                            <td><span class='status pending'>".$row['status']."</span></td>
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

  <div class="ticket-container">                
    <!-- Tableau pour les autres tickets -->
    <div class="ticket-other">
      <main>
        <div class="bottom_data">
          <div class="orders">
            <div class="header">
              <h3>Autres tickets</h3>
            </div>
            <table id="otherTable">
              <!-- Entêtes de colonnes -->
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
              <!-- Corps du tableau -->
              <tbody>
                <?php
                  // Requête pour les autres tickets
                  $other_tickets = $db->prepare("
                    SELECT t.*, u.nom, u.mail, tc.nom_categorie AS categorie, ts.nom_status AS status
                    FROM ticket AS t
                    INNER JOIN utilisateur AS u ON t.utilisateur = u.id_utilisateur
                    INNER JOIN ticket_categorie AS tc ON t.categorie = tc.id_category
                    INNER JOIN ticket_status AS ts ON t.status = ts.id_status
                    WHERE ts.nom_status != 'En attente'
                  ");
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
                            <td>".$row['date']."</td>
                            <td>".$row['lieu']."</td>
                            <td>".$row['categorie']."</td>
                            <td>".$row['prix']."</td>
                            <td>".$row['description']."</td>
                            <td>".$row['justificatif']." ".$justificatifIcon."</td>
                            <td><span class='status completed processing".$statusClass."'>".$row['status']."</span></td>
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

  <!-- Scripts -->
  <script>
    $(document).ready(function () {
      $('#pendingTable').DataTable();
      $('#otherTable').DataTable();
    });
  </script>

  
  <script type="text/javascript" src="../../index.js"></script>
</body>
</html>