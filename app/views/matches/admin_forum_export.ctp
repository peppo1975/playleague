	<!--
	
	AGENDA - http://c5toscana.freeforumzone.leonardo.it/discussione.aspx?idd=6832085
	
	PARTITE IN PROGRAMMA - http://freeforumzone.leonardo.it/discussione.aspx?idd=10031664
	
	RISULATI GIORNATA - http://freeforumzone.leonardo.it/discussione.aspx?idd=10023273
	
	CLASSIFICA - http://freeforumzone.leonardo.it/discussione.aspx?idd=10029519
	
	MARCATORI - http://freeforumzone.leonardo.it/discussione.aspx?idd=9927239
	
	SCHEDINA - http://freeforumzone.leonardo.it/discussione.aspx?idd=10026096
	
	CALENDARIO CON RISULTATI - http://freeforumzone.leonardo.it/discussione.aspx?idd=9894682
	
	ANAGRAFICHE E ROSE - http://freeforumzone.leonardo.it/discussione.aspx?idd=9876225
	
	SITUAZIONE DISCIPLINARE - http://freeforumzone.leonardo.it/discussione.aspx?idd=6786200
	
	NUMERI DEL CAMPIONATO - http://c5toscana.freeforumzone.leonardo.it/discussione.aspx?idd=8953871	
	
	-->	
	
	<div class="tab-container">
	
		<ul class="tab-selector">
	
			<!--
				<li data-index="1" class="selected"><a href="javascript:;">Partite in programma</a></li>
			-->
			<li data-index="2" class="selected"><a href="javascript:;">Risultati giornata</a></li>
			<li data-index="3"><a href="javascript:;">Classifica</a></li>
			<li data-index="4"><a href="javascript:;">Marcatori</a></li>
			<li data-index="5"><a href="javascript:;">Calendario con risultati</a></li>
			<li data-index="6"><a href="javascript:;">Situazione disciplinare</a></li>
			<!--
			<li data-index="7"><a href="javascript:;">Numeri del campionato</a></li>
			-->
			<li data-index="8"><a href="javascript:;">Anagrafica e rose</a></li>
		
		</ul>
		
		<div class="tab-page tab-selected" data-index="2">
		
			<?=$this->element('admin/matches/forum/risultati_giornata');?>
		
		</div>
		
		<div class="tab-page" data-index="3">
		
			<?=$this->element('admin/matches/forum/classifica');?>
		
		</div>		
		
		<div class="tab-page" data-index="4">
		
			<?=$this->element('admin/matches/forum/marcatori');?>
		
		</div>		
		
		<div class="tab-page" data-index="5">
		
			<?=$this->element('admin/matches/forum/calendario_con_risultati');?>
		
		</div>		
		
		<div class="tab-page" data-index="6">
		
			<?=$this->element('admin/matches/forum/situazione_disciplinare');?>
		
		</div>		
		
		<div class="tab-page" data-index="8">
		
			<?=$this->element('admin/matches/forum/anagrafica_e_rose');?>
		
		</div>																
		
	</div>