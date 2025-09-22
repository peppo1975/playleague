<? include __DIR__ . '/../controllers/checkout_controller.php'; ?>
<? $checkout = new Checkout(); ?>
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (isset($_POST['calculating_mac']))
{
    $res = $checkout->calculating_mac();
        header('Content-Type: application/json');
    print json_encode($res);
}

if (isset($_POST['save_parameters']))
{
    $res = $checkout->save_parameters();
//    header('Content-Type: application/json');
    print json_encode($res);
}

if (isset($_POST['save_request_params']))
{
    $res = $checkout->save_request_params();
    header('Content-Type: application/json');
    print json_encode($res);
}