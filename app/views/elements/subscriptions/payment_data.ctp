<?

$authorization = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJxRlItM0w0SGo2RzNWbmNEYmo0alp6YmNhV2lMNmRtNmlYYUNtck9IQ2RRIn0.eyJleHAiOjIwMDQ0MjMyNTQsImlhdCI6MTY4OTA2NTM2OSwiYXV0aF90aW1lIjoxNjg5MDY1MzY5LCJqdGkiOiI4N2Q0ZmI1NS00YmYwLTQ0ZTktOTM1MS1hM2RmZGZiOGY1MDEiLCJpc3MiOiJodHRwczovL3Nzby5heGVwdGEuaXQvYXV0aC9yZWFsbXMvTWVyY2hhbnQiLCJhdWQiOlsicGctcGF5bWVudC1hcGktaW5ldCIsInBnLXBheW1lbnQtYXBpLWluZXQtc2FuZGJveCIsImFjY291bnQiXSwic3ViIjoiNDlkMjJmODYtNDI1My00MjMxLWI3MjQtZDJhMjdiYjBiNGIwIiwidHlwIjoiQmVhcmVyIiwiYXpwIjoicGctcGF5bWVudC1hcGktaW5ldCIsInNlc3Npb25fc3RhdGUiOiI0MzlmNmNjZS1hODgxLTQ5MTktYWMwNS1mMGZiM2U5YmI2YzIiLCJhY3IiOiIxIiwiYWxsb3dlZC1vcmlnaW5zIjpbImh0dHBzOi8vcGF5LmF4ZXB0YS5pdCJdLCJyZWFsbV9hY2Nlc3MiOnsicm9sZXMiOlsib2ZmbGluZV9hY2Nlc3MiLCJ1bWFfYXV0aG9yaXphdGlvbiJdfSwicmVzb3VyY2VfYWNjZXNzIjp7InBnLXBheW1lbnQtYXBpLWluZXQiOnsicm9sZXMiOlsidXNlciJdfSwicGctcGF5bWVudC1hcGktaW5ldC1zYW5kYm94Ijp7InJvbGVzIjpbInVzZXIiXX0sImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoib3BlbmlkIGVtYWlsIG9mZmxpbmVfYWNjZXNzIHByb2ZpbGUiLCJlbWFpbF92ZXJpZmllZCI6ZmFsc2UsIm5hbWUiOiJsdWNhIG1hcmUnIiwicHJlZmVycmVkX3VzZXJuYW1lIjoibHVjYW1hcmVAbWlkbGFuZGV1cm9wYS5jb20iLCJnaXZlbl9uYW1lIjoibHVjYSIsImZhbWlseV9uYW1lIjoibWFyZSciLCJlbWFpbCI6Imx1Y2FtYXJlQG1pZGxhbmRldXJvcGEuY29tIn0.lgmfbgIjnDP9x_mElqHkr6uyHre4BuJ6RXn1n7VDOAiucR-HvtJlXxc-5xV-S1A2Ni5dN_OHvOutzhMKAdi6lErS31YKNZZgmKynSLZljmO1opKqLEZqamx4zi1n9ll09-Sl5hq3Kww9mfuywv8vBNSbK8laWRB_xtN8deGEUGfl-J4y9OOAkBX1OjUinlcTVaitu6ZooALGmg3jmX-dm1c8byzefuMrplOTHk51mb6WfKilELrv0Y1eeDcGHsQyJfLxO_1IFnSNYCsqIbI8p_1QteZMNYKHRWScJrCUf9jyIQRXKKrm5sBphTuvrY9n-VCp60IysR40lc6z6qKAIQ';

$x_license_key = 'PWC9YE8-JGH4VS4-QGSZRFK-Y76B7FN';

$key_easy_checkout = "K2C4H5E-XHRMZ49-J6228CS-8PREQEF";

$CURLOPT_URL = 'https://pay.axepta.it/api/v1/payment/initPayment';

$amount = sprintf("%0.2f", $_GET['totale']);
$shopID = "shopID" . rand(1000, 9999) . uniqid();



$jayParsedAry = [
    "transaction_type" => "PURCHASE",
    "currency" => "EUR",
    "language" => "IT",
    "amount" => $amount,
    "transaction_timeout" => "30000",
    "shopID" => $shopID,
    "addressesURI" => "https://" . $_SERVER['HTTP_HOST'],
    "redirect_successUrl" => "https://" . $_SERVER['HTTP_HOST'] . "/sections/tesseramentoverify/" . $_GET['verifyid'],
    "redirect_failureUrl" => "https://" . $_SERVER['HTTP_HOST'] . "/sections/tesseratimodify/" . $_GET['verifyid'],
    "addressesURI" => "https://" . $_SERVER['HTTP_HOST'],
];

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://pay.axepta.it/api/v1/payment/initPayment',
    CURLOPT_URL => $CURLOPT_URL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($jayParsedAry),
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $authorization,
        'x-license-key: ' . $x_license_key
    ),
));

$response = curl_exec($curl);
curl_close($curl);
ob_start();
echo $response;
$response_curl = ob_get_clean();
?>
<script src="https://pay.axepta.it/sdk/axepta-pg-redirect.js"></script>
<script>
    var axeptaClient = new AxeptaSDKClient("https://pay.axepta.it", "<?= $key_easy_checkout ?>");
</script>
