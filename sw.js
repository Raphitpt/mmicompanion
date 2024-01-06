self.addEventListener("install", (event) => {
  self.skipWaiting();
});

// Function to determine the badge count based on the event data
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

// Logging the result of determineBadgeCount() may not be meaningful, so removed it
self.addEventListener('push', (event) => {
  const promises = [];

  if ('setAppBadge' in navigator) {
    promises.push(determineBadgeCount());
  }

  // Finally...
  event.waitUntil(Promise.all(promises));
});

self.addEventListener('message', (event) => {
  if (event.data.action === 'FETCH_BADGES') {
    determineBadgeCount();
  }
});
