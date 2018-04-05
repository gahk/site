<!-- Setting up standart text page -->
<? foreach($page as $row): ?>
	<? if(isset($editable)):?>
		<h2 id='headerField' class='hasPopover' contenteditable='true'><?=$row->header?></h2>
	<? else: ?>
		<h2><?=$row->header?></h2>
	<? endif;?>



	<? if(isset($editable)){
		echo "<div id='textField' class='hasPopover' contenteditable='true'>";
	}?>
		<?=$row->text?>
	<? if(isset($editable)){ echo "</div>";}?>

<? endforeach; ?>
