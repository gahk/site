<?php
include 'delt.php';
insertHeader("Start", "GAHK-intern forside",array('menuPath'=>"menu/menu.php"));
?>
<head>
    <script type="text/javascript" src="jscharts.js"></script>
</head>
<?php

include('adodb5/adodb.inc.php');
$db = ADONewConnection('mysql');
$db->Connect('localhost', $username, $password, $database); //parameters are from delt.php
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$db->Execute("SET NAMES utf8");

$price = 2.00; //   kr/kWh

$data = $db->GetAll("SELECT * FROM intern_forbrug ORDER BY aar,ugenr ASC");

foreach($data as &$f) {
    //$f['Ugenummer'] = $f['aar'].'-'.leadingZero($f['ugenr']); // fylder for meget på x-aksen. Tags oveni hinanden.
    $f['Ugenummer'] = leadingZero($f['ugenr']);
    $f['dataPoint'] = "['".$f['Ugenummer']."', ".round($f['forbrug']*$price).']';
}
unset($f);

if($_GET['logout']==1) {
//  echo 'LOGOUT';
    session_destroy();
}
?>
<br>
<div style="width: 650px; float: left;">

    <?php
        if(atGAHK()) { //user is sitting at GAHK
            echo 'Du er registreret som siddende på GAHK.<br>';
            echo '<a href="http://gahk.dk/intern/alumneliste/liste.php?mostRecentList=1" >Tryk her for at se den nyeste alumneliste</a>';
            br(3);
        }
        
        ?>
        <b>PR-gruppen har oprettet en T-shirt-shop. Tjek den ud <a href="http://gahk.spreadshirt.dk" target="_blank">her.</a></b>
        <br>
        <a href="http://gahk.spreadshirt.dk/" target="_blank"><img src="http://image.spreadshirt.net/image-server/image/product/23428604/view/2/type/png/width/190/height/190" alt="16533100-23428604"/></a>
        <br>Hvis du vil undgå at betale porto, <a href="http://gahk.dk/intern/PR/">bestil gennem PR-gruppen her</a>.
        <br><br><br><br>
        
        <h3>Her er et billede af en kat. Til dig, fra netværksgruppen</h3>
        
        <img src="http://www.gahk.dk/public/image/intern/cat.gif"><br>

        <b>GAHK har fået ny fællesprinter</b><br>
        <a href="http://gahk.dk/intern/printer/canonWin.php">Guide til installation af GAHKs printer på Windows</a><br>

      
    
    Problemer med at logge på din embedsgruppes mappe på filserveren?<br>
    Netværksgruppen har lavet et program til at klare det for dig, du skal bare kunne huske koden.<br>
    Se mere <a href="http://gahk.dk/intern/gahkhjerne/">her</a>

    <br><br><br><br>
    <img src="wifilogo.png"><br>
    <b>Værd at vide om GAHKs trådløse netværk</b>

    <div style="line-height: 20pt; padding-left: 25px">
        <li>GAHKs trådløse netværk hedder : gahkwifi</li>
        <?php if(atGAHK()) echo '<li>Koden til netværket er : IAlleDeRigerOgLande1908 (denne linje vises kun hvis man sidder på GAHK)</li>'; ?>
        <li>For at bruge det trådløse netværk skal man logge ind på sin bruger og registrere sin MAC-adresse. Alle netværkskort, både til kabel og trådløse, har en MAC-adresse. <a href="http://gahk.dk/intern/mydata/">Login sker her</a></li>
            <div style="line-height: 20pt; padding-left: 25px">
                <li>Det er ligegyldigt fra hvilken computer og over hvilken netforbindelse du registrerer din MAC-adresse.</li>
                <li>Det kan tage op til et minut fra man har registreret sin MAC-adresse til man har adgang til nettet.</li>
                <li>Hvis du ikke har logget ind før, kan du få tilsendt dit alumnenummer og din kode til den email-adresse der er opgivet i alumnelisten.</li>
                <li><a href="http://gahk.dk/intern/mydata/findMacAddressPC.php" target="_blank">Windows - Guide i at finde MAC-adressen (åbnes i ny fane)</a><br></li>
                <li><a href="http://gahk.dk/intern/mydata/findMacAddressMAC.php" target="_blank">MAC - Guide i at finde MAC-adressen (åbnes i ny fane)</a><br></li>
            </div>
    </div>
</div>

<div style="float: left; padding-left: 20px;">
    <b>Begivenheder på GAHK</b><br>
    <iframe src="https://www.google.com/calendar/embed?showTitle=0&amp;showNav=0&amp;showDate=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;mode=AGENDA&amp;height=600&amp;wkst=2&amp;bgcolor=%23ffffff&amp;src=mnic13suhuvarq6ffitg2j30m4%40group.calendar.google.com&amp;color=%23BE6D00&amp;ctz=Europe%2FCopenhagen" style=" border:solid 1px #777 " width="250" height="500" frameborder="0" scrolling="no"></iframe>
        <br>
        <a href="http://gahk.dk/intern/andet/kalender.php">Se kalenderen i fuldskærm</a>
        <?php
            if(atGAHK()) {
                br(2);
                echo 'Tilføj noget til kalenderen:<br>';
                echo '<a href="https://www.google.com/accounts/ServiceLogin?service=cl&passive=true&nui=1&continue=http%3A%2F%2Fwww.google.com%2Fcalendar%2Frender&followup=http%3A%2F%2Fwww.google.com%2Fcalendar%2Frender">Log ind her</a><br>';
                echo 'E-mail: gahkkalender<br>';
                echo 'Adgangskode: nokolugter';
            }
        ?>
</div>


<?php
insertFooter();
?>