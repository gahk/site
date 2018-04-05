<head>
<script type="text/javascript" src="<?=base_url("public/js/jqplot/jquery.jqplot.min.js")?>"></script>
<script type="text/javascript" src="<?=base_url("public/js/jqplot/plugins/jqplot.dateAxisRenderer.min.js")?>"></script>
<link rel="stylesheet" href="<?=base_url("public/js/jqplot/jquery.jqplot.min.css")?>" />

<script type="text/javascript">
$(document).ready(function(){
  var line1=[['12/08-2008',4]];
  var plot1 = $.jqplot('chart1', [<?=$statistic?>], {
    title:'Besøgende på siden pr dag',
    axes:{
        xaxis:{
            renderer:$.jqplot.DateAxisRenderer,
				tickOptions:{formatString:'%b %#d'},
        }
    },
    series:[{lineWidth:3, markerOptions:{show: false}}]
  });
});
</script>

</head>


<div class="contentBox bigSecondBox" style="top: 400px;">
	<div class="transparency"></div>
	<div class="content">
		<h3>Besøgende</h3>


		<div id="chart1"></div>

	</div>
</div>

