<head>
<script src="<?=base_url("public/js/ckeditor/ckeditor.js")?>"></script>

<!-- FOR SUBMIT -->
<script>
function ClickToSave () {
   var headerData = CKEDITOR.instances.headerField.getData();
	var textData = CKEDITOR.instances.textField.getData();
	$("#headerFormInput").val(headerData);
	$("#textFormArea").val(textData);
	$("#editform").submit()
}

function ClickToSaveBg(){
	$("#bgpicFormInput").val($("#img").attr("src"));
	$("#editbgform").submit()
}
</script>


<!-- FOR BACKGROUND-PICKER -->
<script type="text/javascript">
function openKCFinder(div) {
    window.KCFinder = {
        callBack: function(url) {
            window.KCFinder = null;
            div.innerHTML = '<div style="margin:5px">Loading...</div>';
            var img = new Image();
            img.src = url;
            img.onload = function() {
                div.innerHTML = '<img id="img" src="' + url + '" />';
                var img = document.getElementById('img');
                var o_w = img.offsetWidth;
                var o_h = img.offsetHeight;
                var f_w = div.offsetWidth;
                var f_h = div.offsetHeight;
                if ((o_w > f_w) || (o_h > f_h)) {
                    if ((f_w / f_h) > (o_w / o_h))
                        f_w = parseInt((o_w * f_h) / o_h);
                    else if ((f_w / f_h) < (o_w / o_h))
                        f_h = parseInt((o_h * f_w) / o_w);
                    img.style.width = f_w + "px";
                    img.style.height = f_h + "px";
                } else {
                    f_w = o_w;
                    f_h = o_h;
                }
                img.style.marginLeft = parseInt((div.offsetWidth - f_w) / 2) + 'px';
                img.style.marginTop = parseInt((div.offsetHeight - f_h) / 2) + 'px';
                img.style.visibility = "visible";
            }
        }
    };
    window.open('<?=base_url("public/js/kcfinder/browse.php?type=images&dir=images/public")?>',
        'kcfinder_image', 'status=0, toolbar=0, location=0, menubar=0, ' +
        'directories=0, resizable=1, scrollbars=0, width=800, height=600'
    );
}
</script>

</head>
<script>
$(function() {
	$(".hasPopover").popover({offset: 50});

});
</script>


<div class="contentBox mediumSizeBox">
	<div class="transparency"></div>

	<?	if($success):?>
		<div class='alert alert-success'>
		<b>Succes.</b> Siden er nu blevet rettet. 
		</div>
	<? endif;?>
	<? if($successbg): ?>
		<div class='alert alert-success'>
			<b>Succes.</b> Baggrunden er nu rettet. 
		</div>
	<? endif; ?>
	<? if($deletesuccess): ?>
		<div class='alert alert-success'>
			<b>Succes.</b> Sletningen er nu sket
		</div>
	<? endif; ?>

<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#edit" data-toggle="tab">Ret tekst</a></li>
		<li><a href="#bgedit" data-toggle="tab" id="bgedittab">Skift baggrund</a></li>
		<? if(isset($showPylonCalendar)): ?>
					<li><a href="#calendaredit" data-toggle="tab" id="calendaredittab">Ret kalender</a></li>
		<? endif;?>
		<? if(isset($showNews)): ?>
					<li><a href="#newsedit" data-toggle="tab" id="newsedittab">Ret nyheder</a></li>
		<? endif;?>
	</ul>

<!-- Edit background tab -->
	<div class="tab-content" style="overflow: visible;">
		<div class="content tab-pane active" id="edit">
			<div class="well well-small">Klik på teksten for at redigerer den.</div>
			<?php $this->load->view('standart_page_setup.php');?>		

			<hr>
			<?=form_open("page/save/$pageid", array('id' => 'editform'))?>
				<input type="hidden" id="headerFormInput" name="header" />
				<textarea style="display: none;" id="textFormArea" name="text"></textarea>
			</form>
			<button class="btn btn-primary" onclick='ClickToSave()' type="button">Gem</button>

		</div>


<!-- Edit background tab -->
		<div class="content tab-pane" id="bgedit">
			<h2>Skift baggrundsbillede</h2>
			<div class="well well-small">
				Vælg venligt et billede nedenfor som bruges som baggrundsbillede.
				Det er også muligt at uploade et nyt billede som kan bruges. <br /><br />

				Vær opmærksom på at et baggrundsbillede ikke bør fylde mere end 100-300 kb. Men stadig være i en høj opløsning.
				Sørg derfor at komprimerer billedet betydeligt inden det uploades.<br /><br />

				Klik på billeder for at skifte billede:
			</div>
			<div id="image" class="hasPopover" onclick="openKCFinder(this)" 
				data-title="Vælg billede"
				data-content="Klik på billedet for at vælge et andet baggrundsbillede."
				data-trigger="hover"
				data-placement="top"
				rel='popover'>
				<? if(isset($bgpic)): ?>
					<?="<img style='margin: 15px 0px 15px 0px;' id='img' src='$bgpic' />"?>
				<? else: ?>
					<div style="margin:5px;">Klik her for at vælge et andet billede</div>
				<? endif;?>
			</div>

			<hr>
			<?=form_open("page/savebg/$pageid", array('id' => 'editbgform'))?>
				<input type="hidden" id="bgpicFormInput" name="bgpic" />
			</form>
			<button class="btn btn-primary" onclick='ClickToSaveBg()' type="button">Gem baggrund</button>
		</div>


<!-- Edit calendar tab if you are on pylon page -->
		<? if(isset($showPylonCalendar)): ?>

			<div class="content tab-pane" id="calendaredit">
				<iframe id="calendarframe" src="<?=site_url('pylon/editCalendar');?>" scrolling="no" style="width: 100%; border: 0px;"></iframe>
			</div>
		<? endif; ?>

<!-- Edit calendar tab if you are on pylon page -->
		<? if(isset($showNews)): ?>

			<div class="content tab-pane" id="newsedit">
				<iframe id="editframe" src="<?=site_url('news/listAndCreate');?>" scrolling="no" style="width: 100%; border: 0px;"></iframe>
			</div>
		<? endif; ?>



	</div>
	<?php $this->load->view('layout/footer');?>
</div>


</div>



<script>
CKEDITOR.disableAutoInline = true;
CKEDITOR.config.toolbar_HE=[ ['Cut','Copy','Paste','-','Undo','Redo','RemoveFormat']];
CKEDITOR.config.toolbar_TE=[ ['Source','-','Cut','Copy','Paste','-','Undo','Redo','RemoveFormat','-','Link','Unlink','-','Image','SpecialChar'], '/', ['Format','Bold','Italic'], '-', ['Blockquote', 'BulletedList']];


CKEDITOR.inline( 'headerField', {
    toolbar:'TE'
});

CKEDITOR.inline( 'textField', {
    toolbar:'TE'
});
</script>
