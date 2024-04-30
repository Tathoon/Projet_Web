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
  $db = new PDO('mysql:host=localhost;dbname=e11event_bdd;charset=utf8mb4', 'root', '');
  // Set the PDO error mode to exception
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}


function getStatus($status) {
  switch ($status) {
    case 'Complété':
      return '<span class="status completed">Complété</span>';
      break;
    case 'En attente':
      return '<span class="status pending">En attente</span>';
      break;
    case 'Rejeté':
      return '<span class="status processing">Rejeté</span>';
      break;
    default:
      return '';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tickets - Comptable</title>
  <link rel="stylesheet" href="../../styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
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
          <table id="myTable">
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
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql = "SELECT * FROM ticket";
              $result = $db->query($sql);
              
              if ($result && $result->rowCount() > 0) {
                  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                      echo "<tr>";
                      echo "<td>" . (isset($row["id"]) ? $row["id"] : "") . "</td>";
                      echo "<td>" . (isset($row["name"]) ? $row["name"] : "") . "</td>";
                      echo "<td>" . (isset($row["email"]) ? $row["email"] : "") . "</td>";
                      echo "<td>" . $row["date"] . "</td>";
                      echo "<td>" . $row["lieu"] . "</td>";
                      echo "<td>" . $row["nom_categorie"] . "</td>";
                      echo "<td>" . $row["prix"] . "€</td>";
                      echo "<td>" . $row["description"] . "</td>";
                      echo "<td>" . getStatus($row["status"]) . "</td>";
                      echo "</tr>";
                  }
              } else {
                  echo "<tr><td colspan='9'>0 results</td></tr>";
              }
              // Close the connection
              $db = null;
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
  <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
  <script>
    $(document).ready(function () {
      $('#myTable').DataTable();
    });
  </script>
  <script src="../../index.js"></script>
</body>
</html>
