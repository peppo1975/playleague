
<script type="text/javascript">
  /*  if (typeof $ != "undefined")
    {
        $(function ()
        {
            //$("#formAdd").attr("disabled", true);

            $("#FixedDescrizione, #FixedValore").bind("keyup click",function ()
            {
                //console.log($(this).val());

                if ($("#FixedDescrizione").val() != "" && $("#FixedValore").val() != "")
                {
                    //$("#formAdd").attr("disabled", false);
                } else
                {
                    //$("#formAdd").attr("disabled", true);
                }

            });

        });


    }*/
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

<?= $this->Form->input('descrizione', array('label' => 'Nome variabile', 'type' => 'text')); ?>

<div class="clear"></div>	

<div class="post_content">

    <?= $this->element('/backend/ckeditor', array('name' => 'valore', 'title' => 'Valore variabile')); ?>

</div>

<div class="clear"></div>

<?= $this->Form->input('note', array('label' => 'Note', 'type' => 'textarea')); ?>

<div class="clear"></div>

<?= $this->Form->end(); ?>
