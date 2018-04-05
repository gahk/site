<?php

if ($_GET['download']=="list") {

header('Content-Type: text/x-vcard; charset=utf-8;');

#####################################################################
/* sendToHost
 * ~~~~~~~~~~
 * Params:
 *   $host      - Just the hostname.  No http:// or 
                  /path/to/file.html portions
 *   $method    - get or post, case-insensitive
 *   $path      - The /path/to/file.html part
 *   $data      - The query string, without initial question mark
 *   $useragent - If true, 'MSIE' will be sent as 
                  the User-Agent (optional)
 *
 * Examples:
 *   sendToHost('www.google.com','get','/search','q=php_imlib');
 *   sendToHost('www.example.com','post','/some_script.cgi',
 *              'param=First+Param&second=Second+param');
 */

function sendToHost($host,$method,$path,$data,$useragent=1)
{
    // Supply a default method of GET if the one passed was empty
    if (empty($method)) {
        $method = 'GET';
    }
    $method = strtoupper($method);
    $fp = fsockopen($host, 80);
    if ($method == 'GET') {
        $path .= '?' . $data;
    }
    fputs($fp, "$method $path HTTP/1.1\r\n");
    fputs($fp, "Host: $host\r\n");
    fputs($fp,"Content-type: application/x-www-form-urlencoded\r\n");
    fputs($fp, "Content-length: " . strlen($data) . "\r\n");
    if ($useragent) {
        fputs($fp, "User-Agent: MSIE\r\n");
    }
    fputs($fp, "Connection: close\r\n\r\n");
    if ($method == 'POST') {
        fputs($fp, $data);
    }

    while (!feof($fp)) {
        $buf .= fgets($fp,128);
    }
    fclose($fp);
    return $buf;
}

#####################################################################

$postdata='typedpassword=ymer';
$hn=sendToHost('www.gahk.dk','post','/intern/alumneliste/liste.php',$postdata);

//Tar bort toppen
$pos=strpos($hn,'<tbody>');
$hn=substr($hn, $pos);

//Tar bort botten
$pos = strpos($hn, '</tbody>');
$hn = substr($hn, 0, $pos);

preg_match_all('/<tr><td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?)<\/td>/',$hn,$matches);

for($i=0;$i<count($matches[1]);$i++){
echo "BEGIN:VCARD"."\n";
echo "VERSION:2.1"."\n";
//echo "N:Efter;For"."\n";
echo "N:".$matches[1][$i]." (GAHK) \n";
echo "FN:".$matches[1][$i]." (GAHK) \n";
echo "TEL;CELL;VOICE:".$matches[9][$i]."\n";
echo "END:VCARD"."\n";
//echo $matches[1][$i].$matches[9][$i];
}

} else {
echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
echo '<link rel="stylesheet" type="text/css" href="../menu/menu_style.css" >';
include('../menu/menu.php');

echo '<h2>Telefonliste (vcf)</h2>';
echo '<a href="?download=list">Downloade vcf-fil med alle aktuelle kontakter</a>';
echo '<BR><BR><i>Lavet af Pontus Lans - 27. september 2011</i>';
}

?>