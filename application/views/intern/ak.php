<script>
$(document).ready(function(){
  $(".deletebtn").click(function(){
    if (!confirm("Er du sikker på at du vil slette elementet?")){
      return false;
    }
  });
});
</script>

<?if(!empty($akRole)):?>
<div class="pull-right">
		<ul class="nav nav-pills pull-right">
		  <li class="active"><a href="<?= base_url('nyintern/ak') ?>">Personlig</a></li>
		  <li><a href="<?= base_url('nyintern/ak/admin') ?>">Administrer</a></li>
		</ul>
</div>
<?endif;?>


		<h1 class="page-header">Ak krydser
			<?if(!empty($otherPerson)):?><small><?=$otherPerson['0']->firstName?> <?=$otherPerson['0']->lastName?></small><?endif;?>
		</h1>

<div class="row">
	<div class="col-lg-8">

		<h3>Status log</h3>

		<? if(validation_errors()):?>
			<div class='alert alert-danger'>
				<?=validation_errors()?>
			</div>
		<? endif; ?>

		<? if($success):?>
			<div class='alert alert-success'>
				<b>Success.</b> Ak-loggen er opdateret.
			</div>
		<? endif; ?>
	</div>
</div>


<div class="row">
	<div class="col-lg-8">





		<table class="table table-hover">
			 <thead>
				<tr>
				  <th class="header" style="width: 130px;">Krydser</th>
				  <th class="header"></th>
				</tr>
			 </thead>
			 <tbody>
				<?if(empty($otherPerson)):?>
				<?=form_open('nyintern/ak/')?>
				<input type="hidden" name="formname" value="addtolog" />
				<tr class="active">
				 	<td>
						<div class="form-group <?=form_error('krydser')!=''? 'has-error': ''?>">
						<input type="text" class="form-control" name="krydser" placeholder="Antal krydser" style="width: 130px;" value="<?=set_value('krydser')?>">
						</div>
					</td>
					<td>
						<div class="form-group  <?=form_error('comment')!=''? 'has-error': ''?>" style="margin: 0;">
							<textarea class="form-control" name="comment"  style="width: 100%;" rows="3" placeholder="Beskrivelse"><?=set_value('comment')?></textarea>
						</div>
						<br />

						<button type="submit" class="btn btn-primary btn-sm" style="margin: -14px 0 0 0; width: 100%;">Tilføj krydser</button>
					</td>
				</tr>
				</form>
				<?endif;?>

				<? if(count($aklog) == 0):?>
					<tr><td></td><td><i>Intet arbejde er logget i denne periode</i></td></tr>
				<? endif; ?>

				<? foreach($aklog as $aklogElement): ?>
				<tr>
				 	<td><strong><?=$aklogElement->krydser?></strong></td>
				 	<td>
						<?if(!empty($akRole)):?>
							<a class="deletebtn" href="<?=base_url('index.php/nyintern/ak/delete_log_element/'.$aklogElement->id.'/'.$aklogElement->alumne_id)?>"><i class="fa fa-trash-o fa-1x pull-right"></i></a>
						<?endif;?>
						<small><?= date("j M Y", $aklogElement->timestamp)?></small><br/>
						<?=$aklogElement->comment?>
					</td>
				</tr>
				<? endforeach; ?>
		  </table>
	</div>


	<div class="col-lg-4">
		<div class="panel panel-warning" style="margin: 35px 0px 0px 0px;">
			<div class="panel-heading">
	       <div class="row">
	         <div class="col-xs-3"><i class="fa fa-star-o fa-5x"></i></div>
	         <div class="col-xs-9 text-right">
	           <p class="announcement-heading">
					<?if($akstatus==null){ $akstatus = 0; } else {
						$akstatus = $akstatus['0']->totalkrydser; } ?>	

					<?if(empty($akRole)):?>
					<!-- ReadOnly -->
						<?=$akstatus?>
					<? else: ?>
					<!-- Update status form -->
<?=form_open('nyintern/ak/showPersonalLog/'.$visitedAlumneId, Array('class' => 'form-inline'))?>
<input type="hidden" name="formname" value="updatestatus" />
<input type="text" name="krydser" class="form-control"  style="width: 100px; display: inline-block;" value="<?=$akstatus?>">
<button type="submit" class="btn btn-primary" >Opdater</button>
</form>
					<? endif; ?>
					</p><p class="announcement-text">Ak-krydser</p>
	         </div>
	       </div>
	     </div>
			<div class="panel-footer announcement-bottom">
			  <div class="row">
				 <div class="col-xs-12">Din status</div>
			  </div>
			</div>
		</div>
	</div>
</div>
