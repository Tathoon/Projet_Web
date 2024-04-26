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

  <h1 class="title">Formulaire de dépôt de note de frais</h1>

  <div class="box-general">
    <form action="tickets_commercial.php" method="post" enctype="multipart/form-data">

      <div class="mb-3">
        <label for="choix">Type de frais :</label>
          <select name="categorie" id="categorie">
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
        <label for="cout" class="">Coût du frais :</label>
        <input name="cout" type="text" id="cout">
      </div>

      <div class="mb-3">
        <label for="description" class="">Description du frais :</label>
        <input name="description" type="text" id="description">
      </div>

      <div class="mb-3">
        <label for="lieu" class="">Lieu du frais :</label>
        <input name="lieu" type="text" id="lieu">
      </div>

      <div class="mb-3">
        <label for="justificatif">Justificatif :</label>
        <input type="file" name="justificatif" id="justificatif">
      </div>

      <div class="mb-3">
        <button type="submit" class="">Ajouter la note de frais</button>
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
        echo "Une erreur s'est produite lors du téléchargement du fichier.";
    }
}
?>
    </form>
  </div>

  

  
  <script type="text/javascript" src="../../index.js"></script>
</body>
</html>