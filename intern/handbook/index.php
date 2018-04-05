<?php
include '../delt.php';
insertHeader("GAHKs digitale håndbog", "GAHKs digitale håndbog");
?>
<head>
    <link type="text/css" href="style.css" rel="stylesheet"/>
    <link type="text/css" href="content.css" rel="stylesheet"/>
    <link type="text/css" href="word.css" rel="stylesheet"/>
</head>

<?php
include('../adodb5/adodb.inc.php');
$db = ADONewConnection('mysql');
//	$db->debug = true;
$db->Connect('localhost', $username, $password, $database); //parameters are from delt.php
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$db->Execute("SET NAMES utf8");

$access = 0;
if(atGAHK()) {
    $access = 1;
}
$userIP = $_SERVER['REMOTE_ADDR'];
if($_POST['typedpassword'] === $userpassword || $_POST['typedpassword'] === $adminpassword_handbook) {
    $access = 1;
    date_default_timezone_set("Europe/Copenhagen");
    $accessUntil = date("Y-m-d H:i:s",time()+3600);
    $entry = array('ip'=>$userIP,'accessUntil'=>$accessUntil);
    $rs = $db->GetAll("SELECT * FROM intern_handbook_access");

    if(in_array($userIP,reduceArrayArray($rs, 'ip'))) {
        $db->AutoExecute('intern_handbook_access',$entry,'UPDATE',"intern_handbook_access.ip = '$userIP'");
    } else {
        $db->AutoExecute('intern_handbook_access',$entry,'INSERT');
    }
} else {

    date_default_timezone_set("Europe/Copenhagen");
    $now = date("Y-m-d H:i:s");
    $rs = $db->GetAll("SELECT ip FROM intern_handbook_access WHERE intern_handbook_access.accessUntil >= '$now'");
    if(in_array($userIP,reduceArrayArray($rs, 'ip')))
        $access = 1;
}

if($access) {
    $rs = $db->GetAll("SELECT * FROM intern_handbook WHERE displayed = 1 ORDER BY intern_handbook.orderIndex ASC");

    br(2);
    echo '<div class="leftBlock">';
        echo '<ul>';
        foreach($rs as $key => $entry) {
            $current = "";
            if($entry['ID']===$_GET['id']) {
                $current = ' class="current"';
                $content = $entry['content'];
            }
            echo '<li'.$current.'><a href="index.php?id='.$entry['ID'].'">'.$entry['title'].'</a></li>';
            
            if($current && substr_count($entry['content'],'<h2>') > 1) {
                
                $matches = array();
                preg_match_all("/<h2>(.*?)<\/h2>/", $content, &$matches);

                foreach($matches[0] as $key => $fullStr) {
                    $h1Str = strip_tags($fullStr);
                    if(trim($h1Str)) {
                        echo '<ul><li><a href="#'.($key+1).'">'.$h1Str.'</a></li></ul>';
                        $content = str_replace($fullStr, '<a name="'.($key+1).'"></a>'.$fullStr, $content);
                    }
                }
            }
        }
        echo '</ul>';
    echo '</div>';

    if($_GET['id'] != "") {
        echo '<div class="rightBlock">';
            echo $content;
        echo '</div>';
    }
    echo '<div style="clear:both;"></div>';
} else {
    ?>
    <form action="index.php?id=11" method="post" name="chooseList">
        <label for="typedpassword">Kodeord : </label><input type="password" size="25" name="typedpassword" id="typedpassword" value=""/>
        <input type="submit" value="Log ind" /><br>
    </form>
    <?php
    if($_POST['typedpassword'])
        redText('Log ind fejlede.');
}

$db->Close();
insertFooter();
?>