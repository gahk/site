<div class="row">

	<div class="col-lg-12">
		<h1>Log ind <small>Gahk Intern</small></h1>
		Log ind på gahk.dk's administrations side ved at indtaste e-mail og kodeord nedenfor. Oplysningerne er de samme som du har oplyst til gahk-intern.<br /><br />
	</div>
</div>

<div class="row">
	<div class="col-lg-3">
		<?
		if(validation_errors() || $showError){
			echo "<div class='alert alert-danger'>";

			if(validation_errors()){
				echo "Alle felter skal udfyldes";
			} else {
				echo "Brugernavn og kodeord passede ikke sammen. Prøv igen.";
			}

			echo"</div>";
		}

		?>

		<? $attributes = array('role' => 'form'); ?>
		<?=form_open(current_url(), $attributes)?>
			<div class="form-group">
					<label for="email">E-mail:</label>
					<input type="text" name="email" class="form-control">
			</div>
			<div class="form-group">
				<label for="password">Kodeord:</label>
				<input type="password" name="password" class="form-control">
			</div>
			<div class="form-group">
				<button class="btn btn-primary" type="submit">Log ind</button>
				<a class="btn btn-default" href="admin/forgotpass">Glemt password?</a>
			
			</div>
			
			
			
		</form>

	</div>
</div>

