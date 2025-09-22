<style type="text/css">
    .formAdd .text-input input[type="text"] { width: 300px; }
</style>

<?= $this->Form->create('Match', array('action' => 'filters', 'prefix' => 'admin', 'class' => 'formAdd')); ?>

<div class="form_header">

    <h2>Filtra gare</h2>
    <ul>

        <li><?= $this->Form->submit('reset campi', array('type' => 'reset', 'div' => false, 'id' => 'formResetFields')); ?></li>
        <li><?= $this->Form->submit('annulla', array('type' => 'button', 'div' => false, 'id' => 'formReset')); ?></li>
        <li><?= $this->Form->submit('filtra tabella', array('type' => 'submit', 'div' => false)); ?></li>
    </ul>
    <div class="clear"></div>

</div><!-- close form_header -->

<?= $backend->getFilter('Match.Data'); ?>

<div class="clear"></div>

<?= $backend->getFilter('Match.Data2'); ?>

<div class="clear"></div>

<?= $backend->getFilter('Match.Ora'); ?>

<div class="clear"></div>

<?= $backend->getFilter('Campionati.Nome'); ?>

<div class="clear"></div>

<?= $backend->getFilter('Half.Descrizione'); ?>

<div class="clear"></div>

<?= $backend->getFilter('Match.CasaNome'); ?>

<div class="clear"></div>

<?= $backend->getFilter('Match.TrasfertaNome'); ?>

<div class="clear"></div>

<?= $backend->getFilter('Match.Giornata'); ?>

<div class="clear"></div>

<?= $backend->getFilter('Causalresult.Descrizione'); ?>

<div class="clear"></div>

<?= $backend->getFilter('Campi.Descrizione'); ?>

<div class="clear"></div>

<?= $backend->getFilter('Match.NomeGara'); ?>

<div class="clear"></div>

<?= $backend->getFilter('Match.Risultato'); ?>

<div class="clear"></div>

<?= $backend->getFilter('Match.NomeArbitro'); ?>

<div class="clear"></div>

<?= $backend->getFilter('Match.NomeArbitro2'); ?>

<div class="clear"></div>

<?= $backend->getFilter('Match.NomeDelegato'); ?>

<div class="clear"></div>

<?= $backend->getFilter('Match.NomeDelegatoA'); ?>

<div class="clear"></div>	


<!--//GIUSEPPE 2020-09-06 -------------------------------- -->
<!-- ricerca per sport -->

<?= $backend->getFilter('Campionati.sport'); ?>

<div class="clear"></div>

<!--  ---------------------------------------------------- -->

<?= $this->Form->end(); ?>
