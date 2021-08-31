let message;

document.addEventListener('DOMContentLoaded', function () {
    message = document.querySelector(".my-formulaire-info");

    //si l'élément existe dans le HTML, alors seulement on lui installe le gestionnaire d'événement
    if (typeof (message) !== undefined && message !== null) {
        message.addEventListener("click", hideInfo)
    }
})

function hideInfo() {
    message.remove();
}