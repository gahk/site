<?php
include('../delt.php');
//print_arr($_GET);

//////////////////// Set up database connection ////////////////////
include('../adodb5/adodb.inc.php');
$db = ADONewConnection('mysql'); 
//	$db->debug = true; 
$db->Connect('localhost', $username, $password, $database); //parameter are from delt.php
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$db->Execute("SET NAMES utf8");

$email = $_GET['email'];
$randCode = $_GET['randCode'];
$rs = $db->GetAll("select * from intern_alumne_pylon WHERE email='$email'");

insertHeader("Pylon", "Email-indstillinger", array('hideMenu'=>1));
//////////////////// Evaluate Post Array ////////////////////
if($rs[0]) {
	if($_GET['action'] === 'removeEmailFromDatabase') {
		$db->Execute("DELETE FROM intern_alumne_pylon WHERE email = '$email'");
		greenText('Email fjernet fra databasen.');
	}
} else {
	redText('Email ikke fundet i databasen.');
}
br(2);

//print_arr($rs);

$rs = $db->GetAll("select * from intern_alumne_pylon WHERE email='$email'"); 
if($email) {
	if($rs[0]) {
		if($randCode === $rs[0]['randCode']) {
			echo $email.'<br>';
			?>
			<form action="admin.php" method="get" name="form_email_update">
				<input type="hidden" name="email" value="<?php echo $email; ?>"/>
				<input type="hidden" name="randCode" value="<?php echo $randCode; ?>"/>
				<input type="hidden" name="action" value="removeEmailFromDatabase"/>
				<br>
				<input type="submit" value="Fjern email fra database"><br> - Du vil herefter ikke modtage flere emails fra Pylon-foreningen.
			</form>
			<?php
		} else {
			redText('<br>Koden passer ikke til email-adressen');
		}
	}
}

insertFooter();
?>