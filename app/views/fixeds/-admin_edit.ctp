
<script type="text/javascript">
    if (typeof $ != "undefined")
    {
        $(function ()
        {
            //$("#formAdd").attr("disabled", true);

            $("#FixedDescrizione, #FixedValore").keyup(function ()
            {
                //console.log($(this).val());

                if ($("#FixedDescrizione").val() != "" && $("#FixedValore").val())
                {
                    $("#formAdd").attr("disabled", false);
                }
                else
                {
                    $("#formAdd").attr("disabled", true);
                }

            });

        });
    }
</script>

<?= $this->Form->create('Fixed', array('url' => '/admin/fixeds/edit/' . $id_contenuto, 'prefix' => 'admin', 'class' => 'formAdd', 'type' => 'file')); ?>

<div class="form_header">

    <h2>Modifica contenuto fisso</h2>
    <ul>

        <li><?= $this->Form->submit('reset campi', array('type' => 'reset', 'div' => false)); ?></li>
        <li><?= $this->Form->submit('annulla', array('type' => 'button', 'div' => false, 'id' => 'formReset')); ?></li>
        <li><?= $this->Form->submit('salva', array('type' => 'submit', 'div' => false, 'id' => 'formAdd')); ?></li>
    </ul>
    <div class="clear"></div>

</div><!-- close form_header -->

<h3>Modifica contenuto</h3>


<?= $this->Form->input('descrizione', array('label' => 'Variabile Globale (utilizzare _ per collegare parole separate)', 'type' => 'text', 'value' => $descrizione)); ?>
<div class="clear"></div>	
<?= $this->Form->input('valore', array('label' => 'Valore', 'type' => 'textarea', 'value' => $valore)); ?>
<div class="clear"></div>	
<?= $this->Form->input('note', array('label' => 'Note', 'type' => 'textarea', 'value' => $note)); ?>


<?= $this->Form->end(); ?>
