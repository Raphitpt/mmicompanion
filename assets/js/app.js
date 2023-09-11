function main() {
  const permission = document.querySelector("#push-permission");
  if (
    !permission ||
    !("Notification" in window) ||
    !("serviceWorker" in navigator) || Notification.permission !== "default"


  ) {
    return;
  }
  const button = document.createElement("button");
  button.textContent = "Enable Push Notifications";
  permission.appendChild(button);
  button.addEventListener("click", askPermission);
}
async function askPermission() {
  const permission = await Notification.requestPermission();
  if (permission == "granted") {
    registerServiceWorker();
  }
}
async function registerServiceWorker() {
  const registrations = await navigator.serviceWorker.register("./../sw.js");
  let subscription = await registrations.pushManager.getSubscription();
  if (!subscription) {
    subscription = await registrations.pushManager.subscribe({
      userVisibleOnly: true,
      applicationServerKey: await getPublicKey(),
    })
  }
  
  await saveSubscription(subscription);
}
async function getPublicKey() {
  const { key } = await fetch("./../Helpers/vapidPublicKey.php", {
    headers: {
      Accept: "application/json",
    },
  }).then((response) => response.json());

  return key;
}

async function saveSubscription(subscription) {
  const {key} = await fetch("./../Helpers/saveSubscription.php", {
    method: "POST",
    body: JSON.stringify(subscription),
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
  });

}

main();
