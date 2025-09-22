<script type="text/javascript">
    if (typeof $ != "undefined")
    {

        $(".ResponsabileClass").live('change', function ()
        {

            if ($("#AthleteResponsabileNo").is(':checked'))
            {
                $('input[name="data[Athlete][Responsabile]"]').val('No');
            }
            else
            {
                $('input[name="data[Athlete][Responsabile]"]').val('Si');
            }

        });
        $(".ArbitroClass").live('change', function ()
        {

            if ($("#AthleteArbitroNo").is(':checked'))
            {
                $('input[name="data[Athlete][Arbitro]"]').val('No');
                $("#AthleteEmail").parent('div').removeClass('required');
            }
            else
            {
                $('input[name="data[Athlete][Arbitro]"]').val('Si');
                $("#AthleteEmail").parent('div').addClass('required');
            }

        });
        /*
         $(".SessoClass").live('change', function(){
         
         if($("#AthleteSessoNo").is(':checked')) {
         $('input[name="data[Athlete][Sesso]"]').val('Maschio');
         } else {
         $('input[name="data[Athlete][Sesso]"]').val('Femmina');
         }
         
         });	
         */
        $(".SportivoClass").live('change', function ()
        {

            if ($("#AthleteSportivoNo").is(':checked'))
            {
                $('input[name="data[Athlete][Sportivo]"]').val('No');
            }
            else
            {
                $('input[name="data[Athlete][Sportivo]"]').val('Si');
            }

        });

        $("#AthleteAdminSearchAthleteIndexForm").delegate("#createAthlete", "click", function ()
        {

            var i = 0;

            $('#AthleteAdminSearchAthleteIndexForm').find('.error-message').remove();

            $('#AthleteAdminSearchAthleteIndexForm .required').each(function (index)
            {

                if ($(this).children('input').val() == '')
                {

                    var obj = $(this);

                    i++;

                    obj.append('<div class="error-message">Campo obbligatorio</div>');

                }

            });

            $('#AthleteAdminSearchAthleteIndexForm .required').each(function (index)
            {

                if ($(this).find('input[type="radio"]').length > 0)
                {

                    if ($(this).find('input').is(':checked') == false)
                    {

                        var obj = $(this);

                        i++;

                        obj.append('<div class="error-message">Campo obbligatorio</div>');

                    }

                }

            });

            if (i == 0)
            {

                var data = $("#AthleteAdminSearchAthleteIndexForm").serialize();

                $.post('/admin/athletes/newAthlete', data, function (ret)
                {

                    if (ret.add != 'error')
                    {

                        $(".formSearch input[type='button']").trigger('click', [ret.add]);

                    }
                    else
                    {

                        var element = $('#AthleteCognome');
                        var error = $('<div>').addClass('error-message').text('Atleta già esistente');

                        error.insertAfter(element);

                    }

                }, 'json');

            }

        });
    }
</script>
<div class="Athlete_search">

    <?= $this->Form->create('Athlete', array('prefix' => 'admin', 'class' => 'formSearch')); ?>

    <div class="form_header">

        <h2>Ricerca atleti</h2>
        <ul>

            <li><?= $this->Form->submit('reset campi', array('type' => 'reset', 'div' => false, 'id' => 'formResetFields')); ?></li>
            <li><?= $this->Form->submit('cerca', array('type' => 'button', 'id' => 'searchButton', 'div' => false)); ?></li>
            <li><?= $this->Form->button('cancella selezione', array('type' => 'button', 'div' => false, 'id' => 'deleteSelection')); ?></li>
            <li><?= $this->Form->button('inserisci', array('type' => 'button', 'div' => false, 'id' => 'createAthlete')); ?></li>
        </ul>
        <div class="clear"></div>

    </div><!-- close form_header -->

    <?= $this->Form->input('Atleta'); ?>

    <?= $this->Form->input('Cognome'); ?>
    <?= $this->Form->input('Nome'); ?>
    <?= $this->Form->input('DataNascita', array('label' => 'Data di nascita', 'type' => 'text', 'class' => 'datePicker dateSmall')); ?>

    <div class="clear"></div>

    <? //=$this->Form->input('LuogoNascita',array('label' => 'Luogo di nascita'));?>


    <!--//GIUSEPPE 2023-07-28-->
    <div class="autocomplete">
        <?= $this->Form->input('LuogoNascita', array('label' => 'Luogo di nascita')); ?>
    </div>
    <!-- ********************* -->
    <?=
    $this->Form->input('Sesso',
            array(
                'type' => 'radio',
                //'default' => 'M',
                'class' => 'SessoClass',
                'options' => array('Maschio' => 'M', 'Femmina' => 'F'),
    ));
    ?>

    <?=
    $this->Form->input('Responsabile',
            array(
                'type' => 'radio',
                //'default' => 'No',
                'class' => 'ResponsabileClass',
                'options' => array('Si' => 'Si', 'No' => 'No'),
    ));
    ?>

    <?=
    $this->Form->input('Sportivo',
            array(
                'type' => 'radio',
                //'default' => 'Si',
                'class' => 'SportivoClass',
                'options' => array('Si' => 'Si', 'No' => 'No'),
    ));
    ?>	

    <?=
    $this->Form->input('Arbitro',
            array(
                'type' => 'radio',
                //'default' => 'No',
                'class' => 'ArbitroClass',
                'options' => array('Si' => 'Si', 'No' => 'No'),
    ));
    ?>

    <div class="clear"></div>

    <?= $this->Form->input('Indirizzo'); ?>
    <?= $this->Form->input('Cap', array('class' => 'small')); ?>
    <? //= $this->Form->input('Localita'); ?>
    <!--//GIUSEPPE 2023-07-28-->
    <div class="autocomplete">
        <?= $this->Form->input('Localita'); ?>
    </div>
    <!-- ********************* -->

    <?= $this->Form->input('Provincia', array('class' => 'small')); ?>	

    <div class="clear"></div>

    <?=
    $this->Form->input('TipoDocumento', array(
        'label' => 'Tipo documento',
        'options' => array(
            'Carta Identità' => 'Carta Identità',
            'Patente' => 'Patente',
            'Passaporto' => 'Passaporto'
        ),
        'empty' => true
    ));
    ?>

    <?= $this->Form->input('NumeroDocumento', array('label' => 'Num. documento')); ?>

    <?= $this->Form->input('ScadenzaDocumento', array('label' => 'Scadenza documento', 'type' => 'text', 'class' => 'datePicker dateSmall')); ?>

    <div class="clear"></div>

    <?= $this->Form->input('CodiceFiscale', array('label' => 'Codice Fiscale', 'type' => 'text')); ?>

    <div class="input">
        <?= $this->Form->input('Telefono', array('div' => false)); ?>
    </div>

    <div class="input">
        <?= $this->Form->input('Cellulare', array('div' => false)); ?>
    </div>

    <div class="input">
        <?= $this->Form->input('Lavoro', array('label' => 'Telefono lavoro', 'div' => false)); ?>
    </div>

    <div class="clear"></div>


    <div class="input">
        <?= $this->Form->input('Fax', array('div' => false)); ?>
    </div>

    <div class="input">
        <?= $this->Form->input('Email', array('div' => false, 'class' => 'big')); ?>
    </div>


    <!--GIUSEPPE 2023-07-28 *******************-->
    <div class="clear"></div>

    <div class="input text required">
        <label for="AthleteCityNascita">id Luogo di nascita (automatico)</label>
        <input name="data[Athlete][CityNascita]" type="number" maxlength="7" value="<?= $this->data['Athlete']['CityNascita'] ?>" id="AthleteCityNascita" required data-readonly>
    </div>

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

    <!--input read only required ID CITTA -->
    <style>
        input[data-readonly] {
            pointer-events: none;
        }

    </style>

    <script>
        var AthleteLuogoNascita = document.getElementById("AthleteLuogoNascita");
        AthleteLuogoNascita.addEventListener('keyup', richiamaCity);


        var AthleteLocalita = document.getElementById("AthleteLocalita");
        AthleteLocalita.addEventListener('keyup', richiamaCity);

        async function richiamaCity(e)
        {
            //                                    document.getElementById("AthleteCityNascita").value = "";
            const countries = await httpPost("/apis/cities", {"city_name": e.srcElement.value});
            await analizza(countries, e);
        }


        // -------------------------------------------------------------------------------
        function analizza(arr, e)
        {
            var a, b, i, val = e.srcElement.value;
            var id_input = e.srcElement.id;
            /*close any already open lists of autocompleted values*/
            closeAllLists();
            if (!val)
            {
                return false;
            }

            switch (id_input)
            {
                case "AthleteLuogoNascita":
                    document.getElementById("AthleteCityNascita").value = "";
                    break;
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
                if (arr[i].city_name.substr(0, val.length).toUpperCase() == val.toUpperCase())
                {
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

                        switch (id_input)
                        {
                            case "AthleteLuogoNascita":
                                document.getElementById("AthleteCityNascita").value = index;
                                break;
                        }

                        document.getElementById(id_input).value = this.getElementsByTagName("input")[0].value;


                        console.log(arr[index]);

                        /*close the list of autocompleted values,
                         (or any other open lists of autocompleted values:*/
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            })

        }




        function closeAllLists(elmnt)
        {
            /*close all autocomplete lists in the document,
             except the one passed as an argument:*/
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++)
            {
                if (elmnt != x[i] && elmnt != document.getElementById("AthleteLuogoNascita"))
                {
                    x[i].parentNode.removeChild(x[i]);
                }

                //                                        if (elmnt != x[i] && elmnt != document.getElementById("SquadreGeneralCounselBirthplace"))
                //                                        {
                //                                            x[i].parentNode.removeChild(x[i]);
                //                                        }
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


        // -------------------------------------------------------------------------
        function httpPost(link, to_send)
        {
            return new Promise((resolve, reject) => {

                //            var link = "/apis/cities";
                //            var to_send = {id};
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

    <?= $this->Form->end(); ?>

</div>

<div class="div_append"></div>