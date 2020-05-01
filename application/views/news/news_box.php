<!DOCTYPE HTML>

<html lang="da">
<? $this->load->view('layout/head.php');?>
<head>
<base target="_parent" />
<script>
$(function() {
/*Shall this window be shown or destroyed*/
	if(<?=$shownews?> == '0') {
		$("#news_box", window.parent.document).hide();
	}

/*Resizing of window*/
	$(window).resize(function(){
		var $frame = $("#frameBox", window.parent.document);
		$frame.height($(document).height());
		console.log("resize "+$(document).height());
	}).resize();
});
</script>
		


</head>
<body id="newsContent">

	<? foreach($news as $row): ?>
		<h3><?=$row->title?> 
			<small><?=$row->day?>. <?=$months[$row->month-1]?> <?=$row->year?></small>
		</h3>
<!--strip_tags() for removing html-->
		<p><?=word_limiter($row->text, 40)?><br />
			<a href="<?= site_url('/news/show/'.$row->id); ?>">Læs mere</a>
		</p>

	<? endforeach; ?>


	<div class="pagination pagination-mini" style="margin: 5px 0 0 0; padding: 0px;">
	  <ul>
		<?for($i = 0; $i < $numberofpages; $i++):?>
		 <li <?if($currentpage == $i){echo"class='active'";}?>><a href="?from=<?=($i)*$rowsPerPage?>"><?=$i+1?></a></li>
		<?endfor;?>
	  </ul>
	</div>

</body>

</html>
