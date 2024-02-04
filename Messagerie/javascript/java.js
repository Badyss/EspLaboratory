function changerprofil() { //Fonction pour éditer son profil 
    const overlay = document.getElementById('overlay');

    if (overlay.style.display === 'block') {
        overlay.style.display = 'none';
    } else {
        overlay.style.display = 'block';
    }
}


function toggleDashboard() { //Fonction d'animation pour l'A2F
    var elements = ['vues', 'profil', 'dlc'];
    var totalAnimations = elements.length;

    elements.forEach(function(elementId, index) {
        setTimeout(function() {
            var element = document.getElementById(elementId);
            element.classList.add('slide-right');
            setTimeout(function() {
                element.style.visibility = 'hidden';
                applyFilterToBackground();
                if (index === totalAnimations - 1) {
                    fadeInElement('verifdash', 500); 
                }
            }, 500);
        }, index * 200);
    });
}

function fadeInElement(elementId, duration) { //Fonction d'animation pour afficher la balise d'A2F
    var element = document.getElementById(elementId);
    var startTime = null;
    element.style.visibility = 'visible';
    element.style.display = 'block';

    function fade(time) {
        if (!startTime) startTime = time;

        var progress = time - startTime;
        var opacity = progress / duration;

        if (opacity <= 1) {
            element.style.opacity = opacity;
            requestAnimationFrame(fade);
        } else {
            element.style.opacity = 1;
        }
    }

    requestAnimationFrame(fade);
}

function applyFilterToBackground() { //Fonction pour appliquer un effet au background
    var bodyBefore = document.body.style;
    bodyBefore.setProperty('--filter-hue', 'hue-rotate(130deg) blur(1px) contrast(1.1)');
}

function successtrue() {
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const verificationSuccess = urlParams.get('verification_success');

        if (verificationSuccess === 'true') {
            var verifdashElement = document.getElementById('verifdash');
            if (verifdashElement) {
                verifdashElement.style.display = 'none';
                activeadmin();
            }
        }
    });
}

function panelcontrol(section) {
    var maindash = document.getElementById('maindash');
    var usersdash = document.getElementById('usersdash');

    if (section === 'maindash') {
        maindash.style.display = 'block';
        usersdash.style.display = 'none';
    } else if (section === 'usersdash') {
        maindash.style.display = 'none';
        usersdash.style.display = 'block';
    }
}

function editer() {
    var editCase = event.target.closest('.editcase');
    var userId = editCase.dataset.userId;
    window.location.href = 'supprimer.php?userId=' + userId;
}

function toggleAdminStatus() {
    var editCase = event.target.closest('.toggleadmin');
    var userId = editCase.dataset.userId;
    window.location.href = 'admin.php?userId=' + userId;
}


function afficherGraphique(jours, comptesCrees) {
    var ctx = document.getElementById("graphiqueComptes").getContext("2d");

    var graphique = new Chart(ctx, {
        type: "bar",
        data: {
            labels: jours,
            datasets: [{
                label: "Comptes créés",
                data: comptesCrees,
                backgroundColor: "rgba(75, 192, 192, 0.2)",
                borderColor: "rgba(75, 192, 192, 1)",
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function afficherGraphiqueFormation(formationLabels, formationData) {
    var ctx = document.getElementById("graphiqueFormation").getContext("2d");

    var graphique = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: formationLabels,
            datasets: [{
                data: formationData,
                backgroundColor: [
                    "#FF6384",
                    "#36A2EB",
                    "#FFCE56",
                    "#4CAF50"
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: "right"
            }
        }
    });
}

function rechercheAjax() {
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('name');
        const searchResults = document.querySelector('.result');
  
        searchInput.addEventListener('input', function() {
          const searchQuery = searchInput.value.trim();
  
          const xhr = new XMLHttpRequest();
          const url = `recherche.php?search=${searchQuery}`;
  
          xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
              if (xhr.status === 200) {
                searchResults.innerHTML = xhr.responseText;
              } else {
                // Gérer les erreurs
              }
            }
          };
  
          xhr.open('GET', url, true);
          xhr.send();
        });
        searchResults.addEventListener('click', function(event) {
          if (event.target && event.target.matches('p.searchResult')) {
            const userId = event.target.dataset.userid;
            window.location.href = `phpsql/profil.php?UserId=${userId}`;
          }
        });
      });
}

function crermsg(action) { //Display pour créer groupe/mp
    var crergroupeDiv = document.getElementById("crergroupe");
    var crerconvDiv = document.getElementById("crerconv");

    if (action === 'crergroupe') {
        crergroupeDiv.style.display = 'block';
        crerconvDiv.style.display = 'none';
    } else if (action === 'crerconv') {
        crergroupeDiv.style.display = 'none';
        crerconvDiv.style.display = 'block';
    } else if (action === 'fermer') {
        crergroupeDiv.style.display = 'none';
        crerconvDiv.style.display = 'none';
    }
}
