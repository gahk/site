<?php
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////// EVALUATE POST_ARRAY /////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
//print_arr($_POST);

if($_POST["action"]==="addPerson") { //////////// ADD NEW ALUMNI ////////////
	$alumne = array();
	foreach($_POST as $key => $value) {
		$alumne[$key]=$value;
	}
	if($alumne["fylgje2"]) $alumne["fylgje"] = $alumne["fylgje2"];
	
	// Should be cleaned up since workgroups and cleanings cannot be added anymore this way
	$posAddCategories = array("workgroup2","cleaning2","study2"); //possible category additions (new workgroups, cleaning dutys or studys can be added to the database here)
	foreach($posAddCategories as $checkVar) {  //checkVar=workgroup2.., origVar=workgroup
		$origVar =  substr($checkVar, 0, strlen($checkVar)-1); // origvar: workgroup,cleaning,study)
		$newWorkgroup = $alumne[$checkVar]; //If nothing was typed in the form, this is ""
		if($newWorkgroup) {
			$alumne[$origVar] = $newWorkgroup;
			$categories = $db->GetAll("SELECT $origVar FROM intern_alumne_$origVar WHERE $origVar='$newWorkgroup'");
			if(!$categories) $db->AutoExecute("intern_alumne_$origVar",$alumne,'INSERT');
		}
	}
	
	if($alumne['alumne_ID']) { //////////// ADD EXISTING ALUMNI TO LIST ////////////
		$alumne["monthNumber"] = $month;
		$alumne["room"]=$alumne["room2"];
		$db->AutoExecute('intern_alumne_liste',$alumne,'INSERT');
	} else { //////////// ADD NEW ALUMNI TO THE DATABASE AND THE LIST ////////////
		$alumne["birthday"] = $_POST["birthday"];
		$password = genRandomString(8);
		$alumne["password"] = hash("sha256", $password);
		$alumne["moveInDay"] = $_POST["moveInYear"]."-".leadingZero($_POST["moveInMonth"])."-01";
		$db->AutoExecute('intern_alumne',$alumne,'INSERT');
		if($alumne[addToThisList]) {
			$alumne['alumne_ID']=$db->Insert_ID();
			$alumne["monthNumber"] = $month;
			$db->AutoExecute('intern_alumne_liste',$alumne,'INSERT');
		}
		// send email to new resident if settings say so
		$rs = $db->GetAll("select * from intern_alumne_emailtonew");
		if($rs[0]['sendEmailToNewPersons'] && $alumne['email']) {
			$body = $rs[0]['emailBody'];
			$body = replaceTagsWithValuesAndPasswordAsOtherParam($body, $alumne, $password);
			mailFormatted($alumne['email'],'Velkommen til GAHKs interne netværk',$body);
		}
	}
}

elseif($_POST["action"]==="removePerson") { //////////// REMOVE ALUMNI FROM CURRENT ALUMNI LIST ////////////
	$alumneToDel = $_POST["alumneID"];
	$db->Execute("DELETE FROM intern_alumne_liste WHERE monthNumber = '$month' AND alumne_ID = '$alumneToDel'");
	// send email to residents moving out if settings say so
	$rs = $db->GetAll("select * from intern_alumne WHERE ID = '$alumneToDel'");
	$alumne = $rs[0];
	$rs = $db->GetAll("select * from intern_alumne_pylon_email_settings");
	if($rs[0]['moveout_sendEmail'] && $alumne['email']) {
		$body = $rs[0]['moveout_emailBody'];
		$body = replaceTagsWithValues($body, $alumne);
		mailFormatted2($alumne['email'],$rs[0]['moveout_emailFrom'],$rs[0]['moveout_emailSubject'],$body);
	}
}

elseif($_POST["action"]==="copyList") { //////////// COPY LIST TO NEXT MONTH ////////////
	if($_POST['confirmCopy']) {
		$month2 = $_POST['month2'];
		$alumner = $db->GetAll("SELECT * FROM intern_alumne JOIN intern_alumne_liste ON intern_alumne_liste.alumne_ID = intern_alumne.ID WHERE intern_alumne_liste.monthNumber = '$month'");
		foreach($alumner as $alumne) {
			unset($alumne['ID']);
			$alumne["monthNumber"] = $month2;
			$db->AutoExecute('intern_alumne_liste',$alumne,'INSERT');
		}
		$month = $month2;
	} else {
		redText("Bekræftelse ikke afmærket. Ingen handling foretaget.");
	}
}

elseif($_POST["action"]==="deleteList") { //////////// DELETE LIST ////////////
	if($_POST['confirmDelete']) {
		$monthToDel = $_POST["month"];
		$db->Execute("DELETE FROM intern_alumne_liste WHERE monthNumber = '$monthToDel'");
                header( 'Location: http://gahk.dk/intern/alumneliste/index.php?admin=true' ) ;
	} else {
		redText("Bekræftelse ikke afmærket. Ingen handling foretaget.");
	}
}

elseif($_POST["action"]==="deleteWorkgroup") { //////////// DELETE WORKGROUP CATEGORY ////////////
	$db->Execute("DELETE FROM intern_alumne_workgroup WHERE ID = '".$_POST["workgroupToDelete"]."'");
	echo $_POST["workgroupToDelete"];
}

elseif($_POST["action"]==="deleteCleaning") { //////////// DELETE CLEANING CATEGORY ////////////
	$db->Execute("DELETE FROM intern_alumne_cleaning WHERE ID = '".$_POST["cleaningToDelete"]."'");
}

elseif($_POST["action"]==="deleteStudy") { //////////// DELETE STUDY ////////////
	$db->Execute("DELETE FROM intern_alumne_study WHERE ID = '".$_POST["studyToDelete"]."'");
}

elseif($_POST["action"]==="editList") { //////////// EDIT LIST ////////////
	$emailsettings = $db->GetAll("SELECT * FROM intern_alumne_emailnetworkstatus");
	
	for ( $i = 0; $i < count($_POST['alumne_ID']); $i++) {
		$updatedAlumne = array();
		foreach($_POST as $key => $value) {
			$updatedAlumne[$key] = $value[$i];
		}
		$aID = $updatedAlumne['alumne_ID'];
		
		if($_POST['networkClosed']) {
			$alumne = $db->GetRow("SELECT * FROM intern_alumne WHERE ID  = $aID");
			if($updatedAlumne['networkClosed'] != $alumne['networkClosed']) { // change in network status
				if($alumne['email']) { // only attempt to send email if email address is in system
					if($updatedAlumne['networkClosed']) { // network just closed
						if($emailsettings[0]['enabled']) {
							$alumne['networkClosedDetails'] = $updatedAlumne['networkClosedDetails'];
							$body = replaceTagsWithValues($emailsettings[0]['body'], $alumne);
							mailFormatted($alumne['email'], "GAHK: Dit netværk er lukket", $body);
						}
					} else { // network just opened again
						if($emailsettings[1]['enabled']) {
							$body = replaceTagsWithValues($emailsettings[1]['body'], $alumne);
							mailFormatted($alumne['email'], "GAHK: Dit netværk er åbent igen", $body);
						}
					}
				}
			}
		}
		
		//print_arr($alumne);
		
		if(!$updatedAlumne['networkClosed']) $updatedAlumne['networkClosedDetails'] = ""; //if network is open, delete details
		$db->AutoExecute('intern_alumne_liste',$updatedAlumne,'UPDATE',"intern_alumne_liste.alumne_ID = '$aID' AND intern_alumne_liste.monthNumber = '$month'");
		$db->AutoExecute('intern_alumne',$updatedAlumne,'UPDATE',"intern_alumne.ID = $aID");
	}
}

elseif($_POST["action"]==="editNameOfPerson") { //////////// EDIT NAME OF PERSON ////////////
	$alumne = array();
	$alumne["firstName"] = $_POST["newFirstName"];
	$alumne["lastName"] = $_POST["newLastName"];
	$alumne["alumne_ID"] = $_POST["alumne_ID"];
	if($_POST["newNameOK"]) {
		$rs = $db->GetRow("SELECT firstName FROM intern_alumne WHERE ID = '".$alumne["alumne_ID"]."'");
		$db->AutoExecute('intern_alumne',$alumne,'UPDATE',"intern_alumne.ID = '".$alumne["alumne_ID"]."'");
	}
}
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////// EVALUATE POST_ARRAY /////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
?>
