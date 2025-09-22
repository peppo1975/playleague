<?
//GIUSEPPE  20/10/2016 -> filtra la classe
$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 
$nameClass = $classPage["Name"];

$type_sport = array("primary"=>"CALCIO","secondary"=>"CALCIO","quaternary"=>"TENNIS");

$sport = $type_sport[$nameClass];

?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#genera").click(function () {
            $("#UserPassword").val('');
            $("#UserPasswordConfirm").val('');
            $.get("/admin/users/generatepwd", function (ret) {
                $("#UserPassword").val(ret.pwd);
                $("#UserPasswordConfirm").val(ret.pwd);
            }, 'json');
        });
    });

    $(function () {

        $("#formSignupAthlete").submit(function () {

            var data = $(this).serialize();

            $('.error-message').remove();
            var error = 0;
            $('#formSignupAthlete .required').each(function () {
                var obj = $(this);
                if (obj.find('input').val() == '')
                {
                    obj.append('<div class="error-message">Campo obbligatorio.</div>');
                    error = 1;
                }
            });
            if (error == 1)
                return false;

            ajaxLoader('show');

            $.post('/users/checkTessera', data, function (ret) {
                $('.athlete_info').html(ret);
                ajaxLoader('hide');
            }, 'html');

            return false;

        });

    });

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

                    <div class="row">
                        <div class="col-md-6">
                            <?= $this->Form->input('Nome', array('type' => 'text', 'label' => 'Nome')); ?>	
                        </div>
                        <div class="col-md-6">
                            <?= $this->Form->input('Cognome', array('type' => 'text', 'label' => 'Cognome')); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $this->Form->input('Tessera', array('type' => 'text', 'label' => 'Numero tessera')); ?>
                            <?if($sport=="CALCIO"):  ?>
                            <small>Il <strong>numero ti tessera</strong> è visibile anche sul sito, cercando la propria squadra nell'omonima sezione dedicata</small>
                            <?elseif($sport=="TENNIS"): ?>
                             <small>Il <strong>numero ti tessera</strong> è visibile anche sul sito, cercando la propria squadra nella sezione “Calendari e classifiche ” in home page, selezionando la propria squadra in un torneo disputato</small>
                             <?endif;?>
                        </div>
                        <div class="col-md-6">
                            <?= $this->Form->input('signup_code', array('type' => 'text', 'label' => 'Codice controllo')); ?>
                             <?if($sport=="CALCIO"):  ?>
                            <small>Il <strong>codice di controllo</strong>  è assegnato d’ufficio al presidente della squadra, contattare il suddetto o la sede</small>
                            <?elseif($sport=="TENNIS"): ?>
                             <small>Il <strong>codice di controllo</strong> è assegnato d’ufficio al referente/capitano della squadra, contattare il suddetto o la sede</small>
                             <?endif;?>
                        </div>		
                    </div>
                    <div class="row" style="margin-top:40px;">
                        <div class="col-md-12" style="margin-bottom:20px;">
                            <small><strong>*</strong> campi obbligatori</small>
                        </div>
                        <div class="col-md-12">
                            <?= $this->Form->submit('Registrati ora', array('type' => 'submit', 'div' => true, 'id' => 'controlla', 'class' => 'btn btn-primary pull-left mb-xl')); ?>
                            
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
