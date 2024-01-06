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

self.addEventListener('push', (event) => {
  const data = event.data.json();

  const title = data.notification.title;
  const options = {
    body: data.notification.body,
  };

  determineBadgeCount();

  event.waitUntil(
    self.registration.showNotification(title, options)
  );
});

self.addEventListener('message', (event) => {
  if (event.data.action === 'FETCH_BADGES') {
    determineBadgeCount();
  }
});

function determineBadgeCount() {
  return fetch('./pages/getNotifs.php', {
    method: 'POST',
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`Network response was not ok: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      if (data.notif_message !== undefined && data.notif_infos !== undefined) {
        const totalBadgeCount = data.notif_message + data.notif_infos;

        if ('setAppBadge' in navigator) {
          navigator.setAppBadge(totalBadgeCount);
        } else {
          throw new Error('setAppBadge is not supported');
        }
      } else {
        throw new Error('Invalid data format received for badge count');
      }
    })
    .catch((error) => {
      console.error('There was a problem with the fetch operation:', error.message);
    });
}