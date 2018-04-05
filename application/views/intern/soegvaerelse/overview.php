<?if(!empty($indstilling)):?>
<div class="pull-right">
		<ul class="nav nav-pills pull-right">
		  <li class="active"><a href="<?= base_url('nyintern/soegvaerelse') ?>">Personlig</a></li>
		  <li><a href="<?= base_url('nyintern/soegvaerelse/admin') ?>">Administrer</a></li>
		</ul>
</div>
<?endif;?>

<h1 class="page-header">Værelses søgning <small> Oversigt</small></h1>

<?
	function mn2m($monthNumber) {
    	$m = $monthNumber % 12;
		//if ($m == 0) $m = 12;
		return $m;
	}

	function mn2y($monthNumber) {
		$Y = (int)(($monthNumber)/12);
		return $Y;
	}
?>
<!-- This style and script is for collaping tables -->
<style>
	.hiddenRow {
		padding: 0 !important;
	}	
</style>


<script type="text/javascript" src="<?=base_url("/public/js/soegvaerelse/vaerelse.js")?>"></script>

<? if(sizeof($offers) > 0): ?>
	<div class="jumbotron">
		                  <h1>Søg værelse</h1>
		                  <p>Der er pt. <?=sizeof($offers)?> værelser i udbud, som du har mulighed for at søge. Se værelserne nedenfor.</p>
       <p>
<?foreach($monthWithOffers as $monthOffer):?>
	<? setlocale(LC_TIME, "da_DK"); ?>
	<p><a href="<?= base_url('nyintern/soegvaerelse/soeg/'.$monthOffer[1]) ?>" class="btn btn-primary btn-lg" role="button">Start værelses ansøgning: <?=  date('F', mktime(0, 0, 0, $monthOffer[0]+1, 10)); ?></a></p>
<?endforeach;?>

      </p>
  </div>

<? if($success): ?>
	<p class="bg-success" style="padding: 10px;"><b>Success</b>. Vi har gemt din værelses ansøgning. Indstillingen vil søge for at du får tilbud det højst prioriterede værelse, hvor du har den højeste kvotient (hvis nogen).</p>
<? endif; ?>

<h3>Dine ansøgninger</h3>

<?$maanedKey = "";
	$monthcount = 0;
 ?>

<? foreach ($myApplications as  $key=>$application): ?>
	<?
	/**
	 * This is making new rows
	 * Every round end prev row (besides first row) and start a new
	 * Before first room a div for month-caption is shown
	 */
	 ?>
	 
	<?if($monthcount % 2 == 0 && $key != 0): ?>
		</div>
	<? endif; ?>
	<?if($monthcount % 2 == 0): ?>
		<div class="row" style="padding-bottom: 20px;">
	<? endif; ?>
	<? $monthcount++; ?>
<div class="col-md-6">
<div class="panel panel-default" style="padding: 10px;">
<div class="row">
	<div class="col-md-5">
					<canvas id="<?=$application->vaerelse_id ?>-<?=$application->id?>Canvas" style="width: 95%; max-width: 342px;" height="189"></canvas>
									<script>
											$(function() {
												drawMap("<?=$application->vaerelse_id ?>-<?=$application->id ?>Canvas", 
												"<?=$application->vaerelse_id ?>","<?=$roomdata[$application->vaerelse_id]['number'] ?>",
												"<?=$roomdata[$application->vaerelse_id]['side'] ?>","<?=$roomdata[$application->vaerelse_id]['floor'] ?>",
												"<?=base_url('public/image/intern/' . $roomdata[$application->vaerelse_id]['floor'] . '.png') ?>");
											});
									</script>
	</div>
	<div class="col-md-7">
		<h4 style="line-height: 20px;">Værelse <?=$roomdata[$application->vaerelse_id]['number'] ?> <br />
				<small>
					<?=$roomdata[$application->vaerelse_id]['floor'] ?> <?=$roomdata[$application->vaerelse_id]['side'] ?>
					<? if($roomdata[$application->vaerelse_id]['detail'] != ""): ?>
						- <?=$roomdata[$application->vaerelse_id]['detail'] ?>
				<? endif; ?>
				</small>
		</h4>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<table class="table table-condensed" id="applicaWithId<?=$application -> id ?>">
			<thead>
	        	<tr>
		          <th>Prioritet</th>
		          <th>Kvotient</th>
		          <th>Dato for ansøgning</th>
		          <th></th>
		        </tr>
	      	</thead>
	      	<tbody>
		      	<tr data-toggle='collapse' style='cursor:pointer'  data-target='#row<?=$application -> id?>'>
		      		<td><?=$application->priority?>.</td>
	      			<td><?=$application->K?></td>
	      			<td><?=gmdate("d. M Y", $application->applyDatetime)?></td>
	      			<td><a class="btn btn-default btn-xs" href="#detaljer" role="button">Se detaljer</a></td>
	      		</tr>
	      		<tr>
					<td class='hiddenRow' colspan='4' style='background-color: #F5F5F5;'>
					<div class='collapse collapseTable<?=$application -> id?>' id='row<?=$application -> id?>' style='padding: 7px;'>
					<input type='hidden' value='<?=$application->ansoegnings_id?>' />
					<div class='iFrameHere'></div>
					</div></td></tr>
	      	</tbody>
			</table>
			<script>			
				$(document).ready(function () {
				//	$('#applicaWithId<?=$application -> id ?>').append(trcollapse);

					//Makes sure all other collapse hides when one opens
					//Loads also extra details
					$(".collapseTable<?=$application -> id ?>").on('show.bs.collapse', function () {
						
						$('.collapse.in').collapse('hide');
						var ansoegningsId = $(this).find("input[type=hidden]").val();
						$(this).find(".iFrameHere").html("<iframe src='https://gahk.dk/nyintern/soegvaerelse/getKvotientData/"+ansoegningsId+"' frameBorder='0' style='width: 100%; height: 465px;'></iframe>");
				
					});		
					
				});
			</script>	
	</div>
</div>

</div>
</div>
<? endforeach;?>
<?if($monthcount % 2 == 0 && count($myApplications) != 0): ?>
	</div>
<? endif; ?>




<br />
<h3>Værelser i udbud</h3>
	<div class="row">
	<? foreach($offers as $key=>$offer): ?>
	<?if($key%3 == 0):?>
		</div><div class="row">
	<? endif; ?>
	
		<div class="col-md-4 text-center">
		    <div class="panel panel-default">
		        <div class="panel-body">
						<canvas id="<?=$offer->vaerelses_id?>-<?=$offer->month?>Canvas" style="width: 100%;  max-width: 342px;" height="189"></canvas>
						<script>
							$(function() {
								drawMap("<?=$offer->vaerelses_id?>-<?=$offer->month?>Canvas", 
									"<?=$offer->vaerelses_id?>",
									"<?=$roomdata[$offer->vaerelses_id]['number']?>",
									"<?=$roomdata[$offer->vaerelses_id]['side']?>",
									"<?=$roomdata[$offer->vaerelses_id]['floor']?>",
									"<?=base_url('public/image/intern/'.$roomdata[$offer->vaerelses_id]['floor'].'.png')?>");
							});
						</script>


							<!--<img src="<?=base_url('public/image/intern/'.$roomdata[$offer->vaerelses_id]['floor'].'.png')?>" style="width: 100%;  max-width: 342px;" alt="" />-->
							<div class="text-left" style="width: 100%; margin: 0 auto;">
			          <h4>Værelse <?=$roomdata[$offer->vaerelses_id]['number']?>
								<small><?=$roomdata[$offer->vaerelses_id]['floor']?> <?=$roomdata[$offer->vaerelses_id]['side']?>
								<? if($roomdata[$offer->vaerelses_id]['detail'] != ""): ?>
									- <?=$roomdata[$offer->vaerelses_id]['detail']?>
								<? endif; ?>
	</small></h4>

								Indflytning: 1. <?= date('F', mktime(0, 0, 0, mn2m($offer->month)+1, 10)) ?> <?=mn2y($offer->month)?>
							
							</div>
		        </div>
		    </div>
		</div>
	<? endforeach; ?>
	</div>
<? else: ?>
<div class="well well-sm">
	<div class="row">
		<div class="col-xs-1">
			<h2 style="margin-top: 10px;"><i class="fa fa-clock-o fa-2x"></i></h2>
		</div>
		<div class="col-xs-11">	
		<h2 style="margin: 10px 0px 0px 10px;">Der er ingen værelser i udbud for tiden</h2>
		<small style="margin: 0px 0px 0px 10px;">Indstillingen skal sætte et værelse i udbud før at de kan ansøges.</small>
		</div>
	</div>
</div>

	
<? endif;
