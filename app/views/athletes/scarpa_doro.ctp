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
<? //2018-06-07 file nuovo        ?>

<?
$scarpa_oro_m5 = bubble_sort($maschile_c5);
$scarpa_oro_m7 = bubble_sort($maschile_c7);
$scarpa_oro_f5 = bubble_sort($femminile_c5);
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
                            Scarpa d'oro
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


                                Scarpa d'oro

                            </h2>
                            <p>
                                
                                Ancora una bella novità esclusiva! Per premiare i super bomber della stagione (divisi per calcio a 5, calcio a 7 e femminile)
                                il sistema stilerà una speciale classifica tenendo conto dei gol fatti in tutta la stagione, in ogni squadra in cui un giocatore ha militato, e anche delle presenze.
                                Dalla stagione 2018/19 sarà anche istituito un premio dedicato!

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
                            <? $type_rounds = $scarpa_oro_m5 ?>
                            <? include 'table_scarpa_doro.php'; ?>
                        </div>

                        <div id="maschili7">
                            <? $type_rounds = $scarpa_oro_m7 ?>
                            <? include 'table_scarpa_doro.php'; ?>
                        </div>

                        <div id="femminili5">
                            <? $type_rounds = $scarpa_oro_f5 ?>
                            <? include 'table_scarpa_doro.php'; ?>
                        </div>


                    </div>

                    <div class="clear"></div>
                </div>
            </div>
            <div class="wrapper-box-bottom"></div>
        </div>
    </div>
</div>
</div>
<?





function bubble_sort($scarpa_oro)
{
    $sort_athletes = array();
    /* devo prima indicizzarli... */

    foreach ($scarpa_oro as $key_athlete => $info_goals)
    {
        $sort_athletes[] = $info_goals;
    }

    /*
      $file = 'scarpa_oro_bubble.txt';

      if (file_exists($file))
      unlink($file);

      file_put_contents($file, print_r($temp_athletes, true));

     */

    $length = count($sort_athletes);

    do
    {
        $scambio = false;
        $temp = "";
        for ($i = 0; $i < $length - 1; $i++)
        {
            if ($sort_athletes[$i]['GareGoal'] > $sort_athletes[$i + 1]['GareGoal'])
            {
                $temp = $sort_athletes[$i + 1];
                $sort_athletes[$i + 1] = $sort_athletes[$i];
                $sort_athletes[$i] = $temp;
                $scambio = true;
            }
        }
        //break;

        if (!$scambio)
            break;
    }
    while (true);

    /*
      $file = 'scarpa_oro_bubble_1_step.txt';

      if (file_exists($file))
      unlink($file);

      file_put_contents($file, print_r($temp_athletes, true));
     */


    do
    {
        $scambio = false;
        $temp = "";
        for ($i = 0; $i < $length - 1; $i++)
        {
            if ($sort_athletes[$i]['Goal'] < $sort_athletes[$i + 1]['Goal'])
            {
                $temp = $sort_athletes[$i + 1];
                $sort_athletes[$i + 1] = $sort_athletes[$i];
                $sort_athletes[$i] = $temp;
                $scambio = true;
            }
        }
        //break;

        if (!$scambio)
            break;
    }
    while (true);

   
      $file = 'scarpa_oro_bubble_2_step.txt';

      if (file_exists($file))
      unlink($file);

      file_put_contents($file, print_r($sort_athletes, true));
     

    return $sort_athletes;
}
?>