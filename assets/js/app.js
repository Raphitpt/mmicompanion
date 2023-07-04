function urlBase64ToUint8Array(base64String) {
    let padding = '='.repeat((4 - base64String.length % 4) % 4);
    let base64 = (base64String + padding)
      .replace(/\-/g, '+')
      .replace(/_/g, '/');
  
    let rawData = window.atob(base64);
    let outputArray = new Uint8Array(rawData.length);
  
    for (let i = 0; i < rawData.length; ++i) {
      outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
  }
  
  function main() {
    const btn = document.querySelector("#btn");
    if (!btn || !("Notification" in window) || !("serviceWorker" in navigator)) {
      return;
    }
  
    const button = document.createElement("button");
    button.textContent = "Activer les notifications";
    btn.appendChild(button);
    button.addEventListener("click", askPermission);
  
    async function askPermission() {
      const permission = await Notification.requestPermission();
      if (permission === "granted") {
        registerServiceWorker();
      }
    }
  
    async function registerServiceWorker() {
      const registration = await navigator.serviceWorker.register("../sw.js");
      let subscription = await registration.pushManager.getSubscription();
      if (!subscription) {
        subscription = await registration.pushManager.subscribe({
          userVisibleOnly: true,
          applicationServerKey: urlBase64ToUint8Array('BMGOAIHOuUE_CGaXM58rtEOi_67LnOPcVJ-toCN7oU_QvL6kH_gbHC3kfrAfz8Fj_6Z-F4u9FEgMjeSv0t59FYU'),
        });
      }
  
      await saveSubscription(subscription);
    }
  
    async function saveSubscription(subscription) {
      await fetch("../assets/php/subscription.php", {
        method: "post",
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify(subscription),
      });
    }
  
    // const sendNotificationButton = document.getElementById('sendNotificationButton');
    // sendNotificationButton.addEventListener('click', () => {
    //   fetch('../assets/php/send_notification.php', {
    //     method: 'POST',
    //     headers: {
    //       'Content-Type': 'application/json',
    //       'Accept': 'application/json'
    //     }
    //   })
    //     .then(response => response.json())
    //     .then(data => {
    //       console.log('Notification envoyée avec succès:', data);
    //     })
    //     .catch(error => {
    //       console.error('Erreur lors de l\'envoi de la notification:', error);
    //     });
    // });
  
  }
  
  main();