<div class="table-container booking-table-container table-responsive">
	<table class="table-matches table-border table-striped table-condensed table">
	<?php foreach($tornei as $r): ?>
		<tr>
			<td><a href="/impianti/torneo/<?=$campoId?>/<?=$r['Campionato']?>"><?=$r['Nome']?></a></td>
		</tr>
	<?php endforeach; ?>
	</table>
</div>