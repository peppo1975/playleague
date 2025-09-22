<? if(count($data)): ?>			
	<div class="table-container container-table-profile">

		<div id="results-box">
				
			<table class="table table-matches table-bordered table-striped table-condensed">	

				<thead class="table-header">
					<th>Squadra</th>
				</thead>

					<? foreach($data as $squadra): ?>
					<?php if(!empty($squadra['Yearbook']['NomeSquadra'])): ?>
						<tr>
							<td>
								<a href="/squadre/<?=$squadra['Yearbook']['Squadra'];?>/1/<?=strtolower(Inflector::Slug($squadra['Yearbook']['NomeSquadra'],'-'));?>" title="Modifica squadra <?=$squadra['Yearbook']['NomeSquadra'];?>">
									<?=$squadra['Yearbook']['NomeSquadra'];?>
								</a>
							</td>	
						</tr>
					<?php endif; ?>
					
					<? endforeach; ?>
			</table>
		</div><!-- close #results-box -->
	</div><!-- close table-container -->
<? else: ?>
	<div class="alert alert-warning">
		Non ci sono squadre da amministrare.
	</div>
<? endif; ?>				