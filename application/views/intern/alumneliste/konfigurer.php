<?php
include('delt.php');
echo '<link type="text/css" href="https://gahk.dk/intern/alumneliste/konfigurer.css" rel="stylesheet"/>';

?>

<div class="pull-right">

        <ul class="nav nav-pills pull-right">

          <li><a href="<?= base_url('nyintern/alumneliste') ?>">Vis liste</a></li>

          <li><a href="<?= base_url('nyintern/alumneliste/update') ?>">Rediger</a></li>

          <li class='active'><a href="<?= base_url('nyintern/alumneliste/configure') ?>">Konfigurer</a></li>

        </ul>

</div>

<?php

if(true) {
    insertHeader("Alumneliste - Konfiguration", "Alumneliste - Konfiguration", $headerArgs);

    include('adodb5/adodb.inc.php');
    $db = ADONewConnection('mysqli');
    $db->Connect('localhost', $username, $password, $database);
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $db->Execute("SET NAMES utf8");

    ///////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////// EVALUATE POST_ARRAY /////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////
    if($_POST['action']==='change') {
        for ( $i = 0; $i < count($_POST['wID']); $i++) {
            $entry = array();
            foreach($_POST as $key => $value) {
                    $entry[$key] = $value[$i];
            }
            $wid = $entry['wID'];
            if($entry['workgroup'] && $wid > 0) {
                $db->AutoExecute('intern_alumne_workgroup',$entry,'UPDATE',"intern_alumne_workgroup.ID = $wid");
            } elseif($entry['workgroup'] && $entry['workgroup']!='(ny)' && $wid == 0) {
                unset($entry['ID']);
                $rs = $db->GetAll("select * from intern_alumne_workgroup");
                if(!in_array($entry['workgroup'],  reduceArrayArray($rs, 'workgroup'))) {
                    $db->AutoExecute('intern_alumne_workgroup',$entry,'INSERT');
                }
            } else {
                $db->Execute("DELETE FROM intern_alumne_workgroup WHERE ID = '".$wid."'");
            }
	}

        for ( $i = 0; $i < count($_POST['cID']); $i++) {
            $entry = array();
            foreach($_POST as $key => $value) {
                    $entry[$key] = $value[$i];
            }
            $cid = $entry['cID'];
            if($entry['cleaning'] && $cid > 0) {
                $db->AutoExecute('intern_alumne_cleaning',$entry,'UPDATE',"intern_alumne_cleaning.ID = $cid");
            } elseif($entry['cleaning'] && $entry['cleaning']!='(ny)' && $cid == 0) {
                unset($entry['ID']);
                $rs = $db->GetAll("select * from intern_alumne_cleaning");
                if(!in_array($entry['cleaning'],  reduceArrayArray($rs, 'cleaning'))) {
                    $db->AutoExecute('intern_alumne_cleaning',$entry,'INSERT');
                }
            } else {
                $db->Execute("DELETE FROM intern_alumne_cleaning WHERE ID = '".$cid."'");
            }
		}
	} elseif($_POST['action']==='change_email_settings_when_new_alumne_created') {
		$entry = array('emailBody' => $_POST['body'], 'sendEmailToNewPersons' => $_POST['sendEmails']);
		$db->AutoExecute('intern_alumne_emailtonew',$entry,'UPDATE',"intern_alumne_emailtonew.ID = 1");
	
    } elseif($_POST['action']==='add_email') {
		$entry = array('email' => $_POST['email_to_add']);
		$db->AutoExecute('intern_alumne_emailsubscribers',$entry,'INSERT');
	} elseif($_POST['action']==='remove_email') {
		$db->Execute("DELETE FROM intern_alumne_emailsubscribers WHERE email = '".$_POST['email_to_delete']."'");
	} elseif($_POST['action']==='change_emailnetworkstatus') {
		$entry = array('body' => $_POST['body1'], 'enabled' => $_POST['enabled1']);
		$db->AutoExecute('intern_alumne_emailnetworkstatus',$entry,'UPDATE'," intern_alumne_emailnetworkstatus.ID = 1");
		$entry = array('body' => $_POST['body2'], 'enabled' => $_POST['enabled2']);
		$db->AutoExecute('intern_alumne_emailnetworkstatus',$entry,'UPDATE'," intern_alumne_emailnetworkstatus.ID = 2");
	}

    ///////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////// ERROR CHECKING ////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////
    $errorStr = "";
    $rsW = $db->GetAll("select * from intern_alumne_workgroup");
    if(count(reduceArrayArray($rsW, 'workgroup')) != count(array_unique(reduceArrayArray($rsW, 'workgroup')))) {
        $errorStr .= 'Samme embedsgruppe optræder flere gange<br><br>';
    }
    $rsC = $db->GetAll("select * from intern_alumne_cleaning");
    if(count(reduceArrayArray($rsC, 'cleaning')) != count(array_unique(reduceArrayArray($rsC, 'cleaning')))) {
        $errorStr .= 'Samme rengøring optræder flere gange<br><br>';
    }
    if(array_sum(reduceArrayArray($rsW, 'w_amount')) != array_sum(reduceArrayArray($rsC, 'c_amount'))) {
        $errorStr .= 'Ikke samme antal på rengøringer som i embedsgrupper<br><br>';
    }
    if($errorStr) redText($errorStr);

    
    ///////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////// FORMS ////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////
    $rs = $db->GetAll("select * from intern_alumne_workgroup ORDER BY workgroup ASC");
    
    //echo '<form action="konfigurer.php" method="post" name="chooseList">';
    echo form_open('nyintern/alumneliste/configure');
    echo '<input type="hidden" name="action" value="change"/>';
    echo '<input type="hidden" name="typedpassword" value="' . $_POST["typedpassword"] . '"/>';
    echo '<input type="submit" value="Foretag ændringer"><br><br>';
    ?>
	Tilføj en ny embedsgruppe eller rengøring: Erstart (ny) i øverste række i den pågældende søjle med det ønskede navn. Tryk derefter på <b>Foretag ændringer</b>.<br>
	Fjern embedsgruppe eller rengøring: Slet teksten i det pågældende felt, og tryk derefter på <b>Foretag ændringer</b>.
	<br><br>
	<?php
	echo '<div style="float:left;">';
    echo '<b>Embedsgrupper</b><br>';
        echo '<table border="1">';
            echo '<th>Embedsgruppe</th>';
            echo '<th>Antal</th>';

            echo '<tr>';
            echo '<input type="hidden" name="wID[]" value="0"/>';
            echo '<td><input type="text" name="workgroup[]" value="(ny)" onFocus="javascript:this.value='."''".'"/></td>';
            echo '<td><input size=5 type="text" name="w_amount[]" value="0"/></td>';
            echo '</tr>';
            $counter = 0;
            foreach($rs as $w) {
                echo '<tr>';
                echo '<input type="hidden" name="wID[]" value="'.$w['ID'].'"/>';
                echo '<td><input type="text" name="workgroup[]" value="'.$w['workgroup'].'"/></td>';
                echo '<td><input size=5 type="text" name="w_amount[]" value="'.$w['w_amount'].'"/></td>';
                echo '</tr>';
                $counter += $w['w_amount'];
            }
            echo '<tr><td>I alt</td><td>'.$counter.'</td></tr>';

        echo '</table>';
    echo '</div>';

    echo '<div style="float:left; padding-left: 20px;">';

        $rs = $db->GetAll("select * from intern_alumne_cleaning ORDER BY cleaning ASC");
        echo '<b>Rengøringer</b><br>';
        echo '<table border="1">';
            echo '<th>Rengøring</th>';
            echo '<th>Antal</th>';

            echo '<tr>';
            echo '<input type="hidden" name="cID[]" value="0"/>';
            echo '<td><input type="text" name="cleaning[]" value="(ny)" onFocus="javascript:this.value='."''".'"/></td>';
            echo '<td><input size=5 type="text" name="c_amount[]" value="0"/></td>';
            echo '</tr>';
            $counter = 0;
            foreach($rs as $c) {
                echo '<tr>';
                echo '<input type="hidden" name="cID[]" value="'.$c['ID'].'"/>';
                echo '<td><input type="text" name="cleaning[]" value="'.$c['cleaning'].'"/></td>';
                echo '<td><input size=5 type="text" name="c_amount[]" value="'.$c['c_amount'].'"/></td>';
                echo '</tr>';
                $counter += $c['c_amount'];
            }
            echo '<tr><td>I alt</td><td>'.$counter.'</td></tr>';

        echo '</table>';
    echo '</div>';
    echo '</form>';
	
	?>
	<div class="contentblock">
	<b>Indstillinger for velkomst-email</b><br>
	Nedenstående mail sendes til nye alumner der oprettes i systemet, hvis Send velkomst-email er sat til Ja.<br>
	Man bruge følgende 'tags': {firstName},{lastName},{alumne_ID},{password},{alumnelistekode}. Disse 'tags' bliver erstattet af den nye alumnes parametre.
	<?php
	$rs = $db->GetAll("select * from intern_alumne_emailtonew");
	?>
	
	<?
	   //<form action="konfigurer.php" method="post" name="email_body">
        echo form_open('nyintern/alumneliste/configure');
    ?>
		<input type="hidden" name="action" value="change_email_settings_when_new_alumne_created"/>
		<?php echo '<input type="hidden" name="typedpassword" value="' . $_POST["typedpassword"] . '"/>'; ?>
		<textarea id="body" name="body" rows="10" cols="55"><?php echo $rs[0]['emailBody']; ?></textarea><br>
		Send velkomst-email: <?php selector('sendEmails',$rs[0]['sendEmailToNewPersons'],array(1,0),array('Ja','Nej'), null); ?><br>
		<input type="submit" value="Foretag ændringer">
	</form>
	
	<br><br><br>
	<b>Email-adresser på ekstra modtagere ved gruppemails</b><br>
	<br>
	Tilføj ny ekstra modtager:<br>
    <?
       //<form action="konfigurer.php" method="post" name="emails">
        echo form_open('nyintern/alumneliste/configure');
    ?>
		<input type="hidden" name="action" value="add_email"/>
		<?php echo '<input type="hidden" name="typedpassword" value="' . $_POST["typedpassword"] . '"/>'; ?>
		<input type="text" name="email_to_add" value="" /><br>
		<input type="submit" value="Tilføj email-adresse">
	</form>
	<br>
	Fjern email-adresse:<br>
	<?
       //<form action="konfigurer.php" method="post" name="emails">
        echo form_open('nyintern/alumneliste/configure');
    ?>
		<input type="hidden" name="action" value="remove_email"/>
		<?php echo '<input type="hidden" name="typedpassword" value="' . $_POST["typedpassword"] . '"/>';
		$rs = $db->GetAll("SELECT email FROM intern_alumne_emailsubscribers WHERE email != ''");
		$emails = reduceArrayArray($rs,"email");
		selector('email_to_delete','',$emails,$emails, null);
		?><br>
		<input type="submit" value="Fjern email-adresse">
	</form>
	
	<br>
	Følgende emails er på listen over ekstra modtagere:<br>
	<?php
		echo (implode('<br>',$emails));
		echo '<br><br><br>';
	?>
	</div>
	<div class="contentblock">
	<b>Indstillinger for email ved lukning/åbning af netværk</b><br>
	<?php
	$rs = $db->GetAll("SELECT * FROM intern_alumne_emailnetworkstatus"); // $rs[0]: closed, $rs[1]: open
	?>
	<?
        //<form action="konfigurer.php" method="post" name="email_body">
        echo form_open('nyintern/alumneliste/configure');
    ?>
		<input type="hidden" name="action" value="change_emailnetworkstatus"/>
		<?php echo '<input type="hidden" name="typedpassword" value="' . $_POST["typedpassword"] . '"/>'; ?>
		Email når netværk lukkes:<br>
		<textarea id="body1" name="body1" rows="10" cols="55"><?php echo $rs[0]['body']; ?></textarea><br>
		Send email når netværk lukkes: <?php selector('enabled1',$rs[0]['enabled'],array(1,0),array('Ja','Nej'), null); ?><br>
		<br>Email når netværk åbnes:<br>
		<textarea id="body2" name="body2" rows="10" cols="55"><?php echo $rs[1]['body']; ?></textarea><br>
		Send email når netværk åbnes: <?php selector('enabled2',$rs[1]['enabled'],array(1,0),array('Ja','Nej'), null); ?><br>
		<input type="submit" value="Foretag ændringer">
	</form>
	</div>
    <?php
    $db->Close();
}

?>


