<div class="breadcrumbs-container">

	<ul>

		<li>
			<a data-ajax="false" href="/mobile" title="Home page">
				Home
			</a>
			&rsaquo; 
		</li>
		<li>
			<a data-ajax="false" href="/mobile/categories/<?=$parent['Page']['id'];?>/<?=strtolower(Inflector::Slug($parent['Page']['title'],'-'));?>" title="<?=$parent['Page']['title'];?>">
				<?=$parent['Page']['title'];?>
			</a>
			&rsaquo;
		</li>
		<li>
			<?=$data['Page']['title'];?> 
		</li>
		
	</ul>
	
</div>


<script>

	$(document).bind('pageinit', function(){
	
		var data = <?=json_encode($albo);?>;
		
		$('select[name="type_id"]').die('change').live('change', function(){
		
			var me = $(this);
			
			$('.results-box').hide();
			$('select[name="champ_id"]').parents('div.input').hide();
			$('select[name="champ_id"]').parents('div.input').find('.ui-btn-text').find('span').text('Seleziona campionato');
			$('select[name="champ_id"]').empty();
			
			
			if(me.val() == "")
				return false;
			
			$('select[name="champ_id"]').parents('div.input').show();
			$('select[name="champ_id"]').empty().append('<option value="">Seleziona campionato</option>');
			
			for(i in data[me.val()]) {
			
				$('select[name="champ_id"]').append('<option value="'+i+'">'+i+'</option>');
			
			}
			
			$('select[name="champ_id"]').trigger('create');
		
		});
		
		$('select[name="champ_id"]').die('change').live('change', function(){
		
			var me     = $(this);
			var values = data[$('select[name="type_id"]').val()][me.val()];
			
			$('.results-box').empty().show();
			//$('.results-box').append();
			
			var ul = $('<ul data-inset="true" data-role="listview" data-theme="a">');
				ul.append('<li class="ui-bar-a" data-form="ui-bar-a" data-theme="a" data-swatch="a" data-role="list-divider" role="heading">'+me.val()+'</li>');
			
			for(i in values) {
			
				ul.append('<li><h2>'+i+'</h2><div class="page-content">'+values[i]+'</div></li>');
			
			}
			
			$('.results-box').append(ul).trigger('create');
		
		});
	
	});

</script>

<div class="albo-container">

	<h2><?=$data['Page']['title'];?></h2>
	
	<? if($data['Page']['content'] != ""): ?>
	
		<div class="page-content">
		
			<?=$data['Page']['content'];?>
		
		</div>
	
	<? endif; ?>
	
	<div class="filter-albo">
	
		<div class="input champ-input">
	
			<select name="type_id" autocomplete="off">
			
					<option value="">Seleziona tipologia campionato</option>
				
					<? foreach($albo as $type => $d): ?>
					
						<option value="<?=$type;?>"><?=$type;?></option>
					
					<? endforeach; ?>
				
			</select>
		
		</div>		
	
		<div class="input champ-input" style="display: none;">
	
			<select name="champ_id" autocomplete="off">
			
					<option value="">Seleziona campionato</option>
				
			</select>
		
		</div>			
	
	</div>
	
	<div class="results-box" style="display: none;">
	
		
	
	</div>

</div>