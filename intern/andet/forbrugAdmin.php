<?php
include '../delt.php';
insertHeader("GAHKs forbrug", "GAHKs forbrug");
?>
<head>
    <script type="text/javascript" src="../jscharts.js"></script>
    <script type="text/javascript" language="javascript" src="../jquery.min.js"></script>
    <script type="text/javascript" language="javascript" src="../dataTables/jquery.dataTables.min.js"></script>
    <link type="text/css" href="../dataTables/demo_table.css" rel="stylesheet" />
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('#standardTable').dataTable( {
                "aaSorting": [ [0,'desc'] ],
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": false,
                "bSort": true,
                "bInfo": false,
                "bAutoWidth": false,
                "oLanguage": {
                    sProcessing:   "Henter...",
                    sLengthMenu:   "Vis: _MENU_ linjer",
                    sZeroRecords:  "Ingen forbrugstal fundet",
                    sInfo:         "Viser _TOTAL_ forbrugstal",
                    sInfoEmpty:    "Ingen forbrugstal fundet",
                    sInfoFiltered: "(ud af _MAX_ alumner)",
                    sInfoPostFix:  "",
                    sSearch:       "Søg:",
                    sUrl:          "",
                    oPaginate: {
                        sFirst:    "Første",
                        sPrevious: "Forrige",
                        sNext:     "Næste",
                        sLast:     "Sidste"
                    }
                }
            } );
        } );
    </script>
</head>
<?php
//////////////////// Set up database connection ////////////////////
include('../adodb5/adodb.inc.php');
$db = ADONewConnection('mysql');
$db->Connect('localhost', $username, $password, $database); //parameters are from delt.php
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$db->Execute("SET NAMES utf8");

$price = 2.00; //   kr/kWh

if($_POST['typedpassword'] === $adminpassword_forbrug) {


    //////////////////// Evaluate POST array ////////////////////
    if($_POST['action'] === 'removeEntry') {
        $idToRemove = $_POST['entryToRemove'];
        $db->Execute("DELETE FROM intern_forbrug WHERE ID = '$idToRemove'");
    } elseif($_POST['action'] === 'addEntry') {
        $dataPoint = array();
        $dataPoint['ugenr'] = $_POST['weekNumber'];
        $dataPoint['aar'] = $_POST['year'];
        $dataPoint['forbrug'] = $_POST['consumption'];
        $db->AutoExecute('intern_forbrug',$dataPoint,'INSERT');
    }



    echo 'Denne uge er nummer: '.date("W",time()).'<br><br>';

    $data = $db->GetAll("SELECT * FROM intern_forbrug ORDER BY aar,ugenr ASC");

    foreach($data as &$f) {
        $f['Ugenummer'] = $f['aar'].'-'.leadingZero($f['ugenr']);
        $f['dataPoint'] = "['".$f['Ugenummer']."', ".round($f['forbrug']*$price).']';
        $f['dato'] = date("d/m/Y",StartOfWeek($f['aar'],$f['ugenr']));
        $f['dato'] .= ' - '.date("d/m/Y",3600*24*6+StartOfWeek($f['aar'],$f['ugenr']));
        $f['pris'] = round($f['forbrug']*$price).' kr';
        $f['forbrug'] .= ' kWh';
    }
    unset($f);
    
    $cols = array("Ugenummer","dato","forbrug","pris");

    echo '<div style="float:left;">';
        createDataTable($data, $cols);
        ?>
        <fieldset>
            <legend>Fjern forbrugstal</legend>
            <form id="form_remove_datapoint" action="forbrugAdmin.php" method="post"><br>
                <input type="hidden" name="typedpassword" value="<?php echo $_POST['typedpassword']; ?>">
                <input type="hidden" name="action" value="removeEntry">
                Ugenummer : <?php selector('entryToRemove', 0, array_reverse(reduceArrayArray($data, 'ID')), array_reverse(reduceArrayArray($data, 'Ugenummer'))); ?><br>
                <input type="submit" value="Fjern det valgte forbrugstal"/><br>
            </form>
        </fieldset>

        <fieldset>
            <legend>Tilføj forbrugstal</legend>
            <form id="form_add_datapoint" action="forbrugAdmin.php" method="post"><br>
                <input type="hidden" name="typedpassword" value="<?php echo $_POST['typedpassword']; ?>">
                <input type="hidden" name="action" value="addEntry">
                <label for="consumption">Forbrug i kWh : </label>
                <input type="text" name="consumption" id="consumption" method="post"><br>
                <?php $y = date("Y",time()-7*24*3600); ?>
                Ugenummer : <?php selector('year', $y, numberArray($y-1,$y+1), numberArray($y-1,$y+1)); ?><br>
                Ugenummer : <?php selector('weekNumber', date("W",time()-7*24*3600), numberArray(1,53), numberArray(1,53)); ?><br>
                <input type="submit" value="Tilføj forbrugstal"/><br>
            </form>
        </fieldset>
        <?php
    echo '</div>';

    ?>
    <div style="float:left; margin-left:20px;">
        <div id="graph">Loading graph...</div>
        <script type="text/javascript">
            <?php
            $str = implode(',',  reduceArrayArray($data, 'dataPoint'));
            $str = 'var myData = new Array('.$str.');';
            echo $str;
            ?>
            var myChart = new JSChart('graph', 'line');
            myChart.setDataArray(myData);
            myChart.setTitle('GAHKs udgifter til el [kr / uge]');
            myChart.setTitleColor('#8E8E8E');
            myChart.setTitleFontSize(10);
            myChart.setAxisNameX('');
            myChart.setAxisNameY('');
            myChart.setAxisColor('#C4C4C4');
            myChart.setAxisValuesColor('#343434');
            myChart.setAxisPaddingLeft(60);
            myChart.setAxisPaddingRight(60);
            myChart.setAxisPaddingTop(50);
            myChart.setAxisPaddingBottom(40);
            <?php
                'echo myChart.setAxisValuesNumberX('.(count(reduceArrayArray($forbrug, 'dataPoint'))).');';
            ?>
            
            myChart.setGraphExtend(true);
            myChart.setGridColor('#c2c2c2');
            myChart.setLineWidth(6);
            myChart.setLineColor('#9F0505');
            myChart.setSize(616, 321);
            myChart.setBackgroundImage('chart_bg.jpg');
            myChart.draw();
        </script>
    </div>
<?php
} else {
    redText("Log ind fejlede.");
}


function StartOfWeek($year, $week)
{
    $Jan1 = mktime(1,1,1,1,1,$year);
    $MondayOffset = (11-date('w',$Jan1))%7-3;
    $desiredMonday = strtotime(($week-1) . ' weeks '.$MondayOffset.' days', $Jan1);
    return $desiredMonday;
}

insertFooter();
?>