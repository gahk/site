<head>
	<script>
		$(function() {
			$( "#ansoegningTabel tr" ).click(function(){
				if(!$(this).hasClass("trSelected")){
					window.location.href = "showAnsoegning/"+$(this).children(".ansoegningId").val();
				}
			});

		});
	</script>
</head>
<h1 class="page-header">Ansøgning om <?=$ansoegning[0]->typeOfAnsoegning?></h1>

<ol class="breadcrumb">
    <li>
        <i class="fa fa-list"></i>  <a href="<?=base_url("optagelse/listAnsoegninger")?>">Ansoegnings overblik</a>
    </li>
    <li class="active">
        <?=$ansoegning[0]->fullName?>
    </li>
</ol>

<? if($success): ?>
	<div class='alert alert-success'>
		Ansøgningen er nu sat som modtaget 
	</div>
<? endif; ?>


<div class="btn-group" role="group" aria-label="Actions på ansøgningen" style="margin: 10px 0px 10px 0px;">
	<? if($ansoegning[0]->receiverFirstName != ""): ?>
		<button type="button" class="btn btn-primary" disabled="disabled">Modtaget af <?=$ansoegning[0]->receiverFirstName?> <?=$ansoegning[0]->receiverLastName?></button>
	<? elseif($ansoegning[0]->receivedByAlumneId != 0): ?>
		<button type="button" class="btn btn-primary" disabled="disabled">Modtaget</button>
	<? else: ?>
		<a href="<?=base_url("/optagelse/setasreceived/".$ansoegning[0]->id)?>" class="btn btn-primary">Sæt som "Modtaget"</a>	
	<? endif;?>
</div>



<h3><?=$ansoegning[0]->fullName?></h3>
<table class="table">
	<tr>
		<td>Sendt afsted:</td>
		<td><?=$ansoegning[0]->day?>. <?=$months[$ansoegning[0]->month-1]?> <?=$ansoegning[0]->year?></td>
	</tr>
	<tr>
		<td>Mail:</td>
		<td><?=$ansoegning[0]->email?></td>
	</tr>
	<tr>
		<td>Alder:</td>
		<td><?=$ansoegning[0]->age?></td>
	</tr>

	<?if($ansoegning[0]->fieldofstudy):?>
	<tr>
		<td>Studieretning:</td>
		<td><?=$ansoegning[0]->fieldofstudy?></td>
	</tr>
	<?endif;?>

	<?if($ansoegning[0]->studyyear):?>
	<tr>
		<td>Antal år studeret:</td>
		<td><?=$ansoegning[0]->studyyear?></td>
	</tr>
	<?endif;?>

	<?if($ansoegning[0]->yearleft):?>
	<tr>
		<td>Studieår tilbage:</td>
		<td><?=$ansoegning[0]->yearleft?></td>
	</tr>
	<?endif;?>

	<?if($ansoegning[0]->university):?>
	<tr>
		<td>Universitet:</td>
		<td><?=$ansoegning[0]->university?></td>
	</tr>
	<?endif;?>
	<?if($ansoegning[0]->occupation):?>
	<tr>
		<td>Beskæftigelse:</td>
		<td><?=$ansoegning[0]->occupation?></td>
	</tr>
	<?endif;?>
	<?if($ansoegning[0]->heardAboutUs):?>
	<tr>
		<td>Hørt om Gahk:</td>
		<td><?=$ansoegning[0]->heardAboutUs?></td>
	</tr>
	<?endif;?>
</table>



<br />
<?if($ansoegning[0]->motivation):?>
<h4>Motivation:</h4>
<?=$ansoegning[0]->motivation?>
<?endif;?>

