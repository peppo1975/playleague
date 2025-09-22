<!doctype html>
<style>
    .autocomplete-items {
        /*position: absolute;*/
        /*position: relative;*/
        /*border: 1px solid #d4d4d4;*/
        border: 3px solid yellowgreen;
        border-bottom: none;
        border-top: none;
        z-index: 99;
        /*position the autocomplete items to be the same width as the container:*/
        top: 100%;
        left: 0;
        right: 0;
    }

    .autocomplete-items div {
        padding: 10px;
        cursor: pointer;
        background-color: #fff;
        border-bottom: 1px solid #d4d4d4;
    }

    /*when hovering an item:*/
    .autocomplete-items div:hover {
        background-color: #e9e9e9;
    }

    /*when navigating through the items using the arrow keys:*/
    .autocomplete-active {
        background-color: DodgerBlue !important;
        color: #ffffff;
    }
</style>
<html>
    <head>
        <title>title</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    </head>
    <body>
        <nav class="navbar navbar-expand-lg bg-body-tertiary" hidden="">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Navbar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                        </li>
                    </ul>
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>

        <div class="container-fluid" style="padding-top: 45px;">
            <div class="row">
                <div class="col-lg">
                    <button class="btn btn-outline-success" id="associa_tutte">Associa tutte città</button>
                    <br>
                    <br>
                    <label>città</label>
                    <id id="citta"></id>
                    <br>
                    <label>associate</label>
                    <id id="presente"></id>
                    <br>
                    <label>non associate</label>
                    <id id="non_presente"></id>
                    <br>
                    <label>controllate</label>
                    <id id="controllati"></id>/<id id="totali"></id>
                    <br>
                </div>
                <div class="col-lg">
                    <button class="btn btn-outline-success" tipo="bas" id="citta_bas_non_associate">Citta BAS non associate</button>
                </div>
                <div class="col-lg">
                    <button class="btn btn-outline-success" tipo="tutte" id="citta_non_associate">Citta non associate</button>
                </div>
            </div>


            <hr>
            <div class="row">
                <div class="col-lg" id="bas_no_city">

                </div>
                <div class="col-lg" id="peole_bas_no_city">

                </div>
            </div>


        </div>






        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


        <script>
            var atleti = <?= json_encode($res['CITY_ALL']) ?>;

            var link = "https://<?= $_SERVER['HTTP_HOST'] ?>/apis/associaCittaAtletiPresentiDB";
            var link_2 = "https://<?= $_SERVER['HTTP_HOST'] ?>/apis/associaCittaAtletiPresentiBAS";
            var link_3 = "https://<?= $_SERVER['HTTP_HOST'] ?>/apis/cittaNonAssociateAll";
            var atletiCitta = {};

            var associa_tutte = document.getElementById("associa_tutte");
            var i = 0;
            var presente = 0;
            var non_presente = 0;

            document.getElementById("totali").innerHTML = atleti.length;

            associa_tutte.addEventListener('click', () => {

                document.getElementById("bas_no_city").innerHTML = "";
                send(link, atleti[i]);

            });


            document.getElementById("citta_bas_non_associate").addEventListener('click', cittaBasNonAssociate);
            document.getElementById("citta_non_associate").addEventListener('click', cittaBasNonAssociate);


            async function send(link, to_send)
            {


                if (atleti.length == i)
                {
                    i = 0;
                    cittaBasNonAssociate();

                    return;
                }

                const d = await httpPost(link, to_send);
                console.log(d);

                document.getElementById("citta").innerHTML = d['LuogoNascita'];
                document.getElementById("controllati").innerHTML = i + 1;

                switch (d['presente'])
                {
                    case true:
                        presente++;
                        document.getElementById("presente").innerHTML = presente;
                        break;

                    case false:
                        non_presente++;
                        document.getElementById("non_presente").innerHTML = non_presente;
                        break;
                }

                i++;

                send(link, atleti[i]);
            }



            async function cittaBasNonAssociate(e)
            {
//                alert(t);

                var type = this.getAttribute('tipo');

                var link_send = "";

                var titleText = "";

                switch (type)
                {
                    case "bas":
                        link_send = link_2;
                        titleText = "BAS"
                        break;

                    case "tutte":
                        link_send = link_3;
                        titleText = "TUTTE"
                        break;
                }

                document.getElementById("bas_no_city").innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
                document.getElementById("peole_bas_no_city").innerHTML = "";
                const g = await httpPost(link_send, {});
                atletiCitta = g;

                document.getElementById("bas_no_city").innerHTML = '';


                const title = document.createElement("h3");
                title.innerHTML = titleText + " (" + Object.keys(g).length + ")";
                document.getElementById("bas_no_city").appendChild(title);

                const button = document.createElement("button");
                button.classList.add("btn");
                button.classList.add("btn-outline-success");
                button.setAttribute("id", "save_all_city_not_bas");
                button.innerText = "SALVA TUTTO";
                document.getElementById("bas_no_city").appendChild(button);



                Object.keys(g).forEach((i, index) => {

                    const form = document.createElement("div");
                    form.classList.add("row");
                    form.classList.add("g-3");

//                    form.classList.add("was-validated");
                    form.setAttribute("id", "form_" + index);

                    const div_1 = document.createElement("div");
                    div_1.classList.add("col-auto");
                    const input_1 = document.createElement("input");
                    input_1.setAttribute("type", "text");
                    input_1.classList.add("form-control-plaintext");
                    input_1.readOnly = true;
                    input_1.value = i;

                    const div_2 = document.createElement("div");
                    div_2.classList.add("col-auto");
                    const input_2 = document.createElement("input");
                    input_2.setAttribute("id", "city_err_" + index);
                    input_2.setAttribute("form_id", "form_" + index);
                    input_2.setAttribute("city_nascita", 0);
                    input_2.setAttribute("type", "text");
                    input_2.setAttribute("size", "30");
                    input_2.setAttribute("nome_iniziale", i);
                    input_2.classList.add("form-control");
                    input_2.classList.add("myInputCitta");
                    input_2.value = i;


                    form.appendChild(div_1);
                    div_1.appendChild(input_1);

                    form.appendChild(div_2);
                    div_2.appendChild(input_2);

                    const div_3 = document.createElement("div");
                    div_3.classList.add("col-auto");
                    const button_dettaglio = document.createElement("button");
                    button_dettaglio.innerText = "Dettagli atleti";
                    button_dettaglio.classList.add("btn");
                    button_dettaglio.classList.add("btn-outline-success");
                    button_dettaglio.classList.add("btn-sm");
                    button_dettaglio.classList.add("button_dettaglio");
                    button_dettaglio.value = i;

                    form.appendChild(div_3);
                    div_3.appendChild(button_dettaglio);

                    document.getElementById("bas_no_city").appendChild(form);
                });

                classInputCitta();

                button.addEventListener('click', saveAllCityNoBas);

                const button_dettaglio = document.getElementsByClassName("button_dettaglio");

                Object.keys(button_dettaglio).forEach((i) => {
                    button_dettaglio[i].addEventListener('click', dettagliAtleti);
                })
            }

            function classInputCitta()
            {
                var myInputCitta = document.getElementsByClassName("myInputCitta");

                Object.keys(myInputCitta).forEach((i) => {
                    myInputCitta[i].addEventListener('keyup', richiamaCity);
                });

            }

            function dettagliAtleti(e)
            {
                console.log(e);

                var value = e.srcElement.value;

                console.log(atletiCitta[value]);

                document.getElementById("peole_bas_no_city").innerHTML = "";

                var array = {}
                Object.keys(atletiCitta[value][0]).map((i) => {
                    var key = atletiCitta[value][0][i]['Atleta'];
                    array[key] = atletiCitta[value][0][i];
                });

                const h = document.createElement("h3");
                h.innerHTML = value;
                document.getElementById("peole_bas_no_city").appendChild(h);

                createTableAtleti(array);

            }

            function createTableAtleti(atletiCitta_value)
            {
                const table = document.createElement("table");
                const thead = document.createElement("thead");
                const tbody = document.createElement("tbody");

                table.classList.add("table");
                table.appendChild(thead);
                table.appendChild(tbody);

                var title = ["Cognome", "Nome", "Data nascita", "Email", "Telefono", "Cellulare"];
                const r_h = document.createElement("tr");
                thead.appendChild(r_h);
                Object.keys(title).forEach((i) => {
                    const c = document.createElement("th");
                    const text = document.createTextNode(title[i]);
                    c.appendChild(text);
                    r_h.appendChild(c);
                })

                

                Object.keys(atletiCitta_value).forEach((i) => {
                    //atletiCitta[i];
                    var t = document.createElement("tr");

                    var c1 = document.createElement("td");
                    var t1 = document.createTextNode(atletiCitta_value[i].Cognome);
                    c1.append(t1);


                    var c2 = document.createElement("td");
                    var t2 = document.createTextNode(atletiCitta_value[i].Nome);
                    c2.append(t2);

                    var c3 = document.createElement("td");
                    var t3 = document.createTextNode(atletiCitta_value[i].DataNascitaFormat);
                    c3.append(t3);

                    var c4 = document.createElement("td");
                    var t4 = document.createTextNode(atletiCitta_value[i].Email);
                    c4.append(t4);

                    var c5 = document.createElement("td");
                    var t5 = document.createTextNode(atletiCitta_value[i].Telefono);
                    c5.append(t5);

                    var c6 = document.createElement("td");
                    var t6 = document.createTextNode(atletiCitta_value[i].Cellulare);
                    c6.append(t6);


                    t.appendChild(c1);
                    t.appendChild(c2);
                    t.appendChild(c3);
                    t.appendChild(c4);
                    t.appendChild(c5);
                    t.appendChild(c6);

                    tbody.appendChild(t);
                });

                document.getElementById("peole_bas_no_city").appendChild(table);

            }

            async function saveAllCityNoBas()
            {
                var to_send = {};
                var myInputCitta = document.getElementsByClassName("myInputCitta");

                Object.keys(myInputCitta).forEach((i) => {
                    console.log(myInputCitta[i]);
                    const city_nascita = myInputCitta[i].getAttribute('city_nascita');
                    const nome_iniziale = myInputCitta[i].getAttribute('nome_iniziale');
                    const nome = myInputCitta[i].value;

                    if (parseInt(city_nascita) > 0)
                    {
                        to_send[city_nascita] = {};
                        to_send[city_nascita]['nome_iniziale'] = nome_iniziale;
                        to_send[city_nascita]['nome'] = nome;
                    }
                });

                var link = "https://<?= $_SERVER['HTTP_HOST'] ?>/apis/salvaCittaBasNonAssociate";
                const f = await httpPost(link, to_send);


                console.log(f);

                document.getElementById("citta_bas_non_associate").click();
            }

            async function richiamaCity(e)
            {
                console.log(e);
                const countries = await listCity(e);
                await analizza(countries, e);
            }

            function listCity(e)
            {
                return new Promise((resolve, reject) => {

                    var link = "/apis/cities";
                    var to_send = {"city_name": e.srcElement.value};
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", link);
                    xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
                    const body = JSON.stringify(to_send);
                    xhr.send(body);
                    xhr.onload = () => {

                        if (xhr.readyState == 4 && xhr.status == 200)
                        {
                            var arr = JSON.parse(xhr.response);

                            resolve(arr);
                        }
                        else
                        {
                            reject(new Error(xhr.statusText));
                        }
                    };
                });
            }


            function analizza(arr, e)
            {
                var a, b, i, val = e.srcElement.value;
                var id_input = e.srcElement.id;
                var form_id = document.getElementById(id_input).getAttribute("form_id");
                /*close any already open lists of autocompleted values*/
                closeAllLists(id_input);
                if (!val)
                {
                    return false;
                }
                currentFocus = -1;
                /*create a DIV element that will contain the items (values):*/
                a = document.createElement("DIV");
                a.setAttribute("id", e.srcElement.id + "autocomplete-list");
                a.setAttribute("class", "autocomplete-items");
                /*append the DIV element as a child of the autocomplete container:*/
                e.srcElement.parentNode.appendChild(a);
                /*for each item in the array...*/

                Object.keys(arr).map((i) => {
                    /*check if the item starts with the same letters as the text field value:*/
                    if (arr[i].city_name.substr(0, val.length).toUpperCase() == val.toUpperCase())
                    {
                        /*create a DIV element for each matching element:*/
                        b = document.createElement("DIV");
                        /*make the matching letters bold:*/
                        b.innerHTML = "<strong>" + arr[i].city_name.substr(0, val.length) + "</strong>";
                        b.innerHTML += arr[i].city_name.substr(val.length);
                        /*insert a input field that will hold the current array item's value:*/
                        b.innerHTML += "<input type='hidden' id='" + i + "' value='" + arr[i].city_name + "'>";
                        /*execute a function when someone clicks on the item value (DIV element):*/
                        b.addEventListener("click", function (e) // quando clicco sull'elenco città
                        {
                            /*insert the value for the autocomplete text field:*/
//                            var nome = this.getElementsByTagName("input")[0].value;
                            var nome = e.srcElement.innerText;
                            var nom = this.getElementsByTagName("input");
                            var index = this.getElementsByTagName("input")[0].id;
                            console.log(id_input + " of select");
                            document.getElementById(id_input).value = nome;
                            document.getElementById(id_input).setAttribute("city_nascita", index);
                            document.getElementById(form_id).classList.add("was-validated");
                            // -------

                            console.log(arr[index]);

                            /*close the list of autocompleted values,
                             (or any other open lists of autocompleted values:*/
                            closeAllLists();
                        });
                        a.appendChild(b);
                    }
                });
            }



            function closeAllLists(elmnt)
            {
                /*close all autocomplete lists in the document,
                 except the one passed as an argument:*/
                var x = document.getElementsByClassName("autocomplete-items");

                for (var i = 0; i < x.length; i++)
                {
                    x[i].parentNode.removeChild(x[i]);
                }
            }

            function addActive(x)
            {
                /*a function to classify an item as "active":*/
                if (!x)
                    return false;
                /*start by removing the "active" class on all items:*/
                removeActive(x);
                if (currentFocus >= x.length)
                    currentFocus = 0;
                if (currentFocus < 0)
                    currentFocus = (x.length - 1);
                /*add class "autocomplete-active":*/
                x[currentFocus].classList.add("autocomplete-active");
            }

            function removeActive(x)
            {
                /*a function to remove the "active" class from all autocomplete items:*/
                for (var i = 0; i < x.length; i++)
                {
                    x[i].classList.remove("autocomplete-active");
                }
            }

            document.addEventListener("click", function (e)
            {
                closeAllLists(e.target);
            });

        </script>
        <script>
            function httpPost(link, to_send)
            {
                return new Promise((resolve, reject) => {

                    const xhr = new XMLHttpRequest();

                    xhr.open("POST", link);

                    xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");

                    const body = JSON.stringify(to_send);

                    xhr.send(body);

                    xhr.onload = () => {

                        if (xhr.readyState == 4 && xhr.status == 200)
                        {
                            var arr = JSON.parse(xhr.response);
                            resolve(arr);
                        }
                        else
                        {
                            reject(new Error(xhr.statusText));
                        }
                    };
                });
            }
        </script>


    </body>
</html>





