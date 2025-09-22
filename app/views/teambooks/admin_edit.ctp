
	<?=$this->element("/backend/edit_scripts");?>
	<?=$this->element("/backend/tab_scripts");?>
	
	<script type="text/javascript">
	if (typeof $ != "undefined") {
		$('.tab-selector').live('click', function(){
		
			var child = $(this).find('li.selected').children('a').html();
			
			if(child == 'Annuario') {
				
				$('.tab-menu').hide();
			
			} else $('.tab-menu').show();
		
		});
	}
	</script>

	<?=$this->Form->create('Teambook', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica annuario squadra: <span><?=$this->data['Teambook']['SquadraSearch'];?></span></h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('modifica',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<input type="hidden" name="modded" value="false" />
	
	</div><!-- close form_header -->
	
	<div class="tab-container">
	
		<ul class="tab-selector">
		
			<li data-index="1" class="selected"><a href="javascript:;">Annuario</a></li>
			<li data-index="2"><a href="javascript:;">Riepilogo</a></li>
			
		</ul>
		<ul class="tab-menu">
			<li>
				<a href="/admin/teambooks/pdf" rel="timmytip" title="Stampa">
					<img src="/img/timmyshare/icon_print.png"/>
				</a>
			</li>
		</ul>
		
		<div class="tab-page tab-selected" data-index="1">
	
			<?=$this->Form->input('AnnuarioSquadra');?>
			
			
			<?=$this->Form->input('SquadraSearch',array('label' => 'Squadra','class' => 'autoComplete','data-url' => '/admin/teambooks/searchSquadra','data-dest' => 'TeambookSquadra'));?>
			<?=$this->Form->input('Squadra',array('type' => 'hidden'));?>
			
			<?
			$options = array();
			foreach($AnniSportivi as $AnnoSportivo) {
			  $options[$AnnoSportivo['AnniSportivi']['AnnoSportivo']] = $AnnoSportivo['AnniSportivi']['AnnoSportivo'];
			 }
			?>
			
			<div class="clear"></div>
			
			
			<?=$this->Form->input('AnnoSportivo', array('type'=>'select', 'default'=>'1', 'options' => $options));?>
			
			<div class="clear"></div>
			
			<?=$this->Form->input('DepositoCauzionale');?>
			
			<div class="clear"></div>
			
			<?=$this->Form->input('Note');?>

			<div class="clear"></div>	
			
		</div>
		
		<div class="tab-page" data-index="2">
					
			<?=$this->Form->input('contabile', array('label' => 'Contabile stagione', 'disabled' => 'disabled', 'readonly' => 'readonly', 'value' => $this->data['Teambook']['AnnoSportivo']));?> 
			
			<?=$this->Form->input('aggiornamento', array('label' => 'Aggiornato al', 'disabled' => 'disabled', 'readonly' => 'readonly', 'value' => date('d/m/Y')));?> 
			
			<div class="clear"></div>
			
			<?=$this->Form->input('RiepilogoSquadra', array('label' => 'Squadra', 'disabled' => 'disabled', 'readonly' => 'readonly', 'value' => $this->data['Teambook']['SquadraSearch']));?> 
			
			<div class="clear"></div>
						
			<?=$this->Form->input('RiepilogoDepositoCauzionale', array('label' => 'Deposito cauzionale', 'disabled' => 'disabled', 'readonly' => 'readonly', 'value' => $this->data['Teambook']['DepositoCauzionale']));?> 
			
			<div class="clear"></div>
			
			<?=$this->Form->input('RiepilogoDepositoCauzionale', array('label' => 'Debito', 'disabled' => 'disabled', 'readonly' => 'readonly', 'value' => $tot_debito));?> 
			
			<div class="clear"></div>
			
			<? $saldo = $tot_debito - $this->data['Teambook']['DepositoCauzionale']; ?>
			
			<?=$this->Form->input('RiepilogoDepositoCauzionale', array('label' => 'Saldo', 'disabled' => 'disabled', 'readonly' => 'readonly', 'value' => $saldo));?> 
			
			<div class="clear"></div>
			
			<? $dati_pdf = array(
			
				'Anno' => $this->data['Teambook']['AnnoSportivo'],
				'Data' => date('d/m/Y'),
				'Squadra' => $this->data['Teambook']['SquadraSearch'],
				'DepositoCauzionale' => $this->data['Teambook']['DepositoCauzionale'],
				'Debito' => $tot_debito,
				'Saldo' => $saldo,
			
			); ?>
			
			<?$this->requestAction('/admin/teambooks/sessionFromView/dati', array('arr' => $dati_pdf));?>
			
			<h3>Disciplinari</h3>
			
			<? ob_start(); ?>
						
			<table class="form_table form_table_full">
			
				<tr>
					<th>Data</th>
					<th>Sanzione</th>
					<th>Manifestazione</th>
					<th>Giornata</th>
					<th>Punti</th>
					<th>Descrizione</th>
				</tr>
				
				<?$disciplinari_pdf = array();?>
			
				<? foreach($disciplinari as $disciplinare): ?>
				
					<? $calendario 			      = $this->requestAction('/admin/matches/searchCalendarioById/' . $disciplinare['Disciplinari']['Calendario']); ?>					
					<? $calendario_giornata       = $calendario['Match']['Giornata']; ?>					
					<? $calendario_partita 	      = $calendario['Match']['Partita']; ?>
					<? $calendario_data 		  = $calendario['Match']['Data_it']; ?>
					<? $calendario_manifestazione = $calendario['Campionati']['Nome'];?>
									
					<tr>
						<td><?=$calendario_data;?></td>
						<td><?=$disciplinare['Disciplinari']['Sanzione'];?></td>
						<td><?=$calendario_manifestazione;?></td>
						<td><?=$calendario_giornata;?></td>
						<td><?=$disciplinare['Disciplinari']['Punti'];?></td>
						<td><?=$disciplinare['Disciplinari']['Descrizione'];?></td>
					</tr>
					
					<?$disciplinari_pdf[] = array(
					
						$calendario_data,$disciplinare['Disciplinari']['Sanzione'],$calendario_manifestazione,$calendario_giornata,$disciplinare['Disciplinari']['Punti'],$disciplinare['Disciplinari']['Descrizione']
					
					);?>
				
				<? endforeach; ?>
				
				<?$this->requestAction('/admin/teambooks/sessionFromView/disciplinari', array('arr' => $disciplinari_pdf));?>
			
			</table>
			
			<? $disciplinari_export = ob_get_clean(); ?>
			
			<?=$disciplinari_export;?>
						
			<?$this->requestAction('/admin/teambooks/sessionFromView/disciplinari_prova', array('arr' => $disciplinari_export));?>
		
			<h3>Causali</h3>
			
			<?ob_start();?>
						
			<table class="form_table form_table_full">
			
				<tr>
					<th>Data</th>
					<th>Sanzione</th>
					<th>Manifestazione</th>
					<th>Giornata</th>
					<th>Punti disciplina</th>
					<th>Descrizione</th>
				</tr>
				
				<?$causali_pdf = array();?>
				
				<? foreach($causali as $causale): ?>
				
						<? $calendario 			      = $this->requestAction('/admin/matches/searchCalendarioById/' . $causale['Calendari']['Calendario']); ?>					
						<? $calendario_giornata       = $calendario['Match']['Giornata']; ?>					
						<? $calendario_partita 	      = $calendario['Match']['Partita']; ?>
						<? $calendario_data 		  = $calendario['Match']['Data_it']; ?>
						<? $calendario_manifestazione = $calendario['Campionati']['Nome'];?>
				
					<tr>
						<td><?=$calendario_data?></td>
						<td><?=$causale['CausaliRisultato']['Sanzione'];?></td>
						<td><?=$calendario_manifestazione;?></td>
						<td><?=$calendario_giornata;?></td>
						<td><?=$causale['CausaliRisultato']['PuntiDisciplina'];?></td>
						<td><?=$causale['CausaliRisultato']['Descrizione'];?></td>
					</tr>
					
					<?$causali_pdf[] = array(
					
						$calendario_data,$causale['CausaliRisultato']['Sanzione'],$calendario_manifestazione,$calendario_giornata,$causale['CausaliRisultato']['PuntiDisciplina'],$causale['CausaliRisultato']['Descrizione']
					
					);?>
				
				<? endforeach; ?>
				
				<?$this->requestAction('/admin/teambooks/sessionFromView/causali', array('arr' => $causali_pdf));?>
			
			</table>
			
			<? $causali_export = ob_get_clean(); ?>
			
			<?=$causali_export;?>
						
			<?$this->requestAction('/admin/teambooks/sessionFromView/causali_prova', array('arr' => $causali_export));?>

			<h3>Tesserati</h3>			
			
			<? ob_start();?>
			
			<table class="form_table form_table_full">
			
				<tr>
					<th>Nome</th>
					<th>Cognome</th>
					<th>Responsabile</th>
					<th>Tesseramento</th>
					<th>Assicurazione</th>
				</tr>
				
				<?$tesserati_pdf = array();?>
				
				<? foreach($tesserati as $tesserato): ?>
				
				<? $annuario_atleta = $this->requestAction('/admin/yearbooks/searchAnnuarioByIdAndAnno/' . $tesserato['Atleti']['Atleta'] . '/' . $this->data['Teambook']['AnnoSportivo']); ?>
		
					<tr>
						<td><?=$tesserato['Atleti']['Nome'];?></td>
						<td><?=$tesserato['Atleti']['Cognome'];?></td>
						<td><?=$tesserato['Atleti']['Responsabile'];?></td>
						<td><?=$annuario_atleta['Yearbook']['Tessera'];?></td>
						<td><?=$annuario_atleta['Yearbook']['NomeAssicurazione'];?></td>
					</tr>
					
					<?$tesserati_pdf[] = array(
					
						$tesserato['Atleti']['Nome'],$tesserato['Atleti']['Nome'],$tesserato['Atleti']['Responsabile'],$annuario_atleta['Yearbook']['Tessera'],$annuario_atleta['Yearbook']['NomeAssicurazione']
					
					);?>
				
				<? endforeach; ?>
				
				<?$this->requestAction('/admin/teambooks/sessionFromView/tesserati', array('arr' => $tesserati_pdf));?>
												
			</table>
			
			<?$tesserati_export = ob_get_clean();?>
			
			<?=$tesserati_export;?>

			<?$this->requestAction('/admin/teambooks/sessionFromView/tesserati_prova', array('arr' => $tesserati_export));?>
							
		</div>
		
	</div>
		
	<?=$this->Form->end();?>
