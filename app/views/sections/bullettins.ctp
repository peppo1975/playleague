<?

//debug($campionati);

?>

<div class="wrapper-box">
	<div class="wrapper-box-top"></div>
		<div class="wrapper-box-contents">
			<div class="contents-box" id="bg-retino">
				<h1>Bollettini e calendari stagione <?=$anno;?> - <?=ucfirst($this->params['pass'][1]);?></h1>

					<div class="contents-block-left">
					<? foreach($campionati as $campionato): ?>
					
					<?
					
						$gironi_order = array_orderby($campionato['Half'], 'Descrizione', SORT_ASC);
					
					?>

					<? foreach($gironi_order as $gironi): ?>
					
						<?
						
						$title = $campionato['Campionati']['Nome'] . ' - ' . $gironi['Descrizione'];
						
						?>
						
						<div class="block-box">
						<a href="javascript:;" data-campionato-id="<?=$campionato['Campionati']['Campionato'];?>" data-girone-id="<?=$gironi['GironeCampionato'];?>" title="<?=$title;?>"><?=$title;?></a>
						</div>
						
					<? endforeach; ?>

					<? endforeach;?>
					
					<? if(!count($campionati)):?>
					
						Nessun campionato presente con i seguenti criteri.
					
					<? endif; ?>
					
					</div><!-- close contents-box-left -->
					<div class="contents-box-right">
					<script type="text/javascript">
					$(function(){
						
						$("#categories").find('li').click(function(){
							alert('click');
						});
					
					});
					</script>
							<div class="contents-box-right-container" id="categories"><!-- il blocco dei tags prende l'id categories -->
								<h3>Anno sportivo</h3>
								<?
									
									$urls = explode('/', $this->params['url']['url']);
									foreach($urls as $k => $param) {
										if(in_array($param, $this->params['pass'])) unset($urls[$k]);
									}
								
								?>
								<ul class="anno">
									<? foreach($anni as $anno_s): ?>
										<li <?if($anno_s == $this->params['pass'][2]):?>class="selected"<?endif;?>><a href="javascript:;" data-url="/<?=$urls[0];?>/c5/maschile/<?=$anno_s;?>" title="Anno sportivo <?=$anno_s;?>"><?=$anno_s;?></a></li>
									<? endforeach; ?>
								</ul>								
								<h3>Tipo</h3>
								<ul class="type">
									<li <?if('maschile' == $this->params['pass'][1]):?>class="selected"<?endif;?>><a href="javascript:;" data-url="/<?=$urls[0];?>/c5/maschile/<?=$anno;?>" title="Campionato maschile">Maschile</a></li>
									<li <?if('femminile' == $this->params['pass'][1]):?>class="selected"<?endif;?>><a href="javascript:;" data-url="/<?=$urls[0];?>/c5/femminile/<?=$anno;?>" title="Campionato femminile">Femminile</a></li>
								</ul>						
							</div><!-- contents-box-right-container -->
					</div><!-- contents-box-right -->
					<div class="clear"></div>
			</div><!-- close contents-box -->
		</div><!-- close wrapper-box-contents -->
		<div class="wrapper-box-bottom"></div>
</div><!-- close wrapper-box -->