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

// Function to determine the badge count based on the FCM message data
function determineBadgeCount(data) {
  try {
    const payload = JSON.parse(data.text());
    // Process the FCM message data to compute the badge count
    // For example, extract count from payload
    return payload.count || 1; // Default to 1 if count is not present
  } catch (error) {
    console.error('Error parsing FCM data:', error);
    return 1; // Default to 1 in case of error
  }
}

self.addEventListener('push', (event) => {
  const badgeCount = determineBadgeCount(event.data);
  const promises = [];

  if ('setAppBadge' in self.navigator) {
    // Promise to set the badge
    const setBadgePromise = self.navigator.setAppBadge(badgeCount);
    promises.push(setBadgePromise);
  }

  // Parse the notification payload from the FCM message data
  try {
    const payload = JSON.parse(event.data.text());
    // Promise to show a notification
    const showNotificationPromise = self.registration.showNotification(payload.notification.title, {
      body: payload.notification.body,
      icon: payload.notification.icon,
      badge: payload.notification.badge,
      data: payload.notification.data,
    });
    promises.push(showNotificationPromise);
  } catch (error) {
    console.error('Error parsing FCM notification data:', error);
  }

  // Finally...
  event.waitUntil(Promise.all(promises));
});
