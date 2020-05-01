<?
	$monthArray = array("januar", "februar", "marts", "april", "maj", "juni", "juli", "august", "september", "oktober", "november", "december");
?>

<script>
$(function() {
	//For adding more priority buttons when writing
	var numberOfPriority = 2;
	$( ".prioritySelect" ).change(function() {
			if($("#priority"+numberOfPriority).val() != ""){
				numberOfPriority++;
				$("#priorityFormGroup"+numberOfPriority).show();
			}
	});


	$("form").change( function(){
		var formdata=$('form').serialize();
		$.ajax({
			type: "POST",
			url: "<?=base_url('/nyintern/soegvaerelse/getKAsJson/'.$monthNr)?>",
			data: formdata,
			cache: false,
			success: function(result){
				var jObj = JSON.parse(result);
				$("#kvotientTd").html("= "+jObj.K);
				$("#aLabel").html("a = "+jObj.a);
				$("#bLabel").html("b = "+jObj.b);
			}
		});
	}).change();


});


</script>



<h1 class="page-header">Værelses søgning <small> Ansøg</small></h1>

<!-- Messages -->
<? if(validation_errors()):?>
	<div class='alert alert-danger'>
		<?=validation_errors()?>
	</div>
<? endif; ?>

<?=form_open("nyintern/soegvaerelse/indsend/$monthNr", array('class' => 'form-horizontal'))?>
<div class="row">
	<div class="col-md-2">
	<h2>Step 1</h2>
	</div>
	<div class="col-md-10" style="margin-top: 25px;">
	<p class="lead">Hvilke værelser vil du søge?</p>
			<?for($i=0; $i<sizeof($offers); $i++): ?>
				<div class="form-group" id="priorityFormGroup<?=$i?>" <?if($i >= 9 && set_value('priority['.($i-1).']') == "" ){ echo"style='display:none;'";}?> >
					<label for="priority" class="col-sm-2 control-label" style="text-align:left;"><?=($i+1)?>. prioritet</label>
					<div class="col-sm-10">
							<select name="priority[<?=$i?>]" class="form-control prioritySelect" id="priority<?=$i?>">
								<option value="" selected="">Intet valgt</option>
								<? foreach($offers as $offer): ?>
									<option value="<?=$offer->vaerelses_id?>" <?=set_select('priority['.($i).']', $offer->vaerelses_id);?> >
										Værelse <?=$roomdata[$offer->vaerelses_id]['number']?> &nbsp;&nbsp; - &nbsp;&nbsp; <?=$roomdata[$offer->vaerelses_id]['floor']?> <?=$roomdata[$offer->vaerelses_id]['side']?> <?=$roomdata[$offer->vaerelses_id]['detail']?>
									</option>
								<? endforeach; ?>
							</select>
					</div>
				</div>
		<? endfor;?>

	</div>
</div>


<br />

<div class="row">
	<div class="col-md-2">
	<h2>Step 2</h2>
	</div>
	<div class="col-md-10" style="margin-top: 25px;">
	<p class="lead">Lad os beregne din kvotient</p>
<? 
$diff = abs(strtotime(date('Y-m-01')) - strtotime($userData[0]->moveInDay));
$monthsSinceMoveIn = floor($diff / (30*60*60*24));
?>

<!-- Indflytningsdato  -->
<div  class="row">
	<div class="col-sm-5  control-label" style="text-align:left;">
		<p>Du flyttede ind:</p>
	</div>
<div class="col-sm-7  control-label" style="text-align:left;">
		<p><?=date("j. F Y", strtotime($userData[0]->moveInDay))?> (<?=$monthsSinceMoveIn?> måneder siden)</p>
	</div>
</div>

<!-- Indflytning ved ansøgning -->
<? 
$diff = strtotime($monthOfferAsTime) - strtotime(date('Y-m-d H:i:s'));
$monthToOfferMove = ceil($diff / (30*60*60*24));
?>
<div  class="row">
	<div class="col-sm-5  control-label" style="text-align:left;">
		<p>Dato for den søgte indflytning:</p>
	</div>
	<div class="col-sm-7  control-label" style="text-align:left;">
		<p><?=date("j. F Y", strtotime($monthOfferAsTime))?> (om <?=$monthToOfferMove?> måneder)</p>
	</div>
</div>

<div class="row">
	<div for="orlovButton" class="col-sm-5 control-label" style="text-align:left;">
		Hvornår forventer du at afslutte dit studie?
	</div>
	<div class="col-sm-4">
			<select name="leaveMonth" class="form-control">
				<?$i = 0;?>
				<option value="">Vælg måned</option>
				<?foreach($monthArray as $monthString):?>
					<option value="<?=$i?>" <?=set_select('leaveMonth', $i)?>>Pr. 1. <?=$monthString?></option>
					<? $i = $i+1; ?>
				<?endforeach?>
			</select>
	</div>
	<div class="col-sm-3">
		<select name="leaveYear" class="form-control">
			<? $thisYear = date("Y"); ?>
			<option value="">Vælg år</option>
			<?for($i = 0; $i < 7; $i++):?>
				<option value="<?=$thisYear+$i?>" <?=set_select('leaveYear', $thisYear+$i)?>><?=$thisYear+$i?></option>
			<?endfor;?>
		</select>
	</div>
	<div class="clearfix visible-xs-block"></div>
</div>

<div class="row">
	<div class="col-sm-12" style="text-align:left;padding-top:8px;">
	Vælg den første dato efter du har forsvaret speciale. Hvis du for eksempel forsvarer speciale den 8. juni, skal du vælge 1. juli.
	</div>
	<div class="col-sm-12" style="text-align:left;padding-top:8px;">
	Fraflytningsdato, hvorved forståssidste dag,man harmulighed for at boherifølge 5-årsreglen
eller normeretstudietid. Hvis studieplanen kræver fraflytning førstudiets afslutning, gælder
denne dato	</div>
</div>

<br /><br />

<div class="panel panel-default">
  <div class="panel-heading">
		<div id="orlovButtonGroup">
			<div class="col-sm-5">
			<label for="orlovButton" class="control-label" style="text-align:left; padding-left: 0px;">
				Har du været på orvlov?
			</label>
				<br /><small>Tryk flere gange, for at oprette flere perioder.</small>
			</div>
			<div class="col-sm-7">
					<button type="button" id="addOrvlov" class="btn btn-primary btn-sm" style="margin-top: 10px;">Tilføj orvlov</button>
			</div>
			<div class="clearfix visible-xs-block"></div>
		</div>
  </div>

	<div class="panel-body">
		<?for($k=0; $k<7; $k++): ?>
		<div class="orlovGroup" id="orlovGroup<?=$k?>" <? if(set_value('orlovMoveOutMonth['.($k).']') == ""):?>style="display: none;"<? endif;?>>
			<div class="row">
				<div for="orlovMoveOut" class="col-sm-5 control-label" style="text-align:left;">
					Hvornår flyttede du ud?
				</div>
				<div class="col-sm-4">
						<select name="orlovMoveOutMonth[<?=$k?>]" class="form-control orlovMoveOutMonthSelect">
							<?$i = 0;?>
							<option value="">Vælg måned</option>
							<?foreach($monthArray as $monthString):?>
								<option value="<?=$i++?>" <? if($i == set_value('orlovMoveOutMonth['.($k).']')):?>selected<?endif;?>  >	<?=$monthString?>
								</option>
							<?endforeach?>
						</select>
				</div>
				<div class="col-sm-3">
					<select class="form-control" name="orlovMoveOutYear[<?=$k?>]">
						<option value="">Vælg år</option>
						<? $thisYear = date("Y"); ?>
						<?for($i = 0; $i > -7; $i--):?>
							<option value="<?=$thisYear+$i?>" <? if($thisYear+$i == set_value('orlovMoveOutYear['.($k).']')):?>selected<?endif;?>>
								<?=$thisYear+$i?></option>
						<?endfor;?>
					</select>
				</div>
				<div class="clearfix visible-xs-block"></div>
			</div>

			<div class="row" style="padding-top: 10px;">
				<div for="orlovMoveIn" class="col-sm-5 control-label" style="text-align:left;">
					Hvornår flyttede du ind igen?
				</div>
				<div class="col-sm-4">
						<select name="orlovMoveInMonth[<?=$k?>]" class="form-control">
							<?$i = 0;?>
							<option value="">Vælg måned</option>
							<?foreach($monthArray as $monthString):?>
								<option value="<?=$i++?>" <? if($i == set_value('orlovMoveInMonth['.($k).']')):?>selected<?endif;?>><?=$monthString?></option>
							<?endforeach?>
						</select>
				</div>
				<div class="col-sm-3">
					<select class="form-control" name="orlovMoveInYear[<?=$k?>]">
						<option value="">Vælg år</option>
						<? $thisYear = date("Y"); ?>
						<?for($i = 0; $i > -7; $i--):?>
							<option value="<?=$thisYear+$i?>" <? if($thisYear+$i == set_value('orlovMoveInYear['.($k).']')):?>selected<?endif;?>>
							<?=$thisYear+$i?></option>
						<?endfor;?>
					</select>
				</div>
				<div class="clearfix visible-xs-block"></div>
			</div>
		</div>  <!-- Orlov group ends -->
		<?endfor;?>

  </div>

</div>

<script>
//Adding orlov formula group
var orlovCount = 0;
$("#addOrvlov").click(function(){
/*	orlovGroup.attr('class', 'orlovGroup');
	orlovGroup.find(".orlovMoveOutMonthSelect").attr('name', "orlovMoveOutMonth["+orlovCount+"]");
	orlovGroup.show();*/
	var orlovGroup = $("#orlovGroup"+orlovCount).show();
	if(orlovCount++ != 0){
		orlovGroup.before("<hr style='margin: 29px 0px 29px 0px;'>");
	}
});
</script>



	<br />
	<p class="lead">Din kvotient</p>


			<table>
				<tr>
					<td rowspan="2"><h4 style="margin: 6px;">K &nbsp; = &nbsp;</h4></td>
					<td style="text-align: center;"><h4 style="margin: 6px;">a &middot; 100</h4></td>
					<td rowspan="2"><h4 style="margin: 6px;" id="kvotientTd"></h4></td>
				</tr>
				<tr>
					<td style="text-align: center; border-top: 1px solid black;"><h4 style="margin: 6px;">a + b + 12</h4></td>
				</tr>
			</table>
		<small>
			'<i id='aLabel'>a</i>' er antal måneder du har boet på GAHK. '<i id='bLabel'>b</i>' er antal måneder til du forventer at afslutte dit studie.</small>
	</div>
</div>




<br /><br />
<div class="row">
	<div class="col-md-2">
	<h2>Færdig</h2>
	</div>
	<div class="col-md-10" style="margin-top: 25px;">
	<p class="lead">Afslut ansøgningen</p>
	<button type="submit" class="btn btn-primary">Send værelsesansøgningen</a>
	</div>

</div>

</form>
