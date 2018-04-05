<?php
include '../delt.php';
insertHeader("Printer", "Installation af printer under Windows 7 (måske også Windows Vista)");
?>
<p>
<font size="3">
<b>Hvis nogen prøver installation under Windows Vista, så skriv lige tilbage til nielsoleholck@gmail.com og sig om det virker</b><br>
</font>
</p>
Download installationsfilen: <a href="http://gahk.dk/intern/printer/installfiles/upd-5101-pcl6_winxp-vista.exe">Installationsfil (15,2 MB)</a>. Dobbeltklik på filen<br><br>
Tryk på Unzip, tryk OK, tryk Close
<br><br>
Find filerne i filfinder på placeringen C:\HP Universal Print Driver PCL6 v5.1.0.1 (medmindre du selv har valgt en anden sti).
<br><br>
Dobbeltklik på Install.exe
<br><br>
Tryk Ja (til at acceptere betingelserne).
<br><br>
Vælg Traditionel tilstand og tryk Installer
<br><br>
Hvis der popper et vindue op som spørger hvilken type printer du vil installere, så vælg Add a network, wireless or Bluetooth printer<br>
Printeren skal være tændt mens dette finder sted. Vælg hp LaserJet 4345 mfp (Hewlett-Packard) på adressen 192.168.0.5<br>
Tryk next
<br><br>
Vælg et printernavn, f. eks. GAHK Printer
<br><br>
Printer sharing: Vælg Do not share this printer
<br><br>
Vælg evt. Set as default printer hvis du vil have den som din standardprinter
<br><br>
Tryk Finish
<br><br>
HPs program skal lige konfigurere printeren, tryk på Udfør.
<?php
insertFooter();
?>