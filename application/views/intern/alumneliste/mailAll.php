<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
include '../delt.php';
$access=0;
if($_POST["typedpassword"]===$adminpassword_mailall) $access=1;
if($_POST["typedpassword"]===$adminpassword_network) $access=2;


//////////////////// Set up database connection ////////////////////
include('../adodb5/adodb.inc.php');
$db = ADONewConnection('mysql'); 
//	$db->debug = true; 
$db->Connect('localhost', $username, $password, $database); //parameter are from delt.php
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$db->Execute("SET NAMES utf8");

?>
<head><link type="text/css" href="forms.css" rel="stylesheet"/></head>
<link type="text/css" href="../jQuery.validity/jquery.validity.css" rel="Stylesheet" />
<script type="text/javascript" language="javascript" src="../jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="../jQuery.validity/jquery.validity.pack.js"></script>
<script type="text/javascript">	
		$(function() {
		
			$("#from_mailall").validity(function() {
				$("#sender").require("Afsender skal udfyldes");
				$("#subject").require("Emne skal udfyldes");
				$("#body").require("Besked skal udfyldes");
			});
	
		});
</script>
<?php

if(!$access) {
	insertHeader("Alumneliste", "Forkert kode");
	redText("Adgang nægtet");
} else {

	//////////////////// Figure out which list to show ////////////////////
	$month = $_POST["month"]; //month in int format (Y*12 + m)
	if($month === 'allPersons') {
		$monthStr = 'alle alumner i databasen';
	} else {
		$monthStr = mn2mstr($month);
	}
	insertHeader("Alumneliste", "Alumneliste for $monthStr");
	

	////////////////// GET ALUMNER FROM DATABASE //////////////////
	if($month === "allPersons") {
		$rs = $db->GetAll("SELECT email FROM intern_alumne WHERE email != ''");
	} else {
		$rs = $db->GetAll("SELECT email FROM intern_alumne JOIN intern_alumne_liste ON intern_alumne_liste.alumne_ID = intern_alumne.ID WHERE (intern_alumne_liste.monthNumber = '$month' AND intern_alumne.email != '')");
	}
	$rs_extra = $db->GetAll("SELECT email FROM intern_alumne_emailsubscribers WHERE email != ''");
	$emails = array_merge(reduceArrayArray($rs,"email"), reduceArrayArray($rs_extra,"email"));
	?>
	
	
	
	<!-- ////////////////// MAIL FORM ////////////////// -->
	<fieldset>
	<legend>Send mail til liste</legend>
	<form action="mailAllDone.php" method="post" id="from_mailall" name="from_mailall">
	<input type="hidden" name="action" value="mailAll"/>
	<input type="hidden" name="typedpassword" value="<?php echo $_POST["typedpassword"]; ?>"/>
	<input type="hidden" name="month" value="<?php echo $month; ?>"/>
	Afsender: <input type="text" id="sender" name="sender"/><br>
	<i>Eksempel: Festgruppen</i>
	<br><br>
	Emne: <input type="text" id="subject" name="subject"/><br><br>
	Besked:<br><textarea id="body" name="body" rows="10" cols="55"></textarea><br />
        <input type="radio" name="mode" value="oneByOne"> Send individuelle mails<br>
        <input type="radio" name="mode" value="oneMail" checked> Send som én mail<br>
	<input type="submit" value="Send mail til liste"/><br>
        <br>
        Hvis <i>Send individuelle mails</i> vælges, kan man bruge følgende udtryk: {firstName},{lastName},{alumne_ID},{password}. Disse udtryk bliver erstattet af de enkelte alumners parametre.
	</form>
	</fieldset>
	
	<?php
	echo '<br>Mail sendes til '.count($emails).' emailadresser:<br><br>';
	foreach($emails as $e) {
		if($e) {
			echo "$e<br>";
		}
	}
	//	print_r($emails);
}


$db->Close();
//echo greenText("<br><br>End of php");
insertFooter();
?>