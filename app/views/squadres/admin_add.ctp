
<?= $this->Form->create('Squadre', array('action' => 'add', 'prefix' => 'admin', 'class' => 'formAdd', 'type' => 'file')); ?>

<div class="form_header">

    <h2>Aggiungi nuova squadra</h2>
    <ul>

        <li><?= $this->Form->submit('reset campi', array('type' => 'reset', 'div' => false)); ?></li>
        <li><?= $this->Form->submit('annulla', array('type' => 'button', 'div' => false, 'id' => 'formReset')); ?></li>
        <li><?= $this->Form->submit('crea', array('type' => 'submit', 'div' => false)); ?></li>
    </ul>
    <div class="clear"></div>

</div><!-- close form_header -->

<div class="clear"></div>	

<?php
//GIUSEPPE 03/10/2016 -------------------

$arrayRadio = array();

$res = mysql_query("SELECT * FROM TipoSport WHERE 1");

while ($row = mysql_fetch_assoc($res))
{
    $arrayRadio[] = $row['sport'];
}
?>

<?= $this->Form->radio('sport', $arrayRadio, array('value' => 0)); //GIUSEPPE  lo 0 sta per "seleziona il primo indice dell'array (quindi "CALCIO")"?>

<? // print_r($this->Form)// -------------------------------------- ?>


<?= $this->Form->input('Denominazione', array('label' => 'Denominazione', 'class' => 'big')); ?>

<?=
$this->Form->input('SquadraServizio',
        array(
            'legend' => 'Squadra di servizio',
            'type' => 'radio',
            'options' => array(1 => 'Si', 0 => 'No'),
        ));
?>			

<div class="clear"></div>	

<?= $this->Form->input('Storia', array('label' => 'Storia', 'style' => 'width: 500px; height: 300px;')); ?>

<div class="clear"></div>	

<!--//GIUSEPPE 2023-07-28 ---------------------------------------------- -->
<div id="test">
</div>
<!-- ------------------------------------------------------------------- --> 

<?=
$backend->getFiles('squadra_id', 0, array(
    'tag' => array(
        'Squadra' => 'Immagine squadra',
        'Logo' => 'Logo squadra',
        'Sponsor' => 'Sponsor squadra',
        'SponsorEsterno' => 'Sponsor esterno',
        'Trofeo' => 'Simbolo trofeo',
    ),
));
?>
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
                a.setAttribute("data-ajax", "/admin/squadres/edit/" + element_id + "?modded=true");
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
<?= $this->Form->end(); ?>

