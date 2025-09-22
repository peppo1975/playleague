<?= $this->element("/backend/edit_scripts"); ?>


<?= $this->Form->create('Type', array('action' => 'edit', 'prefix' => 'admin', 'class' => 'formAdd', 'type' => 'file')); ?>

<div class="form_header">

    <h2>Modifica tipologia per manifestazione: <span><?= $this->data['Type']['Nome']; ?></span></h2>
    <ul>

        <li><?= $this->Form->submit('reset campi', array('type' => 'reset', 'div' => false)); ?></li>
        <li><?= $this->Form->submit('annulla', array('type' => 'button', 'div' => false, 'id' => 'formReset')); ?></li>
        <li><?= $this->Form->submit('modifica', array('type' => 'submit', 'div' => false)); ?></li>
    </ul>
    <div class="clear"></div>

</div><!-- close form_header -->

<?= $this->Form->input('id'); ?>

<?= $this->Form->input('Nome', array('label' => 'Nome', 'type' => 'text', 'class' => 'big')); ?>

<div class="clear"></div>
<h3>Associazione manifestazione</h3>

<div class="clear"></div>
<?= $this->Form->input('event_id', array('label' => 'Manifestazione da associare', 'type' => 'select', 'options' => $events)); ?>

<div class="clear"></div>
<br /><br />

<h3>Associazione sport</h3>

<?
$tipologie = array(
    'c5f' => 'Calcio a 5 Femminile',
    'c5m' => 'Calcio a 5 Maschile',
    'c7f' => 'Calcio a 7 Femminile',
    'c7' => 'Calcio a 7 Maschile',
    'c11' => 'Calcio a 11',
    't' => 'Tennis'
);
?>

<? foreach ($tipologie as $key => $tipologia): ?>
    <?=
    $this->Form->input('nome', array('label' => $tipologia, 'type' => 'select', 'name' => 'data[Type][content][0][' . $key . ']',
        'value' => (int) $this->data['Type']['content'][0][$key],
        'options' => array(
            '0' => 'No',
            '1' => 'Si'
        )
    ));
    ?>
<? endforeach; ?>

<br /><br />

<div class="clear"></div>
<br /><br />

<h3>Regolamento</h3>


<? for ($i = 1; $i <= 8; $i++): ?>

    <?=
    $this->Form->input('nome', array('label' => 'Nome voce regolamento', 'type' => 'text', 'name' => 'data[Type][content][' . $i . '][nome]',
        'value' => $this->data['Type']['content'][$i]['nome']
    ));
    ?>
    <?=
    $this->Form->input('valore', array('label' => 'Valore voce regolamento', 'type' => 'text', 'name' => 'data[Type][content][' . $i . '][valore]',
        'value' => $this->data['Type']['content'][$i]['valore']
    ));
    ?>


    <?=
    $this->Form->input('testo', array('label' => 'Testo aggiuntivo regolamento', 'type' => 'textarea', 'name' => 'data[Type][content][' . $i . '][testo]',
        'value' => $this->data['Type']['content'][$i]['testo']
    ));
    ?>

    <div class="clear"></div>
    <hr />
<? endfor; ?>



<?php echo $this->Form->input('matches', array('type' => 'select', 'multiple' => true, 'options' => $campionati, 'label' => 'Associa orari iscrizioni', 'selected' => $this->data['Type']['matches'])); ?>




<?= $this->Form->end(); ?>
	
