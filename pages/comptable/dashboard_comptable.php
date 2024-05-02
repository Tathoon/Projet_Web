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




     $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');
     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Requête SQL pour récupérer les mois par ticket pour le statut 1
$sql_last_months_per_ticket_status_1 = "SELECT DISTINCT id_ticket, MONTH(date) AS dernier_mois 
FROM ticket 
WHERE status = 1
ORDER BY dernier_mois DESC";
$result_last_months_per_ticket_status_1 = $db->query($sql_last_months_per_ticket_status_1);

$months_per_ticket_status_1 = array();

while ($row = $result_last_months_per_ticket_status_1->fetch(PDO::FETCH_ASSOC)) {
$months_per_ticket_status_1[$row['id_ticket']] = $row['dernier_mois'];
}

$monthsJSON_status_1 = json_encode($months_per_ticket_status_1);

// Requête SQL pour récupérer les mois par ticket pour le statut 2
$sql_last_months_per_ticket_status_2 = "SELECT DISTINCT id_ticket, MONTH(date) AS dernier_mois 
FROM ticket 
WHERE status = 2
ORDER BY dernier_mois DESC";
$result_last_months_per_ticket_status_2 = $db->query($sql_last_months_per_ticket_status_2);

$months_per_ticket_status_2 = array();

while ($row = $result_last_months_per_ticket_status_2->fetch(PDO::FETCH_ASSOC)) {
$months_per_ticket_status_2[$row['id_ticket']] = $row['dernier_mois'];
}

$monthsJSON_status_2 = json_encode($months_per_ticket_status_2);






// Exécutez la requête SQL pour récupérer les prix des tickets par mois
$sql_prix_par_mois = "SELECT MONTH(date) AS mois, SUM(prix) AS prix_total
                      FROM ticket
                      WHERE status = 1
                      GROUP BY MONTH(date)";
$result_prix_par_mois = $db->query($sql_prix_par_mois);

$prix_par_mois = array();

// Parcourez les résultats de la requête et stockez les prix dans un tableau associatif
while ($row = $result_prix_par_mois->fetch(PDO::FETCH_ASSOC)) {
    $prix_par_mois[$row['mois']] = $row['prix_total'];
}

// Convertissez le tableau associatif en JSON pour l'utiliser dans JavaScript
$prixParMoisJSON = json_encode($prix_par_mois);











 
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
      <a href="#" class="active"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
      <a href="tickets_comptable.php"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
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
       <a href="#" class="active"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
       <a href="tickets_comptable.php"><i class="fa-solid fa-ticket"></i><span>Tickets</span></a>
       <a href="../autres/notifications.php"><i class="fa-solid fa-bell"></i><span>Notifications</span></a>
    <a href="../autres/settings.php"><i class="fas fa-sliders-h"></i><span>Paramètres</span></a>
    <a href="../../index.php?logout=true" class="logout-comptable" ><i class="fa-solid fa-right-from-bracket"></i><span>Déconnexion</span></a>
  </div>
 


   <body>
    <div class="content">
        <main>
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
            <div class="header">
                <h1><i class="fa-solid fa-gauge"></i> Dashboard</h1>
            </div>
            <div class="bottom_data">
                <div class="orders">
                    <div class="header">
                        <h3>Graphique des dépenses</h3>
                    </div>
                    <!-- Canvas pour le graphique des dépenses -->
                    <canvas id="ticketCountByMonthChart" class="graph"></canvas>
                </div>
                <div class="orders">
                    <div class="header">
                        <h3>Graphique des tickets</h3>
                    </div>
                    <!-- Canvas pour le graphique des tickets -->
                    <canvas id="prixParMoisChart" class="graph"></canvas>
                </div>
            </div>
        </main>
    </div>



 
   
     <script>





document.addEventListener('DOMContentLoaded', function() {
    // Récupération des données des derniers mois par ticket pour le statut 1 depuis PHP
    const monthsPerTicketStatus1 = <?php echo $monthsJSON_status_1; ?>;

    // Récupération des données des derniers mois par ticket pour le statut 2 depuis PHP
    const monthsPerTicketStatus2 = <?php echo $monthsJSON_status_2; ?>;

    // Fonction pour compter le nombre de tickets par mois pour chaque statut
    function countTicketsByMonth(monthsPerTicket) {
        const ticketsCountByMonth = {};
        for (const ticketId in monthsPerTicket) {
            const month = monthsPerTicket[ticketId];
            if (ticketsCountByMonth[month]) {
                ticketsCountByMonth[month]++;
            } else {
                ticketsCountByMonth[month] = 1;
            }
        }
        return ticketsCountByMonth;
    }

    // Compter le nombre de tickets par mois pour le statut 1
    const ticketsCountByMonthStatus1 = countTicketsByMonth(monthsPerTicketStatus1);
    // Compter le nombre de tickets par mois pour le statut 2
    const ticketsCountByMonthStatus2 = countTicketsByMonth(monthsPerTicketStatus2);

    // Préparation des données pour le graphique
    const months = Object.keys(ticketsCountByMonthStatus1); // Utilisez les mois du statut 1 comme base

    // Création des données pour chaque statut
    const ticketCountsStatus1 = Object.values(ticketsCountByMonthStatus1);
    const ticketCountsStatus2 = Object.values(ticketsCountByMonthStatus2);

    // Création du graphique
    var ctx = document.getElementById('ticketCountByMonthChart').getContext('2d');
    var ticketCountByMonthChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Nombre de tickets accépté',
                data: ticketCountsStatus1,
                borderColor: 'blue',
                borderWidth: 2
            },
            {
                label: 'Nombre de tickets en attente',
                data: ticketCountsStatus2,
                borderColor: 'green',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
});




document.addEventListener('DOMContentLoaded', function() {
    // Récupération des données des prix par mois depuis PHP
    const prixParMois = <?php echo $prixParMoisJSON; ?>;

    // Préparation des données pour le graphique
    const mois = Object.keys(prixParMois);
    const prixTotal = Object.values(prixParMois);

    // Création du graphique
    var ctx = document.getElementById('prixParMoisChart').getContext('2d');
    var prixParMoisChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: mois,
            datasets: [{
                label: 'Prix total par mois',
                data: prixTotal,
                borderColor: 'blue',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
});













const categoryLabels = <?php echo $labelsJSON; ?>;
    const pricesPerCategory = <?php echo $prices_per_category_json; ?>;
    var chartData = <?php echo json_encode($data); ?>;

    // Graphique des dépenses
    var ctx1 = document.getElementById('totalExpensesChart').getContext('2d');
    var totalExpensesChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: categoryLabels, // Utilisez les catégories comme étiquettes
            datasets: [{
                label: 'Dépenses par catégorie',
                data: pricesPerCategory, // Utilisez les prix par catégorie comme données
                borderColor: 'blue',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
        }
    });

    // Graphique des tickets
    var ctx2 = document.getElementById('totalTicketsChart').getContext('2d');
    var totalTicketsChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: Object.keys(chartData), // Utilisez les clés de l'objet chartData comme étiquettes
            datasets: [{
                label: 'Nombre total de tickets',
                data: Object.values(chartData), // Utilisez les valeurs de l'objet chartData comme données
                backgroundColor: 'green'
            }]
        },
        options: {
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
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