<?php
$expire=time()+3600*24*30*18; //cookies expire in 1½ years
setcookie("name", $_GET["name"], $expire);
setcookie("email", $_GET["email"], $expire);
setcookie("moveInMonth", $_GET["moveInMonth"], $expire);
setcookie("moveInYear", $_GET["moveInYear"]+date("Y"), $expire);
setcookie("doneStudyingMonth", $_GET["doneStudyingMonth"], $expire);
setcookie("doneStudyingYear", $_GET["doneStudyingYear"]+date("Y"), $expire);
setcookie("leaveMonth1", $_GET["leaveMonth1"], $expire);
setcookie("leaveYear1", $_GET["leaveYear1"]+date("Y"), $expire);
setcookie("leaveMonth2", $_GET["leaveMonth2"], $expire);
setcookie("leaveYear2", $_GET["leaveYear2"]+date("Y"), $expire);
setcookie("orlov", $_GET["orlov"], $expire);
$moveYear=$_GET["moveYear"] + date("Y");
$moveInYear=$_GET["moveInYear"] + date("Y");

include '../delt.php';
include 'settings.php';
insertHeader("Kvotient", "Status");

//print_arr($_GET);

$entry = array();
$entry['name'] = $_GET['name'];
$entry['moveMonth'] = $_GET["moveYear"]*12+$_GET["moveMonth"];
$entry['moveInMonth'] = $_GET["moveInYear"]*12+$_GET["moveInMonth"];
$entry['doneStudyingMonth'] = $_GET["doneStudyingYear"]*12+$_GET["doneStudyingMonth"];
$entry['leaveMonth1'] = $_GET["leaveYear1"]*12+$_GET["leaveMonth1"];
$entry['leaveMonth2'] = $_GET["leaveYear2"]*12+$_GET["leaveMonth2"];
if($_GET['orlov']) $entry['leaveOfAbsence'] = 1;
$entry['displayed'] = 1;

$entry['priorities'] = $_GET["prio1"];
for($i = 2; $i <= $numberOfPriorities; $i++) {
    if($_GET["prio".$i] && !in_array($_GET["prio".$i], explode('-', $entry['priorities']))) $entry['priorities'] .= '-'.$_GET["prio".$i];
}

$orlov=$entry['leaveMonth2']-$entry['leaveMonth1'];
$a=$entry['moveMonth']-$entry['moveInMonth']-$orlov;  //$a er antallet af måneder personen har boet på GAHK på det tidspunkt kvotienten udregnes til.
$b=$entry['doneStudyingMonth']-$entry['moveMonth'];  //b er antallet af måneder tilbage af studietiden på det tidspunkt kvotienten udregnes til.
$K=number_format($a*100/(3+$a+$b),3);
$entry['K'] = $K;



include('../adodb5/adodb.inc.php');
$db = ADONewConnection('mysql');
$db->Connect('localhost', $username, $password, $database); //parameter are from delt.php
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$db->Execute("SET NAMES utf8");


date_default_timezone_set("Europe/Copenhagen");
$entry['applyDatetime'] = date("Y-m-d H:i:s");

$db->AutoExecute('intern_kvotient',$entry,'INSERT');
$databaseSuccess = $db->Insert_ID();



$to = "kvotient@gahk.dk";//"nielsoleholck@gmail.com,indstillingen@gahk.dk";
$subject = "Værelsesansøgning - " . $_GET["name"] . " - K = ". $K;

$body = "Ansøgning til værelsesflytning den 1. " . mn2mstr($entry['moveMonth']) . "\n\n".
"Ansøger: ".$_GET["name"].
"\nFlyttede ind på GAHK: ".mn2mstr($entry['moveInMonth']).
"\nForventer at være færdig med studiet: ".mn2mstr($entry['doneStudyingMonth']);

if($_GET['orlov']) {
    $body .= "\nTog på orlov: ".mn2mstr($entry['leaveMonth1']).
    "\nKom hjem fra orlov: ".mn2mstr($entry['leaveMonth2']);
} else {
    $body .= "\nHar ikke været på orlov";
}

$body .= "\n\nKvotient: ".$K."\n";

$prioArray = explode('-',$entry['priorities']);
foreach($prioArray as $i => $value) {
    $body .= "\n" . ($i+1) . ". prioritet: " . $roomDescription[$value];
}

$headers = 'From: interngahk@gahk.dk' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
if ($_GET["email"]) $to = $to . ", " . $_GET["email"];
if (mail($to, $subject, $body, $headers)) {
	$emailSuccess = 1;	
}

if($emailSuccess && $databaseSuccess) {
	greenText("<b>Ansøgningen er sendt til indstillingen samt oprettet i databasen.</b><br>");
	echo('<b>Din ansøgning skulle gerne fremgå af <a href="http://gahk.dk/intern/kvotient/seAnsoegninger.php">denne oversigt.</a></b><br>');
} else {
	if(!$emailSuccess) redText("<br><b>Fejl i afsendelse af email til Indstillingen. Kontakt Indstillingen.</b>");
	if(!$databaseSuccess) redText("<br><b>Der var et problem med at oprette din ansøgning i databasen. Kontakt indstillingen.</b>");
}

insertFooter();
$db->Close();
?>