<?
//GIUSEPPE 2017/01/04 (QUESTA PAGINA E' FATTA EX NOVO)

$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 

$nameClass = $classPage["Name"];

$cauzione = $this->requestAction('sections/readDeposit/' . $nameClass); // quota deposita letta da database e filtrata in base alla classe (primary, secondary, quaternary)

$anno_sportivo = $this->requestAction('sections/readAnnoSportivo'); // questo valore lo troviamo nel controller 

$anno_precedente = $anno_sportivo - 1;

//Correggo le maiuscole e le minuscole

foreach ($tesserati['atleti'] as $i => $tesserato)
{


    foreach ($tesserato as $id => $value_id)
    {
        //echo $id." -> ".$value_id."<br>";
        //$tesserati['Subscription'][$i] = 

        if (strlen(strpos($id, "Email")) > 0)
        {
            $tesserati['atleti'][$i][$id] = strtolower($value_id);
        }
        else if (strlen(strpos($id, "CodiceFiscale")) > 0)
        {
            $tesserati['atleti'][$i][$id] = strtoupper($value_id);
        }
        else if (strlen(strpos($id, "Provincia")) > 0)
        {
            $tesserati['atleti'][$i][$id] = strtoupper($value_id);
        }
        else
        {
            $tesserati['atleti'][$i][$id] = ucwords(strtolower($value_id));
        }/**/
    }
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
                            <li>Tesseramenti e iscrizioni online  <?= $anno_precedente . "/" . $anno_sportivo ?></li>
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
                    $steps = array(
                        '1' => 'Tesseramenti',
                        '2' => 'Pagamento',
                        '5' => 'Conferma Dati'
                    );
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

                        <h2 >Verifica i dati di tesseramento</h2>

                    </header>

                    <div class="col-md-9">

                        <div class="athlete-box">

                            <div class="anagrafica-box">

                                <!--  ------------------------------------------------------------------------------------------------ -->

                                <? foreach ($tesserati['atleti'] as $i => $tesserato): ?>

                                    <section class="panel" >

                                        <? //echo $i;  ?>
                                        <? //print_r($tesserato);  ?>

                                        <header class="panel-heading">

                                            <div class="panel-actions">

                                                <div id="nome_barra" class="left-element" style="float:left;"><div class="btn btn-default"  onclick="reset_text_box('<?= $i ?>')">Reload</div></div>

                                                <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a> 

                                            </div>

                                            <div ><h2 class="panel-title" id="cognome_nome_<?= $i ?>"><strong><?= $tesserato['Cognome'] . " " . $tesserato['Nome'] ?></strong></h2></div>

                                        </header>

                                        <div class="panel-body ats">

                                            <form class="form-horizontal form-bordered" autocomplete="off" method="post" onsubmit="return false;">

                                                <input type="hidden" name="<?= $i ?>" id="Atleta" value="<?= $tesserato['Atleta'] ?>"> <!--//GIUSEPPE -->

                                                <div class="form-group">

                                                    <label class="col-md-3 control-label" for="inputDefault"><strong>Cognome:<sup>*</sup></strong></label>

                                                    <div class="col-md-6">

                                                        <input id="Cognome_<?= $i ?>" name="<?= $i ?>" class="form-control" value="<?= $tesserato['Cognome'] ?>" readonly="readonly" >

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

                                                        <input id="Nome_<?= $i ?>" name="<?= $i ?>" class="form-control" value="<?= $tesserato['Nome'] ?>" readonly="readonly" >

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
                                                        <input id="Email" name="<?= $i ?>" class="form-control email" value="<?= $tesserato['Email'] ?>">
                                                    </div>
                                                    <div id="mess_Email" name="<?= $i ?>" style="padding-top: 5px; text-align: center; font-style: italic; color:#000;"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="inputDefault"><strong>Data di nascita:<sup>*</sup></strong></label>
                                                    <div class="col-md-6">
                                                        <input id="DataNascita" name="<?= $i ?>" class="form-control datanasc" value="<?= $tesserato['DataNascita'] ?>"  readonly="readonly" >

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
                                                        <input id="LuogoNascita" name="<?= $i ?>" class="form-control" value="<?= $tesserato['LuogoNascita'] ?>">

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
                                                        <input id="CodiceFiscale" name="<?= $i ?>" class="form-control" value="<?= $tesserato['CodiceFiscale'] ?>" maxlength="16" style="text-transform:uppercase">
                                                    </div>
                                                    <div id="mess_CF" name="<?= $i ?>" style="padding-top: 5px; text-align: center; font-style: italic; color:#000;"></div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="inputDefault">Indirizzo:</label>
                                                    <div class="col-md-6">
                                                        <input id="Indirizzo" name="<?= $i ?>" class="form-control" value="<?= $tesserato['Indirizzo'] ?>">


                                                        <?
                                                        //= $this->Form->input('Indirizzo', 
                                                        //	array('label' => false, 
                                                        //	'class' => 'form-control', 
                                                        //'required' => 'required')); 
                                                        ?>

                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="inputDefault">CAP:</label>
                                                    <div class="col-md-6">

                                                        <input id="Cap" name="<?= $i ?>" class="form-control" value="<?= $tesserato['Cap'] ?>"maxlength="5" style="text-transform:uppercase">


                                                        <?
                                                        //= $this->Form->input('Cap', 
                                                        //	array('label' => false, 
                                                        //'class' => 'form-control', 
                                                        //'required' => 'required'));
                                                        ?>

                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="inputDefault">Localit&agrave;:</label>
                                                    <div class="col-md-6">
                                                        <input id="Localita" name="<?= $i ?>" class="form-control" value="<?= $tesserato['Localita'] ?>">

                                                        <?
                                                        //= $this->Form->input('Localita', 
                                                        //	array('label' => false, 
                                                        //'class' => 'form-control'));
                                                        ?>

                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="inputDefault">Provincia:</label>
                                                    <div class="col-md-6">

                                                        <input id="Provincia" name="<?= $i ?>" class="form-control" value="<?= $tesserato['Provincia'] ?>" maxlength="2" style="text-transform:uppercase">



                                                        <?
                                                        //= $this->Form->input('Provincia', 
                                                        //array('label' => false, 
                                                        //'class' => 'form-control'));
                                                        ?>

                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="inputDefault">Telefono cellulare</label>
                                                    <div class="col-md-6">
                                                        <input id="Cellulare" name="<?= $i ?>" class="form-control" value="<?= $tesserato['Cellulare'] ?>">


                                                        <?
                                                        //= $this->Form->input('Cellulare', 
                                                        //	array('label' => false, 
                                                        //'class' => 'form-control'));
                                                        ?>

                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="inputDefault">Sesso</label>
                                                    <div class="col-md-6">

                                                        <select id="Sesso" name="<?= $i ?>" class="form-control"  readonly="readonly" >
                                                            <option value="Maschio" <? if ($tesserato['Sesso'] == "Maschio"): ?>selected="selected"<? endif; ?> >Maschio</option>
                                                            <option value="Femmina" <? if ($tesserato['Sesso'] == "Femmina"): ?>selected="selected"<? endif; ?> >Femmina</option>
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
                                                    <label class="col-md-3 control-label" for="inputDefault">Tipo Documento:</label>
                                                    <div class="col-md-6">

                                                        <select id="TipoDocumento" name="<?= $i ?>" class="form-control"> 
                                                            <option value="<?= utf8_encode("Carta Identità") ?>" <? if (strstr($tesserato['TipoDocumento'], "Carta")): ?>selected="selected"<? endif; ?> ><?= utf8_encode("Carta Identità") ?></option>
                                                            <option value="Patente"  <? if (strstr($tesserato['TipoDocumento'], "Patente")): ?>selected="selected"<? endif; ?>  >Patente</option>
                                                            <option value="Passaporto"  <? if (strstr($tesserato['TipoDocumento'], "Passaporto")): ?>selected="selected"<? endif; ?>  >Passaporto</option>
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

                                                        <input id="NumeroDocumento" name="<?= $i ?>" class="form-control" value="<?= $tesserato['NumeroDocumento'] ?>">

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

                                <? endforeach; ?>

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

                <!--
                        <div class="contents-text">
                        
                        
                        
                        
                        <div style="padding: 20px;">
                        
                        
                        
                        <div class="alert alert-success" style="text-align: center;">
                        Grazie, in data <strong><?= date("d/m/Y H:i"); ?></strong> abbiamo ricevuto la tua richiesta per n.� 
                        <strong><?= count($tesserati['atleti']); ?> </strong> tesseramenti <br />e un pagamento di 
                        <strong><?= $tesserati['atleti'][0]['totale']; ?> &euro;</strong> effettuato tramite <strong>Carta di Credito</strong>
                        </div>
                        
                        <div class="call-to-action-btn" style="text-align: center; margin-top: 40px;"> 
                        <a class="btn btn-sm btn-primary" href="/">Ritorna alla home page</a>
                        </div>
                        
                        </div>
                        
                        
                        </div>
                -->
            </div>
        </div>
    </div>			

    <script type="text/javascript">

        var dati_atleti;

        var dati_atleti_upload;

        $(document).ready(function ()
        {
            $('.datanasc').mask('00/00/0000');

            dati_atleti = <?= json_encode($tesserati) ?>;		// carico di dati degli atleti (quelli originari non ancora modificati). Mi servir� per il reload

            dati_atleti_upload = dati_atleti;	// carico di dati degli atleti originari. Poi le modifiche verranno fatte qui dentro e quindi l'upload;

            alert('Controlla e conferma i dati degli atleti.\n\nA seguito della conferma ogni atleta che hai tesserato riceverà una email di riepilogo nella quale saranno riportati esclusivamente i suoi dati personali.\n\nN.B: Se non effettui la procedura di conferma nessuna email sarà inviata.');

        });



        $('.email').live('keyup', function (event, data)
        {

            var me = $(this);

            var form = me.closest('form');

            var index = me.attr('name')

            validateMail = false;

            var emailWindow = form.find('#Email').val();

            var id = form.find("#Atleta").val();

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
                        form.find('#Email').val('');
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
                        console.log("L'IP di destinazione non � valido!");
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



            switch (id_textbox) // solo Cognome e Nome hanno l'id diverso dalle propriet� del joson atleti
            {
                case "Cognome_" + index:
                    //console.log(" -> " + $(this).val());
                    id_textbox = "Cognome";
                    break;

                case "Nome_" + index:
                    //console.log(" -> " + $(this).val());
                    id_textbox = "Nome";
                    break;
            }

            dati_atleti_upload['atleti'][index][id_textbox] = $(this).val();

        });


        $(".form-control").click(function () // se cambio sesso o tipo documento
        {
            var id_textbox = $(this).attr('id');

            var index = $(this).attr('name');

            if (id_textbox === "Sesso" || id_textbox === "TipoDocumento")
            {
                //console.log(" -> " + id_textbox);

                dati_atleti_upload['atleti'][index][id_textbox] = $(this).val();
            }
        })
        // ........................................................................




        // RELOAD VALORI INIZIALI .................................................

        function reset_text_box(index)
        {
            //console.log(" -> " + index);

            var id_text_box;

            dati_atleti = <?= json_encode($tesserati) ?>;

            $(".panel").each(function (index_panel)
            {
                if (index_panel == parseInt(index))
                {

                    for (property in dati_atleti['atleti'][index]) // leggo le propriet� del json
                    {

                        if (property === "Cognome" || property === "Nome")
                        {
                            id_text_box = property + "_" + index;
                        }
                        else
                        {
                            id_text_box = property;
                        }

                        //console.log(" -> " + id_text_box);

                        if (document.getElementById(id_text_box) != null) // non � detto che tutte le propriet� siano inserite nelle text box
                        {

                            var value_for_textbox = dati_atleti['atleti'][index][property];

                            dati_atleti_upload['atleti'][index][property] = value_for_textbox;

                            $(this).find("#" + id_text_box).val(value_for_textbox);

                            //console.log(" ...-> " + value_textbox);
                        }
                    }

                    $("#cognome_nome_" + index).html("<strong>" + dati_atleti['atleti'][index]["Cognome"] + " " + dati_atleti['atleti'][index]["Nome"] + "</strong>");

                    //console.log("OK");
                }
            });
        }

        // ........................................................................

        $("#nextstep").click(function ()
        {
            //controllare che le textbox principali siano piene

            var id_text_box;

            var cognome_nome = [];

            var exist_error = false;

            var position_first_error = 0;

            $(".panel").each(function (index_panel)
            {

                var id_obbligatori = ["Cognome_" + index_panel, "Nome_" + index_panel, "Email", "DataNascita", "LuogoNascita", "CodiceFiscale", "NumeroDocumento"];

                for (property in dati_atleti_upload['atleti'][index_panel]) // leggo le propriet� del json
                {
                    //console.log(dati_atleti_upload['atleti'][index_panel][property]);

                    if (property === "Cognome" || property === "Nome")
                    {
                        id_text_box = property + "_" + index_panel;
                    }
                    else
                    {
                        id_text_box = property;
                    }

                    if (id_obbligatori.indexOf(id_text_box) != -1) // mi accerto che la text box è un dato obbligatorio
                    {

                        if ($(this).find("#" + id_text_box).val() == "") // in caso di textbox vuota
                        {
                            $(this).find("#" + id_text_box).closest('.form-group').addClass("has-error");

                            exist_error = true;

                            if (position_first_error == 0)
                            {
                                var offset = $(this).find("#" + id_text_box).offset();

                                position_first_error = offset.top; // in caso di dati obbligatori mancanti, ricavo la posizione del primo dato vuoto, per poi posizonarmi su

                                //console.log(offset.top)
                            }

                            //return;
                        }
                        else  // in caso di textbox non vuota
                        {

                            $(this).find("#" + id_text_box).closest('.form-group').removeClass("has-error");


                            if (id_text_box == 'Email')
                            {
                                $(this).find("#mess_Email").html('');

                                var check_email = true;

                                var caratteri_email = $(this).find("#" + id_text_box).val();

                                check_email = emailCheck(caratteri_email);

                                if (check_email == false)
                                {
                                    exist_error = true;

                                    var text_Email = "L'email non è valida";

                                    $(this).find("#" + id_text_box).closest('.form-group').addClass("has-error");

                                    $(this).find("#mess_Email").html(text_Email);


                                    if (position_first_error == 0)
                                    {
                                        var offset = $(this).find("#" + id_text_box).offset();

                                        position_first_error = offset.top; // in caso di dati obbligatori mancanti, ricavo la posizione del primo dato vuoto, per poi posizonarmi su

                                    }
                                }
                            }
                            else if (id_text_box == 'CodiceFiscale') // controllo la lunghezza del CF
                            {
                                $(this).find("#mess_CF").html('');

                                var caratteri_CF = $(this).find("#" + id_text_box).val();

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

                                    $(this).find("#" + id_text_box).closest('.form-group').addClass("has-error");

                                    $(this).find("#mess_CF").html(text_CF);


                                    if (position_first_error == 0)
                                    {
                                        var offset = $(this).find("#" + id_text_box).offset();

                                        position_first_error = offset.top; // in caso di dati obbligatori mancanti, ricavo la posizione del primo dato vuoto, per poi posizonarmi su

                                    }
                                }

                            }

                        }

                    }

                }

                if (exist_error) // inserisco il nome e il cognome degli atleti con dati obbligatori non completi
                {
                    cognome_nome.push(dati_atleti_upload['atleti'][index_panel].Cognome + " " + dati_atleti_upload['atleti'][index_panel].Nome);

                    exist_error = false;
                }

            });

            if (cognome_nome.length > 0)
            {

                var attack_to_names;

                if (cognome_nome.length == 1)
                {
                    attack_to_names = "\n\n - non ha tutti i dati obbligatori - ";
                }


                if (cognome_nome.length > 1)
                {
                    attack_to_names = "\n\n - non hanno tutti i dati obbligatori - ";
                }

                alert(cognome_nome.join("\n\n") + attack_to_names);// join unisce il contrenuto dell'array in un a stringa con separatore "\n"

                //alert("MANCANO DEI DATI OBBLIGATORI");

                $('body,html').animate({scrollTop: position_first_error - 200}, 0); // mi posizione sul primo errore
            }
            else
            {
                //console.log("AVANTI");

                $("#ajax-loader").fadeIn(200);

                $.post("/sections/updateTesseramentiLandPage", {tesserati_upload: JSON.stringify(dati_atleti_upload), uniqid: '<?= $uniqid ?>'}, function (result)
                {

                    console.log(result);

                    location.href = "/sections/tesseraticonfirm/<?= $uniqid ?>"

                });
            }

        });

    </script>