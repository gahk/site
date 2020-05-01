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
		  <li><a href="<?= base_url('nyintern/oelkaelder/overview') ?>">Overblik</a></li>
		  <li class="active"><a href="<?= base_url('nyintern/oelkaelder/allsales') ?>">Salgsoverblik</a></li>
		  <li><a href="<?= base_url('nyintern/oelkaelder/admin') ?>">Administrer</a></li>
		  <li><a href="<?= base_url('nyintern/oelkaelder/assortment') ?>">Sortiment</a></li>
		</ul>
	</div>
<? endif; ?>

<h1 class="page-header">
	Salgsoverblik
</h1>

<div class="row">
	<div class="col-lg-4 col-lg-push-8">
		<div class="panel panel-default" style="margin-top: 30px;">
			<div class="panel-heading">Søgning</div>
			<div class="panel-body">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="control-label col-sm-6" for="saleAmount">Handler større end:</label>
						<div class="col-sm-6">
							<div class=" input-group">
								<input type="number" step="1" class="form-control" name="saleAmount" id="saleAmount" value="<?=$lowerAmount?>">
								<div class="input-group-addon"> kr</div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-12">
							<div class="pull-right">
								<button type="button" class="btn btn-primary" onclick="location.href = 'https://gahk.dk/nyintern/oelkaelder/allsales/<?=$startItem?>/' + document.querySelector('#saleAmount').value;">Hent</button>
							</div>
						</div>
					</div>
				</form>
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
					for ($i = 0; $i < count($transactions); $i++) {
						$transaction = $transactions[$i]; ?>
					<? if ($transaction->type == "purchase"): ?>
						<tr><td>
							<table class="table table-condensed table-hidden" style="margin-bottom: 0px;">
								<tr>
									<td width="80%">
										<? if($oelkaelder): ?>
											<a href="<?= base_url("nyintern/oelkaelder/deleteTransaction/$transaction->transactionId/0") ?>"><i class="fa fa-trash-o fa-1x"></i></a>
										<? endif; ?>
										<em><small>
											<?=$transaction->time?>
											<?
												$started = false;
												foreach ($transaction->alumni as $alumnum) {
													echo ($started ? ', ' : "");
													echo $alumnum->name;

													$started = true;
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
				<a href="https://gahk.dk/nyintern/oelkaelder/allsales/<?=$prevItem?>/<?=$lowerAmount?>">Tilbage</a>
			<? else: ?>
				Tilbage
			<? endif; ?> | 
			
			<a href="https://gahk.dk/nyintern/oelkaelder/allsales/<?=$endItem?>/<?=$lowerAmount?>">Frem</a>
		</div>
	</div>
</div>