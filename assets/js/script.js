
  const burgerButton = document.querySelector('.burger-header');
  const menu = document.querySelector('.burger_content-header');
  const link = document.querySelectorAll('.burger_content_link-header');
  const documentBody = document.body;

// Fonction pour basculer l'état du menu
function toggleMenu() {
  const isMenuOpen = menu.style.transform === 'translateX(0%)';

  if (isMenuOpen) {
    menu.style.transform = 'translateX(-100%)';
  } else {
    menu.style.transform = 'translateX(0%)';
  }
}

function getDataFromAgenda() {
  let xhr = new XMLHttpRequest();

  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {

      let response = JSON.parse(xhr.responseText);

      let status = response.status;
      let agendaHTML = response.agenda_html;
      let evalCount = response.eval_count;
      let agendaCount = response.agenda_count;

      console.log("Statut de la réponse : " + status);
      console.log("HTML de l'agenda : " + agendaHTML);
      console.log("Nombre d'évaluations : " + evalCount);
      console.log("Nombre de tâches : " + agendaCount);

      document.querySelector(".container").innerHTML = agendaHTML;
    }
  };
  xhr.open("POST", "test_agenda.php", true);
  xhr.send();
}


// Ajoutez un gestionnaire d'événement au clic sur le bouton burger
burgerButton.addEventListener('click', (event) => {
  event.stopPropagation(); // Empêche la propagation de l'événement de clic
  toggleMenu();
});

// Ajoutez un gestionnaire d'événement au clic sur le document
document.addEventListener('click', (event) => {
  // Fermez le menu si l'élément cliqué est à l'extérieur
  if (!menu.contains(event.target)) {
    menu.style.transform = 'translateX(-100%)';
  }
});

link.forEach(function(link) {
  link.addEventListener('click', function(event) {
    event.stopPropagation();
    const selectLink = document.querySelector('.select_link-header');
    const clickedDiv = event.target.parentElement;
    clickedDiv.appendChild(selectLink);
    toggleMenu();
  });
});
