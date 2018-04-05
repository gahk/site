<h1>Indkøb mellem <?=$startdate?> og <?=$enddate?></h1>

<table border="1" cellpadding="5">
	<tr>
		<td width="300"><b>Navn</b></td>
		<td width="200"><b>Beløb</b></td>
	</tr>
	<? foreach($sales as $sale): ?>
		<tr>
			<td><?=$sale->name?></td>
			<td><?=(str_replace('.', ',', $sale->amount / 100))?></td>
		</tr>
	<? endforeach; ?>
</table>