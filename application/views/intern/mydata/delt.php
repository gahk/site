<?php

$adminpassword_indstillingen = "Numedtinder"; //password for indstillingen
$adminpassword_network = "9e51da23816902684d0bda9f32c222dc5985fc2e2384db0488170090fb5a50eb"; //password for netværksgruppen (brotherc)
$adminpassword_inspection = "blomsterbørn"; //password for inspektionen & køkkengruppe
$adminpassword_mailall = "abemad"; //password for mail all site
$adminpassword_forbrug = "el"; //password for entering electricity consumption
$adminpassword_handbook = "disko";
$adminpassword_pylon = "annebo";
$userpassword = "ymer"; //password for other users



//SQL connection stuff
$username="gahk_dk";
$password="keldogfrederik";
$database="gahk_dk";
//SQL connection stuff



function atGAHK() {
    $gahkip = array("192.38.116.242","192.38.116.243","192.38.116.244","192.38.116.245","192.38.116.246");

    if(in_array($_SERVER['REMOTE_ADDR'],$gahkip)) {
        return true;
    } else {
        return false;
    }
}



$roomFloor = array();
$roomSide = array();
$roomDetails = array();
$roomDescription = array();
$i=0;
$roomOnFloor = 0;
$room_number = array();
while($i<=61) {
  $roomOnFloor++;
	if($i >=1 && $i <= 8) {
    if($i == 1) { $roomOnFloor = 1;}
		$roomFloor[$i] = "stuen";
		$roomSide[$i] = "mod gaden";
    $room_number[$i] = sprintf("%03d", $roomOnFloor);
	} elseif($i == 9 || $i == 10) {
		$roomFloor[$i] = "stuen";
		$roomSide[$i] = "mod gården";
		if($i == 9) $roomDetails[$i] = "(røvhullet)";
    $room_number[$i] = sprintf("%03d", $roomOnFloor);
	} elseif($i >= 11 && $i <=19) {
		$roomFloor[$i] = "1. sal";
		$roomSide[$i] = "mod gaden";
    if($i == 11) { $roomOnFloor = 1;}
    $room_number[$i] = sprintf("%03d", $roomOnFloor+100);
	} elseif($i >= 20 && $i <=24) {
		$roomFloor[$i] = "1. sal";
		$roomSide[$i] = "mod gården";
    $room_number[$i] = sprintf("%03d", $roomOnFloor+100);
	} elseif($i >= 25 && $i <=33) {
		$roomFloor[$i] = "2. sal";
		$roomSide[$i] = "mod gaden";
    if($i == 25) { $roomOnFloor = 1;}
    $room_number[$i] = sprintf("%03d", $roomOnFloor+200);
	} elseif($i >= 34 && $i <=38) {
		$roomFloor[$i] = "2. sal";
		$roomSide[$i] = "mod gården";
    $room_number[$i] = sprintf("%03d", $roomOnFloor+200);
	} elseif($i >= 39 && $i <=47) {
		$roomFloor[$i] = "3. sal";
		$roomSide[$i] = "mod gaden";
    if($i == 39) { $roomOnFloor = 1;}
    $room_number[$i] = sprintf("%03d", $roomOnFloor+300);
	} elseif($i >= 48 && $i <=52) {
		$roomFloor[$i] = "3. sal";
		$roomSide[$i] = "mod gården";
    $room_number[$i] = sprintf("%03d", $roomOnFloor+300);
	} elseif($i >= 53 && $i <=56) {
		$roomFloor[$i] = "4. sal";
		$roomSide[$i] = "mod gaden";
		if($i == 53 || $i == 56) {
			$roomDetails[$i] = "(atelierværelse)";
		} elseif($i == 55) {
			$roomDetails[$i] = "(fængslet)";
		} elseif($i == 54) {
			$roomDetails[$i] = "(arresten)";
		}
    	if($i == 53) { $roomOnFloor = 1;}
    	$room_number[$i] = sprintf("%03d", $roomOnFloor+400);
	} elseif($i >= 57 && $i <=61) {
		$roomFloor[$i] = "4. sal";
		$roomSide[$i] = "mod gården";
		$roomDetails[$i] = "(hemseværelse)";
    $room_number[$i] = sprintf("%03d", $roomOnFloor+400);
	}
	
	if($i > 0) {
		$roomDescription[$i] = trim("Værelse " . $room_number[$i] . " - " . $roomFloor[$i] . " " . $roomSide[$i]);
		if (isset($roomDetails[$i])) {
			$roomDescription[$i] = $roomDescription[$i] . " " . $roomDetails[$i];
		}
	} elseif($i == 0) {
		$roomDescription[$i] = "Intet valgt";
	}
	$i++;
}

$monthName = array(1=> "Januar", "Februar", "Marts", 
"April", "Maj", "Juni", "Juli", "August", 
"September", "Oktober", "November", "December");

function greenText($str) {
	echo '<font color="green">'.$str.'</font>';
}
function redText($str) {
	echo '<font color="red">'.$str.'</font>';
}

function reduceArrayArray($arrayArray,$key) { //Takes all values from a given key in all arrays that are in an array and produces a new array (1-dimensional) with keys 0,1,2... and all the values
	$arrayOut = array();
	foreach($arrayArray as $arr) {
		array_push($arrayOut, $arr[$key]);
	}
	return $arrayOut;
}

function br($nrOfBreaks) {
	echo str_repeat("<br>", $nrOfBreaks);
}

function insertHeader($browsertitle, $titleOnPage, $args) {
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
	echo '<html>';
	echo '<head>';
	echo "<title>GAHK - $browsertitle</title>";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
	echo '<link rel="stylesheet" type="text/css" href="https://www.gahk.dk/intern/menu/menu_style.css" >';
	echo '</head>';
        if($args['menuPath']) {
            $menuPath = $args['menuPath'];
        } else {
            $menuPath = "../menu/menu.php";
        }

	if(!$args['hideMenu']) {
            //include($menuPath);
            echo '<br>';
        }
        
        if(!$args['hideTitleOnPage']) {
            echo '<p>';
            echo '<font size="6">';
            echo "$titleOnPage<br>";
            echo '</font>';
            echo '</p>';
        }

        if($_GET['errorMessage']) {
            redText($_GET['errorMessage']);
            br(2);
        }
}

function insertFooter() {
	echo '</html>';
}

function print_arr($arr) {
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

function selector($varName,$defaultValue,$values,$dispValues,$args) {
	$str = "";
	if(count($dispValues)==count($values)) {
		$str .= '<select name="' . $varName . '" id="'.$varName.'"';
                if($args['onchange']) {
                    $str .= ' onchange="'.$args['onchange'].'"';
                }
                $str .= '><br>';
		foreach($values as $i => $value) {
			$str .= '<OPTION VALUE="' . $value . '"';
			if($value == $defaultValue) $str .= " SELECTED";
			$str .= ">" . $dispValues[$i] . "\n";
		}
		$str .= "</SELECT>";	
	} else {
		$str .= "<br>Error in selector $varName: Not same number of display-values and values<br>";
	}
	if($args['returnAsString']) {
		return $str;
	} else {
		echo $str;
	}
}

function numberArray($valStart,$valEnd) {
	$arr = array();
	for($i=$valStart; $i <= $valEnd; $i++) {
		array_push($arr, $i);
	}
	return $arr;
}

function sortArray($arr,$subkey) {
	foreach($arr as $k=>$v) {
		$b[$k] = strtolower($v[$subkey]);
	}
	asort($b);
	foreach($b as $key=>$val) {
		$c[] = $arr[$key];
	}
	return $c;
}

function leadingZero($num) {
	if ((int)$num < 10) {
		return "00".(int)$num;
	} elseif ((int)$num < 100) {
		return "0".(int)$num;
	} else {
		return (int)$num;
	}
}

function shortenName($firstName,$lastName) {
	if(count(func_get_args()) == 2) {
		$name = $firstName . " ";
		$lastNames = explode(" ",$lastName);
		foreach($lastNames as $n) {
			$name .= $n . " ";
		}
		return trim($name);
	} else {
		$name = "";
		$names = explode(" ",$firstName);
		foreach($names as $key => $n) {
			if($key == 0 || $key == 1) {
				$name .= ucfirst($n) . " ";
			} else {
				$name .= strtoupper($n[0]) . " ";
			}
		}
		return trim($name);
	}
}

function genRandomString($length) {
    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
    $string = "";
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}



///////// MONTHNUMBER FUNCTIONS /////////
// The monthnumber format works like this: mn = 12*year+month
function mn2mstr($monthNumber) {
	$monthName = array(1=> "Januar", "Februar", "Marts", 
		"April", "Maj", "Juni", "Juli", "August", 
		"September", "Oktober", "November", "December");

	$Y = (int)(($monthNumber-1)/12);
    $m = $monthNumber % 12;
	if ($m == 0) { 
		$m = 12; 
	}
	
	$str = $monthName[$m] . " " . $Y;
	return strtolower($str);
}

function mn2m($monthNumber) {
    $m = $monthNumber % 12;
	if ($m == 0) $m = 12;
	return $m;
}

function mn2y($monthNumber) {
	$Y = (int)(($monthNumber-1)/12);
	return $Y;
}
///////// MONTHNUMBER FUNCTIONS /////////



function translate($word) {
	$translations = array("name" => "Navn", "firstName" => "Fornavn", "lastName" => "Efternavn", "room" => "Værelse", "workgroup" => "Embedsgruppe", "cleaning" => "Rengøring", "fylgje" => "Fylgje", "birthday" => "Fødselsdag", "moveInDay" => "Indflyttet", "study" => "Studie", "phone" => "Telefon", "email" => "Email", "alumne_ID" => "Unikt alumne-ID", "networkClosed" => "Netværksstatus", "networkClosedDetails" => "Netværk lukket - detaljer", "memberType" => "Medlemstype", "randCode" => "Tilfældig kode");
	if($translations[$word]) {
		return $translations[$word];
	} else {
		return $word;
	}
}

function createDataTable($dataArray,$tableColumns,$updateMonth = 0) {
	if($updateMonth) {
		//echo '<form action="liste.php" method="post"><br>';
		echo '<input type="hidden" name="action" value="editList"/>';
		echo '<input type="hidden" name="typedpassword" value="' . $_POST["typedpassword"] . '"/>';
		echo '<input type="hidden" name="month" value="' . $updateMonth . '"/>';
	}

	echo '<style type="text/css">table {font-size: 80%;}</style>'; //sets the font size of the table

	echo '<div id="container">';
		echo '<table cellpadding="0" cellspacing="0" border="1" class="display" id="standardTable">';

			echo '<thead><tr>';
			foreach($tableColumns as $columnName) {
				echo "<th>".ucfirst(translate($columnName))."</th>";
			}
			echo '</tr></thead>';

			echo '<tbody>';
			foreach ($dataArray as $entry) {
				echo '<tr>';
				foreach($tableColumns as $varName) {
					echo "<td>".$entry[$varName]."</td>";
				}
				echo '</tr>';
			}
			echo '</tbody>';

			echo '<tfoot><tr>';
			foreach($tableColumns as $columnName) {
				echo "<th>".ucfirst(translate($columnName))."</th>";
			}
			echo '</tr></tfoot>';

		echo '</table>';
	echo '</div>';

	if($updateMonth) {
		echo '<br><br><input type="submit" value="Foretag ændringer af alumneliste ovenfor"/>';
		echo '</form>';
	}
}


///////// EMAIL FUNCTIONS /////////
function mailFormatted($to,$subject,$body) {
    $subject = '=?UTF-8?B?'.base64_encode($subject).'?=';
    $headers = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n";
    $headers .= 'From: interngahk@gahk.dk' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

    if(mail($to, $subject, $body, $headers)) {
        return 1;
    } else {
        return 0;
    }
}

function mailFormatted2($to,$from,$subject,$body) {
    $subject = '=?UTF-8?B?'.base64_encode($subject).'?=';
    $headers = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n";
    $headers .= 'From: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion();

    if(mail($to, $subject, $body, $headers)) {
        return 1;
    } else {
        return 0;
    }
}


function replaceTagsWithValues($text, $alumne) {
	global $userpassword;
	$params = array('password','alumne_ID','firstName','lastName','networkClosedDetails');
    foreach($params as $p) {
        $text = str_replace('{'.$p.'}', $alumne[$p], $text);
    }
	$text = str_replace('{alumnelistekode}',$userpassword, $text);
	return $text;
}


function replaceTagsWithValuesAndPasswordAsOtherParam($text, $alumne, $password) {
	global $userpassword;
	$params = array('alumne_ID','firstName','lastName','networkClosedDetails');
    foreach($params as $p) {
        $text = str_replace('{'.$p.'}', $alumne[$p], $text);
    }

	$text = str_replace('{password}', $password, $text);
	
	$text = str_replace('{alumnelistekode}',$userpassword, $text);
	return $text;
}


///////// EMAIL FUNCTIONS /////////





function strposall($haystack,$needle){
/**
 * strposall
 *
 * Find all occurrences of a needle in a haystack
 *
 * @param string $haystack
 * @param string $needle
 * @return array or false
 */ 

    $s=0;
    $i=0;

    while (is_integer($i)){

        $i = strpos($haystack,$needle,$s);

        if (is_integer($i)) {
            $aStrPos[] = $i;
            $s = $i+strlen($needle);
        }
    }
    if (isset($aStrPos)) {
        return $aStrPos;
    }
    else {
        return false;
    }
}


?>
