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






///////////////////////////////////////////////conexion base de donnée//////////////////////////




     $db = new PDO("mysql:host=e11event.mysql.database.azure.com;dbname=e11event_bdd", 'Tathoon', '*7d7K7yt&Q8t#!');

     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);








///////////////////////////////////////////////graphique nombre de ticket en attente et validé par mois////////////////////////




$sql_last_months_per_ticket_status_1 = "SELECT DISTINCT id_ticket, MONTH(date) AS dernier_mois

FROM ticket 

WHERE status = 1

ORDER BY dernier_mois DESC";

$sql_last_months_per_ticket_status_2 = "SELECT DISTINCT id_ticket, MONTH(date) AS dernier_mois

FROM ticket 

WHERE status = 3

ORDER BY dernier_mois DESC";






$result_last_months_per_ticket_status_1 = $db->query($sql_last_months_per_ticket_status_1);

$result_last_months_per_ticket_status_2 = $db->query($sql_last_months_per_ticket_status_2);






$months_per_ticket_status_1 = array();

$months_per_ticket_status_2 = array();




while ($row = $result_last_months_per_ticket_status_1->fetch(PDO::FETCH_ASSOC)) {

$months_per_ticket_status_1[$row['id_ticket']] = $row['dernier_mois'];

}

while ($row = $result_last_months_per_ticket_status_2->fetch(PDO::FETCH_ASSOC)) {

    $months_per_ticket_status_2[$row['id_ticket']] = $row['dernier_mois'];

    }

    




$monthsJSON_status_1 = json_encode($months_per_ticket_status_1);

$monthsJSON_status_2 = json_encode($months_per_ticket_status_2);










////////////////////////////////////// graphique dépense totale par mois///////////////////////






$sql_prix_par_mois = "SELECT MONTH(date) AS mois, SUM(prix) AS prix_total

                      FROM ticket

                      WHERE status = 1

                      GROUP BY MONTH(date)";




$result_prix_par_mois = $db->query($sql_prix_par_mois);

$prix_par_mois = array();

while ($row = $result_prix_par_mois->fetch(PDO::FETCH_ASSOC)) {

    $prix_par_mois[$row['mois']] = $row['prix_total'];

}

$prixParMoisJSON = json_encode($prix_par_mois);










//////////////////////////////// pie chart Dépense totale par catégories////////////////////




    $sql_tickets_per_category = "SELECT tc.nom_categorie AS category, COUNT(t.id_ticket) AS ticket_count

                                FROM ticket_categorie tc 

                                LEFT JOIN ticket t ON tc.id_category = t.categorie

                                WHERE t.status = 1 

                                GROUP BY tc.nom_categorie";




    $result_tickets_per_category = $db->query($sql_tickets_per_category);

    $ticketCountsPerCategory = array();

    while ($row = $result_tickets_per_category->fetch(PDO::FETCH_ASSOC)) {

        $ticketCountsPerCategory[$row['category']] = $row['ticket_count'];

    }

    $ticketCountsPerCategoryJSON = json_encode($ticketCountsPerCategory);










    //////////////////////pie chart Nombre de tickets par catégories////////////////////




    $sql_total_price_per_category = "SELECT tc.nom_categorie AS category, SUM(t.prix) AS total_price

                                    FROM ticket_categorie tc 

                                    LEFT JOIN ticket t ON tc.id_category = t.categorie

                                    WHERE t.status = 1 

                                    GROUP BY tc.nom_categorie";




    $result_total_price_per_category = $db->query($sql_total_price_per_category);

    $totalPricePerCategory = array();

    while ($row = $result_total_price_per_category->fetch(PDO::FETCH_ASSOC)) {

        $totalPricePerCategory[$row['category']] = $row['total_price'];

    }

    $totalPricePerCategoryJSON = json_encode($totalPricePerCategory);










////////////////////////////////////////

 

     $sql_number_users = "SELECT COUNT(DISTINCT id_utilisateur) AS total_utilisateurs FROM utilisateur WHERE role = 3";

     $result_users = $db->query($sql_number_users);

 

     $sql_number_tickets = "SELECT COUNT(DISTINCT id_ticket) AS total_tickets FROM ticket WHERE MONTH(date) = (SELECT MAX(MONTH(date)) FROM ticket)";

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

    <a href="../autres/settings.php"><i class="fas fa-sliders-h"></i><span>Paramètres</span></a>

    <a href="../../index.php?logout=true" class="logout-comptable" ><i class="fa-solid fa-right-from-bracket"></i><span>Déconnexion</span></a>

</div>

 














<body>

    <div class="content">

        <main>




            <div class="header">

            <h1><i class="fa-solid fa-gauge"></i> Dashboard Comptable</h1>

            </div>

            <ul class="cards">

                <li>

                    <i class="bx bx-group"></i>

                    <span class="info">

                        <h3><?php echo $total_utilisateurs; ?></h3>

                        <p>Nombre de comptables</p>

                    </span>

                </li>

                <li>

                    <i class="bx bx-movie"></i>

                    <span class="info">

                        <h3><?php echo $total_tickets; ?></h3>

                        <p>Nombre Tickets sur le dernier mois</p>

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

                        <h3><?php echo number_format($total_depense, 2); ?> €</h3>

                        <p>Dépenses</p>

                    </span>

                </li>

            </ul>




            <div class="bottom_data">

                <div class="orders">

                    <div class="header">

                        <h3>Graphique des tickets</h3>

                    </div>

                    <canvas id="ticketCountByMonthChart" class="graph"></canvas>

                </div>

                <div class="orders">

                    <div class="header">

                        <h3>Graphique des dépenses</h3>

                    </div>

                    <canvas id="prixParMoisChart" class="graph"></canvas>

                </div>

            </div>






            <div class="charts-container">

                <div class="pie-chart">

                    <div class="header">

                    <h3>Dépenses totales par catégories</h3>

                    </div>

                    <canvas id="totalPriceByCategoryChart"></canvas>

                </div>

                <div class="pie-chart">

                    <div class="header">

                    <h3>Nombre de tickets par catégories</h3>

                    </div>

                    <canvas id="ticketCountByCategoryChart"></canvas>

                </div>

                <ul class="cards">

                    <li>

                    <i class='bx bxs-wallet' ></i>

                        <a href="tickets_comptable.php">

                            <span class="info">

                                <h3 class="page-redirection"><i class="fa-solid fa-arrow-right"></i>    Tickets</h3>

                                <p>Gestion de ticket du comptable</p>

                            </span>

                        </a>

                    </li>

                </ul>

            </div>




        </main>

    </div>








 

   

<script>

    function updateChartLegendColors(chart, labelColor) {

    chart.options.plugins.legend.labels.color = labelColor;

    chart.update();

}




//////////// Graphique nombre de ticket en attente et validé par mois//////////////

document.addEventListener('DOMContentLoaded', function() {

    const darkModeSwitch = document.getElementById('dark-mode-toggle');

    let labelColor = darkModeSwitch.checked ? 'white' : 'black';




    const monthsPerTicketStatus1 = <?php echo $monthsJSON_status_1; ?>;

    const monthsPerTicketStatus2 = <?php echo $monthsJSON_status_2; ?>;




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




    const ticketsCountByMonthStatus1 = countTicketsByMonth(monthsPerTicketStatus1);

    const ticketsCountByMonthStatus2 = countTicketsByMonth(monthsPerTicketStatus2);




    const months = Object.keys(ticketsCountByMonthStatus1).map(month => {

        return new Intl.DateTimeFormat('default', { month: 'long' }).format(new Date(2024, month - 1, 1));

    });




    const ticketCountsStatus1 = Object.values(ticketsCountByMonthStatus1);

    const ticketCountsStatus2 = Object.values(ticketsCountByMonthStatus2);




    var ctx = document.getElementById('ticketCountByMonthChart').getContext('2d');

    var ticketCountByMonthChart = new Chart(ctx, {

        type: 'line',

        data: {

            labels: months,

            datasets: [{

                label: 'Nombre de tickets accepté',

                data: ticketCountsStatus1,

                borderColor: 'rgba(242, 86, 86, 0.8)',

                backgroundColor: 'rgba(242, 86, 86, 0.2)',

                borderWidth: 2

            },

            {

                label: 'Nombre de tickets en attente',

                data: ticketCountsStatus2,

                borderColor: 'rgba(63, 187, 255, 0.8)',

                backgroundColor: 'rgba(63, 187, 255, 0.2)',

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




    darkModeSwitch.addEventListener('change', function() {

        labelColor = this.checked ? 'white' : 'black';

        updateChartLegendColors(ticketCountByMonthChart, labelColor);

    });

});








//////////////////////////// Graphique dépense totale par mois//////////////////////

document.addEventListener('DOMContentLoaded', function() {

    const darkModeSwitch = document.getElementById('dark-mode-toggle');

    let labelColor = darkModeSwitch.checked ? 'white' : 'black';




    const prixParMois = <?php echo $prixParMoisJSON; ?>;

    const mois = Object.keys(prixParMois);

    const prixTotal = Object.values(prixParMois);

    const months = mois.map(month => {

        return new Intl.DateTimeFormat('default', { month: 'long' }).format(new Date(2024, month - 1, 1));

    });




    var ctx = document.getElementById('prixParMoisChart').getContext('2d');

    var prixParMoisChart = new Chart(ctx, {

        type: 'line',

        data: {

            labels: months,

            datasets: [{

                label: 'Prix total par mois',

                data: prixTotal,

                borderColor: 'rgba(39, 245, 92, 0.59)',

                backgroundColor: 'rgba(39, 245, 92, 0.2)',

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




    darkModeSwitch.addEventListener('change', function() {

        labelColor = this.checked ? 'white' : 'black';

        prixParMoisChart.options.plugins.legend.labels.color = labelColor;

        prixParMoisChart.update();

    });

});








////////////////////////// Pie chart Dépense totale par catégories////////////////////

document.addEventListener('DOMContentLoaded', function() {

    const darkModeSwitch = document.getElementById('dark-mode-toggle');

    let labelColor = darkModeSwitch.checked ? 'white' : 'black';




    const totalPricePerCategory = <?php echo $totalPricePerCategoryJSON; ?>;

    const categories = Object.keys(totalPricePerCategory);

    const totalPrices = Object.values(totalPricePerCategory);




    var ctx = document.getElementById('totalPriceByCategoryChart').getContext('2d');

    var totalPriceByCategoryChart = new Chart(ctx, {

        type: 'polarArea',

        data: {

            labels: categories,

            datasets: [{

                label: 'Prix total par catégorie',

                data: totalPrices,

                backgroundColor: [

                    'rgb(254, 205, 211)', 

                    'rgb(207, 232, 255)', 

                    'rgb(187, 247, 208)', 

                    'rgb(252, 252, 174)', 

                    'rgb(140, 162, 245)', 

                    'rgb(252, 201, 146)',  

                    'rgb(250, 175, 217)',

                    'rgba(173, 148, 235)'

                ],

                borderWidth: 1

            }]

        },

        options: {

            responsive: true,

            legend: {

                position: 'right' 

            }

        }

    });




    darkModeSwitch.addEventListener('change', function() {

        labelColor = this.checked ? 'white' : 'black';

        totalPriceByCategoryChart.options.plugins.legend.labels.color = labelColor;

        totalPriceByCategoryChart.update();

    });

});






/////////////////////// Pie chart Nombre de tickets par catégories//////////////////////////

document.addEventListener('DOMContentLoaded', function() {

    const darkModeSwitch = document.getElementById('dark-mode-toggle');

    let labelColor = darkModeSwitch.checked ? 'white' : 'black';




    const ticketCountsPerCategory = <?php echo $ticketCountsPerCategoryJSON; ?>;

    const categories = Object.keys(ticketCountsPerCategory);

    const ticketCounts = Object.values(ticketCountsPerCategory);




    var ctx = document.getElementById('ticketCountByCategoryChart').getContext('2d');

    var ticketCountByCategoryChart = new Chart(ctx, {

        type: 'doughnut',

        data: {

            labels: categories,

            datasets: [{

                label: 'Nombre de tickets par catégorie',

                data: ticketCounts,

                backgroundColor: [

                    'rgb(254, 205, 211)', 

                    'rgb(207, 232, 255)', 

                    'rgb(187, 247, 208)', 

                    'rgb(252, 252, 174)', 

                    'rgb(140, 162, 245)', 

                    'rgb(252, 201, 146)',  

                    'rgb(250, 175, 217)',

                    'rgba(173, 148, 235)'

                ],

                borderWidth: 1

            }]

        },

        options: {

            responsive: true,

            legend: {

                position: 'right'

            },

            plugins: {

                legend: {

                    labels: {

                        color: labelColor 

                    }

                }

            }

        }

    });




    darkModeSwitch.addEventListener('change', function() {

        labelColor = this.checked ? 'white' : 'black';

        ticketCountByCategoryChart.options.plugins.legend.labels.color = labelColor;

        ticketCountByCategoryChart.update();

    });

});




















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
