<div class="contentBox mediumSizeBox">
	<div class="transparency"></div>
		<div class="content">
			<? foreach($page as $row): ?>
				<h2><?=$row->header?></h2>
				<p align="justify">
				<?=$row->text?>
				</p>
			<? endforeach; ?>
			<div class="center"><?=anchor('optagelse/ansoeg', 'Søg optagelse', array('class' => 'btn btn-primary'))?><div>
		<?php $this->load->view('layout/footer');?>
	</div>
</div>

