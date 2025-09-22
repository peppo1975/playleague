<?php

session_start();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Transaction
{





    function __construct()
    {
        $this->timestampUTC();
        print "<br>";
       
    }





    private function timestampUTC()
    {
//        $time  = time();
//        $check = $time + date("Z", $time);
//        echo strftime("%B %d, %Y @ %H:%M:%S UTC", $check);
        echo date("Y-m-d\TH:i:s");
    }





    public function generatingApi($array)
    {
//        $array          = [];
//        $array[]        = $URLMS          = "URLMS=http://demo.ssb.net/index.html?EMAILCLI=tryme@demo.net&CART=02";
//        $array[]        = $URLDONE        = "URLDONE=http://demo.demo.net/mimesys/urlok.html?oper=900";
//        $array[]        = $ORDERID        = "ORDERID=7893133444445";
//        $array[]        = $SHOPID         = "SHOPID=880022537000001";
//        $array[]        = $AMOUNT         = "AMOUNT=5000";
//        $array[]        = $CURRENCY       = "CURRENCY=978";
//        $array[]        = $ACCOUNTINGMODE = "ACCOUNTINGMODE=D";
//        $array[]        = $AUTHORMODE     = "AUTHORMODE=I";
//        $array[]        =  "vuQSHDwf-ETrvvwxam-9s9Ub2XYsd2ZZc-m-WVUuE94-9b4LLH6-e3cRcWRFKebXSPq--Q-5dA-Dtw-KQ6RLKbKsanmzrnYkxCH-";

        $temp = [];
        
        $key_secret = $array['key_secret'];
        
//        unset($array['key_secret']);

        foreach ($array as $key => $value)
        {
            if ($key !== "key_secret")
            {
                $temp[] = sprintf("%s=%s", $key, $value);
            }
            else
            {
                $temp[] = $value;
            }
        }

        $_SESSION['temp'] = $temp;

        $hash = implode("&", $temp);

        $_SESSION['hash'] = $hash;

        $mac = sha1($hash);
        
//        $mac = hash_hmac("sha256",$hash,$key_secret);

        $_SESSION['mac'] = $mac;

        return trim($mac);

        /*


          MAC=Hash(URLMS=<urlms>&URLDONE=<urldone>&ORDERID=<orderid>&SHOPID=<shopid>
          &AMOUNT=<Amount>&CURRENCY=<Currency>&ACCOUNTINGMODE=<accountingmode>&AUTHORMODE=<authormode>&<startsecretstring>)

         */
    }





}
