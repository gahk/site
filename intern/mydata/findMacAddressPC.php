<?php
include '../delt.php';
insertHeader("Netværk", "Windows - Find MAC-adresse");
?>
MAC-adressen er unik for hvert stykke netværksudstyr. Både dit netværkskort (til kabel), og dit trådløse netværkskort har en MAC-adresse.
<br><br>
Færst skal cmd åbnes. Under Windows XP: Tryk på startknappen, vælg kør/run, skriv cmd og tryk enter.<br>
Under Windows Vista og Windows 7: Tryk på startknappen, skriv cmd og tryk enter.
<br><br>
Nu er terminalen åben.
<br>
Skriv ipconfig/all. Med denne kommando kan man se alle detaljer om sine netværkskort. Nu skulle det gerne se ca. sådan her ud:
<br>
<img src="ipconfig.png"/><br>
Find "Ethernet adapter Local Area Connection" og "Wireless LAN adapter Wireless Network Connection" (hvis din computer har en trådløs forbindelse).<br><br>

Nu kan MAC-adresserne (også kaldet Fysiske adresser/Physical Adresses) afløses.


<br><br>
<br><br>
<b>Alternativ metode</b>
<br><br>
Skriv getmac. Physical Address er MAC-addressen. Hvis din computer har både et netværksstik og et tråsløst netkort, er der to MAC-addresser.
<br>
Nu skulle det gerne se sådan her ud:
<br>
<img src="getmac.png"/><br>
Tilføj begge MAC-addresser til din bruger.



<?php
insertFooter();
?>