<?php
include('../delt.php');

insertHeader("Pylon", "Pylon");
?>
<body onLoad="document.forms.chooseList.typedpassword.focus()">
	<b>Anvend Pylongruppens mailsystem</b>
	<ul>
		<li>Send gruppemails til alle Pylon-medlemmer</li>
	</ul>
	<form action="pylonMail.php" method="post" name="chooseList">
		<label for="typedpassword2">Kodeord : </label><input type="password" size="25" name="typedpassword" id="typedpassword2" value=""/><br>
		<input type="submit" value="Gå til mailsystem" /><br>
	</form>
	<br><br><br><br>
	<b>Administrer Pylongruppens mailsystem</b>
	<ul>
		<li>Rediger signatur der sendes med i bunden af gruppemails</li>
		<li>Rediger den email der sendes til folk som fjernes fra alumnelisten</li>
		<li>Se liste over pyloner</li>
		<li>Tilføj og fjern pyloner fra mailinglisten</li>
	</ul>
	<form action="pylon.php" method="post" name="chooseList">
		<label for="typedpassword">Kodeord : </label><input type="password" size="25" name="typedpassword" id="typedpassword" value=""/><br>
		<input type="submit" value="Gå til indstillinger" /><br>
	</form>
</body>
<?php
insertFooter();
?>