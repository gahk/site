<?php
///////////////////////////////////////////////////////////////////////////////////
////////////////////////////// FORMS AND SUBMIT BUTTONS ///////////////////////////
///////////////////////////////////////////////////////////////////////////////////
function hiddens($action) {
	global $month;
	echo '<input type="hidden" name="action" value="'.$action.'"/>';
	echo '<input type="hidden" name="typedpassword" value="' . $_POST["typedpassword"] . '"/>';
	echo '<input type="hidden" name="month" value="' . $month . '"/>';
}


////////////////// ADD NEW PERSON //////////////////
$year = date("Y");
br(2);
echo '<fieldset>';
echo '<legend>Tilføj ny alumne (der ikke findes i databasen)</legend>';
echo '<form id="form_addperson" action="liste.php" method="post"><br>';
hiddens("addPerson");

foreach($newPersonVariables as $varName) {
	if(!(in_array($varName, array_merge($specialSqlNames,array("name"))))) {
		echo '<label for="'.$varName.'">'.translate($varName).': </label>';
		if($varName==="birthday") {
			echo '<input type="text" name="birthday" id="birthday"/>';
		} elseif($varName==="moveInDay") {
			selector("moveInMonth", date("m",time()+15*24*3600), numberArray(1,12),array_values($monthName));
			selector("moveInYear", date("Y",time()+15*24*3600), numberArray($year-5,$year+1), numberArray($year-5,$year+1) );
		
		} elseif($varName==="room") {
			selector("room",0,numberArray(0,61),$roomDescription);
		
		} elseif($varName==="fylgje") {
			selector("fylgje","",reduceArrayArray($alumner,"name"),reduceArrayArray($alumner,"name"));
			echo '<input type="text" name="'.$varName."2".'" />';
		
		} elseif($varName==="workgroup" || $varName==="cleaning") {
			$rs = $db->GetAll("select * from intern_alumne_$varName ORDER BY $varName ASC");
			selector($varName,"",reduceArrayArray($rs,$varName),reduceArrayArray($rs,$varName));
	
                } elseif($varName==="study") {
                        $rs = $db->GetAll("select * from intern_alumne_$varName ORDER BY $varName ASC");
			selector($varName,"",reduceArrayArray($rs,$varName),reduceArrayArray($rs,$varName));
			echo '<input type="text" name="'.$varName."2".'"/>';

                } else {
			echo '<input type="text" name="'.$varName.'" id="'.$varName.'" />';
		}
		echo '<br>';
	}
}
echo '<label></label>';
echo '<input type="checkbox" name="addToThisList" value="yes" checked />Tilføj denne alumne til alumnelisten for '.$monthStr.' (listen ovenfor)<br>';
echo '<label></label>';
echo '<input type="submit" value="Tilføj alumne"><br>';
echo '</form>';
echo '</fieldset>';


////////////////// ADD EXISTING PERSON //////////////////
br(2);
echo '<fieldset>';
echo '<legend>Tilføj eksisterende alumne</legend>';
echo '<br>Tilføj eksisterende alumne (alumne der allerede er oprettet i databasen) til alumnelisten for '.$monthStr.' (listen ovenfor)';
echo '<form id="form_addexistingperson" action="liste.php" method="post"><br>';
hiddens("addPerson");
$rs = $db->GetAll("SELECT * FROM intern_alumne WHERE intern_alumne.ID NOT IN (SELECT alumne_ID FROM intern_alumne_liste WHERE intern_alumne_liste.monthNumber = '".$month."')");

foreach($rs as &$al) {
	$al["name"] = $al["firstName"]." ".$al["lastName"]; //create name from firstName and lastName
}
unset($al);
$rs = sortArray($rs, "name");	

echo '<label for="alumne_ID">Alumne: </label>'; selector("alumne_ID",0,reduceArrayArray($rs,"ID"),reduceArrayArray($rs,"name")); echo '<br>';
echo '<label for="room2">Værelse: </label>'; selector("room2",0,numberArray(0,61),$roomDescription); echo '<br>';

foreach(array("workgroup","cleaning") as $varName) {
	$rs = $db->GetAll("select * from intern_alumne_$varName ORDER BY $varName ASC");
	echo '<label for="'.$varName.'">'.translate($varName).': </label>';
	selector($varName,"",reduceArrayArray($rs,$varName),reduceArrayArray($rs,$varName));
	echo '<br>';
}
echo '<input type="submit" value="Tilføj eksisterende alumne"/><br>';
echo '</form>';
echo '</fieldset>';


////////////////// REMOVE PERSON FROM THIS LIST //////////////////
br(2);
echo '<fieldset>';
echo '<legend>Fjern alumne fra alumnelisten for '.$monthStr.' (listen ovenfor)</legend>';
echo '<form action="liste.php" method="post"><br>';
hiddens("removePerson");
selector("alumneID",0,reduceArrayArray($alumner,"alumne_ID"),reduceArrayArray($alumner,"name")); //PROBLEM HERE? CHECK
echo '<input type="submit" value="Fjern alumne"/><br>';
echo '</form>';
echo '</fieldset>';


////////////////// EDIT PERSON NAME //////////////////
br(2);
echo '<fieldset>';
echo '<legend>Foretag navneændring på eksisterende alumne</legend>';
echo '<b>Vigtigt: </b>Det er <b>IKKE</b> meningen at man skal "oprette" en ny alumne i databasen ved at ændre navnet på en udflyttet alumne.<br>';
echo 'Hver alumne i databasen har et unikt ID. Derfor skal denne funktion <b>KUN</b> anvendes hvis en alumnes navn er indtastet forkert.<br>';
echo '<form id="form_newname" name="form_newname" action="liste.php" method="post"><br>';
hiddens("editNameOfPerson");
$rs = $db->GetAll("SELECT * FROM intern_alumne ORDER BY firstName, lastName");
foreach($rs as &$al) {
	$al["name"] = $al["firstName"]." ".$al["lastName"]; //create name from firstName and lastName
}
unset($al);
echo "Eksisterende alumne: "; selector("alumne_ID",0,reduceArrayArray($rs,"ID"),reduceArrayArray($rs,"name")); echo '<br>';
echo '<label for="newFirstName">Fornavn: </label>';
echo '<input type="text" name="newFirstName" id="newFirstName" size="30"/><br>';
echo '<label for="newLastName">Efternavn: </label>';
echo '<input type="text" name="newLastName" id="newLastName" size="30"/><br>';
echo '<label for="newNameOK" style="float: left; width: 150px;">Godkend navneændring: </label>';
echo '<input type="checkbox" name="newNameOK" id="newNameOK" value="yes" unchecked><br>';
echo '<input type="submit" value="Foretag navneændring af alumne"/><br>';
echo '</form>';
echo '</fieldset>';


////////////////// DELETE STUDY //////////////////
br(2);
echo '<fieldset>';
echo '<legend>Fjern studie</legend>';
echo '<form action="liste.php" method="post"><br>';
hiddens('deleteStudy');
$rs = $db->GetAll("SELECT * FROM intern_alumne_study ORDER BY study ASC");
selector('studyToDelete',0,reduceArrayArray($rs,"ID"),reduceArrayArray($rs,"study"));
echo '<input type="submit" value="Fjern studie"/><br>';
echo '</form>';
echo '</fieldset>';



////////////////// COPY LIST TO NEXT MONTH //////////////////
br(2);
$rs = $db->GetAll("SELECT DISTINCT monthNumber FROM intern_alumne_liste ORDER BY monthNumber DESC");
//if($month + 1)
$month2 = $rs[0]['monthNumber'] + 1;

echo '<fieldset>';
echo '<legend>Kopier denne liste til '.mn2mstr($month+1).'</legend>';
if(!in_array($month+1,reduceArrayArray($rs,"monthNumber"))) {
	echo '<form id="form_copylist" action="liste.php" method="post"><br>';
	hiddens("copyList");
        echo '<input type="hidden" name="month2" value="'.$month2.'"/>';
	echo 'Tjek listen igennem for fejl før du kopierer den, da fejlen ellers vil optræde flere steder.<br><br>';
	echo '<label for="confirmCopy" style="float: left; width: 170px;">Godkend kopiering af liste: </label>';
	echo '<input type="checkbox" name="confirmCopy" id="confirmCopy" value="yes" unchecked><br>';
	echo '<input type="submit" value="Kopier alumneliste"/>';
	echo '</form>';
} else {
	echo "Der findes allerede en liste for ".mn2mstr($month+1);
}
echo '</fieldset>';


////////////////// DELETE LIST //////////////////
br(2);
echo '<fieldset>';
echo '<legend>Slet denne liste</legend>';
echo '<form id="form_deletelist action="liste.php" method="post"><br>';
hiddens("deleteList");
echo '<label for="confirmDelete" style="float: left; width: 170px;">Godkend sletning af liste: </label>';
echo '<input type="checkbox" name="confirmDelete" id="confirmDelete" value="yes" unchecked><br>';
echo '<input type="submit" value="Slet denne liste ('.mn2mstr($month).')"/>';
echo '</form>';

echo '</fieldset>';

///////////////////////////////////////////////////////////////////////////////////
////////////////////////////// FORMS AND SUBMIT BUTTONS ///////////////////////////
///////////////////////////////////////////////////////////////////////////////////
?>