<?php
include('../delt.php');
include('../adodb5/adodb.inc.php');
if($_GET["password"] === 'mLXAC6V2wf') {
	$db = ADONewConnection('mysqli'); 
	//	$db->debug = true; 
	$db->Connect('localhost', $username, $password, $database);
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$month = date("Y")*12+date("m");
	$lastMonth = $month-1;
	
	$macs1 = $db->GetAll("SELECT DISTINCT mac FROM (intern_alumne JOIN intern_alumne_liste ON intern_alumne_liste.alumne_ID = intern_alumne.ID JOIN intern_alumne_macaddress ON intern_alumne_macaddress.alumne_ID = intern_alumne.ID) WHERE (intern_alumne_liste.monthNumber >= '$lastMonth' AND intern_alumne.networkClosed = 0)");
	
	$macs2 = $db->GetAll("SELECT * FROM intern_alumne_macaddress WHERE alumne_ID = 0");
	
	date_default_timezone_set("Europe/Copenhagen");
	$now = date("Y-m-d H:i:s");
	$macs3 = $db->GetAll("SELECT mac FROM intern_alumne_macaddress_temp WHERE intern_alumne_macaddress_temp.accessUntil >= '$now'");
	$exclude = $db->GetAll("SELECT DISTINCT mac FROM (intern_alumne JOIN intern_alumne_liste ON intern_alumne_liste.alumne_ID = intern_alumne.ID JOIN intern_alumne_macaddress ON intern_alumne_macaddress.alumne_ID = intern_alumne.ID) WHERE (intern_alumne_liste.monthNumber >= '$lastMonth' AND intern_alumne.networkClosed = 1)");

//	print_r($macs3);
	$macs3 = array_diff(reduceArrayArray($macs3, "mac"), reduceArrayArray($exclude, "mac"));
//	print_r($macs3);
//	echo '<br>';
	
	$allMacs = array_unique(array_merge(reduceArrayArray($macs1, "mac"),reduceArrayArray($macs2, "mac"),$macs3));

/*	echo '<pre>';
	print_r($allMacs);
	echo '</pre>';*/
	$separator = "\r\n";
	
	if($allMacs) {
		echo str_replace("-",":",implode($separator,$allMacs));
	}
}
?>