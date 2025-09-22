<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/ckeditor/adapters/jquery.js"></script>

<? $html->script('/js/script_for_match.js', false); ?>
<?
print $backend->formIndex('Match',
                array(
                    
                    'Manifestazione' =>
                    array(
                        'field' => 'Campionati.Nome',
                        'order' => true
                    ),
                    
                    'Girone' =>
                    array(
                        'field' => 'Half.Descrizione',
            //          'afterRender' => 'truncateFieldz',
                        'order' => true
                    ),
                    
                    'G.ta' =>
                    array(
                        'field' => 'Match.Giornata',
                        'order' => true
                    ),
                    
                    'GG' =>
                    array(
                        'field' => 'Match.Data3',
                        'afterRender' => 'getDay',
                        'order' => true
                    ),
                    
                    'Data' =>
                    array(
                        'field' => 'Match.Data',
                        'order' => true,
                        'afterRender' => 'make_date',
                        'afterSearch' => 'invert_date'
                    ),
                    
                    'Data finale' =>
                    array(
                        'field' => 'Match.Data2',
                        'order' => true,
                        'afterRender' => 'make_date',
                        'afterSearch' => 'invert_date'
                    ),
                    
                    'H' =>
                    array(
                        'field' => 'Match.Ora',
                        'order' => true
                    ),
                    
                    'Sq. CASA' =>
                    array(
                        'field' => 'Match.CasaNome',
                        //'afterRender' => 'truncateFieldz',
                        'order' => true
                    ),
                    'Sq. TRAS.' =>
                    array(
                        'field' => 'Match.TrasfertaNome',
                        //'afterRender' => 'truncateFieldz',
                        'order' => true
                    ),
                    
                    'Campo' =>
                    array(
                        'field' => 'Campi.Descrizione',
                        'order' => true
                    ),
                    
                    'Goal C.' =>
                    array(
                        'field' => 'Match.Risultato',
                        'afterRender' => 'getGoalCasa',
                    ),
                    
                    'Goal T.' =>
                    array(
                        'field' => 'Match.Risultato',
                        'afterRender' => 'getGoalTrasferta',
                    ),
                    
                    'Causale' =>
                    array(
                        'field' => 'Causalresult.Descrizione',
                        'afterRender' => 'truncateFieldz',
                        'order' => true
                    ),
                    
                    'Nome G.' =>
                    array(
                        'field' => 'Match.NomeGara',
                        'afterRender' => 'truncateFieldz',
                        'order' => true
                    ),
                    
                    'Nome Arbitro' =>
                    array(
                        'field' => 'Match.NomeArbitro',
                        'order' => true
                    ),

                    'Arbitro Singolo' =>
                    array(
                        'field' => 'Match.Calendario',
                        'afterRender' => 'countArbitro2New',
                    ),

                    'DEL' =>
                    array(
                        'field' => 'Match.NomeDelegato',
                        'afterRender' => 'truncateFieldz',
                        'order' => true
                    ),

                    'DEL/ARB' =>
                    array(
                        'field' => 'Match.NomeDelegatoA',
                        'afterRender' => 'truncateFieldz',
                        'order' => true
                    ),

                    'SPORT' =>
                    array(
                        'field' => 'Campionati.sport',
                        'order' => true
                    ),

                    'PlayLeague' =>
                    array(
                        'field' => 'Campionati.Playleague',
                        'afterRender' => 'truncateFieldz',
                        'order' => true,
                    ),

                    'ARB' =>
                    /* array(
                      'field' => 'Match.CountArbitro',
                      'order' => false,
                      'afterRender' => 'checkArbitro'
                      ), */
                    array(
                        'field' => 'Match.Calendario',
                        'afterRender' => 'countArbitroNew',
                    ),

                    'N.GARA' =>
                    array(
                        'field' => 'Match.Partita',
                        'order' => true,
                    ),

                    'ARBITRO2' =>
                    /* array(
                      'field' => 'Match.CountArbitro',
                      'order' => false,
                      'afterRender' => 'checkArbitro'
                      ), */
                    array(
                        'field' => 'Match.NomeArbitro2',
                    ),
                                        
                    'ARBITRO' =>
                    /* array(
                      'field' => 'Match.CountArbitro',
                      'order' => false,
                      'afterRender' => 'checkArbitro'
                      ), */
                    array(
                        'field' => 'Match.NomeArbitro',
                    ),
                )
                , array(
            'defaultOrder' => 'Match.Data',
            'defaultDir' => 'DESC',
            'conditions' => array('Campionati.InUso' => 'Si'),
            'pageTitle' => 'Gestione campionati',
            'quickSearch' => array('Match.Data', 'Half.Descrizione', 'Campionati.Nome', 'Match.CasaNome', 'Match.TrasfertaNome', 'Match.NomeGara', 'Campi.Descrizione'),
            'besideQuickSearch' => '
            <ul>
                ' . ((isAllowed('Matches', 'admin_refresh')) ? '<li><a href="javascript:;" title="Generazione calendario" rel="timmytip" id="refresh_champ"><img src="/img/timmyshare/icon_calendar.png" width="20" height="20" alt="" /></a></li>' : '') . '
                ' . ((isAllowed('Prints', 'admin_index')) ? '<li><a href="javascript:;" title="Stampe" rel="timmytip" id="print_bullettins"><img src="/img/timmyshare/icon_print.png" width="20" height="20" alt="" /></a></li>' : '') . '
                ' . ((isAllowed('Squadres', 'admin_almanacco_index')) ? '<li><a href="javascript:;" title="Stampa almanacco" rel="timmytip" id="print_almanacco"><img src="/img/icon_almanacco.png" width="20" height="20" alt="" /></a></li>' : '') . '
                ' . ((isAllowed('Matches', 'sendLdaIndex')) ? '<li><a href="javascript:;" title="Comunicazioni designatori" rel="timmytip" id="sendLdaComunication"><img src="/img/icon_lda_comunication.png" width="20" height="20" alt="" /></a></li>' : '') . '          
                ' . ((isAllowed('Athletes', 'admin_sendMailSms')) ? '<li><a href="javascript:;" title="Invia E-Mail o SMS" rel="timmytip" id="sendMailSms"><img src="/img/icon_mail_sms.png" width="20" height="20" alt="" /></a></li>' : '') . '
                ' . ((isAllowed('Athletes', 'admin_createList')) ? '<li><a href="javascript:;" title="Aggiungi contatti" data-index="matches" rel="timmytip" id="add_contacts"><img src="/img/icon_mail_addcontact.png" width="20" height="20" alt="" /></a></li>' : '') . '
                ' . '<li><button id="createPdf">PDF</button><div id="attendi_msg" style="display:none">Attendi...</div></li>' . '
            </ul>'
            ,
            'conditions' => $conditions
        ));
?>
<!--//GIUSEPPE 2022-09-13-->
<script>
    $(() => {
        //

        filtraPlayLeague();

        $("#createPdf").click(() => {
//            alert("test");
            var to_send = {};
            to_send['pdf_matches_list'] = {};

            var index = 0;
            $(".index-row").each(function () {
                row = $(this);
//                console.log(row.html());
                id_row = row.attr('id');
//                to_send[id_row] = {};
                to_send['pdf_matches_list'][index] = {};

                $(`#${id_row} td`).each(function () {
                    col_class = $(this).attr('class').trim();
                    col = $(this).html();
                    col_this = $(this);
                    //console.log(col_this);
                    switch (col_class)
                    {
                        case "td_manifestazione":
                        case "td_girone":
                        case "td_gg":
                        case "td_data":
                        case "td_h":
                        case "td_nomearbitro":
                        case "td_campo":
                            to_send['pdf_matches_list'][index][col_class] = col;
                            break;

                        case "td_sq_casa":
                        case "td_sq_tras":
//                            var squadra = col_this.context.children[0].title == "" ? col_this.context.children[0].textContent : col_this.context.children[0].title;
                            var squadra = col_this.context.textContent;
                            to_send['pdf_matches_list'][index][col_class] = squadra;
                            break;
                    }
                });

                index++;

            });

//            console.log(to_send);
            $("#createPdf").toggle();
            $("#attendi_msg").toggle();
            $.post("/admin/matches/pdfMatchesList", to_send, function (data) {
//                console.log(data);
                $("#createPdf").toggle();
                $("#attendi_msg").toggle();
                window.open("/admin/matches/pdfMatchesList", '_blank');
            });
        });


        function filtraPlayLeague()
        {


            var not_playleague = [];

            $(".index-row").each(function ()
            {
                var id = $(this).attr('id');

                var g = $(this);

                var value = g[0].children[4].innerText;

                if (value == "0")
                {
                    not_playleague.push(id);
                }
                if (value == "1")
                {

                }

            });

            $("tr .td_playleague").each(function ()
            {
                var value = $(this).text();

//                console.log(value);

                if (value == "0")
                {
                    $(this).text("");
                }
                if (value == "1")
                {
                    $(this).text("PlayLeague");
                }
//     
            });

            var group_id = '<?= $grp = $this->Session->read('User.group_id'); ?>';

            if (group_id == 3)
            {
                for (i in not_playleague)
                {
                    $("#" + not_playleague[i]).hide();
                }
            }

        }

    });
</script>
<!-- ------------------- -->