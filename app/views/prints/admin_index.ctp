<?=$this->element("/backend/add_edit_scripts");?>
<script type="text/javascript">
$(function() {
	
	$("#PrintCampionato").unbind('change').live('change', function() {
	
		if($(this).val() == 'default') {
		
			$('.gironi').hide();
			$('.giornate').hide();
			
			return false;
		
		}
		
		var par = $(this).closest('.champ-box');

		if (par.length == 0) par = $(this).closest('.champ-box-2');

		var da = "";
		var a = "";

		if ($("#PrintDataIns").val() != "") {


			da = $("#PrintDataIns").val();
			da = da.replace(/\//g , "-");

		}


		if ($("#PrintDataOuts").val() != "") {


			a = $("#PrintDataOuts").val();
			a = a.replace(/\//g , "-");

		}


		if ($("#PrintDataIn").val() != "") {


			da = $("#PrintDataIn").val();
			da = da.replace(/\//g , "-");

		}


		if ($("#PrintDataOut").val() != "") {


			a = $("#PrintDataOut").val();
			a = a.replace(/\//g , "-");

		}


		$.get("/admin/prints/getDay/" + $(this).val() + "/" + da + "/" + a,function(ret) {
			
			if (ret.find == undefined) 
				var giornate = 0; 
			else
				var giornate = ret.find;

			var selecteds = ret.selecteds;
			
			par.find(".giornate div.input .checkbox").remove();
			
			var selected = "";

			for (var i = 0; i < giornate; i++) {
				
				selected = "";
				for (var j = 0; j < selecteds.length;j++) {


					if ((i+1) == selecteds[j]) selected = "checked='checked'";

				}

				par.find(".giornate div.input").append('<div class="checkbox"><label>' + (i+1) + '</label><input type="checkbox" name="data[Print][Giornate][]" class="checkGiornate" value="' + (i+1) + '" ' + selected + '/></div>');
				
			}
			
			$(".giornate").show();
			
			
		},'json');
		
		$.get("/admin/prints/getHalf/" + $(this).val(),function(ret) {
	
				if (ret != undefined && ret.length > 0) {
					
					par.find(".gironi div.input .checkbox").remove();
					
					for (var i = 0; i < ret.length; i++) {
				
						par.find(".gironi div.input").append('<div class="checkbox"><label>' + ret[i].Half.Descrizione + '</label><input type="checkbox" name="data[Print][Gironi][]" class="checkGironi" value="' + ret[i].Half.GironeCampionato + '" /></div>');
				
					}
					
					par.find(".gironi").show();
					
				}
	
			
		},'json');

	});
	
	$("#PrintAdminIndexForm").height(400).css('overflow-y','scroll');
	
	$("#PrintAdminNotesForm").height(400).css('overflow-y','scroll');
	$("#timmybox_container").css('margin-top',10);

	$(".printButton").click(function() {
		
	/*
		var data = $("#PrintAdminIndexForm").serialize();
		
		var cb = 0;
	*/


		var arr = new Array;




		$(".champ-box-2").each(function() {

		var champ = new Array;
		var giornate = $(this).find(".checkGiornate:checked");
		champ["0"] = $(this).find('#PrintCampionato').val();
		champ["1"] = new Array;
		giornate.each(function() {

			champ["1"].push($(this).val());

		});

		champ["2"] = new Array;

		var gironi = $(this).find('.checkGironi:checked');
		gironi.each(function() {

			champ["2"].push($(this).val());

		});



		if (champ["1"].length > 0 && champ["2"].length > 0)
			arr.push(champ);


		champ["3"]=$("#PrintStampa2").val();
		champ["4"]=$("#PrintExportPdf").val();







		});

		var data = JSON.stringify(arr);


		$(".ret-box-2").html("Generazione PDF In corso, Attendere...");

		$.post('/admin/prints/bullettins2/',{ "data": data },function(ret) {
	
		location.href = ret;


		},'html');

		
	});



	$(".printButton2").click(function(e) {

		e.stopPropagation();
		e.preventDefault();
		
	/*
		var data = $("#PrintAdminIndexForm").serialize();
		
		var cb = 0;
	*/


		var arr = new Array;




		$(".champ-box").each(function() {

		var champ = new Array;
		var giornate = $(this).find(".checkGiornate:checked");
		champ["0"] = $(this).find('#PrintCampionato').val();
		champ["1"] = new Array;
		giornate.each(function() {

			champ["1"].push($(this).val());

		});

		champ["2"] = new Array;

		var gironi = $(this).find('.checkGironi:checked');
		gironi.each(function() {

			champ["2"].push($(this).val());

		});



		if (champ["1"].length > 0 && champ["2"].length > 0)
			arr.push(champ);


		champ["3"]=0;
		champ["4"]=$("#PrintExportPdf").val();







		});

		var data = JSON.stringify(arr);


		$(".ret-box").html("Generazione PDF In corso, Attendere...");

		$.post('/admin/prints/notes/',{ "data": data },function(ret) {
	
		location.href = ret;


		},'html');


		return false;

		
	});
	
	$(".checkGironi, .checkGiornate").live('change',function() {
		
		if ($(".checkGironi:checked").length > 0 && $(".checkGiornate:checked").length > 0) $(".printButton").removeAttr('disabled');
		else $(".printButton").attr('disabled','disabled');
		
	});
	
	$(".checkGironi").live('change',function() {
		
		if ($(".checkGironi:checked").length > 1) {
			
			$(".tip_stampa").show();
			
		} else {
			
			$(".tip_stampa").hide();
			
		}
		
	});
		
	
});

</script>


<?if(isset($this->params['pass'][0]) && $this->params['pass'][0] != ''):?>
<script type="text/javascript">

$(function(){

	$(document).ready(function(){
	
		var tab_selected = '<?=$this->params['pass'][0];?>';
		
		$('.tab-selector').find('li').removeClass('selected');
		$('.tab-selector').find('li[data-index='+tab_selected+']').addClass('selected');
		
		$('.tab-container').find('.tab-page').removeClass('tab-selected');
		$('.tab-container').find('div[data-index='+tab_selected+']').addClass('tab-selected');
	
	});

});

</script>
<?endif;?>
	
	<div class="tab-container">
	
		<ul class="tab-selector">
	
			<? if (isAllowed('Prints','admin_bullettins')): ?><li data-index="1" class="selected"><a href="javascript:;">Stampa bollettino</a></li><? endif; ?>
			<? if (isAllowed('Prints','admin_calendars')): ?><li data-index="2"><a href="javascript:;">Stampa calendario</a></li><? endif; ?>
			<? if (isAllowed('Prints','admin_single_lda')): ?><li data-index="3"><a href="javascript:;">Stampa LDA singolo</a></li><? endif; ?>
			<? if (isAllowed('Prints','admin_ldaMounth')): ?><li data-index="6"><a href="javascript:;">Stampa LDA mensile</a></li><? endif; ?>
			<? if (isAllowed('Prints','admin_general_lda')): ?><li data-index="4"><a href="javascript:;">Stampa LDA generale</a></li><? endif; ?>
			<? if (isAllowed('Prints','admin_recepit')): ?><li data-index="5"><a href="javascript:;">Stampa ricevute campi</a></li><? endif; ?>
			<? if (isAllowed('Prints','admin_certifications')): ?><li data-index="7"><a href="javascript:;">Stampa certificazioni</a></li><? endif; ?>
			<? if (isAllowed('Prints','admin_notes')): ?><li data-index="8" class=""><a href="javascript:;">Stampa note gara</a></li><? endif; ?>

		</ul>
		
		<? if (isAllowed('Prints','admin_bullettins')): ?>
		<div class="tab-page tab-selected" data-index="1">
			<br />
			<br />
			<a href="/admin/matches/bollettini">Clicca qui per andare alla nuova funzione bollettini</a>
		</div>
		<div class="tab-old tab-oldold"  style="display: none;" data-index="1">
		
				<?=$this->Form->create('Print');?>
				


				<?=$this->Form->input('DataIns', array('type' => 'text', 'label' => 'Dal', 'class' => 'datePicker', 'div' => false));?>
				<?=$this->Form->input('DataOuts', array('type' => 'text', 'label' => 'Al', 'class' => 'datePicker', 'div' => false));?>
					
				<div class="clear"></div>	
				<br />
				<input type="button" id="searchChamp" value="Trova campionati" />
				<br />
				<div class="clear"></div>	
				

				<script type="text/javascript">

					$("#searchChamp").unbind('click').live('click',function() {

						var datain = $("#PrintDataIns").val();
						var dataout = $("#PrintDataOuts").val();
						$(".ret-box-2").html('');
						$.post('/admin/prints/searchgiornate',{ "datain": datain, "dataout": dataout },function(ret) {

									var cbox = $(".champ-box-2").clone();


							for (var i = 0; i < ret.length; i++) {




									var tmp = cbox.clone().show();


									tmp.find('input,select').each(function() {


											var name = $(this).attr('name');

											name = name.replace('[x]','[' + i + ']');

											$(this).attr('name',name);

									});

									tmp.find('#PrintCampionato').val(ret[i]).show().trigger('change');
/*
									var me = tmp.find('#PrintCampionato');

									me.attr('data-index',i);

												if($(me).val() == 'default') {
												
													$('.gironi').hide();
													$('.giornate').hide();
													
													return false;
												
												}
												
												var par = $(me).closest('.champ-box');

												if (par.length == 0) par = $(me).closest('.champ-box-2');

												$.get("/admin/prints/getDay/" + $(me).val(),function(ret) {
													
													var mindex = $(me).attr('data-index');

													if (ret.find == undefined) 
														var giornate = 0; 
													else
														var giornate = ret.find;
													
													par.find(".giornate div.input .checkbox").remove();
													
													for (var j = 0; j < giornate; j++) {
														
														par.find(".giornate div.input").append('<div class="checkbox"><label>' + (j+1) + '</label><input type="checkbox" name="data[Print][Giornate][]" class="checkGiornate" value="' + (j+1) + '"/></div>');
														
													}
													
													$(".giornate").show();
													
													
												},'json');
												
												$.get("/admin/prints/getHalf/" + $(me).val(),function(ret) {
														
														var mindex = $(me).attr('data-index');

														if (ret != undefined && ret.length > 0) {
															
															par.find(".gironi div.input .checkbox").remove();
															
															for (var j = 0; j < ret.length; j++) {
														
																par.find(".gironi div.input").append('<div class="checkbox"><label>' + ret[j].Half.Descrizione + '</label><input type="checkbox" name="data[Print][Gironi][]" class="checkGironi" value="' + ret[j].Half.GironeCampionato + '" /></div>');
														
															}
															
															par.find(".gironi").show();
															
														}
											
													
												},'json');
*/
									tmp.appendTo($(".ret-box-2"));


							}


						},'json');

					});

				</script>

				<div class="ret-box-2">

				</div>

				<div class="champ-box-2" style="display: none;">

				<?=$this->Form->input('Campionato', array('type' => 'select', 'name'=>'data[Print][Campionato]', 'label' => 'Campionato', 'options' => $campionati, 'div' => false));?>

				<div class="clear"></div>

				<div class="giornate" style="display: none;">

				<?=$this->Form->input('Giornate', array(
				'type' => 'select',
				'label' => 'Giornate',
				'multiple' => 'checkbox',
				'name'=>'data[Print][Giornate]',
				'options' => array(
				)
				));?>
				
				<div class="clear"></div>
				<a style="padding-left: 10px; color: #000;" onclick="$(this).parent('.giornate').find('.checkGiornate').attr('checked',true); if ($('.checkGironi:checked').length > 0 && $('.checkGiornate:checked').length > 0) $('.printButton').removeAttr('disabled'); else $('.printButton').attr('disabled','disabled');" href="javascript:;" class="select-all-day">seleziona tutte</a>				
				
				</div>
				
				<div class="gironi" style="display: none;">

				<?=$this->Form->input('Girone', array(
				'type' => 'select',
				'label' => 'Gironi',
				'multiple' => 'checkbox',
				'name'=>'data[Print][Girone]',

				'options' => array(
				)
				));?>
				
				<div class="clear"></div>
				<a style="padding-left: 10px; color: #000;" onclick="$(this).parent('.gironi').find('.checkGironi').attr('checked',true); if ($('.checkGironi:checked').length > 0 && $('.checkGiornate:checked').length > 0) $('.printButton').removeAttr('disabled'); else $('.printButton').attr('disabled','disabled'); if ($('.checkGironi:checked').length > 1) { $('.tip_stampa').show(); } else { $('.tip_stampa').hide(); }" href="javascript:;" class="select-all-day">seleziona tutti</a>				
				
				</div>

				<div class="clear"></div>

				</div>

				<div class="tip_stampa" style="display: none;">

				<?=str_replace('fieldset','fieldset style="width: 250px;"',$this->Form->input('Stampa', array(
				'type' => 'radio',
				'label' => 'Modalità di stampa',

				'options' => array(
				
					'1' => '1 girone per pagina',
					'2' => '2 gironi per pagina',
				
				),
				'value' => '1'
				)));?>
				
				</div>

				<div class="clear"></div>

				<div class="tip_export">

				<?=$this->Form->input('Export', array(
				'type' => 'radio',
				'label' => 'Modalità di esportazione',

				'options' => array(
				
					'pdf' => 'PDF',
					'xls' => 'XLS',
				
				),
				'value' => 'pdf'
				));?>
				
				</div>


				<div class="clear"></div>
						
				<?=$this->Form->button('Stampa', array('type' => 'button', 'class' => 'printButton', 'disabled' => 'disabled','div' => true,'label' => ''));?>
						
				<?=$this->Form->end();?>
		
		</div>
		
		<? endif; ?>
		


		<? if (isAllowed('Prints','admin_bullettins')): ?>
		

		<div class="tab-page" data-index="8">

			<a href="javascript:;" title="Stampa note gara selezionate" rel="timmytip" id="print_notes"><img src="/img/timmyshare/icon_print.png" width="20" height="20" alt="" /> Stampa le note gara selezionate</a>
		</div>

		<div class="tab-page" data-index="8888">
		
				<?=$this->Form->create('Print',array('action' => 'admin_notes'));?>
				
				<?=$this->Form->input('DataIn', array('type' => 'text', 'label' => 'Dal', 'class' => 'datePicker', 'div' => false));?>
				<?=$this->Form->input('DataOut', array('type' => 'text', 'label' => 'Al', 'class' => 'datePicker', 'div' => false));?>
					

				<div class="clear"></div>	



				<input type="button" id="searchGiornate" value="Trova giornate" />

				<script type="text/javascript">

					$("#searchGiornate").unbind('click').live('click',function() {

						var datain = $("#PrintDataIn").val();
						var dataout = $("#PrintDataOut").val();
						$.post('/admin/prints/searchgiornate',{ "datain": datain, "dataout": dataout },function(ret) {

									var cbox = $(".champ-box").clone();


							for (var i = 0; i < ret.length; i++) {




									var tmp = cbox.clone().show();

									tmp.find('#PrintCampionato').val(ret[i]).trigger('change').show();

									tmp.appendTo($(".ret-box"));


							}


						},'json');

					});

				</script>

				<div class="ret-box">

				</div>
				<div class="champ-box" style="display: none;">
				<?=$this->Form->input('Campionato', array('type' => 'select', 'label' => 'Campionato', 'options' => $campionati, 'div' => false));?>

				<div class="clear"></div>

				<div class="giornate" style="display: none;">

				<?=$this->Form->input('Giornate', array(
				'type' => 'select',
				'label' => 'Giornate',
				'multiple' => 'checkbox',
				'options' => array(
				)
				));?>
				
				<div class="clear"></div>
				<a style="padding-left: 10px; color: #000;" onclick="$(this).parent('.giornate').find('.checkGiornate').attr('checked',true); if ($('.checkGironi:checked').length > 0 && $('.checkGiornate:checked').length > 0) $('.printButton').removeAttr('disabled'); else $('.printButton').attr('disabled','disabled');" href="javascript:;" class="select-all-day">seleziona tutte</a>				
				
				</div>
				
				<div class="gironi" style="display: none;">

				<?=$this->Form->input('Girone', array(
				'type' => 'select',
				'label' => 'Gironi',
				'multiple' => 'checkbox',
				'options' => array(
				)
				));?>
				
				<div class="clear"></div>
				<a style="padding-left: 10px; color: #000;" onclick="$(this).parent('.gironi').find('.checkGironi').attr('checked',true); if ($('.checkGironi:checked').length > 0 && $('.checkGiornate:checked').length > 0) $('.printButton').removeAttr('disabled'); else $('.printButton').attr('disabled','disabled'); if ($('.checkGironi:checked').length > 1) { $('.tip_stampa').show(); } else { $('.tip_stampa').hide(); }" href="javascript:;" class="select-all-day">seleziona tutti</a>				
				
				</div>

				<div class="clear"></div>

				<div class="tip_stampa" style="display: none;">
				<!--
				<?=str_replace('fieldset','fieldset style="width: 250px;"',$this->Form->input('Stampa', array(
				'type' => 'radio',
				'label' => 'Modalità di stampa',

				'options' => array(
				
					'1' => '1 girone per pagina',
					'2' => '2 gironi per pagina',
				
				),
				'value' => '1'
				)));?>
				-->
				</div>

				<div class="clear"></div>

			

				<div class="clear"></div>
				</div>
						<div class="tip_export">


				<?=$this->Form->input('Export', array(
				'type' => 'radio',
				'label' => 'Modalità di esportazione',

				'options' => array(
				
					'pdf' => 'PDF',
					'xls' => 'XLS',
				
				),
				'value' => 'pdf'
				));?>
				
				</div>	
				<?=$this->Form->button('Stampa', array('type' => 'submit', 'class' => 'printButton2','div' => true,'label' => ''));?>
						
				<?=$this->Form->end();?>
		
		</div>
		
		<? endif; ?>
		

		<? if (isAllowed('Prints','admin_calendars')): ?>
		
		<div class="tab-page" data-index="2">
		
				<?=$this->element("/backend/add_edit_scripts");?>
				<script type="text/javascript">

				$(function() {
					
					$("#calendarPrintCampionato").live('change', function() {
					
						if($(this).val() == 'default') {
						
							$('.gironi').hide();
							$('.giornate').hide();
							
							return false;
						
						}
								
						$.get("/admin/prints/getHalf/" + $(this).val(),function(ret) {
					
								if (ret != undefined && ret.length > 0) {
									
									$(".calendar_gironi div.input .checkbox").remove();
									
									for (var i = 0; i < ret.length; i++) {
								
										$(".calendar_gironi div.input").append('<div class="checkbox"><label>' + ret[i].Half.Descrizione + '</label><input type="checkbox" name="data[calendarPrint][Gironi][]" class="calendar_checkGironi" value="' + ret[i].Half.GironeCampionato + '" /></div>');
								
									}
									
									$(".calendar_gironi").show();
									
								}
					
							
						},'json');

					});
					
					$(".calendarPrintButton").click(function() {
						
						var data = $("#calendarPrintAdminIndexForm").serialize();	
						
						$.post('/admin/prints/calendars/'+$("#calendarPrintCampionato").val(),data,function(ret) {
						
								location.href = '/' + ret.link;
							
						},'json');
						
					});
					
					$(".calendar_checkGironi").live('change',function() {
											
						if ($(".calendar_checkGironi:checked").length > 0) $(".calendarPrintButton").removeAttr('disabled');
						else $(".calendarPrintButton").attr('disabled','disabled');
						
					});			
					
				});

				</script>

					<?=$this->Form->create('calendarPrint');?>
					
					<div class="clear"></div>	
					
					<?=$this->Form->input('Campionato', array('type' => 'select', 'label' => 'Campionato', 'options' => $campionati, 'div' => false));?>

					<div class="clear"></div>
					
					<div class="calendar_gironi" style="display: none;">

						<?=$this->Form->input('Girone', array(
						'type' => 'select',
						'label' => 'Gironi',
						'multiple' => 'checkbox',
						'options' => array(
						)
						));?>
					
					</div>

					<div class="clear"></div>

					<div class="calendar_tip_export">

						<?=$this->Form->input('Export', array(
						'type' => 'radio',
						'label' => 'Modalità di esportazione',

						'options' => array(
						
							'pdf' => 'PDF',
							'xls' => 'XLS',
						
						),
						'value' => 'pdf'
						));?>
					
					</div>

					<div class="clear"></div>
							
					<?=$this->Form->button('Stampa', array('type' => 'button', 'class' => 'calendarPrintButton', 'disabled' => 'disabled','div' => true,'label' => ''));?>
							
					<?=$this->Form->end();?>
		
		</div> 
		
		<? endif; ?>
		
		<? if (isAllowed('Prints','admin_single_lda')): ?>
		
		<div class="tab-page" data-index="3">
		
				<script type="text/javascript">

				$(function() {
					
					$(".singleLdaprintButton").click(function() {
						
						var data = $("#singleLdaPrintAdminIndexForm").serialize();	
						
						$.post('/admin/prints/single_lda/', data,function(ret) {
						
								location.href = '/' + ret.link;
							
						},'json');
						
					});
					
					$("#singleLdaPrintAdminIndexForm :input").live('change',function() {
											
						var vuoto = 1;
						
						$("#singleLdaPrintAdminIndexForm :input.singleLda").each(function(index){												
						
							if($(this).val() == '') vuoto = 0;
						
						});
												
						if(vuoto == 0) $('.singleLdaprintButton').attr('disabled', 'disabled');
						else $('.singleLdaprintButton').attr('disabled', false);
						
					});
							
				});

				</script>

					<?=$this->Form->create('singleLdaPrint');?>
					
					<div class="clear"></div>	
						
					<?=$this->Form->input('DataIn', array('type' => 'text', 'label' => 'Dal', 'class' => 'datePicker singleLda', 'div' => false));?>
					<?=$this->Form->input('DataOut', array('type' => 'text', 'label' => 'Al', 'class' => 'datePicker singleLda', 'div' => false));?>
					
					<div class="clear"></div>
					
					<?=$this->Form->input('NomeAtleta',array('label' => 'Arbitro','class' => 'autoComplete singleLda','data-url' => '/admin/matches/searchArbitro','data-dest' => 'singleLdaPrintAtleta'));?>
					<?=$this->Form->input('Atleta', array('type' => 'hidden', 'class' => 'singleLda'));?> 

					<div class="clear"></div>
							
					<div class="singlelda_tip_export">

						<?=$this->Form->input('Export', array(
						'type' => 'radio',
						'label' => 'Modalità di esportazione',

						'options' => array(
						
							'pdf' => 'PDF',
							'xls' => 'XLS',
						
						),
						'value' => 'pdf',
						));?>
					
					</div>
					
					<div class="clear"></div>
					
					<?=$this->Form->button('Stampa', array('type' => 'button', 'class' => 'singleLdaprintButton', 'disabled' => true, 'div' => true,'label' => ''));?>
							
					<?=$this->Form->end();?>
		
		</div>
		
		<? endif; ?>
		
		<? if (isAllowed('Prints','admin_ldaMounth')): ?>
		
		<div class="tab-page" data-index="4">
		
				<script type="text/javascript">

				$(function() {
					
					$(".generalLdaprintButton").click(function() {
						
						var data = $("#generalLdaPrintAdminIndexForm").serialize();	
												
						$.post('/admin/prints/general_lda/', data,function(ret) {
						
								location.href = '/' + ret.link;
							
						},'json');
						
					});
					
					$("#generalLdaPrintAdminIndexForm :input").live('change',function() {
						
						var vuoto = 1;
						
						$("#generalLdaPrintAdminIndexForm :input.generalLda").each(function(index){
						
							if($(this).val() == '') vuoto = 0;
						
						});
						
						if(vuoto == 0) $('.generalLdaprintButton').attr('disabled', 'disabled');
						else $('.generalLdaprintButton').attr('disabled', false);
						
					});
							
				});

				</script>

					<?=$this->Form->create('generalLdaPrint');?>
					
					<div class="clear"></div>	
						
					<?=$this->Form->input('DataIn', array('type' => 'text', 'label' => 'Dal', 'class' => 'datePicker generalLda', 'div' => false));?>
					<?=$this->Form->input('DataOut', array('type' => 'text', 'label' => 'Al', 'class' => 'datePicker generalLda', 'div' => false));?>
					
					<div class="clear"></div>
								
					<div class="tip_export">

					<?=$this->Form->input('Export', array(
					'type' => 'radio',
					'label' => 'Modalità di esportazione',

					'options' => array(
					
						'pdf' => 'PDF',
						'xls' => 'XLS',
					
					),
					'value' => 'pdf'
					));?>
					
					</div>
					
					<div class="clear"></div>
					
					<?=$this->Form->button('Stampa', array('type' => 'button', 'class' => 'generalLdaprintButton', 'disabled' => true, 'div' => true,'label' => ''));?>
							
					<?=$this->Form->end();?>
			
		</div>
		
		<? endif; ?>
		
		<? if (isAllowed('Prints','admin_recepit')): ?>
		
		<div class="tab-page" data-index="5">
		
					<?=$this->Form->create('generalCampi');?>
					
					<script type="text/javascript">
					
						var match_id = new Array;
					
						$(function() {
							
							$(".index-select-checkbox:checked").each(function() {
								
								match_id.push($(this).val());
								
							});
							
							var total_number = parseInt(match_id.length) * 2;
							
							$(".print-number").text(total_number);
									
							if (match_id.length == 0) {
								
								$(".generalCampiButton").attr('disabled','disabled');
								
							}
							
							
							$("#generalCampiAdminIndexForm").submit(function(e) {
								
								e.preventDefault();
								
								$.post("/admin/prints/recepit",{ "matches": match_id },function(ret) {
									
									if(ret.link != 'pdf_error') {
									
										location.href = ret.link;
									
									} else {
									
										alert('Impossibile stampare, squadre già paganti');
									
									}
									
								},'json');
							
								return false;
								
							}); 
									
							
									
						});
					
					</script>
					
					<div class="input">
					<label><span class="print-number"></span> ricevute verranno stampate</label>
					<?=$this->Form->button('Stampa', array('type' => 'submit', 'class' => 'generalCampiButton', 'div' => false));?>
					</div>
					
					<?=$this->Form->end();?>
		</div>
		
		<? endif; ?>
		
		<? if (isAllowed('Prints','admin_recepit')): ?>
		
		<div class="tab-page" data-index="6">		
		
				<script type="text/javascript">

				$(function() {
					
					$(".ldaMounthPrintButton").click(function() {
						
						var data = $("#ldaMounthAdminIndexForm").serialize();	
												
						$.post('/admin/prints/ldaMounth/', data,function(ret) {
						
								location.href = '/' + ret.link;
							
						},'json');
						
					});
					
					$("#ldaMounthAdminIndexForm :input").live('change',function() {
						
						var vuoto = 1;
						
						$("#ldaMounthAdminIndexForm :input.mounthLda").each(function(index){
						
							if($(this).val() == '') vuoto = 0;
						
						});
						
						if(vuoto == 0) $('.ldaMounthPrintButton').attr('disabled', 'disabled');
						else $('.ldaMounthPrintButton').attr('disabled', false);
						
					});
							
				});

				</script>

					<?=$this->Form->create('ldaMounth');?>
					
					<div class="clear"></div>	
						
					<?=$this->Form->input('DataIn', array('type' => 'text', 'label' => 'Dal', 'class' => 'datePicker mounthLda', 'div' => false));?>
					<?=$this->Form->input('DataOut', array('type' => 'text', 'label' => 'Al', 'class' => 'datePicker mounthLda', 'div' => false));?>
					
					<div class="clear"></div>
								
					<div class="tip_export">

					<?=$this->Form->input('Export', array(
					'type' => 'radio',
					'label' => 'Modalità di esportazione',

					'options' => array(
					
						'pdf' => 'PDF',
						'xls' => 'XLS',
					
					),
					'value' => 'pdf'
					));?>
					
					</div>
					
					<div class="clear"></div>
					
					<?=$this->Form->button('Stampa', array('type' => 'button', 'class' => 'ldaMounthPrintButton', 'disabled' => true, 'div' => true,'label' => ''));?>
							
					<?=$this->Form->end();?>		
		
		</div>
		
		<? endif; ?>
		
		<? if (isAllowed('Prints','admin_certifications')): ?>
		
		<div class="tab-page" data-index="7">
		
			<script type="text/javascript">
			
			$(function(){
				
				$('#CertificationAnno').die('change').live('change', function(){
					
					var me = $(this);
					if(me.val() != '')
					{
						$('.CertificationPrintButton').attr('disabled', false);
					}
					else
					{
						$('.CertificationPrintButton').attr('disabled', true);
					}
					
					$(".CertificationPrintButton").die('click').live('click',function() {
						
						//var data = $("#CertificationAdminIndexForm").serialize();	
												
						//$.post('/admin/prints/certifications/' + $('#CertificationAnno').val(), data,function(ret) {
						
								location.href = '/admin/prints/certifications/' + $('#CertificationAnno').val();
							
						//},'json');
						
					});					
					
				});
				
			});
			
			</script>
		
			<?=$this->Form->create('Certification');?>
		
			<?=$this->Form->input('anno', array('type' => 'select', 'label' => 'Anno sportivo', 'class' => '', 'options' => $anni, 'empty' => true));?>
			
			<div class="clear"></div>
		
			<div class="input submit" style="padding-top: 5px;">
		
			<?=$this->Form->button('Stampa certificazioni', array('type' => 'button', 'class' => 'CertificationPrintButton', 'disabled' => true, 'div' => false,'label' => ''));?>
			
			</div>
							
			<?=$this->Form->end();?>		
		
		</div>
		
		<? endif; ?>		
		
	</div>
