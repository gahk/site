<?php
include '../delt.php';
//print_arr($_POST);
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
	insertHeader("Pylon: Indstillinger", "Pylon: Indstillinger");
	include 'head.php';
	
	///////////////////////////////////////////////////////////////////////////////////
	///////////////////////////// EVALUATE POST ARRAY /////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////
	
	if($_POST['action']==='change_pylon_email_settings') {
			$db->AutoExecute('intern_alumne_pylon_email_settings',$_POST,'UPDATE',"ID = 1");
			$str_message = 'Email-indstillinger opdateret.';
	}
	
	if($_POST['action']==='removePerson') {
			$pylonID = $_POST['removeByEmail'];
			$rs = $db->GetAll("select * from intern_alumne_pylon WHERE ID = '$pylonID'");
			$str_message = $rs[0]['email'].' fjernet fra databasen.';
			$db->Execute("DELETE FROM intern_alumne_pylon WHERE ID = '$pylonID'");
	}
	///////////////////////////////////////////////////////////////////////////////////
	///////////////////////////// EVALUATE POST ARRAY /////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////
	greenText($str_message.'<br><br>');
	
	
	?>
	<fieldset>
	<legend>Signatur i gruppemails</legend>
	<div class="contentblock">
	Signatur i emails - Følgende vil stå i bunden af de gruppe-emails der sendes:
	<?php
	$rs = $db->GetAll("select * from intern_alumne_pylon_email_settings");
	?>
	<form action="pylon.php" method="post" name="email_signature">
		<input type="hidden" name="action" value="change_pylon_email_settings"/>
		<?php echo '<input type="hidden" name="typedpassword" value="' . $_POST["typedpassword"] . '"/>'; ?>
		<textarea id="body" name="mailinglist_email_signature" rows="6" cols="65"><?php echo $rs[0]['mailinglist_email_signature']; ?></textarea><br>
		Fra (email): <input type="text" name="mailinglist_from_email" value="<?php echo $rs[0]['mailinglist_from_email']; ?>"/><br>
		<input type="submit" value="Foretag ændringer">
	</form>
	</fieldset>
	<br><br>
	
	
	<fieldset>
	<legend>Indstillinger for email til alumner der fjernes fra alumnelisten</legend>
	<div class="contentblock">
	Nedenstående mail sendes til alumner der fjernes fra en liste, hvis "Send Pylon-email" er sat til Ja.<br>
	Man bruge følgende 'tags': {firstName},{lastName},{alumne_ID},{password},{alumnelistekode}. Disse 'tags' bliver erstattet af alumnens parametre.
	<?php
	$rs = $db->GetAll("select * from intern_alumne_pylon_email_settings");
	?>
	<form action="pylon.php" method="post" name="email_body">
		<input type="hidden" name="action" value="change_pylon_email_settings"/>
		<?php echo '<input type="hidden" name="typedpassword" value="' . $_POST["typedpassword"] . '"/>'; ?>
		<textarea id="moveout_emailBody" name="moveout_emailBody" rows="20" cols="95"><?php echo $rs[0]['moveout_emailBody']; ?></textarea><br>
		Emne: <input type="text" name="moveout_emailSubject" value="<?php echo $rs[0]['moveout_emailSubject']; ?>"/><br>
		Fra: <input type="text" name="moveout_emailFrom" value="<?php echo $rs[0]['moveout_emailFrom']; ?>"/><br>
		Send Pylon-email ved udflytning: <?php selector('moveout_sendEmail',$rs[0]['moveout_sendEmail'],array(1,0),array('Ja','Nej')); ?><br>
		<input type="submit" value="Foretag ændringer">
	</form>
	</fieldset>
	<br><br>

	<fieldset>
	<legend>Liste over pyloner</legend>
	<?php
	////////////////// FORMAT DATA FOR TABLE //////////////////
	$pyloner = $db->GetAll("select * from intern_alumne_pylon");
	
	foreach($pyloner as &$py) {
		$py["name"] = $py["firstName"]." ".$py["lastName"]; //create name from firstName and lastName
		$py["link"] = '<a target="_blank" href="admin.php?email='.$py["email"]."&randCode=".$py["randCode"].'">link</a>';
	}
	unset($py);
	$pyloner = sortArray($pyloner,"name");
	
	$tableColumns = array("email","link");
	createDataTable($pyloner,$tableColumns);
	?>
	</fieldset>
	<br><br>
	
	<fieldset><legend>Fjern Pylon fra databasen</legend>
		<form id="form_remove_person" action="pylon.php" method="post">
			<input type="hidden" name="action" value="removePerson"/>
			<input type="hidden" name="typedpassword" value="<?php echo $_POST["typedpassword"]; ?>"/>
			<label for="removeByEmail">Email: </label><?php selector("removeByEmail",1,reduceArrayArray($pyloner,'ID'),reduceArrayArray($pyloner,'email')); ?><br>
			<label for="removeByEmailCheckBox">Godkend fjernelse</label><input type="checkbox" name="removeByEmailCheckBox" id="removeByEmailCheckBox" value="yes" unchecked><br>
			<input type="submit" value="Fjern fra databasen">
		</form>
	</fieldset>	
	
	<?php
}
insertFooter();
?>