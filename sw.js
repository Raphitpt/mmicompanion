self.addEventListener("install", () => {
  self.skipWaiting();
});

self.addEventListener("push", (event) => {
  const data = event.data ? event.data.json() : {};
  const unreadCount = data.unreadCount; // Utilisez data.unreadCount au lieu de message.unreadCount

  event.waitUntil(
    self.registration.showNotification(data.title, {
      body: data.body,
    })
  );

  // set or update the badge
  if (navigator.setAppBadge) {
    if (unreadCount && unreadCount > 0) {
      navigator.setAppBadge(unreadCount);
    } else {
      navigator.clearAppBadge();
    }
  }
});
