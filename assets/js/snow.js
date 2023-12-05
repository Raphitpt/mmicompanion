function createSnowflake() {
    const snowflake = document.createElement("div");
    snowflake.className = "snowflake";

    // Ajouter une marge de 20px sur les côtés
    snowflake.style.left = Math.random() * (window.innerWidth - 40) + 20 + "px";

    document.getElementById("snow-container").appendChild(snowflake);

    const animationDelay = Math.random() * 5; // Ajout d'un délai aléatoire pour un début plus fluide
    const animationDuration = Math.random() * 2 + 4;
    
    snowflake.style.animation = `fall ${animationDuration}s linear ${animationDelay}s infinite`;
}

function startSnowfall() {
    for (let i = 0; i < 50; i++) {
        createSnowflake();
    }
}

startSnowfall();

// Empêcher le défilement horizontal
document.body.style.overflowX = 'hidden';

// Rafraîchir la page lors du redimensionnement de la fenêtre pour recalculer les positions
window.addEventListener('resize', function() {
    location.reload();
});