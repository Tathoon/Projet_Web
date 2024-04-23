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
      <a href="tickets_admin.php?menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" class="<?php echo isset($_GET['menu']) && $_GET['menu'] === 'active' ? 'active' : ''; ?>"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
      <a href="utilisateurs_admin.php?menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" class="<?php echo isset($_GET['menu']) && $_GET['menu'] === 'active' ? 'active' : ''; ?>"><i class="fas fa-table"></i><span>Utilisateurs</span></a>
      <a href="../autres/notifications.php?menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" class="<?php echo isset($_GET['menu']) && $_GET['menu'] === 'active' ? 'active' : ''; ?>"><i class="fa-solid fa-bell"></i><span>Notifications</span></a>
      <a href="../autres/settings.php?menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" class="<?php echo isset($_GET['menu']) && $_GET['menu'] === 'active' ? 'active' : ''; ?>"><i class="fas fa-sliders-h"></i><span>Settings</span></a>
      <a href="../../index.php?logout=true&menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" ><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
    </div>
  </div>

  <div class="sidebar">
    <div class="profile_info">
      <img src="../../images/user-icon.png" class="profile_image" alt="">
      <h4><?php echo ucfirst($_SESSION['nom']) . " " . ucfirst($_SESSION['prenom']) ; ?></h4>
    </div>
      <a href="#" class="active"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
      <a href="tickets_admin.php?menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" class="<?php echo isset($_GET['menu']) && $_GET['menu'] === 'active' ? 'active' : ''; ?>"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
      <a href="utilisateurs_admin.php?menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" class="<?php echo isset($_GET['menu']) && $_GET['menu'] === 'active' ? 'active' : ''; ?>"><i class="fas fa-table"></i><span>Utilisateurs</span></a>
      <a href="../autres/notifications.php?menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" class="<?php echo isset($_GET['menu']) && $_GET['menu'] === 'active' ? 'active' : ''; ?>"><i class="fa-solid fa-bell"></i><span>Notifications</span></a>
      <a href="../autres/settings.php?menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" class="<?php echo isset($_GET['menu']) && $_GET['menu'] === 'active' ? 'active' : ''; ?>"><i class="fas fa-sliders-h"></i><span>Settings</span></a>
      <a href="../../index.php?logout=true&menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" class="logout" ><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
  </div>


  <div class="content">
      <main>
        <div class="header">
          <h1>Dashboard</h1>
        </div>
        <ul class="cards">
          <li>
            <i class="bx bx-group"></i>
            <span class="info">
              <h3>7,373</h3>
              <p>Total Utilisateurs</p>
            </span>
          </li>
          <li>
            <i class="bx bx-movie"></i>
            <span class="info">
              <h3>9,373</h3>
              <p>Nombre Tickets</p>
            </span>
          </li>
          <li>
          <i class="bx bx-line-chart"></i>
            <span class="info">
              <h3>5,373</h3>
              <p>Bénéfice</p>
            </span>
          </li>
          <li>
            <i class="bx bx-dollar-circle"></i>
            <span class="info">
              <h3>$6,373</h3>
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
      </main>
    </div>
  <script type="text/javascript" src="../../index.js"></script>
</body>
</html>