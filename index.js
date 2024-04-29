/* ------------------*/
/*                   */
/*                   */
/*     DARK MODE     */
/*                   */
/*                   */
/*-------------------*/

window.addEventListener('DOMContentLoaded', function() {
  var avatar = document.querySelector('img[src="../../images/avatar/dancing-toothless-tothless.gif"]');
  var otherAvatars = document.querySelectorAll('img:not([src="../../images/avatar/dancing-toothless-tothless.gif"])');
  var isPlaying = localStorage.getItem('isPlaying') === 'true';
  var audioPath;
  if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    audioPath = '/Projet_Web/images/music/driftveil.mp3';
  } else {
    audioPath = '/images/music/driftveil.mp3';
  }
  
  var audio = new Audio(audioPath);
  audio.volume = 0.01;
  audio.loop = true;

  var currentTime = localStorage.getItem('currentTime');
  if (currentTime) {
    audio.currentTime = parseFloat(currentTime);
  }

  if (isPlaying) {
    audio.play();
  }

  if (avatar) {
    document.addEventListener('click', function(event) {
      if (event.target.src === avatar.src) {
        if (!isPlaying) {
          audio.play();
          isPlaying = true;
        } else {
          audio.pause();
          audio.currentTime = 0;
          isPlaying = false;
        }
        localStorage.setItem('isPlaying', isPlaying);
      }
    });
  }

  otherAvatars.forEach(function(otherAvatar) {
    otherAvatar.addEventListener('click', function() {
      if (isPlaying) {
        audio.pause();
        audio.currentTime = 0;
        isPlaying = false;
        localStorage.setItem('isPlaying', isPlaying);
      }
    });
  });

  window.addEventListener('beforeunload', function() {
    localStorage.setItem('currentTime', audio.currentTime);
  });
});

document.addEventListener('DOMContentLoaded', function() {

  function applyDarkModeOnLoad() {
      const isDarkMode = localStorage.getItem('darkMode') === 'true';
      const body = document.body;

      const darkModeToggle = document.getElementById('dark-mode-toggle');
      if (darkModeToggle) {
          darkModeToggle.checked = isDarkMode;
      }

      if (isDarkMode) {
          body.classList.add('dark-mode');
      } else {
          body.classList.remove('dark-mode');
      }
  }

  applyDarkModeOnLoad();

  function toggleDarkMode() {
      const body = document.body;
      const isDarkMode = body.classList.toggle('dark-mode');

      localStorage.setItem('darkMode', isDarkMode);
  }

  const darkModeToggle = document.getElementById('dark-mode-toggle');
  if (darkModeToggle) {
      darkModeToggle.addEventListener('change', toggleDarkMode);
  }
});

document.addEventListener('DOMContentLoaded', function() {

  function changeImage() {
      const logoImage = document.getElementById('logo-image');
      const darkModeToggle = document.getElementById('dark-mode-toggle');

      const isDarkMode = darkModeToggle.checked;


      if (isDarkMode) {
          logoImage.src = "images/Logo-WebDarkmode.jpg";
      } else {
          logoImage.src = "images/Logo-Web.png";
      }
  }

  const darkModeToggle = document.getElementById('dark-mode-toggle');
  if (darkModeToggle) {
      darkModeToggle.addEventListener('change', changeImage);
  }

  changeImage();
});


/* ------------------*/
/*                   */
/*                   */
/*      NAVBAR       */
/*                   */
/*                   */
/*-------------------*/

/* NAVBAR CLOSE */
$(document).ready(function(){
  $('.nav_btn').click(function(){
    $('.mobile_nav_items').toggleClass('active');
  });
});

/* CHANGEMENT AVATAR NAVBAR */
var radios = document.querySelectorAll('input[name="avatar"]');
var mobileProfileImage = document.querySelector('.mobile_profile_image');
var profileImage = document.querySelector('.profile_image');

// Ajoutez un écouteur d'événements à chaque bouton radio
radios.forEach(function(radio) {
    radio.addEventListener('change', function() {
        // Modifiez les attributs src des images lorsque le bouton radio est sélectionné
        var avatarSrc;
        if (this.checked) {
            // Si un bouton radio est coché, mettez à jour les images en conséquence
            if (this.id === 'avatar1') {
                avatarSrc = '../../images/avatar/avatar1.png';
            } else if (this.id === 'avatar2') {
                avatarSrc = '../../images/avatar/avatar2.png';
            } else if (this.id === 'avatar3') {
                avatarSrc = '../../images/avatar/avatar3.png';
            } else if (this.id === 'avatar4') {
                avatarSrc = '../../images/avatar/avatar4.png';
            } else if (this.id === 'avatar5') {
                avatarSrc = '../../images/avatar/avatar5.png';
            } else if (this.id === 'avatar6') {
                avatarSrc = '../../images/avatar/dancing-toothless-tothless.gif';
            } else if (this.id === 'avatar7') {
                avatarSrc = '../../images/avatar/Donald-Duck.gif';
            } else if (this.id === 'avatar8') {
                avatarSrc = '../../images/avatar/Pedro.gif';
            } else if (this.id === 'avatar9') {
                avatarSrc = '../../images/avatar/PowerRanger.gif';
            } else if (this.id === 'avatar10') {
                avatarSrc = '../../images/avatar/angry-cat.gif';
            } else if (this.id === 'avatar11') {
                avatarSrc = '../../images/avatar/quokka.gif';
            }
            mobileProfileImage.src = avatarSrc;
            profileImage.src = avatarSrc;

            // Enregistrez l'avatar sélectionné dans le stockage local
            localStorage.setItem('selectedAvatar', avatarSrc);
        } else {
            // Si aucun bouton radio n'est coché, définissez l'image de base
            mobileProfileImage.src = '../../images/user-icon.png';
            profileImage.src = '../../images/user-icon.png';
        }
    });
});


function showMoreAvatars() {
  document.getElementById('hiddenAvatars').style.display = 'flex';
  document.getElementById('showMoreAvatars').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('showMoreAvatars').addEventListener('click', showMoreAvatars);
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


/* GRAPHIQUE EN BARS */
document.addEventListener('DOMContentLoaded', function() {

  const darkModeSwitch = document.getElementById('dark-mode-toggle');

  let labelColor = darkModeSwitch.checked ? 'white' : 'black';

  const data = {
    labels: ['Total Utilisateurs', 'Nombre de Tickets', 'Dépense', 'Tickets en Attente'],
    datasets: [
        { label: 'Total Utilisateurs', data: [chartData.total_utilisateurs, null, null, null,], backgroundColor: 'rgba(54, 162, 235, 0.2)', borderColor: 'rgba(54, 162, 235, 1)', borderWidth: 1 },
        { label: 'Nombre de Tickets', data: [null, chartData.total_tickets, null, null], backgroundColor: 'rgba(255, 206, 86, 0.2)', borderColor: 'rgba(255, 206, 86, 1)', borderWidth: 1 },
        { label: 'Dépense', data: [null, null, chartData.total_depense, null], backgroundColor: 'rgba(75, 192, 192, 0.2)', borderColor: 'rgba(75, 192, 192, 1)', borderWidth: 1 },
        { label: 'Tickets en Attente', data: [null, null, null, chartData.total_tickets_attente], backgroundColor: 'rgba(255, 99, 132, 0.2)', borderColor: 'rgba(255, 99, 132, 1)', borderWidth: 1 }
    ]
  };

  const config = {
    type: 'bar',
    data,
    options: {
        scales: { 
            y: { type: 'logarithmic', ticks: { callback: value => Number(value.toString()) } },
            x: { ticks: { color: labelColor } }
        },
        barPercentage: 11,
        categoryPercentage: 0.2,
        plugins: { legend: { labels: { color: labelColor } } }
    }
  };

  const myChart = new Chart(document.getElementById('myChart'), config);

  darkModeSwitch.addEventListener('change', function() {
    labelColor = this.checked ? 'white' : 'black';
    
    myChart.options.plugins.legend.labels.color = labelColor;
    myChart.options.scales.x.ticks.color = labelColor;
    
    myChart.update();
  });
});


/* CAMEMBERT */

document.addEventListener('DOMContentLoaded', function() {

  const darkModeSwitch = document.getElementById('dark-mode-toggle');

  let labelColor = darkModeSwitch.checked ? 'white' : 'black';

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
        { 
          type: 'pie', 
          data: myData,
          options: {
            plugins: {
              legend: {
                labels: {
                  color: labelColor 
                }
              }
            }
          }
        }
      );
    } else {
      console.error('La bibliothèque Chart.js n\'est pas chargée.');
    }
  } else {
    console.error('Aucune donnée disponible pour afficher le graphique.');
  }

  darkModeSwitch.addEventListener('change', function() {
    
      labelColor = this.checked ? 'white' : 'black';
      
      myChartCAM.options.plugins.legend.labels.color = labelColor;
      
      myChartCAM.update();
  });
});
