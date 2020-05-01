<!DOCTYPE HTML>

<html lang="da">
<? $this->load->view('layout/head.php');?>
<head>
	<script type="text/javascript" src="<?=base_url('public/js/pylon/pylon.js')?>"></script>

<script>
$(function() {
	$(window).resize(function(){
		var $frame = $("#calendarframe", window.parent.document);
		$frame.height($(document).height());
		console.log("resize "+$(document).height());
	}).resize();
});
</script>
</head>
<body>
<div class="overflowhidden">
<div class="content">

<h2>Ret kalender</h2>

<table id="pylonCalendar" class="table">
	<? foreach($calendar as $row): ?>
		<tr>
			<td style="width: 78px;"><?=$row->day?>. <?=$months[$row->month-1]?> <?=$row->year?></td>
			<td><?=$row->name?></td>
			<td style="width: 16px;"><a href="<?= site_url('/pylon/delete/'.$row->id); ?>"><i class="icon-trash"></i></a></td>
		</tr>
		<tr style="display: none;">
			<td  colspan="2" style="border-top: 0px; padding-top: 0px">
				<?=$row->description?>
			</td>
		</tr>
	<?endforeach;?>
</table>




<h3>Opret nyt indlæg</h3>

<? if(validation_errors()):?>
	<div class='alert alert-error'>
		De markerede felter skal udfyldes.
	</div>
<?endif;?>
<? if(isset($success)):?>
	<div class='alert alert-success'>
		<?=$success?>
	</div>
<? endif; ?>

<?=form_open('pylon/save_calendar',  array('class' => 'verticalForm'))?>
		<div>
				<label for="name">Navn på inlæg: <h4>*</h4></label>
				<input type="text" name="name" class="<?=form_error('name')!=''? 'error': ''?>" value="<?=set_value('name')?>" style="width: 395px;">
		</div>

		<div>
			<div class="pull-left">
					<label for="day">Dag (dd): <h4>*</h4></label>
					<input type="text" name="day" class="<?=form_error('day')!=''? 'error': ''?>" value="<?=set_value('day')?>" style="width:120px;">
			</div>
			<div class="pull-left" style="padding: 0px 5px 0px 5px;">
					<label for="month">Måned (mm): <h4>*</h4></label>
					<input type="text" name="month" class="<?=form_error('month')!=''? 'error': ''?>" value="<?=set_value('month')?>" style="width:120px;">
			</div>
			<div class="pull-left">
					<label for="year">År (åååå): <h4>*</h4></label>
					<input type="text" name="year" class="<?=form_error('year')!=''? 'error': ''?>" value="<?=set_value('year')?>" style="width:120px;">
			</div>
		</div>
<div>
<label for="description">Kort beskrivelse: <h4>*</h4></label>
<textarea name="description" class="<?=form_error('description')!=''? 'error': ''?>" style="width:395px; height:100px;"><?=set_value('description')?></textarea></div>
</div>

<button type="submit" class="btn btn-primary" type="button">Opret indlæg</button>
</form>

</div>
</div>
</body>
</html>
