<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Gahk intern - <?=$pagename?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?=base_url('public/intern/css/bootstrap.css')?>" rel="stylesheet">

    <!-- Add custom CSS here -->
    <link href="<?=base_url('public/intern/css/sb-admin.css')?>" rel="stylesheet">
    <link rel="stylesheet" href="<?=base_url('public/intern/font-awesome/css/font-awesome.min.css')?>">
    <!-- Page Specific CSS -->
    <link rel="stylesheet" href="<?=base_url('public/intern/morris-0.4.3.min.css')?>">

    <!-- JavaScript -->
    <script src="<?=base_url('public/js/jquery/jquery-1.8.3.min.js')?>"></script>
    <!--<script src="<?=base_url('public/intern/js/jquery-1.10.2.js')?>"></script>-->
    <script src="<?=base_url('public/intern/js/bootstrap.js')?>"></script>

    <!-- Page Specific Plugins -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="<?=base_url('public/intern/morris-0.4.3.min.js')?>"></script>
    <script src="<?=base_url('public/intern/js/tablesorter/jquery.tablesorter.js')?>"></script>
    <script src="<?=base_url('public/intern/js/tablesorter/tables.js')?>"></script>

	</head>
	<body style="margin: 0px; background-color: #F5F5F5;">
		<span class="pull-right" style="padding-right: 10px;"><small><i class="fa fa-clock-o fa-1x" style="padding-right: 5px;"></i> <?=gmdate("d. M Y", $applyDatetime)?></small></span>
		<h4>Sådan er kvotienten beregnet</h4>
										
		<table>
			<tr>
				<td rowspan='2'><h5 style='margin: 6px;'>K &nbsp; = &nbsp;</h5></td>
				<td style='text-align: center;'><h5 style='margin: 6px;'>a &middot; 100</h5></td>
				<td rowspan='2'><h5 style='margin: 6px;' id='kvotientTd'> &nbsp; = &nbsp; <?=$k?></h5></td>
			</tr>
			<tr>
				<td style='text-align: center; border-top: 1px solid black;'><h5 style='margin: 6px;'>a + b + 12</h5></td>		
			</tr>
		</table>
				
		<table>
			<tr>
				<td style="width: 60px;"><small>a = <?=$a?></small></td><td><small><i>Antal måneder boende på GAHK</i></small></td>
			</tr>
			<tr>
				<td style="width: 60px;"><small>b = <?=$b?></small></td><td><small><i>Antal måneder til forventet afsluttelse af studie</i></small></td>
			</tr>	
		</table>
				
		<br />		
		<h5>Data benyttet</h5>				
		<table class="table table-condensed">
			<tr>
				<td><small>Flyttet ind:</small></td><td style="width: 15px;"></td><td><small><?=$moveInMonth?></small></td>					
			</tr>
			<tr>
				<td><small>Søgte værelses indflytningsdag:</small></td><td></td><td><small><?=$moveMonth?></small></td>
			</tr>
			<tr>
				<td><small>Forventet afslutning af studie:</small></td><td></td><td><small><?=$doneStudying?></small></td>
			</tr>
			<tr>
				<td><small>Total orlov:</small></td><td></td><td><small><?=$totalOrlov?> måneder</small></td>
			</tr>													
		</table>								
		
		<h5>Orlov</h5>
			<?if(count($orlov) > 0):?>
			<table class="table table-condensed">
				<tr>
					<th><small>Start</small</th><th><small>Slut</small></th><th><small>Længde</small></th>
				</tr>
				<?foreach($orlov as $orlovIterator):?>
				<tr>
					<td><small><?=$orlovIterator["orlovStart"]?></small></td><td><small><?=$orlovIterator["orlovSlut"]?></small></td><td><small><?=$orlovIterator["orlovLength"]?></small></td>
				</tr>
				<?endforeach;?>
			</table>
			<? else:?>
			<i><small>Intet orlov</small></i>
			<? endif;?>
	</body
</html>