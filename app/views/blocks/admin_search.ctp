<script type="text/javascript">

//$(function(){$("#BlockMotherPage").find('option:eq(1)').remove();});

</script>

	<?=$this->Form->create('Block', array('action' => 'search','prefix' => 'admin','class' => 'formAdd'));?>

	<div class="form_header">

								<h2>Ricerca blocco</h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false,'id' => 'formResetFields'));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('cerca',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
				<?=$this->Form->input('Block.mother_page', array('label' => 'Pagina madre', 'type' => 'select', 'options' => $tree, 'empty' => true));?>
			
			<div class="clear"></div>

			<?=$this->Form->input('Block.title', array('type' => 'text', 'label' => 'Titolo blocco', 'class' => 'big'));?>
			
			<div class="clear"></div>
			
			<?=$this->Form->input('Block.type_it', array('type' => 'select', 'empty' => true, 'label' =>'Tipo','options' => array('Mostra tutto' => 'Mostra tutto', 'Mostra anteprima' => 'Mostra anteprima')));?>

	<?=$this->Form->end();?>