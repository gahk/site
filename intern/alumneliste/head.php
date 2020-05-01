<head>
	<script type="text/javascript" language="javascript" src="../jquery.min.js"></script>
	<script type="text/javascript" language="javascript" src="../dataTables/jquery.dataTables.min.js"></script>
	<link type="text/css" href="../dataTables/demo_table.css" rel="stylesheet" />
	<link type="text/css" href="tableExtra.css" rel="stylesheet" />
<?php if($access > 1) { ?>
	<link type="text/css" href="forms.css" rel="stylesheet"/>
	<link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.1.custom.css" rel="stylesheet" />
	<link type="text/css" href="../jQuery.validity/jquery.validity.css" rel="Stylesheet" />
	<script type="text/javascript" language="javascript" src="../jqueryui/js/jquery-ui-1.8.1.custom.min.js"></script>
	<script type="text/javascript" language="javascript" src="../jQuery.validity/jquery.validity.pack.js"></script>
	<script type="text/javascript">	
		$(function() {
		
			$("#form_copylist").validity(function() {
				$("#confirmCopy").assert($("#confirmCopy:checked").length != 0,'Godkend ikke afmærket');
			});
                        $("#form_deletelist").validity(function() {
				$("#confirmDelete").assert($("#confirmDelete:checked").length != 0,'Godkend ikke afmærket');
			});
		
			$("#form_newname").validity(function() {
				$("#newFirstName").require("Fornavn skal udfyldes");
				$("#newLastName").require("Efternavn skal udfyldes");
				$("#newNameOK").assert($("#newNameOK:checked").length != 0,'Godkend ikke afmærket');
			});
		
			$("#form_addperson").validity(function() {
				$("#birthday").match("date", "Formatet skal være: YYYY-MM-DD");
				$("#firstName").require("Fornavn skal udfyldes");
				$("#lastName").require("Efternavn skal udfyldes");
				$("#room").match("number").greaterThan(0,"Vælg et værelse");
				$("#email").match("email", "Ugyldig email");
			});
			
			$("#form_addexistingperson").validity(function() {
				$("#room2").match("number").greaterThan(0,"Vælg et værelse");
			});
			
			$.extend($.validity.patterns, {
				date:/^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$/
			});
			
			
		});
        </script>

	<script type="text/javascript">
		$(function() {
			$('#birthday').datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd',
				yearRange: 'c-10:c+4',
				defaultDate: '-22y'
			});
		});
		jQuery(function($){
		$.datepicker.regional['da'] = {
			closeText: 'Luk',
			prevText: '&#x3c;Forrige',
			nextText: 'Næste&#x3e;',
			currentText: 'Idag',
			monthNames: ['Januar','Februar','Marts','April','Maj','Juni',
			'Juli','August','September','Oktober','November','December'],
			monthNamesShort: ['01 Jan','02 Feb','03 Mar','04 Apr','05 Maj','06 Jun',
			'07 Jul','08 Aug','09 Sep','10 Okt','11 Nov','12 Dec'],
			dayNames: ['Søndag','Mandag','Tirsdag','Onsdag','Torsdag','Fredag','Lørdag'],
			dayNamesShort: ['Søn','Man','Tir','Ons','Tor','Fre','Lør'],
			dayNamesMin: ['Sø','Ma','Ti','On','To','Fr','Lø'],
			weekHeader: 'Uge',
			dateFormat: 'dd-mm-yy',
			firstDay: 1,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''};
		$.datepicker.setDefaults($.datepicker.regional['da']);
		});
	</script>
<?php } ?>
        <script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			$('#standardTable').dataTable( {
				"bPaginate": false,
				"bLengthChange": false,
				"bFilter": true,
				"bSort": true,
				"bInfo": true,
				"bAutoWidth": false,
                                <?php echo $sortStr; ?>
				"oLanguage": {
					sProcessing:   "Henter...",
					sLengthMenu:   "Vis: _MENU_ linjer",
					sZeroRecords:  "Ingen alumner fundet",
					sInfo:         "Viser _TOTAL_ alumne(r)",
					sInfoEmpty:    "Ingen alumner fundet",
					sInfoFiltered: "(ud af _MAX_ alumner)",
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
</head>