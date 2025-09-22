<?php
////$URLMS          = "https://midlandsport.it?URLMS";
//$URLMS          = "https://atpos.ssb.it/vpos/payments/main?PAGE";
//$URLDONE        = "https://midlandsport.it?URLDONE";
//$URLBACK        = "https://midlandsport.it?URLBACK";
//$ORDERID        = "234";
//$SHOPID         = "880022537000001";
//$AMOUNT         = "200";
//$CURRENCY       = "978";
//$ACCOUNTINGMODE = "D";
//$AUTHORMODE     = "I";
//$CHIAVESEGRETA  = "ra3W-vHVPfKDHY5d5xR82rC--aECsxcVXC-kXFRnjyaW-sCFjYR8Gg-NMzZ9fL-ZnTwdprpsYSHN-tSK-P----yzLc-qcf-gCX5c";
//
//
////$CHIAVESEGRETA  = "";
//// Calcolo MAC
////$string = 'URLMS=' . $URLMS . '&URLDONE=' . $URLDONE . '&ORDERID=' . $ORDERID . '&SHOPID=' . $SHOPID . '&AMOUNT=' . $AMOUNT . '&CURRENCY=' . $CURRENCY . '&ACCOUNTINGMODE=' . $ACCOUNTINGMODE . '&AUTHORMODE=' . $AUTHORMODE . '&' . $CHIAVESEGRETA;
////$mac    = sha1(trim($string));
//
//$string = 'URLMS=' . $URLMS . '&URLDONE=' . $URLDONE . '&ORDERID=' . $ORDERID . '&SHOPID=' . $SHOPID . '&AMOUNT=' . $AMOUNT . '&CURRENCY=' . $CURRENCY . '&ACCOUNTINGMODE=' . $ACCOUNTINGMODE . '&AUTHORMODE=' . $AUTHORMODE;
//$mac    = hash_hmac('sha256', $string, $CHIAVESEGRETA);
//;
//
//// Parametri obbligatori
//$obbligatori = array(
//    'URLMS'          => $URLMS,
//    'URLDONE'        => $URLDONE,
//    'URLBACK'        => $URLBACK,
//    'ORDERID'        => $ORDERID,
//    'SHOPID'         => $SHOPID,
//    'AMOUNT'         => $AMOUNT,
//    'CURRENCY'       => $CURRENCY,
//    'ACCOUNTINGMODE' => $ACCOUNTINGMODE,
//    'AUTHORMODE'     => $AUTHORMODE,
//    'MAC'            => $mac,
//    'PAGE'           => 'LAND',
//);
//
//// Parametri facoltativi
//$facoltativi = array(
//);
//
//$requestParams = array_merge($obbligatori);
//
//print "<br><hr>";
//
//foreach ($requestParams as $name => $value):
//    echo sprintf("<p>%s: %s</p>", $name, $value);
//endforeach;
//
//print "<br><hr>";
//
//print_r($string);
//
//print "<br><hr>";
//
//
//
//$requestUrl = "https://atpostest.ssb.it/atpos/pagamenti/main";
////$requestUrl = "https://atpostest.ssb.it/atpos/pagamenti/main?PAGE=LAND";
//
//echo "request URL: $requestUrl";
//
//print "<br><hr>";
//
//echo "CHIAVE GENERAZIONE MAC:<br> {$CHIAVESEGRETA}";
//
//print "<br><hr>";
?>
<? include __DIR__ . '/../controllers/checkout_controller.php'; ?>
<?
$checkout = new Checkout();

$parameters    = $checkout->parameters;
$requestParams = $checkout->requestParams;
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Hugo 0.88.1">
        <title>Checkout example · Bootstrap v5.1</title>

        <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/checkout/">



        <!-- Bootstrap core CSS -->
        <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }
        </style>


        <!-- Custom styles for this template -->
        <link href="../assets/form-validation.css" rel="stylesheet">
    </head>
    <body class="bg-light">

        <div class="container-xxl">
            <main>
                <div class="py-5 text-center">
                    <h2>Checkout form (SIA VPOS)</h2>
                </div>
                <div class="row parameters_input">
                    <div class="col-lg">
                        <div id="key_text">
                            <label for="username" class="form-label"> </label>
                            <div class="input-group has-validation">
                                <span class="input-group-text">KEY START</span>
                                <input type="text" class="form-control form-control-sm parameters" id="key_start" placeholder="" value="<?= $parameters['key_start'] ?>" readonly="">

                            </div>
                        </div>


                        <div id="page_text">
                            <label for="username" class="form-label"> </label>
                            <div class="input-group has-validation">
                                <span class="input-group-text">PAGE PAYMENT</span>
                                <input type="text" class="form-control form-control-sm parameters" id="page_payment" placeholder="" value="<?= $parameters['page_payment'] ?>" readonly="">

                            </div>
                        </div>
                        <hr>
                        <button type="button"  class="btn btn-info edit_parameters">MODIFICA</button>
                        <button type="button" id="save_parameters" class="btn btn-success" style="display: none">SALVA</button>
                        <button type="button"  class="btn btn-warning back_parameters" style="display: none">ANNULLA</button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg string">

                    </div>

                </div>
                <hr>
                <div class="row g-5">


                    <div class="col-md-7 col-lg-8">


                        <form class="needs-validation" method='POST' action='<?= $parameters['page_payment'] ?>' target="_blank">
                            <div class="row g-3">


                                <? // foreach ($obbligatori as $name => $value): ?>
                                <? foreach ($requestParams as $name => $value): ?>
                                    <div class="col-12">
                                        <?
                                        $add = "";
                                        if ($name == "MAC")
                                            $add = " (hash:   HMAC-256)"
                                            ?>
                                        <label  class="form-label"><?= $name . $add ?></label>
                                        <input type="string" class="form-control  form-control-sm  input-to-send" id="<?= $name ?>" name="<?= $name ?>" value="<?= $value ?>" placeholder="<?= $name ?>">
                                        <div class="invalid-feedback">
                                            Please enter a valid value.
                                        </div>
                                    </div>
                                <? endforeach; ?>



                            </div>

                            <hr class="my-4">

                            <button id="send_form" class="w-100 btn btn-primary btn-lg" type="submit" style="display: none">Continue to checkout</button>

                        </form>

                        <button id="save_and_send" class="w-100 btn btn-primary btn-lg">Continue to checkout</button>
                    </div>
                </div>
            </main>

            <footer class="my-5 pt-5 text-muted text-center text-small">

            </footer>
        </div>


        <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
        <!--<script src="form-validation.js"></script>-->
        <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
        <script>

            /*
             
             to_send = {};
             
             $.post(urlPost, to_send, function (data)
             {
             console.log(data);
             });
             
             */

            urlPost = '../functions/checkout_functions.php'

            $(function ()
            {
                calculating_mac();

                $("#save_and_send").click(function ()
                {
                    to_send = {};

                    to_send['save_request_params'] = {};

                    $(".input-to-send").each(function ()
                    {
                        id = $(this).attr('id');
                        value = $(this).val();
                        to_send['save_request_params'][id] = value;
                    });

                    $.post(urlPost, to_send, function (data)
                    {
                        console.log(data);
                    });

                    $("#send_form").trigger('click');
                });

                $(".input-to-send").keyup(function ()
                {
                    calculating_mac();
                });

                $("#save_parameters").click(function ()
                {
                    to_send = {};
                    to_send['save_parameters'] = {};

                    $(".parameters").each(function ()
                    {
                        id = $(this).attr('id');
                        value = $(this).val();
                        to_send['save_parameters'][id] = value;
                    });

                    $.post(urlPost, to_send, function (data)
                    {
                        console.log(data);
                        location.reload();
                    });
                });

                $(".edit_parameters").click(function ()
                {

                    $(this).toggle('fast');
                    $('.back_parameters').toggle('fast');
                    $('#save_parameters').toggle('fast');

                    $(".parameters").prop('readonly', false);
                });

                $(".back_parameters").click(function ()
                {

                    $(this).toggle('fast');
                    $('.edit_parameters').toggle('fast');
                    $('#save_parameters').toggle('fast');

                    $(".parameters").prop('readonly', true);
                });

                /* - - - - - - - - - - - - - - - - - - - - - - - - - */

                function calculating_mac()
                {
                    to_send = {};
                    to_send['calculating_mac'] = {};

                    $(".input-to-send").each(function ()
                    {
                        id = $(this).attr('id');
                        value = $(this).val();
                        to_send['calculating_mac'][id] = value;
                    });

                    to_send['calculating_mac']['key_start'] = $("#key_start").val();

                    console.log(to_send);

                    $.post(urlPost, to_send, function (data)
                    {
                        console.log(data);

                        $("#MAC").val(data['mac']);
                        $(".string").html(data['string'])
                    });
                }

            });
        </script>
    </body>
</html>
