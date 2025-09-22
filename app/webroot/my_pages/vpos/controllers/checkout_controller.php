<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Checkout
{

    public $parameters    = [];
    public $requestParams = [];





    function __construct()
    {
        $this->read_parameters();
        $this->requestParams();
    }





    private function read_parameters()
    {
        if (file_exists(__DIR__ . '/../assets/files/key_pagepayment.json'))
        {
            //apri file

            $file = json_decode(file_get_contents(__DIR__ . '/../assets/files/key_pagepayment.json'), true);

            foreach ($file as $key => $value)
            {
                $this->parameters[$key] = $value;
            }
        }
        else
        {
            //crea file
            file_put_contents(__DIR__ . '/../assets/files/key_pagepayment.json', json_encode(['key_start' => '', 'page_payment' => '']));

            $this->read_parameters();
        }
    }





    private function requestParams()
    {
        // Parametri obbligatori
        $requestParams = array(
            'URLMS'          => '',
            'URLDONE'        => '',
            'URLBACK'        => '',
            'ORDERID'        => '',
            'SHOPID'         => '',
            'AMOUNT'         => '',
            'CURRENCY'       => '',
            'ACCOUNTINGMODE' => '',
            'AUTHORMODE'     => '',
            'MAC'            => '',
            'PAGE'           => '',
        );

        if (file_exists(__DIR__ . '/../assets/files/requestParams.json'))
        {
            //apri file
            $file = json_decode(file_get_contents(__DIR__ . '/../assets/files/requestParams.json'), true);

            foreach ($file as $key => $value)
            {
                $this->requestParams[$key] = $value;
            }
        }
        else
        {
            //crea file
            file_put_contents(__DIR__ . '/../assets/files/requestParams.json', json_encode($requestParams));

            $this->requestParams();
        }
    }





    public function calculating_mac()
    {
        $res = [];

        $calculating_mac = $_POST['calculating_mac'];


        $key_start = $calculating_mac['key_start'];

        unset($calculating_mac['key_start']);
        unset($calculating_mac['MAC']);
        unset($calculating_mac['URLBACK']);
        unset($calculating_mac['PAGE']);

        $temp = [];

        foreach ($calculating_mac as $name => $value)
        {
            $temp[] = sprintf("%s=%s", $name, $value);
        }

        $string = implode("&", $temp);

        $mac = hash_hmac('sha256', $string, $key_start);
//        $mac = base64_encode(hash_hmac('sha256', $string, $key_start, true));

        $res['temp']   = $temp;
        $res['string'] = $string;
        $res['mac']    = $mac;

        return $res;
    }





    public function save_parameters()
    {
        file_put_contents(__DIR__ . '/../assets/files/key_pagepayment.json', json_encode($_POST['save_parameters']));
    }





    public function save_request_params()
    {
        $requestParams = $_POST['save_request_params'];

        file_put_contents(__DIR__ . '/../assets/files/requestParams.json', json_encode($_POST['save_request_params']));

        return $requestParams;
    }





}
