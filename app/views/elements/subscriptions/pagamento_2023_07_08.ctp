<? include('payment_data.ctp') ?>

<?
//GIUSEPPE  20/10/2016 -> filtra la classe
$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 
$nameClass = $classPage["Name"];

$type_sport = array("primary" => "CALCIO", "secondary" => "CALCIO", "quaternary" => "TENNIS");

//echo $type_sport[$nameClass];

$protocol = "http://";
?>

<?
$id_sport = "";

switch ($nameClass)
{
    case "primary":
        $id_sport = 0;
        break;

    case "quaternary":
        $id_sport = 1;
        break;
}

$cauzione = "";

if ($_GET['totale'] == 0)
{

    $cauzione = 0;
}
else
{
    $cauzione = 1;
}
?>

<? if (!isset($_REQUEST['totale']) && (int) @$_GET['c'] == 0): ?>


    <div class="alert alert-success">
        Grazie, la tua richiesta di iscrizione al campionato è stata effettuata correttamente.<br /><br />

        Con la presente iscrizione la squadra in predicato nelle figure dei tre responsabili dichiarano;<br />
        - di conoscere e accettare lo statuto e tutti i regolamenti MIDLAND GS per la stagione sportiva <br />
        - di aver preso visione e di ben conoscere, in particolar modo, le eventuali e possibili conseguenze economiche previste dal regolamento “Strutturazione e regolamentazione delle 
        manifestazioni” <br />
        - che tutti i tesserati della squadra sono stati riconosciuti idonei a svolgere l’attività sportiva amatoriale<br /><br />

        Il Presidente e tutto il Consiglio Direttivo dichiarano di essere in possesso dei requisiti per ricoprire le cariche segnalate e di essere consci delle responsabilità personali che derivano dalla carica da loro ricoperta sia in ordine alla posizione sanitaria che alle eventuali pendenze economiche. 
        Eventuali disdette saranno accettate (con relativa restituzione del deposito cauzionale) entro 7 giorni dalla data di iscrizione, purché la manifestazione non abbia già preso inizio (cioè siano stati emessi i calendari).<br /><br />
    </div>

    <script type="text/javascript">


        $(document).ready(function ()
        {

            //GIUSEPPE 20/10/2016 
            //l'ho commentato, devo verificare a cosa serve
            //$.post('/sections/iscrizioneverify/<?= $_GET['verifyid']; ?>/1/0');
        });

    </script>

<? else: ?>
    <div class="payment">

        <section class="panel">
            <header class="panel-heading">

                <h2 class="panel-title">Procedi con il pagamento</h2>
            </header>
            <div class="panel-body">
                <form class="form-horizontal form-bordered" autocomplete="off" method="post" onsubmit="return false;">
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">Totale importo da pagare:</label>
                        <div class="col-md-6">
                            <p class="lead" style="padding-left: 0px; margin-top: 5px;">
                                <? if (isset($_REQUEST['totale'])): ?>

                                    <?= number_format($_REQUEST['totale'], 2, ",", "."); ?> &euro;<? $totale = $_REQUEST['totale']; ?>

                                <? else: ?>

                                    <? $totale = $_GET['totale']; ?>
                                    <?= number_format($totale, "2", ",", "."); ?> &euro;

                                <? endif; ?>
                            </p>
                        </div>
                    </div>
                    <? if ($_GET[c] == 1 && $_GET['totale'] == 0): ?>
                    <? else: ?>

                        <? if (isset($_GET['squadra_tennis'])): ?>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">Metodo di pagamento:</label>
                                <div class="col-md-6">
                                    <input type="radio" value="150" name="cauzione" class="cauzione" checked> Paga con carta di credito<br />
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault"></label>
                                <div class="col-md-6">
                                    <input type="radio" id="iban" value="150" name="cauzione" class="cauzione" > 
                                    Bonifico bancario  <br />
                                    Intestatario: <strong><?= $iban_intestatario ?></strong> <br />
                                    IBAN: <strong><?= $iban ?></strong>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault"></label>
                                <div class="col-md-6">
                                    <input type="radio" id="sede" name="cauzione" class="cauzione" > Presso la sede<br />
                                </div>
                            </div>

                        <? else: ?>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">Metodo di pagamento:</label>
                                <div class="col-md-6">
                                    <input type="radio" value="150" name="cauzione" class="cauzione" checked> Paga con carta di credito<br />
                                </div>
                            </div>
                        <? endif; ?>

                    <? endif; ?>
                    <? if (!isset($_GET['d'])): ?>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">&nbsp;</label>
                            <div class="col-md-">


                                <input type="radio" value="0" name="cauzione" class="cauzione"> Pagamento con Bonifico Bancario
                            </div>
                        </div>
                    <? endif; ?>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault"><strong>Email di notifica conferma tesseramento/iscrizione:<sup>*</sup></strong></label>
                        <div class="col-md-4">

                                                                <!-- <input type="text" name="email_paying" id="email_paying" class="email form-control" onkeyup="myFunction(this.value)"> -->
                            <? //= $this->Form->input('Email', array('label' => false, 'class' => 'email form-control', 'required' => 'required'));   ?>
                            <?= $this->Form->input('Email', array('label' => false, 'class' => 'form-control email', 'required' => 'required')); ?>
                        </div>
                    </div>




                </form>
            </div>

            <div class="panel-footer">
                <ul class="pager">

                    <li class="next" id="validate">
                        <a href="#" id="nextstep" class="btn btn-success">Concludi l'ordine <i class="fa fa-angle-right"></i></a>
                    </li>
                    <li class="previous" id="validate">
                        <a href="#" id="prevstep" class="btn btn-default"><i class="fa fa-angle-left"></i> Torna indietro </a>
                    </li>

                </ul>
            </div>

        </section>



    </div>

    <div class="bonifico" style="display: none;">

        <div class="alert alert-success">

            Grazie, la tua richiesta di iscrizione è stata effettuata correttamente.<br /><br />
            Di seguito le coordinate bancarie per effettuare il pagamento di <b><?= $totale; ?> &euro;</b>:<br /><br />

            Bonifico bancario  <br />
            Intestatario: <strong><?= $iban_intestatario ?></strong> <br />
            IBAN: <strong><?= $iban ?></strong>

        </div>

    </div>

    <?php
    $redirect = '';
    $error    = false;

    $test_mode      = 0;
    $test_mode_auth = 0;




    /* $trackid = $_GET['verifyid'];

      $amt = $totale;

      $action = '4'; */


    $udf1 = 'TESSERAMENTI ' . date("d/m/Y");


    if (isset($_GET['c']) && $_GET['c'] == 1)
    {

        $udf1 = 'ISCRIZIONE CALCIO ' . date("d/m/Y");
    }

    if (isset($_GET['squadra_tennis'])) //GIUSEPPE 20/10/2016 filtro squadra1 e squadra2 tennis
    {

        $squadra_to_read_pay_page = "";

        switch ($_GET['squadra_tennis'])
        {
            case 1:
                $squadra_to_read_pay_page = "SQUADRA 1";
                break;

            case 2:
                $squadra_to_read_pay_page = "SQUADRA 2";
                break;

            case 3:
                $squadra_to_read_pay_page = "SQUADRE 1 & 2";
                break;
        }

        $udf1 = 'ISCRIZIONE TENNIS ' . $squadra_to_read_pay_page . " " . date("d/m/Y");
    }

    $udf2          = '';
    $lang_iso_code = 'it';
    if ($lang_iso_code == 'it')
        $langid        = 'ITA';
    elseif ($lang_iso_code == 'es')
        $langid        = 'SPA';
    elseif ($lang_iso_code == 'fr')
        $langid        = 'FRA';
    elseif ($lang_iso_code == 'de')
        $langid        = 'DEU';
    else
        $langid        = 'USA';


    $responseurl = $protocol . $_SERVER['SERVER_NAME'] . '/sections/tesseramentoverify/' . $_GET['verifyid'] . '/' . $type_sport[$nameClass]; //tesseramento atleti 13/11/2016


    $recoveryurl = $protocol . $_SERVER['SERVER_NAME'] . '/sections/tesseratimodify/' . $_GET['verifyid'];




    if (isset($_GET['c']) && $_GET['c'] == 1)
    { //iscrizioni squadre
        if ($type_sport[$nameClass] == "CALCIO")
        {
            $responseurl = $protocol . $_SERVER['SERVER_NAME'] . '/sections/iscrizioneverify/' . $_GET['verifyid'] . '/' . $id_sport . '/' . $cauzione;
        }

        if ($type_sport[$nameClass] == "TENNIS")
        {
            $responseurl = $protocol . $_SERVER['SERVER_NAME'] . '/sections/iscrizioneverify/' . $_GET['verifyid'] . '/' . $id_sport . '/' . $cauzione . '/' . $_GET['squadra_tennis'];
        }

        //$recoveryurl = $protocol . $_SERVER['SERVER_NAME'] . '/sections/iscrizioneconfirm/' . $_GET['verifyid'];

        $recoveryurl = $protocol . $_SERVER['SERVER_NAME'] . '/sections/iscrizionemodify/' . $_GET['verifyid'];
    }


    // GIUSEPPE 2018-07-16 --------------------------------------------------------------------------------------------

    /*  $divisa  = "EUR";
      $importo = $_GET['totale'] * 100;

      // Calcolo MAC
      $mac = sha1('codTrans=' . $codTrans . 'divisa=' . $divisa . 'importo=' . $importo . $CHIAVESEGRETA);

      // Parametri obbligatori
      $obbligatori = array(
      'alias'    => $ALIAS,
      'importo'  => $importo,
      'divisa'   => $divisa,
      'codTrans' => $codTrans,
      'url'      => $responseurl,
      'url_back' => $recoveryurl,
      'mac'      => $mac,
      );




      // Parametri facoltativi
      $facoltativi = array(
      );

      $requestParams = array_merge($obbligatori, $facoltativi);

      $aRequestParams = array();
      foreach ($requestParams as $param => $value)
      {
      $aRequestParams[] = $param . "=" . $value;
      }

      $stringRequestParams = implode("&", $aRequestParams);

      $redirectUrl = $requestUrl . "?" . $stringRequestParams;

      $redirect = $redirectUrl; */

    // ------------------------------------
    // c = 0 -> teseramenti
    // c = 1 -> iscrizioni
    //15/10/2016 


    /*     * * * * * * * * * * * * * * * */

    $calculating_mac = array(
        'URLMS'          => $urlms,
        'URLDONE'        => $responseurl,
        'ORDERID'        => $orderid,
        'SHOPID'         => $shopid,
        'AMOUNT'         => $amount,
        'CURRENCY'       => $currency,
        'ACCOUNTINGMODE' => $accountingmode,
        'AUTHORMODE'     => $authormode,
    );


    $temp = [];

    foreach ($calculating_mac as $name => $value)
    {
        $temp[] = sprintf("%s=%s", $name, $value);
    }

    $string = implode("&", $temp);

    $mac = hash_hmac('sha256', $string, $key_start);

    /*     * * * * * * * * * * * * * * * */

    if ($_GET['c'] == 1 && $_GET['totale'] == 0) // qui l'iscrizione verrà pagata in seguito -> l'1 indica cauzione NON pagata' 
    {
        $redirect = $protocol . $_SERVER['SERVER_NAME'] . '/sections/iscrizioneverify/' . $_GET['verifyid'] . '/' . $id_sport . '/' . $cauzione;
    }

    // --------------------------
    ?>


    <form action="<?=$page_payment?>" method="POST" name="myform"> 

        <input type="hidden" name="PAGE" value="LAND"> 
        <input type="hidden" name="AMOUNT"  value="<?= $amount ?>"> 
        <input type="hidden" name="CURRENCY"  value="<?= $currency ?>"> 
        <input type="hidden" name="SHOPID"  value="<?= $shopid ?>"> 
        <input type="hidden" name="ORDERID"  value="<?=$orderid?>"> 
        <input type="hidden" name="URLDONE"   value="<?=$responseurl?>"> 
        <input type="hidden" name="URLBACK"  value="<?=$recoveryurl?>"> 
        <input type="hidden" name="URLMS"  value="<?=$urlms?>"> 
        <input type="hidden" name="ACCOUNTINGMODE" value="D"> 
        <input type="hidden" name="AUTHORMODE" value="I"> 

        <input type="hidden" name="MAC" value="<?=$mac?>"> 

        <!--<input type=submit value="Go...">--> 
    </form>

    <script type="text/javascript">

        $(document).ready(function ()
        {

            //GIUSEPPE 01/09/2016
            $("#nextstep").click(function ()
            {
                console.log("QUI");
                if (validateMail)
                {
                    if ($(".cauzione:first").is(':checked'))
                    {

                        var uniqid = '<?= $_GET['verifyid'] ?>';

                        $.get("/sections/save_email_payor/" + emailWindow + "/" + uniqid, function (data)
                        {
                            //location.href = '<?= $redirect; ?>';

                            document.myform.submit();

                            console.log('normale' + '<?= $redirect; ?>');

                        });

                    }
                    else
                    {

                        var uniqid = '<?= $_GET['verifyid'] ?>';

                        $.get("/sections/save_email_payor/" + emailWindow + "/" + uniqid, function (data)
                        {
                            var id_sport = <?= $id_sport ?>;

                            var redirect;

                            var cauzione;

                            switch (id_sport)
                            {
                                case 0:
                                    redirect = '<?= $protocol . $_SERVER['SERVER_NAME'] . '/sections/iscrizioneverify/' . $_GET['verifyid'] . '/' . $id_sport . '/' . $cauzione; ?>';
                                    break;


                                case 1:
                                    if ($("#iban").is(":checked"))
                                    {
                                        console.log("iban");

                                        cauzione = 2;
                                    }
                                    if ($("#sede").is(":checked"))
                                    {
                                        console.log("sede");

                                        cauzione = 3;
                                    }

                                    var squadra_tennis = '<?= $_GET['squadra_tennis'] ?>';

                                    redirect = '<?= $protocol . $_SERVER['SERVER_NAME'] . '/sections/iscrizioneverify/' . $_GET['verifyid'] . '/' . $id_sport . '/'; ?>' + cauzione + '/' + squadra_tennis;
                                    break;

                            }

                            location.href = redirect;

                            console.log("other " + redirect);
                        });
                    }
                }
                else
                {
                    alert("ATTENZIONE\n\nInserire un indrizzo email valido per ricevere l'email di conferma tesseramento/iscrzione.");
                }

            });
            //

            //GIUSEPPE // attivato dal tast "TORNA INDIETRO"
            $("#prevstep").click(function ()
            {
                var c = <?= $_GET['c'] ?>


                //15/102016 sistemato il ritorno indietro;
                switch (c)
                {
                    case 0:
                        location.href = '/subscriptions/tesseramenti?step=4&c=0&d=1&sport=<?= $type_sport[$nameClass] ?>'; // la k indica la presenza di cookie
                        break

                    case 1:
                        location.href = '/subscriptions/tesseramenti?step=3&verifyid=<?= $_GET['verifyid'] ?>';
                        break;
                }

                //location.href='/subscriptions/tesseramenti?step=4&c=0&d=1&k=1'; // la k indica la presenza di cookie
            });
            //

        });

        //GIUSEPPE 01/09/2016 -------------------------------------------------------------

        var validateMail = false;
        var emailWindow = "";

        $('.email').live('keyup', function ()
        {
            validateMail = false;
            emailWindow = $(this).val();
            console.log(emailWindow);
            emailCheck(emailWindow);

        })


        function emailCheck(emailStr)
        {
            var emailPat = /^(.+)@(.+)$/;
            var specialChars = "\\(\\)<>@,;:\\\\\\\"\\.\\[\\]";
            var validChars = "[^\\s" + specialChars + "]";
            var quotedUser = "(\"[^\"]*\")";
            var ipDomainPat = /^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/;
            var atom = validChars + "+";
            var word = "(" + atom + "|" + quotedUser + ")";
            var userPat = new RegExp("^" + word + "(\\." + word + ")*$");
            var domainPat = new RegExp("^" + atom + "(\\." + atom + ")*$");
            var matchArray = emailStr.match(emailPat);
            if (matchArray == null)
            {
                console.log("L'email sembra essere sbagliata: (controlla @ e .)");
                return false;
            }
            var user = matchArray[1];
            var domain = matchArray[2];
            if (user.match(userPat) == null)
            {
                console.log("La parte dell'email prima di '@' non sembra essere valida!");
                return false;
            }
            var IPArray = domain.match(ipDomainPat);
            if (IPArray != null)
            {
                for (var i = 1; i <= 4; i++)
                {
                    if (IPArray[i] > 255)
                    {
                        console.log("L'IP di destinazione non è valido!");
                        return false;
                    }
                }
                return true;
            }
            var domainArray = domain.match(domainPat);
            if (domainArray == null)
            {
                console.log("La parte dell'email dopo '@' non sembra essere valida!");
                return false;
            }
            var atomPat = new RegExp(atom, "g");
            var domArr = domain.match(atomPat);
            var len = domArr.length;
            if (domArr[domArr.length - 1].length < 2 ||
                    domArr[domArr.length - 1].length > 6)
            {
                console.log("Il dominio di primo livello (es: .com e .it) non sembra essere valido!");
                return false;
            }
            if (len < 2)
            {
                var errStr = "L'indirizzo manca del dominio!";
                console.log(errStr);
                return false;
            }
            console.log("email OK")
            validateMail = true;
            return validateMail;
        }
        // ----------------------------------------------------------
    </script>
<? endif; ?>
