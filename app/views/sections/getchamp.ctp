<?//$data = $campi; echo json_encode($data);?>		
<div class="row">
	<? /*
		<div class="col-md-12">
		<? if (!empty($regolamento)): ?>
		<blockquote class="alert alert-info">
		
		<div class="row">
		<div class="col-md-3"><i class="fa fa-caret-right"></i>
		<b>Iscrizione:</b> 15,00 €
		</div>
		<div class="col-md-3"><i class="fa fa-caret-right"></i>
		<b>Tesseramenti:</b> 15,00 €
		</div>
		
		<div class="col-md-3"><i class="fa fa-caret-right"></i>
		<b>Quota campo:</b> 5,00 €
		</div>
		
		
		<div class="col-md-3"><i class="fa fa-caret-right"></i>
		<b>Cauzione:</b> 50,00 €
		</div>
		</div>
		
		
		
		</blockquote>
		<blockquote class="with-borders">
		<h4 class="text-color-primary">Informazioni torneo/manifestazione</h4>
		<?=$regolamento;?>
		</blockquote>
		<? endif; ?>
		
		</div>
		
	*/ ?>
	<div class="col-md-12">
		<? $data = $campi; ?>		
		<div class="row">
			<? $i = 0; ?>
			<? foreach($data as $block): ?>
			<div class="col-md-3">
				<span class="thumb-info thumb-info-hide-wrapper-bg">
					<? $nomegirone = ""; ?>
					<? foreach ($block['Campi']['subscriptions'] as $girone => $orari): ?>
					<? $show = 0; ?>			
					<? for ($z = 0; $z < $orari['caselle']; $z++): ?>
					<? if ($orari['Campo'][$z]==$block['Campi']['Campo']): ?>
					<? $show = 1; ?>
					<? endif; ?>
					<? endfor; ?>					
					<? if ($show == 1): ?>
					
					<? foreach ($block['Campi']['Half'] as $half): ?>	
										



					<? if ($half['GironeCampionato'] == $girone) 
						{						
							$nomegirone[] = $half['Descrizione'];					
						}
					?>
					<? endforeach; ?>
					<? endif; ?>
					<? endforeach; ?>				
					<span class="thumb-info-wrapper">
						<? $thumb = $thumbnail->link(array('path' => $block['Campi']['img_evidenza'], 'w' => 240,'h' => 150, 'zc' => 1,'aoe' => 1)); ?>
						<img src="<?=(substr_count($thumb, "error")? '/img/website/ml-impianti-default.jpg' : $thumb);?>" class="img-responsive" alt="">
						<span class="thumb-info-title">
							<span class="thumb-info-inner"><?=$block['Campi']['Descrizione'];?></span>
							<? if ($block['Campi']['claim'] <> '') { ?>
								<span class="thumb-info-type"><?=$block['Campi']['claim'];?></span>
							<? } ?>
						</span>
					</span>
					<span class="thumb-info-caption">
						<span class="thumb-info-caption-text">
							
							<?				$address       = (($block['Campi']['Indirizzo'] == '')? $block['Campi']['Indirizzo'] : $block['Campi']['Indirizzo'] . ' -') . ' ' . $block['Campi']['Citta']; ?>
							
							<?=$this->Text->truncate($address,38,array('ending'=>'...'));?><br />
							

							<?  if(count($nomegirone)<=1) { ?>
								<b>Girone:</b>
								<? } else  { ?>
								<b>Gironi:</b>
								<? }; ?>
								<?=implode(", ",$nomegirone); ?>															
						</span>						
						<div class="champ-separator text-center">
							
							<a class="btn btn-info" onclick="timmy_load('/sections/hours/<?=$tipo;?>/<?=$block['Campi']['Campo'];?>/<?=$block['Campi']['Campionato'];?>?rand=' + Math.random());" data-id="<?=$block['Campi']['Campo'];?>" href="javascript:;">Visualizza orari</a>
						</div>						
					</span>
				</span>				
			</div>			
			<? if (($i+1) % 4 == 0): ?>			
		</div>
		<div class="row">
			
			<? endif; ?>
			<? $i++; ?>
			<? endforeach; ?>			
		</div>		
	</div>	
</div>