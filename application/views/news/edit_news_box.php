<!DOCTYPE HTML>

<html lang="da">
<? $this->load->view('layout/head.php');?>
<head>
<base target="_parent" />
<script>

$(function() {
	$(window).resize(function(){
		var $frame = $("#editframe", window.parent.document);
		$frame.height($("body").height());
		console.log("resize "+$("body").height());
	}).resize();
});
</script>

<script src="<?=base_url("public/js/ckeditor/ckeditor.js")?>"></script>

</head>

<body>

	<table id="ansoegningTabel" class="table table-striped">
		<thead>
			<th style="width: 75px;">Dato</th>
			<th>Titel</th>
			<th></th>
		</thead>
		<tbody>
		<? foreach($news as $row): ?>
			<tr>
				<input type="hidden" class="ansoegningId" value="<?=$row->id?>" />
				<td><?=$row->day?>. <?=$months[$row->month-1]?> <?=$row->year?></td>
				<td><?=$row->title?></td>
				<td style="width: 45px;">
					<a href="<?= site_url('/news/edit/'.$row->id); ?>" style="margin: 0px 10px 0px 0px"><i class="icon-pencil"></i></a>
					<a href="<?= site_url('/news/delete/'.$row->id); ?>"><i class="icon-trash"></i></a>
				</td>
			</tr>
		<? endforeach; ?>	
		</tbody>
	</table>

		<div class="pagination pagination-small" style="margin: 10px 0 20px 0">
		  <ul>
			<?for($i = 0; $i < $numberofpages; $i++):?>
			 <li <?if($currentpage == $i){echo"class='active'";}?>><a href="?from=<?=($i)*$rowsPerPage?>"><?=$i+1?></a></li>
			<?endfor;?>
		  </ul>
		</div>

	<hr>

	<a class="btn btn-primary" href="create">Opret ny nyhed</button>



</body>

</html>







