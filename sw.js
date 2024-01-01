// self.addEventListener("install", (event) => {
//   self.skipWaiting();
// });

// // // Function to determine the badge count based on the event data
// // function determineBadgeCount() {
// //   return fetch('./pages/getNotifs.php', {
// //     method: 'POST',
// //     headers: {
// //       'X-Requested-With': 'XMLHttpRequest',
// //     },
// //   })
// //     .then((response) => {
// //       if (!response.ok) {
// //         throw new Error(`Network response was not ok: ${response.status}`);
// //       }
// //       return response.json();
// //     })
// //     .then((data) => {
// //       if (data.notif_message !== undefined && data.notif_infos !== undefined) {
// //         const totalBadgeCount = data.notif_message + data.notif_infos;
// //         return self.navigator.setAppBadge(totalBadgeCount);
// //       } else {
// //         throw new Error('Invalid data format received for badge count');
// //       }
// //     })
// //     .catch((error) => {
// //       console.error('There was a problem with the fetch operation:', error.message);
// //     });
// // }

// // self.addEventListener('push', (event) => {
// //   const promises = [];

// //   if ('setAppBadge' in self.navigator) {
// //     promises.push(determineBadgeCount());
// //   }

// //   // Finally...
// //   event.waitUntil(Promise.all(promises));
// // });

// // self.addEventListener('message', (event) => {
// //   if (event.data.action === 'FETCH_BADGES') {
// //     determineBadgeCount();
// //   }
// // });

// if ('setAppBadge' in navigator) {
//   navigator.setAppBadge(42);
// }

// self.addEventListener('push', (event) => {
//   navigator.setAppBadge(42);
// });
let requestCounter = 0;

self.addEventListener('install', function(e) {
  console.log('[ServiceWorker] Install');
  self.skipWaiting();
});

self.addEventListener('activate', function(e) {
  console.log('[ServiceWorker] Activate');
  return self.clients.claim();
});

self.addEventListener('fetch', function(e) {
  if ('setAppBadge' in navigator) {
    navigator.setAppBadge(++requestCounter);
  }
  console.log('[Service Worker] Fetch', e.request.url);
  e.respondWith(fetch(e.request));
});
