<script type="text/javascript">
    if (typeof $ != "undefined")
    {
        $("#genera").click(function ()
        {
            $.get("/admin/users/generatepwd", function (ret)
            {
                $("#YearbookSignupCode").val(ret.pwd);
            }, 'json');
        });
    }
</script>   

<?= $this->element("/backend/add_edit_scripts"); ?>

<?= $this->element("/backend/edit_scripts"); ?>


<?= $this->Form->create('Yearbook', array('action' => 'edit', 'prefix' => 'admin', 'class' => 'formAdd', 'type' => 'file')); ?>

<div class="form_header">
    <!--//GIUSEPPE 2023-07-28 ***************************************************************************-->
        <!--<h2>Modifica annuario: <span><?= $this->data['Athlete']['Anagrafica'] . ' - ' . $this->data['Yearbook']['Tessera']; ?></span></h2>-->
    <h2>Modifica annuario: <span><?= $this->data['Athlete']['Anagrafica'] . ' - ' . $this->data['Yearbook']['card_id']; ?></span></h2>
    <!--*************************************************************************************************-->
    <ul>

        <li><?= $this->Form->submit('reset campi', array('type' => 'reset', 'div' => false)); ?></li>
        <li><?= $this->Form->submit('annulla', array('type' => 'button', 'div' => false, 'id' => 'formReset')); ?></li>
        <li><?= $this->Form->submit('modifica', array('type' => 'submit', 'div' => false)); ?></li>
    </ul>
    <div class="clear"></div>

</div><!-- close form_header -->

<input type="hidden" name="modded" value="false" />

<?= $this->Form->input('Athlete.Anagrafica', array('type' => 'hidden')); ?>

<?
$options = array();
foreach ($AnniSportivi as $AnnoSportivo)
{
    $options[$AnnoSportivo['AnniSportivi']['AnnoSportivo']] = $AnnoSportivo['AnniSportivi']['AnnoSportivo'];
}
?>

<script type="text/javascript">
    if (typeof $ != "undefined")
    {
        $(function ()
        {

            function getFilter()
            {

                anno = $("#YearbookAnnoSportivo");
                squadra = $("#YearbookNomeSquadraCampionato");

                if (anno.val() == '')
                {

                    squadra.attr('data-url', '/admin/yearbooks/searchSquadraCampionato');

                }
                else
                {

                    squadra.attr('data-url', '/admin/yearbooks/searchSquadraCampionato/' + anno.val());

                }

            }

            $(document).ready(function ()
            {

                getFilter();

            });

            $('.formAdd').delegate("#YearbookAnnoSportivo", "change", function ()
            {

                getFilter();

            });

        });
    }
</script>   

<?= $this->Form->input('AnnoSportivo', array('type' => 'select', 'options' => $options)); ?>

<div class="clear"></div>

<!-- stampa etichetta -->

<ul class="tab-menu">
    <li>
        <a rel="timmytip" href="/admin/prints/yearLabel/<?= $this->data['Yearbook']['Annuario']; ?>" title="Stampa etichetta">
            <img src="/img/timmyshare/icon_print.png">
        </a>
    </li>
</ul>

<!-- fine stampa etichetta -->
<!--//GIUSEPPE 2023-07-28 ***************************************************************************-->
<?//= $this->Form->input('Tessera', array('readonly' => 'readonly')); ?>
<div class="input select">
    <label for="YearbookCardId">Tessera</label>
    <input name="data[Yearbook][card_id]" readonly="readonly" id="YearbookCardId" value="<?= $this->data['Yearbook']['card_id'] ?>">
</div>
<!--************************************************************************************************-->

<div class="clear"></div>

<?= $this->Form->input('DataVidimazione', array('label' => 'Data di Vidimazione', 'type' => 'text', 'class' => 'datePicker')); ?>   

<?= $this->Form->input('SquadraCampionato', array('type' => 'hidden')); ?>


<div class="clear"></div>

<?= $this->Form->input('TipoAssicurazione', array('type' => 'hidden')); ?>

<div class="clear"></div> 

<!-- Campi nascosti con id -->
<?= $this->Form->input('NomeSquadraCampionato', array('label' => 'Squadra/Campionato', 'class' => 'autoComplete', 'data-url' => '/admin/yearbooks/searchSquadraCampionato', 'data-dest' => 'YearbookSquadraCampionato')); ?>
<? //=$this->Form->input('AtletaSearch',array('label' => 'Atleta', 'data-id' => $this->data['Yearbook']['Atleta'], 'class' => 'searchAthlete', 'data-url' => '/admin/athletes/searchAthlete','data-dest' => 'YearbookAtleta'));?>
<?= $this->Form->input('AtletaSearch', array('label' => 'Atleta', 'data-id' => $this->data['Yearbook']['Atleta'], 'class' => 'searchAthlete', 'value' => $this->data['Athlete']['Anagrafica'], 'data-dest' => 'YearbookAtleta')); ?>
<?= $this->Form->input('Atleta', array('type' => 'hidden')); ?>
<?=
$this->Form->input('Responsabile',
        array(
            'type' => 'radio',
            'options' => array('Si' => 'Si', 'No' => 'No'),
        ));
?>  
<?=
$this->Form->input('isAdmin',
        array(
            'legend' => 'Amministratore squadra',
            'type' => 'radio',
            'options' => array('1' => 'Si', '0' => 'No'),
        ));
?>  
<?
$options1 = array();
foreach ($TipiAssicurazione as $TipoAssicurazione)
{
    $options1[$TipoAssicurazione['TipiAssicurazione']['TipoAssicurazione']] = $TipoAssicurazione['TipiAssicurazione']['Descrizione'];
}
?>

<?= $this->Form->input('signup_code', array('label' => 'Codice controllo', 'type' => 'text')); ?>

<div class="input">
    <label>&nbsp;</label>
    <?= $this->Form->submit('Genera codice', array('type' => 'button', 'div' => false, 'id' => 'genera')); ?>
</div>      

<div class="clear"></div>

<?= $this->Form->input('TipoAssicurazione', array('type' => 'select', 'options' => $options1, 'empty' => true)); ?>

<div class="clear"></div>





<!--//GIUSEPPE 2019-11-11 ********************* --> 
<? if ($this->data['Yearbook']['TipoSport'] == "TENNIS"): ?>
    <? // print_r($this->data['Yearbook']); ?>
    <br>

    <!--            <div class="row">
                    <div class="input text">
                        <label for="YearbookPuntiSingoloPlus">Punti Singolo +/-</label>
                        <input name="data[Yearbook][PuntiSingoloPlus]" type="number" value="<?= $this->data['Yearbook']['PuntiSingoloPlus'] ?>" id="YearbookPuntiSingoloPlus">
                    </div>
                </div>
                <div class="row">
                    <div class="input text">
                        <label for="YearbookPuntiDoppioPlus">Punti Doppio +/-</label>
                        <input name="data[Yearbook][PuntiDoppioPlus]" type="number" value="<?= $this->data['Yearbook']['PuntiDoppioPlus'] ?>" id="YearbookPuntiDoppioPlus">
                    </div>  
                </div>-->

    <div class="input text">
        <label for="YearbookPuntiSingoloPlus">Punti Singolo +/-</label>
        <input name="" type="number" id="PuntiSingoloPlus">
    </div>

    <div class="input text">
        <label for="YearbookPuntiDoppioPlus">Punti Doppio +/-</label>
        <input name="" type="number"  id="PuntiDoppioPlus">
    </div>  


    <div class="input text">
        <label for="YearbookPuntiDoppioPlus">Note punti</label>
        <textarea rows="4" cols="50" name="CommentPoints" id="CommentPoints"></textarea>

    </div>

    <div class="input">
        <label>&nbsp;</label>
        <input type="button" id="insert_punti_plus" value="Inserisci punti plus">
    </div>



    <div class="clear"></div>
    <!--tabella-->
    <h3>Plus points</h3>
    <table id="plus_points_table">
        <tr>
            <th>S.</th>
            <th>D.</th>
            <th>Squadra</th>
            <th>Note</th>
            <!--<th></th>-->
            <th></th>
        </tr>
        <? $punti_plus = $this->data['Yearbook']['Plus']; ?>
        <? foreach ($punti_plus as $key => $punti): ?>
            <tr id="row_<?= $punti['PuntoPlus'] ?>">
                <td><?= $punti['SingoloPlus'] ?></td>
                <td><?= $punti['DoppioPlus'] ?></td>
                <td><?= $punti['Denominazione'] ?></td>
                <td><?= $punti['Descrizione'] ?></td>
                <!--<td><img src="/img/timmyshare/icon_edit.png" width="15" height="15"></td>-->
                <td id="<?= $punti['PuntoPlus'] ?>" class="delete_plus"><img src="/img/timmyshare/icon_delete.png" width="16" height="16" alt="cancella"></td>
            </tr>
        <? endforeach; ?>
    </table>
    <p></p>
<? endif; ?>
<!-- ****************************************** --> 



<?= $this->Form->input('Note'); ?>

<div class="clear"></div>   





<div class="clear"></div>   






<!--//GIUSEPPE 2019-11-11 ********************* --> 
<? if ($this->data['Yearbook']['TipoSport'] == "CALCIO"): ?>
    <h3>Forum</h3>

    <?
    $ruoli = array(
        "POR" => "POR",
        "CEN" => "CEN",
        "LAT" => "LAT",
        "UNI" => "UNI",
        "PIV" => "PIV",
        "ALL" => "ALL",
        "DIR" => "DIR",
        "DIF" => "DIF",
        "ATT" => "ATT",
    );
    ?>

    <?= $this->Form->input('NumeroMaglia', array('type' => 'text', 'label' => 'Numero maglia')); ?>

    <?= $this->Form->input('Ruolo', array('type' => 'select', 'label' => 'Ruolo', 'empty' => true, 'options' => $ruoli)); ?>
<? endif; ?>
<!-- ****************************************** --> 


<script type="text/javascript">
    if (typeof $ != "undefined")
    {
        $(function ()
        {

            var value = $("#YearbookGiovanili").val();

            if (value == 'Si')
            {

                $("#checkGiovanili").attr('checked', true);

            }
            else if (value == 'No')
            {

                $("#checkGiovanili").attr('checked', false);

            }

            $("#checkGiovanili").change(function ()
            {

                var checked = $(this).is(':checked');

                if (checked)
                {

                    $("#YearbookGiovanili").val('Si');

                }
                else
                {

                    $("#YearbookGiovanili").val('No');

                }

            });



            /* //GIUSEPPE */
            $("#insert_punti_plus").click(function ()
            {
                //location.reload();



                var annuario = '<?= $this->data['Yearbook']['Annuario'] ?>';
                var singolo = $("#PuntiSingoloPlus").val();
                var doppio = $("#PuntiDoppioPlus").val();
                var note = addslashes($("#CommentPoints").val());
                var campionato_text = '<?= addslashes($this->data['Yearbook']['NomeSquadraCampionato']) ?>';
                var campionato = '<?= addslashes($this->data['Yearbook']['NomeSquadraCampionato']) ?>';
                var tessera = '<?= $this->data['Yearbook']['Tessera'] ?>';

                var plus = {Annuario: annuario, Tessera: tessera, SingoloPlus: singolo, DoppioPlus: doppio, Descrizione: note};


                if (singolo == "" && doppio == "")
                {
                    alert("Inserisci almeno un punteggio plus");
                    return;
                }
                console.log(plus);

                $.post("/admin/yearbooks/addPlus/", plus, function (data)
                {
                    console.log(data);
                    $("#plus_points_table").append("<tr><td>" + singolo + "</td><td>" + doppio + "</td><td>" + campionato + "</td><td>" + note + "</td></tr>");
                    $("#PuntiSingoloPlus").val("");
                    $("#PuntiDoppioPlus").val("");
                    $("#CommentPoints").val("");
                });



            });

            $(".delete_plus").click(function ()
            {
                id = $(this).attr('id');
                del = confirm("Cancellare il punteggio selezionato?");
                if (del)
                {
                    $.post("/admin/yearbooks/delPlus/", {id: id}, function (data)
                    {
                        console.log(data);
                    });
                    $("#row_" + id).hide();
                }

            });
            /* ********** */

        });







        function addslashes(string)
        {
            return string.replace(/\\/g, '\\\\').
                    replace(/\u0008/g, '\\b').
                    replace(/\t/g, '\\t').
                    replace(/\n/g, '\\n').
                    replace(/\f/g, '\\f').
                    replace(/\r/g, '\\r').
                    replace(/'/g, '\\\'').
                    replace(/"/g, '\\"');
        }
    }
</script>



<!--//GIUSEPPE 2019-11-11 ********************* --> 
<? if ($this->data['Yearbook']['TipoSport'] == "CALCIO"): ?>
    <div class="input">
        <label for="checkGiovanili">Giovanili</label>
        <input type="checkbox" id="checkGiovanili" />
        <?= $this->Form->input('Giovanili', array('type' => 'hidden')); ?>
    </div>  
<? endif; ?>
<!-- ****************************************** --> 


<?= $this->Form->end(); ?>
    
