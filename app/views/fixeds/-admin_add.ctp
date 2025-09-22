
<script type="text/javascript">
    if (typeof $ != "undefined") {
        $(function () {
            $("#formAdd").attr("disabled", true);

            $("#FixedDescrizione, #FixedValore").keyup(function () {
                //console.log($(this).val());

                if ($("#FixedDescrizione").val() != "" && $("#FixedValore").val())
                {
                    $("#formAdd").attr("disabled", false);
                } else
                {
                    $("#formAdd").attr("disabled", true);
                }

            });

        });
    }
</script>

<?= $this->Form->create('Fixed', array('action' => 'add', 'prefix' => 'admin', 'class' => 'formAdd', 'type' => 'file')); ?>

<div class="form_header">

    <h2>Aggiungi nuova variabile globale</h2>
    <ul>

        <li><?= $this->Form->submit('reset campi', array('type' => 'reset', 'div' => false)); ?></li>
        <li><?= $this->Form->submit('annulla', array('type' => 'button', 'div' => false, 'id' => 'formReset')); ?></li>
        <li><?= $this->Form->submit('crea', array('type' => 'submit', 'div' => false, 'id' => 'formAdd')); ?></li>
    </ul>
    <div class="clear"></div>

</div><!-- close form_header -->

<h3>Creazione contenuto</h3>




<?= $this->Form->input('descrizione', array('label' => 'Variabile (utilizzare _ per collegare parole separate)', 'type' => 'text')); ?>
<div class="clear"></div>	
<?= $this->Form->input('valore', array('label' => 'Valore', 'type' => 'textarea')); ?>
<div class="clear"></div>	
<?= $this->Form->input('note', array('label' => 'Note', 'type' => 'textarea')); ?>



<?= $this->Form->end(); ?>
