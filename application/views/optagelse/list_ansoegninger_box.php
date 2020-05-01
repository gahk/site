<head>
	

	<script>


		$(function() {
			$( "#ansoegningTabelBody tr" ).click(function(){
				if(!$(this).hasClass("trSelected")){
					window.location.href = "showAnsoegning/"+$(this).children(".ansoegningId").val();
				}
			});

		});

		$(document).ready(function(){
		    $('#ansoegningTabel').DataTable({
				     "info": false,
				     "order": [[ 0, "desc" ]]


				}
		    	);
		});
	</script>
</head>
<div class="contentBox mediumSizeBox">
	<div class="transparency"></div>
	<h1  class="page-header">Ansøgninger</h1>
	<ol class="breadcrumb">
    <li>
        <i class="fa fa-list"></i>  Ansøgnings overblik
    </li>
</ol>
	
Nedenfor ses en liste over nyeste ansøgninger til rundvisning og fremleje.<br /><br />



	<table id="ansoegningTabel" class="table table-striped ">
		<thead>
			<th>Dato</th>
			<th>Type</th>
			<th>Navn</th>
			<th>Uddannelse</th>
			<th>Har modtaget ansøgningen</th>
			<th>Køn</th>
		</thead>
		<tbody id="ansoegningTabelBody">
		<? foreach($ansoegninger as $row): ?>
			<? $isNewAnsoegning = $row->receivedByAlumneId == 0 ? "success": ""; ?>
			<tr class="<?=$isNewAnsoegning?>">
				<input type="hidden" class="ansoegningId" value="<?=$row->id?>" />
				<td><span style="display:none"><?=$row->timestamp?></span><?=$row->day?>. <?=$months[$row->month-1]?> <?=$row->year?></td>
				<td>
					<? if($row->typeOfAnsoegning == "fremleje"){ echo "<span class='label label-default'>Fremleje</span>";} ?>
					<? if($row->typeOfAnsoegning == "rundvisning"){ echo "<span class='label label-primary'>Rundvisning</span>";} ?>
				</td>
				<td><a href="<?=base_url("/optagelse/showAnsoegning/".$row->id)?>"><?=$row->fullName?></a></td>
				<td><?=$row->university?></td>
				<td>
					<? if($row->receiverFirstName != ""): ?>
						<?=$row->receiverFirstName?> <?=substr($row->receiverLastName, 0, 1)?>.
					<? elseif($row->receivedByAlumneId == 0): ?>
						<span class="label label-info">Ikke modtaget</span>
					<? endif; ?>
				</td>
				
				<td>
					<?= $row->female==1? "Kvinde": "Mand"; ?>
				</td>
			</tr>
		<? endforeach; ?>	
		</tbody>
	</table>


<nav>
  <ul class="pagination">
  	<?if($currentpage > 0):?>
	    <li>
	      <a href="?from=<?=($currentpage-1)*$rowsPerPage?>" aria-label="Previous">
	        <span aria-hidden="true">&laquo;</span>
	      </a>
	    </li>
    <?endif;?>
    <?
    	$minNumb = max(0, $currentpage-7);
		$maxNumb = min($numberofpages, $currentpage+(20-($currentpage-$minNumb)));
    ?>
    <?for($i = $minNumb; $i < $maxNumb; $i++):?>
    <li <?if($currentpage == $i){echo"class='active'";}?>><a href="?from=<?=($i)*$rowsPerPage?>"><?=$i+1?></a></li>
	<?endfor;?>

  </ul>
</nav>


<?php $this->load->view('layout/footer');?>
</div>

