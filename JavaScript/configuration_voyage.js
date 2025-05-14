let activities_price = [10, 20, 30, 0];
let transports_price = [100, 60, 80, 50];
let nb_personnes = 1;


// Calcule le prix total d'un stage donné
function calcul_stage(stage, nb_personnes) {
    // stage est un tableau : [niveauHotel, activités[], transport]
    let total = 0;

    // [0] : niveau hôtel
    const niveauHotel = stage[0] ?? 1;
    total += (2 ** (niveauHotel - 1)) * 25 * 2;

    // [1] : activités
    const activites = stage[1] ?? 0;
    for (let i = 0; i < 4; i++) {
        total += (activities_price[i] * ((activites & (1 << i)) !== 0));
    }

    // [2] : transport
    const transport = stage[2] ?? 1;
    total += transports_price[transport - 1]

    stage.forEach(s => {
        console.log(typeof s);
    })

    return total * nb_personnes;
}

// Calcule le prix total pour tous les stages
function calcul_price(data, nb_personnes = 1) {
    let total = 100 * nb_personnes;

    data.forEach(stage => {
        total += calcul_stage(stage, nb_personnes);
    });

    return total;
}



window.addEventListener("load", function (){
    console.log(price);

    /** @type {HTMLInputElement} */
    const number_input = document.getElementById("nb_personnes");
    number_input.addEventListener("change", () => {
        nb_personnes = number_input.value;
        console.log(nb_personnes)
        document.getElementById("avion").textContent = (nb_personnes * 100).toString();
        document.getElementById("price").textContent = calcul_price(price, nb_personnes).toString();

    });
    document.getElementById("price").textContent = calcul_price(price, nb_personnes).toString();


    const trips = document.getElementById("form").querySelectorAll('div.trip');
    trips.forEach((trip, index_trip) => {
        const champs = trip.querySelectorAll('select, input, button');
        console.log(champs);


        champs.forEach(champ => {
            champ.addEventListener('input', () => {
                switch (champ.nodeName) {
                    case 'SELECT':
                        // console.log("select"+ index_trip);
                        price[index_trip][0] = champ.value;
                        break;
                    case 'INPUT':
                        // console.log("input"+ index_trip);
                        switch (champ.type) {
                            case 'checkbox':
                                console.log("checkbox" + index_trip);
                                console.log("oui" + champ.value);
                                price[index_trip][1] ^= (1 << champ.value - 1);
                                break;
                            case 'radio':
                                // console.log("radio" + index_trip);
                                price[index_trip][2] = champ.value;
                                break;
                        }
                        break;
                }
                console.log(price);
                document.getElementById("price").textContent = calcul_price(price, nb_personnes).toString();
            })

        });

    });

    //ici on met à jour l'url
    const form = document.getElementById("form");

    form.addEventListener("input", (event) => {
        const input = event.target;
        const name = input.name;
        const currentParams = new URLSearchParams(window.location.search);

        if (input.type === "checkbox") {
            const checkboxes = form.querySelectorAll(`input[name="${name}"]`);
            const selected = [];

            checkboxes.forEach(cb => {
                if (cb.checked) selected.push(cb.value);
            });

            currentParams.delete(name);
            selected.forEach(val => currentParams.append(name, val));

        } else if (input.type === "radio") {
            currentParams.set(name, input.value);

        } else {
            if (input.value) {
                currentParams.set(name, input.value);
            } else {
                currentParams.delete(name);
            }
        }

        const newUrl = window.location.pathname + "?" + currentParams.toString();
        history.replaceState({}, "", newUrl);
    });
});