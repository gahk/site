
<?

function cmp($a, $b) {
	if ($a->active == $b->active) {
		return strcmp($a->name, $b->name);
	}
	return $b->active - $a->active;
}

usort($products, "cmp");

?>

<div class="pull-right">
	<ul class="nav nav-pills pull-right">
	  <li><a href="<?= base_url('nyintern/oelkaelder/overview') ?>">Overblik</a></li>
	  <li><a href="<?= base_url('nyintern/oelkaelder/allsales') ?>">Salgsoverblik</a></li>
	  <li><a href="<?= base_url('nyintern/oelkaelder/admin') ?>">Administrer</a></li>
	  <li class="active"><a href="<?= base_url('nyintern/oelkaelder/assortment') ?>">Sortiment</a></li>
	</ul>
</div>

<h1 class="page-header">Sortiment</h1>

<? if ($error != ""): ?>
<div class="alert alert-danger"><strong>Fejl!</strong> <?=$error?></div>
<? endif; ?>

<div class="row"><form method="post">
	<input type="hidden" name="updatePrice" value="1" />

	<div class="col-lg-12">
		<table class="table">
			<thead>
				<tr>
					<th class="header" style="width: 80px;"></th>
					<th class="header">Name</th>
					<th class="header" style="width: 200px;">Betalingshop</th>
					<th class="header" style="width: 130px;">Pris pr. 100 g</th>
					<th class="header" style="width: 130px;">Pris</th>
					<th class="header" style="width: 60px;"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach($products as $product): ?>
					<tr>
						<td><img src="<?=$product->imageurl?>" width="70px"></td>
						<td><?=$product->name?></td>
						<td>
							<div class="input-group">
								<input type="string" class="form-control" id="price_steps<?=$product->productId?>" name="price_steps<?=$product->productId?>" placeholder="50;100;500;1000" style="display: inline-block; width: 180px;" value="<?=$product->price_steps?>" />
								<div class="input-group-addon">øre</div>
							</div>
						</td>
						<td>
							<div class="input-group">
								<input type="number" class="form-control" id="weight_price<?=$product->productId?>" name="weight_price<?=$product->productId?>" step="1" placeholder="Beløb" style="display: inline-block; width: 80px;" value="<?=$product->weight_price?>" />
								<div class="input-group-addon">øre</div>
							</div>
						</td>
						<td>
							<div class="input-group">
								<input type="number" class="form-control" id="price<?=$product->productId?>" name="price<?=$product->productId?>" step="1" placeholder="Beløb" style="display: inline-block; width: 80px;" value="<?=$product->current_price?>" />
								<div class="input-group-addon">øre</div>
							</div>
						</td>
						<td>
							<input type="hidden" name="productId<?=$product->productId?>" value="<?=$product->productId?>" />
							<? if($product->active == 1): ?>
								<a href="<?=base_url("nyintern/oelkaelder/deactivateProduct/$product->productId")?>" class="btn btn-primary">Aktiv</a>
							<? else: ?>
								<a href="<?=base_url("nyintern/oelkaelder/activateProduct/$product->productId")?>" class="btn btn-default">Inaktiv</a>
							<? endif; ?>
						</td>
					</tr>
				<? endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="row">
		<div class="col-xs-8 col-sm-8" style="padding-left: 50px; padding-top: 5px;">
			<i>Prisen kan entes sættes som en fast pris, som pris per vægt eller vha. betalingshop. For fast pris, sæt vægt pris til 0 og lad betalingshop være tom. For pris per vægt, lad pris være tom. Betalingshop lader køberen selv vælge pris. Hvis pris per vægt ikke er 0 vil køberen vælge antal gram, ellers vil køberen vælge pris. De forskellige prishop der kan vælges af køberen skal indtastes som fire priser i øre med semikoloner (;) mellem. For 0.5kr, 1kr, 5kr og 10kr skal 50;100;500;1000 indtastes. </i>
		</div>

		<div class="col-xs-4 col-sm-4">
			<div class="pull-right">
				<button type="submit" class="btn btn-primary">Gem priser</button>
			</div>
		</div>
	</div>
</form></div>

<div class="row" style="margin-top: 30px;">
	<div class="col-lg-6">
		<div class="panel panel-info">
			<div class="panel-heading">Tilføj produkt</div>
			<div class="panel-body"><form method="post">
				<input type="hidden" name="addProduct" value="1" />

				<div class="form-group">
					<label for="productName">Navn</label>
					<input class="form-control" type="text" name="productName" />
				</div>

				<div class="form-group">
					<label for="productPrice">Pris</label>
					<div class="input-group">
						<input class="form-control" type="number" name="productPrice" id="productPrice" step="1" placeholder="Pris" />
						<div class="input-group-addon">øre</div>
					</div>
				</div>

				<div class="form-group">
					<label for="productImage">Billed</label>
					<select class="form-control" id="productImage" name="productImage">
						<? foreach($images as $image): ?>
							<option value="https://gahk.dk/public/image/intern/oel/<?=$image;?>"><?=$image;?></option>
						<? endforeach; ?>
					</select>
				</div>

				<div class="pull-right">
					<button type="submit" class="btn btn-primary">Tilføj</button>
				</div>
			</form></div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel panel-info">
			<div class="panel-heading">Upload produktfoto</div>
			<div class="panel-body">
				<?php echo form_open_multipart('nyintern/oelkaelder/upload');?>
					<div class="input-group">
						<input type="file" name="userfile" />
					</div>

					<div class="pull-right">
						<button type="submit" class="btn btn-primary">Upload</button>
					</div>
				</form>
			</div>
			<div class="panel-heading">Max 100 kb, og 400x400. Brug <a href="http://compressimage.toolur.com/">denne</a> side til komprimering hvis nødvendigt.</div>
		</div>
	</div>
</div>
