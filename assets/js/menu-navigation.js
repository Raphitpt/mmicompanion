
// ANIMATION MENU BURGER

// Sélectionnez le bouton burger, le menu de navigation et le document
const burgerButton = document.querySelector('#burger-header');
const closeButton = document.querySelector('#close_burger-header');
const menu = document.querySelector('#burger_content-header');
const link = document.querySelectorAll('.burger_content_link-header');

const documentBody = document.body;

// Ajoutez un gestionnaire d'événement au clic sur le bouton burger
burgerButton.addEventListener('click', (event) => {
  event.stopPropagation(); // Empêche la propagation de l'événement de clic
  toggleMenu();

  documentBody.classList.add('no-scroll');
  burgerButton.classList.add('active');
  closeButton.classList.remove('active');
});

// Ajoutez un gestionnaire d'événement au clic sur le document
document.addEventListener('click', (event) => {
  // Fermez le menu si l'élément cliqué est à l'extérieur
  if (!menu.contains(event.target)) {
    menu.style.transform = 'translateX(-100%)';
    documentBody.classList.remove('no-scroll');
    burgerButton.classList.remove('active');
    closeButton.classList.add('active');
  }
});

// Ajoutez un gestionnaire d'événement au clic sur le bouton de fermeture
closeButton.addEventListener('click', (event) => {
  event.stopPropagation(); // Empêche la propagation de l'événement de clic
  toggleMenu();

  documentBody.classList.remove('no-scroll');
  closeButton.classList.add('active');
  burgerButton.classList.remove('active');
});

// Fonction pour basculer l'état du menu
function toggleMenu() {
  const isMenuOpen = menu.style.transform === 'translateX(0%)';

  if (isMenuOpen) {
    menu.style.transform = 'translateX(-100%)';
    documentBody.classList.remove('no-scroll');
    burgerButton.classList.remove('active');
    closeButton.classList.add('active');
  } else {
    menu.style.transform = 'translateX(0%)';
  }
}

// Animation du bouton burger

// burgerButton.addEventListener('click', function() {
//   if (burgerButton.classList.contains('active')) {
//     burgerButton.classList.remove('active');
//   } else {
//     burgerButton.classList.add('active');
//   }
// });



// link.forEach(function(link) {
//   link.addEventListener('click', function(event) {
//     event.stopPropagation();
//     const selectLink = document.querySelector('.select_link-header');
//     const clickedDiv = event.target.parentElement;
//     clickedDiv.appendChild(selectLink);
//     toggleMenu();
//   });
// });

// function getDataFromFile(x) {
//   let xhr = new XMLHttpRequest();

//   xhr.onreadystatechange = function() {
//     if (xhr.readyState === 4 && xhr.status === 200) {

//       let HTMLcontent = xhr.responseText;

//      console.log("HTML: " + HTMLcontent);

//       document.querySelector(".container").innerHTML = HTMLcontent;
//     }
//   };
//   xhr.open("POST", x, true);
//   xhr.send();
// }

function updatePoints(x) {
  let xhr = new XMLHttpRequest();

  xhr.open('POST', './../pages/points.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
              console.log('Points mis à jour avec succès !');
          } else {
              console.error('Erreur lors de la mise à jour des points');
          }
      }
  };

  let points = x;
  let data = 'points=' + encodeURIComponent(points);
  xhr.send(data);
}

function acceptCGU() {
  let xhr = new XMLHttpRequest();

  xhr.open('POST', './../pages/acceptCGU.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
              console.log('CGU acceptées avec succès !');
              document.querySelector('.CGU-index').style.display = 'none';
          } else {
              console.error('Erreur lors de l\'acceptation des CGU');
          }
      }
  };

  let data = 'CGU=' + encodeURIComponent('true');
  xhr.send(data);

}

const CGUbtn = document.querySelector('.button_CGU-validate');
CGUbtn.addEventListener('click', function() {
  acceptCGU();
  console.log('click');
});
CGUbtn.addEventListener('touchstart', function() {
  acceptCGU();
  console.log('touch');
});