<script type="text/javascript" src="<?=base_url('public/js/optagelse/maxlengthtextarea.js')?>"></script>
<div class="contentBox bigSecondBox">
	<div class="transparency"></div>
	<div class="content">

		<h3><?=$this->lang->line('soeg_fremleje_label')?></h3>
		<?=$this->lang->line('soeg_fremleje_description_label')?><br /><br />


		<?php 
			if(validation_errors()){
				echo "
					<div class='alert alert-error'>".$this->lang->line('empty_field_error');

				if(form_error('recaptcha_response_field')!=''){
					echo "<br />".$this->lang->line('recaptcha_error');
				}

				echo"</div>";
			}
			if($success){
				echo "
					<div class='alert alert-success'>
						".$this->lang->line('success_message')."
					</div>";
			}
		?>


		<?=form_open('optagelse/send_fremleje/'.$this->lang->line('language'),  array('class' => 'verticalForm'))?>
			<div class="row-fluid">
				<div class="span12">
					<label><?=$this->lang->line('fulde_navn_label')?>: <h4>*</h4></label>
					<input type="text" name="fullName" class="<?=form_error('fullName')!=''? 'error': ''?>" placeholder="<?=$this->lang->line('fulde_navn_label')?>" style="width: 100%;" value="<?=set_value('fullName')?>">
				</div>
			</div>
			<div class="row-fluid">
					<label>Køn: <h4>*</h4></label>
						 <input type="radio" name="gender" value="male" <?php echo  set_radio('gender', 'male'); ?> /> Mand
  						 <input type="radio" name="gender" value="female" <?php echo  set_radio('gender', 'female'); ?> /> Kvinde
	

			</div>

			<div class="row-fluid">
				<div class="span5">
					<label><?=$this->lang->line('alder_label')?>: <h4>*</h4></label>
					<input type="text" name="age" class="<?=form_error('age')!=''? 'error': ''?>" placeholder="<?=$this->lang->line('alder_label')?>" style="width: 90%;" value="<?=set_value('age')?>">
				</div>
				<div class="span7">
					<label><?=$this->lang->line('email_label')?>: <h4>*</h4></label>
					<input type="text" name="email" class="<?=form_error('email')!=''? 'error': ''?>" placeholder="<?=$this->lang->line('email_label')?>" style="width: 100%;" value="<?=set_value('email')?>">
				</div>
			</div>

			<div class="row-fluid">
				<div class="span12">
					<label><?=$this->lang->line('beskaeftigelse_label')?>:<h4>*</h4></label>
					<input type="text" name="occupation" class="<?=form_error('occupation')!=''? 'error': ''?>" placeholder="<?=$this->lang->line('beskaeftigelse_label')?>" style="width: 100%;" value="<?=set_value('occupation')?>">
				</div>
			</div>

			<div class="row-fluid">
				<div class="span12">
					<label for="inputEmail"><?=$this->lang->line('heardAboutUs_label')?> <h4>*</h4></label>
					<select style="width: 103%;" id="heardAboutUsInput" name="heardAboutUs" class="<?=form_error('heardAboutUs')!=''? 'error': ''?>">
						<option value="" <?=set_value('heardAboutUs')==""?"selected":""?>><?=$this->lang->line('select')?></option>
						<? foreach ($heardAboutUsOption as $heardAboutUsElement): ?>
						<option value="<?=$heardAboutUsElement['danish']?>" <?=set_value('heardAboutUs')==$heardAboutUsElement['danish']?"selected":""?>><?=$heardAboutUsElement[$language]?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span12">
					<label for="inputMotivation"><?=$this->lang->line('motivation_label')?> (<div id="remainingLengthTempId" style="display: inline;">500</div>): <h4>*</h4></label>

					<textarea style="width: 100%; height: 70px;" id="motivation" data_maxlength="500" class="<?=form_error('motivation')!=''? 'error': ''?>" name="motivation"><?=set_value('motivation')?></textarea>
				</div>
			</div>

							<?php echo $recaptcha ?>
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>

			<? echo"<input type='hidden' value='".$this->lang->line('language')."'>";?>
			<button type="submit" class="btn btn-primary" type="button">Send</button>
		</form>		

	</div>
</div>

