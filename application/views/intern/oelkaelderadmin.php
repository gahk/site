<?
  	//var_dump($shoppers);
	//var_dump($inactiveShoppers);
?>

<div class="pull-right">
	<ul class="nav nav-pills pull-right">
	  <li><a href="<?= base_url('nyintern/oelkaelder/overview') ?>">Overblik</a></li>
	  <li><a href="<?= base_url('nyintern/oelkaelder/allsales') ?>">Salgsoverblik</a></li>
	  <li class="active"><a href="<?= base_url('nyintern/oelkaelder/admin') ?>">Administrer</a></li>
	  <li><a href="<?= base_url('nyintern/oelkaelder/assortment') ?>">Sortiment</a></li>
	</ul>
</div>

<h1 class="page-header">Administrer alumnegæld</h1>

<? if ($depositStr != ""): ?>
<div class="alert alert-success"><?=$depositStr?></div>
<? endif; ?>

<div class="row" style="margin-top: 20px;">
	<form method="post">
		<input type="hidden" name="updateSaldo" value="1" />
		<div class="row"><div class="col-lg-12">
			<table class="table table-hover">
				<thead>
					<tr>
						<th class="header">Navn</th>
						<th class="header">Saldo</th>
						<th class="header" style="width: 130px;">Indbetaling</th>
						<th class="header" style="width: 130px;"></th>
					</tr>
				</thead>
				<tbody>
					<? foreach($shoppers as $shopper): ?>
						<tr>	
							<td style="vertical-align: middle;">
								<a href="<?=base_url("nyintern/oelkaelder/overview/$shopper->alumnumId")?>">
									<?=$shopper->name?>
								</a>
							</td>
							<td style="vertical-align: middle;"><?=orensToPriceStr($shopper->saldo)?> kr.</td>
							<td style="vertical-align: middle;">
								<div class="input-group">
									<input type="number" class="form-control" id="deposit<?=$shopper->shopperId?>" name="deposit<?=$shopper->shopperId?>" step="0.01" placeholder="Beløb" style="width: 100px;" />
									<div class="input-group-addon">kr</div>
								</div>
							</td>
							<td style="vertical-align: middle;">
								<a href="<?=base_url("nyintern/oelkaelder/deactivate/$shopper->shopperId")?>" class="btn btn-default">Deaktiver konto</a>
							</td>
						</tr>
					<? endforeach; ?>
				</tbody>
			</table>
		</div></div>

		<div class="pull-right" style="margin-bottom: 10px;">
			<button type="submit" class="btn btn-primary">Gem indbetalinger</button>
		</div>
	</form>
</div>

<div class="row" style="margin-top: 30px;">
	<div class="col-lg-6">
		<div class="panel panel-info">
			<div class="panel-heading">Genaktiver deaktiveret alumne</div>
			<div class="panel-body">
				<form method="post" action="<?=base_url('nyintern/oelkaelder/activate')?>">
					<select name="shopperId" id="shopperId" class="form-control">
						<option>Ingen alumne valgt</option>
						<? foreach($inactiveShoppers as $shopper): ?>
							<option value="<?=$shopper->shopperId?>">
								<?=$shopper->name?> - <?=($shopper->saldo/100)?> kr.
							</option>
						<? endforeach; ?>
					</select>
					<br />
					<div class="pull-right">
						<button type="submit" class="btn btn-primary">Aktiver alumne</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel panel-info">
			<div class="panel-heading">Tilføj alumne fra alumnelisten</div>
			<div class="panel-body">
				<form method="post" action="<?=base_url('nyintern/oelkaelder/addShopper')?>">
					<select id="alumnumId" name="alumnumId" class="form-control">
						<option>Ingen alumne valgt</option>
						<? foreach($nonShoppers as $nonShopper): ?>
							<option value="<?=$nonShopper->alumnumId?>">
								<?=$nonShopper->name?>
							</option>
						<? endforeach; ?>
					</select>
					<br />
					<div class="pull-right">
						<button type="submit" class="btn btn-primary">Tilføj alumne</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="row" style="margin-top: 30px;">
	<div class="col-lg-6">
		<div class="panel panel-info">
			<div class="panel-heading">Advarselsmail 1</div>
			<div class="panel-body">
				<form method="post" class="form-horizontal" action="<?=base_url('nyintern/oelkaelder/setWarningMail')?>">

					<div class="form-group">
						<div class="col-sm-12">
						<textarea class="form-control" rows="5" name="message" placeholder="Besked"><?=$warning1->message?></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-3" for="amount">Beløbsgrænse:</label>
						<div class="col-sm-9">
							<input type="number" class="form-control" name="amount" id="amount" step="0.01" placeholder="Beløb" value="<?=$warning1->amount/100?>">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-3" for="active"></label>
						<div class="col-sm-9">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="active" id="active" 
									<? if($warning1->active): ?>
										checked
									<? endif;?>
									> Aktiveret
								</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-12">
							<div class="pull-right">
								<button type="submit" class="btn btn-primary">Gem</button>
							</div>
						</div>
					</div>

					<input type="hidden" name="warningNumber" value="1">
				</form>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel panel-info">
			<div class="panel-heading">Advarselsmail 2</div>
			<div class="panel-body">
				<form method="post" class="form-horizontal" action="<?=base_url('nyintern/oelkaelder/setWarningMail')?>">

					<div class="form-group">
						<div class="col-sm-12">
						<textarea class="form-control" rows="5" name="message" placeholder="Besked"><?=$warning2->message?></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-3" for="amount">Beløbsgrænse:</label>
						<div class="col-sm-9">
							<input type="number" class="form-control" name="amount" id="amount" step="0.01" placeholder="Beløb" value="<?=$warning2->amount/100?>">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-3" for="active"></label>
						<div class="col-sm-9">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="active" id="active" 
									<? if($warning2->active): ?>
										checked
									<? endif;?>
									> Aktiveret
								</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-12">
							<div class="pull-right">
								<button type="submit" class="btn btn-primary">Gem</button>
							</div>
						</div>
					</div>

					<input type="hidden" name="warningNumber" value="2">
				</form>
			</div>
		</div>
	</div>
</div>


<div class="row" style="margin-top: 30px;">
	<div class="col-lg-6">
		<div class="panel panel-info">
			<div class="panel-heading">Generer indbetalingsrapport</div>
			<div class="panel-body">
				<form method="post" class="form-horizontal" action="<?=base_url('nyintern/oelkaelder/depositReport')?>">
					<div class="form-group">
						<label class="control-label col-sm-3" for="startdate">Startdato:</label>
						<div class="col-sm-9">
							<input type="date" class="form-control" name="startdate" id="startdate">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-3" for="enddate">Slutdato:</label>
						<div class="col-sm-9">
							<input type="date" class="form-control" name="enddate" id="enddate">
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-12">
							<div class="pull-right">
								<button type="submit" class="btn btn-primary">Hent</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel panel-info">
			<div class="panel-heading">Generer salgsrapport</div>
			<div class="panel-body">
				<form method="post" class="form-horizontal" action="<?=base_url('nyintern/oelkaelder/saleReport')?>">
					<div class="form-group">
						<label class="control-label col-sm-3" for="startdate">Startdato:</label>
						<div class="col-sm-9">
							<input type="date" class="form-control" name="startdate" id="startdate">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-3" for="enddate">Slutdato:</label>
						<div class="col-sm-9">
							<input type="date" class="form-control" name="enddate" id="enddate">
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-12">
							<div class="pull-right">
								<button type="submit" class="btn btn-primary">Hent</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="row" style="margin-top: 30px;">
	<div class="col-lg-6">
		<div class="panel panel-info">
			<div class="panel-heading">Generer antal</div>
			<div class="panel-body">
				<form method="post" class="form-horizontal" action="<?=base_url('nyintern/oelkaelder/saleReportQuantity')?>">
					<div class="form-group">
						<label class="control-label col-sm-3" for="startdate">Startdato:</label>
						<div class="col-sm-9">
							<input type="date" class="form-control" name="startdate" id="startdate">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-3" for="enddate">Slutdato:</label>
						<div class="col-sm-9">
							<input type="date" class="form-control" name="enddate" id="enddate">
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-12">
							<div class="pull-right">
								<button type="submit" class="btn btn-primary">Hent</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>