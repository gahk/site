<?php

function priceStrToOrens($priceStr) {
	return (int)(((double)$priceStr) * 100.0);
}

function orensToPriceStr($orens) {
	return "" + (((double)$orens) / 100.0);
}

?>