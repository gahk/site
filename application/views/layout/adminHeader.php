<!DOCTYPE HTML>


<html lang="da">
	<? include("head.php"); ?>
	
	<body>

<div class="container">
	<?
		if($username != ""){
			echo"<div id='adminmenu'>";
				if($administrator == 1){
					echo anchor('admin/useradm', 'Administrer brugere');
					echo "&nbsp;&nbsp; | &nbsp;&nbsp;";
				}

				echo anchor('admin/logout', 'Log ud');
			echo"</div>";
		}
	?>
	<header class="adminheader">
		<a href="<?=base_url('index.php/admin')?>" class="logoLink">
			<img class="logo" alt="" src="<?=base_url('public/image/elements/logo-k80.png')?>" />
			<h5>Administration<h5>
			<h1>G. A. Hagemanns Kollegium</h1>
		</a>
	</header>
</div>
	<nav>
		<div class="container">

		<?
			//Find out which link which is clicked
			for ($i = 1; $i <= 7; $i++) {
				if($i == $menucat)
					$menuItemClass[$i] = "selectedItem";
				else
					$menuItemClass[$i] = "";
			}
		?>
			<ul>
				<? if(isset($username) && isset($editpage) && $username != "" && $editpage == "1"): ?>
					<li><?=anchor('page/edit/1', 'Hjem', array('class' => $menuItemClass[1]))?></li>
					<li><?=anchor('page/edit/14', 'Historie', array('class' => $menuItemClass[2]))?></li>
					<li><?=anchor('page/edit/3', 'Kollegielivet', array('class' => $menuItemClass[3]))?></li>
					<li><?=anchor('page/edit/2', 'Faciliteter', array('class' => $menuItemClass[4]))?></li>
					<li><?=anchor('page/edit/6', 'Optagelse', array('class' => $menuItemClass[5]))?></li>
					<li><?=anchor('page/edit/4', 'Legater', array('class' => $menuItemClass[6]))?></li>
					<li><?=anchor('page/edit/21', 'Kontakt', array('class' => $menuItemClass[7]))?></li>

					<?	if(isset($indstilling) && $indstilling == 1): ?>
						<!-- Makes seperator between, if needed -->
						<li>&nbsp;&nbsp; | &nbsp;&nbsp;</li>
					<? endif; ?>
				<? endif;?>				

					<?	if(isset($indstilling) && $indstilling == 1): ?>
						<li>
							<?= anchor('optagelse/listAnsoegninger', 'Indstilling') ?>
						</ li>
					<? endif; ?>
			</ul>
		</div>
	</nav>

	<!--Submenu -->
	<?include("adminSubmenu.php"); ?>

<div class="container row-fluid" style="top:40px;">
