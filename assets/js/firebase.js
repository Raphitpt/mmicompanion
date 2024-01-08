import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-app.js";
import {
  getMessaging,
  getToken,
} from "https://www.gstatic.com/firebasejs/10.7.0/firebase-messaging.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-analytics.js";
import axios from "https://cdn.skypack.dev/axios";

const firebaseConfig = {
  apiKey: "AIzaSyCjTSvi2mReuoaSK9PlbFl-0Hvre04yj8M",
  authDomain: "mmi-companion.firebaseapp.com",
  projectId: "mmi-companion",
  storageBucket: "mmi-companion.appspot.com",
  messagingSenderId: "995711151734",
  appId: "1:995711151734:web:7175344e2f03e3665bf957",
  measurementId: "G-7F3M3RX1WJ",
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
const messaging = getMessaging(app);

// const myButton = document.querySelector("#push-permission-button");
// if (myButton){
//   myButton.addEventListener("click", requestPermission);

// }
// const permission = document.querySelector("#push-permission");
//   if (
//     !permission ||
//     !("Notification" in window) ||
//     !("serviceWorker" in navigator) || Notification.permission !== "default"

//   ) {
//     return;
//   }
//   const button = document.createElement("button");
//   button.textContent = "Activer les notifications";
//   permission.appendChild(button);
//   button.addEventListener("click", askPermission);

function notif() {
  const permissionContainer = document.querySelector("#push-permission");
  const enableNotificationsButton = document.querySelector(
    "#enable-notifications"
  );

  if (
    !permissionContainer ||
    !("Notification" in window) ||
    !("serviceWorker" in navigator) ||
    Notification.permission !== "default"
  ) {
    return;
  }

  enableNotificationsButton.addEventListener("click", () => {
    requestPermission();
    permissionContainer.style.display = "none";
  });

  // Afficher la popup après un délai de 2 secondes (modifiable selon vos besoins)
  setTimeout(() => {
    permissionContainer.style.display = "flex";
  }, 2000);
}

async function requestPermission() {
  Notification.requestPermission().then((permission) => {
    if (permission === "granted") {
      // Retrieve the FCM registration token
      getToken(messaging, {
        vapidKey:
          "BFyDCKvv1s5q49SnH0-SVGJl2kJ5UHzaqq1d8YjSDCQtAY3ub38YyVxmlPXWZHNR6RVMH_YGFqvkBzzY9DBrIz8",
      })
        .then((currentToken) => {
          // Send the token to your server for storage
          axios
            .post("./../Helpers/saveSubscription.php", { token: currentToken })
            .then((response) => {})
            .catch((error) => {
              console.error("Error saving token:", error);
            });
        })
        .catch((err) => {
          console.error("Unable to retrieve token:", err);
        });
    } else {
      console.log("Unable to get permission to notify.");
    }
  });
}

window.addEventListener("DOMContentLoaded", () => {
  notif();
  // si la permission est déjà accordée

  if (Notification.permission === "granted") {
    requestPermission();
  }
});
