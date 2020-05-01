<?php
require_once('delt.php');

//////////////////// Set up database connection ////////////////////
include('adodb5/adodb.inc.php');
$db = ADONewConnection('mysql');
//	$db->debug = true;
$db->Connect('localhost', $username, $password, $database); //parameters are from delt.php
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$db->Execute("SET NAMES utf8");


//////////////////// Figure out which list to show ////////////////////
$rs = $db->GetRow("SELECT DISTINCT monthNumber FROM intern_alumne_liste ORDER BY monthNumber DESC");
$month = $rs["monthNumber"];


include 'config.php';

////////////////// GET ALUMNER FROM DATABASE //////////////////
$alumner = $db->GetAll("SELECT * FROM intern_alumne JOIN intern_alumne_liste ON intern_alumne_liste.alumne_ID = intern_alumne.ID WHERE intern_alumne_liste.monthNumber = '$month'");
	
////////////////// FORMAT DATA FOR TABLE //////////////////
$rs = $db->GetAll("select * from intern_alumne_workgroup ORDER BY workgroup ASC");
$correctWorkgroups = reduceArrayArray($rs,"workgroup");
$rs = $db->GetAll("select * from intern_alumne_cleaning ORDER BY cleaning ASC");
$correctCleanings = reduceArrayArray($rs,"cleaning");

foreach($alumner as &$al) {
	$al["name"] = $al["firstName"]." ".$al["lastName"]; //create name from firstName and lastName
}
unset($al);
$alumner = sortArray($alumner,"name");
	
$alumnerFormattedToTable = $alumner;
foreach($alumnerFormattedToTable as &$al) {
	$al["fylgje"] = shortenName($al["fylgje"], null); //shorten name of fylgje
	$al["room"] = leadingZero($al['room']);
	if (isset($roomFloor[$al['room']])) {
		$al["room"] = $al["room"] . " - " . $roomFloor[$al['room']];
	}

	$al["birthday"] = date("d/m/Y",strtotime($al["birthday"]));
    $al["moveInDay"] = date("d/m/Y",strtotime($al["moveInDay"]));
}
unset($al);
	
	
////////////////// MAKE TABLE //////////////////
$alumnerFormattedToTable = sortArray($alumnerFormattedToTable, "name");

//createDataTable($alumnerFormattedToTable,$tableColumns[$access],$month);

echo '{"alumni":[';
$started = false;

foreach ($alumnerFormattedToTable as $alumnum) {
	if ($started) {
		echo ",";
	}
	echo "{";
	echo '"name":"'.$alumnum["name"].'",';
	echo '"room":"'.$alumnum["room"].'",';
	echo '"workGroup":"'.$alumnum["workgroup"].'",';
	echo '"cleaning":"'.$alumnum["cleaning"].'",';
	echo '"parent":"'.$alumnum["fylgje"].'",';
	echo '"birthday":"'.$alumnum["birthday"].'",';
	echo '"moveInDate":"'.$alumnum["moveInDay"].'",';
	echo '"study":"'.$alumnum["study"].'",';
	echo '"phone":"'.$alumnum["phone"].'",';
	echo '"email":"'.$alumnum["email"].'",';
	echo '"id":"'.$alumnum["alumne_ID"].'"';
	echo "}";
	$started = true;
}

echo ']}';

$db->Close();
//echo greenText("<br><br>End of php");
//insertFooter();
?>

