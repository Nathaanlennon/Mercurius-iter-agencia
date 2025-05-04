// Déclaration d'une liste vide pour stocker les voyages
let trips = [];

// Fonction pour afficher dynamiquement une liste de voyages dans la page
function print_trips(trips_list) {
    // On vide d'abord la zone d'affichage des résultats, puis on crée une div pour chaque voyage,
    // avec ses informations de base (nom, prix, durée). Un clic sur une div redirige vers une page de détails.
    const result = document.getElementById("trips");
    result.innerHTML = "";
    trips_list.forEach(trip => {
        const div = document.createElement("div");
        div.className = "result";
        div.innerHTML = trip.name + "<br> Prix minimum : " + trip.price * nb_personnes + "€" +
            "<br> Durée : " + trip.duration + " jours";
        div.onclick = function () {
            window.location = "voyage_sheet.php?id=" + trip.id;
        };
        result.appendChild(div);
    });
}

// Code exécuté automatiquement au chargement de la page
window.addEventListener("load", function () {
    // On ajoute un écouteur sur le formulaire de tri pour réagir à chaque modification
    const form = document.getElementById("tri");
    form.addEventListener("change", function () {
        // Récupération du type et de l'ordre de tri choisis par l'utilisateur
        const type = document.getElementById("type").value;
        const ordre = document.getElementById("ordre").value;

        // En fonction du type de tri sélectionné (prix, durée ou nom),
        // on trie la liste des voyages dans l'ordre demandé
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
                // Si le type de tri est inconnu, on affiche un message d'avertissement dans la console
                console.warn("Type de tri inconnu :", type);
        }

        // Une fois triée, la nouvelle liste de voyages est réaffichée
        print_trips(trips);
    });
});
