<?if(!empty($akRole)):?>
<div class="pull-right">
		<ul class="nav nav-pills pull-right">
		  <li><a href="<?= base_url('nyintern/ak') ?>">Personlig</a></li>
		  <li class="active"><a href="<?= base_url('nyintern/ak/admin') ?>">Administrer</a></li>
		</ul>
</div>
<?endif;?>



<div class="row">
	<div class="col-lg-8">
		<h1>Ak krydser</h1><br />

<h3 style="margin: 0px 0px 0px 0px;">Start ny embedsperiode</h3>
I starten af en ny embedsperiode skal alumners status for ak-krydser nedjusteres.
Ved at trykke på knappen nedenfor vil du nedjusterer alles status. Derudover vil du tømme alumners log over ak-krydser så i på en frisk kan få overblik over jeres periode. <br /><br />

<? if(validation_errors()): ?>
	<p class='bg-danger' style="padding: 10px;">
		<?=validation_errors(" ", " ")?>
	</p>
<? endif; ?>
<?php if ($success): ?>
	<p class="bg-success" style="padding: 10px;"><b>Success</b>. Du har nu nedjusteret alle alumners ak-krydser</p>
<?php endif ?>

<div style="border: 1px solid #AAAAAA; padding: 15px;">
	<?=form_open('nyintern/ak/reduceAllKrydser', Array('class' => 'form-inline'))?>
	<div class="row">

		<div class="col-sm-5" style="padding: 6px 0px 10px 15px;">
			Nedjuster alle alumner med antal krydser:
		</div>
		<div class="col-sm-4">
			<input type="text" name="krydser" class="form-control"  style="width: 300px; display: inline-block;" value="10"/>
		</div>
	</div>
	<!--<div class="checkbox">
		<label>
			<input type="checkbox" checked="true"/> Ryd loggen, så alumners log er mere overskuelig
		</label>
	</div>-->
	<br />
	<button type="submit" class="btn btn-primary" style="margin: 5px 0px 0px 0px;" onclick="javascript:return confirm('Er du sikker på at du vil nedjusterer alle alumners ak-krydser. Dette kan ikke fortrydes.');">Start ny periode</button>
	</form>
</div>



		<br /><br />

		<h3>Status log</h3>

<div class="row">
	<div class="col-lg-9">



		<table class="table table-hover tablesorter">
			 <thead>
				<tr>
				  <th class="header" style="width: 100px;">Status</th>
				  <th class="header" style="width: 100px;">I periode</th>
				  <th class="header">Navn</th>
				</tr>
			 </thead>
			 <tbody>

				<? foreach($allAkStatus as $aklogElement): ?>
				<tr>
				 	<td><strong>
<? if(empty($aklogElement->totalKrydser)){
		echo "0";
} else {
		echo $aklogElement->totalKrydser; 
}?>
				</strong></td>
				<td>
<? if(empty($aklogElement->krydserInLog)){
		echo "0";
} else {
		echo $aklogElement->krydserInLog; 
}?>
				</td>
			 	<td>
					<a href="<?=base_url('nyintern/ak/showPersonalLog/'.$aklogElement->alumne_id)?>"><?=$aklogElement->firstName?> <?=$aklogElement->lastName?></a>
				</td>
				</tr>
				<? endforeach; ?>
		  </table>
	</div>

</div>
