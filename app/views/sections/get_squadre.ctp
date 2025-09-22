<!-- //GIUSEPPE 2024-07-06 -->

<style>
    /*loader*/
    /*// https://loading.io/css/*/
    .lds-hourglass,
    .lds-hourglass:after {
        box-sizing: border-box;
    }
    .lds-hourglass {
        display: inline-block;
        position: relative;
        width: 80px;
        height: 80px;
    }
    .lds-hourglass:after {
        content: " ";
        display: block;
        border-radius: 50%;
        width: 0;
        height: 0;
        margin: 8px;
        box-sizing: border-box;
        border: 32px solid currentColor;
        border-color: currentColor transparent currentColor transparent;
        animation: lds-hourglass 1.2s infinite;
    }
    @keyframes lds-hourglass {
        0% {
            transform: rotate(0);
            animation-timing-function: cubic-bezier(0.55, 0.055, 0.675, 0.19);
        }
        50% {
            transform: rotate(900deg);
            animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        }
        100% {
            transform: rotate(1800deg);
        }
    }
</style>




<link rel="stylesheet" href="/porto_admin/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css">
<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/layout.js"></script>


<div role="main" class="main">

    <div style="background: #f5f5f5; margin-bottom: 20px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumb" style="margin-bottom: 0">
                        <li><a href="/">Home</a></li>
                        <li class="">Squadre</li>

                        <li class="active">

                            <? $tipo = $tipoSportSesso['tipo']; ?>
                            <? $sesso = $tipoSportSesso['sesso']; ?>
                            <? // if ($tipo == 0): ?>

                            Calcio a <?= $tipo == 0 ? "5" : "7" ?> <?= $sesso == 0 ? "maschile" : "femminile" ?>

                            <? // elseif ($tipo == 1): ?>


                            <? // endif; ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>



    <div class="container" id="main-custom">
        <div class="row">
            <div class="col-md-12">
                <br />
                <div class="contents-box teams-view" id="bg-retino">

                    <h2>Squadre calcio a <?= $tipo == 0 ? "5" : "7" ?> </h2>
                    <p class="lead"><span><?= $sesso == 0 ? "Maschile" : "Femminile" ?></span></p>
                    <hr />

                    <blockquote class="with-borders fields-filter-container">
                        <div class="row">
                            <div class="form-groups">


                                <div class="col-md-6 type-alfabeto">
                                    <div class="row">
                                        <label class="control-label col-md-12">Iniziale squadra</label>
                                    </div>
                                    <!--<select data-plugin-selectTwo class="form-control populate" data-filter="alfabeto" name="alfabeto">-->
                                    <select class="form-control populate" id="elencoIniziale" data-filter="alfabeto" name="alfabeto" style="padding: 8px; height: 40px;">
                                        <option value="" data-index="  data-lettera="" >
                                            <?= "" ?>
                                        </option>
                                        <? foreach ($alfabeto['elenco'] as $i => $lettera): ?>

                                            <option value="<?= $lettera; ?>" data-index="<?= $i; ?>"  data-lettera="<?= $lettera; ?>" <?= $lettera == $alfabeto['first'] ? "selected" : "" ?>>
                                                <?= $lettera; ?>
                                            </option>

                                        <? endforeach; ?>
                                    </select>
                                </div>


                                <div class="col-md-6 type-stagione">
                                    <div class="row">
                                        <label class="control-label col-md-12">Ricerca libera</label>
                                    </div>
                                    <input type="text" 
                                           id="ricercaLibera"
                                           class="form-control" 
                                           style="height: 40px;" 
                                           name="search-text" 
                                           placeholder="Ricerca squadra per nome..." 
                                           autocomplete="off"/>
                                    <!--<input type="text" class="form-control autoComplete" style="height: 40px;" name="search-text" placeholder="Ricerca squadra per nome..." autocomplete="off"  data-url="/sections/getSquadreAjax/<?= $tipo; ?>/<?= $sesso; ?>" data-dest="id_squadra" />-->
                                    <!--<input type="hidden" id="id_squadra" />--> 
                                </div>


                            </div>
                        </div>
                    </blockquote>












                    <div class="filter-box">

                        <!-- filtro in base agli anni -->

                        <div class="input autocomplete-input">

                        </div>

                        <div class="clear"></div>
                    </div><!-- close filter-box -->
                    <div class="clear"></div>
                    <div class="ui-tabs-container ui-tab-passrecovery">


                        <div class="clear"></div>

                        <div class="ui-tabs">
                            <div class="col-lg" style="text-align: center" id="loadListaSquadre">
                                <div class="lds-hourglass"></div>
                            </div>
                            <div class="row" id="listaSquadre">


                            </div>
                        </div>

                    </div>

                </div>


            </div>
        </div>
    </div>
</div>
<style>
    .scudo {
        line-height: 63px;
    }
    .backgroungImage {
        background-image:  63px;
    }

</style>
<script>
    class Squadre {

        final;
        ordineLettura;
        loadListaSquadre;
        sesso;
        sessoTipo;
        constructor(final, ordineLettura) {
            this.final = final;
            this.ordineLettura = ordineLettura;
            this.loadListaSquadre = document.getElementById("loadListaSquadre")

            this.sesso = '<?= $tipoSportSesso['sesso'] ?>';
            this.sessoTipo = '<?= $tipoSportSesso['tipo'] ?>';

            this.costruisciStruttura();
        }

        costruisciStruttura()
        {
            var listaSquadre = document.getElementById("listaSquadre");
            var elencoFiltro = document.getElementById("elencoIniziale");
            var ricercaLibera = document.getElementById("ricercaLibera");
            var filtro = elencoFiltro.value;
            var ricerca = ricercaLibera.value;
            var nomeSquadra = "";

            listaSquadre.innerHTML = "";
            this.loadListaSquadre.style.display = null; //loading

            var filtroRicerca = [];
            var firstLetter = "";



//            Object.keys(this.final).forEach((i) => {
            Object.keys(this.ordineLettura).forEach((index) => {

                var i = ordineLettura[index];
                nomeSquadra = this.final[i].NomeSquadra.trim();

                if (filtro == "0 - 9")
                {
                    filtroRicerca = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
                    firstLetter = nomeSquadra[0];
                } else
                {

                    firstLetter = nomeSquadra[0].toUpperCase();

                    if ((filtro == " ") || (filtro == "")) // quando seleziono la parte vuota della select
                    {
                        filtro == "".toString();
                        firstLetter = "".toString();
                    }

                    filtroRicerca = [filtro];
                }



//                if (this.final[i].NomeSquadra[0].toUpperCase() != filtro)
                if (!filtroRicerca.includes(firstLetter))
                {

                } else
                {

                    if (ricerca !== "")
                    {
                        var lenRicerca = ricerca.length;
                        var ricercaTestuale = nomeSquadra.substring(0, lenRicerca).toLowerCase();

                        if (ricerca.toLowerCase() !== ricercaTestuale)
                        {
                            return;
                        }
                    }

                    var div = document.createElement('div');
                    div.classList.add("col-md-4");
                    div.classList.add("squadra");

                    var blockquote = document.createElement('blockquote');
                    blockquote.classList.add("with-borders");
                    blockquote.classList.add("squadra-block");

                    var row = document.createElement('div');
                    row.classList.add('row');

                    var col_1 = document.createElement('div');
                    col_1.classList.add("col-md-4");
                    col_1.classList.add("col-xs-4");
                    col_1.style.height = '70px';
                    var imgLogo = document.createElement('div');
                    //img-thumbnail text-center
                    imgLogo.classList.add("img-thumbnail");
                    imgLogo.classList.add("text-center");
                    imgLogo.style.width = '70px';
                    imgLogo.style.height = '70px';



                    if (this.final[i].Logo == "")
                    {
                        var imLogoi = document.createElement('i');
                        imLogoi.classList.add("fa");
                        imLogoi.classList.add("fa-3x");
                        imLogoi.classList.add("fa-shield");
                        imLogoi.classList.add('scudo');
                        imgLogo.appendChild(imLogoi);
                    } else
                    {

                        var imLogoPgn = document.createElement('div');
                        var imLogoPgn = '<div style="background-image:url(' + this.final[i].Logo + '); background-size: contain; background-repeat: no-repeat; background-position: center center; width: 60px; height: 60px;"/>'
                        imgLogo.innerHTML = imLogoPgn;
                    }


                    col_1.appendChild(imgLogo);

                    var col_2 = document.createElement('div');
                    col_2.classList.add("col-md-8");
                    col_2.classList.add("col-xs-8");

                    var name = document.createElement('a');
                    name.innerHTML = this.final[i].NomeSquadra;
                    name.setAttribute("href", "/squadra/dettaglio/" + i + "/?option=" + this.sessoTipo + "-" + this.sesso);
                    name.setAttribute("target", "_blank");
                    col_2.appendChild(name);

                    var br = document.createElement('br');
                    col_2.appendChild(br);

                    var label = document.createElement('label');
                    label.classList.add("label");
                    label.classList.add("label-info");
                    label.innerHTML = this.final[i].Stagioni + " stagioni / " + this.final[i].Manifestazioni + " campionati";

                    col_2.appendChild(label);

                    row.appendChild(col_1);
                    row.appendChild(col_2);

                    blockquote.append(row);

                    div.appendChild(blockquote);

                    listaSquadre.appendChild(div);
                }
            });

            this.loadListaSquadre.style.display = 'none';
        }
    }
</script>
<script>
    var final = <?= json_encode($final) ?>;
    var ordineLettura = <?= json_encode($ordineLettura) ?>;
    var squadre;

    window.addEventListener("load", (event) => {
        console.log("qui");
        squadre = new Squadre(final, ordineLettura);
    });

    elencoIniziale = document.getElementById('elencoIniziale');
    elencoIniziale.addEventListener('change', () => {
//        squadre = new Squadre(final, ordineLettura);
        squadre.costruisciStruttura();
    });

    ricercaLibera = document.getElementById("ricercaLibera");
    ricercaLibera.addEventListener('keyup', () => {
        squadre.costruisciStruttura();
    });

</script>