<? 
	$data['transactions'] = $transactions;
	$data['shopperInfo'] = $shopperInfo;

	//var_dump($data);
?>

<style type="text/css">
.table-hidden {
	background-color: transparent !important;
}

.table-hidden td {
	border: 0px !important;
}
</style>

<? if($oelkaelder): ?>
	<div class="pull-right">
		<ul class="nav nav-pills pull-right">
		  <li class="active"><a href="<?= base_url('nyintern/oelkaelder/overview') ?>">Overblik</a></li>
		  <li><a href="<?= base_url('nyintern/oelkaelder/allsales') ?>">Salgsoverblik</a></li>
		  <li><a href="<?= base_url('nyintern/oelkaelder/admin') ?>">Administrer</a></li>
		  <li><a href="<?= base_url('nyintern/oelkaelder/assortment') ?>">Sortiment</a></li>
		</ul>
	</div>
<? endif; ?>

<h1 class="page-header">
	Ølkælder overblik <?if(!empty($otherPerson)):?><small><?=$otherPerson['0']->firstName?> <?=$otherPerson['0']->lastName?></small><?endif;?>
</h1>

<? if($shopperInfo == false): ?> <!-- the alumnum is not registered as shopper -->
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-warning">
			<div class="panel-heading"><div class="panel-title">Du er ikke oprettet i ølkælderen!</div></div>
			<div class="panel-body">
				Dette kan blandt andet skyldes at du endnu ikke har betalt depositum. Kontakt ølkælderen for at høre om mulighederne for at blive oprettet i systemet. Du finder medlemmerne af den nuværende ølkælder på alumnelisten.
			</div>
		</div>
	</div>
</div>

<? else: ?> <!-- $shopperInfo == false (the alumnum is not registered as shopper) -->
<div class="row">
	<div class="col-lg-4 col-lg-push-8">
		<div class="panel <? if($shopperInfo->saldo < 0): ?>panel-warning<? else: ?>panel-success<? endif;?>"  
			 style="margin: 35px 0px 0px 0px;">
			<div class="panel-heading" style="text-align: right;">
				<h2><?=orensToPriceStr($shopperInfo->saldo)?> kr.</h2>
			</div>
			<div class="panel-footer announcement-bottom">
				<div class="row">
					<div class="col-xs-12">Saldo</div>
				</div>
			</div>
		</div>

		<div class="panel panel-default" style="margin-top: 30px;">
			<div class="panel-body">
				Indbetaling til ølkælderen foregår på reg.nr. 7320 konto nr. 0002075807. Ølkælderen tjekker indbetalinger med jævne mellemrum, så der kan gå flere dage før indbetalingen kan ses i din oversigt. Sørg for at indbetale tilstrækkeligt til at holde dig i positiv saldo.
			</div>
			<div class="panel-footer">Indbetaling </div>
		</div>

		
		<div class="panel panel-default" style="margin-top: 30px;">
			<table class="table">
				<thead>
					<tr>
						<td><b>Varetype</b></td>
						<td><b>Pris</b></td>
					</tr>
				</thead>
				<tbody>
				    <? if(count($overview) == 0): ?>
				    	<td>Ingen køb</td>
				    	<td> </td>
					<? endif; ?>

					<? foreach($overview as $product): ?>
					<tr>
						<td><?=$product->name?></td>
						<td><?=($product->amount / 100)?> kr</td>
					</tr>
					<? endforeach; ?>
				</tbody>
			</table>
			<div class="panel-footer">Indkøbsoversigt for 
				<form method="post" style="display: inline;"><select onchange="this.form.submit()" name="overviewMonth">
					<?
						$monthNames = array("januar", "februar", "marts", "april", "maj", "juni", "juli", "august", "september", "oktober", "november", "december");
						$months = array();
						$values = array();
						$endYear = 0 + date("Y");
						for ($year = 2016; $year <= $endYear; $year++) {
							if ($year == 2016) {
								$startMonth = 8;
							} else {
								$startMonth = 0;
							}
							if ($year == $endYear) {
								$endMonth = 0 + date("n");
							} else {
								$endMonth = 12;
							}

							for ($month = $startMonth; $month < $endMonth; $month++) {
								$months[] = "$monthNames[$month] $year";
								$values[] = "$month:$year";
							}
						}

						for ($i = count($months) - 1; $i >= 0; $i--) {
					?>
  						<option value="<?=$values[$i]?>"
  						<? if ($values[$i] == $overviewMonth): ?>
  						selected
  					<? endif; ?>
  						><?=$months[$i]?></option>
  					<?
  						}
  					?>
				</select></form>
			</div>
		</div>
	</div>

	<div class="col-lg-8 col-lg-pull-4">
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="header">Handler</th>
				</tr>
			</thead>
			<tbody>
				<? if(count($transactions) == 0): ?>
					<tr><td></td><td><i>Der er endnu ikke foretaget nogle handler.</i></td></tr>
				<? endif; ?>

				<? 
					for ($i = $startItem; $i < min($endItem, count($transactions)); $i++) {
						$transaction = $transactions[$i]; ?>
					<? if ($transaction->type == "purchase"): ?>
						<tr><td>
							<table class="table table-condensed table-hidden" style="margin-bottom: 0px;">
								<tr>
									<td width="80%">
										<? if($oelkaelder): ?>
											<a href="<?= base_url("nyintern/oelkaelder/deleteTransaction/$transaction->transactionId/$currentId") ?>"><i class="fa fa-trash-o fa-1x"></i></a>
										<? endif; ?>
										<em><small>
											<?=$transaction->time?>
											<?
												$started = false;
												foreach ($transaction->alumni as $alumnum) {
													if ($alumnum->alumnumId != $alumneId) {
														echo ($started ? ', ' : " med ");
														echo $alumnum->name;

														$started = true;
													}
												}

											?>		
										</small></em>
									</td>
									<td width="20%"><strong>-<?=orensToPriceStr($transaction->price / count($transaction->alumni))?> kr</strong></td>
								</tr>

								<? foreach($transaction->items as $item): ?>
									<tr>
										<td>
											<? if($item->quantity > 1): ?>
												<small><?=$item->quantity?></small>
												<strong>x</strong>
											<? endif; ?>
											<small><?=$item->name?></small>
										</td>
										<td><small><?=orensToPriceStr($item->price)?> kr</small></td>
									</tr>
								<? endforeach; ?>

							</table>
						</td></tr>
					<? else: ?>
						<tr><td>
							<table class="table table-condensed table-hidden" style="margin-bottom: 0px;">
								<tr>
									<td width="80%">
										<? if($oelkaelder): ?>
											<a href="<?= base_url("nyintern/oelkaelder/deleteDeposit/$transaction->ID") ?>"><i class="fa fa-trash-o fa-1x"></i></a>
										<? endif; ?>
										<em><small><?=$transaction->time?></small></em>
										</td>
									<td width="20%"><strong><?=orensToPriceStr($transaction->amount)?> kr</strong></td>
								</tr>
							</table>
						</td></tr>
					<? endif; ?>
				<? } ?>
			</tbody>
		</table>

		<div style="margin: 0 auto; width: 150px;">
			<? if($prevItem != $startItem): ?>
				<a href="https://gahk.dk/nyintern/oelkaelder/overview/<?=$currentId?>/<?=$prevItem?>">Tilbage</a>
			<? else: ?>
				Tilbage
			<? endif; ?> | 
			<? if($endItem < count($transactions)): ?>
				<a href="https://gahk.dk/nyintern/oelkaelder/overview/<?=$currentId?>/<?=$endItem?>">Frem</a>
			<? else: ?>
				Frem
			<? endif; ?>
		</div>
	</div>
</div>
<? endif; ?> <!-- $shopperInfo == false (the alumnum is not registered as shopper) -->