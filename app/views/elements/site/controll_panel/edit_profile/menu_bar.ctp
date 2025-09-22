<?
//GIUSEPPE 2017-02-20 -> filtra la classe e il tipo di tesseramento

$sport_options = "no_options";

$user = $this->Session->read('Login.data');
//$sport_options['view'] = true;

$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 

$nameClass = $classPage["Name"];

$listSport = array("primary" => "CALCIO", "secondary" => "CALCIO", "quaternary" => "TENNIS");

$sport = $listSport[$nameClass];

$typeCard = $this->requestAction('users/readSportAthlete/' . $user['id'] . '/' . $sport); // questo valore lo troviamo nel controller (se l'atleta è tesserato allo sport della pagina da cui vuole accedere, mi restituisce lo sport stesso)

if ($typeCard[0] != "")
{
    $sport_options = $sport;
}
?>
<div class="tabs tabs-bottom tabs-center tabs-simple">
    <ul class="nav nav-tabs">
        <li class="<?
        if ($page == "utente")
        {
            echo "active";
        }
        ?>">
            <a data-toggle="" href="/gestione/profilo/<?= $user['id']; ?>/Athlete" aria-expanded="true">
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
        <? if ($sport == "CALCIO" && $sport_options == "CALCIO"): //GIUSEPPE 2017-02-21 .... i menu dell'atleta devono variare in base al calcio o al tennis  ?>
            <li class="<?
                if ($page == "vota")
                {
                    echo "active";
                }
                ?>">
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


            <li class="<?
                if ($page == "gestione_squadre")
                {
                    echo "active";
                }
                ?>">
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
    <?
elseif ($sport == "TENNIS" && $sport_options == "TENNIS"):
    ?>
            <li class="<?
                if ($page == "gestione_squadre")
                {
                    echo "active";
                }
                ?>">
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
            <li class="<?
                if ($page == "partite_tennis")
                {
                    echo "active";
                }
                ?>">
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
</div>