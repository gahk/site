<?php
//quarantine tells how often a MAC-address can get temporary access (in seconds)
$quarantine = 3600*24*30; //temp access only every 30 days
$tempAccessTime = 60*10; //temp access in 10 minutes
session_start();
include('../delt.php');
insertHeader("Mine data", "Mine data login","Mine Data login");
?>
<head>
	<link type="text/css" rel="Stylesheet" href="mydata.css" />
	<link type="text/css" rel="Stylesheet" href="../jQuery.validity/jquery.validity.css" />
	<script type="text/javascript" language="javascript" src="../jquery.min.js"></script>
	<script type="text/javascript" language="javascript" src="../jQuery.validity/jquery.validity.pack.js"></script>
	<script type="text/javascript">	
				$(function() {
					$('#form_tempAccess').validity(function() {
						$("#mac").require("MAC-adresse skal udfyldes").match(/^[0-9A-Fa-f]{2}[-|:][0-9A-Fa-f]{2}[-|:][0-9A-Fa-f]{2}[-|:][0-9A-Fa-f]{2}[-|:][0-9A-Fa-f]{2}[-|:][0-9A-Fa-f]{2}$/, "Ugyldigt format");
					});
				});
	</script>
</head>
<?php
if($_GET["admin"] || isset($_COOKIE["alumne_ID"])) {
	echo '<body onLoad="document.forms.login.userpassword.focus()">';
} else {
	echo '<body onLoad="document.forms.login.alumne_ID.focus()">';
}

if($_POST['action'] === "sendDetails") {
	if(!$_POST['email']) {
		redText("Ingen email-adresse indtastet<br><br>");
	} else {
		include('../adodb5/adodb.inc.php');
		$db = ADONewConnection('mysql'); 
		$db->Connect('localhost', $username, $password, $database);
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $db->Execute("SET NAMES utf8");
		$rs = $db->GetAll("SELECT * FROM intern_alumne where email = '".$_POST['email']."'");
		
		if(count($rs) == 0) {
			redText("Email-adresse ikke fundet<br><br>");
		} elseif(count($rs) > 1) {
			redText("Email-adresse fundet hos mere end én alumne. Ingen oplysninger sendt.<br><br>");
		} else {
			if($rs[0]['password']=='') { //if for some reason password is empty, generate a new
				$db->AutoExecute('intern_alumne',array('password' => genRandomString(8)),'UPDATE',"intern_alumne.ID = '".$rs[0]['ID']."'");
				$rs = $db->GetAll("SELECT * FROM intern_alumne where ID = '".$rs[0]['ID']."'");
			}
		
			$to = $_POST['email'];
			$subject = "Login-oplysninger til GAHK intern";
			$body = "Alumnenummer: ".$rs[0]['ID']."\r\n"."Kodeord: ".$rs[0]['password'];
			$body .= "\r\n\r\nhttp://gahk.dk/intern/mydata/";
			$headers = 'From: interngahk@gahk.dk' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
			$mailSent = 0;
			if (mail($to, $subject, $body, $headers)) {
				greenText("Login-oplysninger sendt.<br><br>");
			} else {
				redText("Fejl i afsendelse af oplysninger. Kontakt netværksgruppen.<br><br>");
			}
		}
	}
} elseif($_POST['action'] === "tempAccess") {
	$mac = trim(str_replace(':','-',strtoupper($_POST['mac'])));

	include('../adodb5/adodb.inc.php');
	$db = ADONewConnection('mysql'); 
	$db->Connect('localhost', $username, $password, $database);
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $db->Execute("SET NAMES utf8");
	
	//Delete entries in alumne_macaddress_temp that are expired
	date_default_timezone_set("Europe/Copenhagen");
	$now = date("Y-m-d H:i:s");
	$db->Execute("DELETE FROM intern_alumne_macaddress_temp WHERE timeToDelete <= '$now'");
	
	$rs = $db->GetAll("SELECT mac FROM intern_alumne_macaddress_temp");

	if(!in_array($mac,reduceArrayArray($rs,'mac'))) {
		$device['mac'] = $mac;
		$device['accessUntil'] = date("Y-m-d H:i:s",strtotime($now)+$tempAccessTime);
		$device['timeToDelete'] = date("Y-m-d H:i:s",strtotime($now)+$quarantine);
		$db->AutoExecute('intern_alumne_macaddress_temp',$device,'INSERT');
		greenText("Midlertidig adgang givet til MAC-adressen $mac");
		br(2);
	} else {
		$rs = $db->GetRow("SELECT * FROM intern_alumne_macaddress_temp WHERE mac = '$mac'");
		if($rs['accessUntil'] > $now) {
			redText("Midlertidig adgang er allerede givet til $mac og udløber ".$rs['accessUntil'].".");
		} else {
			redText("Midlertidig adgang IKKE givet. $mac har karantæne i forhold til midlertidig adgang.");
		}
		br(2);
	}
}



echo '<b>Log ind</b>';
echo '<form action="mydata.php" method="post" name="login">';

if($_GET["admin"]) {
	echo '<input type="hidden" size="25" name="alumne_ID" id="alumne_ID" value="0"/><br>';
} else {
	$val = "";
	if(isset($_COOKIE["alumne_ID"])) $val = $_COOKIE["alumne_ID"];
	echo '<label for="alumne_ID">Alumnenummer i databasen eller email-adresse : </label><input type="text" size="25" name="alumne_ID" id="alumne_ID" value="'.$val.'"/><br>';
}

?>
<label for="userpassword">Personligt kodeord : </label><input type="password" size="25" name="userpassword" id="userpassword" value=""/><br>
<input type="submit" value="Log ind"/><br>
<?php if($_POST['mac']) echo '<input type="hidden" name="mac" value="'.$mac.'">'; ?>
</form>

<?php if(!$_GET["admin"]) { ?>
	Har du aldrig logget på før eller kan du ikke huske dine login-oplysninger? Kig herunder.<br /><br /><br />

	<b>Glemt password?</b>
	Klik her: <a href="http://www.gahk.dk/nyintern/admin/forgotpass"?>Glemt kodeord</a>.<br /><br />

<br />
<?
/*
	<br><br><br>
	<b>Tilsend login-oplysninger til min email-adresse. Både "Alumnenummer i databasen" og "personligt kodeord" vil blive tilsendt. Dette kræver at din email er skrevet ind i alumnelisten.</b>
	<form action="index.php" method="post" name="email">
	<label for="email">Email-adresse : </label><input type="text" size="25" name="email" id="email" value=""/><br>
	<input type="submit" value="Send login-oplysninger"/><br>
	<input type="hidden" name="action" value="sendDetails"/>
	</form>
	<br><br><br><br><br>
	*/
?>	
	<b>Giv midlertidigt adgang for min MAC-adresse, så jeg har mulighed for at logge ind på min mail og finde mine login-oplysninger.</b><br>
	Din computer har kun adgang til netværket hvis dine MAC-addresser er registreret i databasen. Denne funktion kan bruges hvis du ikke kender dit alumnenummer eller din personlige login-kode.<br>Efter du har fået midlertidig adgang (10 minutter), vil du have mulighed for at logge ind på din email og finde oplysninger, så du derefter kan logge ind her på siden.
	<form id="form_tempAccess" action="index.php" method="post" name="tempAccess">
	<div class="contentBlock">
		<label for="mac1">MAC-adresse : </label><input type="text" size="25" name="mac" id="mac" value=""/><br>
		<i>Husk at vælge MAC-adressen til det netværkskort du bruger til forbindelsen.</i>
	</div><br><br><br>
	<input type="submit" value="Giv midlertidig adgang"/><br>
	<input type="hidden" name="action" value="tempAccess"/>
	</form>
	<a href="http://gahk.dk/intern/gahkhjerne/" target="_blank">Bruger du windows kan du bruge dette program.</a><br>
        <a href="http://gahk.dk/intern/mydata/findMacAddressPC.php" target="_blank">Sådan finder du din MAC-addresse (Guide til Windows)</a><br>
	<a href="http://gahk.dk/intern/mydata/findMacAddressMAC.php" target="_blank">Sådan finder du din MAC-addresse (Guide til MAC)</a><br>
<?php } ?>
	

</body>
<?php
//$db->Close();
insertFooter();
?>