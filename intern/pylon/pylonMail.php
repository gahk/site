<?php
include '../delt.php';
$access=0;
if($_POST["typedpassword"]===$adminpassword_pylon) $access=1;

//////////////////// Set up database connection ////////////////////
include('../adodb5/adodb.inc.php');
$db = ADONewConnection('mysql'); 
//	$db->debug = true; 
$db->Connect('localhost', $username, $password, $database); //parameter are from delt.php
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$db->Execute("SET NAMES utf8");

if(!$access) {
	insertHeader("Pylon: Indstillinger", "Forkert kode");
	redText("Adgang nægtet");
} else {
	insertHeader("Pylon: Mailsystem", "Pylon: Afsend gruppe-email");
	
	
	///////////////////////////////////////////////////////////////////////////////////
	///////////////////////////// EVALUATE POST ARRAY /////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////
	$rs = $db->GetAll("select * from intern_alumne_pylon_email_settings");
	$from = $rs[0]['mailinglist_from_email'];
	$email_signature = $rs[0]['mailinglist_email_signature'];
	$pyloner = $db->GetAll("select * from intern_alumne_pylon");
	
	if($_POST['action']==='pylon_group_email') {
		$subject = $_POST["subject"];
		$body = $_POST["body"];
		foreach($pyloner as &$py) {
			$link = 'http://gahk.dk/intern/pylon/admin.php?email='.$py["email"]."&randCode=".$py["randCode"];
			$email_signature_personal = str_replace("{link}", $link, $email_signature);
			mailFormatted2($py["email"],$from,$subject,$body."\r\n\r\n------------------------------------------------------------\r\n".$email_signature_personal);
		}
		$str_message = "Email afsendt til ".count($pyloner). " modtagere.";
	}
	
	greenText($str_message.'<br><br>');	
	///////////////////////////////////////////////////////////////////////////////////
	
	?>
	<head>
		<link type="text/css" href="style.css" rel="stylesheet" />
	</head>
	
	
	<div class="contentblock">
	<form action="pylonMail.php" method="post" name="email_signature">
		<input type="hidden" name="action" value="pylon_group_email"/>
		<?php echo '<input type="hidden" name="typedpassword" value="' . $_POST["typedpassword"] . '"/>'; ?>
		<label for="subject"><b>Emne:</b></label><input type="text" name="subject" id="subject" value="" /><br>
		<b>Besked:</b><br>
		<textarea id="body" name="body" rows="6" cols="65"></textarea><br>
		<b>Signatur:</b><br>
		<?php echo $email_signature; ?><br><br>
		<b>Afsender-adresse: </b><?php echo $from; ?><br>
		<b>Modtagere: </b>Der vil blive sendt mails til <?php echo count($pyloner); ?> email-adresser.<br><br>
		<input type="submit" value="Afsend gruppe-email">
	</form>
<?php
}
insertFooter();
?>