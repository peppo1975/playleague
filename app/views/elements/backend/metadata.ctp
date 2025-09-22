<?=$this->Form->input('Metadata.title', array('type' => 'text', 'class' => 'big', 'label' => 'Titolo pagina (per una corretta indicizzazione si consiglia inserire max. 60 caratteri)'));?>
			
<div class="clear"></div>
			
<?=$this->Form->input('Metadata.keywords', array('type' => 'text', 'class' => 'big', 'label' => 'Keywords (per una corretta indicizzazione si consiglia usare da 1 a 10 keywords, separate da virgola)'));?>
			
<div class="clear"></div>
			
<?=$this->Form->input('Metadata.description', array('type' => 'textarea', 'label' => 'Descrizione (per una corretta indicizzazione si consiglia inserire max. 150 caratteri)'));?>