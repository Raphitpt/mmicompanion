importScripts('https://www.gstatic.com/firebasejs/10.7.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.0/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyCjTSvi2mReuoaSK9PlbFl-0Hvre04yj8M",
    authDomain: "mmi-companion.firebaseapp.com",
    projectId: "mmi-companion",
    storageBucket: "mmi-companion.appspot.com",
    messagingSenderId: "995711151734",
    appId: "1:995711151734:web:7175344e2f03e3665bf957",
    measurementId: "G-7F3M3RX1WJ",
  });

const messaging = firebase.messaging();



// Handle background messages
messaging.onBackgroundMessage(() => {
  if ('setAppBadge' in navigator) {
    navigator.setAppBadge(1); // Mettez à jour le badge avec le nombre souhaité
  }
});
