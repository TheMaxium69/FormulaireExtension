let message;


document.addEventListener('DOMContentLoaded', function () {
    message = document.querySelector(".my-formulaire-info");
    if (typeof (message) !== undefined && message !== null) {
        message.addEventListener("click", hideInfo);
    }
    document.querySelectorAll(".btn-supp").forEach(function (btn) {
        btn.addEventListener("click", deleteEmail)
    });
})

function hideInfo() {
    message.remove();
}

function deleteEmail() {
    const id = this.dataset.id;

    let data = new FormData();
    data.append("action", "myformulaire_delete");
    data.append("id", id);

    fetch(myFormScript.adminUrl,
        {
            method: "POST",
            body: data
        })
        .then(function (response) {
            if (response.ok) {
                return response.json();
            } else {
                alert("Une erreur est survenue.")
            }
        })
        .then(function (data) {
            data.result ?
                location.reload() :
                alert(data.message);
        })
}