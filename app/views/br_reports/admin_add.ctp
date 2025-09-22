
	<?=$this->Form->create('BrReport', array('action' => 'add','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Aggiungi nuovo report</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('crea',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('user_id', array('type' => 'hidden', 'value' => $auth['id']));?>
	
	<?=$this->Form->input('priority', array('label' => 'Priorità', 'type' => 'select', 'options' => array('0' => 'Bassa','1'=>'Media','2'=>'Alta'), 'empty' => true));?>
	<?=$this->Form->input('zone_id', array('label' => 'Zona applicazione', 'type' => 'select', 'options' => $zones, 'empty' => true));?>
	<?=$this->Form->input('category_id', array('label' => 'Categoria', 'type' => 'select', 'options' => $categories, 'empty' => true));?>
	
	<?
	
		$type_requests = array(
		
		 'bug/malfunzionamento' => 'bug/malfunzionamento',
		 'richiesta sviluppo nuova procedura' => 'richiesta sviluppo nuova procedura',
		 'spiegazioni sul funzionamento' => 'spiegazioni sul funzionamento',		
		
		);
	
	?>
	
	<?=$this->Form->input('type_request', array('label' => 'Tipo di richiesta', 'type' => 'select', 'options' => $type_requests, 'empty' => true));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('email', array('label' => 'Indirizzo email utente', 'type' => 'text', 'value' => $auth['email']));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('title', array('label' => 'Titolo', 'type' => 'text','class' => 'big'));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('object', array('label' => 'Oggetto', 'type' => 'text', 'class' => 'big'));?>
	
	<div class="clear"></div>
	
	<div class="post_content">
	
		<?=$this->element('/backend/ckeditor', array('name' => 'content', 'title' => 'Messaggio/Segnalazione'));?>
	
	</div>
	
	<div class="clear"></div>

	<?=$backend->getFiles('brreport_id', 0);?>
				
	<?=$this->Form->end();?>
