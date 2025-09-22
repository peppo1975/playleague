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
	<? ksort($maschile); ?>
	
	<? if (isset($femminile)): ?>
	<? else: ?>
	var femminile = new Array;
	<? $femminile = array(); ?>
	<? endif; ?>
	
		var sex = 0;
	$(document).bind('pageinit', function(){
	
	
		$(".sex-input select").live('change',function() {
			$(".letter-input,.letter-input .letter_id").hide();
			$(".squadra_id").hide();
			if ($(this).val() != '') {
				
				var sesso = parseInt($(this).val());
				$(".letter-input").show().find('.letter_id_' + sesso).show();
				sex = sesso;
			}
			
		});
		
		$(".letter_id select").bind('change',function() {
			
			var letter = $(this).val();
			$(".squadra_id").hide();
			if ($(this).val() != '-1') {
				$(".squadra_id_" + sex + "[data-letter='" + letter + "']").show();
			}
		
		});
		
		$(".squadra_id select").live('change',function() {
			if ($(this).val() != "") {
				//show    
				$.mobile.showPageLoadingMsg();
				$.get('/mobile/getsquadra/' + $(this).val() + '?type=<?=$data['Page']['id'];?>',function (ret) {
					$(".results-box").html(ret).show();
							//show    
					$.mobile.hidePageLoadingMsg();
				},'html');
			}
		});
		
	});

</script>

<div class="calcio-container">

	<h2><?=$data['Page']['title'];?></h2>
	
	<? if($data['Page']['content'] != ""): ?>
	
		<div class="page-content">
		
			<?=$data['Page']['content'];?>
		
		</div>
	
	<? endif; ?>
	
	<div class="filter-calcio">

		<div class="input year-input">
	
			<select name="year_id" autocomplete="off" onchange="location.href = '/<?=$this->params['url']['url'];?>?anno='+$(this).val();">
			
					<option <? if($anno == "all"): ?>selected="selected"<? endif; ?> value="all">Anno sportivo</option>
					
					<? foreach($anni as $a): ?>
					
						<option value="<?=$a;?>" <? if($a == $anno): ?>selected="selected"<? endif; ?>><?=$a;?></option>
					
					<? endforeach; ?>
				
			</select>
		
		</div>	
	
		<div class="input sex-input">
	
			<select name="sex_id" autocomplete="off">
			
					<option value="">Tipo campionato</option>
				
					<option value="0">Maschile</option>
					<? if (!isset($no_femminile)): ?>
					<option value="1">Femminile</option>
					<? endif; ?>
				
			</select>
		
		</div>		
	
		<div class="input letter-input" style="display: none;">
			<div class="letter_id_0 letter_id">
			<select name="letter_id_0" class="letter_id_0" autocomplete="off" data-type="0">
			
					<option value="-1">Iniziale squadra</option>
				
					<? foreach ($maschile as $alfa => $beto): ?>
					<? if (!empty($alfa)): ?>
					<option value="<?=$alfa;?>"><?=$alfa;?></option>
					<? else: ?>
					<option value="<?=$alfa;?>">0-9</option>
					<? endif;?>
					<? endforeach; ?>
						
			</select>
			</div>
			<div class="letter_id_1 letter_id">
			<select name="letter_id_1" class="" autocomplete="off" data-type="1">
			
					<option value="-1">Iniziale squadra</option>
				
					<? foreach ($femminile as $alfa => $beto): ?>
					
					<? if (!empty($alfa)): ?>
					<option value="<?=$alfa;?>"><?=$alfa;?></option>
					<? else: ?>
					<option value="<?=$alfa;?>">0-9</option>
					<? endif;?>
					
					<? endforeach; ?>
			</select>
			</div>
		
		</div>			
	
		<div class="input squadra-input">
	
			<? foreach ($maschile as $alfa => $squadre): ?>

			<div class="squadra_id_0 squadra_id" style="display: none;" data-letter="<?=$alfa;?>">
			<select name="squadra_id_<?=$alfa;?>" autocomplete="off">
							<option value="">Nome squadra</option>
							<? foreach ($squadre as $squadra): ?>
							
								<option value="<?=$squadra['Squadre']['Squadra'];?>"><?=$squadra['Squadre']['Denominazione'];?></option>
							
							<? endforeach; ?>
			</select>
			</div>
	
			<? endforeach; ?>


			<? foreach ($femminile as $alfa => $squadre): ?>

			<div class="squadra_id_1 squadra_id" style="display: none;" data-letter="<?=$alfa;?>">
			<select name="squadra_id_<?=$alfa;?>" autocomplete="off">
							<option value="">Nome squadra</option>
							<? foreach ($squadre as $squadra): ?>
							
								<option value="<?=$squadra['Squadre']['Squadra'];?>"><?=$squadra['Squadre']['Denominazione'];?></option>
							
							<? endforeach; ?>
			</select>
			</div>
	
			<? endforeach; ?>

		</div>	
		
	</div>
	
	<div class="results-box" id="results-box" style="display: none;">
	
		
	
	</div>

</div>