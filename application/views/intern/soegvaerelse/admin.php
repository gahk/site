<script type="text/javascript" src="<?=base_url("/public/js/soegvaerelse/vaerelse.js") ?>"></script>
<?
	$monthArray = array("Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December");
?>

<!-- This style and script is for collaping tables -->
<style>
	.hiddenRow {
		padding: 0 !important;
	}	
</style>


<?if(!empty($indstilling)):?>
<div class="pull-right">
		<ul class="nav nav-pills pull-right">
		  <li><a href="<?= base_url('nyintern/soegvaerelse') ?>">Personlig</a></li>
		  <li class="active"><a href="<?= base_url('nyintern/soegvaerelse/admin') ?>">Administrer</a></li>
		</ul>
</div>
<?endif; ?>

<h1 class="page-header">Værelses søgning <small> Administration</small></h1>
<?
function mn2m($monthNumber) {
	$m = $monthNumber % 12;
	if ($m == 0)
		$m = 12;
	return $m;
}

function mn2y($monthNumber) {
	$Y = (int)(($monthNumber - 1) / 12);
	return $Y;
}
?>


<!-- Messages -->
<? if(validation_errors()):?>
	<div class='alert alert-danger'>
		<?=validation_errors()?>
	</div>
<? endif; ?>

<? if($success):?>
	<div class='alert alert-success'>
		<b>Success</b>
	</div>
<? endif; ?>









<?$maanedKey = "";
	$monthcount = 0;
 ?>
<? foreach($offers as $key=>$offer): ?>
	<?
	$monthcount++;
	$nymaaned = date('F', mktime(0, 0, 0, mn2m($offer -> month) + 1, 10));
	if ($nymaaned != $maanedKey) {
		$monthcount = 0;
		$maanedKey = $nymaaned;
	}
	?>

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

	<? if($monthcount == 0): ?>
		<div class="row" style="padding-bottom: 10px;"><div class="col-md-12">
		<h3>Værelser i udbud: <?=$nymaaned ?></h3>
		</div></div>
	<? endif; ?>
	
	<?if($monthcount % 2 == 0): ?>
		<div class="row" style="padding-bottom: 20px;">
	<? endif; ?>


<div class="col-md-6">

<div class="panel panel-default" style="padding: 10px;">

<div class="row">
	<div class="col-md-5">
					<canvas id="<?=$offer -> vaerelses_id ?>-<?=$offer -> month ?>Canvas" style="width: 95%; max-width: 342px;" height="189"></canvas>
									<script>
											$(function() {
												drawMap("<?=$offer -> vaerelses_id ?>-<?=$offer -> month ?>Canvas", 
												"<?=$offer -> vaerelses_id ?>","<?=$roomdata[$offer -> vaerelses_id]['number'] ?>",
												"<?=$roomdata[$offer -> vaerelses_id]['side'] ?>","<?=$roomdata[$offer -> vaerelses_id]['floor'] ?>",
												"<?=base_url('public/image/intern/' . $roomdata[$offer -> vaerelses_id]['floor'] . '.png') ?>");
											});
									</script>
	</div>
	<div class="col-md-7">
		<h4 style="line-height: 20px;">Værelse <?=$roomdata[$offer -> vaerelses_id]['number'] ?> <br />
				<small>
					<?=$roomdata[$offer -> vaerelses_id]['floor'] ?> <?=$roomdata[$offer -> vaerelses_id]['side'] ?>
					<? if($roomdata[$offer->vaerelses_id]['detail'] != ""): ?>
						- <?=$roomdata[$offer -> vaerelses_id]['detail'] ?>
				<? endif; ?>
				</small>
		</h4>
		<form action="<?=base_url("nyintern/soegvaerelse/closeOffer/".$offer->id) ?>">
			 <input type="submit"  class="btn btn-default" value="Afslut udbudet" onclick="return confirm('Er du sikker på at du vil afslutte udbuddet. Dette kan ikke fortrydes.')">
		</form>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<table class="table table-condensed" id="applicaForRoom<?=$offer -> id ?>">
			<thead>
	        	<tr>
		          <th>Navn</th>
		          <th>Kvotient</th>
		          <th>Prioritet</th>
		        </tr>
	      	</thead>
	      	<tbody>
	      	
	      	</tbody>
			</table>
			<script>			
				$(document).ready(function () {
				    $.getJSON("https://www.gahk.dk/nyintern/soegvaerelse/getApplicationByRoom/<?=$offer -> vaerelses_id ?>",
					function (json) {
						var tr;
						var won = 0;
						for (var i = 0; i < json.length; i++) {
								//Main row
								if(json[i].won == "1"){
									tr = "<tr data-toggle='collapse' style='cursor:pointer'  data-target='#row<?=$offer -> vaerelses_id ?>-"+i+"'><td><b>" + json[i].firstName+" "+ json[i].lastName + "</b></td><td><b>" + json[i].K + "</b></td><td><b>" + json[i].priority + "</b></td></tr>";
									won = json[i].K;
								} else {
									tr = "<tr data-toggle='collapse' style='cursor:pointer' data-target='#row<?=$offer -> vaerelses_id ?>-"+i+"'><td>" + json[i].firstName+" "+ json[i].lastName + "</td><td>" + json[i].K + "</td><td>" + json[i].priority + "</td></tr>";
								}	
								$('#applicaForRoom<?=$offer -> id ?>').append(tr);
		
								//Hiden extra details
								var trcollapse = "<tr>"+
								"<td class='hiddenRow' colspan='3' style='background-color: #F5F5F5;'>"+
								"<div class='collapse collapseTable<?=$offer -> vaerelses_id ?>' id='row<?=$offer -> vaerelses_id ?>-"+i+"' style='padding: 7px;'>"+
									"<input type='hidden' value='"+json[i].ansoegnings_id+"' />"+
									"<div class='iFrameHere'></div>"+
								"</div></td></tr>";
								$('#applicaForRoom<?=$offer -> id ?>').append(trcollapse);


						}
						//Makes sure all other collapse hides when one opens
						//Loads also extra details
						$(".collapseTable<?=$offer -> vaerelses_id ?>").on('show.bs.collapse', function () {
							$('.collapse.in').collapse('hide');
							var ansoegningsId = $(this).find("input[type=hidden]").val();
							$(this).find(".iFrameHere").html("<iframe src='https://gahk.dk/nyintern/soegvaerelse/getKvotientData/"+ansoegningsId+"' frameBorder='0' style='width: 100%; height: 465px;'></iframe>");
					
						});
						// HEHEHEHE
						if (<?=$this->session->userdata('alumne_id')?>===254) {
							var formdata='priority%5B0%5D=1&leaveMonth=1&leaveYear=2020';
							$.ajax({
								type: "POST",
								url: "<?=base_url('/nyintern/soegvaerelse/getKAsJson/'.$offer -> month)?>",
								data: formdata,
								cache: false,
								success: function(result){
									var jObj = JSON.parse(result);
									console.log(jObj.K);
									if (parseFloat(jObj.K) >= parseFloat(won)) {
										$('#applicaForRoom<?=$offer -> id ?>').css("background-color", "Aquamarine");
									} else {
										$('#applicaForRoom<?=$offer -> id ?>').css("background-color", "Coral");
									}
								}
							});
						}
					});
				});
			</script>	
	</div>
</div>

</div>

</div>

<? endforeach; ?>
<? if(sizeof($offers) > 0): ?>
</div><!-- END row -->
<? endif; ?>







<div class="row">
	
	<div class="col-lg-6">
		<h2>Opret nyt værelses udbud</h2>
        <form role="form" method="POST" action="<?=base_url("nyintern/soegvaerelse/createoffer")?>">

            <div class="form-group">
                <label>Værelse</label>
                <select name="vaerelses_id" class="form-control">
					<option value="" selected="">Intet valgt</option>
                    <?for($i=1; $i<=61; $i++): ?>
						<option value="<?=$i?>" >
							Værelse <?=$roomdata[$i]['number'] ?> &nbsp;&nbsp; - &nbsp;&nbsp; <?=$roomdata[$i]['floor'] ?> <?=$roomdata[$i]['side'] ?> <?=$roomdata[$i]['detail'] ?>
						</option>								
					<? endfor; ?>
                </select>
            </div>
            
           <div class="form-group">
                <label>Værelset er ledigt fra</label>
                <div class="row">
    				<div class="col-lg-6">            
		                <select name="month" class="form-control">
							<?$i = 1;?>
							<option value="">Vælg måned</option>
							<?foreach($monthArray as $i => $monthString):?>
								<option value="<?=$i?>"><?=$monthString?></option>
							<?endforeach?>
						</select>
					</div>
					<div class="col-lg-6">
						<select name="year" class="form-control">
							<? $thisYear = date("Y"); ?>
							<option value="<?=$thisYear?>" selected="true"><?=$thisYear?></option>
							<?for($i = 1; $i <= 1; $i++):?>
								<option value="<?=$thisYear+$i?>" <?=set_select('leaveYear', $thisYear+$i)?>><?=$thisYear+$i?></option>
							<?endfor;?>
						</select>
					</div>
				</div>
           </div>
            
            <button type="submit" class="btn btn-primary">Start udbuddet</button>

        </form>

	</div>
	
	<div class="col-lg-6" style="padding: 7px 0px 0px 40px;">
		<h3>Plantegning</h3>
		<small><i>Klik for at se tegning i stor</i></small>
		<br /><br />
		<a href="<?=base_url("/public/image/intern/plantegning.png")?>"><img style="width: 30%" src="<?=base_url("/public/image/intern/plantegning.png")?>" /></a>
	</div>

</div>


<br /><br /><br />

