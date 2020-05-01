<script type="text/javascript" src="<?=base_url('public/js/pylon/pylon.js')?>"></script>
<div class="contentBox smallSecondBox">
	<div class="transparency"></div>
	<div class="content">

		<h3>Kalender</h3>
		<table id="pylonCalendar" class="table">
			<tbody>
				<? foreach($calendar as $row): ?>
					<tr>
						<td style="width: 78px;"><?=$row->day?>. <?=$months[$row->month-1]?> <?=$row->year?></td>
						<td><?=$row->name?></td>
					</tr>
					<tr style="display: none;">
						<td  colspan="2" style="border-top: 0px; padding-top: 0px">
							<?=$row->description?>
						</td>
					</tr>

				<? endforeach; ?>		
			</tbody>
		</table>

	</div>
</div>

