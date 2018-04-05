<?php
include '../delt.php';
insertHeader("Netværk", "MAC - Find MAC-adresse");
?>
MAC-adressen (har ikke noget med Mac-computere at gøre) er unik for hvert stykke netværksudstyr. Både dit netværkskort (til kabel), og dit trådløse netværkskort har en MAC-adresse.
<br><br>
For at få adgang til GAHKs netværk skal du registrere din MAC-adresse.<br>
For at finde din MAC-adresse skal du åbne programmet "Netværktøj". Det findes i mappen "Hjælpeprogrammer" (som ligger i mappen programmer).<br>
<img src="mac1.png"/><br><br>
Efter du har åbnet programmet, vises følgende skærm. "Hardware-Adresse" er EN af din computers MAC-Adresser. Ethernet (en0).<br>
<img src="mac2.png"/><br><br>
For adgang til WIRELESS / TRÅDLØS netværk skal du bruge din "Airport" MAC-adresse. Derfor skal du vælge "AirPort" (ikke "Ethernet").<br>
<img src="mac3.png"/><br><br>
Nu vises Hardware-Adressen (MAC-adressen) for dit trådløse netværkskort. Det er MAC-Adressen som du skal indtaste for at få adgang til GAHK trådløs netværk.<br>
<img src="mac4.png"/><br>

<?php
insertFooter();
?>