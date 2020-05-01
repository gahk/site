<?php

function insideGAHK() {
    $gahkip = array("192.38.116.242","192.38.116.243","192.38.116.244","192.38.116.245","192.38.116.246");

    if(in_array($_SERVER['REMOTE_ADDR'],$gahkip)) {
        return true;
    } else {
        return false;
    }
}

function isInspektion() {
	return $this->session->userdata('indstilling');
}

?>