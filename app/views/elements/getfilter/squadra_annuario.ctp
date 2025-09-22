		<div id="results-box">		
		
			<div class="summary-info">
			
			<h3>Dati della squadra</h3>
			
			<div class="summary-data">

	<?=$this->Form->create('Athlete', array('url' => '/gestione/profilo/' . $this->data['Athlete']['Atleta'] . '/' . 'Athlete','type' => 'file', 'id' => 'profile-form',


        'class'         => 'form-horizontal',
        'inputDefaults' => array(
            'format'  => array( 'before', 'between','label',
                                'input', 'error', 'after' ),
            'class' => 'form-control',
            'div'     => array( 'class' => 'form-group' ),
            'label'   => array( 'class' => 'control-label' ),
            'between' => '<div class="col-lg-12">',
            'after'   => '</div>',
            'error'   => array( 'attributes' => array( 'wrap'  => 'span',
                                                       'class' => 'text-danger' ) 

	))));?>

			<?=$this->Form->input('contabile', array('label' => 'Contabile stagione', 'disabled' => 'disabled', 'readonly' => 'readonly', 'value' => $this->data['Teambook']['AnnoSportivo']));?> 
			
			<?=$this->Form->input('aggiornamento', array('label' => 'Aggiornato al', 'disabled' => 'disabled', 'readonly' => 'readonly', 'value' => date('d/m/Y')));?> 
			
			<?=$this->Form->input('RiepilogoSquadra', array('label' => 'Squadra', 'disabled' => 'disabled', 'readonly' => 'readonly', 'value' => $this->data['Teambook']['SquadraSearch']));?> 
						
			<?=$this->Form->input('RiepilogoDepositoCauzionale', array('label' => 'Deposito cauzionale', 'disabled' => 'disabled', 'readonly' => 'readonly', 'value' => '€ ' . $this->data['Teambook']['DepositoCauzionale']));?> 

			<?=$this->Form->input('RiepilogoDepositoCauzionale', array('label' => 'Debito', 'disabled' => 'disabled', 'readonly' => 'readonly', 'value' => '€ ' . $tot_debito));?> 
			
			<? $saldo = $tot_debito - $this->data['Teambook']['DepositoCauzionale']; ?>
			
			<?=$this->Form->input('RiepilogoDepositoCauzionale', array('label' => 'Saldo', 'disabled' => 'disabled', 'readonly' => 'readonly', 'value' => '€ ' . $saldo));?> 
			
<?=$this->Form->end();?>

			</div>
			
			</div>
			
			<div class="clear"></div>
			
			<div id="table-container-annuario">
			
			<? if(count($disciplinari)): ?>
			
			<h3>Disciplinari</h3>
			
			<? ob_start(); ?>
						
			<table class="table-matches table table-condensed table-striped table-bordered">
			
				<thead class="table-header">
					<th>Data</th>
					<th>Sanzione</th>
					<th>Manifestazione</th>
					<th>Giornata</th>
					<th>Punti</th>
					<th>Descrizione</th>
				</thead>
				
				<?$disciplinari_pdf = array();?>
			
				<? foreach($disciplinari as $k => $disciplinare): ?>
				
					<? $calendario 			      = $this->requestAction('/admin/matches/searchCalendarioById/' . $disciplinare['Disciplinari']['Calendario']); ?>					
					<? $calendario_giornata       = $calendario['Match']['Giornata']; ?>					
					<? $calendario_partita 	      = $calendario['Match']['Partita']; ?>
					<? $calendario_data 		  = $calendario['Match']['Data_it']; ?>
					<? $calendario_manifestazione = $calendario['Campionati']['Nome'];?>
									
					<tr class="<? if((($k+1)%2) == 0): ?>alternate<? endif; ?>">
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
			
			</table>
			
			<? $disciplinari_export = ob_get_clean(); ?>
			
			<?=$disciplinari_export;?>
			
			<? endif; ?>
		
			<? if(count($causali)): ?>
		
			<h3>Causali</h3>
			
			<?ob_start();?>
						
			<table class="table-matches table table-condensed table-striped table-bordered">
			
				<thead class="table-header">
					<th>Data</th>
					<th>Sanzione</th>
					<th>Manifestazione</th>
					<th>Giornata</th>
					<th>Punti disciplina</th>
					<th>Descrizione</th>
				</thead>
				
				<?$causali_pdf = array();?>
				
				<? foreach($causali as $k => $causale): ?>
				
						<? $calendario 			      = $this->requestAction('/admin/matches/searchCalendarioById/' . $causale['Calendari']['Calendario']); ?>					
						<? $calendario_giornata       = $calendario['Match']['Giornata']; ?>					
						<? $calendario_partita 	      = $calendario['Match']['Partita']; ?>
						<? $calendario_data 		  = $calendario['Match']['Data_it']; ?>
						<? $calendario_manifestazione = $calendario['Campionati']['Nome'];?>
				
					<tr class="<? if((($k+1)%2) == 0): ?>alternate<? endif; ?>">
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
			
			</table>
			
			<? $causali_export = ob_get_clean(); ?>
			
			<?=$causali_export;?>
			
			<? endif; ?>
			
			<? if(count($tesserati)): ?>

			<h3>Tesserati</h3>			
			
			<? ob_start();?>
			
			<table class="table-matches table table-condensed table-striped table-bordered">
			
				<thead class="table-header">
					<th>Nome</th>
					<th>Cognome</th>
					<th>Responsabile</th>
					<th>Tesseramento</th>
					<th>Assicurazione</th>
				</thead>
				
				<?$tesserati_pdf = array();?>
				
				<? foreach($tesserati as $k => $tesserato): ?>
				
				<? $annuario_atleta = $this->requestAction('/admin/yearbooks/searchAnnuarioByIdAndAnno/' . $tesserato['Atleti']['Atleta'] . '/' . $this->data['Teambook']['AnnoSportivo']); ?>
		
					<tr class="<? if((($k+1)%2) == 0): ?>alternate<? endif; ?>">
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
												
			</table>
			
			<?$tesserati_export = ob_get_clean();?>
			
			<?=$tesserati_export;?>
			
			<? endif; ?>
			
			</div>

			</div>
							