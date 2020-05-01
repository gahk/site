<div class="contentBox mediumSizeBox">
	<div class="transparency"></div>
	<div class="content">

<h2>Log ind</h2>
Log ind på gahk.dk's administrations side ved at indtaste e-mail og kodeord nedenfor. Oplysningerne er de samme som du har oplyst til gahk-intern.<br /><br />

<?
if(validation_errors() || $showError){
	echo "<div class='alert alert-error'>";

	if(validation_errors()){
		echo "Alle felter skal udfyldes";
	} else {
		echo "Brugernavn og kodeord passede ikke sammen. Prøv igen.";
	}

	echo"</div>";
}

?>

<?=form_open(current_url())?>

<div class="row-fluid">
	<div class="span12">
		<label for="email">E-mail:</label>
		<input type="text" name="email" style="width: 95%;">
	</div>
</div>
<div class="row-fluid">
	<div class="span12">
		<label for="password">Kodeord:</label>
		<input type="password" name="password" style="width: 95%;">
	</div>
</div>

<button class="btn btn-primary" style="width: 98%;" type="submit">Log ind</button>
</form>

<br />
<?php $this->load->view('layout/footer');?>
	</div>
</div>

