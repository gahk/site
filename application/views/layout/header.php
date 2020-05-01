<!DOCTYPE HTML>

<!--
This page is created by Theis F. Hinz in 2012-2013
Visit www.theisfh.dk
-->

<html lang="da">
	<? include("head.php"); ?>
	<body>

<div class="container">
	<header>
		<a href="<?=base_url('')?>" class="logoLink">
			<img class="logo" alt="" src="<?=base_url('public/image/elements/logo-k80.png')?>" />
			<h1 id='logoText'>G. A. Hagemanns Kollegium</h1>
		</a>
	</header>
</div>
	<nav>
		<div class="container">

		<?
			//Find out which link which is clicked
			for ($i = 1; $i <= 8; $i++) {
				if($i == $menucat)
					$menuItemClass[$i] = "selectedItem";
				else
					$menuItemClass[$i] = "";
			}
		?>
			<ul>
				<li><?=anchor('velkommen', 'Hjem', array('class' => $menuItemClass[1]))?></li>
				<li><?=anchor('kollegielivet/historie', 'Historie', array('class' => $menuItemClass[2]))?></li>
				<li><?=anchor('vision', 'Vision', array('class' => $menuItemClass[3]))?></li>
				<li><?=anchor('kollegielivet', 'Kollegielivet', array('class' => $menuItemClass[4]))?></li>
				<li><?=anchor('faciliteter', 'Faciliteter', array('class' => $menuItemClass[5]))?></li>
				<li><?=anchor('optagelse', 'Optagelse', array('class' => $menuItemClass[6]))?></li>
				<li><?=anchor('legater', 'Legater', array('class' => $menuItemClass[7]))?></li>
				<li><?=anchor('kontakt', 'Kontakt', array('class' => $menuItemClass[8]))?></li>
			</ul>
		</div>
	</nav>

	<!--Submenu -->
	<? include("submenu.php"); ?>

<div id="toTop">^ Gå til toppen</div>

<div class="container row-fluid" style="top:40px;">
