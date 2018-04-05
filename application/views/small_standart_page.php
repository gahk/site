<div class="contentBox smallSizeBox">
	<div class="transparency"></div>
	<div class="content">

		<? foreach($page as $row): ?>

			<h2><?=$row->header?></h2>
			<p align="justify">
			<?=$row->text?>
			</p>
		<? endforeach; ?>

<?php $this->load->view('layout/footer');?>
	</div>
</div>

