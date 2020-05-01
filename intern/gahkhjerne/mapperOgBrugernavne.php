<?php
include '../delt.php';
insertHeader("GAHK-Hjerne", "GAHK-Hjerne - Oversigt over mapper og brugernavne på filserveren");
?>
<head>
    <style type="text/css">
    td,th {
        padding: 5px;
        border: 1px solid black;
    }
    tr {
        border: 1px solid black;
    }
    </style>
</head>


<table border="1">

    <tr>
        <td align="center" colspan="3"><b>Mapper på filserveren der ikke kræver kodeord</b></td>
    </tr>

    <tr>
        <th>Mappe</th>
        <th>Sti</th>
        <th>Bruger</th>
    </tr>



    <tr>
        <td>Public (ingen kode krævet)</td>
        <td>\\GAHK-HJERNE\Public</td>
        <td>Alle brugere har adgang</td>
    </tr>

    <tr>
        <td>GAHK Foto (kun fotogruppen har skriveadgang)</td>
        <td>\\GAHK-HJERNE\GAHK Foto</td>
        <td>Alle brugere har adgang</td>
    </tr>

    <tr>
        <td>GAHK Foto Upload</td>
        <td>\\GAHK-HJERNE\GAHK Foto Upload</td>
        <td>Alle brugere har adgang</td>
    </tr>

    <tr>
        <td align="center" colspan="3" style="border: none;"><div style="height: 20px;"></div></td>
    </tr>
    <tr>
        <td align="center" colspan="3"><b>Embedsgruppemapper (kræver kodeord)</b></td>
    </tr>

    <tr>
        <th>Mappe</th>
        <th>Sti</th>
        <th>Bruger</th>
    </tr>

    <tr>
        <td>AK-gruppens mappe</td>
        <td>\\GAHK-HJERNE\AK</td>
        <td>ak</td>
    </tr>

    <tr>
        <td>Netværksgruppens mappe</td>
        <td>\\GAHK-HJERNE\Netvaerk</td>
        <td>netvaerk</td>
    </tr>

    <tr>
        <td>Ølkælderens mappe</td>
        <td>\\GAHK-HJERNE\Oelkaelder</td>
        <td>oelkaelder</td>
    </tr>

    <tr>
        <td>Festgruppens mappe</td>
        <td>\\GAHK-HJERNE\Fest</td>
        <td>fest</td>
    </tr>

    <tr>
        <td>PR-gruppens mappe</td>
        <td>\\GAHK-HJERNE\PR</td>
        <td>pr</td>
    </tr>

    <tr>
        <td>Reppernes mappe</td>
        <td>\\GAHK-HJERNE\Repper</td>
        <td>repper</td>
    </tr>

    <tr>
        <td>Køkkengruppens mappe</td>
        <td>\\GAHK-HJERNE\Koekken</td>
        <td>koekken</td>
    </tr>

    <tr>
        <td>Kalorietællernes mappe</td>
        <td>\\GAHK-HJERNE\Kalorie</td>
        <td>kalorie</td>
    </tr>

    <tr>
        <td>Legatgruppens mappe</td>
        <td>\\GAHK-HJERNE\Legat</td>
        <td>legat</td>
    </tr>

    <tr>
        <td>Kalorietællernes mappe</td>
        <td>\\GAHK-HJERNE\Kalorie</td>
        <td>kalorie</td>
    </tr>

    <tr>
        <td>Pylongruppens mappe</td>
        <td>\\GAHK-HJERNE\Pylon</td>
        <td>pylon</td>
    </tr>
    
    <tr>
        <td>Indstillingens mappe</td>
        <td>\\GAHK-HJERNE\Indstillingen</td>
        <td>indstillingen</td>
    </tr>
    
    <tr>
        <td>Inspektionens mappe</td>
        <td>\\GAHK-HJERNE\Inspektionen</td>
        <td>inspektionen</td>
    </tr>

</table>

<?php
insertFooter();
?>