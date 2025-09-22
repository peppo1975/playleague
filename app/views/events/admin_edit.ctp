<?= $this->element("/backend/edit_scripts"); ?>


<?= $this->Form->create('Event', array('action' => 'edit', 'prefix' => 'admin', 'class' => 'formAdd', 'type' => 'file')); ?>

<div class="form_header">

    <h2>Modifica manifestazione: <span><?= $this->data['Event']['Nome']; ?></span></h2>
    <ul>

        <li><?= $this->Form->submit('reset campi', array('type' => 'reset', 'div' => false)); ?></li>
        <li><?= $this->Form->submit('annulla', array('type' => 'button', 'div' => false, 'id' => 'formReset')); ?></li>
        <li><?= $this->Form->submit('modifica', array('type' => 'submit', 'div' => false)); ?></li>
    </ul>
    <div class="clear"></div>

</div><!-- close form_header -->

<?= $this->Form->input('id'); ?>

<?= $this->Form->input('Nome', array('label' => 'Nome', 'type' => 'text', 'class' => 'big')); ?>

<!-- //GIUSEPPE 2019-03-15 -->

<?=
$this->Form->input('id_sport', array(
    'label' => 'Tipo sport',
    'type' => 'select',
//    'options' => array('0' => 'CALCIO', '1' => 'TENNIS'), //GIUSEPPE 2020-09-01
    'options' => $sport, //GIUSEPPE 2020-09-01
));
?>

<!-- --------------------- -->

<div class="clear"></div>
<?= $this->Form->input('data_inizio', array('label' => 'Data inizio torneo', 'type' => 'text', 'class' => '')); ?>

<div class="clear"></div>
<?= $this->Form->input('data_fine', array('label' => 'Data fine iscrizioni', 'type' => 'text', 'class' => '')); ?>
<div class="clear"></div>


<div class="post">

    <?
    $config['toolbar'] = array(
        array('Source', '-', 'Undo', 'Redo', '-', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-'),
        array('Find', 'Replace', 'SelectAll', 'RemoveFormat'),
        array('Bold', 'Italic', 'Underline', 'Strike'),
        array('Image', 'Link', 'Unlink', 'Anchor'),
        array('NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote')
    );
    ?> 
    <div class="post_content">

        <?= $this->element('/backend/ckeditor', array('name' => 'content')); ?> 

    </div>

</div>

<div class="clear"></div>
<div id="formUploadContainer">

    <!-- //GIUSEPPE 2022-21-02-->
    <div class="row extern-link" style="display: none">
        <div class="col-lg">
            <div class="clear"></div>
            <?= $this->Form->input('IsExternLink', array('label' => 'Link esterno', 'type' => 'checkbox', 'class' => '')); ?>
            <div class="clear"></div>
            <?= $this->Form->input('ExternLink', array('label' => 'Inserisci il link esterno', 'type' => 'text', 'class' => '')); ?>
            <div class="clear"></div>
        </div>
    </div>
    <!-- ********************** -->

    <?=
    $backend->getFiles('event_id', $this->data['Event']['id'], array(
        'tag' => array('' => 'Logo'),
    ));
    ?>

</div>

<!-- //GIUSEPPE 2022-21-02-->
<script>
    IdSport = $("#EventIdSport").val();

    is_tennis();

    $("#EventIdSport").change(function ()
    {
        IdSport = $(this).val();
        is_tennis();
    });

    $("#EventIsExternLink").change(function ()
    {
        is_tennis();
    });


    /* ---------------------------------- */

    function is_tennis()
    {
        if (parseInt(IdSport) == 1)
        {
            $(".extern-link").show('fast');
            if ($("#EventIsExternLink").is(":checked"))
            {
                $("#EventExternLink").attr('disabled', false);
            }
            else
            {
                $("#EventExternLink").attr('disabled', true);
            }
            console.log("<?= $this->data['Event']['Nome']; ?>", IdSport, $("#EventIsExternLink").is(":checked"));
        }
        else
        {
            $(".extern-link").hide('fast');
        }
    }
</script>
<!-- ********************** -->

<?= $this->Form->end(); ?>
    
