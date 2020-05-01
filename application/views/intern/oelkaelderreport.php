<h1>Indbetalinger mellem <?=$startdate?> og <?=$enddate?></h1>

<table border="1" cellpadding="5">
	<tr>
		<td width="50"><b>ID</b></td>
		<td width="300"><b>Navn</b></td>
		<td width="200"><b>Samlede indbetalinger</b></td>
		<td width="200"><b>Saldo</b></td>
	</tr>
	<? foreach($shoppers as $shopper): ?>
		<tr>
			<td><?=$shopper->alumnumId?></td>
			<td><?=$shopper->name?></td>
			<td><?=(str_replace('.', ',', $shopper->deposits / 100))?></td>
			<td><?=(str_replace('.', ',', $shopper->saldo / 100))?></td>
		</tr>
	<? endforeach; ?>
</table>