<script type="text/javascript" src="<?=base_url('public/js/optagelse/maxlengthtextarea.js')?>"></script>
<script>

	//Changes label for studieretning if University is "Andet"
	$(function() {
			$("#universityInput").change(function(){
				if($("#universityInput option:selected").text() == "Andet"){
					$("#studieretningLabel").html("Studieretning & Universitet: <h4>*</h4>");
					$("#fieldofstudyInput").attr("placeholder", "Studieretning & Universitet");
				} else {
					$("#studieretningLabel").html("Studieretning: <h4>*</h4>");
					$("#fieldofstudyInput").attr("placeholder", "Studieretning");
				}
			}
		);
	});
</script>

<div class="contentBox bigSecondBox">
	<div class="transparency"></div>
	<div class="content">

		<h3>Book en rundvisning</h3>
		Inden du kan søge optagelse vil vi gerne vise dig hvor vi bor.<br /><br />

		<?php 
			if(validation_errors()){
				echo "
					<div class='alert alert-error'>
					De markerede felter skal udfyldes.";

				if(form_error('recaptcha_response_field')!=''){
					echo "<br /> Du indtastede ikke bogstaverne i feltet korrekt. Prøv igen.";
				}

				echo"</div>";
			}
			if($success){
				echo "
					<div class='alert alert-success'>
					<b>Tak.</b> Bookingen er nu sendt, og vi vil kontakte dig inden længe. 
					</div>";
			}
		?>

		<?=form_open('optagelse/send_rundvisning',  array('class' => 'verticalForm'))?>

			<div class="row-fluid">
				<div class="span5">
					<label for="inputEmail">Fulde navn: <h4>*</h4></label>
					<input type="text" name="fullName" class="<?=form_error('fullName')!=''? 'error': ''?> form-control" placeholder="Fulde navn" style="width: 90%;" value="<?=set_value('fullName')?>">
				</div>
				<div class="span7">
					<label for="inputEmail">E-mail: <h4>*</h4></label>
					<input type="text" name="email" class="<?=form_error('email')!=''? 'error': ''?> form-control" placeholder="E-mail" style="width: 100%;"  value="<?=set_value('email')?>">
				</div>
			</div>
			<div class="row-fluid">
					<label>Køn: <h4>*</h4></label>
						 <input type="radio" name="gender" value="male" <?php echo  set_radio('gender', 'male'); ?> /> Mand
  						 <input type="radio" name="gender" value="female" <?php echo  set_radio('gender', 'female'); ?> /> Kvinde
	

			</div>
			<div class="row-fluid">
				<div class="span5">
					<label for="inputEmail">Alder: <h4>*</h4></label>
					<input type="text" name="age" class="<?=form_error('age')!=''? 'error': ''?> form-control" placeholder="Alder" style="width: 90%;" value="<?=set_value('age')?>">
				</div>
				<div class="span7">
					<label for="inputEmail">Hvor længe har du studeret: <h4>*</h4></label>
					<input type="text" name="studyyear" class="<?=form_error('studyyear')!=''? 'error': ''?> form-control" placeholder="Antal år" style="width: 100%;" value="<?=set_value('studyyear')?>">
				</div>
			</div>

			<div class="row-fluid">
				<div class="span5">
					<label for="inputUniversitet">Universitet: <h4>*</h4></label>

<select style="width: 100%;" id="universityInput" name="university" class="<?=form_error('university')!=''? 'error': ''?> form-control">
  <option value="" <?=set_value('university')==""?"selected":""?>>Vælg</option>
  <option value="AU" <?=set_value('university')=="AU"?"selected":""?>>AU - Aarhus Universitet</option>
  <option value="AAU" <?=set_value('university')=="AAU"?"selected":""?>>AAU - Aalborg Universitet</option>
  <option value="CBS" <?=set_value('university')=="CBS"?"selected":""?>>CBS - Copenhagen Buisness School</option>
  <option value="DTU" <?=set_value('university')=="DTU"?"selected":""?>>DTU - Danmarks Tekniske Universitet</option>
  <option value="ITU" <?=set_value('university')=="ITU"?"selected":""?>>ITU - IT-Universitetet</option>
  <option value="KU" <?=set_value('university')=="KU"?"selected":""?>>KU - Københavns Universitet</option>
  <option value="RUC" <?=set_value('university')=="RUC"?"selected":""?>>RUC - Roskilde Universitet</option>
  <option value="SDU" <?=set_value('university')=="SDU"?"selected":""?>>SDU - Syddansk Universitet</option>
  <option value="Andet" <?=set_value('university')=="Andet"?"selected":""?>>Andet</option>
</select>

				</div>
				<div class="span7">
					<label for="inputStudieretning" id="studieretningLabel">Studieretning: <h4>*</h4></label>
					<input type="text" class="<?=form_error('fieldofstudy')!=''? 'error': ''?> form-control" id="fieldofstudyInput" name="fieldofstudy" placeholder="Studieretning" style="width: 100%;" value="<?=set_value('fieldofstudy')?>">
				</div>
			</div>



			<div class="row-fluid">
				<div class="span12">
					<label for="inputEmail">Hvorfra har du hørt om kollegiet? <h4>*</h4></label>
					<select style="width: 103%;" id="heardAboutUsInput" name="heardAboutUs" class="<?=form_error('heardAboutUs')!=''? 'error': ''?> form-control">
						<option value="" <?=set_value('heardAboutUs')==""?"selected":""?>>Vælg</option>
						<? foreach ($heardAboutUsOption as $heardAboutUsElement): ?>
						<option value="<?=$heardAboutUsElement['danish']?>" <?=set_value('heardAboutUs')==$heardAboutUsElement['danish']?"selected":""?>><?=$heardAboutUsElement[$language]?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span12">
					<label for="inputMotivation">Kort motivation (<div id="remainingLengthTempId" style="display: inline;">500</div> tegn tilbage): <h4>*</h4></label>
					<textarea style="width: 100%; height: 70px;" id="motivation" data_maxlength="500" name="motivation" 
					class="<?=form_error('motivation')!=''? 'error': ''?> form-control"><?=set_value('motivation')?></textarea>
				</div>
			</div>



				<?php echo $recaptcha ?>
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>




			<button type="submit" class="btn btn-primary" type="button">Send</button>
		</form>		

	</div>
</div>

