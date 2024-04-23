<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tickets - Admin</title>
  <link rel="stylesheet" href="../../styles.css">
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
      <a href="dashboard_admin.php?menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" class="<?php echo isset($_GET['menu']) && $_GET['menu'] === 'active' ? 'active' : ''; ?>"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
      <a href="#" class="active"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
      <a href="utilisateurs_admin.php?menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" class="<?php echo isset($_GET['menu']) && $_GET['menu'] === 'active' ? 'active' : ''; ?>"><i class="fas fa-table"></i><span>Utilisateurs</span></a>
      <a href="../autres/notifications.php?menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" class="<?php echo isset($_GET['menu']) && $_GET['menu'] === 'active' ? 'active' : ''; ?>"><i class="fa-solid fa-bell"></i><span>Notifications</span></a>
      <a href="../autres/settings.php?menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" class="<?php echo isset($_GET['menu']) && $_GET['menu'] === 'active' ? 'active' : ''; ?>"><i class="fas fa-sliders-h"></i><span>Settings</span></a>
      <a href="../../index.php?logout=true&menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>"><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
    </div>
  </div>

  <div class="sidebar">
    <div class="profile_info">
      <img src="../../images/user-icon.png" class="profile_image" alt="">
      <h4><?php echo ucfirst($_SESSION['nom']) . " " . ucfirst($_SESSION['prenom']) ; ?></h4>
    </div>
    <a href="dashboard_admin.php?menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" class="<?php echo isset($_GET['menu']) && $_GET['menu'] === 'active' ? 'active' : ''; ?>"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
    <a href="#" class="active"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
    <a href="utilisateurs_admin.php?menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" class="<?php echo isset($_GET['menu']) && $_GET['menu'] === 'active' ? 'active' : ''; ?>"><i class="fas fa-table"></i><span>Utilisateurs</span></a>
    <a href="../autres/notifications.php?menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" class="<?php echo isset($_GET['menu']) && $_GET['menu'] === 'active' ? 'active' : ''; ?>"><i class="fa-solid fa-bell"></i><span>Notifications</span></a>
    <a href="../autres/settings.php?menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" class="<?php echo isset($_GET['menu']) ? $_GET['menu'] === 'active' : ''; ?>"><i class="fas fa-sliders-h"></i><span>Settings</span></a>
    <a href="../../index.php?logout=true&menu=<?php echo isset($_GET['menu']) ? $_GET['menu'] : 'inactive'; ?>" class="logout" ><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
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
                <tr>
                  <td>103326</td>
                  <td class="img_content">
                    <img src="../../images/user-icon.png" alt="" />
                    <p>John Doe</p>
                  </td>
                  <td>admin@onlineittuts.com</td>
                  <td>6th Sep 2025</td>
                  <td>Lyon</td>
                  <td>caca</td>
                  <td>500€</td>
                  <td>il était dur</td>
                  <td><span class="status completed">Complété</span></td>
                </tr>
                <tr>
                  <td>103626</td>
                  <td class="img_content">
                    <img src="../../images/user-icon.png" alt="" />
                    <p>Jullee Smith</p>
                  </td>
                  <td>admin@onlineittuts.com</td>
                  <td>6th Sep 2025</td>
                  <td>Lyon</td>
                  <td>caca</td>
                  <td>500€</td>
                  <td>il était dur</td>
                  <td><span class="status pending">En attente</span></td>
                </tr>
                <tr>
                  <td>103926</td>
                  <td class="img_content">
                    <img src="../../images/user-icon.png" alt="" />
                    <p>Willims</p>
                  </td>
                  <td>admin@onlineittuts.com</td>
                  <td>6th Sep 2025</td>
                  <td>Lyon</td>
                  <td>caca</td>
                  <td>500€</td>
                  <td>il était dur</td>
                  <td><span class="status processing">Rejeté</span></td>
                </tr>
                <tr>
                  <td>103326</td>
                  <td class="img_content">
                    <img src="../../images/user-icon.png" alt="" />
                    <p>John Doe</p>
                  </td>
                  <td>admin@onlineittuts.com</td>
                  <td>6th Sep 2025</td>
                  <td>Lyon</td>
                  <td>caca</td>
                  <td>500€</td>
                  <td>il était dur</td>
                  <td><span class="status completed">Complété</span></td>
                </tr>
                <tr>
                  <td>103626</td>
                  <td class="img_content">
                    <img src="../../images/user-icon.png" alt="" />
                    <p>Jullee Smith</p>
                  </td>
                  <td>admin@onlineittuts.com</td>
                  <td>6th Sep 2025</td>
                  <td>Lyon</td>
                  <td>caca</td>
                  <td>500€</td>
                  <td>il était dur</td>
                  <td><span class="status pending">En attente</span></td>
                </tr>
                <tr>
                  <td>103326</td>
                  <td class="img_content">
                    <img src="../../images/user-icon.png" alt="" />
                    <p>John Doe</p>
                  </td>
                  <td>admin@onlineittuts.com</td>
                  <td>6th Sep 2025</td>
                  <td>Lyon</td>
                  <td>caca</td>
                  <td>500€</td>
                  <td>il était dur</td>
                  <td><span class="status completed">Complété</span></td>
                </tr>
                <tr>
                  <td>103626</td>
                  <td class="img_content">
                    <img src="../../images/user-icon.png" alt="" />
                    <p>Jullee Smith</p>
                  </td>
                  <td>admin@onlineittuts.com</td>
                  <td>6th Sep 2025</td>
                  <td>Lyon</td>
                  <td>caca</td>
                  <td>500€</td>
                  <td>il était dur</td>
                  <td><span class="status pending">En attente</span></td>
                </tr>
                <tr>
                  <td>103926</td>
                  <td class="img_content">
                    <img src="../../images/user-icon.png" alt="" />
                    <p>Willims</p>
                  </td>
                  <td>admin@onlineittuts.com</td>
                  <td>6th Sep 2025</td>
                  <td>Lyon</td>
                  <td>caca</td>
                  <td>500€</td>
                  <td>il était dur</td>
                  <td><span class="status processing">Rejeté</span></td>
                </tr>
                <tr>
                  <td>103326</td>
                  <td class="img_content">
                    <img src="../../images/user-icon.png" alt="" />
                    <p>John Doe</p>
                  </td>
                  <td>admin@onlineittuts.com</td>
                  <td>6th Sep 2025</td>
                  <td>Lyon</td>
                  <td>caca</td>
                  <td>500€</td>
                  <td>il était dur</td>
                  <td><span class="status completed">Complété</span></td>
                </tr>
                <tr>
                  <td>103626</td>
                  <td class="img_content">
                    <img src="../../images/user-icon.png" alt="" />
                    <p>Jullee Smith</p>
                  </td>
                  <td>admin@onlineittuts.com</td>
                  <td>6th Sep 2025</td>
                  <td>Lyon</td>
                  <td>caca</td>
                  <td>500€</td>
                  <td>il était dur</td>
                  <td><span class="status pending">En attente</span></td>
                </tr>
                <tr>
                  <td>103326</td>
                  <td class="img_content">
                    <img src="../../images/user-icon.png" alt="" />
                    <p>John Doe</p>
                  </td>
                  <td>admin@onlineittuts.com</td>
                  <td>6th Sep 2025</td>
                  <td>Lyon</td>
                  <td>caca</td>
                  <td>500€</td>
                  <td>il était dur</td>
                  <td><span class="status completed">Complété</span></td>
                </tr>
                <tr>
                  <td>103626</td>
                  <td class="img_content">
                    <img src="../../images/user-icon.png" alt="" />
                    <p>Jullee Smith</p>
                  </td>
                  <td>admin@onlineittuts.com</td>
                  <td>6th Sep 2025</td>
                  <td>Lyon</td>
                  <td>caca</td>
                  <td>500€</td>
                  <td>il était dur</td>
                  <td><span class="status pending">En attente</span></td>
                </tr>
                <tr>
                  <td>103926</td>
                  <td class="img_content">
                    <img src="../../images/user-icon.png" alt="" />
                    <p>Willims</p>
                  </td>
                  <td>admin@onlineittuts.com</td>
                  <td>6th Sep 2025</td>
                  <td>Lyon</td>
                  <td>caca</td>
                  <td>500€</td>
                  <td>il était dur</td>
                  <td><span class="status processing">Rejeté</span></td>
                </tr>
                <tr>
                  <td>103326</td>
                  <td class="img_content">
                    <img src="../../images/user-icon.png" alt="" />
                    <p>John Doe</p>
                  </td>
                  <td>admin@onlineittuts.com</td>
                  <td>6th Sep 2025</td>
                  <td>Lyon</td>
                  <td>caca</td>
                  <td>500€</td>
                  <td>il était dur</td>
                  <td><span class="status completed">Complété</span></td>
                </tr>
                <tr>
                  <td>103626</td>
                  <td class="img_content">
                    <img src="../../images/user-icon.png" alt="" />
                    <p>Jullee Smith</p>
                  </td>
                  <td>admin@onlineittuts.com</td>
                  <td>6th Sep 2025</td>
                  <td>Lyon</td>
                  <td>caca</td>
                  <td>500€</td>
                  <td>il était dur</td>
                  <td><span class="status pending">En attente</span></td>
                </tr>
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