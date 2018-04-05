<div class="row">

	<div class="col-lg-12">
		<h1>Skift kodeord <small>Gahk Intern</small></h1><br />
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
				echo $errorText;
			}

			echo"</div>";
		}

		?>

		<? $attributes = array('role' => 'form'); ?>
		<?=form_open(current_url(), $attributes)?>
			<div class="form-group">
				<label for="email">Gammelt kodeord:</label>
				<input type="password" name="oldpassword" class="form-control">
			</div>
			<div class="form-group">
				<label for="password">Nyt kodeord:</label>
				<input type="password" name="newpassword" class="form-control">
			</div>
			<div class="form-group">
				<label for="password">Gentag kodeord:</label>
				<input type="password" name="confpassword" class="form-control">
			</div>
			<div class="form-group">
				<button class="btn btn-primary" type="submit">Gem</button>
			
			</div>
			
			
			
		</form>

	</div>
</div>

