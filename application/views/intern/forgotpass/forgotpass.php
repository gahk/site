<div class="row">

	<div class="col-lg-12">
		<h1>Glemt dit password? <small>Gahk Intern</small></h1>
		Har du glemt dit password, så skriv blot din gahk intern email ind i feltet herunder, herefter sender netværksgruppen dig et nyt password. God dag.<br /><br />
	</div>
</div>

<div class="row">
	<div class="col-lg-3">
		
		<form action="receivedmail" method="post">
			
			<?if($success):?>
				<div class='alert alert-success'>Din midlertidige adgangskode er nu blevet sendt til din GAHK Intern email.</div>
			<?endif?>
			
			<div class="form-group">
					<label for="email">E-mail:</label>
					<input type="text" name="email" class="form-control">
			</div>
			
			<div class="form-group">
				<button class="btn btn-primary" type="submit">Send nyt password</button>
			</div>
		</form>

	</div>
</div>