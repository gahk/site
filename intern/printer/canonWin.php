<?php
include '../delt.php';
insertHeader("Printer", "Installation af Canon-printer på Windows 2000, XP, Vista og 7");
?>
<img src="canon_mf5700_allinone_laser.jpg">
<br><i>Canon MF5770</i>
<br><br>
<ul>
<li>Download installationsfiler
	<ul>Windows 32 bit - Download installationsfilen: <a href="http://gahk.dk/intern/printer/installfiles/MF5700_MFDrivers_Win_x32_EN_7.exe">Installationsfil (20 MB)</a>. Dobbeltklik på filen</ul>
	<ul>Windows 64 bit - Download installationsfilen: <a href="http://gahk.dk/intern/printer/installfiles/MF5700_MFDrivers_Win_x64_EN_7.exe">Installationsfil (11 MB)</a>. Dobbeltklik på filen</ul>
	<ul><i>I tvivl om du har 32 eller 64 bit Windows? Tryk på start, højreklik på Computer og tryk Properties/Egenskaber. Nu kommer der en oversigt frem der viser hvilken type Windows du har.</i></ul>
</li>
<br>
<h2>Windows XP, Vista eller 7 - 32 bit</h2>
<li>Tryk Install</li><br>
<li>Tryk Next<br>Tryk Yes</li><br>
<li>Vælg Network Connection</li><br>
<li>Hvis den spørger om den må foretage ændringer i Windows Firewall. Tryk Yes.</li><br>
<li>Tryk Search by IP. Indtast 192.168.0.6<br>Tryk Next</li><br>
<li>Tjek at der er hak i Printer. Lad Fax forblive uden hak.<br>Tryk Next</li><br>
<li>Port: Vælg 192.168.0.6 hvis den findes i listen. Hvis den ikke er i listen, tryk Add og skriv den selv ind.<br>Tryk Next</li><br>
<li>Printer Name: Vælg hvad du vil kalde printeren. F. eks.: GAHK Canon Printer (eller hvad du nu lige synes).<br>LAD VÆRE med at sætte hak i Use as Shared Prtiner<br>Tryk Next</li><br>
<li>Tryk Start</li><br>
<li>Nu kan du vælge om printeren skal være din foretrukne printer. Tryk Next.</li><br>
<li>Lad være med at printe en testside.</li><br>
<li>Nu beder den dig om at genstarte computeren. Dette er vist ikke nødvendigt for at bruge printeren. Tryk Restart Later.</li>
<br><br>
<h2>Windows XP, Vista eller 7 - 64 bit</h2>
<li>Tryk på Start-knappen og tryk på Devices and Printers</li><br>
<li>Tryk nu på Add a printer</li><br>
<li>Vælg Add a network, wireless or Bluetooth printer</li><br>
<li>Nu søger din computer efter printere på netværket. Den bør finde Canon MF5770 /P på IP-adressen 192.168.0.6</li><br>
<li>Klik på printeren og tryk Next</li><br>
<li>I nogle tilfælde kan programmet selv finde driveren til printeren. Hvis dette er tilfældet er Canon MF5700 Series markeret i listen. Ellers, tryk Have Disk og browse til folderen hvor installationsfilen har pakket sig selv ud. Tryk Open, Ok, Next</li><br>
</ul>

<br><br>
<i>Af Niels Ole Holck - April 2011</i>
<?php
insertFooter();
?>