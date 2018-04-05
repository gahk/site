<?php
include '../delt.php';
insertHeader("Tømmermænd", "Junkfood omkring GAHK");
?>
<head>
	<style type="text/css">
		img {
			width: 200px;
			height : auto;
		}
		
		div.col {
			float: left;
			width: 250px;
		}
		
		h1 {
			font-size: 1.5em;
		}
	</style>
</head>

<div class="col">
	<h1>Tomarza Pizza</h1>
	<a href="tomarza1.jpg"><img src="tomarza1.jpg"></a>
	<br>
	<a href="tomarza2.jpg"><img src="tomarza2.jpg"/></a>
</div>

<div class="col">
	<h1>Mo's Pizza</h1>
	<a href="mos1.jpg"><img src="mos1.jpg"/></a>
	<br>
	<a href="mos2.jpg"><img src="mos2.jpg"/></a>
</div>

<div class="col">
	<h1>Bagelman</h1>
	<a href="bag1.jpg"><img src="bag1.jpg"/></a>
	<br>
	<a href="bag2.jpg"><img src="bag2.jpg"/></a>
</div>
<?php
insertFooter();
?>