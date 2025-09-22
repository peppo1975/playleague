<!-- //GIUSEPPE 2024-08-31 ******************* -->
<style>
    /* https://www.w3schools.com/tags/tryit.asp?filename=tryhtml_button3 */
    #buttonRinnova {
        border: none;
        color: white;
        padding: 16px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        transition-duration: 0.4s;
        cursor: pointer;
        background-color: greenyellow;
        color: black;
        border: 2px solid #04AA6D;
        border-radius: 5px;
    }

    #buttonRinnova:hover {
        background-color: #04AA6D;
        color: white;
    }
</style>
<!-- ************************************ -->
<?= $this->element("/backend/edit_scripts"); ?>

<?= $this->Form->create('Squadre', array('action' => 'edit', 'prefix' => 'admin', 'class' => 'formAdd', 'type' => 'file')); ?>

<div class="form_header">

    <!--<h2>Modifica squadra: <span><?= $this->data['Squadre']['Denominazione']; ?></span></h2>-->
    <h2>Modifica squadra: <span><?= $this->data['Squadre']['Denominazione']; ?></span>
        <div id="buttonRenewal">

        </div>
        <div id="messageRenewal">

        </div>
    </h2> <!--GIUSEPPE 2023-08-22 *******************-->
    <ul>
        <li><?= $this->Form->submit('reset campi', array('type' => 'reset', 'div' => false)); ?></li>
        <li><?= $this->Form->submit('annulla', array('type' => 'button', 'div' => false, 'id' => 'formReset')); ?></li>
        <li><?= $this->Form->submit('modifica', array('type' => 'submit', 'div' => false)); ?></li>
    </ul>
    <div class="clear"></div>

</div><!-- close form_header -->

<input type="hidden" name="modded" value="false" />

</div><!-- close form_header -->


<?php
//GIUSEPPE 03/10/2016 -------------------

$arrayRadio = array();

$res = mysql_query("SELECT * FROM TipoSport WHERE 1");

while ($row = mysql_fetch_assoc($res)) {
    $arrayRadio[] = $row['sport'];
}

//$key = array_search($this->data['Squadre']['sport'], $arrayRadio); // cerca l'indice dello sport associato alla categoria nell'array degli sport

$key = $this->data['Squadre']['id_sport']; // cerca l'indice dello sport associato alla categoria nell'array degli sport
//print_r($this->data);
?>



<?= $this->Form->radio('sport', $arrayRadio, array('value' => $key)); //GIUSEPPE  lo 0 sta per "seleziona il primo indice dell'array (quindi "CALCIO")" ?>

<? // print_r($this->Form)// -------------------------------------- ?>

<?= $this->Form->input('Denominazione', array('label' => 'Denominazione', 'class' => 'big')); ?>




<div class="clear"></div>
<!--GIUSEPPE 2023-08-22 *******************-->
<?= $this->Form->input('email', array('label' => 'Email', 'class' => 'big')); ?>
<?= $this->Form->input('phone', array('label' => 'Telefono', 'class' => 'big')); ?>
<?= $this->Form->input('legal_address', array('label' => 'indirizzo sede legale', 'class' => 'big')); ?>


<div class="autocomplete">
    <?= $this->Form->input('legal_city', array('label' => 'città', 'class' => 'big')); ?>
</div>


<? //= $this->Form->input('constitution_date', array('label' => 'data costituzione', 'class' => 'big')); ?>
<div class="input date"><label for="SquadreConstitutionDateMonth">data costituzione</label>
    <input name="data[Squadre][constitution_date]" id="SquadreConstitutionDate" type="date"
        value="<?= $this->data['Squadre']['constitution_date'] ?>">
</div>

<div class="clear"></div>

<h3>RESPONSABILE</h3>

<div class="clear"></div>

<?=
    $this->Form->input(
        'general_counsel_firstname',
        array(
            'label' => 'Cognome responsabile',
            'class' => 'big cf-generate',
            'required' => true,
            'to_send' => 'general_counsel_firstname'
        )
    );
?>

<?=
    $this->Form->input(
        'general_counsel_lastname',
        array(
            'label' => 'Nome responsabile',
            'class' => 'big cf-generate',
            'required' => true,
            'to_send' => 'general_counsel_lastname'
        )
    );
?>



<div class="input date"><label for="SquadreGeneralCounselBirthday">Data nascita responsabile</label>
    <input name="data[Squadre][general_counsel_birthday]" id="SquadreGeneralCounselBirthday" class="cf-generate"
        required="1" type="date" value="<?= $this->data['Squadre']['general_counsel_birthday'] ?>"
        to_send='general_counsel_birthday'>
</div>


<?=
    $this->Form->input('general_counsel_gender', array(
        'legend' => 'Sesso',
        'type' => 'radio',
        'options' => array('M' => 'M', 'F' => 'F'),
        'required' => true,
        'class' => 'cf-generate',
        'to_send' => 'general_counsel_gender',
    ));
?>
<div class="clear"></div>
<div class="autocomplete">
    <?=
        $this->Form->input(
            'general_counsel_birthplace',
            array(
                'label' => 'Citta di nascita responsabile',
                'class' => 'big cf-generate',
                'required' => true,
                'to_send' => 'general_counsel_birthplace',
                'maxlength' => 255
            )
        );
    ?>
</div>
<div class="clear"></div>
<?=
    $this->Form->input('general_counsel_cf', array(
        'label' => 'Codice fiscale',
        'class' => 'big',
        'required' => true,
        //    'class' => 'cf-generate',
//    'to_send' => 'general_counsel_cf',
    ));
?>


<a id="MEMORANDUM_ARTICLES_ASSOCIATION_DATE"></a><strong>Statuto:</strong>
<a id="MEMORANDUM_ARTICLES_ASSOCIATION">
</a><br>


<a id="AFFILIATION_REQUEST_DATE"></a><strong>Richiesta affiliazione:</strong>
<a id="AFFILIATION_REQUEST">
</a><br>


<a id="PRESIDENT_ID_DATE"></a><strong>Documento di identità responsabile:</strong>
<a id="PRESIDENT_ID">
</a><br>

<div class="clear"></div>


<div class="clear"></div>

<div style="display: none">
    <!--<div>-->
    <input id="legal_city" name="data[Squadre][legal_city]" value="<?= $this->data['Squadre']['legal_city'] ?>"
        to_send="legal_city">

    <input id="general_counsel_birthplace" name="data[Squadre][general_counsel_birthplace]"
        value="<?= $this->data['Squadre']['general_counsel_birthplace'] ?>" class="cf-generate"
        to_send="general_counsel_birthplace">

    <input id="general_counsel_birthplace_city_code" name="data[Squadre][general_counsel_birthplace_city_code]"
        value="<?= $this->data['Squadre']['general_counsel_birthplace_city_code'] ?>" class="cf-generate"
        to_send="general_counsel_birthplace_city_code">
</div>

<hr>
<div class="clear"></div>

<label>Statuto (file .pdf):</label>
<input name="data[Squadre][MEMORANDUM_ARTICLES_ASSOCIATION]" type="file" accept="application/pdf">
<hr>

<label>Richiesta affiliazione (file .pdf):</label>
<input name="data[Squadre][AFFILIATION_REQUEST]" type="file" accept="application/pdf">
<hr>

<label>Documento di identità responsabile (file .pdf):</label>
<input name="data[Squadre][PRESIDENT_ID]" type="file" accept="application/pdf">
<hr>

<div class="clear"></div>

<?=
    $this->Form->input('SquadraServizio', array(
        'legend' => 'Squadra di servizio',
        'type' => 'radio',
        'options' => array(1 => 'Si', 0 => 'No'),
        'class' => 'squadra-servizio',
    ));
?>

<!--***************************************-->

<?
//
//$this->Form->input('SquadraServizio', array(
//    'legend' => 'Squadra di servizio',
//    'type' => 'radio',
//    'options' => array(1 => 'Si', 0 => 'No'),
//));
//
?>

<div class="clear"></div>

<div style="width: 800px; height: 240px; margin-bottom: 180px;">

    <?= $this->element('/backend/ckeditor', array('name' => 'Storia', 'title' => 'Storia')); ?>

</div>

<div class="clear"></div>

<?=
    $backend->getFiles('squadra_id', $this->data['Squadre']['Squadra'], array(
        'tag' => array(
            '' => 'Galleria',
            'Coccarda' => 'Coccarda',
            'Squadra' => 'Immagine squadra',
            'Logo' => 'Logo squadra',
            'Sponsor' => 'Sponsor squadra',
            'SponsorEsterno' => 'Sponsor esterno',
            'Trofeo' => 'Simbolo trofeo',
        ),
    ));
?>

<?= $this->Form->end(); ?>


<!--GIUSEPPE 2023-08-22 *******************-->
<script>

    cgg = new Operazioni(<?= json_encode($this->data['Squadre']) ?>); //GIUSEPPE 2024-08-31 *******************


    var cke_skin_kama = document.getElementsByClassName('cke_editor_data[Squadre][Storia]');
    console.log(cke_skin_kama.length);
    if (cke_skin_kama.length == 1) {

        cke_skin_kama[0].style.display = 'none';
    }



    var SquadreLegalCity = document.getElementById("SquadreLegalCity");
    var SquadreGeneralCounselBirthplace = document.getElementById("SquadreGeneralCounselBirthplace");
    var SquadraServizio = document.getElementsByClassName("squadra-servizio");

    SquadreLegalCity.addEventListener('keyup', richiamaCity);

    SquadreGeneralCounselBirthplace.addEventListener('keyup', richiamaCity);

    Object.keys(SquadraServizio).forEach((i) => {
        SquadraServizio[i].addEventListener('change', (e) => {
            console.log(e);

            if (e.srcElement.checked == true) {
                if (parseInt(e.srcElement.value) == 1) {
                    Object.keys(cf_generate).forEach((i) => {
                        cf_generate[i].disabled = true;
                        document.getElementById("SquadreGeneralCounselCf").disabled = true;
                    });


                }
                if (parseInt(e.srcElement.value) == 0) {
                    Object.keys(cf_generate).forEach((i) => {
                        cf_generate[i].disabled = false;
                        document.getElementById("SquadreGeneralCounselCf").disabled = false;
                    });
                }
            }
        });
    });

    Object.keys(SquadraServizio).forEach((i) => {

        if (SquadraServizio[i].checked == true) {
            if (parseInt(SquadraServizio[i].value) == 1) {
                try {
                    Object.keys(cf_generate).forEach((i) => {
                        cf_generate[i].disabled = true;
                        document.getElementById("SquadreGeneralCounselCf").disabled = true;
                    });
                } catch (exception) {
                    console.log(exception);
                }
            }
            if (parseInt(SquadraServizio[i].value) == 0) {
                try {
                    Object.keys(cf_generate).forEach((i) => {
                        cf_generate[i].disabled = false;
                        document.getElementById("SquadreGeneralCounselCf").disabled = false;
                    });
                } catch (exception) {
                    console.log(exception);
                }



            }
        }

    });


    richiamaIdCity();

    async function richiamaIdCity() {
        const l_c = await listIdCity(document.getElementById("legal_city").value);
        const l_n = await listIdCity(document.getElementById("general_counsel_birthplace").value);
        await associaCity(l_c, l_n);
    }

    function listIdCity(id) {
        return new Promise((resolve, reject) => {

            var link = "/apis/cities";
            var to_send = { id };
            const xhr = new XMLHttpRequest();
            xhr.open("POST", link);
            xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
            const body = JSON.stringify(to_send);
            xhr.send(body);
            xhr.onload = () => {

                if (xhr.readyState == 4 && xhr.status == 200) {
                    var arr = JSON.parse(xhr.response);

                    resolve(arr);
                } else {
                    reject(new Error(xhr.statusText));
                }
            };
        });
    }

    function associaCity(l_c, l_n) {
        document.getElementById("SquadreLegalCity").value = "";
        if (parseInt(document.getElementById("legal_city").value) != 0) {
            document.getElementById("SquadreLegalCity").value = l_c[document.getElementById("legal_city").value]['city_name'];
        }

        document.getElementById("SquadreGeneralCounselBirthplace").value = "";
        if (parseInt(document.getElementById("general_counsel_birthplace").value) != 0) {
            document.getElementById("SquadreGeneralCounselBirthplace").value = l_n[document.getElementById("general_counsel_birthplace").value]['city_name'];
        }

    }
    async function richiamaCity(e) {
        console.log(e);
        const countries = await listCity(e);
        await analizza(countries, e);
    }

    function listCity(e) {
        return new Promise((resolve, reject) => {

            var link = "/apis/cities";
            var to_send = { "city_name": e.srcElement.value };
            const xhr = new XMLHttpRequest();
            xhr.open("POST", link);
            xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
            const body = JSON.stringify(to_send);
            xhr.send(body);
            xhr.onload = () => {

                if (xhr.readyState == 4 && xhr.status == 200) {
                    var arr = JSON.parse(xhr.response);

                    resolve(arr);
                } else {
                    reject(new Error(xhr.statusText));
                }
            };
        });
    }

    function analizza(arr, e) {
        var a, b, i, val = e.srcElement.value;
        var id_input = e.srcElement.id;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) {
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
        //        for (i = 0; i < Object.keys(arr).length; i++)
        Object.keys(arr).map((i) => {
            /*check if the item starts with the same letters as the text field value:*/
            if (arr[i].city_name.substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                /*create a DIV element for each matching element:*/
                b = document.createElement("DIV");
                /*make the matching letters bold:*/
                b.innerHTML = "<strong>" + arr[i].city_name.substr(0, val.length) + "</strong>";
                b.innerHTML += arr[i].city_name.substr(val.length);
                /*insert a input field that will hold the current array item's value:*/
                b.innerHTML += "<input type='hidden' id='" + i + "' value=\"" + arr[i].city_name + "\">";
                /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function (e) // quando clicco sull'elenco città
                {
                    /*insert the value for the autocomplete text field:*/
                    var nome = this.getElementsByTagName("input")[0].value;
                    var nom = this.getElementsByTagName("input");
                    var index = this.getElementsByTagName("input")[0].id;
                    console.log(id_input + " of select");

                    // -------
                    switch (id_input) {
                        case "SquadreLegalCity":
                            document.getElementById("legal_city").value = index;
                            document.getElementById("SquadreLegalCity").value = this.getElementsByTagName("input")[0].value;
                            break;

                        case "SquadreGeneralCounselBirthplace":
                            document.getElementById("general_counsel_birthplace").value = index;
                            document.getElementById("general_counsel_birthplace_city_code").value = arr[index]['city_code'];
                            document.getElementById("SquadreGeneralCounselBirthplace").value = this.getElementsByTagName("input")[0].value;

                            setTimeout(() => {
                                //                                readAllClassCF()
                                cfCreate();
                            }, 100)
                            break;
                    }

                    console.log(arr[index]);

                    /*close the list of autocompleted values,
                     (or any other open lists of autocompleted values:*/
                    closeAllLists();
                });
                a.appendChild(b);
            }
        })

    }


    function closeAllLists(elmnt) {
        /*close all autocomplete lists in the document,
         except the one passed as an argument:*/
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != document.getElementById("SquadreLegalCity")) {
                x[i].parentNode.removeChild(x[i]);
            }

            if (elmnt != x[i] && elmnt != document.getElementById("SquadreGeneralCounselBirthplace")) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }

    function addActive(x) {
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
    function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }


    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });

</script>

<style>
    /*    * {
            box-sizing: border-box;
        }*/

    /*    body {
            font: 16px Arial;
        }*/

    /*the container must be positioned relative:*/
    .autocomplete {
        position: relative;
        display: inline-block;
    }

    .autocomplete-items {
        position: absolute;
        border: 1px solid #d4d4d4;
        border-bottom: none;
        border-top: none;
        z-index: 99;

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

<script>
    var cf_generate = document.getElementsByClassName('cf-generate');
    var to_send_for_cf = {};

    Object.keys(cf_generate).forEach((i) => {
        cf_generate[i].addEventListener('change', cfCreate);
        cf_generate[i].addEventListener('keyup', cfCreate);
        //cf_generate[i].addEventListener('oninput', cfCreate);
    });

    async function cfCreate(e) {
        var arr = await readAllClassCF();
        var cf = await httpPost('/apis/generateCF', arr);
        document.getElementById("SquadreGeneralCounselCf").value = cf.complete;
        console.log(cf);
    }


    function readAllClassCF() {

        return new Promise((resolve, reject) => {

            //            console.log(cf_generate);
            Object.keys(cf_generate).forEach((i) => {
                var name = cf_generate[i].id;
                var attribute = cf_generate[i]['attributes']['to_send']['value'];
                var value = cf_generate[i].value;
                if (name == "SquadreGeneralCounselGenderM" || name == "SquadreGeneralCounselGenderF") {
                    console.log("qui");
                    if (cf_generate[i].checked == true) {
                        //                    console.log(name + " " + cf_generate[i].value);
                    } else {
                        return 0;
                    }
                }

                to_send_for_cf[attribute] = value;

            });

            resolve(to_send_for_cf);

        });

    }


    // ---------------------------------------------------------------------------

    function httpPost(link, to_send) {
        return new Promise((resolve, reject) => {

            //            var link = "/apis/cities";
            //            var to_send = {id};
            const xhr = new XMLHttpRequest();
            xhr.open("POST", link);
            xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
            const body = JSON.stringify(to_send);
            xhr.send(body);
            xhr.onload = () => {

                if (xhr.readyState == 4 && xhr.status == 200) {
                    var arr = JSON.parse(xhr.response);

                    resolve(arr);
                } else {
                    reject(new Error(xhr.statusText));
                }
            };
        });
    }

</script>



<!--***************************************-->