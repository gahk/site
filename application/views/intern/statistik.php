<head>



<script type="text/javascript">

$(document).ready(function(){





	$.get("<?=base_url('nyintern/statistik/getAllStudyData')?>", function(data) {



		new Morris.Line({

			// ID of the element in which to draw the chart.

			element: 'chartStudy',

			// Chart data records -- each entry in this array corresponds to a point on

			// the chart.

			data: $.parseJSON(data),

			// The name of the data record attribute that contains x-values.

			xkey: 'date',

			// A list of names of data record attributes that contain y-values.

			ykeys: ['DTU', 'KU', 'RUC', 'CBS', 'ITU', 'Kunst'],

			labels: ['DTU', 'KU', 'RUC', 'CBS', 'ITU', 'Kunst'],

			// Labels for the ykeys -- will be displayed when you hover over the chart.



			pointSize: 3,

			smooth: false,

			hideHover: 'auto',

			dateFormat: function (x) {

				var d = new Date(x);

				var month = d.getMonth()+1;

				var res = d.getDate()+"/"+ month +" " + d.getFullYear();

				return res.toString();

			}





		});



	});







	$.get("<?=base_url('nyintern/statistik/getAnsoegningerByStudyAndThisYearJSON')?>", function(data) {

		Morris.Donut({

			element: 'ansoegStudyPie',

			data: $.parseJSON(data)

		});

	});





	$.get("<?=base_url('nyintern/statistik/getAnsoegningerByHeardAboutUsAndThisYearJSON')?>", function(data) {

		Morris.Donut({

			element: 'ansoegHeardAboutPie',

			data: $.parseJSON(data)

		});

	});





	$.ajax({

		url: "<?=base_url('nyintern/statistik/getAnsoegningerByStudyAndMonthTable')?>",

		dataType: "HTML",

		error: function(msg){

				console.log(msg.statusText);

				return msg;

		},

		success: function(html){

				$("#ansoegStudyTableBody").html(html);

		}

	});







	$.get("<?=base_url('nyintern/statistik/getAngsoegningStatisticJSON')?>", function(data) {



		new Morris.Line({

			// ID of the element in which to draw the chart.

			element: 'ansoegChart',

			// Chart data records -- each entry in this array corresponds to a point on

			// the chart.

			data: $.parseJSON(data),

			// The name of the data record attribute that contains x-values.

			xkey: 'date',

			// A list of names of data record attributes that contain y-values.

			ykeys: ['rundvisning', 'fremleje'],

			labels: ['rundvisning', 'fremleje'],

			// Labels for the ykeys -- will be displayed when you hover over the chart.



			pointSize: 0,

			smooth: false,

			hideHover: 'auto',

			dateFormat: function (x) {

				var d = new Date(x);

				var month = d.getMonth()+1;

				var res = d.getDate()+"/"+ month +" " + d.getFullYear();

				return res.toString();

			}



		});

	});





	$.get("<?=base_url('nyintern/statistik/getCounterStatistic')?>", function(data) {



		new Morris.Line({

			// ID of the element in which to draw the chart.

			element: 'counterChart',

			// Chart data records -- each entry in this array corresponds to a point on

			// the chart.

			data: $.parseJSON(data),

			// The name of the data record attribute that contains x-values.

			xkey: 'date',

			// A list of names of data record attributes that contain y-values.

			ykeys: ['count'],

			labels: ['Besøgende'],

			// Labels for the ykeys -- will be displayed when you hover over the chart.



			pointSize: 0,

			smooth: false,

			hideHover: 'auto',

			dateFormat: function (x) {

				var d = new Date(x);

				var month = d.getMonth()+1;

				var res = d.getDate()+"/"+ month +" " + d.getFullYear();

				return res.toString();

			}



		});

	});





});







</script>

</head>






<div class="row">

	<!-- Alumner fordelt på universiteter -->

  <div class="col-lg-12">

    <div class="panel panel-primary">

      <div class="panel-heading">

        <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Alumner fordelt på Universiteter</h3>

      </div>

      <div class="panel-body">

        <div id="chartStudy" style="height: 300px;"></div>

      </div>

    </div>

  </div>



</div>





<div class="row">

	<div class="col-lg-6">

		<div class="panel panel-primary">

      <div class="panel-heading">

        <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Ansøgninger pr måned</h3>

      </div>

      <div class="panel-body">

				<div id="ansoegChart"></div>

			</div>

	  </div>

	</div>





	<div class="col-lg-6">

		<div class="panel panel-primary">

      <div class="panel-heading">

        <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Besøgende på gahk.dk</h3>

      </div>

      <div class="panel-body">

				<div id="counterChart"></div>

			</div>

	  </div>

	</div>





</div>







<div class="row">



	<div class="col-lg-6">

		<div class="panel panel-primary">

      <div class="panel-heading">

        <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Rundvisning i år fordelt på hvor rundviste studerer</h3>

      </div>

      <div class="panel-body">

				<div id="ansoegStudyPie"></div>

				<i>Statik for formular for ansøgning om rundvisning på gahk.dk.<br />

				Tallene er fra 1. januar til dd.</i>

			</div>

	  </div>

	</div>



	<div class="col-lg-6">

		<div class="panel panel-primary">

      <div class="panel-heading">

        <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Hvor rundviste har hørt om kollegiet</h3>

      </div>

      <div class="panel-body">

				<div id="ansoegHeardAboutPie"></div>

				<i>Statik for formular for ansøgning om rundvisning på gahk.dk.<br />

				<!--Tallene er fra 1. januar til dd.</i>-->

			</div>

	  </div>

	</div>





</div>











<div class="row">

	<div class="col-lg-12">

    <div class="panel panel-primary">

      <div class="panel-heading">

        <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Universiter hvor rundviste studere</h3>

      </div>

      <div class="panel-body">

				<table id="ansoegStudyTable" class="table table-bordered table-hover table-striped">

          <thead>

            <tr>

              <th style="width: 70px;">Dato <i class="fa fa-sort"></i></th>

              <th>AU <i class="fa fa-sort"></i></th>

              <th>AAU <i class="fa fa-sort"></i></th>

              <th>CBS <i class="fa fa-sort"></i></th>

              <th>DTU <i class="fa fa-sort"></i></th>

              <th>ITU <i class="fa fa-sort"></i></th>

              <th>KU <i class="fa fa-sort"></i></th>

              <th>RUC <i class="fa fa-sort"></i></th>

              <th>SDU <i class="fa fa-sort"></i></th>

              <th>Andet <i class="fa fa-sort"></i></th>

            </tr>

          </thead>

          <tbody id="ansoegStudyTableBody">

					</tbody>

				</table>

				<i>Statik for formular for ansøgning om rundvisning på gahk.dk </i>

      </div>

    </div>

  </div>



</div>











