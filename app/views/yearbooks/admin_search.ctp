
<?= $this->Form->create('Yearbook', array('action' => 'search', 'prefix' => 'admin', 'class' => 'formAdd')); ?>

<div class="form_header">

    <h2>Ricerca tabella annuario atleti</h2>
    <ul>

        <li><?= $this->Form->submit('reset campi', array('type' => 'reset', 'div' => false, 'id' => 'formResetFields')); ?></li>
        <li><?= $this->Form->submit('annulla', array('type' => 'button', 'div' => false, 'id' => 'formReset')); ?></li>
        <li><?= $this->Form->submit('cerca', array('type' => 'submit', 'div' => false)); ?></li>
    </ul>
    <div class="clear"></div>

</div><!-- close form_header -->

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

    //GIUSEPPE 2018-05-04 ----------------------------------------------
    $("#print_contacts").click(function ()
    {
        //console.log("qui");

        var array_ricevuta = {};

        array_ricevuta['atleti'] = [];

        $(".index-select-checkbox").each(function ()
        {
            var singolo = {};

            if ($(this).attr("checked"))
            {

                singolo['atleta'] = $("tr#" + $(this).val() + " .td_atleta").html();
                singolo['nato'] = $("tr#" + $(this).val() + " .td_data_nasc").html();
                singolo['costo'] = $("tr#" + $(this).val() + " .td_costo").html();
                singolo['assicurazione'] = $("tr#" + $(this).val() + " .td_tipo_assicurazione").html();
                singolo['tessera'] = $("tr#" + $(this).val() + " .td_tessera").html();

                array_ricevuta['atleti'].push(singolo);
                array_ricevuta['squadra_manifestazione'] = $("tr#" + $(this).val() + " .td_squadra_campionato").html();

                array_ricevuta['vidimazione'] = $("tr#" + $(this).val() + " .td_data_vidimazione").html();
            }

        });
        if (array_ricevuta['atleti'].length > 0)
        {
            $.post("/sections/saveFileTemp", {json_array: JSON.stringify(array_ricevuta)}, function (data)
            {
                console.log(data) //$uniqid
                //location.href = "/sections/getAthletes/" + data;
                window.open("/sections/getAthletes/" + data, '_blank');

            })
        }
        else
        {
            alert('DEVI SELEZIONARE ALMENO UN TESSERATO');
        }
        //console.log((array_ricevuta.length));
        //console.log(JSON.stringify(array_ricevuta));
    });
    //------------------------------------------------------------------
</script>

<?
$options = array();
$options[''] = '';
foreach ($AnniSportivi as $AnnoSportivo)
{
    $options[$AnnoSportivo['AnniSportivi']['AnnoSportivo']] = $AnnoSportivo['AnniSportivi']['AnnoSportivo'];
}
?>

<?= $this->Form->input('AnnoSportivo', array('type' => 'select', 'options' => $options)); ?>

<? if ($layout == "tablet"): ?>
    <div class="clear"></div>
<? endif; ?>

<div class="input text <? if ($layout == "desktop"): ?>squa-camp<? endif; ?>">
    <?= $this->Form->input('NomeSquadraCampionato', array('label' => 'Squadra/Campionato', 'class' => 'autoComplete big', 'data-url' => '/admin/yearbooks/searchSquadraCampionato', 'data-dest' => 'isnull', 'div' => false)); ?>
</div>

<div class="input text <? if ($layout == "desktop"): ?>nome-atleta<? endif; ?>">
    <?= $this->Form->input('NomeAtleta', array('label' => 'Atleta', 'class' => 'autoComplete', 'data-url' => '/admin/yearbooks/searchAtleta', 'data-dest' => 'isnull', 'div' => false)); ?>
</div>

<div class="input text <? if ($layout == "desktop"): ?>nome-atleta<? endif; ?>">
    <?= $this->Form->input('Athlete.DataNascita', array('label' => 'Data di nascita (atleta)', 'class' => 'datePicker', 'type' => 'text', 'div' => false)); ?>
</div>	

<div class="input text <? if ($layout == "desktop"): ?>responsabile<? else: ?>responsabile-tablet<? endif; ?>">
    <?=
    $this->Form->input('Responsabile', array(
        'type' => 'radio',
        'options' => array('Si' => 'Si', 'No' => 'No'),
        'div' => false
    ));
    ?>

    <?=
    $this->Form->input('isAdmin', array(
        'type' => 'radio',
        'options' => array('Si' => 'Si', 'No' => 'No'),
        'div' => false,
        'legend' => 'Amministratore squadra'
    ));
    ?>	

</div>

<? if ($layout == "tablet"): ?>
    <div class="clear"></div>
<? endif; ?>

<div class="text <? if ($layout == "desktop"): ?>note input<? endif; ?>">
    <?= $this->Form->input('Note', array('divi' => false)); ?>
</div>

<div class="clear"></div>

<div class="<? if ($layout == "desktop"): ?>box-annuario-atleti<? else: ?>input<? endif; ?>">

    <?= $this->Form->input('Tessera', array('maxlength' => 8)); ?>

    <?= $this->Form->input('DataVidimazione', array('label' => 'Data di Vidimazione', 'type' => 'text', 'class' => 'datePicker')); ?>	

    <?
    $options1 = array();
    $options1[''] = '';
    foreach ($TipiAssicurazione as $TipoAssicurazione)
    {
        $options1[$TipoAssicurazione['TipiAssicurazione']['Descrizione']] = $TipoAssicurazione['TipiAssicurazione']['Descrizione'];
    }
    ?>

    <?= $this->Form->input('Yearbook.NomeAssicurazione', array('type' => 'select', 'options' => $options1, 'label' => 'Tipo assicurazione')); ?>

</div>
<!--<div class="input text">
    <input type="button" id="ricevuta" value="STAMPA RICEVUTA">
</div>-->
<div class="clear"></div>	



<?= $this->Form->end(); ?>
