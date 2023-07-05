// Sélectionnez le bouton burger, le menu de navigation et le document
const burgerButton = document.querySelector('.burger-header');
const menu = document.querySelector('.burger_content-header');
const documentBody = document.body;

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

// Fonction pour basculer l'état du menu
function toggleMenu() {
  const isMenuOpen = menu.style.transform === 'translateX(0%)';

  if (isMenuOpen) {
    menu.style.transform = 'translateX(-100%)';
  } else {
    menu.style.transform = 'translateX(0%)';
  }
}
