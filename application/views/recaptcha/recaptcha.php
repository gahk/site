<script type="text/javascript">
var RecaptchaOptions = {
theme:"<?= $theme ?>",
lang:"<?= $lang ?>",
custom_theme_widget: 'recaptcha_widget'
};
</script>
<!--<script type="text/javascript" src="<?= $server ?>/challenge?k=<?= $key.$errorpart ?>"></script>
<noscript>
<iframe src="<?= $server ?>/noscript?lang=<?= $lang ?>&k=<?= $key.$errorpart ?>" height="300" width="500" frameborder="0"></iframe><br/>
<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
<input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
</noscript>
-->

<ul class="thumbnails">
  <li class="span4">

<div id="recaptcha_widget" class="thumbnail" style="display:none;">

		<div id="recaptcha_image"></div>
		<div class="recaptcha_only_if_incorrect_sol" style="color:red">Incorrect please try again</div>

		<div class="span6">
			<label for="inputcaptcha">
				<span class="recaptcha_only_if_image"><?=$this->lang->line('write_letters_label')?><h4>*</h4></span>
				<span class="recaptcha_only_if_audio">Enter the numbers you hear: <h4>*</h4></span>
			</label>

		<input type="text" class="span10 <?=form_error('recaptcha_response_field')!=''? 'error': ''?>" id="recaptcha_response_field" name="recaptcha_response_field" />
		</div>

		<div style="float: right; padding: 2px 5px 0px 0px;">
			<div><a href="javascript:Recaptcha.reload()"><?=$this->lang->line('get_new_captcha_label')?></a></div>
			<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')"><?=$this->lang->line('get_sound_label')?></a></div>
			<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Få en billede CAPTCHA</a></div>

			<div><a href="javascript:Recaptcha.showhelp()"><?=$this->lang->line('help_label')?></a></div>
		</div>
	 </div>

	 <script type="text/javascript"
		 src="http://www.google.com/recaptcha/api/challenge?k=6Lf3utoSAAAAACoFuMil8f5ul9G2OHUwvzqod7UL">
	 </script>
	 <noscript>
		<iframe src="http://www.google.com/recaptcha/api/noscript?k=6Lf3utoSAAAAACoFuMil8f5ul9G2OHUwvzqod7UL"
		     height="300" width="500" frameborder="0"></iframe><br>
		<textarea name="recaptcha_challenge_field" rows="3" cols="40">
		</textarea>
		<input type="hidden" name="recaptcha_response_field"
		     value="manual_challenge">
	 </noscript>

	</li>
</ul>
