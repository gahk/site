<h1>Antal indkøb mellem <?=$startdate?> og <?=$enddate?></h1>

<table border="1" cellpadding="5">
	<tr>
		<td width="300"><b>Navn</b></td>
		<td width="200"><b>Antal</b></td>
	</tr>
	<? foreach($sales as $sale): ?>
		<tr>
			<td><?=$sale->name?></td>
			<td><?=$sale->quantity?></td>
		</tr>
	<? endforeach; ?>
</table>