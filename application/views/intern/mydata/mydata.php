<?php

include('delt.php');
insertHeader("MAC registrering", "MAC registrering", null);
//print_r($_POST); //for debugging
?>
<link type="text/css" rel="Stylesheet" href="https://gahk.dk/intern/jQuery.validity/jquery.validity.css" />
<script type="text/javascript" language="javascript" src="https://gahk.dk/intern/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="https://gahk.dk/intern/jQuery.validity/jquery.validity.pack.js"></script>
<link type="text/css" rel="Stylesheet" href="https://gahk.dk/intern/mydata/mydata.css" />
<script type="text/javascript">
	$(function() {
			$('#form_addDevice').validity(function() {
					$("#deviceName").require("Navn på enhed skal udfyldes");
					$("#agreeTerms").assert($("#agreeTerms:checked").length != 0,'Du skal godkende betingelserne');
					$("#mac").require("MAC-adresse skal udfyldes").match(/^[0-9A-Fa-f]{2}[-|:][0-9A-Fa-f]{2}[-|:][0-9A-Fa-f]{2}[-|:][0-9A-Fa-f]{2}[-|:][0-9A-Fa-f]{2}[-|:][0-9A-Fa-f]{2}$/, "Ugyldigt format");
			});
	});
</script>
<?php
include('adodb5/adodb.inc.php');

$alumne_ID = $alumneId;
//$userpassword = $_POST["userpassword"];

$db = ADONewConnection('mysql'); 
//	$db->debug = true; 
$db->Connect('localhost', $username, $password, $database);
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$db->Execute("SET NAMES utf8");

if($alumne_ID > 0) {
	$rs = $db->GetRow("SELECT * FROM intern_alumne where ID = $alumne_ID");
	$phone = $rs['phone'];
	$email = $rs['email'];
	$passwordFromDatabase = $rs["password"];
} elseif($alumne_ID === '0') {
	$passwordFromDatabase = $adminpassword_network;
	$admin = 1;
}

if(true) {
	
	///////////////////////////// EVALUATE POST_ARRAY /////////////////////////////////
	if($_POST['action'] === 'addDevice') {
		
		////////////// CHECK IF MAC IS ALREADY IN DATABASE //////////////
		$month = date("Y")*12+date("m");
		$lastMonth = $month-1;
		$allMacInfo = $db->GetAll("SELECT firstName,lastName,deviceName,mac,intern_alumne.ID FROM (intern_alumne JOIN intern_alumne_liste ON intern_alumne_liste.alumne_ID = intern_alumne.ID JOIN intern_alumne_macaddress ON intern_alumne_macaddress.alumne_ID = intern_alumne.ID) WHERE (intern_alumne_liste.monthNumber >= '$lastMonth')");
		$macsAdmin = $db->GetAll("SELECT * FROM intern_alumne_macaddress WHERE alumne_ID = 0");
		foreach($macsAdmin as $device) {
			$adminDeviceToBeAdded = array("firstName" => "Netværksgruppen", "lastName" => "", "deviceName" => $device["deviceName"], "mac" => $device["mac"], "ID" => "0");
			array_push($allMacInfo,$adminDeviceToBeAdded);
		}
		$allMacAddresses = reduceArrayArray($allMacInfo,"mac");
		
		$device = array();
		$device['mac'] = trim(str_replace(':','-',strtoupper($_POST['mac'])));
		
		if(!in_array($device['mac'],$allMacAddresses)) {
			////////////// IF MAC IS NOT IN DB => ADD TO USER //////////////
			$device['alumne_ID'] = $_POST['alumne_ID'];
			$device['deviceName'] = $_POST['deviceName'];
			date_default_timezone_set("Europe/Copenhagen");
			$device['timeAdded'] = date("Y-m-d H:i:s");
			$db->AutoExecute('intern_alumne_macaddress',$device,'INSERT');
			echo greenText('Enhed tilføjet<br><br>');
		} else {
			foreach($allMacInfo as $entry) {
				if($device['mac'] === $entry['mac']) {
					redText("Enheden du prøver at tilføje (MAC: ".$entry['mac'].") er allerede registreret af ".$entry['firstName']." ".$entry['lastName']." under navnet: ".$entry['deviceName']."<br>.Enheden kan ikke registreres igen.<br>");
					break;
				}
			}
		}
		
		
	} elseif($_POST['action'] === 'removeDevice') {
		$macToDelete = $_POST['macaddress_ID'];
		$db->Execute("DELETE FROM intern_alumne_macaddress WHERE ID = $macToDelete");
		echo greenText('Enhed fjernet<br><br>');
		
	}
	
	
	///////////////////////////// CONTENT /////////////////////////////////
	
	if($rs["networkClosed"]) {
		redText("Dit netværk er lukket");
		if($rs["networkClosedDetails"]) redText("<br>".$rs["networkClosedDetails"]);
		br(2);
	}
	
	$devicesRegistered = $db->GetAll("SELECT * FROM intern_alumne_macaddress WHERE alumne_ID = $alumne_ID ORDER BY timeAdded DESC");

?>

<br>

<div class="contentBlockContainer">
	<div class="contentBlock">
		<fieldset>
		<legend><b>Tilføj MAC-adresse</b></legend>
		En MAC-adresse har ikke noget med en Mac-computer at gøre. Det er en unik kode hver netværksenhed har.<br><br>
		<a href="https://gahk.dk/wiki/index.php?title=Find_MAC-adresse_(Windows)" target="_blank">Windows - Guide i at finde MAC-adressen (åbnes i ny fane)</a><br>
		<a href="https://gahk.dk/wiki/index.php?title=Find_MAC-adresse_(Mac)" target="_blank">MAC - Guide i at finde MAC-adressen (åbnes i ny fane)</a><br>
		Indtast MAC-adressen herunder, og angiv et navn (så du kan kende enheder du har tilføjet fra hinanden)<br>
		<?
			//<form id="form_addDevice" action="mydata.php" method="post"><br>
			echo form_open('nyintern/mydata', array('id' => 'form_addDevice'));
		?>
		<label for="mac">MAC-adresse : </label><input type="text" size="25" name="mac" id="mac" value="<?php if($_POST['mac']) echo $_POST['mac']; ?>"/><br>
		Eksempel: 1A-2B-3C-4D-5E-6F eller 1A:2B:3C:4D:5E:6F<br>
		<br>
		<label for="deviceName">Navn på enhed : </label><input type="text" size="25" name="deviceName" id="deviceName" value=""/><br>
		Eksempel: Zepto Wireless<br>
		"Navn på enhed" skal udfyldes, så du kan kende de enheder du tilføjer fra hinanden.<br>
		<br>
		<div class="box">
				<div class="boxContent">

				<font size="5">Brugererklæring</font><br><br>
				Som betingelse for adgang til og benyttelse af netværket accepteres at:<br><br>

		-	Overholde bestemmelserne i denne brugererklæring, kollegiets husorden (nærmere beskrevet i Den Gyldne Bog) samt gældende lovgivning.<br><br>

		-	Jeg vil ikke give tredjemand adgang til netværket, ligesom jeg ikke vil anvende udstyr, der giver tredjemand adgang til netværket.<br><br>

		-	Følge enhver henvisning eller påbud afgivet af netværksudvalget.<br><br>


		-	Jeg kan blive frakoblet netværket øjeblikkeligt i tilfælde af overtrædelse af gældende lovgivning, retningslinier fastsat i husordenen (se Den Gyldne Bog) eller i denne brugererklæring.<br><br>

		Endvidere giver jeg ved underskrivelse af denne brugererklæring samtykke til at:<br><br>

		-	Der kan foretages en automatisk overvågning (logning) af hele mit brug af netværket til tekniske undersøgelser (fejlfinding, belastningsmålinger mm.). Alle persondata slettes umiddelbart efter afslutningen af undersøgelsen. Netværksgruppen kan ikke videregive disse persondata.<br><br>

		-	Netværksgruppen må foretage automatisk overvågning (logning) af hele mit brug af kollegiets servere og forbindelsen ind og ud af kollegiet til sikkerhedskontrol (misbrug, hacking mm.). Netværksgruppen kan ved mistanke om misbrug gennemgå alle brugerdata. Netværksgruppen kan videregive nødvendig dokumentation for overtrædelser til inspektionen og kollegiets bestyrelse.<br><br>

		-	Netværksgruppen , ved dommerkendelse, af relevante myndigheder kan blive pålagt at videregive oplysninger om mit brug af netværket.<br><br>

		Jeg er blevet oplyst om, at jeg til enhver tid kan anmode om at få oplyst, hvilke oplysninger der er registreret om mig, ligesom jeg kan kræve urigtige eller vildledende oplysninger slettet, og at kollegiet skal vurdere disse spørgsmål efter reglerne i kapitel 9 og 10 i persondataloven.
		Kollegiets netværksgruppe står, som dataansvarlige, for håndteringen af persondata i henhold til persondataloven.

		Vilkår for brug af ekstern netadgang.<br><br>

		Jeg accepterer, at jeg ved brug af netforbindelsen til og fra kollegiet er underlagt de til enhver tid gældende vilkår for den benyttede udbyder (pt. benyttes Københavns Universitet). Vilkårene kan ændres, med det af udbyderen givne varsel, efter opslag på kollegiet.
				</div>
		</div>
		<br><br><label for="agreeTerms">Jeg accepterer betingelserne : </label><input type="checkbox" size="25" name="agreeTerms" id="agreeTerms" value="yes"/>
		<br><br>
		<input type="hidden" name="action" value="addDevice"/>
		<input type="hidden" name="alumne_ID" value="<?php echo $alumne_ID; ?>"/>
		<input type="hidden" name="userpassword" value="<?php echo $userpassword; ?>"/>
		<input type="submit" value="Tilføj enhed"/><br>
		</form>
		</fieldset>
	</div>
<?php
	if($devicesRegistered) { ?>
		<div class="contentBlock">
		<fieldset>
		<legend><b>Fjern MAC-adresse</b></legend>
		<?php
		if($rs["networkClosed"]) {
			redText("MAC-adresser kan ikke fjernes når dit netværk er lukket.");
		} else {
			//echo '<form action="mydata.php" method="post"><br>';
			echo form_open('nyintern/mydata');
			echo '<input type="hidden" name="action" value="removeDevice"/>';
			echo '<input type="hidden" name="alumne_ID" value="'.$alumne_ID.'"/>';
			echo '<input type="hidden" name="userpassword" value="'.$userpassword.'"/>';
			echo '<label for="macaddress_ID">Navn : </label>';
			selector("macaddress_ID","",reduceArrayArray($devicesRegistered,'ID'),reduceArrayArray($devicesRegistered,'deviceName'), null);
			echo '<br><br><input type="submit" value="Fjern"/><br>';
			echo '</form>';
		}
		?>
		</fieldset>

		<br><br><b>MAC-adresser associeret med din bruger:</b>
		<table border="1">
		<tr><th>Navn på enhed</th>
		<th>MAC-adresse</th>
		<th>Tidspunkt tilføjet</th></tr>

		<?php
		foreach($devicesRegistered as $d) {
			echo '<tr><td>'.$d['deviceName'].'</td>';
			echo '<td>'.$d['mac'].'</td>';
			echo '<td>'.date("d. M Y - H:i",strtotime($d['timeAdded'])).'</td></tr>';
		}
		?>
		</table>
		<?php

		if($admin) {
			$allMacs = getAllMacsFromDB();
			echo '<br><br><b>Alle MAC-adresser i databasen (antal: '.count($allMacs).')</b>';
			echo '<table border="1">'; // table of all mac addresses
			echo '<tr>';
			echo '<th>Navn</th>';
			echo '<th>Navn på enhed</th>';
			echo '<th>MAC-adresse</th>';
			echo '<th>Tidspunkt tilføjet</th>';
			echo '</tr>';

			foreach($allMacs as $d) {
				echo '<tr><td>'.$d['firstName']." ".$d['lastName'].'</td>';
				echo '<td>'.$d['deviceName'].'</td>';
				echo '<td>'.$d['mac'].'</td>';
				echo '<td>'.date("d. M Y - H:i",strtotime($d['timeAdded'])).'</td></tr>';
			}
			echo '</table>'; // table of all mac addresses
		}
	} else {
		echo '<br>Ingen MAC-adresser er registreret.';
	}
	echo '</div>';
} else {
	redText("Login fejlede");
}
?>
</div>
<?php

$db->Close();
insertFooter();

function getAllMacsFromDB() {
	global $db;
	$allMacs = $db->GetAll("SELECT firstName,lastName,deviceName,mac,timeAdded,intern_alumne.ID FROM (intern_alumne JOIN intern_alumne_macaddress ON intern_alumne_macaddress.alumne_ID = intern_alumne.ID)");
	$macsAdmin = $db->GetAll("SELECT * FROM intern_alumne_macaddress WHERE intern_alumne_ID = 0");
	foreach($macsAdmin as $device) {
		$adminDeviceToBeAdded = array("firstName" => "Netværksgruppen", "lastName" => "", "deviceName" => $device["deviceName"], "timeAdded" => $device["timeAdded"], "mac" => $device["mac"], "ID" => "0");
		array_push($allMacs,$adminDeviceToBeAdded);
	}
	return $allMacs;
}
?>
