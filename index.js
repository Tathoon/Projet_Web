/* ------------------*/
/*                   */
/*                   */
/*      NAVBAR       */
/*                   */
/*                   */
/*-------------------*/

$(document).ready(function(){
  console.log("Document ready");
  
  // Vérifie l'URL pour l'état du menu et applique l'état en conséquence
  var urlParams = new URLSearchParams(window.location.search);
  var menuState = urlParams.get('menu');
  if (menuState === 'active') {
      $('.sidebar').addClass('active');
  }
  
  $('#sidebar_btn').click(function(){
      console.log("Bouton du menu cliqué");
      
      // Toggle class 'active' sur '.sidebar'
      $('.sidebar').toggleClass('active');
      
      // Récupère le nouvel état du menu
      var newState = $('.sidebar').hasClass('active') ? 'active' : 'inactive';
      console.log("Nouvel état du menu:", newState);
      
      // Met à jour l'URL avec le nouvel état du menu
      var newUrl = updateQueryStringParameter(window.location.href, 'menu', newState);
      console.log("Nouvelle URL:", newUrl);
      
      window.history.replaceState({}, '', newUrl);
      console.log("URL mise à jour");
  });
});

// Fonction pour mettre à jour les paramètres de l'URL
function updateQueryStringParameter(uri, key, value) {
  console.log("Fonction updateQueryStringParameter appelée");
  
  var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
  var separator = uri.indexOf('?') !== -1 ? "&" : "?";
  if (uri.match(re)) {
      return uri.replace(re, '$1' + key + "=" + value + '$2');
  }
  else {
      return uri + separator + key + "=" + value;
  }
}

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
