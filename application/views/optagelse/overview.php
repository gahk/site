<div class="contentBox mediumSizeBox">
	<div class="transparency"></div>
	<div class="content">
				<? foreach($page as $row): ?>

					<h2><?=$row->header?></h2>
					<p align="justify">
					<?=$row->text?>
					</p>
				<? endforeach; ?>

		<div class="well">
			<div class="row-fluid">
				<div class="span4">
<?=anchor('optagelse/ansoeg', 'Søg optagelse', array('class' => 'btn btn-primary'))?>
				</div>
				<div class="pull-right">
					<div class="btn-group">
						<?=anchor('optagelse/fremlej', 'Fremlej', array('class' => 'btn'))?>
						<?=anchor('optagelse/fremlej/eng', 'Sublet', array('class' => 'btn'))?>
					</div>
				</div>

			</div>
		</div>


		<?php $this->load->view('layout/footer');?>
	</div>
</div>

