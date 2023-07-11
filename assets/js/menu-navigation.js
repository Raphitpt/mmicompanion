
// ANIMATION MENU BURGER

// Sélectionnez le bouton burger, le menu de navigation et le document
const burgerButton = document.querySelector('#burger-header');
const menu = document.querySelector('#burger_content-header');
const link = document.querySelectorAll('.burger_content_link-header');
const documentBody = document.body;

// Ajoutez un gestionnaire d'événement au clic sur le bouton burger
burgerButton.addEventListener('click', (event) => {
  event.stopPropagation(); // Empêche la propagation de l'événement de clic
  toggleMenu();

  documentBody.classList.add('no-scroll');
});

// Ajoutez un gestionnaire d'événement au clic sur le document
document.addEventListener('click', (event) => {
  // Fermez le menu si l'élément cliqué est à l'extérieur
  if (!menu.contains(event.target)) {
    menu.style.transform = 'translateX(-100%)';
    documentBody.classList.remove('no-scroll');
  }
});

// Fonction pour basculer l'état du menu
function toggleMenu() {
  const isMenuOpen = menu.style.transform === 'translateX(0%)';

  if (isMenuOpen) {
    menu.style.transform = 'translateX(-100%)';
    documentBody.classList.remove('no-scroll');
  } else {
    menu.style.transform = 'translateX(0%)';
  }
}

// link.forEach(function(link) {
//   link.addEventListener('click', function(event) {
//     event.stopPropagation();
//     const selectLink = document.querySelector('.select_link-header');
//     const clickedDiv = event.target.parentElement;
//     clickedDiv.appendChild(selectLink);
//     toggleMenu();
//   });
// });

function getDataFromFile(x) {
  let xhr = new XMLHttpRequest();

  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {

      let HTMLcontent = xhr.responseText;

     console.log("HTML: " + HTMLcontent);

      document.querySelector(".container").innerHTML = HTMLcontent;
    }
  };
  xhr.open("POST", x, true);
  xhr.send();
}


