<?php
include '../delt.php';
include '../validEmail.php';
insertHeader("Mailingliste", "Denne side anvendes til at sende en meddelelse til mailinglisten");

$typedpassword = $_POST["typedpassword"];

function mail_attachment ($from , $to, $subject, $message, $attachment){
	$fileatt = $attachment; // Path to the file                  
	$fileatt_type = "application/octet-stream"; // File Type 
	$start=	strrpos($attachment, '/') == -1 ? strrpos($attachment, '//') : strrpos($attachment, '/')+1;
	$fileatt_name = substr($attachment, $start, strlen($attachment)); // Filename that will be used for the file as the attachment 

	$headers = "From: ".$from;

	$file = fopen($fileatt,'rb'); 
	$data = fread($file,filesize($fileatt)); 
	fclose($file); 
		
	$semi_rand = md5(time()); 
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
	$headers .= "\nMIME-Version: 1.0\n" . 
			"Content-Type: multipart/mixed;\n" . 
			" boundary=\"{$mime_boundary}\""; 
	$email_message .= "This is a multi-part message in MIME format.\n\n" . 
				"--{$mime_boundary}\n" . 
				"Content-Type:text/html; charset=\"iso-8859-1\"\n" . 
			   "Content-Transfer-Encoding: 7bit\n\n" . 
	$message . "\n\n";
	$data = chunk_split(base64_encode($data)); 
	$email_message .= "--{$mime_boundary}\n" . 
			  "Content-Type: {$fileatt_type};\n" . 
			  " name=\"{$fileatt_name}\"\n" . 
			  //"Content-Disposition: attachment;\n" . 
			  //" filename=\"{$fileatt_name}\"\n" . 
			  "Content-Transfer-Encoding: base64\n\n" .
	$data . "\n\n" . 
			  "--{$mime_boundary}--\n";

	if(mail($to, $subject, $email_message, $headers)) {
		echo('<b><font color="green">Email sendt med vedhæftet fil.</font></b><br>');
		unlink($attachment); // delete a file after attachment sent.
	} else {	
		die('<b><font color="red">Emailen kunne ikke afsendes.</font></b><br>'); 
	}
}

function extractMailList($mode) {
	global $username, $password, $database;
	mysql_connect(localhost,$username,$password);
	@mysql_select_db($database) or die( "Unable to select database");
	
	$query="SELECT * FROM internmailliste";
	$result=mysql_query($query);
	$num=mysql_numrows($result);
	
	$toReturn="";
	$i=0;
	while ($i < $num) {
		if($mode=="list") {
			$toReturn .= mysql_result($result,$i,"email");
			if($i < $num-1) $toReturn .= "<br>";
		}
		elseif($mode=="recipientlist") {
			if(validEmail(mysql_result($result,$i,"email"))) {
				$toReturn .= mysql_result($result,$i,"email");
				if($i < $num-1) $toReturn .= ",";
			}
		}
	$i++;
	}
		
	mysql_close();
	return $toReturn;//"nielsoleholck@gmail.com";
}

if($typedpassword==$adminpassword) {
	echo('<b><font color="green">Adgang godkendt.</font></b><br>');
	
	if($_POST["action"]=="sendMail") {
		$from = "interngahk@gahk.dk";
		$to = extractMailList("recipientlist");
		$subject = $_POST["subject"];
		$body = $_POST["body"];
		$body2 = "Denne tjeneste kan afmeldes på www.gahk.dk/intern/mailliste";
			
		if($_FILES["upfile"]['name']>'') {
			move_uploaded_file($_FILES["upfile"]["tmp_name"],'temp/'.basename($_FILES['upfile']['name']));
			mail_attachment($from, $to, $subject, $body.'<html><br><br></html>'.$body2, ("temp/".$_FILES["upfile"]["name"]));
		} else {
			$headers = 'From: '.$from. "\r\n" . 'X-Mailer: PHP/' . phpversion();
			if (mail($to, $subject, $body."\n\n".$body2, $headers)) {
				echo('<b><font color="green">Mail sendt.</font></b><br>');
			} else {
				echo('<b><font color="red">Mail ikke sendt.</font></b><br>');
			}
			if($chked==0) unlink($upfile_name);
		}
	} else {
		echo('<form name="filepost" method="post" action="mailadmin.php" enctype="multipart/form-data" id="file">');
		echo('<input type="hidden" name="action" value="sendMail"/>');
		echo('<input type="hidden" name="typedpassword" value="' . $typedpassword . '"/>');
		echo('Emne: <input type="text" name="subject"/><br>');
		echo('Besked:<br><textarea id="t4" name="body" rows="10" cols="49"></textarea><br />');
		echo('Vedhæft fil:<br>');
		echo('<input type="file" size=40 name="upfile"><br>');
		echo('<input type="submit" value="Udsend besked"/></form>');
		
		echo('<br><br><br>');
		
		/*echo('<form method="post" action="mailadmin.php">');
		echo('<input type="hidden" name="action" value="seeList"/>');
		echo('<input type="hidden" name="typedpassword" value="' . $typedpassword . '"/>');
		echo('<input type="submit" value="Se liste over tilmeldte mailadresser"/></form>');*/
		
		//if($_POST["action"]=="seeList") {
			$list = extractMailList("list");
			echo (substr_count($list, "@")) . ' mailadresse(r) tilmeldt.<br><br>' . $list;
		//}
	}
	
} else {
	if($_POST["action"]=="sendPassword") {
		$headers = 'From: kvotient@gahk.dk' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
		if (mail("nielsoleholck@gmail.com", "password til gahk.dk/intern/mailliste", $password, $headers)) {
			echo("<b><font color=\"green\">Koden er sendt til Indstillingen.</font></b><br><br><br>");
		} else {
			echo("<b><font color=\"red\">Fejl i afsendelse af koden. Kontakt Niels Ole.</font></b><br><br><br>");
		}
	} else {
		echo($typedPassword . '<br><br>');
		echo("<b><font color=\"red\">Fejl i login. Koden er ikke korrekt.</font></b><br><br>");

		echo('<form action="index.php" method="get">');
		echo('<input type="submit" value="Tilbage"/></form>');

		echo('<form action="mailadmin.php" method="post">');
		echo('<input type="hidden" name="action" value="sendPassword"/>');
		echo('<input type="submit" value="Send koden til indstillingens mail"/></form>');
	}
}
?>

</body>
</html>