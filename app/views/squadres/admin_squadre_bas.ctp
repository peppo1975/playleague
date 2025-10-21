<?

$html->script('/js/script_my.js', false); ?>

<style>
    .intro {
        /*background-color: greenyellow;*/
    }

    .pointer {
        cursor: pointer
    }

    .sport-selection,
    .new_hour {
        display: flex;
        padding: 10px 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        margin-top: 10px;
        background: aliceblue;
    }

    .sport-selection {
        max-width: 600px;
    }

    .sport-selection select {
        width: 600px !important;
        padding: 10px;
    }

    .sport-selection select option {
        display: block;
        float: left;
        padding: 7px;
        border: 1px solid #efefef;
        margin: 2px;
        font-size: 0.9em;
    }

    .sport-selection select option:hover {
        border: 1px solid #0019ff;

    }

    .sport-selection select option:focus {
        border: 1px solid #0019ff;
        background: #fffb2545;
    }

    .new_hour {
        max-width: 600px;
    }

    .capis-table-filter h2 {
        padding-top: 10px;
        padding-left: 20px;
    }

    #from-to {
        padding: 5px 0;
        font-size: 14px;
    }

    .button-row {
        margin: 15px 0 25px;
        padding-left: 20px;
    }

    .error-input {
        border: 2px solid red;
    }





    html {
        height: 100%;
    }

    /*    body {
            height: 100%;
            background-color: yellow;
        }*/


    .container {
        display: flex;
        justify-content: left;
        align-items: center;
        align-content: center;
        flex-wrap: wrap;
        max-width: 100%;
        height: 100%;
        margin: auto;
    }

    .box {
        /*height: 50px;*/
        /*width: 75px;*/
        width: 45%;
        margin: 10px;
        /*background-color: lightgreen;*/
        /*border: 1px solid #aaa;*/
        justify-content: center;
        align-items: center;
        font-size: 1.2em;
        /*vertical-align: top;*/

    }

    button.creaBas,
    #btnInsertInElencoBas {
        padding: 5px;
        border-radius: 5px;
        background: hsla(11, 100%, 62.2%, 1);
        border: 1px solid hsla(11, 100%, 62.2%, 1);
        color: #fff;
        font-weight: bold;
        cursor: pointer;
        width: 100%;
    }

    button.aggiornaBas,
    #btnInsertInElencoBas {
        padding: 5px;
        border-radius: 5px;
        background: hsl(61.4deg 100% 51.11%);
        border: 1px solid #000;
        color: #000;
        font-weight: bold;
        cursor: pointer;
        width: 100%;

    }

    button.viewAtleti {
        padding: 5px;
        border-radius: 5px;
        background: #3e4dbe;
        border: 1px solid hsl(244.04deg 49.27% 46.95%);
        color: #fff;
        font-weight: bold;
        cursor: pointer;
        width: 100%;
    }
</style>


<style>
    /* The Modal (background) */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        padding-top: 100px;
        /* Location of the box */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 40%;
    }

    /* The Close Button */
    .close {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
</style>
<style>
    .alertAtletiNoBas {
        background-color: red;
        color: white;
        font-weight: bold;
    }
</style>
<div style="margin: 10px 10px 10px 10px">

    <h1>Tabella Squadre BAS</h1>

    <hr>

    <div class="capis-table-filter">
        <h2>Cerca</h2>
        <? // print_r($_SESSION) 
        ?>

        <!--check-->


        <div class="clear"></div>

        <div class="row">

            <div class="col-lg">

            </div>

        </div>

        <div class="container">

            <div class="box">
                <div class="row">

                    <div class="w3-col s4 sport-selection" style="display: flex;">
                        <label class="checkcontainer pointer" style="width: 80%;">
                            <strong>Squadra</strong><br>
                            <input style="padding: 8px 15px; width: 65%;" value="" class="inputSearch"
                                id="cercaSquadra">
                            <strong id="cercaSquadraRisultato"></strong>
                        </label>
                        <a id="cercaSquadraLoading" style="display: none; margin-top: 25px;">
                            <img width="16px"
                                src="https://media0.giphy.com/media/v1.Y2lkPTc5MGI3NjExZDg1c3ZtMjRsajJwcWsybWRuM2QyaWJjNThrZTJwYjdzeDhkbmNiYiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/sSgvbe1m3n93G/giphy.gif"
                                alt="alt" />
                        </a>
                    </div>

                    <div class="w3-col s4 sport-selection" style="display: flex;">
                        <label class="checkcontainer pointer">
                            <strong>Manifestazione</strong>
                            <select name="manifestazioni" id="manifestazioni">

                            </select>
                        </label>
                    </div>

                    <div id="buttonRenewViewDiv" style="display: none;">
                        <div class="w3-col s4 sport-selection" style="display: flex;">
                            <label class="checkcontainer pointer">
                                <button id="buttonRenewView">VEDI RINNOVI</button>
                            </label>
                        </div>
                    </div>


                </div>
            </div>

            <div class="box">
                <div class="row">


                    <!-- 
                        <div class="w3-col s4 sport-selection" style="display: flex;">
                            <label class="checkcontainer pointer">
                                <strong>Manifestazione</strong>
                                <select name="manifestazioni" id="manifestazioni">

                                </select>
                            </label>
                        </div> 
                    -->
                </div>
            </div>



            <div class="box" id="tableSquadre">

            </div>

        </div>
    </div>

</div>

<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="nomeSquadra">Elenco atleti</h2>
        <div>
            <div class="" id="tableAtleti">

            </div>
            <div class="" id="AvvisoTesseramenti" style="display: none; border: 1px solid #ccc; padding: 20px;">
                <h3 style="padding-bottom: 10px; margin-bottom: 10px;">Associa alla BAS gli atleti in attesa di
                    tesseramento</h3>
                <!-- <p>a questo link: <a href="/apis/atletiBASMassivi" target="_blank">Link pagina tesseramenti</a></p> -->
                <!-- //GIUSEPPE 2025-10-13 -->
                <p>a questo link: <a id="link_pagina_tesseramenti" href="/apis/atletiBASMassivi" target="_blank">Link pagina tesseramenti</a></p>
                <!-- --------------------- -->
            </div>
        </div>

    </div>

</div>

<script>
    var listAtletiSquadra;
    var bas;
    var manifestazioni = document.getElementById('manifestazioni');

    window.addEventListener("load", (event) => {

        var tbl_1 = new Table("squadre", "tableSquadre", "id", "Denominazione squadra", "Statuto", "Affiliazione", "Documento", "BAS", "Atleti", "bodySquadre");

        init();
    });

    async function init() {
        var link = "";
        var tableBody = "";
        var valueSearch = "";
        var id = "";
        var result;
        var load;

        link = "/squadres/searchSquadreBas";
        linkManifestazioniBas = "/squadres/manifestazioniBas";
        tableBody = "bodySquadre";
        id = "cercaSquadra";
        load = document.getElementById(id + "Loading");

        load.style.display = 'block';


        manBas = await httpPost(linkManifestazioniBas, {});
        await elencoManifestazioni(manBas);

        await cerca();

        // GIUSEPPE 2024-09-06 --------------------------------------------

        var isRenew = await httpPost("/squadres/isSquadreBasRenew", {});

        var numRenew = parseInt(isRenew['NumRenew']);

        if (numRenew > 0) {
            document.getElementById("buttonRenewViewDiv").style.display = null;
        } else {
            document.getElementById("buttonRenewViewDiv").style.display = 'none';
        }

        console.log(manBas);

        document.getElementById("buttonRenewView").addEventListener('click', () => {
            //alert("test");
            cerca("BasRenew");
        });
    }


    var cercaSquadra = document.getElementById("cercaSquadra");

    cercaSquadra.addEventListener("keyup", cerca);

    //    manifestazioni.addEventListener('change', () => {
    //        bodySquadre = document.getElementById('bodySquadre');
    //        bodySquadre.style.opacity = "0.3";
    //    });

    manifestazioni.addEventListener('change', cerca);

    function elencoManifestazioni(manBas) {
        manifestazioni.innerHTML = "";
        var optionAll = document.createElement('option');
        optionAll.innerHTML = "Tutte le manifestazioni";
        optionAll.value = "0";
        manifestazioni.appendChild(optionAll);

        Object.keys(manBas).forEach((i) => {

            var option = document.createElement('option');

            option.innerHTML = manBas[i].Nome;

            option.value = manBas[i].Campionato;

            //  manifestazioni.classList.add("");

            manifestazioni.appendChild(option);

        })

    }

    function vaiAllaPaginaSquadre() {
        var nome_squadra = document.getElementsByName('nome_squadra');
        Object.keys(nome_squadra).forEach((i) => {
            nome_squadra[i].addEventListener('click', async (e) => {
                console.log(e.srcElement.innerText);
                result = await httpPostNoJsonResponse('/admin/squadres/index', {
                    quickSearch: e.srcElement.innerText
                });
                window.open('/admin/squadres/index', '_blank');
            });
        });
    }


    async function cerca(filter = "") {
        console.log(filter);

        bodySquadre = document.getElementById('bodySquadre');
        bodySquadre.style.opacity = "0.3";

        var id = "cercaSquadra";

        var denominazione = cercaSquadra.value;
        var manifestazione = manifestazioni.value;
        var link = "/squadres/searchSquadreBas";
        var tableBody = "bodySquadre";
        var load = document.getElementById(id + "Loading");

        if (manifestazioni.selectedIndex != -1)
            document.getElementById('titoloManifestazione').innerHTML = manifestazioni[manifestazioni.selectedIndex].innerHTML;

        if (denominazione.length >= 3 || denominazione.length == 0) {
            load.style.display = 'block';
            var result = await httpPost(link, {
                denominazione,
                manifestazione
            });
            console.log(result);
            await addToTheTable(id, result, tableBody, denominazione);
        } else {
            var contenitore = document.getElementById(tableBody);
            contenitore.innerHTML = "";
            var infoRes = document.getElementById(id + "Risultato");
            infoRes.innerHTML = "";
        }

        if (filter == "BasRenew") { // forse non serve piu

            load.style.display = 'block';
            var result = await httpPost("/squadres/searchSquadreBasRenew", {});
            console.log(result);
            await addToTheTable(id, result, tableBody, denominazione);

            var titoloManifestazione = document.getElementById('titoloManifestazione');
            titoloManifestazione.innerHTML = "DA RINNOVARE (" + Object.keys(result.squadre).length + ")";

        }

    }

    function addToTheTable(id, resultAll, tableBody, valueSearch, filter = "") {
        var result = resultAll.squadre;
        var renew_bas = resultAll.renew_bas;
        var anno_sportivo = "";

        var file_pdf = resultAll.file_pdf;
        var contenitore = document.getElementById(tableBody);
        contenitore.style.opacity = "1";
        contenitore.innerHTML = "";

        var infoRes = document.getElementById(id + "Risultato");
        infoRes.innerHTML = Object.keys(result).length.toString() + " risultati";
        var load = document.getElementById(id + "Loading");
        load.style.display = 'none';

        Object.keys(result).forEach((value, index) => {

            var row = document.createElement("tr");
            row.classList.add("table-header");
            row.classList.add("bookersTable");
            row.classList.add("rowTeam");
            row.setAttribute('Squadra', result[value]['Squadra']);
            row.setAttribute('AnnoSportivo', result[value]['AnnoSportivo']);
            anno_sportivo = result[value]['AnnoSportivo'];

            var sendBas = true;
            var buttonAtleti = true;

            for (var i = 0; i <= 7; i++) {
                var cell = document.createElement("td");
                cell.classList.add("cella");
                cell.style.padding = '8px';
                var cellText = document.createTextNode("");
                switch (i) {
                    case 0:
                        cell.style.width = '50px';
                        cell.innerText = result[value]['ID'];
                        break;

                    case 1:
                        var den = result[value]['Nome'];

                        var position = den.toLowerCase().search(valueSearch.toLowerCase());
                        var length = valueSearch.length;

                        var subString = den.substring(position, position + length);

                        cell.innerHTML = den.replace(subString, "<strong>" + subString + "</strong>");

                        cell.setAttribute('name', 'nome_squadra');
                        cell.style.cursor = "pointer";

                        break;

                    case 2:
                        cell.style.color = 'black';
                        cell.innerHTML = "Caricato <br>" + file_pdf[value]['MEMORANDUM_ARTICLES_ASSOCIATION']['date'];

                        if (file_pdf[value]['MEMORANDUM_ARTICLES_ASSOCIATION']['date'] == "") {
                            cell.style.backgroundColor = '#eeee3757';
                            sendBas = false;
                            cell.innerHTML = "Non caricato";
                            cell.style.color = 'black';
                        }
                        break;


                    case 3:
                        cell.style.color = 'black';
                        cell.innerHTML = "Caricato <br>" + file_pdf[value]['AFFILIATION_REQUEST']['date'];
                        if (file_pdf[value]['AFFILIATION_REQUEST']['date'] == "") {
                            cell.style.backgroundColor = '#eeee3757';
                            sendBas = false;
                            cell.innerHTML = "Non caricato";
                            cell.style.color = 'black';
                        }
                        break;

                    case 4:
                        cell.style.color = 'black';
                        cell.innerHTML = "Caricato <br>" + file_pdf[value]['PRESIDENT_ID']['date'];
                        if (file_pdf[value]['PRESIDENT_ID']['date'] == "") {
                            cell.style.backgroundColor = '#eeee3757';
                            sendBas = false;
                            cell.innerHTML = "Non caricato";
                            cell.style.color = 'black';
                        }
                        break;

                    case 5:
                        var isBas = {
                            "0": "NON GENERATA → ",
                            "1": "GENERATA"
                        };
                        var respBas = isBas[result[value]['BAS']];

                        cell.innerText = respBas;
                        cell.style.color = 'black';
                        cell.style.backgroundColor = '#d1ffd1';
                        if (result[value]['BAS'] == "0") {

                            cell.innerText = "";
                            if (sendBas == true) {


                                var button = document.createElement('button');

                                // if (renew_bas.hasOwnProperty(value)) {
                                if (typeof renew_bas[value] !== 'undefined') {
                                    button.innerText = "AGGIORNA BAS";
                                    button.value = result[value]['Squadra'];
                                    button.setAttribute('anno_sportivo', result[value]['AnnoSportivo']);
                                    button.setAttribute('client_id', renew_bas[value]['client_id']);
                                    button.setAttribute('general_counsel_id', renew_bas[value]['general_counsel_id']);
                                    button.setAttribute('Squadra', renew_bas[value]['Squadra']);
                                    button.classList.add('aggiornaBas');
                                } else {
                                    button.innerText = "Genera BAS";
                                    button.value = result[value]['Squadra'];
                                    button.setAttribute('anno_sportivo', result[value]['AnnoSportivo']);
                                    button.classList.add('creaBas');

                                }



                                // if (filter = "BasRenew") {
                                //     button.setAttribute('client_id', result[value]['client_id']);
                                //     button.setAttribute('bas_id', result[value]['bas_id']);
                                // }


                                cell.appendChild(button);
                                cell.style.backgroundColor = '#fff';
                            } else {
                                cell.innerText = respBas + "Mancano documenti";
                                cell.style.backgroundColor = '#ee9999';
                                cell.style.color = 'black';
                                //buttonAtleti = false;
                            }
                        }
                        break;


                    case 6:
                        cell.innerText = "";
                        if (buttonAtleti == true) {
                            var btnAtleti = document.createElement('button');
                            btnAtleti.innerText = "Lista atleti";
                            btnAtleti.classList.add('viewAtleti');

                            btnAtleti.value = result[value]['Squadra'];

                            btnAtleti.setAttribute("id", "team_" + result[value]['Squadra'].toString() + "_" + result[value]['AnnoSportivo'].toString());

                            btnAtleti.setAttribute('anno_sportivo', result[value]['AnnoSportivo']);
                            btnAtleti.setAttribute('campionato', result[value]['Campionato']);
                            btnAtleti.setAttribute('squadra', result[value]['Squadra']);


                            btnAtleti.setAttribute('bas', sendBas);
                            cell.style.backgroundColor = '#fff';
                            cell.appendChild(btnAtleti);
                        }
                        break;
                    case 7:
                        cell.innerText = "";
                        var id = `alert-${result[value]['Squadra']}-${result[value]['AnnoSportivo']}`;
                        cell.setAttribute("id", id);

                        break;
                }

                cell.appendChild(cellText);

                row.appendChild(cell);
            }

            contenitore.appendChild(row);
        });


        var creaBas = document.getElementsByClassName('creaBas');
        Object.keys(creaBas).forEach((i) => {
            creaBas[i].addEventListener('click', associaBas);
        });

        var aggiornaBas = document.getElementsByClassName('aggiornaBas');
        Object.keys(aggiornaBas).forEach((i) => {
            aggiornaBas[i].addEventListener('click', rinnovaBas); // forse non serve piu 
        });


        var viewAtleti = document.getElementsByClassName('viewAtleti');
        var objsend = {
            "squadre": [],
            "campionati": []
        };
        Object.keys(viewAtleti).forEach((i) => {
            viewAtleti[i].addEventListener('click', vediAtletiBas);

            var campionato = viewAtleti[i].getAttribute("campionato");
            var squadra = viewAtleti[i].getAttribute("squadra");
            // objsend.push({ campionato, squadra });
            objsend.squadre.push(squadra);
            objsend.campionati.push(campionato);
        });

        contaNoBas();

        controllaAtletiMaiInseriti(objsend, anno_sportivo);

        vaiAllaPaginaSquadre();


    }

    async function contaNoBas() {
        var viewAtleti = document.getElementsByClassName("viewAtleti");
        var listaSquadre = [];
        var anno_sportivo = "";
        Object.keys(viewAtleti).forEach(async (i) => {
            var id = viewAtleti[i].getAttribute("id");
            anno_sportivo = viewAtleti[i].getAttribute("anno_sportivo");
            var squadra = viewAtleti[i].getAttribute("value");
            listaSquadre.push(squadra);

        });

        const res = await httpPost("/squadres/contaNoBas", {
            anno_sportivo,
            listaSquadre
        });

        await res.forEach((val) => {
            var squadra = val.Squadra;
            document.getElementById("team_" + squadra + "_" + anno_sportivo).style.backgroundColor = 'orange';
        });
    }


    async function controllaAtletiMaiInseriti(objsend, anno_sportivo) {

        var link = "/squadres/controllaAtletiMaiInseriti";
        var elenco = {
            "elenco": objsend,
            "anno_sportivo": anno_sportivo
        };
        const res = await httpPost(link, elenco);
        Object.keys(res).forEach((i) => {
            var value = res[i];
            var team = `team_${value.Squadra}_${anno_sportivo}`;
            var alert = `alert-${value.Squadra}-${anno_sportivo}`;
            document.getElementById(team).style.backgroundColor = 'red';
            // document.getElementById(alert).innerHTML = 'PRESENTI ATLETI MAI TESSERATI BAS';
            // document.getElementById(alert).style.backgroundColor = 'red';
            // document.getElementById(alert).style.color = 'white';
            document.getElementById(team).classList.add('alertAtletiNoBas');

            document.getElementById(alert).innerHTML = 'PRESENTI ATLETI MAI TESSERATI BAS';
            document.getElementById(alert).classList.add('alertAtletiNoBas');

        });
        if (Object.keys(res).length > 0) {
            alert("ci sono atleti mai tesserati BAS");
        }
    }

    async function associaBas(e) {
        console.log(e);
        var squadra = e.srcElement.value;
        var campionato = false;
        var anno_sportivo = e.srcElement.getAttribute('anno_sportivo');

        this.style.backgroundColor = 'yellow';
        this.style.color = '#000';
        this.innerText = "Attendi creazione BAS";


        var res = await httpPost('/admin/halfs/associaBas/', {
            squadra,
            campionato,
            anno_sportivo
        });

        if (res.response == "ERROR_BAS") {
            var message = "";
            Object.keys(res.info.errors).forEach((val) => {
                console.log(val);
                message += res.info.errors[val].join("\n") + "\n";
            });

            alert(message);
        }

        e.target.value = document.getElementById('cercaSquadra').value;
        await cerca(e);

        // alert('test');
    }


    async function rinnovaBas(e) {
        console.log(e);
        var squadra = e.srcElement.value;
        var campionato = false;
        var client_id = e.srcElement.getAttribute('client_id');
        var general_counsel_id = e.srcElement.getAttribute('general_counsel_id');

        this.style.backgroundColor = 'yellow';
        this.innerText = "Attendi creazione BAS";


        var res = await httpPost('/admin/halfs/rinnovaBas/', {
            squadra,
            client_id,
            general_counsel_id
        });
        e.target.value = document.getElementById('cercaSquadra').value;

        //await analizzaHttpPost(res, bas_id);


        await cerca(e);

        // alert('test');
    }

    async function analizzaHttpPost(res, bas_id) {
        var infoFile = [];
        if (res.info['MEMORANDUM_ARTICLES_ASSOCIATION'] == "0") {
            infoFile.push(' - Statuto');
        }
        if (res.info['AFFILIATION_REQUEST'] == "0") {
            infoFile.push(' - Richiesta affiliazione');
        }
        if (res.info['PRESIDENT_ID'] == "0") {
            infoFile.push(' - Documento di identità responsabile');
        }

        if (infoFile.length > 0) {
            var stringAlert = infoFile.join("\n");
            alert("Problemi nell'invio dei file:\n" + stringAlert);
        }

        await cerca("BasRenew");
    }

    async function vediAtletiBas(e) {
        modal.style.display = "block"; // modale;
        var AvvisoTesseramenti = document.getElementById('AvvisoTesseramenti');
        AvvisoTesseramenti.style.display = 'none';
        document.getElementById("tableAtleti").innerHTML = "";
        console.log(e);
        var squadra = e.srcElement.value;
        var campionato = e.srcElement.getAttribute('campionato');
        var anno_sportivo = e.srcElement.getAttribute('anno_sportivo');
        bas = e.srcElement.getAttribute('bas') == "true" ? true : false;

        listAtletiSquadra = await httpPost('/squadres/visualizzaAtletiBas/', {
            campionato,
            squadra,
            anno_sportivo
        });

        creaLink(listAtletiSquadra.Squadra.client_id); //GIUSEPPE 2025-10-13 -----------------------------

        createTableAtleti(listAtletiSquadra, "tableAtleti", bas);

    }

    //GIUSEPPE 2025-10-13 -----------------------------
    function creaLink(client_id) {
        let link_pagina_tesseramenti = document.getElementById("link_pagina_tesseramenti");
        link_pagina_tesseramenti.setAttribute("href", "/apis/atletiBASMassivi/?client_id=" + client_id);
    }
    //-------------------------------------------------

    function createTableAtleti(listAtletiSquadra, idDiv, bas) {

        var body = document.getElementById(idDiv);
        body.innerHTML = "";
        var tbl = document.createElement("table");
        tbl.classList.add("campis-table");
        tbl.classList.add("index_table");
        tbl.style.width = '100%';
        var tblHead = document.createElement("thead");
        var tblBody = document.createElement("tbody");

        var tr = document.createElement("tr");
        var th1 = document.createElement("th");
        var colonne = 3
        var unisciColonne = 2;

        th1.setAttribute('colspan', unisciColonne);
        th1.innerText = listAtletiSquadra['Squadra']['Denominazione'];

        var th2 = document.createElement("th");
        var btnInElencoBas = document.createElement('button');

        var AvvisoTesseramenti = document.getElementById('AvvisoTesseramenti');
        AvvisoTesseramenti.style.display = 'none';

        if (bas) {
            btnInElencoBas.innerText = "Inserisci in coda di tesseramento";
            btnInElencoBas.setAttribute('id', 'btnInsertInElencoBas');
            th2.appendChild(btnInElencoBas);

            AvvisoTesseramenti.style.display = null;
        }



        tr.appendChild(th1);
        tr.appendChild(th2);
        tblHead.appendChild(tr);

        Object.keys(listAtletiSquadra.Atleti).forEach((key) => {
            var atleta = listAtletiSquadra.Atleti[key];
            var tr = document.createElement("tr");
            tr.classList.add('table-header');
            tr.classList.add('bookersTable');
            for (i = 1; i <= colonne; i++) {
                var td = document.createElement("td");
                td.classList.add('cella');
                td.style.padding = '8px';
                switch (i) {
                    case 1:
                        td.innerText = atleta['Cognome'];
                        break;
                    case 2:
                        td.innerText = atleta['Nome'];
                        break;
                    case 3:

                        if (bas) {
                            td.innerText = "Tesserato in questa BAS";
                            tr.style.backgroundColor = '#d1ffd1';


                            if (parseInt(atleta['BAS']) == 0) {
                                td.innerText = "Non in lista tesseramento";
                                tr.style.backgroundColor = '#ff623e';
                            }


                            if (parseInt(atleta['BAS']) == -1) {

                                // atleta['BAS'] = -1 Quando è nella tabella AtletiBAS ma ha il client_id = 0
                                td.innerText = "In attesa di tesseramento (usa il link riportato sotto)";
                                tr.style.backgroundColor = '#eeee3757';
                            }
                        }
                        break;
                }
                tr.appendChild(td);
            }
            tblBody.appendChild(tr);
        });


        tbl.appendChild(tblHead);
        tbl.appendChild(tblBody);
        // put <table> in the <body>
        body.appendChild(tbl);


        var btnInsertInElencoBas = document.getElementById('btnInsertInElencoBas');
        btnInsertInElencoBas.addEventListener('click', addAtletiInBAS, true);
    }

    async function addAtletiInBAS() {
        console.log(listAtletiSquadra);
        var res = await httpPost('/squadres/inserisciAtletiBas/', listAtletiSquadra);

        var squadra = listAtletiSquadra['Squadra']['ID'];
        var anno_sportivo = listAtletiSquadra['Squadra']['AnnoSportivo'];

        listAtletiSquadra = await httpPost('/squadres/visualizzaAtletiBas/', {
            squadra,
            anno_sportivo
        });

        createTableAtleti(listAtletiSquadra, "tableAtleti", bas);

    }

    /* ----------------------------------------------- */
    class Table {
        constructor(name, section, firstCol, secondCol, thirdCol, fourthCol, fifthCol, sixthCol, seventhCol, idBody) {
            this.name = name;
            this.section = section;
            this.firstCol = firstCol;
            this.secondCol = secondCol;
            this.thirdCol = thirdCol;
            this.fourthCol = fourthCol;
            this.fifthCol = fifthCol;
            this.sixthCol = sixthCol;
            this.seventhCol = seventhCol;
            this.idBody = idBody
            this.headerTable();
        }

        headerTable() {
            var body = document.getElementById(this.section);

            var h = document.createElement('h3');
            h.setAttribute('id', 'titoloManifestazione');
            h.innerHTML = "";
            body.appendChild(h);

            var tbl = document.createElement("table");

            tbl.classList.add("campis-table");
            tbl.classList.add("index_table");
            tbl.style.width = '100%';
            var tblHead = document.createElement("thead");
            var tblBody = document.createElement("tbody");
            tblBody.id = this.idBody;
            var tr = document.createElement("tr");

            [this.firstCol, this.secondCol, this.thirdCol, this.fourthCol, this.fifthCol, this.sixthCol, this.seventhCol].forEach((val, index) => {
                var th = document.createElement("th");

                th.innerText = val;
                tr.appendChild(th);

                switch (index) {
                    case 0:
                        th.style.width = '50px';
                        break;

                    case 1:
                        break;
                    case 2:
                        break;
                    case 3:
                        break;
                    case 4:
                        break;
                    case 5:
                        break;
                    case 6:

                        break;
                }
            });

            tblHead.appendChild(tr);


            // cells creation
            for (var j = 0; j < 1; j++) {
                // table row creation
                var row = document.createElement("tr");

                for (var i = 0; i < 2; i++) {
                    // create element <td> and text node 
                    //Make text node the contents of <td> element
                    // put <td> at end of the table row
                    var cell = document.createElement("td");
                    var cellText = document.createTextNode("");
                    switch (i) {
                        case 0:
                            cell.style.width = '50px';
                            break;

                        case 1:
                            break;
                    }
                    cell.appendChild(cellText);
                    row.appendChild(cell);


                }

                //row added to end of table body
                tblBody.appendChild(row);
            }

            // append the <tbody> inside the <table>

            tbl.appendChild(tblHead);
            tbl.appendChild(tblBody);
            // put <table> in the <body>
            body.appendChild(tbl);

        }

    }
</script>







<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close");
    Object.keys(span).forEach((i) => {
        span[i].addEventListener('click', () => {
            modal.style.display = "none";
        });
    })

    // When the user clicks the button, open the modal 
    //    btn.onclick = function ()
    //    {
    //        modal.style.display = "block";
    //    }

    // When the user clicks on <span> (x), close the modal
    //    span.onclick = function ()
    //    {
    //        modal.style.display = "none";
    //    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>