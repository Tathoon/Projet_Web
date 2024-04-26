<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Admin</title>
  <link rel="stylesheet" href="../../styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <?php
    session_start();
    echo $_SESSION['nom'];

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

    $db = new PDO('mysql:host=localhost;dbname=e11event_bdd;charset=utf8mb4', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql_number_users = "SELECT COUNT(DISTINCT id_utilisateur) AS total_utilisateurs FROM utilisateur";
    $result_users = $db->query($sql_number_users);

    $sql_number_tickets = "SELECT COUNT(DISTINCT id_ticket) AS total_tickets FROM ticket";
    $result_tickets = $db->query($sql_number_tickets);

    $sql_depense = "SELECT COALESCE(SUM(prix), 0) AS total_depense FROM ticket";
    $result_depense = $db->query($sql_depense);

    $sql_tickets_attente = "SELECT COUNT(DISTINCT id_ticket) AS total_tickets_attente FROM ticket WHERE status = 3";
    $result_tickets_attente = $db->query($sql_tickets_attente);

    $sql_user_by_number_tickets = "SELECT nom, prenom, COUNT(id_ticket) AS nombre_tickets FROM utilisateur JOIN ticket ON utilisateur.id_utilisateur = ticket.utilisateur GROUP BY utilisateur.id_utilisateur ORDER BY nombre_tickets DESC LIMIT 5";
    $result_user_by_number_tickets = $db->query($sql_user_by_number_tickets);

    if ($result_users !== false && $result_tickets !== false && $result_depense !== false && $result_tickets_attente !== false){
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
      <a href="#" class="active"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
      <a href="tickets_admin.php"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
      <a href="utilisateurs_admin.php"><i class="fas fa-table"></i><span>Utilisateurs</span></a>
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
      <a href="#" class="active"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
      <a href="tickets_admin.php"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
      <a href="utilisateurs_admin.php"><i class="fas fa-table"></i><span>Utilisateurs</span></a>
      <a href="../autres/notifications.php"><i class="fa-solid fa-bell"></i></i><span>Notifications</span></a>
      <a href="../autres/settings.php"><i class="fas fa-sliders-h"></i><span>Settings</span></a>
      <a href="../../index.php?logout=true" class="logout" ><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
  </div>


  <div class="content">
      <main>
        <div class="header">
          <h1><i class="fa-solid fa-gauge"></i> Dashboard</h1>
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
          <li>
            <i class="bx bx-dollar-circle"></i>
            <span class="info">
              <h3><?php echo $total_depense; ?> €</h3>
              <p>Dépense</p>
            </span>
          </li>
        </ul>
        <div class="bottom_data">
          <div class="orders">
            <div class="header">
              <h3>Graphique</h3>
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
                  <h3>Pourcentage de dépense par catégories</h3>
              </div>
              <canvas id="camembertChart"></canvas>
          </div>
          <div class="most-ticket-user">
              <div class="header">
                  <h3>Commercials avec le plus de tickets</h3>
              </div>
              <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Nombre de tickets</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                   foreach ($rows_user_by_number_tickets as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['nom'] . " " . $row['prenom'] . "</td>";
                    echo "<td>" . $row['nombre_tickets'] . "</td>";
                    echo "</tr>";
                    }                
                    ?>
                </tbody>
            </table>
          </div>
      </div>
      </main>
    </div>
  <script type="text/javascript" src="../../index.js"></script>
</body>
</html>