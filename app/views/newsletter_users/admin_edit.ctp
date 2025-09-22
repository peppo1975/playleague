
	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('NewsletterUser', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica utente newsletter</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('salva',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<div class="tab-container">
	
		<ul class="tab-selector">
			<li data-index="1" class="selected"><a href="javascript:;">Utente</a></li>
			<li data-index="2"><a href="javascript:;">Gruppi</a></li>
		</ul>

	<div class="tab-page tab-selected" data-index="1">	

		<?=$this->Form->input('id');?>	
	
		<?=$this->Form->input('email', array('label' => 'Email', 'type' => 'text'));?>
		
		<div class="clear"></div>	
		
		<?=$this->Form->input('name', array('label' => 'Nome', 'type' => 'text'));?>
		
		<?=$this->Form->input('surname', array('label' => 'Cognome', 'type' => 'text'));?>
		
		<div class="clear"></div>	
		
		<?=$this->Form->input('company', array('label' => 'Compagnia', 'type' => 'text'));?>
		
		<?=$this->Form->input('piva', array('label' => 'P.IVA', 'type' => 'text'));?>
		
		<div class="clear"></div>	
		
		<?=$this->Form->input('city', array('label' => 'Citt&agrave', 'type' => 'text'));?>
		
		<?=$this->Form->input('address', array('label' => 'Indirizzo', 'type' => 'text'));?>
		
		<div class="clear"></div>	
		
		<?=$this->Form->input('tel', array('label' => 'Telefono (casa)', 'type' => 'text'));?>
		
		<?=$this->Form->input('cel', array('label' => 'Cellulare', 'type' => 'text'));?>
		
		<?=$this->Form->input('fax', array('label' => 'Fax', 'type' => 'text'));?>

		<div class="clear"></div>	
	
	</div>
	
	<div class="tab-page" data-index="2">
	
			<h3>Gruppi</h3>
			
			<ul class="tag_list">
			
				<?foreach($groups as $group):?>
				
					<?$checked = false; ?>
				
					<?foreach($group['NewsletterUser'] as $user) {
					
						if($user['id'] == $this->data['NewsletterUser']['id']) $checked = true;
					
					}?>
					
					<li>
				
					  <?=$this->Form->checkbox('group_' . $group['NewsletterGroup']['id'], array('value' => $group['NewsletterGroup']['id'], 'hiddenField' => false, 'checked' => $checked));?>
					  <?=$group['NewsletterGroup']['title'];?>
				
					</li>
				
				<? endforeach; ?>
			
			</ul>
	
	</div>
		
	<?=$this->Form->end();?>