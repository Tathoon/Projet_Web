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

    $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql_number_users = "SELECT COUNT(DISTINCT id_utilisateur) AS total_utilisateurs FROM utilisateur";
    $result_users = $db->query($sql_number_users);

    $sql_number_tickets = "SELECT COUNT(DISTINCT id_ticket) AS total_tickets FROM ticket";
    $result_tickets = $db->query($sql_number_tickets);

    $sql_depense = "SELECT COALESCE(SUM(prix), 0) AS total_depense FROM ticket WHERE status = 1";
    $result_depense = $db->query($sql_depense);

    $sql_tickets_attente = "SELECT COUNT(DISTINCT id_ticket) AS total_tickets_attente FROM ticket WHERE status = 3";
    $result_tickets_attente = $db->query($sql_tickets_attente);

    $sql_user_by_number_tickets = "SELECT u.nom, u.prenom, COUNT(t.id_ticket) AS nombre_tickets
                               FROM utilisateur u
                               LEFT JOIN ticket t ON u.id_utilisateur = t.utilisateur
                               WHERE t.status = 1
                               GROUP BY u.id_utilisateur
                               ORDER BY nombre_tickets DESC";

    $result_user_by_number_tickets = $db->query($sql_user_by_number_tickets);


    if ($result_users !== false && $result_tickets !== false && $result_depense !== false && $result_tickets_attente !== false && $result_user_by_number_tickets !== false){
        $row_users = $result_users->fetch(PDO::FETCH_ASSOC);
        $row_tickets = $result_tickets->fetch(PDO::FETCH_ASSOC);
        $row_depense = $result_depense->fetch(PDO::FETCH_ASSOC);
        $row_tickets_attente = $result_tickets_attente->fetch(PDO::FETCH_ASSOC);
        $rows_user_by_number_tickets = $result_user_by_number_tickets->fetchAll(PDO::FETCH_ASSOC);

        
        if ($row_users && $row_tickets && $row_depense && $row_tickets_attente && $rows_user_by_number_tickets) {
            $total_utilisateurs = $row_users["total_utilisateurs"];
            $total_tickets = $row_tickets["total_tickets"];
            $total_depense = $row_depense["total_depense"];
            $total_tickets_attente = $row_tickets_attente["total_tickets_attente"];
        } else {
            echo "Aucun résultat trouvé.";
        }
    } else {
        echo "Une erreur s'est produite lors de l'exécution de la requête.";
    }

    $data = array(
      'total_utilisateurs' => $total_utilisateurs,
      'total_tickets' => $total_tickets,
      'total_depense' => $total_depense,
      'total_tickets_attente' => $total_tickets_attente
    );

    $sql_categories_and_totals = "SELECT tc.nom_categorie, COALESCE(SUM(t.prix), 0) AS prix_total_par_categorie 
                              FROM ticket_categorie tc 
                              LEFT JOIN ticket t ON tc.id_category = t.categorie 
                              WHERE t.status = 1
                              GROUP BY tc.nom_categorie";

    
    $result_categories_and_totals = $db->query($sql_categories_and_totals);
    
    $labels = array();
    $prices_per_category = array();
    
    while ($row = $result_categories_and_totals->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = $row['nom_categorie'];
        $prices_per_category[] = $row['prix_total_par_categorie']; 
    }
    
    $labelsJSON = json_encode($labels);
    $prices_per_category_json = json_encode($prices_per_category);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Admin</title>
  <link rel="icon" href="../../images/Logo_onglet.png" type="image/x-icon">
  <link rel="stylesheet" href="../../styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      <a href="#" class="active"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
      <a href="tickets_admin.php"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
      <a href="utilisateurs_admin.php"><i class="fas fa-table"></i><span>Utilisateurs</span></a>
      <a href="../autres/settings.php"><i class="fas fa-sliders-h"></i><span>Paramètres</span></a>
      <a href="../../index.php?logout=true" ><i class="fa-solid fa-right-from-bracket"></i><span>Déconnexion</span></a>
    </div>
  </div>

  <div class="sidebar">
    <div class="profile_info">
      <img src="../../images/user-icon.png" class="profile_image" alt="">
      <h4><?php echo ucfirst($_SESSION['nom']) . " " . ucfirst($_SESSION['prenom']) ; ?></h4>
    </div>
      <a href="#" class="active"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
      <a href="tickets_admin.php"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
      <a href="utilisateurs_admin.php"><i class="fas fa-table"></i><span>Utilisateurs</span></a>
      <a href="../autres/settings.php"><i class="fas fa-sliders-h"></i><span>Paramètres</span></a>
      <a href="../../index.php?logout=true" class="logout" ><i class="fa-solid fa-right-from-bracket"></i><span>Déconnexion</span></a>
  </div>


  <div class="content">
      <main>
        <div class="header">
          <h1><i class="fa-solid fa-gauge"></i> Dashboard Admin</h1>
        </div>
        <ul class="cards">
          <li>
            <i class="bx bx-group"></i>
            <span class="info">
            <h3><?php echo $total_utilisateurs; ?></h3>
              <p>Total Utilisateurs</p>
            </span>
          </li>
          <li>
            <i class="bx bx-movie"></i>
            <span class="info">
              <h3><?php echo $total_tickets; ?></h3>
              <p>Nombre Tickets</p>
            </span>
          </li>
          <li>
          <i class='bx bxs-stopwatch'></i>
            <span class="info">
              <h3><?php echo $total_tickets_attente; ?></h3>
              <p>Tickets en attente</p>
            </span>
          </li>

      <?php

          $current_month = date('m');

          if (isset($_POST['month'])) {
              $current_month = $_POST['month'];
          }

          $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');
          $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          $sql_depense = "SELECT COALESCE(SUM(prix), 0) AS total_depense FROM ticket WHERE status = 1 AND MONTH(date) = :month";
          $stmt = $db->prepare($sql_depense);
          $stmt->bindParam(':month', $current_month);
          $stmt->execute();
          $expenses = $stmt->fetch(PDO::FETCH_ASSOC);
          $total_depense = $expenses['total_depense'];

          if (isset($_POST['month'])) {
              echo number_format($total_depense, 2); 
              exit;
          }
          
      ?>

      <li>
          <i class="bx bx-dollar-circle"></i>
          <span class="info">
              <h3><?php echo number_format($total_depense, 2); ?> €</h3>
              <p>Dépenses du mois</p>
          </span>
      </li>

        </ul>
        <div class="bottom_data">
          <div class="orders">
            <div class="header">
              <h3>Informations générales</h3>
            </div>
            <canvas id="myChart" class="graph"></canvas>
          </div>
          <div class="reminders">
            <div id="calendar">
              <div id="calendar-header">
                  <span id="month-prev" class="change-month">&lt;</span>
                <h1 id="month"></h1>
                <span id="month-next" class="change-month">&gt;</span>
              </div>
          <div id="days"></div>
              <div id="calendar-body"></div>
          </div>
          </div>
        </div>
        <div class="charts-container">
          <div class="pie-chart">
              <div class="header">
                  <h3>Dépenses totales par catégories</h3>
              </div>
              <canvas id="camembertChart"></canvas>
          </div>
          <div class="most-ticket-user">
              <div class="header">
                  <h3>Commerciaux avec le plus de tickets acceptés</h3>
              </div>
              <table class="user-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Nombre de tickets acceptés</th>
                    </tr>
                </thead>
                <tbody>

                <?php
             
                  $liste_utilisateurs_tickets = [];

                  while ($row = $result_user_by_number_tickets->fetch(PDO::FETCH_ASSOC)) {
                      $rows_user_by_number_tickets[] = $row; 
                  }
                  
                  usort($rows_user_by_number_tickets, function($a, $b) { 
                      return $b['nombre_tickets'] - $a['nombre_tickets'];
                  });
                  
                  $counter = 0;
                  
                  foreach ($rows_user_by_number_tickets as $row) {
                      if ($counter < 10) {
                          echo "<tr>";
                          echo "<td>" . $row['nom'] . " " . $row['prenom'] . "</td>";
                          echo "<td>" . ($row['nombre_tickets'] ?? 0) . "</td>";
                          echo "</tr>";
                          $counter++;
                      } else {
                          break;
                      }
                  }
                  ?>

                </tbody>
            </table>
          </div>
          <ul class="cards">
            <li>
              <i class='bx bxs-wallet' ></i>
              <a href="../commercial/tickets_commercial.php">
                <span class="info">
                <h3 class="page-redirection"><i class="fa-solid fa-arrow-right"></i>    Commercial</h3>
                  <p>Gestion de ticket du commercial</p>
              </a>
              </span>
            </li>
            <li class="comptable-redirection">
              <i class='bx bx-money-withdraw' ></i>
              <a href="../comptable/dashboard_comptable.php">
                <span class="info">
                <h3 class="page-redirection"><i class="fa-solid fa-arrow-right"></i>    Comptable</h3>
                  <p>Dashboard du comptable</p>
              </a>
              </span>
            </li>
          </ul>
        </div>
      </div>
      </main>
    </div>
    <script>
    const categoryLabels = <?php echo $labelsJSON; ?>;
    const pricesPerCategory = <?php echo $prices_per_category_json; ?>;
    var chartData = <?php echo json_encode($data); ?>;

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