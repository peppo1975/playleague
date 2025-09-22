<? $model = Inflector::singularize($this->name); ?>

	<script type="text/javascript">

	if (typeof $ != "undefined") {
	
	var id_dest = '';
	var data_dest = '';
	
		$(function() {
			
			$('.formAdd').delegate('.searchAthlete','click, focus', function() {
			
				var url   = $('.searchAthlete').attr('data-url');
				
				id_dest   = $(this).attr('id');
				
				data_dest = $(this).attr('data-dest');
				
				value	  = $(this).attr('data-id');
				
				arbitro   = $(this).attr('data-arbitro');
				
				console.log('id : ' + value);
				
				timmy_load('/admin/athletes/searchAthleteIndex/');
				
				if(value != '' && value != undefined) {
				
					timmyloader('show');
				
					t = setTimeout(function() {
					
						$("#AthleteAtleta").val(value);
						$("#searchButton").trigger('click');
						$("#AthleteAtleta").val('');
					
					},1000);				
					
					timmyloader('hide');
				
				} 
				if(arbitro == 1 && arbitro != undefined) {
				
					t = setTimeout(function() {
					
						$("#AthleteArbitroSi").attr('checked',true);
					
					},1500);				
				
				}
				
					t = setTimeout(function() {
					
						$("#AthleteCognome").focus();
						$("#deleteSelection").attr('data-id',data_dest).attr('data-search',id_dest);
					
					},2000);	
			
			});	
			
			$('#deleteSelection').live('click', function(){
				
				if($(this).attr('data-id') != undefined && $(this).attr('data-search') != undefined) {
					
				var input 		 = $(this).attr('data-id');
				var input_search = $(this).attr('data-search');
				
				$("#" + input).val('');
				$("#" + input_search).val('');
				$("#" + input_search).attr('data-id','');
				
				timmy_close();
									
					
				}
				
				
			});	
			
			$(".athlete_select").live("click", function () {
			
				var id_athlete = $(this).closest('tr').attr('data-id');
				
				var cognome = $(this).closest('tr').find('td[cognome]').html();
				var nome = $(this).closest('tr').find('td[nome]').html();
				var anagrafica = cognome + ' ' + nome;
				
				$("#" + data_dest).val(id_athlete);
				$("#" + id_dest).val(anagrafica);
				$("#" + id_dest).attr('data-id',id_athlete);
				
				$("#" + data_dest).trigger('change');
				
				timmy_close();
			
			});
			
			$('.athlete_select_edit').live('click', function(){
			
				var id_athlete = $(this).closest('tr').attr('data-id');
				
				$.get('/admin/athletes/ajaxAthleteSearch/' + id_athlete, function(data){
				
					for(i in data.athlete.Athlete) {
					
						$('.formSearch').find('*[name="data[Athlete][' + i + ']"]').val(data.athlete.Athlete[i]);
					
					}
					
					//Sesso
					$('#AthleteSessoMaschio').val('Maschio');
					$('#AthleteSessoFemmina').val('Femmina');
					$('#AthleteSesso' + data.athlete.Athlete['Sesso']).attr('checked',true);
					//Responsabile
					$('#AthleteResponsabile' + data.athlete.Athlete['Responsabile']).attr('checked',true);
					//Sportivo
					$('#AthleteSportivo' + data.athlete.Athlete['Sportivo']).attr('checked',true);
					//Arbitro
					$('#AthleteArbitro' + data.athlete.Athlete['Arbitro']).attr('checked',true);

					$('#createAthlete').val('modifica').text('modifica');
					
				},'json');
			
			});
			
			$(".formSearch input[type='button']").live('click', function(e, athlete) {
			
				var data = $('.formSearch').serialize();
				
				if(athlete != undefined) { $('.formSearch').resetForm(); $("#AthleteAtleta").val(athlete); }
								
				timmyloader('show');
				
				$.post('/admin/athletes/searchAthlete', data, function(ret) {
				
				timmyloader('hide');
				
					$("#AthleteAtleta").val('');
					$('#createAthlete').val('inserisci').text('inserisci');
					$('.formSearch').resetForm();
				
				$('.div_append').html('');
				
					if(ret.length == 0) return false;
				
					$('.div_append').append(
					
						'<table id="table_append" class=\"form_table form_table_full\">' + 
						'<tr>' +
							'<th>Seleziona</th>' +
							'<th>Cognome</th>' +
							'<th>Nome</th>' +
							'<th>Indirizzo</th>' +
							'<th>Cap</th>' +
							'<th>Provincia</th>' +
							'<th>Telefono</th>' +
							'<th>Cellulare</th>' +
							'<th>Email</th>' +
							'<th>Luogo di nascita</th>' +
							'<th>Data di nascita</th>' +
							'<th>Sesso</th>' +
							'<th>Responsabile</th>' +
							'<th>Arbitro</th>' +
							'<th>Sportivo</th>' +
						'</tr>' +
						'</table>'
					);
					
					$('.div_append').height(300).css('overflow','auto');
					$('#timmybox_container').css('margin-top',100);
					$('#timmybox_container').css('margin-left',0);
					
					for(i=0; i<ret.length; i++) {
					
						if(ret[i].Athlete.Indirizzo == null) ret[i].Athlete.Indirizzo = '';
						if(ret[i].Athlete.Cap == null) ret[i].Athlete.Cap = '';
						if(ret[i].Athlete.Localita == null) ret[i].Athlete.Localita = '';
						if(ret[i].Athlete.Provincia == null) ret[i].Athlete.Provincia = '';
						if(ret[i].Athlete.Telefono == null) ret[i].Athlete.Telefono = '';
						if(ret[i].Athlete.Cellulare == null) ret[i].Athlete.Cellulare = '';
						if(ret[i].Athlete.Lavoro == null) ret[i].Athlete.Lavoro = '';
						if(ret[i].Athlete.Email == null) ret[i].Athlete.Email = '';
						if(ret[i].Athlete.Fax == null) ret[i].Athlete.Fax = '';
						if(ret[i].Athlete.LuogoNascita == null) ret[i].Athlete.LuogoNascita = '';
						if(ret[i].Athlete.DataNascita_it == null) ret[i].Athlete.DataNascita_it = '';
						if(ret[i].Athlete.Sesso == null) ret[i].Athlete.Sesso = '';
						if(ret[i].Athlete.TipoDocumento == null) ret[i].Athlete.TipoDocumento = '';
						if(ret[i].Athlete.NumeroDocumento == null) ret[i].Athlete.NumeroDocumento = '';
						if(ret[i].Athlete.ScadenzaDocumento == null) ret[i].Athlete.ScadenzaDocumento = '';
						if(ret[i].Athlete.Responsabile == null) ret[i].Athlete.Responsabile = '';
						if(ret[i].Athlete.Arbitro == null) ret[i].Athlete.Arbitro = '';
						if(ret[i].Athlete.Sportivo == null) ret[i].Athlete.Sportivo = '';
					
						$("#table_append").append(
						
						'<tr data-id="' + ret[i].Athlete.Atleta + '">' +
							'<td><a href="javascript:;" class="athlete_select">Seleziona</a> / <a href="javascript:;" class="athlete_select_edit">Modifica</a></td>' +
							'<td cognome="">' + ret[i].Athlete.Cognome + '</td>' +
							'<td nome="">' + ret[i].Athlete.Nome + '</td>' +
							'<td>' + ret[i].Athlete.Indirizzo + '</td>' +
							'<td>' + ret[i].Athlete.Cap + '</td>' +
							'<td>' + ret[i].Athlete.Provincia + '</td>' +
							'<td>' + ret[i].Athlete.Telefono + '</td>' +
							'<td>' + ret[i].Athlete.Cellulare + '</td>' +
							'<td>' + ret[i].Athlete.Email + '</td>' +
							'<td>' + ret[i].Athlete.LuogoNascita + '</td>' +
							'<td>' + ret[i].Athlete.DataNascita_it + '</td>' +
							'<td>' + ret[i].Athlete.Sesso + '</td>' +
							'<td>' + ret[i].Athlete.Responsabile + '</td>' +
							'<td>' + ret[i].Athlete.Arbitro + '</td>' +
							'<td>' + ret[i].Athlete.Sportivo + '</td>' +
						'</tr>'
						
						);
							

					}
					
				}, 'json');
			
			});
					
		});
		
	}
		
	</script>
