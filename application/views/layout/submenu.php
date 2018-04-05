	<?
		//Find out which link which is clicked
		//id of fist subpage is 7
		for ($i = 1; $i <= 20; $i++) {
			if($i == $pageid)
				$submenuItemClass[$i] = "selectedItem";
			else
				$submenuItemClass[$i] = "";
		}
	?>

	<?if($menucat == 5):?>
		<div class="submenu">
			<div class="container">
				<ul>
					<li><?=anchor('faciliteter/faellesomraede', 'Fællesområde', array('class' => $submenuItemClass[11]))?></li>
					<li><?=anchor('faciliteter/vaerelse', 'Værelse', array('class' => $submenuItemClass[10]))?></li>
				</ul>
			</div>
		</div>
	<?elseif($menucat == 4):?>
		<div class="submenu">
			<div class="container">
				<ul>
					<li><?=anchor('kollegielivet/alumnerne', 'Alumnerne', array('class' => $submenuItemClass[20]))?></li>
					<li><?=anchor('kollegielivet/selvstyre', 'Selvstyre', array('class' => $submenuItemClass[16]))?></li>
					<li><?=anchor('kollegielivet/aaretsgang', 'Årets gang', array('class' => $submenuItemClass[15]))?></li>
					<li><?=anchor('faciliteter/kokken', 'Madordning', array('class' => $submenuItemClass[12]))?></li>
					<li><?=anchor('kollegielivet/bestyrelse', 'Bestyrelse', array('class' => $submenuItemClass[17]))?></li>
					<li><?=anchor('pylon', 'Pylonforening', array('class' => $submenuItemClass[5]))?></li>
				</ul>
			</div>
		</div>
	<?elseif($menucat == 7):?>
		<div class="submenu">
			<div class="container">
				<ul>
					<li><?=anchor('legater/modtagne', 'Modtagne legater', array('class' => $submenuItemClass[18]))?></li>
				</ul>
			</div>
		</div>
	<?elseif($menucat == 6):?>
		<div class="submenu">
			<div class="container">
				<ul>
					<li><?=anchor('optagelse/ansoeg', 'Søg optagelse', array('class' => $submenuItemClass[7]))?></li>
					<li><?=anchor('optagelse/fremlej', 'Fremleje', array('class' => $submenuItemClass[8]))?></li>
					<li><?=anchor('optagelse/fremlej/eng', 'Sublet', array('class' => $submenuItemClass[9]))?></li>
				</ul>
			</div>
		</div>
	<?endif;?>
