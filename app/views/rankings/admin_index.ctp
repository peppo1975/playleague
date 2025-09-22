<? $html->script('/js/script_layout.js', false); ?>
<? $html->script('/js/script_for_ranking.js', false); ?>
<?
print $backend->formIndex('Ranking',
                array(
                    'Squadra' =>
                    array(
                        'field' => 'Ranking.NomeSquadra',
                        'order' => true,
                    ),
                    'Campionato' =>
                    array(
                        'field' => 'SquadreCampionati.Campionato',
                    ),
                    'Girone' =>
                    array(
                        'field' => 'Half.Descrizione',
                        'order' => true,
                    ),
                    'PT' =>
                    array(
                        'field' => 'Ranking.Punti',
                        'order' => true
                    ),
                    'G' =>
                    array(
                        'field' => 'Ranking.Giocate',
                        'order' => true
                    ),
                    'V' =>
                    array(
                        'field' => 'Ranking.Vinte',
                        'order' => true
                    ),
                    'P' =>
                    array(
                        'field' => 'Ranking.Perse',
                        'order' => true
                    ),
                    'N' =>
                    array(
                        'field' => 'Ranking.Nulle',
                        'order' => true,
                    ),
                    'GC' =>
                    array(
                        'field' => 'Ranking.GiocateCasa',
                        'order' => true,
                    ),
                    'VC' =>
                    array(
                        'field' => 'Ranking.VinteCasa',
                        'order' => true
                    ),
                    'PC' =>
                    array(
                        'field' => 'Ranking.PerseCasa',
                        'order' => true,
                    ),
                    'NC' =>
                    array(
                        'field' => 'Ranking.NulleCasa',
                        'order' => true,
                    ),
                    'GF' =>
                    array(
                        'field' => 'Ranking.GiocateFuori',
                        'order' => true
                    ),
                    'VF' =>
                    array(
                        'field' => 'Ranking.VinteFuori',
                        'order' => true
                    ),
                    'NF' =>
                    array(
                        'field' => 'Ranking.NulleFuori',
                        'order' => true,
                    ),
                    'GFt.' =>
                    array(
                        'field' => 'Ranking.GoalFatti',
                        'order' => true
                    ),
                    'GS' =>
                    array(
                        'field' => 'Ranking.GoalSubiti',
                        'order' => true
                    ),
                    'GSC' =>
                    array(
                        'field' => 'Ranking.GoalSubitiCasa',
                        'order' => true,
                    ),
                    'GSF' =>
                    array(
                        'field' => 'Ranking.GoalSubitiFuori',
                        'order' => true
                    ),
                    'GFC' =>
                    array(
                        'field' => 'Ranking.GoalFattiCasa',
                        'order' => true
                    ),
                    'GFF' =>
                    array(
                        'field' => 'Ranking.GoalFattiFuori',
                        'order' => true
                    ),
                    'PT Pen.' =>
                    array(
                        'field' => 'Ranking.PuntiPenalizzazione',
                        'afterRender' => 'getPunti',
                        'order' => true
                    ),
                    'Coppa Disciplina' =>
                    array(
                        'field' => 'Ranking.CoppaDisciplina',
                        'order' => true
                    ),
                )
                , array(
            'defaultOrder' => 'Ranking.Punti',
            'defaultDir' => 'DESC',
            'pageTitle' => 'Gestione classifiche',
            'conditions' => array('1=0'),
            'besideQuickSearch' => '
		
			<ul>
				
				<li><a href="javascript:;" title="Aggiorna classifica" rel="timmytip" id="refresh_ranking"><img src="/img/icon_update_classifiche.png" alt="" /></a></li>
				<li><a href="javascript:;" title="Stampa classifica" rel="timmytip" id="print_rank"><img src="/img/icon_print_classifiche.png" alt="" /></a></li>
				<li><a href="javascript:;" title="Classifica marcatori" rel="timmytip" id="mark_ranking"><img src="/img/icon_classifica_marcatori.png" alt="" /></a></li>
				<li><a href="javascript:;" title="Disciplinari" rel="timmytip" id="discipline_ranking"><img src="/img/icon_disciplinari.png" alt="" /></a></li>
							
			</ul>
		
		'
));
?>
<script>

    filtraPlayLeague();

    function filtraPlayLeague()
    {
        console.log("TEST");

        var not_playleague = [];

        $(".index-row").each(function ()
        {
            var id = $(this).attr('id');

            var g = $(this);

            //var value = g[0].children[4].innerText;
            var giornate = parseInt(g[0].children[5].innerText);
            var coppa_disc = parseInt(g[0].children[23].innerText);

            var coppaDiscNew = Math.round((coppa_disc / giornate) * 100);
            
            g[0].children[23].innerText = coppaDiscNew;
        });
    }

</script>