<?php
include '../validEmail.php';
include '../delt.php';
insertHeader("Mailingliste", "Denne side anvendes til at til- eller afmelde sig mailinglisten");


if($_GET["action"]=="tilmeld") {
	if(validEmail($_GET["email"])) {

		mysql_connect(localhost,$username,$password);
		@mysql_select_db($database) or die( "Unable to select database");
		
		$query = "DELETE FROM internmailliste WHERE email='" . $_GET["email"] . "'";
		mysql_query($query);
		
		$query = "INSERT INTO internmailliste VALUES ('','" . $_GET["email"] . "')";
		mysql_query($query);
		
		mysql_close();
		echo("<b><font color=\"green\">Tilmelding foretaget.</font></b><br><br><br>");
	} else {
		echo("<b><font color=\"red\">Fejl i tilmelding. Indtast gyldig emailadresse.</font></b><br><br><br>");
	}
} elseif($_GET["action"]=="afmeld") {
	if(validEmail($_GET["email"])) {

		mysql_connect(localhost,$username,$password);
		@mysql_select_db($database) or die( "Unable to select database");
		
		$query = "DELETE FROM internmailliste WHERE email='" . $_GET["email"] . "'";
		mysql_query($query);
		mysql_close();
		echo("<b><font color=\"green\">Afmelding foretaget.</font></b><br><br><br>");
	} else {
		echo("<b><font color=\"red\">Fejl i afmelding. Indtast gyldig emailadresse.</font></b><br><br><br>");
	}
}
?>

<b>Tilmeld</b>
<form action="index.php" method="get">
Emailadresse: <input type="text" name="email"/><br>
<input type="hidden" name="action" value="tilmeld"/>
<input type="submit" value="Tilmeld"/></form>

<br><br>
<b>Afmeld</b>
<form action="index.php" method="get">
Emailadresse: <input type="text" name="email"/><br>
<input type="hidden" name="action" value="afmeld"/>
<input type="submit" value="Afmeld"/></form>

<br><br>
<b>Administrer</b>
<form action="mailadmin.php" method="post">
Kode: <input type="password" name="typedpassword"/><br>
<input type="submit" value="Gå til admin-side"/></form>
<i>Glemt kode: Tryk på 'Gå til admin-side' og derefter på 'Send koden til Indstillingens mail'</i>


</body>
</html>