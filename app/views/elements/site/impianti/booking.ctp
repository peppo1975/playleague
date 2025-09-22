						<? 
						
							$campo  = $booking['campo'];
							$giorni = $booking['giorni'];
							
							$dow['1'] = 'Lunedì';
							$dow['2'] = 'Martedì';
							$dow['3'] = "Mercoledì";
							$dow['4'] = 'Giovedì';
							$dow['5'] = 'Venerdì';
							$dow['6'] = 'Sabato';
							$dow['7'] = 'Domenica';
						
						?>
						
						<script type="text/javascript">
						
							function isValidEmail(str) {
  											var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
  											return pattern.test(str);
							}
						
							$(function() {
							
								$(".js_booking").click(function(){
								
									if(!$('#booking-box').is(':visible'))
									$('#booking-box').slideDown('fast');
									else
									$('#booking-box').slideUp('fast');
									
								
								});
							
								$(".booking-allowed").click(function() {
									
									$(".booking-allowed").not($(this)).removeAttr('data-selected');
									$(this).attr('data-selected','1');
									
									// $(".selected-importo").text($(this).attr('data-importo'));
									// $(".selected-data").text($(this).attr('data-giorno-it'));
									
									// $(".selected-impianto").text('<?=$campo['Campi']['Descrizione'];?> dalle ore ' + $(this).attr('data-ora') + " alle ore " + $(this).attr('data-ora-plus'));
									
									// $(".booking-data").fadeIn(500);
									
									$.post('/campis/saveBookingSession', {
									
										'importo': $(this).attr('data-importo'),
										'data': $(this).attr('data-giorno-it'),
										'campo': '<?=$campo['Campi']['Campo'];?>',
										'impianto': '<?=$campo['Campi']['Descrizione'];?>',
										'ora': 'dalle ore ' + $(this).attr('data-ora') + " alle ore " + $(this).attr('data-ora-plus'),		
										'ora_real': $(this).attr('data-ora'),
										'data_real': $(this).attr('data-giorno')
									
									},function() {
										
										timmy_load('/campis/booking_timmy');
										
									});
									
						
									
								});
								
								$("#bookingData").submit(function() {
									$(this).find('.errors').css('opacity',0);
									var error = 0;
									
									var email = $(this).find('input[name="bookerEmail"]').val();
									
									if (!isValidEmail(email)) error = 2;
									
									$(this).find(".required input").each(function() {
										
										if ($.trim($(this).val()) == '') error = 1;
										
									});
									
									if (error == 2) $(this).find('.errors').html('Inserire un indirizzo e-mail valido!').animate({ 'opacity': 1 },500);
									if (error == 1) $(this).find('.errors').html('Compilare tutti i campi obbligatori!').animate({ 'opacity': 1 },500);
									
									if (error == 0) {
										
										ajaxLoader('show');
										
										$.post(
										
										'/campis/bookingSend',{
											
											'bookerNome': $(this).find('input[name="bookerNome"]').val(),
											'bookerCognome': $(this).find('input[name="bookerCognome"]').val(),
											'bookerEmail': $(this).find('input[name="bookerEmail"]').val(),
											'bookerTelefono': $(this).find('input[name="bookerTelefono"]').val(),
											'Data': $(".booking-allowed[data-selected=1]").attr('data-giorno'),
											'Ora':  $(".booking-allowed[data-selected=1]").attr('data-ora'),
											'campo_id': <?=$campo['Campi']['Campo'];?>,
											'Importo': $(".booking-allowed[data-selected=1]").attr('data-importo')
										
										
										},function(data) {

											$(".booking-data").fadeOut(200,function() {
												
												$(this).html(data).fadeIn(200,function() {
												
															
													ajaxLoader('hide');
													
												});
												
											});
									
										return false;
											
										}
										,'html');
										
									}
									
									return false;
									
								});
								
							});
						
						</script>
					
						<style type="text/css">
						.popover-title,.popover-content {
							font-size: 0.9em;
							text-align: center;
						}
						.booking-allowed {
							/*color: green;*/
							cursor: pointer;
						}

						.booking-disabled {
							/*color: red;*/
							cursor: pointer;
						}

						.popover-content b {
							color: #000;
						}

						</style>
						<script type="text/javascript">
  
  
   var hideAllPopovers = function() {
       $('.booking-disabled').each(function() {

       		if ($(document.activeElement) != $(this))
	            $(this).popover('hide');
        });  
    };

    $(document).ready(function() {

$('body').on('click', function (e) {
    //did not click a popover toggle or popover

    if ($(e.target).hasClass('booking-disabled')) {
        $('[data-toggle="popover"]').not($(e.target)).popover('hide');
        return;
    }

    if ($(e.target).data('toggle') !== 'popover'
        && $(e.target).parents('.popover.in').length === 0) { 
        $('[data-toggle="popover"]').not($(e.target)).popover('hide');
    }
});

    });

						</script>
						<div class="wrapper-box" id="booking-box">
							<div class="wrapper-box-top"></div>
							<div class="wrapper-box-contents" id="filter-box">
								<div class="bookingResult">
								<p>Verifica le ore disponibili per il campo <b><?=$campo['Campi']['Descrizione'];?></b> e seleziona quella che preferisci.</p>	
								<div class="table-container booking-table-container table-responsive">
									<table class="table-matches table-border table-striped table-condensed table">
										<thead class="table-header">
						
											<? $max = 0; ?>
						
											<? foreach ($giorni as $i => $giorno): ?>
											
												<td align="center" style="font-weight: normal;">
												<small>
												<? if ($giorno['DayOfWeek']==2||$giorno['DayOfWeek']==3):?>
												<?=substr($dow[$giorno['DayOfWeek']],0,3);?><br />

											<? else: ?>
												<?=substr($dow[$giorno['DayOfWeek']],0,3);?><br />
											<? endif; ?>
											</small>
												<b><?=date("d/m",strtotime($giorno['Data'] . " 00:00:01"));?></b>
												
												<? if (count($giorno['Orari']) > $max) $max = count($giorno['Orari']); ?>
												
												</th>
											
											<? endforeach; ?>
						
										</thead>
										
										
										<? for ($i=0;$i<$max;$i++): ?>
										
						
											
										<tr class="<?=(!($i%2))? 'alternate' : '';?>">
										
										<? foreach ($giorni as $k => $giorno): ?>
										
											<td align="center">
											
											<? if (isset($giorno['Orari'][$i])): ?>
											
											<? if ($giorno['Orari'][$i]['Occupato'] == 1): ?>
											
											<? if ($giorno['Orari'][$i]['Info'] != ""): ?>
																						
											<? endif; ?>
											<!-- fa fa-soccer-ball-o -->

											<? 
												$infos = explode("<br />",($giorno['Orari'][$i]['Info']));
											?>

											<label onclick="" data-toggle="popover" data-plugin-popover data-plugin-options='{"placement": "top","html": true}' data-title="<?=$infos[0];?>" data-content="<?=$infos[1];?><br><?=$infos[2];?>" class="label label-sm label-danger booking-disabled">

											<? if ($giorno['Orari'][$i]['Info'] != ""): ?>
											<i class="fa fa-soccer-ball-o"></i>
											<? endif; ?>
											<?=substr($giorno['Orari'][$i]['Ora'],0,-3);?></label>
											
											<? if ($giorno['Orari'][$i]['Info'] != ""): ?>
											<!--
											<a data-top="-25px;" href="#" rel="timmytip" title="<?=htmlentities($giorno['Orari'][$i]['Info']);?>" >
												<img src="/img/website/icon_goals.png" width="16" haight=="16" alt="" />
											<a>
											-->
											
											<? endif; ?>
											
											<? if ($giorno['Orari'][$i]['Info'] != ""): ?>
																						
											<? endif; ?>
											
											<? else: ?>




											<? $disabled = 0; ?>
											<? 

											foreach ($campo['CampiDisabled'] as $date) {

													if ($date['giorno'] == $giorno['Data']) $disabled = 1;

											}

											?>

											<? if ($disabled == 0): ?>
											
											
											<label class="label label-sm label-success booking-allowed" data-giorno="<?=$giorno['Data'];?>" data-giorno-it="<?=$giorno['Data_it'];?>"  data-ora="<?=substr($giorno['Orari'][$i]['Ora'],0,-3);?>" data-ora-plus="<?=date("H:i",strtotime("+1 hour",strtotime("2011-01-01 " . $giorno['Orari'][$i]['Ora'])));?>" data-importo="<?=$giorno['Orari'][$i]['Importo'];?>"><?=substr($giorno['Orari'][$i]['Ora'],0,-3);?></label>
											

											<? else: ?>

											<label class="label label-sm label-danger booking-disabled"><?=substr($giorno['Orari'][$i]['Ora'],0,-3);?></label>
											
											<? endif; ?>
											
											<? endif; ?>
											
											<? else: ?>
											
											&nbsp;
											
											<? endif; ?>
											</td>
												
										<? endforeach; ?>
											
										</tr>
										
										
										
										<? endfor; ?>
										
										
									</table>

								</div>
								
									<div class="clear"></div>
								</div>		
							</div>
							<div class="wrapper-box-bottom"></div>
						</div>
