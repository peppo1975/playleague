<? 
//GIUSEPPE  20/10/2016 -> filtra la classe
$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 
$nameClass = $classPage["Name"];

$type_sport = array("primary" => "CALCIO", "secondary" => "CALCIO", "quaternary" => "TENNIS");

$sport = $type_sport[$nameClass];
$fixed = $this->requestAction('fixeds/read_all_fixed');
?>


<script type="text/javascript" src="/js/jQuery-Mask-Plugin/dist/jquery.mask.min.js"></script>
<script type="text/javascript">

    $(document).ready(function () {

        $('#UserDataNascita').mask('00/00/0000');
        //location.href = "conferma";
    });

    $(function () {

        $('#UserEmail').focusout(function () {

        });

        $("#controlla").click(function ()
        {
            var Athlete =
                    {
                        "Cognome": $("#UserCognome").val(),

                        "Nome": $("#UserNome").val(),

                        "DataNascita": $("#UserDataNascita").val(),

                        "LuogoNascita": $("#UserLuogoNascita").val(),

                        "Sesso": $("#UserSesso").val(),

                        "Email": $("#UserEmail").val(),

                        "Password": $("#UserPassword").val(),

                        "PasswordConfirm": $("#UserPasswordConfirm").val()
                    }

            //console.log(Athlete);

            var result_read_all = read_all(Athlete);

            //alert(result_read_all);

            if (result_read_all)
            {
                $.post("/athletes/read_for_entry/", {Athlete}, function (data)
                {
                    //console.log(data);

                    var response = true;

                    if (data.localeCompare("ATLETA_PRESENTE") == 0)
                    {
                        alert("ATTENZIONE!!!\n\nL'atleta che stai cercando di registrare risulta gia registrato nel nostro database!");

                        error_box("UserCognome");

                        error_box("UserNome");

                        error_box("UserDataNascita");

                        $('#mess_' + "UserDataNascita").html('atleta già registrato!');

                        response = false;

                    }
                    else if (data.localeCompare("EMAIL_PRESENTE") == 0)
                    {
                        alert("ATTENZIONE!!!\n\nL'indirizzo email inserito risulta gia registrato nel nostro database.\n\Ti consigliamo di usare un altro indirizzo email!");

                        error_box("UserEmail");

                        $('#mess_' + "UserEmail").html('usa un indirizzo email diverso!');

                        response = false;
                    }


                    if (response == true)
                    {
                        console.log("controllo data nascita");

                        $.post("/athletes/insert_for_confirm/", {Athlete}, function (data)
                        {
                            //console.log(data);

                            //return 0;

                            if (data.localeCompare("NO_ETA_MINIMA") == 0)
                            {
                                error_box("UserDataNascita");

                                $('#mess_' + "UserDataNascita").html('età minima: 4 anni');
                            }

                            else if (data.localeCompare("DATA_NASCITA_NON_VALIDA") == 0)
                            {
                                error_box("UserDataNascita");

                                $('#mess_' + "UserDataNascita").html('data di nascita non valida');
                            }
                            else
                            {
                                location.href = '/registrazione/conferma';
                            }

                        });

                        //console.log(Athlete);
                    }

                });
            }

        });
    });



    // GIUSEPPE 2017-03-20 --------------------------------------------

    // controllo che tutti i campi siano pieni
    function read_all(obj_var)
    {
        var result = true;

        var result_validity = true;

        for (i in obj_var)
        {
            no_error_box("User" + i);

            $('#mess_' + "User" + i).html('');

            if (obj_var[i] === "")
            {
                error_box("User" + i);

                result = false;
            }

        }

        result_validity = read_validity(obj_var);

        return result && result_validity;
    }

    //controllo la validità di datanascita, email e password

    function read_validity(obj_var)
    {
        var result = true;


        if (obj_var["DataNascita"].length < 10)
        {
            console.log("Data di nascita non valida");

            error_box("UserDataNascita");
            $("#mess_UserDataNascita").html('data di nascita non valida');

            result = false;
        }

        if (!emailCheck(obj_var["Email"]))
        {
            console.log("email non valida");

            $("#mess_UserEmail").html('email non valida');

            error_box("UserEmail");

            result = false;
        }

        var pasw = obj_var["Password"];

        var pasw_confirm = obj_var["PasswordConfirm"];

        if (pasw.localeCompare(pasw_confirm) != 0)
        {
            error_box("UserPassword");

            error_box("UserPasswordConfirm");

            $("#mess_UserPasswordConfirm").html('le password sono differenti');

            result = false;
        }

        //console.log(obj_var);

        return result;
    }


    // bordo rosso in caso di errore
    function error_box(id_textbox)
    {
        selDiv = document.getElementById(id_textbox);
        selDiv.style.border = "solid 1px red";
    }

    // toglie il bordo rosso in caso di errore
    function no_error_box(id_textbox)
    {
        selDiv = document.getElementById(id_textbox);
        selDiv.style.border = "";
    }

    // controlla la validità delle email
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
    // ----------------------------------------------------------------

</script>




<div role="main" class="main">

    <div style="background: #f5f5f5; margin-bottom: 20px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumb" style="margin-bottom: 0">
                        <li><a href="/">Home</a></li>
                        <li class="active">Registrazione atleti</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container"  id="main-custom">

        <div class="row">
            <div class="col-md-9">

                <h2>Modulo di registrazione atleti</h2>

                <p>
                    Per partecipare alle manifestazioni sportive organizzate e gestite da <strong> <?= $fixed['societa_nome'] ?> </strong> è necessario effettuare la procedura di registrazione 
                    come atleta.
                    Effettuata tale procedura ogni utente registrato come atleta potrà effettuare le procedure di "Iscrizione squadre" e "Tesseramento" atleti.    
                </p>

                <?=
                $this->Form->create('User', array('url' => '/registrazione/atleti', 'id' => 'formSignupAthlete',
                    'class' => 'form-horizontal',
                    'inputDefaults' => array(
                        'format' => array('before', 'between', 'label',
                            'input', 'error', 'after'),
                        'class' => 'form-control',
                        'div' => array('class' => 'form-group'),
                        'label' => array('class' => 'col-lg-2 control-label'),
                        'between' => '<div class="col-lg-12">',
                        'after' => '</div>',
                        'error' => array('attributes' => array('wrap' => 'span',
                                'class' => 'text-danger')),
                    )
                ));
                ?>
                <fieldset>

                <style type="text/css">
                    .athlete-signup-module label{
                        font-weight: bold;
                        color: #000000;
                    }

                    .athlete-signup-module .col-md-6{
                        margin-bottom: 10px;
                    }

                    .athlete-signup-module .form-group{
                        margin-bottom: 5px !important;
                    }

                </style>

                    <div class="row athlete-signup-module">
                            <div class="col-md-6">
                                <?= $this->Form->input('Cognome', array('type' => 'text', 'label' => 'Cognome')); ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->input('Nome', array('type' => 'text', 'label' => 'Nome')); ?>	
                            </div>

                            <div class="col-md-6">
                                <?=
                                $this->Form->input('Sesso', array(
                                    'label' => 'Sesso',
                                    'type' => 'select',
                                    'options' => array('Maschio' => 'Maschio', 'Femmina' => 'Femmina'),
                                ));
                                ?>
                            </div>

                            <div class="col-md-6">
                                <?= $this->Form->input('Email', array('type' => 'text', 'label' => 'Email')); ?>
                                <div id="mess_UserEmail" style="text-align: left; font-style: italic;"  class="text-danger"></div>
                            </div>

                            <div class="col-md-6">
                                <?= $this->Form->input('Password', array('type' => 'password', 'label' => 'Password')); ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->input('Password_confirm', array('type' => 'password', 'label' => 'Conferma password')); ?>
                                <div id="mess_UserPasswordConfirm" style="text-align: left; font-style: italic;"  class="text-danger"></div>
                            </div>

                            <div class="col-md-6">
                                <?= $this->Form->input('DataNascita', array('type' => 'text', 'label' => 'Data di nascita')); ?>
                                <div id="mess_UserDataNascita" style="text-align: left; font-style: italic;" class="text-danger"></div>
                            </div>

                            <div class="col-md-6">
                                <?= $this->Form->input('LuogoNascita', array('type' => 'text', 'label' => 'Luogo di nascita')); ?>
                            </div>


                            

                        <!--                        <div class="col-md-6">
                        <? //= $this->Form->input('Cognome', array('type' => 'text', 'label' => 'Cognome'));   ?>
                                                </div>-->
                    </div>
                    <!--                    <div class="row">
                                                <div class="col-md-6">
                    <? //= $this->Form->input('Tessera', array('type' => 'text', 'label' => 'Numero tessera'));  ?>
                    <? //if ($sport == "CALCIO"): ?>
                                                                                                                <small>Il <strong>numero ti tessera</strong> è visibile anche sul sito, cercando la propria squadra nell'omonima sezione dedicata</small>
                    <? // elseif ($sport == "TENNIS"): ?>
                                                                                                                <small>Il <strong>numero ti tessera</strong> è visibile anche sul sito, cercando la propria squadra nella sezione “Calendari e classifiche ” in home page, selezionando la propria squadra in un torneo disputato</small>
                    <? // endif;  ?>
                                                </div>
                                                <div class="col-md-6">
                    <? //= $this->Form->input('signup_code', array('type' => 'text', 'label' => 'Codice controllo'));  ?>
                    <? //if ($sport == "CALCIO"): ?>
                                                                                                                <small>Il <strong>codice di controllo</strong>  è assegnato d’ufficio al presidente della squadra, contattare il suddetto o la sede</small>
                    <? // elseif ($sport == "TENNIS"): ?>
                                                                                                                <small>Il <strong>codice di controllo</strong> è assegnato d’ufficio al referente/capitano della squadra, contattare il suddetto o la sede</small>
                    <? //endif;  ?>
                                                </div>		
                                        </div>-->
                    <div class="row" style="margin-top:40px;">
                        <div class="col-md-12" style="margin-bottom:20px;">
                            <small><strong>*</strong> campi obbligatori</small>
                        </div>
                        <div class="col-md-12">
                            <? //= $this->Form->submit('Registrati ora', array('type' => 'submit', 'div' => true, 'id' => 'controlla', 'class' => 'btn btn-primary pull-left mb-xl'));  ?>

                            <input type="button" id="controlla" class="btn btn-primary pull-left mb-xl" value="Registrati ora">

                        </div>

                    </div>
                </fieldset>

                <?= $this->Form->end(); ?>

                <div class="athlete_info">

                </div>



            </div><!-- close contents-box -->

            <!--            <div class="col-md-3">
                                <aside class="sidebar">
                                <h4 class="heading-primary">Crea nuovo account</h4>
                                <ul class="nav nav-list narrow">
                                <li  class="" >
                                <a href="/registrazione" title="">
                                Registrazione utenti
                                </a>
                                </li>
                                <li class="active">
                                <a href="/registrazione/atleti"  class="" title="">
                                Registrazione atleti
                                </a>
                                </li>
                                
                                </ul>
                                </aside>
                        </div>-->

        </div><!-- close wrapper-box-contents -->

    </div><!-- close wrapper-box -->
</div>