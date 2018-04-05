<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
include '../delt.php';
$access=0;
if($_POST["typedpassword"]===$adminpassword_mailall || $_POST["typedpassword"]===$adminpassword_network) $access=1;

if(!$access) {
    insertHeader("Alumneliste", "Forkert kode");
    redText("Adgang nægtet");
} else {
    include('../adodb5/adodb.inc.php');
    $db = ADONewConnection('mysql');
    //	$db->debug = true;
    $db->Connect('localhost', $username, $password, $database); //parameters are from delt.php
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $db->Execute("SET NAMES utf8");

    ////////////////// GET ALUMNER FROM DATABASE AND CREATE HEADER //////////////////
    $month = $_POST["month"]; //month in int format (Y*12 + m)
    if($month === 'allPersons') {
            $monthStr = 'alle alumner i databasen';
            $rs = $db->GetAll("SELECT * FROM intern_alumne WHERE email != ''");
    } else {
            $monthStr = mn2mstr($month);
			$rs = $db->GetAll("SELECT * FROM intern_alumne JOIN intern_alumne_liste ON intern_alumne_liste.alumne_ID = intern_alumne.ID WHERE (intern_alumne_liste.monthNumber = '$month' AND intern_alumne.email != '')");
    }
    insertHeader("Alumneliste", "Alumneliste for $monthStr");


    $subject = $_POST['sender'] . ' : ' . $_POST['subject'];
    $body = $_POST['body'];

    if($_POST['mode']==='oneMail') {
		$rs_extra = $db->GetAll("SELECT email FROM intern_alumne_emailsubscribers WHERE email != ''");
		$emails = array_merge(reduceArrayArray($rs,"email"), reduceArrayArray($rs_extra,"email"));
        
		$to = implode(',',$emails);
			
        if(mailFormatted($to,$subject,$body)) {
            greenText('Mail sendt');
        } else {
            redText('Mail IKKE sendt');
        }
    } elseif($_POST['mode']==='oneByOne') {
        $counter=0;
        foreach($rs as $alumne) {
            $to = $alumne['email'];
            $body2 = $body;
            
            $params = array('password','alumne_ID','firstName','lastName');
            foreach($params as $p) {
                $body2 = str_replace('{'.$p.'}', $alumne[$p], $body2);
            }
            $counter += mailFormatted($to,$subject,$body2);
        }

        if($counter == count($rs)) {
            greenText($counter.' mails sendt');
        } else {
            redText('Mails IKKE sendt');
        }
    } else {
        redText('Fejl i tilstand.');
    }
    
}



//echo greenText("<br><br>End of php");
insertFooter();
?>