<head>
<script type="text/javascript" language="javascript" src="../jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="../dataTables/jquery.dataTables.min.js"></script>
<link type="text/css" href="../dataTables/demo_table.css" rel="stylesheet" />
<link type="text/css" href="tableExtra.css" rel="stylesheet" />
<link type="text/css" href="style.css" rel="stylesheet" />
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$('#standardTable').dataTable( {
			"bPaginate": false,
			"bLengthChange": false,
			"bFilter": true,
			"bSort": true,
			"bInfo": true,
			"bAutoWidth": false,
			"oLanguage": {
				sProcessing:   "Henter...",
				sLengthMenu:   "Vis: _MENU_ linjer",
				sZeroRecords:  "Ingen pyloner fundet",
				sInfo:         "Viser _TOTAL_ pylon(er)",
				sInfoEmpty:    "Ingen pyloner fundet",
				sInfoFiltered: "(ud af _MAX_ pyloner)",
				sInfoPostFix:  "",
				sSearch:       "Søg:",
				sUrl:          "",
			  oPaginate: {
					sFirst:    "Første",
					sPrevious: "Forrige",
					sNext:     "Næste",
					sLast:     "Sidste"
			  }
			}
		} );
	} );


		jQuery.fn.dataTableExt.oSort['eu_date-asc']  = function(a,b) {
			var ukDatea = a.split('/');
			var ukDateb = b.split('/');

			var x = (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
			var y = (ukDateb[2] + ukDateb[1] + ukDateb[0]) * 1;

			return ((x < y) ? -1 : ((x > y) ?  1 : 0));
		};

		jQuery.fn.dataTableExt.oSort['eu_date-desc'] = function(a,b) {
			var ukDatea = a.split('/');
			var ukDateb = b.split('/');

			var x = (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
			var y = (ukDateb[2] + ukDateb[1] + ukDateb[0]) * 1;

			return ((x < y) ? 1 : ((x > y) ?  -1 : 0));
		};
</script>
<link type="text/css" href="../jQuery.validity/jquery.validity.css" rel="Stylesheet" />
<script type="text/javascript" language="javascript" src="../jQuery.validity/jquery.validity.pack.js"></script>
<script type="text/javascript">
	$(function() {
		$("#form_add_person").validity(function() {
			$("#firstName").require("Fornavn skal udfyldes");
			$("#lastName").require("Efternavn skal udfyldes");
			$("#email").require("Email skal udfyldes").match("email", "Ugyldig email");
		});
		$("#form_remove_person").validity(function() {
			$("#removeByEmailCheckBox").assert($("#removeByEmailCheckBox:checked").length != 0,'Godkend ikke afmærket');
		});
	});
</script>
</head>