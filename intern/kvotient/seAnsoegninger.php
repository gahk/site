<?php
include '../delt.php';
$admin = 0;
if($_POST["typedpassword"] === $adminpassword_indstillingen) {
	$admin=1;
	$showansoegninger = !$_POST["showpage"];
}
insertHeader("Kvotient", "Liste over ansøgninger");
echo '<link type="text/css" href="style.css" rel="stylesheet"/>';

include('../adodb5/adodb.inc.php');
$db = ADONewConnection('mysql'); 
$db->Connect('localhost', $username, $password, $database); //parameter are from delt.php
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$applications = array();

if($_POST["typedpassword"]==$adminpassword_indstillingen && $_POST["action"]=="emptyDatabase") {
	$db->Execute("TRUNCATE TABLE intern_kvotient");
	$db->Execute("ALTER TABLE intern_kvotient AUTO_INCREMENT = 1");
} elseif($_POST["typedpassword"]==$adminpassword_indstillingen && $_POST["action"]=="alterDisplayed") {

    foreach($_POST['appid'] as $key => $id) {
        $db->AutoExecute('intern_kvotient',array('displayed'=>$_POST['displayed'][$key]),'UPDATE',"intern_kvotient.ID = $id");
    }
}


//Start of permission only for indstillingen
//Remove this if-statement if public page should be shown
if($admin){

if(!$showansoegninger) {
	echo 'Søg gerne et værelse selv om en person med højere kvotient allerede har søgt det.<br>Hvis personen springer fra, vil der typisk ikke blive foretaget en ny værelsesrunde, men den næste på listen vil få værelset.<br><br>';
	echo '<a href="plantegning.php" target="_blank">Se plantegning (ny fane)</a><br><br>';
}

$filterStr = "";
if(!$showansoegninger) $filterStr = " WHERE displayed = '1'";
$rs = $db->GetAll("SELECT * FROM intern_kvotient $filterStr");

foreach($rs as $app) {
    $name = $app['name'];
    $quotient=$app['K'];
    $applyDatetime=$app['applyDatetime'];
    $moveMonth = $app['moveMonth'];
    $moveDateString = "1. " . mn2mstr($moveMonth);
    $displayed = $app['displayed'];

    foreach(explode('-',$app['priorities']) as $key => $value) {
        array_push($applications, new application($name,$quotient,($key+1),$value,$applyDatetime,$moveMonth,$displayed,$app['ID']));
    }
}

// ANSØGNINGER SORTERET EFTER ANSØGER
$applications = sortObject($applications,"person");
findWinnersOfRooms($applications,$roomsWon,$personsWon);

$tableContent="";
$i=0;
$roomString = "";
while($i<sizeof($applications)) {
	if($roomString) $roomString .= "<br>";
	
        if($applications[$i]->winnerOfRoom) $roomString .= '<font color="green"><b>';
	$roomString .= $applications[$i]->priority . ". prioritet: " . $roomDescription[$applications[$i]->room];
	if($applications[$i]->winnerOfRoom) $roomString .= "</b></font>";

	if($i == sizeof($applications)-1) {
		//$tableContent .= tableRowString($applications[$i]->K,$applications[$i]->getMoveDate(),$applications[$i]->name,$applications[$i]->getApplyDatetimeFormatted(),$roomString,$applications[$i]->winnerOfRoom,$applications[$i]->displayed,$applications[$i]->ID);
            $tableContent .= tableRowString($applications[$i]->K,$applications[$i]->getMoveDate(),$applications[$i]->name,$applications[$i]->getApplyDatetimeFormatted(),$roomString,0,$applications[$i]->displayed,$applications[$i]->ID);
		$roomString = "";
	} else {
		if($applications[$i]->ID != $applications[$i+1]->ID) {
			//$tableContent .= tableRowString($applications[$i]->K,$applications[$i]->getMoveDate(),$applications[$i]->name,$applications[$i]->getApplyDatetimeFormatted(),$roomString,$applications[$i]->winnerOfRoom,$applications[$i]->displayed,$applications[$i]->ID);
                    $tableContent .= tableRowString($applications[$i]->K,$applications[$i]->getMoveDate(),$applications[$i]->name,$applications[$i]->getApplyDatetimeFormatted(),$roomString,0,$applications[$i]->displayed,$applications[$i]->ID);
			$roomString = "";
		}
	}	
	$i++;
}

if($applications) {
        echo '<h1>Ansøgninger sorteret efter ansøger</h1>';
        if($showansoegninger) {
            echo '<form action="seAnsoegninger.php" method="post"><br>';
            echo '<input type="hidden" name="action" value="alterDisplayed"/>';
            echo '<input type="hidden" name="typedpassword" value="'.$_POST["typedpassword"].'"/>';
        }
	createTablePersonApplication($tableContent);
	// SLUT: ANSØGNINGER SORTERET EFTER ANSØGER
	if(!$showansoegninger) {
		echo "<br><br>";
		// ANSØGNINGER SORTERET EFTER VÆRELSE
                echo '<h1>Ansøgninger sorteret efter værelse</h1>';
		$applications = sortObject($applications,"room");
		$tableContent="";
		$i=0;
		while($i<sizeof($applications)) {
			if((int)$applications[$i]->room != (int)$lastRoom && $i > 0) {
				echo "<h2>".$roomDescription[$lastRoom]."</h2>";
				createTableRoomApplication($tableContent);
				echo "<br><br>";
				$tableContent="";
			}
			
			$roomString = "";
			if($applications[$i]->winnerOfRoom) $roomString .= '<font color="green"><b>';
			$roomString .= $applications[$i]->priority . ". prioritet: " . $roomDescription[$applications[$i]->room];
			if($applications[$i]->winnerOfRoom) $roomString .= "</b></font>";

			$tableContent .= tableRowString($applications[$i]->K,$applications[$i]->getMoveDate(),$applications[$i]->name,$applications[$i]->getApplyDatetimeFormatted(),$roomString,$applications[$i]->winnerOfRoom,$applications[$i]->displayed,$applications[$i]->ID);
			
			$lastRoom = $applications[$i]->room;
			$i++;
			
			if($i == sizeof($applications)) {
				echo "<h2>".$roomDescription[$lastRoom]."</h2>";
				createTableRoomApplication($tableContent);
			}
		}
	}
	//SLUT: ANSØGNINGER SORTERET EFTER VÆRELSE
	
	if($showansoegninger) {
            echo '<input type="submit" value="Rediger hvilke værelsesansøgninger der vises"/><br>';
            echo '</form>';
        
            echo '<br><form action="seAnsoegninger.php" method="post"><br>';
            echo 'Kode: <input type="password" name="typedpassword"/><br>';
            echo '<input type="hidden" name="action" value="emptyDatabase"/>';
            echo '<input type="submit" value="Fjern alle ansøgninger fra databasen"/><br>';
            echo '</form>';
	}
} else {
    echo '<h1>Ingen ansøgninger fundet i databasen</h1>';
}

//End of permission only for indstillingen
}

$db->Close();
insertFooter();

class application {
	public $name;
	public $quotient;
	public $priority;
	public $room;
	public $applyDatetime;
	public $moveMonth;
	public $ID;
	public $winnerOfRoom;
        public $displayed;

	public function __construct($Name,$K,$Priority,$Room,$ApplyDatetime,$MoveMonth,$displayed,$id) {
         $this->name = $Name;
		 $this->K = $K;
		 $this->priority = $Priority;
		 $this->room = $Room;
		 $this->applyDatetime = $ApplyDatetime;
		 $this->moveMonth = $MoveMonth;
		 $this->ID = $id;
		 $this->winnerOfRoom = 0;
                 $this->displayed = $displayed;
    }

	public function getMoveDate() {	return "1. " . mn2mstr($this->moveMonth); }
	public function getCompareValue() {	return $this->room . $this->quotient; }
	public function getApplyDatetimeFormatted() {
		global $monthName;
                $tmp = strtotime($this->applyDatetime);
		return (int)date("d",$tmp).'. '.strtolower($monthName[(int)date("m",$tmp)])." ".(int)date("Y",$tmp).' - kl '.date("H:i",$tmp);
	}
        public function getTableRow($mode) {
            $data = array('K'=>$this->K, 'moveMonth'=> $this->moveMonth, 'name'=>$this->name, 'applyDatetime'=>$this->applyDatetime, 'priority'=>$this->priority );
            return "1. " . mn2mstr($this->moveMonth);
        }
}

function findWinnersOfRooms($data) {
	//The applications have to be sorted using $applications = sortObject($applications,"person"); before using this function
	$i=0;
	$listRooms = array();
	$listPersons = array();
	while($i < count($data)) {
		if(!in_array($data[$i]->room,$listRooms) && !in_array($data[$i]->ID,$listPersons)) {
                        $data[$i]->winnerOfRoom = 1;
			array_push($listRooms,$data[$i]->room);
			array_push($listPersons,$data[$i]->ID);
		}
		$i++;
	}
}

function sortObject($data,$mode) {
	for ($i = count($data) - 1; $i >= 0; $i--) {
		$swapped = false;
		for ($j = 0; $j < $i; $j++) {
			if($mode == "room") {
				if ( $data[$j]->room < $data[$j + 1]->room || (($data[$j]->room == $data[$j + 1]->room) && $data[$j]->K < $data[$j + 1]->K) ) {
					$tmp = $data[$j];
					$data[$j] = $data[$j + 1];
					$data[$j + 1] = $tmp;
					$swapped = true;
				}
			} elseif($mode == "person") {
				if ( ($data[$j]->K <  $data[$j + 1]->K) ||
					(($data[$j]->K == $data[$j + 1]->K) && $data[$j]->applyDatetime >  $data[$j + 1]->applyDatetime) ||
					(($data[$j]->K == $data[$j + 1]->K) && $data[$j]->applyDatetime == $data[$j + 1]->applyDatetime && $data[$j]->name <  $data[$j + 1]->name) ||
					(($data[$j]->K == $data[$j + 1]->K) && $data[$j]->applyDatetime == $data[$j + 1]->applyDatetime && $data[$j]->name == $data[$j + 1]->name && $data[$j]->priority > $data[$j + 1]->priority)	) {
					$tmp = $data[$j];
					$data[$j] = $data[$j + 1];
					$data[$j + 1] = $tmp;
					$swapped = true;
				}
			} else {
				echo "mode not found!";
			}
		}
		if (!$swapped) {
			return $data;
		}
	}
}

function createTableRoomApplication($str) {
	echo '<table border="1">';
	echo '<th>Kvotient</th>';
	echo '<th>Dato for flytning</th>';
	echo '<th>Navn</th>';
	echo '<th>Ansøgningstidspunkt</th>';
	echo '<th>Prioritet</th>';
	echo $str;
	echo '</table>';
}

function createTablePersonApplication($str) {
	global $showansoegninger;
	echo '<table border="1">';
	echo '<th>Kvotient</th>';
	echo '<th>Dato for flytning</th>';
	echo '<th>Navn</th>';
	echo '<th>Ansøgningstidspunkt</th>';
	echo '<th>Prioritet(er)</th>';
	if($showansoegninger) echo '<th>Vises på listen over ansøgninger</th>';
	echo $str;
	echo '</table>';
}

function tableRowString($K,$moveDate,$name,$applyTime,$prio,$won,$displayed,$id) {
        global $showansoegninger;

	//two modes, one with only a single room, and one with all the rooms applied for by the person
	$str= '<tr>';
	$str.= "<td>$K</td>";
	$str.= "<td>$moveDate</td>";
	if($won) {
            $str.= '<td><font color="green"><b>'.$name.'</b></font></td>';
	} else {
            $str.= "<td>$name</td>";
	}
	$str.= "<td>$applyTime</td>";
	$str.= "<td>$prio</td>";
        if($showansoegninger) {
            $str.= '<input type="hidden" name="appid[]" value="'.$id.'">';
            $str.= '<td>'.selector("displayed[]",$displayed,array(1,0),array("Vises","Vises ikke"),array('returnAsString' => 1)).'</td>';
        }
	$str.= '</tr>';
	return $str;
}

?>
