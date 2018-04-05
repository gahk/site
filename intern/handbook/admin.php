<?php
include '../delt.php';
include 'config.php';
insertHeader("Admin - GAHKs digitale håndbog", "Admin - GAHKs digitale håndbog");
?>
<head>
    
<!-- TinyMCE -->
<script type="text/javascript" src="../tools/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "../tools/tinymce/examples/lists/template_list.js",
		external_link_list_url : "../tools/tinymce/examples/lists/link_list.js",
		external_image_list_url : "../tools/tinymce/examples/lists/image_list.js",
		media_external_list_url : "../tools/tinymce/examples/lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<!-- /TinyMCE -->

</head>


<?php
echo '<body onLoad="document.forms.loginForm.typedpassword.focus()">';

//print_arr($_POST);

if($_POST["typedpassword"]===$adminpassword_handbook) $access=1;		//admin access

if(!$access) {
    if($_POST["typedpassword"]) redText("Log ind fejlede. Forkert kode.");
    echo '<form action="admin.php" method="post" name="loginForm">';
    echo '<label for="typedpassword">Kodeord : </label><input type="password" size="25" name="typedpassword" id="typedpassword" value=""/><br>';
    echo '<input type="submit" value="Log ind" /><br>';
    echo '</form>';
} else {
    /////////////////// ADMIN ACCESS ///////////////////
    include('../adodb5/adodb.inc.php');
    $db = ADONewConnection('mysql');
    //	$db->debug = true;
    $db->Connect('localhost', $username, $password, $database); //parameters are from delt.php
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $db->Execute("SET NAMES utf8");

    /////////////////// EVALUATE POST ARRAY ///////////////////
    if($_POST['action'] === 'newPage') {
        $entry = array('title'=>$_POST['newPageTitle']);
        $db->AutoExecute('intern_handbook',$entry,'INSERT');
        

    } elseif($_POST['action'] === 'editPage') {
        $id = $_POST['ID'];
        $rs = $db->GetRow("SELECT * FROM intern_handbook WHERE ID = $id");
        echo '<h2>Siden der redigeres er: '.$rs['title'].'</h2>';
        ?>
        <form action="admin.php" method="post">
        Ny titel: <input type="text" name="title" value="<?php echo $rs['title']; ?>"/>
        Nyt rækkefølgeindex: <input type="text" name="orderIndex" value="<?php echo $rs['orderIndex']; ?>"/>
        <input type="hidden" name="typedpassword" value="<?php echo $_POST["typedpassword"] ?>"/>
        <input type="hidden" name="action" value="savePage"/>
        <input type="hidden" name="ID" value="<?php echo $_POST["ID"] ?>"/>
            <div>
                <textarea id="content" name="content" rows="35" cols="80" >
                    <?php echo $rs['content']; ?>
                </textarea>
            </div>
        <input type="submit" value="Gem ændringer" />
        </form>
        <?php
        
    } elseif($_POST['action'] === 'savePage') {
        $entry = array('title'=>$_POST["title"],'content'=>$_POST["content"],'orderIndex'=>$_POST["orderIndex"]);
        $db->AutoExecute('intern_handbook',$entry,'UPDATE',"intern_handbook.ID = '".$_POST["ID"]."'");


    } elseif($_POST['action'] === 'editorderIndex') {
        foreach($_POST['ID'] as $key => $id) {
            $entry = array('orderIndex' => $_POST['orderIndex'][$key], 'displayed' => $_POST['displayed'][$key]);
            $db->AutoExecute('intern_handbook',$entry,'UPDATE',"intern_handbook.ID = '$id'");
        }


    } elseif($_POST['action'] === 'uploadFile') {

            if ($_FILES["file"]["error"] > 0) {
                echo "Return Code: " . $_FILES["uploadedfile"]["error"] . "<br />";
            } else {

                $target_path = "upload/";

                $target_path = $target_path . basename( $_FILES['uploadedfile']['name']);
                if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
                    echo "Filen ".  basename( $_FILES['uploadedfile']['name'])." er nu uploaded.";
                } else{
                    echo "Der var en fejl i upload af filen, prøv igen!";
                }

            }
        }
    }


    $rs = $db->GetAll("SELECT * FROM intern_handbook ORDER BY intern_handbook.orderIndex ASC");

    if($_POST['action'] != 'editPage') {
        echo '<form action="admin.php" method="post">';
        echo '<input type="hidden" name="typedpassword" value="' . $_POST["typedpassword"] . '"/>';
        echo '<input type="hidden" name="action" value="editPage"/>';
        echo '<b>Side der skal redigeres:</b><br>';
        selector("ID","",  reduceArrayArray($rs, 'ID'),reduceArrayArray($rs, 'title'));
        echo '<br><input type="submit" value="Rediger side" /><br>';
        echo '</form>';

        ?>
        <br><br><br>
        <b>Tilføj ny side:</b>
        <form action="admin.php" method="post">
            <input type="hidden" name="typedpassword" value="<?php echo $_POST["typedpassword"]; ?>" />
            <input type="hidden" name="action" value="newPage"/>
            Titel på ny side: <input type="text" name="newPageTitle" /><br>
            <input type="submit" value="Tilføj side" />
        </form>

        <br><br><br>
        <b>Upload fil:</b>
        <form action="admin.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="typedpassword" value="<?php echo $_POST["typedpassword"]; ?>" />
            <input type="hidden" name="action" value="uploadFile"/>
            <label for="uploadedfile">Vælg fil:</label>
            <input type="file" name="uploadedfile" id="uploadedfile" />
            <br />
            <input type="submit" value="Upload" />
        </form>

        <?php
        $handle = opendir('upload/');
        if ($handle) {
            echo "<br>Filer uploaded:<br>";
            while (false !== ($file = readdir($handle))) {
                if($file != '.' && $file != '..')
                    echo '<a href="upload/'.$file.'">'.$file.'</a><br>';
            }
            closedir($handle);
        }
        ?>


        <br><br><br>
        <b>Rediger rækkefølge og visning:</b>
        <form action="admin.php" method="post">
            <input type="hidden" name="typedpassword" value="<?php echo $_POST["typedpassword"]; ?>" />
            <input type="hidden" name="action" value="editorderIndex"/>
            <table border="1">
                <th>Titel</th>
                <th>Rækkefølge (lavest øverst)</th>
                <th>Vises</th>

            <?php
                foreach($rs as $entry) {
                    echo '<tr>';
                    echo '<td>'.$entry['title'].'</td>';
                    echo '<input type="hidden" name="ID[]" value="'.$entry['ID'].'">';
                    echo '<td><input type="text" name="orderIndex[]" value="'.$entry['orderIndex'].'"></td>';
                    $displayed = $entry['displayed'];
                    echo '<td>'.selector("displayed[]",$displayed,array(1,0),array("Vises","Vises ikke"),array('returnAsString' => 1)).'</td>';
                    echo '</tr>';
                }
            ?>
            </table>
            <input type="submit" value="Rediger rækkefølge og visning" />
        </form>

        
    }
    <?php
    $db->close();
   
}



echo '</body>';
insertFooter();
?>