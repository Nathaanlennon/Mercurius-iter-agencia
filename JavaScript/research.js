let trips = [];

function print_trips(trips_list) {
    const result = document.getElementById("trips");
    result.innerHTML = "";
    trips_list.forEach(trip => {
        const div = document.createElement("div");
        div.className = "result";
        div.innerHTML = trip.name + "<br> Prix minimum : " + trip.price * nb_personnes + "€" +
            "<br> Durée : " + trip.duration + "jours";
        div.onclick = function () {
            window.location = "voyage_sheet.php?id=" + trip.id;
        };
        result.appendChild(div);
    });

}

window.addEventListener("load", function () {
    const form = document.getElementById("tri");
    form.addEventListener("change", function () {
        const type = document.getElementById("type").value;
        const ordre = document.getElementById("ordre").value;
        switch (type) {
            case "prix":
                trips.sort((a, b) =>
                    ordre === "croissant" ? a.price - b.price : b.price - a.price
                );
                break;

            case "duree":
                trips.sort((a, b) =>
                    ordre === "croissant" ? a.duration - b.duration : b.duration - a.duration
                );
                break;

            case "nom":
                trips.sort((a, b) =>
                    ordre === "croissant" ? a.name.localeCompare(b.name) : b.name.localeCompare(a.name)
                );
                break;

            default:
                console.warn("Type de tri inconnu :", type);
        }

        print_trips(trips);
    });

    document.getElementById("recherche").addEventListener("input", function () {
        this.value = this.value.replace(/[^a-zA-Z0-9@.]/g, "");
    })
});


