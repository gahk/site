<?php
include '../delt.php';
insertHeader("Andet", "Andet");
?>
<ul>
	<li><a href="http://gahk.dk/intern/andet/DenGyldneBog020911.pdf">Den Gyldne Bog</a></li><br><br>
    <li><a href="http://gahk.dk/intern/andet/GahkPakken.pdf">GAHK-pakken</a></li><br><br>
    <li><a href="http://gahk.dk/intern/andet/GahkFremlejekontrakt.pdf">Fremlejekontrakt</a></li><br><br>
    <li><a href="http://gahk.dk/intern/andet/kalender.php">Kalender</a></li><br><br>
	<li><a href="http://gahk.dk/intern/andet/opsigelse.pdf">Opsigelse</a></li><br><br>
    <li><a href="http://gahk.dk/intern/andet/junkfood.php">Junkfood - Tomarzas folder</a></li>
</ul>
<br><br>
Log ind på forbrugssiden:
<form action="forbrugAdmin.php" method="post">
<label for="typedpassword">Kodeord : </label><input type="password" size="25" name="typedpassword" id="typedpassword" value=""/><br>
<input type="submit" value="Log ind på forbrugssiden" />
</form>
<?php
insertFooter();
?>
