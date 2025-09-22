<?
//GIUSEPPE 2017/01/04 (QUESTA PAGINA E' FATTA EX NOVO)

$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 

$nameClass = $classPage["Name"];

$cauzione = $this->requestAction('sections/readDeposit/' . $nameClass); // quota deposita letta da database e filtrata in base alla classe (primary, secondary, quaternary)

$anno_sportivo = $this->requestAction('sections/readAnnoSportivo'); // questo valore lo troviamo nel controller 

$anno_precedente = $anno_sportivo - 1;


//Correggo le maiuscole e le minuscole

foreach ($tesserati['Subscription'] as $i => $transform)
{


    //$tesserati['Subscription'][$i] = 

    if (strlen(strpos($i, "Email")) > 0)
    {
        $tesserati['Subscription'][$i] = strtolower($transform);
    }
    else if (strlen(strpos($i, "CodiceFiscale")) > 0)
    {
        $tesserati['Subscription'][$i] = strtoupper($transform);
    }
    else if (strlen(strpos($i, "Provincia")) > 0)
    {
        $tesserati['Subscription'][$i] = strtoupper($transform);
    }
    else
    {
        $tesserati['Subscription'][$i] = ucwords(strtolower($transform));
    }/**/
}
?>

<link rel="stylesheet" type="text/css" href="/porto_admin/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />

<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

<script type="text/javascript" src="/js/layout.js"></script>

<script type="text/javascript" src="/js/jQuery-Mask-Plugin/dist/jquery.mask.min.js"></script>

<style type="text/css">

    .contents-text p, .contents-text h3 { padding-left: 20px; }
    .contents-text { padding-top: 20px; }

    #progress * {
        box-sizing: border-box;
    }

    #progress {
        padding: 0;
        list-style-type: none;
        font-family: arial;
        font-size: 12px;
        clear: both;
        line-height: 1em;
        margin: 0 -1px;
        text-align: center;
    }

    #progress li {
        float: left;
        padding: 10px 30px 10px 40px;
        background: #eeeeee;
        color: #444;
        position: relative;
        border-top: 1px solid #eeeeee;
        border-bottom: 1px solid #eeeeee;
        width: 19%;
        margin: 0 1px;
    }

    #progress li:first-child:before {
        content: none !important;
    }
    #progress li:before {
        content: '';
        border-left: 16px solid #fff;
        border-top: 16px solid transparent;
        border-bottom: 16px solid transparent;
        position: absolute;
        top: 0;
        left: 0;

    }
    #progress li:after {
        content: '';
        border-left: 16px solid #eeeeee;
        border-top: 16px solid transparent;
        border-bottom: 16px solid transparent;
        position: absolute;
        top: 0;
        left: 100%;
        z-index: 20;
    }

    #progress li.active {
        background: #fd8a15;
        color: #fff;
    }

    #progress li.active:after {
        border-left-color: #fd8a15;
    }


</style>

<div role="main" class="main">

    <div style="background: #f5f5f5; margin-bottom: 20px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumb" style="margin-bottom: 0">
                        <li><a href="/">Home</a></li>
                        <? if (@$_GET['step'] == 4): ?>
                            <li>Tesseramenti e iscrizioni online <?= $anno_precedente . "/" . $anno_sportivo ?></li>
                        <? else: ?>
                            <li class="">Iscrizione online</li>
                        <? endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Admin Extension Specific Page Vendor CSS -->

    <link rel="stylesheet" href="/vendor/theme.admin.extension.css">
    <link rel="stylesheet" href="/vendor/theme.extension.css">
    <div class="container" id="main-custom">

        <div class="row">
            <div class="col-md-12">


                <div class="post-content">
                    <div class="row">
                        <div class="col-md-12">

                            <h2 class="text-center">
                                Tesseramenti e iscrizioni online  <?= $anno_precedente . "/" . $anno_sportivo ?>
                            </h2>
                        </div>
                    </div>

                    <?
                    $steps = "";

                    if ($nameClass == "primary" || "secondary")
                    {
                        $steps = array(
                            '1' => 'Iscrizione',
                            '2' => 'Cauzione',
                            '4' => 'Pagamento',
                            '5' => 'Conferma Dati'
                        );
                    }
                    if ($nameClass == "quaternary")
                    {
                        $steps = array(
                            '1' => 'Iscrizione',
                            '2' => 'Quota iscrizione',
                            '4' => 'Pagamento',
                            '5' => 'Conferma Dati'
                        );
                    }
                    ?>

                    <? $cur_step = 5; ?>

                    <?
                    //print_r($tesserati);
                    ?>

                    <hr />

                </div>



                <div class="row">
                    <div class="wizard-progress wizard-progress-lg">
                        <div class="steps-progress">
                            <div class="progress-indicator" style="width: 0%;"></div>
                        </div>
                        <ul class="wizard-steps">

                            <? $i = 1; ?>
                            <? foreach ($steps as $key => $step): ?>
                                <li <? if ($key == 5): ?>class="active"<? endif; ?>><a href="#" style="cursor: default !important;"><span style="cursor: default !important;"><?= $i; ?></span><?= $step; ?></a></li>
                                <? $i++; ?>
                            <? endforeach; ?>


                        </ul>
                    </div>

                </div>

                <div class="container">

                    <header>

                        <h2 >Verifica i dati di iscrizione</h2>

                    </header>


                    <div class="col-md-9">

                        <div class="athlete-box">

                            <div class="anagrafica-box">

                                <!--  ------------------------------------------------------------------------------------------------ -->
                                <? //print_r(($tesserati['Subscription'])) ?>

                                <? $tesserato = $tesserati['Subscription'] ?>

                                <? //foreach($tesserati['atleti'] as $i => $tesserato): ?>

                                <? for ($i = 0; $i < 3; $i++): ?>

                                    <? if ($tesserato['Cognome_' . $i] != ""): ?>

                                        <section class="panel" >

                                            <? //echo $i;?>
                                            <? //print_r($tesserato);  ?>

                                            <header class="panel-heading">

                                                <div class="panel-actions">

                                                    <div id="nome_barra" class="left-element" style="float:left;"><div class="btn btn-default"  onclick="reset_text_box('<?= $i ?>')">Reload</div></div>

                                                    <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a> 

                                                </div>

                                                <div ><h2 class="panel-title" id="cognome_nome_<?= $i ?>"><strong><?= ucwords(strtolower($tesserato['Cognome_' . $i])) . " " . ucwords(strtolower($tesserato['Nome_' . $i])) ?></strong></h2></div>

                                            </header>

                                            <div class="panel-body ats">

                                                <form class="form-horizontal form-bordered" autocomplete="off" method="post" onsubmit="return false;">

                                                    <input type="hidden" name="<?= $i ?>" id="Atleta_<?= $i ?>" value="<?= $tesserato['id_responsabile_' . $i] ?>"> <!--//GIUSEPPE -->

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label" for="inputDefault"><strong>Cognome:<sup>*</sup></strong></label>

                                                        <div class="col-md-6">

                                                            <input id="Cognome_<?= $i ?>" name="<?= $i ?>" class="form-control" value="<?= ucwords(strtolower($tesserato['Cognome_' . $i])) ?>" readonly="readonly" >

                                                            <?
                                                            //=
                                                            //$this->Form->input('Cognome', 
                                                            //array('label' => false, 
                                                            //'class' => 'form-control' , 
                                                            //'required' => 'required'));
                                                            ?>

                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="inputDefault"><strong>Nome:<sup>*</sup></strong></label>
                                                        <div class="col-md-6">

                                                            <input id="Nome_<?= $i ?>" name="<?= $i ?>" class="form-control" value="<?= ucwords(strtolower($tesserato['Nome_' . $i])) ?>" readonly="readonly" >

                                                            <?
                                                            //= $this->Form->input('Nome', 
                                                            //	array('label' => false, 
                                                            //'class' => 'form-control' , 
                                                            //'required' => 'required')); 
                                                            ?>

                                                        </div>

                                                    </div>



                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="inputDefault"><strong>E-mail:<sup>*</sup></strong></label>
                                                        <div class="col-md-6">
                                                            <input id="Email_<?= $i ?>" name="<?= $i ?>" class="form-control email" value="<?= strtolower($tesserato['Email_' . $i]) ?>">

                                                            <?
                                                            //= $this->Form->input('Email', 
                                                            //array('label' => false, 
                                                            //	'class' => 'form-control email', 
                                                            //'required' => 'required'));
                                                            ?>

                                                        </div>
                                                        <div id="mess_Email_<?= $i ?>" name="<?= $i ?>" style="padding-top: 5px; text-align: center; font-style: italic; color:#000;"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="inputDefault"><strong>Data di nascita:<sup>*</sup></strong></label>
                                                        <div class="col-md-6">
                                                            <input id="DataNascita_it_<?= $i ?>" name="<?= $i ?>" class="form-control datanasc" value="<?= $tesserato['DataNascita_it_' . $i] ?>"  readonly="readonly"  >

                                                            <?
                                                            //= $this->Form->input('DataNascita', 
                                                            //array('label' => false, 
                                                            //'class' => 'datanasc form-control', 
                                                            //'required' => 'required')); 
                                                            ?>

                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="inputDefault"><strong>Luogo di nascita:<sup>*</sup></strong></label>
                                                        <div class="col-md-6">
                                                            <input id="LuogoNascita_<?= $i ?>" name="<?= $i ?>" class="form-control" value="<?= ucwords(strtolower($tesserato['LuogoNascita_' . $i])) ?>">

                                                            <?
                                                            //= $this->Form->input('LuogoNascita', 
                                                            //array('label' => false, 
                                                            //	'class' => 'form-control', 
                                                            //'required' => 'required'));
                                                            ?>

                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="inputDefault"><strong>Codice Fiscale:<sup>*</sup></strong></label>
                                                        <div class="col-md-6">
                                                            <input id="CodiceFiscale_<?= $i ?>" name="<?= $i ?>" class="form-control" value="<?= strtoupper($tesserato['CodiceFiscale_' . $i]) ?>"  maxlength="16" style="text-transform:uppercase">

                                                            <?
                                                            //= $this->Form->input('CodiceFiscale', 
                                                            //	array('label' => false, 
                                                            //'class' => 'form-control', 
                                                            //'required' => 'required')); 
                                                            ?>

                                                        </div>
                                                        <div id="mess_CodiceFiscale_<?= $i ?>" name="<?= $i ?>" style="padding-top: 5px; text-align: center; font-style: italic; color:#000;"></div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="inputDefault"><strong>Indirizzo:<sup>*</sup></strong></label>
                                                        <div class="col-md-6">
                                                            <input id="Indirizzo_<?= $i ?>" name="<?= $i ?>" class="form-control" value="<?= ucwords(strtolower($tesserato['Indirizzo_' . $i])) ?>">


                                                            <?
                                                            //= $this->Form->input('Indirizzo', 
                                                            //	array('label' => false, 
                                                            //	'class' => 'form-control', 
                                                            //'required' => 'required')); 
                                                            ?>

                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="inputDefault"><strong>CAP:<sup>*</sup></strong></label>
                                                        <div class="col-md-6">

                                                            <input id="Cap_<?= $i ?>" name="<?= $i ?>" class="form-control" value="<?= $tesserato['Cap_' . $i] ?>" maxlength="5" style="text-transform:uppercase">


                                                            <?
                                                            //= $this->Form->input('Cap', 
                                                            //	array('label' => false, 
                                                            //'class' => 'form-control', 
                                                            //'required' => 'required'));
                                                            ?>

                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="inputDefault"><strong>Localit&agrave;:<sup>*</sup></strong></label>
                                                        <div class="col-md-6">
                                                            <input id="Localita_<?= $i ?>" name="<?= $i ?>" class="form-control" value="<?= ucwords(strtolower($tesserato['Localita_' . $i])) ?>">

                                                            <?
                                                            //= $this->Form->input('Localita', 
                                                            //	array('label' => false, 
                                                            //'class' => 'form-control'));
                                                            ?>

                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="inputDefault"><strong>Provincia:<sup>*</sup></strong></label>
                                                        <div class="col-md-6">

                                                            <input id="Provincia_<?= $i ?>" name="<?= $i ?>" class="form-control" value="<?= strtoupper($tesserato['Provincia_' . $i]) ?> " maxlength="2" style="text-transform:uppercase">



                                                            <?
                                                            //= $this->Form->input('Provincia', 
                                                            //array('label' => false, 
                                                            //'class' => 'form-control'));
                                                            ?>

                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="inputDefault"><strong>Telefono cellulare:<sup>*</sup></strong></label>
                                                        <div class="col-md-6">
                                                            <input id="Cellulare_<?= $i ?>" name="<?= $i ?>" class="form-control" value="<?= $tesserato['Cellulare_' . $i] ?>">


                                                            <?
                                                            //= $this->Form->input('Cellulare', 
                                                            //	array('label' => false, 
                                                            //'class' => 'form-control'));
                                                            ?>

                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="inputDefault"><strong>Sesso:<sup>*</sup></strong></label>
                                                        <div class="col-md-6">

                                                            <select id="SubscriptionSesso<?= $i ?>" name="<?= $i ?>" class="form-control"  readonly="readonly" >
                                                                <option value="Maschio" <? if ($tesserato['SubscriptionSesso' . $i] == "maschio"): ?>selected="selected"<? endif; ?> >Maschio</option>
                                                                <option value="Femmina" <? if ($tesserato['SubscriptionSesso' . $i] == "femmina"): ?>selected="selected"<? endif; ?> >Femmina</option>
                                                            </select>

                                                            <?
                                                            //=
                                                            //	$this->Form->input('Sesso', array(
                                                            //	'label'		 => false,
                                                            //	'class'		 => 'form-control',
                                                            //	'type'		 => 'select',
                                                            //	'options'	 => array('Maschio' => 'Maschio', 'Femmina' => 'Femmina'),
                                                            //));
                                                            ?>
                                                        </div>
                                                    </div>



                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="inputDefault"><strong>Tipo Documento:<sup>*</sup></strong></label>
                                                        <div class="col-md-6">

                                                            <select id="SubscriptionTipoDocumento<?= $i ?>" name="<?= $i ?>" class="form-control"> 
                                                                <option value="<?= utf8_encode("Carta Identità") ?>" <? if (strstr($tesserato['SubscriptionTipoDocumento' . $i], "carta")): ?>selected="selected"<? endif; ?> ><?= utf8_encode("Carta Identità") ?></option>
                                                                <option value="Patente"  <? if (strstr($tesserato['SubscriptionTipoDocumento' . $i], "patente")): ?>selected="selected"<? endif; ?>  >Patente</option>
                                                                <option value="Passaporto"  <? if (strstr($tesserato['SubscriptionTipoDocumento' . $i], "passaporto")): ?>selected="selected"<? endif; ?>  >Passaporto</option>
                                                            </select>



                                                            <?
                                                            //=
                                                            //	$this->Form->input('TipoDocumento', array(
                                                            //	'label'		 => false,
                                                            //	'class'		 => 'form-control',
                                                            //	'options'	 => array(
                                                            //	'Carta Identit�' => 'Carta Identit�',
                                                            //	'Patente'	 => 'Patente',
                                                            //	'Passaporto'	 => 'Passaporto'
                                                            //	)
                                                            //	));
                                                            ?>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="inputDefault"><strong>Numero Documento:<sup>*</sup></strong></label>
                                                        <div class="col-md-6">

                                                            <input id="NumeroDocumento_<?= $i ?>" name="<?= $i ?>" class="form-control" value="<?= $tesserato['NumeroDocumento_' . $i] ?>">

                                                            <?
                                                            //= $this->Form->input('NumeroDocumento', 
                                                            //	array('label' => false, 
                                                            //'class' => 'form-control')); 
                                                            ?>
                                                        </div>
                                                    </div>	
                                                    <?
                                                    $scadenza_documento_database = explode("-", $tesserato['ScadenzaDocumento_' . $i]);
                                                    $scadenza_documento = $scadenza_documento_database[2] . $scadenza_documento_database[1] . $scadenza_documento_database[0];
                                                    ?>

                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="inputDefault"><strong>Scadenza Documento:<sup>*</sup></strong></label>
                                                        <div class="col-md-6">

                                                            <input id="ScadenzaDocumento_<?= $i ?>" name="<?= $i ?>" class="form-control scaddoc" value="<?= $scadenza_documento ?>">

                                                            <?
                                                            //= $this->Form->input('NumeroDocumento', 
                                                            //	array('label' => false, 
                                                            //'class' => 'form-control')); 
                                                            ?>
                                                        </div>
                                                    </div>	
                                                </form>

                                            </div>

                                        </section>

                                        <? //endforeach;  ?>
                                    <? endif; ?>
                                <? endfor; ?>

                                <!--  ------------------------------------------------------------------------------------------------ -->

                            </div>

                        </div>

                        <div class="panel-footer">
                            <ul class="pager">
                                <li class="next" id="validate">
                                    <a id="nextstep" class="btn btn-success">Conferma <i class="fa fa-angle-right"></i></a>
                                </li>
                            </ul>
                        </div>


                    </div>

                </div>

            </div>
        </div>
    </div>			

    <script type="text/javascript">

        var dati_responsabili;

        var dati_responsabili_upload;

        $(document).ready(function ()
        {
            $('.datanasc').mask('00/00/0000');

            $('.scaddoc').mask('00/00/0000');

            dati_responsabili = <?= json_encode($tesserati) ?>;		// carico di dati degli atleti (quelli originari non ancora modificati). Mi servir� per il reload

            dati_responsabili_upload = <?= json_encode($tesserati) ?>;	// carico di dati degli atleti originari. Poi le modifiche verranno fatte qui dentro e quindi l'upload;

            alert('Controlla e conferma i dati dei responsabili.\n\nA seguito della conferma ogni responsabile che hai iscritto riceverà una email di riepilogo nella quale saranno riportati i suoi dati personali e i nominativi degli altri responsabili.\n\nN.B: Se non effettui la procedura di conferma nessuna email sarà inviata.');

        });



        $('.email').live('keyup', function (event, data)
        {

            var me = $(this);

            var form = me.closest('form');

            var index = me.attr('name')

            validateMail = false;

            var emailWindow = $('#Email_' + index).val();

            var id = $("#Atleta_" + index).val();

            //var emailWindow = $(this).find('#Email')["context"]["value"];//$(this).val

            console.log(emailWindow);

            validateMail = emailCheck(emailWindow);

            if (validateMail)
            {
                $.get("/sections/searchmailconfirm/" + emailWindow + "/" + id, function (data, status)
                {
                    if (data > 0)
                    {
                        //Testo OK
                        alert("ATTENZIONE\n\nL'indirizzo email \n\n" +
                                emailWindow + " <?= utf8_encode('\n\nrisulta già registrato ad un altro utente.\nTi preghiamo di inserirne un altro.'); ?>");
                        $('#Email_' + index).val('');
                    }
                    data = 0;
                });
                validateMail = true;
            }
            ;
        });


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

            return true;
        }

        $(".panel-action-toggle").live('click', function ()
        {
            $(this).closest('.panel').toggleClass('panel-collapsed');
        });


        // UPLOAD JSON ........................................................

        $(".form-control").live('keyup', function () // se digito nelle text box
        {
            var index = $(this).attr('name');

            var id_textbox = $(this).attr('id');

            var Cognome = $("#Cognome_" + index).val();

            var Nome = $("#Nome_" + index).val();

            var property_json;

            $("#cognome_nome_" + index).html("<strong>" + Cognome + " " + Nome + "</strong>");

            dati_responsabili_upload['Subscription'][id_textbox] = $(this).val();

        });


        $(".form-control").click(function () // se cambio sesso o tipo documento
        {
            var id_textbox = $(this).attr('id');

            var index = $(this).attr('name');

            if (id_textbox === "SubscriptionSesso" + index || id_textbox === "SubscriptionTipoDocumento" + index)
            {
                //console.log(" -> " + id_textbox);

                dati_responsabili_upload['Subscription'][id_textbox] = $(this).val();
            }
        })
        // ........................................................................




        // RELOAD VALORI INIZIALI .................................................

        function reset_text_box(index)
        {
            //console.log(" -> " + index);

            var id_text_box;

            for (property in dati_responsabili_upload['Subscription']) // leggo le propriet� del json
            {

                if (property.search(index) != -1)
                {
                    id_text_box = property;

                    //console.log(" -> " + id_text_box);

                    if (document.getElementById(id_text_box) != null) // non � detto che tutte le propriet� siano inserite nelle text box
                    {

                        var value_for_textbox = dati_responsabili['Subscription'][property];

                        dati_responsabili_upload['Subscription'][property] = value_for_textbox;

                        $("#" + id_text_box).val(value_for_textbox);

                        //console.log(" ...-> " + value_textbox);
                    }
                }
            }

            $("#cognome_nome_" + index).html("<strong>" + dati_responsabili['Subscription']["Cognome_" + index] + " " + dati_responsabili['Subscription']["Nome_" + index] + "</strong>");

            //console.log("OK");
        }




        $("#nextstep").click(function ()
        {
            //controllare che le textbox principali siano piene

            var id_text_box;

            var cognome_nome = [];

            var exist_error = false;

            var position_first_error = 0;



            //var id_obbligatori = ["Cognome_" + index_panel, "Nome_" + index_panel, "Email", "DataNascita", "LuogoNascita", "CodiceFiscale", "NumeroDocumento"];

            for (property in dati_responsabili_upload['Subscription']) // leggo le proprietà del json
            {
                //console.log(dati_atleti_upload['atleti'][index_panel][property]);


                id_text_box = property;

                if ($("#" + id_text_box).val() == "")
                {
                    $("#" + id_text_box).closest('.form-group').addClass("has-error");

                    exist_error = true;

                    if (position_first_error == 0)
                    {
                        var offset = $("#" + id_text_box).offset();

                        position_first_error = offset.top; // in caso di dati obbligatori mancanti, ricavo la posizione del primo dato vuoto, per poi posizonarmi su

                        //console.log(offset.top)
                    }

                    //return;
                }
                else
                {
                    $("#" + id_text_box).closest('.form-group').removeClass("has-error");

                    //controllo validità mail e lunghezza CF

                    if (id_text_box.search("Email") >= 0)
                    {
                        // controllo validita' email
                        if ($("#" + id_text_box).length) // controlla se l'elemento esiste
                        {
                            //console.log("len email -> " + $("#" + id_text_box).val().length)

                            $("#mess_" + id_text_box).html('');

                            var check_email = true;

                            var caratteri_email = $("#" + id_text_box).val();

                            check_email = emailCheck(caratteri_email);

                            if (check_email == false)
                            {
                                exist_error = true;

                                var text_Email = "L'email non è valida";

                                $("#" + id_text_box).closest('.form-group').addClass("has-error");

                                $("#mess_" + id_text_box).html(text_Email);


                                if (position_first_error == 0)
                                {
                                    var offset = $("#" + id_text_box).offset();

                                    position_first_error = offset.top; // in caso di dati obbligatori mancanti, ricavo la posizione del primo dato vuoto, per poi posizonarmi su

                                }
                            }

                        }


                    }
                    else if (id_text_box.search("CodiceFiscale") >= 0)
                    {
                        // controllo lunghezza codice fiscale

                        if ($("#" + id_text_box).length) // controlla se l'elemento esiste
                        {

                            $("#mess_" + id_text_box).html('');

                            var caratteri_CF = $("#" + id_text_box).val();

                            var num_caratteri_CF = caratteri_CF.length;

                            if (num_caratteri_CF < 16)
                            {
                                var car_mancanti = 16 - num_caratteri_CF;

                                var text_CF = "mancano " + car_mancanti + " caratteri";

                                if (car_mancanti == 1)
                                {
                                    text_CF = "manca " + car_mancanti + " carattere";
                                }



                                exist_error = true;

                                $("#" + id_text_box).closest('.form-group').addClass("has-error");

                                $("#mess_" + id_text_box).html(text_CF);


                                if (position_first_error == 0)
                                {
                                    var offset = $("#" + id_text_box).offset();

                                    position_first_error = offset.top; // in caso di dati obbligatori mancanti, ricavo la posizione del primo dato vuoto, per poi posizonarmi su

                                }
                            }

                        }

                    }

                }

                //}

            }

            if (exist_error) // inserisco il nome e il cognome degli atleti con dati obbligatori non completi
            {
                //cognome_nome.push(dati_atleti_upload['Subscription'][index_panel].Cognome + " " + dati_atleti_upload['atleti'][index_panel].Nome);

                exist_error = false;
            }



            if (position_first_error > 0)
            {


                alert("MANCANO DEI DATI OBBLIGATORI");

                $('body,html').animate({scrollTop: position_first_error - 200}, 0); // mi posizione sul primo errore
            }
            else
            {
                //console.log("AVANTI");

                $("#ajax-loader").fadeIn(200);

                $.post("/sections/updateIscrizioniLandPage", {responsabili_upload: JSON.stringify(dati_responsabili_upload)
                    , uniqid: '<?= $uniqid ?>'}, function (result)
                {

                    console.log(result);

                    location.href = "/sections/iscrizioneconfirm/<?= $uniqid ?>/1";

                });
            }

        });



    </script>																																																																																							