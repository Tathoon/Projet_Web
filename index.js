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

/* GRAPHGIQUE */
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

// Données pour le graphique
const data = {
  labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet'],
  datasets: [{
    label: 'Ventes mensuelles',
    data: [65, 59, 80, 81, 56, 55, 40],
    backgroundColor: 'rgba(255, 99, 132, 0.2)',
    borderColor: 'rgba(255, 99, 132, 1)',
    borderWidth: 1
  }]
};

data.datasets.push(
    {
      label: 'Courbe 2',
      data: [70, 65, 80, 75, 60, 50, 45], // Données pour la courbe 2
      backgroundColor: 'rgba(255, 206, 86, 0.2)', // Couleur de fond de la zone
      borderColor: 'rgba(255, 206, 86, 1)', // Couleur de la bordure
      borderWidth: 1
    },
    {
      label: 'Courbe 3',
      data: [40, 55, 65, 70, 75, 80, 85], // Données pour la courbe 3
      backgroundColor: 'rgba(75, 192, 192, 0.2)', // Couleur de fond de la zone
      borderColor: 'rgba(75, 192, 192, 1)', // Couleur de la bordure
      borderWidth: 1
    },
    {
      label: 'Autre courbe',
      data: [45, 60, 75, 70, 65, 55, 50], // Les données pour l'autre courbe
      backgroundColor: 'rgba(54, 162, 235, 0.2)', // Couleur de fond de la zone
      borderColor: 'rgba(54, 162, 235, 1)', // Couleur de la bordure
      borderWidth: 1
    }
  );

// Configuration du graphique
const config = {
  type: 'line',
  data: data,
};

// Création du graphique
const myChart = new Chart(
  document.getElementById('myChart'),
  config
);

// Utilisation de vos variables existantes pour les données et la configuration
const myData = {
  labels: ['Label 1', 'Label 2', 'Label 3'],
  datasets: [{
    label: 'Camembert Example',
    data: [30, 40, 30], // Exemple de données
    backgroundColor: [
      'rgb(255, 99, 132)',
      'rgb(54, 162, 235)',
      'rgb(255, 205, 86)'
    ],
    hoverOffset: 4
  }]
};

const myConfig = {
  type: 'pie',
  data: myData,
};

// Création du camembert avec vos variables existantes
var myChartCAM = new Chart(
  document.getElementById('camembertChart'),
  myConfig
);
