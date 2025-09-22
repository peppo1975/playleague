<? $html->script('/js/script_my.js', false); ?>

<style>
    .intro {
        /*background-color: greenyellow;*/
    }
    .pointer {
        cursor: pointer
    }

    .sport-selection,
    .new_hour{
        display: flex;
        padding: 10px 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        margin-top: 10px;
        background: aliceblue;
    }

    .sport-selection{
        max-width: 600px;
    }

    .sport-selection select{
        width: 600px !important;
        padding: 10px;
    }

    .sport-selection select option{
        display: block;
        float: left;
        padding: 7px;
        border: 1px solid #efefef;
        margin: 2px;
        font-size: 0.9em;
    }

    .sport-selection select option:hover{
        border: 1px solid #0019ff;

    }

    .sport-selection select option:focus{
        border: 1px solid #0019ff;
        background: #fffb2545;
    }

    .new_hour{
        max-width: 600px;
    }

    .capis-table-filter h2{
        padding-top: 10px;
        padding-left: 20px;
    }

    #from-to{
        padding: 5px 0;
        font-size: 14px;
    }

    .button-row{
        margin: 15px 0 25px;
        padding-left: 20px;
    }

    .error-input{
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

</style>



<div style="margin: 10px 10px 10px 10px">

    <h1>Tabella ID CAMPIONATI</h1>

    <hr>

    <div class="capis-table-filter">
        <h2>Cerca</h2>
        <? // print_r($_SESSION) ?>

        <!--check-->




        <div class="clear"></div>

        <div class="row">

            <div class="col-lg">

            </div>

        </div>
        <!--                <div class="row" style="margin: 10px 0px 0px 0px">
                            <div class="col-lg " id="response" style="width: 100%; float:left">
                                <img src="https://media2.giphy.com/media/3oEjI6SIIHBdRxXI40/200.gif" alt="alt"/>
                            </div>
                        </div>-->
        <div class="container">

            <div class="box">
                <div class="row" >

                    <div class="w3-col s4 sport-selection" style="display: flex;">
                        <label class="checkcontainer pointer">
                            <strong>Squadra</strong>
                            <input value="" class="inputSearch" id="cercaSquadra">
                            <strong id="cercaSquadraRisultato"></strong>


                        </label>
                        <a id="cercaSquadraLoading" style="display: none">
                            <img width="16px" src="https://media0.giphy.com/media/v1.Y2lkPTc5MGI3NjExZDg1c3ZtMjRsajJwcWsybWRuM2QyaWJjNThrZTJwYjdzeDhkbmNiYiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/sSgvbe1m3n93G/giphy.gif" alt="alt"/>
                        </a>
                    </div>

                </div>
            </div>

            <div class="box">
                <div class="row"  hidden="">

                    <div class="w3-col s4 sport-selection" style="display: flex;">

                        <label class="checkcontainer pointer">
                            <strong>Campionato Playleague</strong>
                            <input value="" class="inputSearch"  id="cercaCampionato">
                            <strong id="cercaCampionatoRisultato"></strong>
                            <a id="cercaCampionatoLoading"style="display: none">
                                <img width="16px" src="https://media0.giphy.com/media/v1.Y2lkPTc5MGI3NjExZDg1c3ZtMjRsajJwcWsybWRuM2QyaWJjNThrZTJwYjdzeDhkbmNiYiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/sSgvbe1m3n93G/giphy.gif" alt="alt"/>
                            </a>
                        </label>
                    </div>

                </div>
            </div>


            <div class="box" id="tableSquadre">

            </div>

            <div class="box" id="tableCampionati">

            </div>

        </div>
    </div>

</div>

<script>

    window.addEventListener("load", (event) => {

        var tbl_1 = new Table("squadre", "tableSquadre", "id", "Denominazione squadra", "bodySquadre");
//        var tbl_2 = new Table("campionati", "tableCampionati", "id", "Denominazione campionato", "bodyCampionati");

        init();

    });

    async function init()
    {
        var link = "";
        var tableBody = "";
        var valueSearch = "";
        var id = "";
        var result;
        var load;

        link = "/admin/squadres/searchSquadre";
        tableBody = "bodySquadre";
        id = "cercaSquadra";
        load = document.getElementById(id + "Loading");

        load.style.display = 'block';
        result = await httpPost(link, {valueSearch});
        console.log(result);
        await addToTheTable(id, result, tableBody, valueSearch);

//        link = "/admin/campionatis/searchCampionati";
//        tableBody = "bodyCampionati";
//        id = "cercaCampionato";
//        load = document.getElementById(id + "Loading");
//
//        load.style.display = 'block';
//        result = await httpPost(link, {valueSearch});
//        console.log(result);
//        await addToTheTable(id, result, tableBody, valueSearch);

    }

    var inputSearch = document.getElementsByClassName("inputSearch");
    Object.keys(inputSearch).forEach((val, index) => {
        inputSearch[index].addEventListener("keyup", cerca);
    });




    async function cerca(e)
    {
        console.log(e);
        var id = e.target.id;
        var valueSearch = e.target.value;
        var link = "";
        var tableBody = "";
        var load = document.getElementById(id + "Loading");
        switch (id)
        {
            case "cercaSquadra":
                link = "/admin/squadres/searchSquadre";
                tableBody = "bodySquadre";
                break;

            case "cercaCampionato":
                link = "/admin/campionatis/searchCampionati";
                tableBody = "bodyCampionati";
                break;
        }

        if (valueSearch.length >= 3 || valueSearch.length == 0)
        {
            load.style.display = 'block';
            var result = await httpPost(link, {valueSearch});
            console.log(result);
            await addToTheTable(id, result, tableBody, valueSearch);
        }
        else
        {
            var contenitore = document.getElementById(tableBody);
            contenitore.innerHTML = "";
            var infoRes = document.getElementById(id + "Risultato");
            infoRes.innerHTML = "";
        }

    }

    function addToTheTable(id, result, tableBody, valueSearch)
    {

        var contenitore = document.getElementById(tableBody);

        contenitore.innerHTML = "";

        var infoRes = document.getElementById(id + "Risultato");
        infoRes.innerHTML = result.length.toString() + " risultati";
        var load = document.getElementById(id + "Loading");
        load.style.display = 'none';

        Object.keys(result).forEach((value, index) => {

            var row = document.createElement("tr");
            row.classList.add("table-header");
            row.classList.add("bookersTable");

            for (var i = 0; i < 2; i++)
            {
                var cell = document.createElement("td");
                cell.classList.add("cella");
                cell.style.padding = '8px';
                var cellText = document.createTextNode("");
                switch (i)
                {
                    case 0:
                        cell.style.width = '50px';
                        cell.innerText = result[index]['ID'];
                        break;

                    case 1:
                        var den = result[index]['Nome'];

                        var position = den.toLowerCase().search(valueSearch.toLowerCase());
                        var length = valueSearch.length;

                        var subString = den.substring(position, position + length);
//                        
                        cell.innerHTML = den.replace(subString, "<strong>" + subString + "</strong>");
                        var span = document.createElement('span');
//

//                        cell.innerText = den;

                        break;
                }

                cell.appendChild(cellText);

                row.appendChild(cell);
            }

            //row added to end of table body
            contenitore.appendChild(row);
        });

        //contenitore.remove();

    }




    /* ----------------------------------------------- */
    class Table
    {
        constructor(name, section, firstCol, secondCol, idBody)
        {
            this.name = name;
            this.section = section;
            this.firstCol = firstCol;
            this.secondCol = secondCol;
            this.idBody = idBody
            this.headerTable();
        }

        headerTable()
        {
            var body = document.getElementById(this.section);

            var tbl = document.createElement("table");

            tbl.classList.add("campis-table");
            tbl.classList.add("index_table");
            tbl.style.width = '100%';
            var tblHead = document.createElement("thead");
            var tblBody = document.createElement("tbody");
            tblBody.id = this.idBody;
            var tr = document.createElement("tr");

            [this.firstCol, this.secondCol].forEach((val, index) => {
                var th = document.createElement("th");

                th.innerText = val;
                tr.appendChild(th);

                switch (index)
                {
                    case 0:
                        th.style.width = '50px';
                        break;

                    case 1:
                        break;
                }
            });

            tblHead.appendChild(tr);


            // cells creation
            for (var j = 0; j < 1; j++)
            {
                // table row creation
                var row = document.createElement("tr");

                for (var i = 0; i < 2; i++)
                {
                    // create element <td> and text node 
                    //Make text node the contents of <td> element
                    // put <td> at end of the table row
                    var cell = document.createElement("td");
                    var cellText = document.createTextNode("");
                    switch (i)
                    {
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
            // tbl border attribute to 
//            tbl.setAttribute("border", "2");


//           tblBody.remove();

        }

    }
</script>








