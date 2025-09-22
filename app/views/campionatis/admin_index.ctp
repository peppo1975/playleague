<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/ckeditor/adapters/jquery.js"></script>

<script type="text/javascript">

<? if (!isset($_SESSION['campionatis_desc'])): ?>

    <? $_SESSION['campionatis_desc'] = 1; ?>

        location.href = '/admin/campionatis/index/order/Campionati.created/desc';



<? endif; ?>


</script>
<?
$html->script('/js/script_for_campionati.js', false);

print $backend->formIndex('Campionati', array(
            'Nome' =>
            array(
                'field' => 'Campionati.Nome',
                'order' => true,
            ),
            'ID' =>
            array(
                'field' => 'Campionati.Campionato',
                'order' => true,
            ),
            'SPORT' =>
            array(
                'field' => 'Campionati.sport',
                'order' => true,
            ),
//             'STAMPA' =>
//             array(
//             'field' => '',
//             'order' => true,
//             ),
//             GIUSEPPE 2022-09-13 
//             'PlayLeague' =>
//             array(
//             'field' => 'Campionati.PlayLeague',
//             'order' => true,
//              ),
            // -----------------
            'Data creazione' =>
            array(
                'field' => 'Campionati.created',
                'order' => true,
                'afterRender' => 'make_date',
                'afterSearch' => 'invert_date'
            ),
            'Anno' =>
            array(
                'field' => 'Campionati.AnnoSportivo_v',
                'order' => true,
            ),
            'In corso' =>
            array(
                'field' => 'Campionati.InCorso',
                'order' => true,
            ),
            'In uso' =>
            array(
                'field' => 'Campionati.InUso',
                'order' => true,
            ),
            'Italiana' =>
            array(
                'field' => 'Campionati.Italiana',
                'order' => false,
            ),
            'Campionato precedente' =>
            array(
                'field' => 'Campionati.NomeCampionatoPrecedente',
                'afterRender' => 'truncateFields',
                'order' => true,
            ),
            'Tariffa arbitro' =>
            array(
                'field' => 'Campionati.TariffaArbitro',
                'order' => true,
            ),
            'Tariffa Arbitro Singolo' =>
            array(
                'field' => 'Campionati.TariffaArbitro2',
                'order' => true,
            ),
            'Tariffa Delegato' =>
            array(
                'field' => 'Campionati.TariffaDelegato',
                'order' => true,
            ),
            'Tariffa Delegato Singolo' =>
            array(
                'field' => 'Campionati.TariffaDelegatoA',
                'order' => true,
            ),
            'ORDER' =>
            array(
                'field' => 'Campionati.order',
                'order' => true,
            ),
                )
                , array(
            'defaultOrder' => 'Campionati.order',
            'defaultDir' => 'ASC',
            'pageTitle' => 'Tabella campionati',
            'order_option' => 1,
            'buttons' => array(
                'Campi supplementari' => array('class' => 'edit', 'img' => '/img/timmyshare/icon_place.png', 'action' => 'edit', 'selected' => 2),
                'Gironi' => array('class' => 'edit', 'img' => '/img/timmyshare/icon_map.png', 'action' => 'edit', 'selected' => 3),
                'Cancella calendario' => array('class' => 'delete-match', 'img' => '/img/icon_delete_match.png')
            ),
            'besideQuickSearch' => '
            <ul>
                <li><a href="javascript:;" title="Invia E-Mail o SMS" rel="timmytip" id="sendMailSms"><img src="/img/icon_mail_sms.png" width="16" height="16" alt="" /></a></li>
                <li><a href="javascript:;" title="Aggiungi contatti" data-index="campionatis" rel="timmytip" id="add_contacts"><img src="/img/icon_mail_addcontact.png" width="16" height="16" alt="" /></a></li>
            </ul>
        ',
            'quickSearch' => array('Campionati.Nome')
        ));
?>
<!--//GIUSEPPE 2022-09-13--> 
<script>
    
    
     var myEnum = {
        Nome: 1,
        ID: 2,
        SPORT: 3,
        STAMPA :4,
        PlayLeague:5
    };
    
    $(() => {
        //alert("terst");
        $(".index-row").each(function ()
        {
            let sport = $(this)[0].cells[myEnum.SPORT].innerText;
            if (sport !== "CALCIO")
            {
                $(this)[0].cells[myEnum.STAMPA].innerText = "";
            }
            else
            {
                if ($(this)[0].cells[myEnum.PlayLeague].innerText == '1')
                {
                    $(this)[0].cells[myEnum.PlayLeague].innerText = 'PlayLeague';
                }
                if ($(this)[0].cells[myEnum.PlayLeague].innerText == '0')
                {
                    $(this)[0].cells[myEnum.PlayLeague].innerText = "";
                }
            }
            console.log($(this)[0].cells);
        })
    })
</script>


<!--//GIUSEPPE 2023-01-03-->
<script>

    elements = document.getElementsByClassName("td_stampa");

    $(() => {

        for (var i = 0; i < elements.length; i++)
        {
            elements[i].addEventListener('click', InfoSquadra, false);
        }


        $(".index-row").each(function ()
        {
            //$(this).html(`<button>pdf</button>`); 

            id = $(this).attr('id');
            sport = $(this)[0].children[myEnum.SPORT].innerText;

            playleague = $(this)[0].children[myEnum.PlayLeague].innerText;

            if (sport == "CALCIO" && playleague == "PlayLeague")
            {
                // $(this)[0].children[myEnum.STAMPA].innerHTML = `<button class="td_stampa_button">Affiliazione BAS</button>`;
                // // $(this)[0].children[myEnum.STAMPA].idCampionato = id;
                // $(this)[0].children[myEnum.STAMPA].tipoSport = sport;
                // console.log(id);
                // console.log($(this)[0]);
                // console.log($(this));
                // console.log("----------------------------------");
            }

            if (playleague == "0")
            {
                $(this)[0].children[myEnum.PlayLeague].innerText = "";
            }


        });


        function InfoSquadra()
        {
            ///alert("tst");

            var idCampionato = $(this)[0].idCampionato;
            var tipoSport = $(this)[0].tipoSport;

            console.log($(this));

            var link = "/admin/campionatis/stampaAffiliazione/?Campionato=" + idCampionato;

            window.open(link, '_blank');

        }
    });
</script>
<!-- ------------------- -->


<!--// ------------------->