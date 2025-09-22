<table class="form_table form_table_full">

<tr>
	<th>Data invio</th>
	<th>Inviata a</th>
</tr>

</table>

<div style="height:100px; overflow: auto; ">

<table class="form_table form_table_full">

<? foreach($stories as $story): ?>

	<tr>
		<td><?=$story['Spool']['modified_it'];?></td>
		<td><?=$story['Spool']['email'];?></td>
	</tr>

<? endforeach; ?>

</table>

</div>