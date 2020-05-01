<?php
include '../delt.php';
if(atGAHK()) {
    header( 'Location: http://192.168.0.1' ) ;
} else {
    header( 'Location: http://www.gahk.dk/intern/?errorMessage=Gahki kan kun tilgås hvis man befinder sig på GAHK.' ) ;
}
?>