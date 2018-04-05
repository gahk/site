<head>
	<script type="text/javascript">
		$("#standartPage").css("text-shadow", "0px 0px 2px #888888");
		$("#standartPage, #standartPage h2, #standartPage h3").css("color", "rgba(0, 0, 0, 0)");
	</script>
	<script src="<?=base_url("public/js/ckeditor/ckeditor.js")?>"></script>

	<script>
	function ClickToSave () {
		var headerData = CKEDITOR.instances.titleField.getData();
		var textData = CKEDITOR.instances.textField.getData();
		$("#headerFormInput").val(headerData);
		$("#textFormArea").val(textData);
		$("#editform").submit()
	}
	</script>
</head>

<div class="contentBox smallSecondBox" id="news_box" style="box-shadow: 0px 0px 10px #FD5158;">
	<div class="transparency"></div>
	<div class="content">

			<?if(isset($news)):?>
			<h3 contenteditable="true" id="titleField"><?=$news[0]->title?></h3>
			<p contenteditable="true"  id="textField"><?=$news[0]->text?></p>
			<?else:?>
			<h3 contenteditable="true" id="titleField">Skriv her en ny nyhed</h3>
			<p contenteditable="true"  id="textField">
				Klik på denne tekst for at rette den nye nyhed.<br />
				Husk at trykke på "Gem" bagefter.
			</p>
			<?endif?>

		<hr>
		<button class="btn btn-primary" onclick='ClickToSave()' type="button">Gem</button>

		<?=form_open("news/save", array('id' => 'editform'))?>
			<!-- Hidden boks for sumbitting -->
			<?if(isset($news)):?>
				<input type="hidden" name="id" value="<?=$news[0]->id?>" />
			<?else:?>
				<input type="hidden" name="id" value="-1" />
			<?endif;?>
			<input type="hidden" id="headerFormInput" name="title" />
			<textarea style="display: none;" id="textFormArea" name="text"></textarea>
		</form>
	</div>
</div>

<script>
CKEDITOR.disableAutoInline = true;
CKEDITOR.config.toolbar_HE=[ ['Cut','Copy','Paste','-','Undo','Redo','RemoveFormat']];
CKEDITOR.config.toolbar_TE=[ ['Source','-','Cut','Copy','Paste','-','Undo','Redo','RemoveFormat','-','Link','Unlink','-','Image','SpecialChar'], '/', ['Format','Bold','Italic'], '-', ['Blockquote', 'BulletedList']];


CKEDITOR.inline( 'titleField', {
    toolbar:'TE'
});

CKEDITOR.inline( 'textField', {
    toolbar:'TE'
});
</script>