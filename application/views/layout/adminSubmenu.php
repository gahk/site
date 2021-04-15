<!--This is close to identical with submenu, but have admin links -->

	<?
		//Find out which link which is clicked
		//id of fist subpage is 7
		for ($i = 1; $i <= 20; $i++) {
			if(isset($pageid) && $i == $pageid)
				$submenuItemClass[$i] = "selectedItem";
			else
				$submenuItemClass[$i] = "";
		}
	?>
	<?if($menucat == 5):?>
		<nav class="submenu">
			<ul>
				<li><?=anchor('page/edit/11', 'Fællesområde', array('class' => $submenuItemClass[11]))?></li>
				<li><?=anchor('page/edit/10', 'Værelse', array('class' => $submenuItemClass[10]))?></li>
			</ul>
		</nav>
	<?elseif($menucat == 4):?>
		<nav class="submenu">
			<ul>
				<li><?=anchor('page/edit/20', 'Alumnerne', array('class' => $submenuItemClass[20]))?></li>
				<li><?=anchor('page/edit/16', 'Selvstyre', array('class' => $submenuItemClass[16]))?></li>
				<li><?=anchor('page/edit/15', 'Årets gang', array('class' => $submenuItemClass[15]))?></li>
				<li><?=anchor('page/edit/12', 'Madordning', array('class' => $submenuItemClass[12]))?></li>
				<li><?=anchor('page/edit/17', 'Bestyrelse', array('class' => $submenuItemClass[17]))?></li>
				<li><?=anchor('page/edit/5', 'Pylon', array('class' => $submenuItemClass[5]))?></li>
			</ul>
		</nav>
	<?elseif($menucat == 7):?>
		<nav class="submenu">
			<ul>
				<li><?=anchor('page/edit/18', 'Modtagne legater', array('class' => $submenuItemClass[18]))?></li>
			</ul>
		</nav>
	<?elseif($menucat == 6):?>
		<nav class="submenu">
			<ul>
				<li><?=anchor('page/edit/7', 'Søg optagelse', array('class' => $submenuItemClass[7]))?></li>
				<li><?=anchor('page/edit/8', 'Fremleje', array('class' => $submenuItemClass[8]))?></li>
				<li><?=anchor('page/edit/9', 'Sublet a room', array('class' => $submenuItemClass[9]))?></li>
			</ul>
		</nav>
	<?endif;?>
