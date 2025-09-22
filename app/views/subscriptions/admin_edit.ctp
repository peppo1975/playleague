	<?=$this->Form->create('Subscription', array('action' => 'edit/' . $campionato['Campionati']['Campionato'],'prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica orari per il campionato: <span><?=$campionato['Campionati']['Nome'];?></span></h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('modifica',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->


<div class="tab-container">
<ul class="tab-selector">


		<? foreach ($gironi as $i => $girone): ?>
	
		<li data-index="<?=$i;?>" <? if ($i==0): ?>class="selected"<?endif;?>><a href="javascript:;">Girone "<?=$girone['Half']['Descrizione'];?>"</a></li>

		<? endforeach; ?>
</ul>


		<? foreach ($gironi as $i => $girone): ?>


<div class="tab-page <? if ($i==0):?>tab-selected<? endif;?>" data-curvalue="<?=(int)$iscrizioni[$girone['Half']['GironeCampionato']]['caselle'];?>"  data-index="<?=$i;?>">
	<?
		$caselle = array();

		for ($i=1;$i<=12;$i++) $caselle[$i]=$i;

	?>
	
	<?=$this->Form->input('iscrizioni[' . $girone['Half']['GironeCampionato'] .']',
	array(
	'name' => 'data[Gironi][' . $girone['Half']['GironeCampionato'] .'][caselle]',
	'class'=>'SubscriptionIscrizioni',
	'label' => 'Numero caselle orarie',
	'type' => 'select',
	'value' => (int)$iscrizioni[$girone['Half']['GironeCampionato']]['caselle'],
	'options' => $caselle

	));?>

	<div class="clear"></div>

	<div class="caselle">

	<? if (isset($iscrizioni[$girone['Half']['GironeCampionato']])): ?>


	<? for ($j = 0; $j < $iscrizioni[$girone['Half']['GironeCampionato']]['caselle']; $j++): ?>


	<div class="casella-skeleton clear" data-index="<?=$i;?>" style="border-bottom: 1px solid #CCC; padding-bottom: 10px; padding-top: 10px;">

		<div class="input">

			<label>Campo</label>

			<select name="data[Gironi][<?=$girone['Half']['GironeCampionato'];?>][Campo][]">

			<? foreach ($campi as $campo): ?>

				<option value="<?=$campo['id'];?>" <? if ($campo['id'] == $iscrizioni[$girone['Half']['GironeCampionato']]['Campo'][$j]): ?>selected="selected"<?endif;?>><?=$campo['campo'];?></option>

			<? endforeach; ?>

			</select>
		</div>
		<div class="input">

			<label>Giorno</label>

			<select name="data[Gironi][<?=$girone['Half']['GironeCampionato'];?>][Giorno][]">

			<? foreach ($giorni as $key => $giorno): ?>

				<option value="<?=$key;?>"  <? if ($key == $iscrizioni[$girone['Half']['GironeCampionato']]['Giorno'][$j]): ?>selected="selected"<?endif;?>><?=$giorno;?></option>

			<? endforeach; ?>

			</select>
		</div>

		<div class="input">

			<label>Orario</label>

			<select name="data[Gironi][<?=$girone['Half']['GironeCampionato'];?>][Orario][]">

			<? foreach ($orari as $key => $orario): ?>

				<option value="<?=$orario;?>" <? if ($orario == $iscrizioni[$girone['Half']['GironeCampionato']]['Orario'][$j]): ?>selected="selected"<?endif;?>><?=$orario;?></option>

			<? endforeach; ?>

			</select>


&nbsp; &nbsp; &nbsp; &nbsp; <a class="index-sub-delete" href="javascript:;">
												<img width="16" height="16" alt="cancella" src="/img/timmyshare/icon_delete.png">
</a>
		</div>

	<div class="clear"></div>

	</div>


	<? endfor; ?>

	<? endif; ?>

	</div>




</div>




<? endforeach; ?>


	<script type="text/javascript">



			function addSkeleton(index) {


				var casella = $("<div />").addClass('casella-skeleton').html($(".casella-skeleton[data-index=" + index + "]").html()).css('padding-bottom','20px').css('border-bottom','1px solid #ccc');
				
				$(".tab-page[data-index=" + index + "] .caselle").append(casella);

			}
			$(document).ready(function() {


					$(".tab-page").each(function(index) {
					curvalue = parseInt($(this).attr('data-curvalue'));
					if (curvalue == 0) {

						curvalue = $(".SubscriptionIscrizioni").val();

						$(this).attr('data-curvalue',curvalue);
						for (var i = 0; i < curvalue; i++) {

							addSkeleton(index);

						}

					}
					
					});

			});
			$(".index-sub-delete").live('click',function() {


				if (confirm("Eliminare l'orario selezionato?")) {

					var page = $(this).closest('.tab-page');

					$(this).closest('.casella-skeleton').remove();

		
					console.log(page);

					page.find('.SubscriptionIscrizioni option').attr('selected','');
					page.find('.SubscriptionIscrizioni option[value=' + page.find('.casella-skeleton:visible').length + ']').attr('selected','selected');

				}

			});
			$(".SubscriptionIscrizioni").live('change',function() {

				var index = parseInt($(this).closest('.tab-page').attr('data-index'));
				var curvalue = parseInt($(this).closest('.tab-page').attr('data-curvalue'));
				var value = parseInt($(this).val());
				curvalue = parseInt(curvalue);
				if (value > curvalue) {

					var offset = value-curvalue;

					console.log(offset);

					for (var i = 0; i < offset; i++) {
					
						console.log('adding ' + i);
						addSkeleton(index);
					}
					$(this).closest('.tab-page').attr('data-curvalue',value);
				}

				if (value < curvalue) {



					var offset = curvalue-value;

					for (var i = curvalue; i > value; i--) {
					
						console.log('substracting ' + i + ' removing:' + (i-1));
					

						$(".tab-page[data-index=" + index + "] .caselle .casella-skeleton:eq(" + (i-1) + ")").remove();
					}

					$(this).closest('.tab-page').attr('data-curvalue',value);
				}


			});

	</script>

</form>


	<? foreach ($gironi as $i => $girone): ?>

	<div class="casella-skeleton clear" data-index="<?=$i;?>" style="border-bottom: 1px solid #CCC; padding-bottom: 10px; padding-top: 10px; display: none;">

		<div class="input">

			<label>Campo</label>

			<select name="data[Gironi][<?=$girone['Half']['GironeCampionato'];?>][Campo][]">

			<? foreach ($campi as $campo): ?>

				<option value="<?=$campo['id'];?>"><?=$campo['campo'];?></option>

			<? endforeach; ?>

			</select>
		</div>
		<div class="input">

			<label>Giorno</label>

			<select name="data[Gironi][<?=$girone['Half']['GironeCampionato'];?>][Giorno][]">

			<? foreach ($giorni as $key => $giorno): ?>

				<option value="<?=$key;?>"><?=$giorno;?></option>

			<? endforeach; ?>

			</select>
		</div>

		<div class="input">

			<label>Orario</label>

			<select name="data[Gironi][<?=$girone['Half']['GironeCampionato'];?>][Orario][]">

			<? foreach ($orari as $key => $orario): ?>

				<option value="<?=$orario;?>"><?=$orario;?></option>

			<? endforeach; ?>

			</select>

&nbsp; &nbsp; &nbsp; &nbsp; <a class="index-sub-delete" href="javascript:;">
												<img width="16" height="16" alt="cancella" src="/img/timmyshare/icon_delete.png">
</a>

		</div>

	<div class="clear"></div>

	</div>

	<? endforeach; ?>