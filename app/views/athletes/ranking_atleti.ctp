<script type="text/javascript" src="/js/layout.js"></script>
<script type="text/javascript">
    $(document).ready(function ()
    {
        //GIUSEPPE 2016-12-19 

        $("#maschili_singoli").show();
        $("#maschili_doppi").hide();
        $("#femminili_singoli").hide();
        $("#femminili_doppi").hide();/**/


        //GIUSEPPE 2016-12-17
        //
        //GIUSEPPE 2019-10-30
        $("#maschile_singolo").click(function ()
        {
            $("#maschili_singoli").show();
            $("#maschili_doppi").hide();
            $("#femminili_singoli").hide();
            $("#femminili_doppi").hide();
        });
        $("#maschile_doppio").click(function ()
        {

            $("#maschili_singoli").hide();
            $("#maschili_doppi").show();
            $("#femminili_singoli").hide();
            $("#femminili_doppi").hide();
        });


        $("#femminile_singolo").click(function ()
        {
            $("#maschili_singoli").hide();
            $("#maschili_doppi").hide();
            $("#femminili_singoli").show();
            $("#femminili_doppi").hide();
        });

        $("#femminile_doppio").click(function ()
        {
            $("#maschili_singoli").hide();
            $("#maschili_doppi").hide();
            $("#femminili_singoli").hide();
            $("#femminili_doppi").show();
        });


        $(".main").click(function () { /*toglie il popover se clicco su qualsiasi punto della pagina */
            $('[data-toggle="popover"]').popover('hide');
            
        });

        $('.popup-marker').popover({
            html: true,
            trigger: 'manual',
            container: 'body'
        }).click(function (e)
        {
            //$('[data-toggle="popover"]').popover('hide');
            my = $(this);
            setTimeout(function () { /*  metto questo timeout, perchè entra in azione il  "$(".main").click(..." subito dopo il popover, chiudendolo */
                my.popover('toggle');
                e.preventDefault();
            }, 100);

        });

    });
</script>
<style>
    .popup-marker{
        cursor: pointer;
    }

    .popover{
        max-width:1000px;
        max-height:1000px;
    }

</style>
<? // print_r($images) ?>
<? // $maschile_femminile = array(0 => "Graduatoria maschile", 1 => "Graduatoria femminile"); ?>
<?
//GIUSEPPE 2019-10-30
$maschile_femminile[0] = ['nome' => 'Maschile singolo', 'id' => 'maschile_singolo'];
$maschile_femminile[1] = ['nome' => 'Maschile doppio', 'id' => 'maschile_doppio'];
$maschile_femminile[2] = ['nome' => 'Femminile singolo', 'id' => 'femminile_singolo'];
$maschile_femminile[3] = ['nome' => 'Femminile doppio', 'id' => 'femminile_doppio'];
?>


<? //print_r($tessera_assicurazione)?>

<div role="main" class="main">

    <div style="background: #f5f5f5; margin-bottom: 20px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumb" style="margin-bottom: 0">
                        <li><a href="/">Home</a></li>
                        <li class="">
                            Ranking atleti
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container" id="main-custom">

        <div class="row">
            <div class="col-md-12">


                <div class="post-content">
                    <div class="row">
                        <div class="col-md-12">
                            <h2>


                                Ranking atleti

                            </h2>
                        </div>

                    </div>
                </div>
                <hr />
                <? // print_r($maschile_femminile)   ?>
                <div class="tabs tabs-bottom tabs-center tabs-simple">
                    <ul class="nav nav-tabs">
                        ?>

                        <? foreach ($maschile_femminile as $key => $tipo_sesso)://foreach($albo as $tipo_sesso => $categorie):       ?> 

                            <!-- 
                            <li class="<? if ($godpig == 0): ?>active<? $godpig = 1; ?><? endif; ?>">
                            <a href="#<?= strtolower(str_replace(" ", "_", $tipo_sesso)); ?>" id="<?= strtolower(str_replace(" ", "_", $tipo_sesso)); ?>" data-toggle="tab"><?= $tipo_sesso; ?></a>
                            </li>
                            -->

                            <!--//GIUSEPPE 2019-10-30-->
                            <li class="<? if ($key == 0): ?>active<? $key = 1; ?><? endif; ?>">
                                <a href="#<?= $tipo_sesso['id']; ?>" id="<?= $tipo_sesso['id']; ?>" data-toggle="tab"><?= $tipo_sesso['nome']; ?></a>
                            </li>
                            <!--*************************-->

                        <? endforeach; ?>

                    </ul>
                    <div class="tab-content">


                        <!--//GIUSEPPE 2019-10-30-->
                        <div id="maschili_singoli">
                            <? $sesso = "Maschio" ?>
                            <? $tipo = "points_f_s" ?>
                            <? include('table_ranking.ctp') ?>
                        </div>

                        <div id="maschili_doppi">
                            <? $sesso = "Maschio" ?>
                            <? $tipo = "points_f_d" ?>
                            <? include('table_ranking.ctp') ?>
                        </div>

                        <div id="femminili_singoli">
                            <? $sesso = "Femmina" ?>
                            <? $tipo = "points_f_s" ?>
                            <? include('table_ranking.ctp') ?>
                        </div>

                        <div id="femminili_doppi">
                            <? $sesso = "Femmina" ?>
                            <? $tipo = "points_f_d" ?>
                            <? include('table_ranking.ctp') ?>
                        </div>
                        <!--******************************-->


                        <div class="clear"></div>
                    </div><!-- close contents-box -->
                </div><!-- close wrapper-box-contents -->
                <div class="wrapper-box-bottom"></div>
            </div><!-- close wrapper-box --> 
        </div>
    </div>
</div>


<?

function calcola_gare($atleta)
{
    $win_s = (int) $atleta['partite']['win_s'];

    $win_d = (int) $atleta['partite']['win_d'];

    $total = $win_s + $win_d;

    $info = array();

    $impl = "";

    $res = $total;


    if ($win_s > 0)
    {
        $info[] = $win_s . "s";
    }
    if ($win_d > 0)
    {
        $info[] = $win_d . "d";
    }

    $impl = implode(", ", $info);

    if ($total > 0)
        $res = sprintf ("%s (%s)", $total, $impl);

    return $res;
}
?>