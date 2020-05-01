<div class="row">

	<div class="col-lg-12">
		<h1>Rediger oplysninger <small>Gahk Intern</small></h1><br />
	</div>
</div>

<div class="row">
	<div class="col-lg-6">

		Her kan du redigere dine oplysninger i alumnelisten. <br />
		<br />

		<?
		if(validation_errors()){
			echo "<div class='alert alert-danger'>";

			echo validation_errors();

			echo"</div>";
		}

		?>

	</div>
</div>

<div class="row">
	<div class="col-lg-3">

		<? $attributes = array('role' => 'form'); ?>
		<?=form_open(current_url(), $attributes)?>
			<div class="form-group">
				<label for="email">E-mail:</label>
				<input type="email" name="email" class="form-control" value="<?=$email?>">
			</div>
			<div class="form-group">
				<label for="phone">Telefonnummer:</label>
				<input type="tel" name="phone" class="form-control" value="<?=$phone?>">
			</div>
			<div class="form-group">
				<button class="btn btn-primary" type="submit">Gem</button>
			</div>
			
		</form>

	</div>
</div>

