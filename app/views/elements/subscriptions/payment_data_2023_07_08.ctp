<?

/* PRODUZIONE * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  */


$key_start = "ra3W-vHVPfKDHY5d5xR82rC--aECsxcVXC-kXFRnjyaW-sCFjYR8Gg-NMzZ9fL-ZnTwdprpsYSHN-tSK-P----yzLc-qcf-gCX5c";

$urlms = "https://atpos.ssb.it/vpos/payments/main?PAGE";

$page_payment = "https://atpos.ssb.it/atpos/pagamenti/main";

$shopid = "880022537000001";

$orderid = $_GET['verifyid']; // ambiente di produzione


/* AMBIEBTE DI TEST * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  */

/* 

	$key_start = "cZDgeAb-VsVbLy6238ckxy8LURSczRcAY-gSn-st---nsJnGPr-eYRZM2-EhGpe-hBfRFFq-PCH-WZ7-ZB2hj-YZKDzMT7--R-eG";

	$urlms = "https://atpostest.ssb.it/vpos/payments/main?PAGE";

	$page_payment = "https://atpostest.ssb.it/atpos/pagamenti/main";

	$shopid = "200911123701394";

	$orderid = "midlandsport".$_GET['verifyid']; // ambiente di test

*/



$amount = $_GET['totale'] * 100;

$currency = "978";

$accountingmode = "D";

$authormode = "I";

$page = "LAND";
?>