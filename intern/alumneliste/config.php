<?php
//////////////////// Decide which variables to show in table ////////////////////
$tableColumns[1] = array("name","room","workgroup","cleaning","fylgje","birthday","moveInDay","study","phone","email"); //variables shown when in user mode
if($access == 1 && $_POST["display"]==="showCustomList") {
    $tableColumns[1] = $_POST['tableColumns'];
    $showExtra[1] = $_POST['showExtra'];
}

if($month === 'allPersons') $tableColumns[1] = array_diff($tableColumns[1],array("room","workgroup","cleaning")); //remove room, workgroup and cleaning when showing all persons
if($m) $tableColumns[1] = array("name","room","phone");
$tableColumnsNormalUser = $tableColumns[1];

$showExtra[1] = array("moveInAndOut","floorPlan");
if($m) $showExtra[1] = array("floorPlan");
if($month === 'allPersons') $showExtra[1] = array_diff($showExtra[1],array("moveInAndOut","floorPlan")); //remove moveInAndOut, and floorPlan when showing all persons

$tableColumns[2] = array("name","room","workgroup","cleaning","fylgje","birthday","moveInDay","study","phone","email","alumne_ID"); //variables to be shown when in indstillingen admin mode
$showExtra[2] = array("moveInAndOut");

$tableColumns[3] = array("name","networkClosed","networkClosedDetails","alumne_ID"); //variables to be shown when in inspection admin mode
$showExtra[3] = array("");

$newPersonVariables = array("firstName","lastName","room","workgroup","cleaning","fylgje","birthday","moveInDay","study","phone","email"); //variables for new person form


function sort_keyword($column) {
    if(in_array($column, array("birthday","moveInDay"))) {
        return '{ "sType": "eu_date" }';
    } else {
        return "null";
    }
}
?>
