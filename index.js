/* ------------------*/
/*                   */
/*                   */
/*      NAVBAR       */
/*                   */
/*                   */
/*-------------------*/

$(document).ready(function(){
  $('.nav_btn').click(function(){
    $('.mobile_nav_items').toggleClass('active');
  });
});


/* ------------------*/
/*                   */
/*                   */
/*     PAGE ADMIN    */
/*                   */
/*                   */
/*-------------------*/

/* CALENDRIER */
  let date = new Date();

function renderCalendar() {
    date.setDate(1);

    const monthDays = document.getElementById('calendar-body');
    const month = document.getElementById('month');
    const daysElement = document.getElementById('days');

    const lastDay = new Date(
        date.getFullYear(),
        date.getMonth() + 1,
        0
    ).getDate();

    const prevLastDay = new Date(
        date.getFullYear(),
        date.getMonth(),
        0
    ).getDate();

    const firstDayIndex = date.getDay();

    const lastDayIndex = new Date(
        date.getFullYear(),
        date.getMonth() + 1,
        0
    ).getDay();

    const nextDays = 7 - lastDayIndex - 1;

    const months = [
        'Janvier',
        'Février',
        'Mars',
        'Avril',
        'Mai',
        'Juin',
        'Jullet',
        'Août',
        'Septembre',
        'Octobre',
        'Novembre',
        'Décembre'
    ];

    const days = [
        'D',
        'L',
        'M',
        'M',
        'J',
        'V',
        'S'
    ];

    month.innerText = `${months[date.getMonth()]} ${date.getFullYear()}`;
    daysElement.innerHTML = days.map(day => `<div>${day}</div>`).join('');

    let dates = '';

    for (let x = firstDayIndex; x > 0; x--) {
        dates += `<div class='prev-date'>${prevLastDay - x + 1}</div>`;
    }

    for (let i = 1; i <= lastDay; i++) {
        if (
            i === new Date().getDate() &&
            date.getMonth() === new Date().getMonth() &&
            date.getFullYear() === new Date().getFullYear()
        ) {
            dates += `<div class='today'>${i}</div>`;
        } else {
            dates += `<div>${i}</div>`;
        }
    }

    for (let j = 1; j <= nextDays; j++) {
        dates += `<div class='next-date'>${j}</div>`;
    }
    monthDays.innerHTML = dates;
}

document.getElementById('month-prev').addEventListener('click', () => {
    document.getElementById('calendar-body').classList.add('fade-out');
    setTimeout(() => {
        date.setMonth(date.getMonth() - 1);
        renderCalendar();
        document.getElementById('calendar-body').classList.remove('fade-out');
    }, 500);
});

document.getElementById('month-next').addEventListener('click', () => {
    document.getElementById('calendar-body').classList.add('fade-out');
    setTimeout(() => {
        date.setMonth(date.getMonth() + 1);
        renderCalendar();
        document.getElementById('calendar-body').classList.remove('fade-out');
    }, 500);
});

renderCalendar();

console.log(chartData);

/* GRAPHIQUE */

// Données pour le graphique
const data = {
  labels: ['Total Utilisateurs', 'Nombre de Tickets', 'Dépense', 'Tickets en Attente'],
  datasets: [
    {
      label: 'Total Utilisateurs',
      data: [chartData.total_utilisateurs, null, null, null,],
      backgroundColor: 'rgba(54, 162, 235, 0.2)',
      borderColor: 'rgba(54, 162, 235, 1)',
      borderWidth: 1
    },
    {
      label: 'Nombre de Tickets',
      data: [null, chartData.total_tickets, null, null],
      backgroundColor: 'rgba(255, 206, 86, 0.2)',
      borderColor: 'rgba(255, 206, 86, 1)',
      borderWidth: 1
    },
    {
      label: 'Dépense',
      data: [null, null, chartData.total_depense, null],
      backgroundColor: 'rgba(75, 192, 192, 0.2)',
      borderColor: 'rgba(75, 192, 192, 1)',
      borderWidth: 1
    },
    {
      label: 'Tickets en Attente',
      data: [null, null, null, chartData.total_tickets_attente],
      backgroundColor: 'rgba(255, 99, 132, 0.2)',
      borderColor: 'rgba(255, 99, 132, 1)',
      borderWidth: 1
    }
  ]
};


const config = {
  type: 'bar',
  data: data,
  options: {
    scales: {
      y: {
        type: 'logarithmic',
        ticks: {
          callback: function (value, index, values) {
            return Number(value.toString());
          }
        }
      }
    },
    barPercentage: 11,
    categoryPercentage: 0.2,
  }
};


// Création du graphique
const myChart = new Chart(
  document.getElementById('myChart'),
  config
);



// Vérifier si les données sont correctement récupérées
const dataAvailable = categoryLabels.length > 0 && pricesPerCategory.length > 0;

if (dataAvailable) {
  const myData = {
    labels: categoryLabels, 
    datasets: [{
      label: 'Dépense total ',
      data: pricesPerCategory,
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
      hoverOffset: 4
    }]
  };

  if (typeof Chart !== 'undefined') {
    var myChartCAM = new Chart(
      document.getElementById('camembertChart'),
      { type: 'pie', data: myData }
    );
  } else {
    console.error('La bibliothèque Chart.js n\'est pas chargée.');
  }
} else {
  console.error('Aucune donnée disponible pour afficher le graphique.');
}



