<?php
include '../delt.php';

$access=0;
if($_POST["typedpassword"]===$userpassword || (!$_POST["typedpassword"] && atGAHK())) $access=1;			//normal user - normal display
if($_POST["typedpassword"]===$adminpassword_indstillingen) $access=2;		//admin indstillingen
if($_POST["typedpassword"]===$adminpassword_inspection) $access=3;		//admin inspektionen
if($access > 1 && $_POST["month"]==="allPersons") $access=1; //no admin access in this mode

if($access && $_GET['m'] && $_POST["month"]!='allPersons') {
    //enable mobile view mode if m && not allPersons mode.
    $m = true;
    $access = 1; //no admin access in this view mode
}


//////////////////// Set up database connection ////////////////////
include('../adodb5/adodb.inc.php');
$db = ADONewConnection('mysql');
//	$db->debug = true;
$db->Connect('localhost', $username, $password, $database); //parameters are from delt.php
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$db->Execute("SET NAMES utf8");


//////////////////// Figure out which list to show ////////////////////
if($_GET["mostRecentList"] || !$_POST['month']) {
	$rs = $db->GetRow("SELECT DISTINCT monthNumber FROM intern_alumne_liste ORDER BY monthNumber DESC");
	$month = $rs["monthNumber"];
} else {
	$month = $_POST["month"]; //month in int format (Y*12 + m)
}


include 'config.php';


//////////////////// If in normal viewing mode, make sure that sorting after dates work ////////////////////
if($access == 1) {
    $tableSortOptions = array();
    foreach($tableColumns[1] as $t) {
        array_push($tableSortOptions,sort_keyword($t));
    }
    $sortStr = '"aoColumns": ['.implode(",",$tableSortOptions).'],';
}


//////////////////// Trim the post array ////////////////////
foreach($_POST as &$var) { 
	if(!is_array($var)) {
		$var = trim($var);
	} else {
		foreach($var as &$element) {
			$element = trim($element);
		}
		unset($element);
	}
}
unset($var);



//////////////////// Evaluate post array and make changes to the database if needed ////////////////////
if($access==2 || $access==3) include('evalPostArray.php');



//////////////////// Header ////////////////////
if($month === 'allPersons') {
	$monthStr = "alle alumner i databasen";
} else {
	$monthStr = mn2mstr($month);
}

$headerArgs = array();
if($_POST['action'] === 'showCustomList' && !in_array("menu",$_POST['showExtra'])) $headerArgs['hideMenu'] = 'true';
if($m) $headerArgs['hideMenu'] = 'true';

if(!$access) {
    if($_GET['mostRecentList'] && $_POST["typedpassword"]=="") {
        header( 'Location: http://gahk.dk/intern/alumneliste' ) ;
    } else {
        header( 'Location: http://gahk.dk/intern/alumneliste/?errorMessage=Adgang nægtet. Forkert kode' ) ;
    }
} else {
	insertHeader("Alumneliste", "Alumneliste for ".$monthStr, $headerArgs);
}


//////////////////// Stylesheets and JavaScript ////////////////////
include 'head.php';
?>
<body onload="document.getElementsByTagName('input')[0].focus();" ></body>
<?php

if($access) {
        ////////////////// GET ALUMNER FROM DATABASE //////////////////
	if($month === "allPersons") {
		$alumner = $db->GetAll("SELECT * FROM intern_alumne");
	} else {
		$alumner = $db->GetAll("SELECT * FROM intern_alumne JOIN intern_alumne_liste ON intern_alumne_liste.alumne_ID = intern_alumne.ID WHERE intern_alumne_liste.monthNumber = '$month'");
	}
	
	
	////////////////// ERROR CHECKING //////////////////
	if($access == 2) {
		$errors = array();

                ///// Checking if workgroups in this month fit with configuration
                $workgroups = $db->GetAll("SELECT * FROM intern_alumne_workgroup ORDER BY workgroup ASC");
                $listCountWorkgroups = array_count_values(reduceArrayArray($alumner,'workgroup'));
                foreach($workgroups as $w) {
                    if($w['w_amount']) {
                        if(!$listCountWorkgroups[$w['workgroup']]) {
                            array_push($errors, $w['workgroup'].' har ingen medlemmer. Skal have '.$w['w_amount'].' medlem(mer).');
                        } elseif($w['w_amount'] != $listCountWorkgroups[$w['workgroup']]) {
                            array_push($errors, $w['workgroup'].' skal have ' . $w['w_amount'] . ' medlem(mer). Gruppen har '.$listCountWorkgroups[$w['workgroup']]. ' medlem(mer).');
                        }
                    }
                }
                foreach($listCountWorkgroups as $w => $amount) {
                    if(!in_array($w, reduceArrayArray($workgroups, 'workgroup'))) {
                            array_push($errors, $w.' findes ikke i databasen.');
                    }
                }

                ///// Checking if cleanings in this month fit with configuration
                $cleanings = $db->GetAll("SELECT * FROM intern_alumne_cleaning ORDER BY cleaning ASC");
                $listCountCleanings = array_count_values(reduceArrayArray($alumner,'cleaning'));
                foreach($cleanings as $c) {
                    if($c['c_amount']) {
                        if(!$listCountCleanings[$c['cleaning']]) {
                            array_push($errors, $c['cleaning'].' har ingen medlemmer. Skal have '.$c['c_amount'].' medlem(mer).');
                        } elseif($c['c_amount'] != $listCountCleanings[$c['cleaning']]) {
                            array_push($errors, $c['cleaning'].' skal have ' . $c['c_amount'] . ' medlem(mer). Rengøringen har '.$listCountCleanings[$c['cleaning']]. ' medlem(mer).');
                        }
                    }
                }
                foreach($listCountCleanings as $c => $amount) {
                    if(!in_array($c, reduceArrayArray($cleanings, 'cleaning'))) {
                            array_push($errors, $c.' findes ikke i databasen.');
                    }
                }

		$nrAl = count($alumner);
		$nrUniqueAl = count(array_unique(reduceArrayArray($alumner,"alumne_ID")));
		if($nrAl != $nrUniqueAl) array_push($errors,"En eller flere alumner optræder flere gange i denne liste.");
		
		foreach($alumner as $a) {
			foreach($alumner as $a2) {
				if($a2['room'] == $a['room'] && $a['alumne_ID'] != $a2['alumne_ID']) array_push($errors,"Flere har værelse ".$a['room']);
			}
		}
		
		if($errors) {
			echo '<br><fieldset>';
			echo '<legend>Advarsler</legend>';
			redText(implode('<br>',array_unique($errors)));
			echo '</fieldset>';
		}
	}
	
	
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
	
		if($access == 1) {			//normal user access
			$al["fylgje"] = shortenName($al["fylgje"],null); //shorten name of fylgje
			$al["room"] = leadingZero($al['room']). " - " . $roomFloor[$al['room']];
			if($al["email"]) $al["email"] = '<a href="mailto:'.$al["email"].'">'.$al["email"].'</a>';
                        $al["birthday"] = date("d/m/Y",strtotime($al["birthday"]));
                        $al["moveInDay"] = date("d/m/Y",strtotime($al["moveInDay"]));
                        $al["phone"] = '<a href="tel:'.$al["phone"].'">'. $al["phone"]. '</a>';
	
		} elseif($access == 2) {	//indstillingen access
			$al["room"] = leadingZero($al['room']);

                        $workgroups = $correctWorkgroups; // if workgroup doesn't exist, the current value is added here
                        $workgroupsDisp = $correctWorkgroups;
                        if(!in_array($al['workgroup'],$workgroups)) {
                            array_push($workgroups,$al['workgroup']);
                            array_push($workgroupsDisp,'??? '.$al['workgroup']);
                        }
                        $al['workgroup'] = selector('workgroup[]',$al['workgroup'],$workgroups,$workgroupsDisp,array('returnAsString'=>'true'));

                        $cleanings = $correctCleanings; // if workgroup doesn't exist, the current value is added here
                        $cleaningsDisp = $correctCleanings;
                        if(!in_array($al['cleaning'],$cleanings)) {
                            array_push($cleanings,$al['cleaning']);
                            array_push($cleaningsDisp,'??? '.$al['cleaning']);
                        }
                        $al['cleaning'] = selector('cleaning[]',$al['cleaning'],$cleanings,$cleaningsDisp,array('returnAsString'=>'true'));


			foreach($al as $varName => $value) {
				if(!in_array($varName,array("name","ID","alumne_ID","workgroup","cleaning"))) {
					$al[$varName] = '<input type="text" size="10" name="'.$varName.'[]" value="'.$value.'"/>';
				} elseif($varName==="alumne_ID") {
					$al[$varName] = $value.'<input type="hidden" name="alumne_ID[]" value="'.$al["alumne_ID"].'"/>';
				}
			}
		
		} elseif($access == 3) {	//inspektionen access
			$al["networkClosed"] = selector("networkClosed[]",$al["networkClosed"],array(0,1),array("Åbent","NETVÆRK LUKKET"),array('returnAsString'=>'true'));
			$al["networkClosedDetails"] = '<input type="text" size="18" name="networkClosedDetails[]" value="'.$al["networkClosedDetails"].'"/>';
			$al["alumne_ID"] = $al["alumne_ID"].'<input type="hidden" name="alumne_ID[]" value="'.$al["alumne_ID"].'"/>';
		}
	}
	unset($al);
	
	
	////////////////// MAKE TABLE //////////////////
	$alumnerFormattedToTable = sortArray($alumnerFormattedToTable, "name");
	if($access == 2 || $access == 3) {
		createDataTable($alumnerFormattedToTable,$tableColumns[$access],$month);
	} else {
                if(atGAHK()) echo 'Du sidder på GAHKs netværk, og behøvede derfor ikke en kode. Hvis du vil have adgang til listen udefra skal du bruge koden : '.$userpassword;
		createDataTable($alumnerFormattedToTable,$tableColumns[$access]);
	}
	
	
	////////////////// WHO MOVED IN AND OUT SINCE LAST MONTH //////////////////
        if(in_array("moveInAndOut",$showExtra[$access])) {
            $rsLastMonth = $db->GetAll("SELECT alumne_ID,firstName,lastName FROM intern_alumne JOIN intern_alumne_liste ON intern_alumne_liste.alumne_ID = intern_alumne.ID WHERE intern_alumne_liste.monthNumber = '".($month-1)."'");
            $rsThisMonth = $db->GetAll("SELECT alumne_ID,firstName,lastName FROM intern_alumne JOIN intern_alumne_liste ON intern_alumne_liste.alumne_ID = intern_alumne.ID WHERE intern_alumne_liste.monthNumber = '$month'");

            if($rsLastMonth && $rsThisMonth && $access!=3) {
                    $movedIn = array_diff(reduceArrayArray($rsThisMonth,"alumne_ID"),reduceArrayArray($rsLastMonth,"alumne_ID"));
                    $movedOut = array_diff(reduceArrayArray($rsLastMonth,"alumne_ID"),reduceArrayArray($rsThisMonth,"alumne_ID"));

                    echo "<br><br><br><b>Følgende person(er) er flyttet ind på GAHK 1. ".$monthStr.":</b><br>";
                    foreach($movedIn as $key => $value) {
                            echo $rsThisMonth[$key]['firstName'] . " " . $rsThisMonth[$key]['lastName'] . "<br>";
                    }
                    echo "<br><b>Følgende person(er) er flyttet ud af GAHK 1. ".$monthStr.":</b><br>";
                    foreach($movedOut as $key => $value) {
                            echo $rsLastMonth[$key]['firstName'] . " " . $rsLastMonth[$key]['lastName'] . "<br>";
                    }
            }
        }

	if(in_array("floorPlan",$showExtra[$access])) echo '<br><br><img src="../kvotient/plantegning.png" alt="Placering af værelser"/>';
}

if($access==2) {
	include('formsAndSubmitButtons.php');
}

if($access) {
	echo '<br><br><i>Lavet af Niels Ole Holck 2010-2011 - nielsoleholck@gmail.com</i>';
}


$db->Close();
//echo greenText("<br><br>End of php");
insertFooter();
?>
