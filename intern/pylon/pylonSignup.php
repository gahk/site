<?php
include('../delt.php');
insertHeader("Pylon", "Pylon");

//////////////////// Set up database connection ////////////////////
include('../adodb5/adodb.inc.php');
$db = ADONewConnection('mysql'); 
//	$db->debug = true; 
$db->Connect('localhost', $username, $password, $database); //parameter are from delt.php
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$db->Execute("SET NAMES utf8");

$email = $_GET['email'];

if($email) {
	$rs = $db->GetAll("select * from intern_alumne_pylon WHERE email = '$email'");
	if($rs[0] == null) {
		$pylon = array("randCode" => genRandomString(16),"email" => $email);
		$db->AutoExecute('intern_alumne_pylon',$pylon,'INSERT');
		greenText($email.' tilføjet databasen.');
	} else {
		redText($email.' findes allerede i databasen.');
	}
}
br(2);
?>
	<b>Tilmeld Pylon-foreningens mailingliste</b>
	<form id="form_add_person" action="pylonSignup.php" method="get">
		<label for="email">Email-adresse: </label><input type="text" name="email" id="email" value="" /><br>
		<input type="submit" value="Tilmeld">
	</form>
<?php
insertFooter();
?>