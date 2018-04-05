<head>
<script type="text/javascript" src="<?=base_url("public/js/jqplot/jquery.jqplot.min.js")?>"></script>
<script type="text/javascript" src="<?=base_url("public/js/jqplot/plugins/jqplot.dateAxisRenderer.min.js")?>"></script>

<script type="text/javascript" src="<?=base_url("public/js/jqplot/plugins/jqplot.enhancedLegendRenderer.js")?>"></script>

<link rel="stylesheet" href="<?=base_url("public/js/jqplot/jquery.jqplot.min.css")?>" />

<script type="text/javascript">
$(document).ready(function(){
  var plot1 = $.jqplot('chartAnsoegning', [<?=$rundvisningStatistic?>, <?=$fremlejeStatistic?>], {
		title:'Ansøgninger pr måned',
		axes:{
			xaxis:{
				renderer:$.jqplot.DateAxisRenderer,
				tickOptions:{formatString:'%b %#d'},
				numberTicks: 6
			}
		},
		series:[
			{lineWidth:3,markerOptions:{show: false}, label: "rundvisning"},
			{lineWidth:3, markerOptions: {show: false}, label: "fremleje"}
		],

	  legend: {
			show: true,
         placement: 'inside',
			location: "nw"
     }
  });
});
</script>

</head>


<div class="contentBox smallSizeBox" style="top: 400px;">
	<div class="transparency"></div>
	<div class="content">

		<h3>Ansøgninger</h3>


		<div id="chartAnsoegning"></div>

	</div>
</div>

