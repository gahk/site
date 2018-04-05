<?php
include '../delt.php';

insertHeader("Kvotient", "Denne side anvendes til at udregne din kvotient");
?>
<head>
<script type="text/javascript" language="javascript" src="../jquery.min.js"></script>
<link type="text/css" href="style.css" rel="stylesheet" />
<link type="text/css" href="style.css" rel="stylesheet" />
<link type="text/css" rel="Stylesheet" href="../jQuery.validity/jquery.validity.css" />
<script type="text/javascript" language="javascript" src="../jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="../jQuery.validity/jquery.validity.pack.js"></script>
<link type="text/css" rel="Stylesheet" href="mydata.css" />
<script type="text/javascript">
    $(function() {
        $('#form_apply').validity(function() {
            $("#name").require("Navn skal udfyldes");
            $("#prio1").match("number").greaterThan(0,"Vælg et værelse");
            $("#email").match("email", "Ugyldig email");
        });
    });
</script>
<script type="text/javascript">
    function calculate(f){
        if(f['orlov'][1].checked) {
            $(f['leaveMonth1']).removeAttr('disabled');
            $(f['leaveMonth2']).removeAttr('disabled');
            $(f['leaveYear1']).removeAttr('disabled');
            $(f['leaveYear2']).removeAttr('disabled');
            var orlov = Number(f['leaveYear2'].value)*12+Number(f['leaveMonth2'].value)-(Number(f['leaveYear1'].value)*12+Number(f['leaveMonth1'].value));
        } else {
            $(f['leaveMonth1']).attr('disabled', 'disabled');
            $(f['leaveMonth2']).attr('disabled', 'disabled');
            $(f['leaveYear1']).attr('disabled', 'disabled');
            $(f['leaveYear2']).attr('disabled', 'disabled');
            var orlov = 0;
        }
        var a = Number(f['moveYear'].value)*12+Number(f['moveMonth'].value)-(Number(f['moveInYear'].value)*12+Number(f['moveInMonth'].value))-orlov;
        var b = Number(f['doneStudyingYear'].value)*12+Number(f['doneStudyingMonth'].value)-(Number(f['moveYear'].value)*12+Number(f['moveMonth'].value));
        
        var iMoveMonth = f['moveMonth'].selectedIndex;
        var iMoveYear = f['moveYear'].selectedIndex;

        var moveMonthStr = f['moveMonth'].options[iMoveMonth].text.toLowerCase() + " " + f['moveYear'].options[iMoveYear].text;
        document.getElementById("moveStr").innerHTML = "Den 1. "+ moveMonthStr + " har du boet på GAHK i:";
        document.getElementById("a").innerHTML = a + " måneder (a)";
        document.getElementById("b").innerHTML = "Og du har<br>"+b+" måneder (b) tilbage af dit studium";
        document.getElementById("orlov").innerHTML = "Du har været på orlov i "+orlov+" måneder";
        var K = Number(a*100/(3+a+b));
        document.getElementById("kvotient").innerHTML = "Kvotient: "+K.toFixed(3);
        document.getElementById("applyMonthStr").innerHTML = "Ansøgning om værelsesflytning den 1. " + moveMonthStr;
    }
</script>
</head>
<body onLoad="calculate(document.form_apply)">
<?php
$thisYear = date("Y");
$thisMonth = date("m");
?>

<form id="form_apply" action="sendtKvotient.php" method="get" name="form_apply">
<div style="width: 400px; float: left;">

Hvornår flytter du værelse?<br>
Den 1. <?php
selector("moveMonth",(($thisMonth-1) % 12)+2,numberArray(1,12),array_values($monthName),array('onchange'=>'calculate(this.form)'));
selector("moveYear", ($thisMonth==12), numberArray($thisYear,$thisYear+1), numberArray($thisYear,$thisYear+1),array('onchange'=>'calculate(this.form)') );
?>
<br><br><br>

Hvornår flyttede du ind på GAHK?<br>
Den 1. <?php
selector("moveInMonth",$_COOKIE["moveInMonth"],numberArray(1,12),array_values($monthName),array('onchange'=>'calculate(this.form)'));
selector("moveInYear",$_COOKIE["moveInYear"]-$thisYear,numberArray($thisYear-5,$thisYear),numberArray($thisYear-5,$thisYear),array('onchange'=>'calculate(this.form)'));
?>
<br><br><br>

Hvornår forventer du at afslutte dit studie?<br>
Den 1. <?php
selector("doneStudyingMonth",$_COOKIE["doneStudyingMonth"],numberArray(1,12),array_values($monthName),array('onchange'=>'calculate(this.form)'));
selector("doneStudyingYear",$_COOKIE["doneStudyingYear"]-$thisYear,numberArray($thisYear,$thisYear+5),numberArray($thisYear,$thisYear+5),array('onchange'=>'calculate(this.form)'));
?>
<br><br><br>

Har du været på orlov?<br>
<?php
if($_COOKIE["orlov"]) {
	echo '<input type="radio" onchange="calculate(this.form)" name="orlov" value="" >Nej<br>';
	echo '<input type="radio" onchange="calculate(this.form)" name="orlov" value="Ja" checked>Ja<br>';
} else {
	echo '<input type="radio" onchange="calculate(this.form)" name="orlov" value="" checked>Nej<br>';
	echo '<input type="radio" onchange="calculate(this.form)" name="orlov" value="Ja" >Ja<br>';
}
?>


Hvis knappen er sat til Nej, kan du ignorere de nedenstående værdier.<br>
Hvis du har været på orlov: Hvornår flyttede du ud af GAHK for at tage på orlov?<br>
Den 1. <?php
selector("leaveMonth1",$_COOKIE["leaveMonth1"],numberArray(1,12),array_values($monthName),array('onchange'=>'calculate(this.form)'));
selector("leaveYear1",$_COOKIE["leaveYear1"]-$thisYear,numberArray($thisYear-5,$thisYear),numberArray($thisYear-5,$thisYear),array('onchange'=>'calculate(this.form)'));
?>
<br>

Og hvornår flyttede du ind på GAHK igen?<br>
Den 1. <?php
selector("leaveMonth2",$_COOKIE["leaveMonth2"],numberArray(1,12),array_values($monthName),array('onchange'=>'calculate(this.form)'));
selector("leaveYear2",$_COOKIE["leaveYear2"]-$thisYear,numberArray($thisYear-5,$thisYear),numberArray($thisYear-5,$thisYear),array('onchange'=>'calculate(this.form)'));
?>
<br><br>

</div>
<div style="width: 400px; float: left; padding-left: 50px;">
    Kvotienten udregnes efter formlen:
    <br>
    <img src="KvotientLigning.jpg" alt=""/><br><br>
    <div id="moveStr"></div>
    <div id="a"></div>
    <div id="b"></div><br>
    <div id="orlov"></div>
    <br><br>
    <font size="5"><b><div id="kvotient"></div></b></font>
</div>

<div style="clear:both; width:800px;">

<font size="6"><div id="applyMonthStr"></div></font>
<fieldset>
    <?php
    if(isset($_COOKIE["name"])) {
            echo 'Navn: <input type="text" id="name" name="name" value="' . $_COOKIE["name"] . '">';
    } else {
            echo 'Navn: <input type="text" id="name" name="name">';
    }
    echo '<br>Email: <input type="text" size="30" name="email" id="email"';
    if(isset($_COOKIE["email"])) echo ' value="' . $_COOKIE["email"] . '"';
    echo "/> Valgfri. Hvis du skriver din emailadresse i dette felt, vil du modtage en kopi af ansøgningen.<br>";

    for($i=1; $i<=9; $i++) {
            echo $i.'. prioritet: ';
            selector("prio".$i,0,numberArray(0,61),$roomDescription);
            echo '<br>';
    }
    ?>

</fieldset>
<input style="margin-top: 20px;" type="submit" value="Indsend værelsesansøgning"/>
</div>
</form>



</body>
<?php
insertFooter();
?>