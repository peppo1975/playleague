<?= $this->Form->create('Athlete', array('action' => 'add', 'prefix' => 'admin', 'class' => 'formAdd', 'type' => 'file')); ?>

<div class="form_header">

    <h2>Anagrafica inserimento nuovo atleta</h2>
    <ul>

        <li><?= $this->Form->submit('reset campi', array('type' => 'reset', 'div' => false)); ?></li>
        <li><?= $this->Form->submit('annulla', array('type' => 'button', 'div' => false, 'id' => 'formReset')); ?></li>
        <li><?= $this->Form->submit('crea', array('type' => 'submit', 'div' => false)); ?></li>
    </ul>
    <div class="clear"></div>

</div><!-- close form_header -->


<?= $this->Form->input('Cognome'); ?>
<?= $this->Form->input('Nome'); ?>

<div class="clear"></div>

<? //= $this->Form->input('Indirizzo'); ?>
<? //= $this->Form->input('Cap'); ?>
<? //= $this->Form->input('Localita'); ?>
<? //= $this->Form->input('Provincia'); ?>

<div class="clear"></div>

<? //= $this->Form->input('Telefono'); ?>
<? //= $this->Form->input('Cellulare'); ?>
<? //= $this->Form->input('Lavoro', array('label' => 'Telefono lavoro')); ?>
<?= $this->Form->input('Email', array('class' => 'big')); ?>
<? //= $this->Form->input('Fax'); ?>   
<? //= $this->Form->input('CodiceFiscale'); ?> 

<div class="clear"></div>

<? //= $this->Form->input('LuogoNascita', array('label' => 'Luogo di nascita')); ?>
<? //= $this->Form->input('DataNascita', array('label' => 'Data di nascita', 'type' => 'text', 'class' => 'datePicker')); ?>  

<div class="input date required"><label for="AthleteDataNascita">Data di nascita</label>
    <input 
        name="data[Athlete][DataNascita]" 
        id="AthleteDataNascita"  
        required="1" 
        type="date" 
        value = "<?= $this->data['Athlete']['DataNascita'] ?>"
        >
</div>

<!--$this->data['Athlete']['DataNascita']-->
<? //print_r($this->data)?>

<?=
$this->Form->input('Sesso',
        array(
            'type' => 'radio',
            'options' => array('Maschio' => 'M', 'Femmina' => 'F'),
        ));
?>

<div class="clear"></div>

<?
//=
//$this->Form->input('TipoDocumento', array(
//    'label' => 'Tipo documento',
//    'options' => array(
//        'Carta Identità' => 'Carta Identità',
//        'Patente' => 'Patente',
//        'Passaporto' => 'Passaporto'
//    )
//));
?>

<? //= $this->Form->input('NumeroDocumento', array('label' => 'Num. documento'));  ?>

<? //= $this->Form->input('ScadenzaDocumento', array('label' => 'Scadenza documento', 'type' => 'text', 'class' => 'datePicker'));  ?>


<div class="clear"></div>

<?
//=
//$this->Form->input('Responsabile',
//        array(
//            'type' => 'radio',
//            'options' => array('Si' => 'Si', 'No' => 'No'),
//));
?>      

<?
//=
//$this->Form->input('Arbitro',
//        array(
//            'type' => 'radio',
//            'default' => 'No',
//            'options' => array('Si' => 'Si', 'No' => 'No'),
//));
?>      

<?
//=
//$this->Form->input('ArbitroAttivo',
//        array(
//            'legend' => 'Arbitro attivo',
//            'type' => 'radio',
//            'options' => array(1 => 'Si', 0 => 'No'),
//));
?>      

<?=
$this->Form->input('Sportivo',
        array(
            'type' => 'radio',
            'default' => 'Si',
            'options' => array('Si' => 'Si', 'No' => 'No'),
        ));
?>      
<div class="clear"></div>
<?
//=
//$this->Form->input('Delegato',
//        array(
//            'type' => 'radio',
//            'default' => 'No',
//            'options' => array('Si' => 'Si', 'No' => 'No'),
//));
?>      

<?
//=
//$this->Form->input('Allenatore',
//        array(
//            'type' => 'radio',
//            'default' => 'No',
//            'options' => array('Si' => 'Si', 'No' => 'No'),
//));
?>      
<? if ($group_id == 5): ?>

    <div class="clear"></div>

    <!--    <div style="width: 600px;">

    <?= $this->element('/backend/ckeditor', array('name' => 'Note', 'title' => 'Note')); ?>


       </div>
       <div class="clear"></div>-->

<? endif; ?>        

<? if ($layout != "tablet"): ?>

    <!--    <div id="formUploadContainer">

    <?= $backend->getFiles('athlete_id', 0); ?>

        </div>-->

<? endif; ?>



<!--//GIUSEPPE 2020-09-01 *****************************-->

<form method="post" action="" enctype="multipart/form-data"id="myform"> 
    <? $message_link = " scarica modello excel " ?>
    <hr>

    <h2>Inserici dati da excel</h2> 

    <div class="clear"></div>

    <p>Clicca su <b><?= $message_link ?></b> e compila i campi. Terminata la compilazione esegui l'upload del file</p>

    <div class="clear"></div>
    <br>
    <!--    <div>
            Girone <input id="nomeGirone" />
        </div>-->
    <br>
    <!--<div style="display: none" id="formSendXlsx">--> 
    <div  id="formSendXlsx"> 
        <a href="/download/Atleti.xlsx" target="_blank"><?= $message_link ?></a>&emsp;
        <input type="file" id="file" name="file" /> 
        <input type="button" class="button" value="Upload" id="but_upload"> 

    </div> 


    <div class="clear"></div>
    <div id="errorResponse">

    </div>
    <div id="errorInsert">

    </div>
    <div id="errorAssociazione">

    </div>
    <div id="response">

    </div>
</form> 

<!--//GIUSEPPE 2023-07-28 ---------------------------------------------- -->
<div id="test">
</div>
<!-- ------------------------------------------------------------------- --> 

<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#but_upload").click(function ()
        {
            document.getElementById('response').innerHTML = "";
            var fd = new FormData();
            var files = $('#file')[0].files[0];
            fd.append('file', files);

//            fd.append('girone', nomeGirone.value);

            $.ajax({
//                url: 'read_atleti_xlsx', //GIUSEPPE 2024-05-26
                url: 'load_atleti_campionati_xlsx',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function (response)
                {
                    res = JSON.parse(response);
                    console.log(res);


                    if (res['upload'] === "OK")
                    {
//                        console.log(response);
//                      $("#response").html(res['table']); vecchia versione con admin_add_table.ctp
                        addTable(res);
                    } else if (res['upload'] === "KO")
                    {
                        alert(res['message']);
                    } else
                    {
                        alert('PROBLEMA DI SISTEMA');
                    }
                },
            });
        });
    });
</script> 

<!--//GIUSEPPE 2024-05-26-->
<script>

    var formSendXlsx = document.getElementById('formSendXlsx');

//    var nomeGirone = document.getElementById('nomeGirone');
//    nomeGirone.addEventListener('keyup', analizzaGirone);
//    function analizzaGirone(e)
//    {
//        console.log(e);
//        if (e.srcElement.value == "")
//        {
//            formSendXlsx.style.display = 'none';
//        }
//        else
//        {
//            formSendXlsx.style.display = null;
//        }
//    }


    function addTable(res)
    {
        console.log("table");
        var atleti = res.array;


        var errorResponse = document.getElementById("errorResponse");
        var errorInsert = document.getElementById("errorInsert");
        var errorAssociazione = document.getElementById("errorAssociazione");

        errorResponse.innerHTML = "";
        errorResponse.style.backgroundColor = null;

        errorInsert.innerHTML = "";
        errorInsert.style.backgroundColor = null;

        errorAssociazione.innerHTML = "";
        errorAssociazione.style.backgroundColor = null;

        var errorRows = [];
        var errorRowsInsert = [];
        var errorRowsAssociazione = [];

        var body = document.getElementById("response");
        body.innerHTML = "";

        var table = document.createElement('table');
        var thead = document.createElement('thead');
        var tbody = document.createElement('tbody');

        var columns = ["Riga", "Cognome", "Nome", "DataNascita", "LuogoNascita", "NomeSquadra", "RESPONSE"];

        var row1 = document.createElement('tr');
        Object.keys(columns).forEach((i) => {
            var th = document.createElement('th');
            th.innerText = columns[i];
            row1.appendChild(th);
        });


        Object.keys(atleti).forEach((a) => {
            var row = document.createElement('tr');
            var atleta = atleti[a];

            Object.keys(columns).forEach((i) => {

                var td = document.createElement('td');

                if (columns[i] == "RESPONSE")
                {

                    if (parseInt(atleta['LuogoNascitaId']) == -1 || atleta['errData'] == true)
                    {
                        td.style.backgroundColor = 'red';
                        td.style.color = 'white';
                        errorRows.push(a);
                    } else
                    {
                        td.style.backgroundColor = 'green';
                    }


                    if (atleta.Annuario == 0)
                    {
                        if (!errorRows.includes(a))
                        {
                            row.style.backgroundColor = 'red';
                            td.style.backgroundColor = 'red';
                            errorRowsInsert.push(a);
                            row.style.color = 'white';
                        }

                    }

                    if (atleta.Annuario == -1)
                    {
                        row.style.backgroundColor = 'orange';
                        td.style.backgroundColor = 'red';
                        errorRowsAssociazione.push(a);
                    }

                } else if (columns[i] == "Riga")
                {

                    td.innerText = a;
                } else
                {
                    td.innerText = atleta[columns[i]];

                    switch (columns[i])
                    {
                        case "LuogoNascita":

                            if (parseInt(atleta['LuogoNascitaId']) == -1)
                            {
                                td.style.backgroundColor = 'red';
                                td.style.color = 'white';
                            }
                            break;

                        case "DataNascita":

                            if (atleta['errData'] == true)
                            {
                                td.style.backgroundColor = 'red';
                                td.style.color = 'white';
                            }
                            break;

                    }

                }

                row.appendChild(td);



            });

            tbody.appendChild(row);

        });

        thead.appendChild(row1);

        table.appendChild(thead);
        table.appendChild(tbody);
        body.appendChild(table);

        if (errorRows.length > 0)
        {
//            alert("Attenzione, ci sono alcuni dati non validi");
            var righe = errorRows.join(',');
            errorResponse.innerHTML = "Attenzione, ci sono alcuni dati non validi alle righe " + righe;
            errorResponse.style.backgroundColor = 'orange';

        }
        if (errorRowsInsert.length > 0)
        {
//            alert("Attenzione, ci sono alcuni dati non validi");
            var righeNotInsert = errorRowsInsert.join(',');
            errorInsert.innerHTML = "Attenzione, a livello DB, c'è un errore nell'inserimento delle righe " + righeNotInsert;
            errorInsert.style.backgroundColor = 'red';
            errorInsert.style.color = 'white';

        }
        if (errorRowsAssociazione.length > 0)
        {
//            alert("Attenzione, ci sono alcuni dati non validi");
            var righeNotAssociate = errorRowsAssociazione.join(',');
            errorAssociazione.innerHTML = "Attenzione c'è una errata associazione campionato - squadra alle righe " + righeNotAssociate;
            errorAssociazione.style.backgroundColor = 'orange';
            //errorInsert.style.color = 'white';

        }

    }


</script>
<!--***************************************************-->
<!--//GIUSEPPE 2023-07-28 ---------------------------------------------- -->  
<? if (isset($element_id)): ?>
    <script>



        //        if (document.getElementById('tabella') == null)
        //        {
        //            var element_id = '<?= $element_id ?>';
        //            var body = document.getElementById("test");
        //
        //            var tbl = document.createElement("table");
        //
        //            tbl.setAttribute("id", "index_table");
        //            tbl.setAttribute("id", "tabella");
        //
        //            var tblBody = document.createElement("tbody");
        //            tblBody.classList.add("content");
        //
        //
        //            var row = document.createElement("tr");
        //            row.setAttribute("id", element_id);
        //            row.classList.add("index-row");
        //            row.classList.add("switch");
        //            row.setAttribute("data-dest", "view_mode");
        //            row.setAttribute("data-ajax", "/admin/squadres/edit/" + element_id);
        //
        //
        //            var cell = document.createElement("td");
        //            cell.classList.add("tools");
        //
        //            var ul = document.createElement("ul");
        //            var li = document.createElement("li");
        //            var a = document.createElement("a");
        //            var img = document.createElement("img");
        //
        //            a.setAttribute("href", "javascript:;");
        //            a.classList.add("index-row-edit");
        //            a.classList.add("switch");
        //            a.setAttribute("data-id", element_id);
        //            a.setAttribute("data-dest", "view_mode");
        //            a.setAttribute("data-ajax", "/admin/squadres/edit/" + element_id + "?modded=true");
        //            a.setAttribute("rel", "timmytip");
        //            a.setAttribute("data-tip-title", "Modifica");
        //
        //
        //
        //            img.setAttribute("src", "/img/timmyshare/icon_edit.png");
        //
        //
        //            ul.appendChild(li);
        //            li.appendChild(a);
        //
        //            a.appendChild(img);
        //
        //            cell.appendChild(ul);
        //
        //            row.appendChild(cell);
        //            tblBody.appendChild(row);
        //
        //
        //            tbl.appendChild(tblBody);
        //
        //            body.appendChild(tbl);
        //
        //            tbl.setAttribute("border", "2");
        //
        //
        //            setTimeout(() => {
        //                console.log("click");
        //                a.dispatchEvent(new Event('click'));
        //            }, 3000);
        //
        //        }

        creaTab();

        function buttonCreate()
        {
            return new Promise((resolve, reject) => {
                var element_id = '<?= $element_id ?>';
                var body = document.getElementById("test");
                body.innerHTML = "";
                var a = document.createElement("button");
                var img = document.createElement("img");
                a.setAttribute("id", "schiaccio");
                a.setAttribute("href", "javascript:;");
                a.classList.add("index-row-edit");
                a.classList.add("switch");
                a.setAttribute("data-id", element_id);
                a.setAttribute("data-dest", "view_mode");
                a.setAttribute("data-ajax", "/admin/athletes/edit/" + element_id + "?modded=true");
                a.setAttribute("rel", "timmytip");
                a.setAttribute("data-tip-title", "Modifica");
                img.setAttribute("src", "/img/timmyshare/icon_edit.png");
                a.appendChild(img);
                body.appendChild(a);
                resolve(a);
            });
        }

        async function creaTab()
        {
            const a = await buttonCreate();
            setTimeout(() => {
                a.click();
            }, 500);
        }

    </script>
<? endif; ?>
<!-- ------------------------------------------------------------------- -->  