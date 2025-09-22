                                            
<?php
/*
  // Pagamento semplice - Avvio pagamento

  // Alias e chiave segreta
  $ALIAS = '<ALIAS>'; // Sostituire con il valore fornito da Nexi
  $CHIAVESEGRETA = '<CHIAVE SEGRETA PER CALCOLO MAC>'; // Sostituire con il valore fornito da Nexi

  $requestUrl = "https://int-ecommerce.nexi.it/ecomm/ecomm/DispatcherServlet";
  $merchantServerUrl = "https://" . $_SERVER['HTTP_HOST'] . "/xpay/php/pagamento_semplice/codice_base/";

  $codTrans = "TESTPS_" . date('YmdHis');
  $divisa = "EUR";
  $importo = 5000;

  // Calcolo MAC
  $mac = sha1('codTrans=' . $codTrans . 'divisa=' . $divisa . 'importo=' . $importo . $CHIAVESEGRETA);

  // Parametri obbligatori
  $obbligatori = array(
  'alias' => $ALIAS,
  'importo' => $importo,
  'divisa' => $divisa,
  'codTrans' => $codTrans,
  'url' => $merchantServerUrl . "esito.php",
  'url_back' => $merchantServerUrl . "annullo.php",
  'mac' => $mac,
  );

  // Parametri facoltativi
  $facoltativi = array(
  );

  $requestParams = array_merge($obbligatori, $facoltativi);

  print_r($requestParams);
 */
?>

<?php
//$URLMS          = "https://midlandsport.it?URLMS";
$URLMS          = "https://atpos.ssb.it/vpos/payments/main?PAGE";
$URLDONE        = "https://midlandsport.it?URLDONE";
$URLBACK        = "https://midlandsport.it?URLBACK";
$ORDERID        = "234";
$SHOPID         = "880022537000001";
$AMOUNT         = "200";
$CURRENCY       = "978";
$ACCOUNTINGMODE = "D";
$AUTHORMODE     = "I";
$CHIAVESEGRETA  = "ra3W-vHVPfKDHY5d5xR82rC--aECsxcVXC-kXFRnjyaW-sCFjYR8Gg-NMzZ9fL-ZnTwdprpsYSHN-tSK-P----yzLc-qcf-gCX5c";


//$CHIAVESEGRETA  = "";
// Calcolo MAC
//$string = 'URLMS=' . $URLMS . '&URLDONE=' . $URLDONE . '&ORDERID=' . $ORDERID . '&SHOPID=' . $SHOPID . '&AMOUNT=' . $AMOUNT . '&CURRENCY=' . $CURRENCY . '&ACCOUNTINGMODE=' . $ACCOUNTINGMODE . '&AUTHORMODE=' . $AUTHORMODE . '&' . $CHIAVESEGRETA;
//$mac    = sha1(trim($string));

$string         = 'URLMS=' . $URLMS . '&URLDONE=' . $URLDONE . '&ORDERID=' . $ORDERID . '&SHOPID=' . $SHOPID . '&AMOUNT=' . $AMOUNT . '&CURRENCY=' . $CURRENCY . '&ACCOUNTINGMODE=' . $ACCOUNTINGMODE . '&AUTHORMODE=' . $AUTHORMODE;
$mac            = hash_hmac('sha256', $string, $CHIAVESEGRETA);
;

// Parametri obbligatori
$obbligatori = array(
    'URLMS'          => $URLMS,
    'URLDONE'        => $URLDONE,
    'URLBACK'        => $URLBACK,
    'ORDERID'        => $ORDERID,
    'SHOPID'         => $SHOPID,
    'AMOUNT'         => $AMOUNT,
    'CURRENCY'       => $CURRENCY,
    'ACCOUNTINGMODE' => $ACCOUNTINGMODE,
    'AUTHORMODE'     => $AUTHORMODE,
    'MAC'            => $mac,
    'PAGE'           => 'LAND',
);

// Parametri facoltativi
$facoltativi = array(
);

$requestParams = array_merge($obbligatori);

print "<br><hr>";

foreach ($requestParams as $name => $value):
    echo sprintf("<p>%s: %s</p>", $name, $value);
endforeach;

print "<br><hr>";

print_r($string);

print "<br><hr>";



$requestUrl = "https://atpostest.ssb.it/atpos/pagamenti/main";
//$requestUrl = "https://atpostest.ssb.it/atpos/pagamenti/main?PAGE=LAND";

echo "request URL: $requestUrl";

print "<br><hr>";

echo "CHIAVE GENERAZIONE MAC:<br> {$CHIAVESEGRETA}";

print "<br><hr>";
?>

<html>
    <head></head>
    <body>
        <form method='POST' action='<?php echo $requestUrl ?>'>
            <?php foreach ($requestParams as $name => $value): ?>
                <input type='hidden' name='<?php echo $name; ?>' value='<?php echo htmlentities($value); ?>' />
            <?php endforeach; ?>

            <input type='submit' value='VAI ALLA PAGINA DI CASSA' />
        </form>
    </body>
</html>

