let activities_price = [10, 20, 30, 0];
let transports_price = [100, 60, 80, 50];
let nb_personnes = 1;


async function fetchData() {
    const response = await fetch("../json/villes_activites.json");
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    const data = Object.values(await response.json());

    let filteredData = {};
    data.forEach(item => {
        if (stages.includes(item["name"])) {
            filteredData[item["name"]] = [item["activities"], item["price"]];
        }
    });

    return filteredData;  // retourne l'objet filtré
}

// Calcule le prix total d'un stage donné
function calcul_stage(stage, nb_personnes, data) { //data = les activités et leurs prix
    // stage est un tableau : [niveauHotel, activités[], transport]
    let total = 0;

    // [0] : niveau hôtel
    const niveauHotel = stage[0] ?? 1;
    total += (2 ** (niveauHotel - 1)) * 25 * 2;

    // [1] : activités
    const activites = stage[1] ?? 0;
    console.log(data);

    for (let i = 0; i < 4; i++) {
        total += (data[1][i] * ((activites & (1 << i)) !== 0));
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
function calcul_price(data_price, nb_personnes = 1, data) {
    let total = 100 * nb_personnes;

    for (let i = 0; i < data_price.length; i++) {
        total += calcul_stage(data_price[i], nb_personnes, data[stages[i]]);
    }

    return total;
}


window.addEventListener("load", function () {
    console.log(price);


    let data_stages = fetchData().then(data => {
        /** @type {HTMLInputElement} */
        const number_input = document.getElementById("nb_personnes");
        number_input.addEventListener("change", () => {
            nb_personnes = number_input.value;
            console.log(nb_personnes)
            document.getElementById("avion").textContent = (nb_personnes * 100).toString();
            document.getElementById("price").textContent = calcul_price(price, nb_personnes, data).toString();

        });
        document.getElementById("price").textContent = calcul_price(price, nb_personnes, data).toString();

        //pour le prix de l'avion de départ
        document.getElementById("avion").textContent = (nb_personnes * 100).toString();
        console.log("data stages");
        console.log(data_stages);

        const form = document.getElementById("form");
        // console.log(stages);

        stages.forEach(function (stage, index) {
            const div = document.getElementById(`${index}`);
            div.innerHTML = "";
            div.innerHTML = `
                <span class='name'>${stage} :</span><br>
                Niveau hôtel :
                <select name="${index}1">
                    <option value="1" ${price[index][0] !== 1 ? "" : "selected"}>1</option>
                    <option value="2" ${price[index][0] === 2 ? "selected" : ""}>2</option>
                    <option value="3" ${price[index][0] === 3 ? "selected" : ""}>3</option>
                    <option value="4" ${price[index][0] === 4 ? "selected" : ""}>4</option>
                    <option value="5" ${price[index][0] === 5 ? "selected" : ""}>5</option>
                </select><br>
                Activités :`;
            for (let i = 0; i < data[stage][0].length; i++) {
                div.innerHTML += `<label><input type='checkbox' name="${index}2[]" value="${i + 1}" ${(price[index][1] & (2 ** (i))) !== 0 ? 'checked' : ''}> ${data[stage][0][i]}</label>`;
            }
            div.innerHTML += `
                <br> Transport :
                <label><input type='radio' name="${index}3" value="1" ${price[index][2] !== 1 ? "" : "checked"}> avion</label>
                <label><input type='radio' name="${index}3" value="2" ${price[index][2] === 2 ? "checked" : ""}> voiture</label>
                <label><input type='radio' name="${index}3" value="3" ${price[index][2] === 3 ? "checked" : ""}> bateau</label>
                <label><input type='radio' name="${index}3" value="4" ${price[index][2] === 4 ? "checked" : ""}> train</label>
                <br>
                <br>

                `;

        });


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
                    let fetchprice = fetchData().then(data => {
                        document.getElementById("price").textContent = calcul_price(price, nb_personnes, data).toString();
                    })
                    fetchprice = null;
                })

            });

        });
    });
    data_stages = null;


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