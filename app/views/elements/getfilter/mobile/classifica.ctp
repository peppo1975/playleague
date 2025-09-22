<?php
if (!function_exists("array_orderby")) {
function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}
}
?>
<?
	foreach($giornate as $k => $giornata) {

		if($giornata['Match']['Giornata'] > $nextDay_real) unset($giornate[$k]);

	}
?>

<script type="text/javascript">

$(function(){
	
	$('.switch-giornata').bind('click', function(){
		
		$.get('/mobile/getFilter/<?=$campionato;?>/<?=$girone;?>/classifica/0/' + $(this).attr('data-giornata-id'), function(data){
			
				$('.filter-container').empty().append(data).trigger('create');
			
		});
		
	});
	
});

</script>

<script type="text/javascript">
	
	$(function(){
	
		$('select[name="giornata_id"]').die('change').live('change', function(){
		
			var me      = $(this);
			var buttons = $('.buttons-container');
			
			buttons.find('ul').find('li[data-giornata-id='+me.val()+']').trigger('click');
		
		});
	
	});

</script>
	<div data-role="navbar" class="buttons-container" style="display: none;">
									<ul class="switch-table-menu">
		
										<? foreach ($giornate as $i => $giornata): ?>
											
										<li class="switch-giornata <?=($giornata['Match']['Giornata']==$nextDay)? 'selected' : '';?>" data-giornata-id="<?=$giornata['Match']['Giornata'];?>"><a href="javascript:;" title="Giornata <?=$giornata['Match']['Giornata'];?>"><?=$giornata['Match']['Giornata'];?></a></li>
									
										<? endforeach; ?>

									</ul>
	</div>
	
	<select name="giornata_id">
	
		<? foreach ($giornate as $i => $giornata): ?>
		
			<option <?=($giornata['Match']['Giornata']==$nextDay)? 'selected="selected"' : '';?> value="<?=$giornata['Match']['Giornata'];?>">Giornata: <?=$giornata['Match']['Giornata'];?></option>
		
		<? endforeach; ?>
	
	</select>
									
									<div class="clear"></div>									
									
									<div id="results-box">
									
									<!-- TABELLA CALENDARIO -->
									
									<? foreach ($giornate as $i => $giornata): ?>
									
									<? if($giornata['Campionati']['Italiana'] == 'Si') break; ?>
									
									<table class="table-matches <?=($giornata['Match']['Giornata'] != $nextDay)? 'hidden' : '';?>" data-giornata-id="<?=$giornata['Match']['Giornata'];?>">
										<tr class="table-header">
												<th>Pos.</th>
												<th>Societ&agrave;</th>
												<th>Punti</th>
	
										</tr>
										
										<? if(!isset($arr_class[$giornata['Match']['Giornata']])) continue; ?>
										
										<? $c_classifica = $arr_class[$giornata['Match']['Giornata']]; ?>
										<? $classifiche  = array_orderby($c_classifica,'Punti',SORT_DESC); ?>
										
										<? 
											
											$c_classifica = $arr_class[$giornata['Match']['Giornata']];
											foreach ($c_classifica as $k => $classifica) 
												$c_classifica[$k]['DiffReti'] = (int)($classifica['GoalFatti'] - $classifica['GoalSubiti']);									
											$classifiche = array_orderby($c_classifica,'Punti',SORT_DESC,'DiffReti',SORT_DESC,'GoalFatti',SORT_DESC,'CoppaDisciplina',SORT_ASC);
											
										?>										
										
										<? foreach ($classifiche as $k => $classifica): ?>
										

										
										<tr class="<?=(($k+1) % 2 == 0)? 'alternate' : '';?>" data-casa-id="<?=$classifica['SquadraCampionato'];?>">
											
											<td><?=($k+1);?></td>
											<td><a data-transition="pop" href="#classifica-<?=$giornata['Match']['Giornata'];?>-<?=$k;?>" data-rel="popup" title=""><?=$classifica['SquadraNome'];?></a></td>
											
											
											<td><?=$classifica['Punti'];?></td>
											<!--
											<td><?=$classifica['Giocate'];?></td>
											<td><?=$classifica['Vinte'];?></td>
											<td><?=$classifica['Perse'];?></td>
											<td><?=$classifica['Nulle'];?></td>
											<td><?=$classifica['GoalFatti'];?></td>
											<td><?=$classifica['GoalSubiti'];?></td>
											<td><?=$classifica['CoppaDisciplina'];?></td>
											-->
											
											
										</tr>
										
										<? endforeach; ?>
										
									</table>
													<div class="popup-container">
													
															<? foreach ($classifiche as $k => $classifica): ?>
										
						
											<div id="classifica-<?=$giornata['Match']['Giornata'];?>-<?=$k;?>" data-role="popup">
											<h3><?=$classifica['SquadraNome'];?></h3>
											<table>
										
											<tr><td>Punti</td><td class="number"><?=$classifica['Punti'];?></td></tr>
											<tr><td>Giocate</td><td  class="number"><?=$classifica['Giocate'];?></td></tr>
											<tr><td>Vinte</td><td  class="number"><?=$classifica['Vinte'];?></td></tr>
											<tr><td>Perse</td><td  class="number"><?=$classifica['Perse'];?></td></tr>
											<tr><td>Nulle</td><td class="number"><?=$classifica['Nulle'];?></td></tr>
											<tr><td>Goal fatti</td><td class="number"><?=$classifica['GoalFatti'];?></td></tr>
											<tr><td>Goal subiti</td><td class="number"><?=$classifica['GoalSubiti'];?></td></tr>
											<tr><td>Coppa disc.</td><td class="number"><?=$classifica['CoppaDisciplina'];?></td></tr>
											</table>
											</div>
								
										<? endforeach; ?>
											</div>

									<? endforeach; ?>
									
									</div><!-- close results-box -->
