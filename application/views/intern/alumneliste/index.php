<?php
include('../delt.php');
include('config.php');

$headerArgs = array();
if($_GET['m']) $headerArgs['hideMenu'] = 'true';
insertHeader("Alumneliste", "Alumneliste", $headerArgs);
echo '<body onLoad="document.forms.chooseList.typedpassword.focus()">';

include('../adodb5/adodb.inc.php');
$db = ADONewConnection('mysql'); 
$db->Connect('localhost', $username, $password, $database);
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$db->Execute("SET NAMES utf8");

$rs = $db->GetAll("SELECT DISTINCT monthNumber FROM intern_alumne_liste ORDER BY monthNumber DESC");

$lists = reduceArrayArray($rs,'monthNumber');
$listNames = array();
foreach($lists as $monthNumber) {
	array_push($listNames, ucfirst(mn2mstr($monthNumber)));
}

if($_GET['mailAll']) {
    $link = "mailAll.php";
} elseif($_GET['config']) {
    $link = "konfigurer.php";
} elseif($_GET['pylon']) {
	$link = "../pylon/pylon.php";
} else {
    $link = "liste.php";
}
if($_GET['m']) $link .= '?m=true';

?>
<form action="<?php echo $link ?>" method="post" name="chooseList">
<?php

if($_GET["admin"] || $_GET["mailAll"]) {
	echo '<label for="typedpassword">Kodeord : </label><input type="password" size="25" name="typedpassword" id="typedpassword" value=""/><br>';
} else {
	if(atGAHK()) { //user is sitting at GAHK
		echo ' Du er registreret som siddende på GAHK, og koden er ikke nødvendig.';
	} else {
		echo '<label for="typedpassword">Kodeord : </label><input type="password" size="25" name="typedpassword" id="typedpassword" value=""/>';
	}
	echo '<br>';
}

$allPersonsStr = "Alle alumner";
array_push($lists, "allPersons");
array_push($listNames, $allPersonsStr);
$str = "";
if($_GET["allPersons"]) $str = "allPersons";
// Set default list to the current month instead of newest
if($_GET["mailAll"] === 'true' || $_GET["closeNetwork"] === '1') $str = date("m",time())+12*date("Y",time());
?>
<?php
if(!$_GET['config'] && !$_GET['pylon']) {
	echo '<label for="month">Vælg alumneliste : </label>';
	selector("month",$str,$lists,$listNames);
}
?>
	
<br>
<input type="hidden" name="display" value="showCustomList" /><br>
<?php
    if($_GET['config']) {
        $buttonStr = "Gå til konfiguration";
    } elseif($_GET['mailAll']) {
        $buttonStr = "Gå til mail-formular";
    } elseif($_GET['pylon']) {
		$buttonStr = "Gå til pylon-indstillinger";
	} else {
		$buttonStr = "Gå til alumneliste";
    }

    echo '<input type="submit" value="'.$buttonStr.'" /><br>';
?>


<?php if(!($_GET['config'] || $_GET['admin'] || $_GET['mailAll'] || $_GET['pylon'])) { ?>
    <br><br>
    <fieldset style="width:200px;">
    <legend>Vis brugerdefineret liste</legend>

    <?php
    foreach($tableColumnsNormalUser as $var) {
        echo translate($var);
        $checkedStr = 'checked="true"';
        if($_GET['m'] && !in_array($var,array("name","room","phone"))) $checkedStr = '';
        echo '<input type="checkbox" name="tableColumns[]" value="'.$var.'" style="float:right;" '.$checkedStr.'><div style="height: 5px;"></div>';
    }
    ?>
    <br>Vis ind- og udflyttere<input type="checkbox" name="showExtra[]" value="moveInAndOut" <?php if(!$_GET['m']) echo 'checked="true"'; ?> style="float:right;"><div style="height: 5px;"></div>
    Vis plantegning<input type="checkbox" name="showExtra[]" value="floorPlan" checked="true" style="float:right;"><div style="height: 5px;"></div>
    Vis menu<input type="checkbox" name="showExtra[]" value="menu" <?php if(!$_GET['m']) echo 'checked="true"'; ?> style="float:right;"><div style="height: 5px;"></div>
    </fieldset>
<?php } ?>
</form>

</body>
<?php
$db->Close();
insertFooter();
?>