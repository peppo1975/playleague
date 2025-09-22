<?=$this->Form->create('Right', array('url' => '/admin/users/create_rights'));?>

<?=$this->Form->input('group_id', array('label' => 'Gruppo', 'type' => 'select', 'options' => $groups));?>

<div class="clear"></div>

<?=$this->Form->input('resource', array('label' => 'Risorsa', 'type' => 'select', 'options' => $controllers, 'empty' => 'Scegli controller'));?>

<div class="clear"></div>

<?=$this->Form->input('action', array('label' => 'Azione', 'type' => 'text'));?>

<div class="clear"></div>

<?=$this->Form->input('allow', array('label' => 'Autorizzazione', 'type' => 'select', 'options' => array('1' => 'Si', '0' => 'No')));?>

<div class="clear"></div>

<?=$this->Form->end('Crea');?>