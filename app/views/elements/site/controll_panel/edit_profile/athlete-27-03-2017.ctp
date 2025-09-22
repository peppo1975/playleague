<?
//GIUSEPPE 2017-02-20 -> filtra la classe e il tipo di tesseramento

$sport_options = "no_options";

//$sport_options['view'] = true;

$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 

$nameClass = $classPage["Name"];

$listSport = array("primary" => "CALCIO", "secondary" => "CALCIO", "quaternary" => "TENNIS");

$sport = $listSport[$nameClass];

$typeCard = $this->requestAction('users/readSportAthlete/' . $this->data['Athlete']['Atleta'] . '/' . $sport); // questo valore lo troviamo nel controller (se l'atleta è tesserato allo sport della pagina da cui vuole accedere, mi restituisce lo sport stesso)

if ($typeCard[0] != "")
{
    $sport_options = $sport;
}
?>

<script type="text/javascript">

    $(function ()
    {

        $("body").delegate('.isNumber', 'keydown', function (e)
        {

            var code = e.keyCode;

            if (isNaN(String.fromCharCode(code)) && code != 8 && code != 40 && code != 38 && code != 37 && code != 39 && code != 116 && code != 9 && code != 46)
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
                        <li class="active">Informazioni personali</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="/vendor/theme.admin.extension.css">
    <link rel="stylesheet" href="/vendor/theme.extension.css">
    <div class="container" id="main-custom">
        <div class="row">
            <div class="col-md-12">
                <div class="tabs tabs-bottom tabs-center tabs-simple">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a data-toggle="" href="/gestione/profilo/<?= $this->data['Athlete']['Atleta']; ?>/Athlete" aria-expanded="true">
                                <span class="featured-boxes featured-boxes-style-6 p-none m-none">
                                    <span class="featured-box featured-box-primary featured-box-effect-6 p-none m-none" style="height: 100px;">
                                        <span class="box-content p-none m-none">
                                            <i class="icon-featured fa fa-user"></i>
                                        </span>
                                    </span>
                                </span>									
                                <p class="mb-none pb-none">Profilo utente</p>
                            </a>
                        </li>
                        <? if ($sport == "CALCIO" && $sport_options == "CALCIO"): //GIUSEPPE 2017-02-21 .... i menu dell'atleta devono variare in base al calcio o al tennis ?>
                            <li class="">
                                <a data-toggle="" href="/gestione/vota/<?= $sport ?>" aria-expanded="false">
                                    <span class="featured-boxes featured-boxes-style-6 p-none m-none">
                                        <span class="featured-box featured-box-primary featured-box-effect-6 p-none m-none" style="height: 100px;">
                                            <span class="box-content p-none m-none">
                                                <i class="icon-featured fa fa-star"></i>
                                            </span>
                                        </span>
                                    </span>									
                                    <p class="mb-none pb-none">Votazioni</p>
                                </a>
                            </li>


                            <li class="">
                                <a data-toggle="" href="/gestione/squadre" aria-expanded="false">
                                    <span class="featured-boxes featured-boxes-style-6 p-none m-none">
                                        <span class="featured-box featured-box-primary featured-box-effect-6 p-none m-none" style="height: 100px;">
                                            <span class="box-content p-none m-none">
                                                <i class="icon-featured fa fa-shield"></i>
                                            </span>
                                        </span>
                                    </span>									
                                    <p class="mb-none pb-none">Gestione squadre</p>
                                </a>
                            </li>
                        <? elseif ($sport == "TENNIS" && $sport_options == "TENNIS"): ?>

                            <li class="">
                                <a data-toggle="" href="/gestione/tennis_points" aria-expanded="false">
                                    <span class="featured-boxes featured-boxes-style-6 p-none m-none">
                                        <span class="featured-box featured-box-primary featured-box-effect-6 p-none m-none" style="height: 100px;">
                                            <span class="box-content p-none m-none">
                                                <i class="icon-featured fa fa-adjust"></i>
                                            </span>
                                        </span>
                                    </span>									
                                    <p class="mb-none pb-none">Partite tennis</p>
                                </a>
                            </li>

                        <? endif; ?>


                    </ul>
                    <div id="tabsNavigationSimpleIcons1" class="tab-pane">


                        <div style="padding: 20px;">
                            <?=
                            $this->Form->create('Athlete', [
                                'url' => '/gestione/profilo/' . $this->data['Athlete']['Atleta'] . '/' . 'Athlete', 'type' => 'file',
                                'id' => 'profile-form',
                                "class" => "form-horizontal form-bordered"]);
                            ?>

                            <div class="col-md-12 pinpin">
                                <div class="col-md-9">
                                    <?= $this->element('forms/athlete'); ?>
                                </div>
                                <div class="col-md-3">
                                    <div class="pin-wrapper" style="height: 223px;">
                                        <aside class="sidebar" id="sidebar" data-plugin-sticky="" data-plugin-options="{&quot;minWidth&quot;: 991, &quot;containerSelector&quot;: &quot;.pinpin&quot;, &quot;padding&quot;: {&quot;top&quot;: 110}}" style="width: 263px;">
                                            <?= $this->Form->submit('Modifica profilo', array('type' => 'submit', 'class' => 'btn btn-lg btn-info')); ?>
                                        </aside>
                                    </div>
                                </div>
                            </div>
                            <?= $this->Form->end() ?>
                        </div>
                    </div>
                </div>
            </div>


            <!-- <div class="col-md-3">
    <aside class="sidebar">
            <h4 class="heading-primary">Gestione account</h4>
            <ul class="nav nav-list narrow">
                    <li class="active"><a href="/gestione/profilo/<? //= $this->data['Athlete']['Atleta'];  ?>/Athlete" title="Informazioni personali">Informazioni personali</a></li>
                    <li><a href="/gestione/vota" title="Votazioni">Votazioni</a></li>
                    <li><a href="/gestione/squadre" title="Gestione squadre">Gestione squadre</a></li>
            </ul>
    </aside>
</div> -->
        </div>
    </div>
</div>


