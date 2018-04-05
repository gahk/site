<head>
	<link rel="stylesheet" href="<?=base_url("public/js/jquery-ui/css/custom-theme/jquery-ui-1.10.1.custom.css")?>" />
	<style>
	  .ui-autocomplete-loading {
		 background: white url(<?=base_url("public/image/elements/ui-anim_basic_16x16.gif")?>) right center no-repeat;
	  }
  </style>
  <script>
  $(function() { 
    $( "#alumne" ).bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).data( "ui-autocomplete" ).menu.active ) {
          event.preventDefault();
        }
      }).autocomplete({source:'alumneSearch/', minLength:2});
  });
  </script>
</head>

<div class="contentBox mediumSizeBox">
	<div class="transparency"></div>
	<h2>Administrer brugere</h2>
Her kan du giver brugere rettighed til at administrerer hjemmesiden.
For at en alumne kan få adgang, skal personen være registreret på gahk intern.<br /><br />

<? if(isset($success)):?>
	<div class='alert alert-success'>
		<?=$success?>
	</div>
<? endif; ?>
<? if(isset($fejl)):?>
	<div class='alert alert-error'>
		<?=$fejl?>
	</div>
<? endif; ?>


	<div class="contrastBox">
		<?=form_open('admin/adduseradm')?>
			<h3>Giv alumne rettigheder</h3>
			<div class="input-append row-fluid">
			  <input class="span12" id="alumne" name="fullname" type="text" placeholder="Søg på alumne (navn)">
			</div>
			<div>
				<table>
					<tr>
						<td style="width:140px;"><input type="checkbox" name="administrator" value="1"> Super Admin</td>
						<td style="width:140px;"><input type="checkbox" name="editpage" value="1"> Ret Side</td>
						<td style="width:140px;"><input type="checkbox" name="indstilling" value="1"> Indstilling</td>
						<td style="width:140px;"><input type="checkbox" name="inspektion" value="1"> Inspektion</td>
					</tr>
					<tr>
						<td><input type="checkbox" name="kokkengruppe" value="1"> Køkkengruppen</td>
						<td><input type="checkbox" name="ak" value="1"> Ak-gruppe</td>
						<td rowspan="2"><input type="checkbox" name="oelkaelder" value="1"> Ølkælderen</td>
					</tr>
				</table>
			</div>



			<br/>
			<button class="btn" type="submit"><i class="icon-ok"></i> Tilføj</button>
		</form>
	</div>


<br /><br />
<h3>Alle administrator-brugere</h3>

	<table class="table table-striped">
		<thead>
			<th>Navn</th>
			<th>Roller</th>
			<th></th>
		</thead>
		<tbody>
		<? foreach($useradm as $row): ?>
			<tr>
				<td><?=$row->firstName?> <?=$row->lastName?></td>
				<td>
					<?if($row->editpage):?>
						<span class="label label-warning">Ret side</span>
					<?endif;?>
					<?if($row->indstilling):?>
						<span class="label label-info">Indstilling</span>
					<?endif;?>
					<?if($row->inspektion):?>
						<span class="label label-primary">Inspektion</span>
					<?endif;?>
					<?if($row->kokkengruppe):?>
						<span class="label label-danger" style="background-color:#00CC66;">Køkkengruppen</span>
					<?endif;?>
					<?if($row->oelkaelder):?>
						<span class="label label-danger" style="background-color:#CC4488;">Ølkælderen</span>
					<?endif;?>
					<?if($row->administrator):?>
						<span class="label label-important">Super Admin</span>
					<?endif;?>
					<?if($row->ak):?>
						<span class="label label-success">Ak-gruppe</span>
					<?endif;?>
				</td>
				<td style="width: 30px;">
					<?if($loggedInAlumnId != $row->alumne_id):?>
						<a href="<?=base_url('index.php/admin/deleteuseradm/'.$row->id)?>"><span class="glyphicon glyphicon-trash"></span></a>
					<?endif;?>
				</td>
			</tr>
		<? endforeach; ?>		
		</tbody>
	</table>

<?php $this->load->view('layout/footer');?>
</div>

