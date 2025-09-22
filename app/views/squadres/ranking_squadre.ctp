<script type="text/javascript">
    $(document).ready(function () {

        $(".M5").addClass('active');

        $("#maschili5").show();
        $("#maschili7").hide();
        $("#femminili5").hide();





        $(".M5").click(function () {

            $(".M5").addClass('active');
            $(".M7").removeClass('active');
            $(".F5").removeClass('active');

            $("#maschili5").show();
            $("#maschili7").hide();
            $("#femminili5").hide();
        });



        $(".M7").click(function () {

            $(".M5").removeClass('active');
            $(".M7").addClass('active');
            $(".F5").removeClass('active');


            $("#maschili5").hide();
            $("#maschili7").show();
            $("#femminili5").hide();
        });



        $(".F5").click(function () {

            $(".M5").removeClass('active');
            $(".M7").removeClass('active');
            $(".F5").addClass('active');


            $("#maschili5").hide();
            $("#maschili7").hide();
            $("#femminili5").show();
        });


    });
</script>
<?
$dir_save = APP . "webroot/files/ranking_teams";
$file = 'ranking_squadre_year.txt';
//$ranking_teams = unserialize(file_get_contents($dir_save . "/" . $file));
//echo $rankin_teams;
//echo $dir_save . "/" . $file;
//print_r($rankin_teams);
?>
<div role="main" class="main">
    <div style="background: #f5f5f5; margin-bottom: 20px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumb" style="margin-bottom: 0">
                        <li><a href="/">Home</a></li>
                        <li  class="">
                            Squadre
                        </li>
                        <li class="">
                            Ranking squadre
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
                                Ranking squadre
                            </h2>
                            <p>

                               Un'altra incredibile esclusiva! Con un calcolo matematico il nostro gestionale mostra attraverso i numeri
                                la "qualità" delle squadre*. Un servizio che potrà anche servire per orientarsi nella scelta delle manifestazioni a livello dedicato.
                            </p>
                            <p>
                                <small>*Il coefficiente è generato tenendo conto: del numero delle stagioni di partecipazione, del numero dei tornei disputati, del numero di tornei vinti e dei punteggi individuali di ogni singolo tesserato, calcolando di ognuno il numero di gare disputate,
                                con diversa assegnazione di punti tenendo conto di quelle vinte, pareggiate o perse.</small>

                            </p>
                        </div>

                    </div>
                </div>
                <hr />

                <div class="tabs tabs-bottom tabs-center tabs-simple">
                    <ul class="nav nav-tabs">

                        <li class="M5">
                            <a href="#" id="M5" data-toggle="tab"><?= "Maschili C5" ?></a>
                        </li>

                        <li class="M7">
                            <a href="#" id="M7" data-toggle="tab"><?= "Maschili C7" ?></a>
                        </li>

                        <li class="F5">
                            <a href="#" id="F5" data-toggle="tab"><?= "Femminili C5" ?></a>
                        </li>

                    </ul>
                    <div class="tab-content" style="text-align: center">

                        <div id="maschili5">
                            <? $type_rounds = array("Tipo"=>"C5","Sesso"=>"M") ?>
                            <? include 'table_ranking_squadre.php'; ?>
                        </div>

                        <div id="maschili7">
                            <?  $type_rounds = array("Tipo"=>"C7","Sesso"=>"M") ?>
                            <? include 'table_ranking_squadre.php'; ?>
                        </div>

                        <div id="femminili5">
                            <?  $type_rounds = array("Tipo"=>"C5","Sesso"=>"F")  ?>
                            <? include 'table_ranking_squadre.php'; ?>
                        </div>


                    </div>

                    <div class="clear"></div>
                </div><!-- close wrapper-box-contents -->
                <div class="wrapper-box-bottom"></div>
            </div><!-- close wrapper-box --> 
        </div>
    </div>
</div>




