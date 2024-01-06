// ANIMATION MENU BURGER

// Sélectionnez le bouton burger, le menu de navigation et le document
const burgerButton = document.querySelector("#burger-header");
const closeButton = document.querySelector("#close_burger-header");
const menu = document.querySelector("#burger_content-header");
const link = document.querySelectorAll(".burger_content_link-header");

const documentBody = document.body;

// Ajoutez un gestionnaire d'événement au clic sur le bouton burger
burgerButton.addEventListener("click", (event) => {
  event.stopPropagation(); // Empêche la propagation de l'événement de clic
  toggleMenu();

  documentBody.classList.add("no-scroll");
  burgerButton.classList.add("active");
  closeButton.classList.remove("active");
});

// Ajoutez un gestionnaire d'événement au clic sur le document
document.addEventListener("click", (event) => {
  // Fermez le menu si l'élément cliqué est à l'extérieur
  if (!menu.contains(event.target)) {
    menu.style.transform = "translateX(-100%)";
    documentBody.classList.remove("no-scroll");
    burgerButton.classList.remove("active");
    closeButton.classList.add("active");
  }
});

// Ajoutez un gestionnaire d'événement au clic sur le bouton de fermeture
closeButton.addEventListener("click", (event) => {
  event.stopPropagation(); // Empêche la propagation de l'événement de clic
  toggleMenu();

  documentBody.classList.remove("no-scroll");
  closeButton.classList.add("active");
  burgerButton.classList.remove("active");
});

// Fonction pour basculer l'état du menu
function toggleMenu() {
  const isMenuOpen = menu.style.transform === "translateX(0%)";

  if (isMenuOpen) {
    menu.style.transform = "translateX(-100%)";
    documentBody.classList.remove("no-scroll");
    burgerButton.classList.remove("active");
    closeButton.classList.add("active");
  } else {
    menu.style.transform = "translateX(0%)";
  }
}

// Gestion de la partie notification

const menuIcon = document.querySelector("#btn_notification");
const navMenu = document.querySelector(".container_notifications-header");

// Ajouter un gestionnaire d'événement au clic sur l'icône du menu
menuIcon.addEventListener("click", function (event) {
  event.stopPropagation(); // Empêche la propagation de l'événement de clic
  toggleMenuNotif();
});

// Ajoutez un gestionnaire d'événement au clic sur le document
document.addEventListener("click", (event) => {
  // Fermez le menu si l'élément cliqué est à l'extérieur
  if (!navMenu.contains(event.target)) {
    navMenu.classList.remove("menu_notification_open");
    navMenu.classList.add("menu_notification_close");
  }
});

// Sélectionner tous les liens du menu
const menuLinks = document.querySelectorAll(".menu-link");

// Ajouter un gestionnaire d'événement de clic à chaque lien du menu
menuLinks.forEach((link) => {
  link.addEventListener("click", (event) => {
    // Fermer le menu en cliquant sur un lien
    toggleMenuNotif();
  });
});

function toggleMenuNotif() {
  // Vérifier si le menu est actuellement visible ou caché
  const isMenuOpen = navMenu.classList.contains("menu_notification_open");

  // Inverser la visibilité du menu en ajoutant ou en supprimant la classe 'open'
  if (isMenuOpen) {
    navMenu.classList.remove("menu_notification_open");
    navMenu.classList.add("menu_notification_close");
  } else {
    navMenu.classList.add("menu_notification_open");
    navMenu.classList.remove("menu_notification_close");
  }
}

// // Ajoutez un gestionnaire d'événement au clic sur le document
// document.addEventListener("click", (event) => {
//   // Fermez le menu si l'élément cliqué est à l'extérieur
//   if (!contentNotification.contains(event.target)) {
//     contentNotification.classList.add("hidden");
//   }
// });

// // Fonction pour basculer l'état du menu

// function toggleNotification() {
//   const isMenuOpen = contentNotification.classList.contains("hidden");

//   if (isMenuOpen) {
//     contentNotification.classList.add("hidden");
//   } else {

//   }
// }

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

// AGENDA

function handleCheckboxChange() {
  let checkbox = this;
  let contentAgenda = checkbox.parentNode.querySelector(
    ".content_item_list_flexleft-agenda"
  );
  let content = checkbox.parentNode.querySelector(
    ".description_item_list_flexleft-agenda"
  );

  if (checkbox.checked) {
    contentAgenda.style.textDecoration = "line-through";
    contentAgenda.style.opacity = "0.5";
    content.style.display = "none";
  } else {
    contentAgenda.style.textDecoration = "none";
    contentAgenda.style.opacity = "1";
    content.style.display = "block";
  }
}

function updatePoints(x) {
  let xhr = new XMLHttpRequest();

  xhr.open("POST", "./../pages/points.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        console.log("Points mis à jour avec succès !");
      } else {
        console.error("Erreur lors de la mise à jour des points");
      }
    }
  };

  let points = x;
  let data = "points=" + encodeURIComponent(points);
  xhr.send(data);
}

function acceptCGU() {
  let xhr = new XMLHttpRequest();

  xhr.open("POST", "./../pages/acceptCGU.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        console.log("CGU acceptées avec succès !");
        document.querySelector(".CGU-index").style.display = "none";
      } else {
        console.error("Erreur lors de l'acceptation des CGU");
      }
    }
  };

  let data = "CGU=" + encodeURIComponent("true");
  xhr.send(data);
}

const CGUbtn = document.querySelector("#button_CGU-validate");
if (CGUbtn != null) {
  CGUbtn.addEventListener("click", function () {
    acceptCGU();
  });
  CGUbtn.addEventListener("touchstart", function () {
    acceptCGU();
  });
}

// Selection du thème
// Identify the select element
const themeSelect = document.querySelector("#themeSelect");
if (themeSelect != null) {
  // Function that changes the theme and sets a localStorage variable to track the theme between page loads
  function switchTheme() {
    const selectedTheme = themeSelect.value;

    if (selectedTheme === "dark") {
      localStorage.setItem("theme", "dark");
      document.documentElement.setAttribute("data-theme", "dark");
    } else {
      localStorage.setItem("theme", "light");
      document.documentElement.setAttribute("data-theme", "light");
    }
  }

  // Listener for changing themes
  themeSelect.addEventListener("change", switchTheme);

  // Pre-select the theme based on the localStorage variable
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme) {
    themeSelect.value = savedTheme;
    switchTheme(); // Apply the saved theme on page load
  }
}

function loadTheme() {
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme) {
    if (savedTheme === "dark") {
      document.documentElement.setAttribute("data-theme", "dark");
    } else {
      document.documentElement.setAttribute("data-theme", "light");
    }
  }
}

loadTheme();

/* Service Worker */
// if ('serviceWorker' in navigator) {
//   navigator.serviceWorker
//     .register('https://dev.mmi-companion.fr/mmicompanion/sw.js')
//     .then((registration) => {
//       // Mettre à jour le service worker si nécessaire
//       if (registration.installing) {
//         registration.installing.postMessage({ type: 'SKIP_WAITING' });
//       }

//       console.log('Service Worker Registered');
//     })
//     .catch((error) => {
//       console.error('Service Worker Registration failed:', error);
//     });
// }

const cloche_notification = document.querySelector("#btn_notification");
const notification = document.querySelectorAll(".container_notifications-header");

cloche_notification.addEventListener("click", function () {
  const idNotifElements = document.querySelectorAll(".id_notif");
  const idNotifArray = Array.from(idNotifElements).map(
    (element) => element.textContent
  );
  if (idNotifArray.length > 0) {
    setTimeout(function () {
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "/pages/read_notif.php", true);
      xhr.setRequestHeader("Content-Type", "application/json");

      
      const data = {
        notifications_ids: idNotifArray,
      };
      xhr.send(JSON.stringify(data));
    }, 3000);
  }
});
