<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>



<?
//GIUSEPPE 2017-02-20 -> filtra la classe e il tipo di tesseramento
//$sport_options['view'] = true;

$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 

$nameClass = $classPage["Name"];

$listSport = array("primary" => "CALCIO", "secondary" => "CALCIO", "quaternary" => "TENNIS");

$sport = $listSport[$nameClass];
?>


<?
$data = $this->Session->read('Login.data');

if ($data['is_arbitro'])
    $type = 'Athlete';
elseif ($data['is_user'])
    $type = 'User';

$id = $data['id'];

$mesi = array(
    '01' => 'Gennaio',
    '02' => 'Febbraio',
    '03' => 'Marzo',
    '04' => 'Aprile',
    '05' => 'Maggio',
    '06' => 'Giugno',
    '07' => 'Luglio',
    '08' => 'Agosto',
    '09' => 'Settembre',
    '10' => 'Ottobre',
    '11' => 'Novembre',
    '12' => 'Dicembre',
);

$mesi_short = array(
    '01' => 'Gen',
    '02' => 'Feb',
    '03' => 'Mar',
    '04' => 'Apr',
    '05' => 'Mag',
    '06' => 'Giu',
    '07' => 'Lug',
    '08' => 'Ago',
    '09' => 'Set',
    '10' => 'Ott',
    '11' => 'Nov',
    '12' => 'Dic',
);

$options = array(
    0 => 'Nessun voto',
    1 => 'Gravemente insufficiente',
    2 => 'Insufficiente',
    3 => 'Appena sufficiente',
    4 => 'Sufficiente',
    5 => 'Discreto',
    6 => 'Buono',
    7 => 'Ottimo',
);

$end_days = array(
    '01' => '31',
    '02' => '29',
    '03' => '31',
    '04' => '30',
    '05' => '31',
    '06' => '30',
    '07' => '31',
    '08' => '31',
    '09' => '30',
    '10' => '31',
    '11' => '30',
    '12' => '31',
);
?>

<script type="text/javascript">

    $(function () {

        $(".table-matches").delegate('.vote', 'click', function () {

            var obj = $(this);
            var type = obj.attr('data-type');
            var athlete = obj.attr('data-id');
            var allow = obj.parents('tr').attr('vote-allow');
            var match = obj.parents('tr').attr('data-id');

            timmy_load('/lda_votes/vote_index/' + match + '/' + athlete);

        });

        $('.switch-giornata').bind('click', function () {

            location.hash = $(this).attr('data-giornata-id');

        });

    });

    $(document).ready(function () {

        var loc_hash = location.hash.replace('#', '');
        $('.switch-giornata[data-giornata-id="' + loc_hash + '"]').trigger('click');

        $(".switch-giornata").click(function () {

            $(".switch-giornata").removeClass('active');
            $(this).addClass('active');

            var giornata_id = $(this).attr('data-giornata-id');

            $(".table-matches").addClass('hidden');
            $(".table-matches[data-giornata-id=" + giornata_id + "]").removeClass('hidden');

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
                        <li class="active">Votazioni</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <div class="row">
            <div class="col-md-9">

                <h2>Votazioni</h2>


                <div class="table-container container-table-profile">

                    <? if (count($sfide_mensili)): ?>

                        <ul class="switch-table-menu pagination pagination-sm">

                            <?
                            end($sfide_mensili);
                            $first = key($sfide_mensili);
                            ?>

                            <? foreach ($sfide_mensili as $mese => $matches): ?>

                                <li class="switch-giornata <? if ($mese == $first): ?>active<? endif; ?>" data-giornata-id="<?= $mese; ?>"><a href="javascript:;" title="<?= $mesi[$mese]; ?>"><?= $mesi_short[$mese]; ?></a></li>

                            <? endforeach; ?>

                        </ul>	

                        <div class="clear"></div>

                        <div id="results-box">

                            <? foreach ($sfide_mensili as $k => $matches): ?>

                                <table class="table table-bordered table-striped table-condensed table-matches <? if ($k != $first): ?>hidden<? endif; ?>" data-giornata-id="<?= $k; ?>">	

                                    <thead>
                                    <th>Giorno</th>
                                    <th>Ora</th>
                                    <th>Impianto</th>
                                    <th>Partita</th>
                                    <th>Ris.</th>
                                    <th>Note</th>
                                    <th>Gara</th>
                                    <th>Arbitro</th>
                                    <th>Delegato</th>
                                    </thead>

                                    <? $j = 0; ?>

                                    <? foreach ($matches as $k => $match): ?>

                                        <?
                                        if (!empty($match['Match']['Risultato']))
                                        {
                                            $vote_allow = 1;
                                        }
                                        else
                                        {
                                            $vote_allow = 0;
                                        }
                                        ?>					

                                        <tr class="<?= (($j + 1) % 2 == 0) ? 'alternate' : ''; ?>" data-casa-squadra-id="<?= $match['Casa']['Squadra']; ?>" data-trasferta-squadra-id="<?= $match['Trasferta']['Squadra']; ?>" data-casa-id="<?= $match['Match']['Casa']; ?>" data-trasferta-id="<?= $match['Match']['Trasferta']; ?>" vote-allow="<?= $vote_allow; ?>" data-id="<?= $match['Match']['Calendario']; ?>">
                                            <td><span class="number"><?= $match['Match']['Data_it']; ?></span></td>
                                            <td><span class="number"><?= $match['Match']['Ora']; ?></span></td>
                                            <td>

                                                <? if ($match['Campi']['latitudine'] != '' && $match['Campi']['longitudine'] != '' && empty($match['Match']['Risultato'])): ?>
                                                    <script type="text/javascript">
                                                        $(function () {
                                                            $('.open_maps').unbind('click').bind('click', function () {
                                                                $.post('/campis/saveMapsSession', {
                                                                    'Nome': '<?= $match['Campi']['Descrizione']; ?>',
                                                                    'latitudine': '<?= $match['Campi']['latitudine']; ?>',
                                                                    'longitudine': '<?= $match['Campi']['longitudine']; ?>',
                                                                    'indirizzo': '<?= $match['Campi']['Indirizzo']; ?>',
                                                                    'citta': '<?= $match['Campi']['Citta']; ?>',
                                                                    'provincia': '<?= $match['Campi']['Provincia']; ?>',
                                                                    'telefono': '<?= $match['Campi']['Telefono']; ?>',
                                                                    'email': '<?= $match['Campi']['Email']; ?>',
                                                                }, function () {

                                                                    var uniqid = Math.random();

                                                                    timmy_load('/campis/maps?midland=' + uniqid);

                                                                });
                                                            });

                                                        });
                                                    </script>				
                                                    <a class="open_maps" href="javascript:;" rel="timmytip" title="<?= $match['Campi']['Descrizione']; ?>">
                                                        <?= $match['Campi']['Descrizione']; ?>
                                                    </a>
                                                <? else: ?>
                                                    <?= $match['Campi']['Descrizione']; ?>
                                                <? endif; ?>
                                            </td>
                                            <td><?= $match['Match']['CasaNome']; ?> - <?= $match['Match']['TrasfertaNome']; ?></td>
                                            <td><span class="number"><?= $match['Match']['Risultato']; ?></span></td>
                                            <td><?= $match['Causalresult']['Descrizione']; ?></td>
                                            <td><?= $match['Match']['NomeGara']; ?></td>

                                            <?
                                            $giaVotato = $this->requestAction('/lda_votes/giaVotato/' . $this->Session->read('Login.data.id') . '/' . $match['Lda']['Arbitro'] . '/' . $match['Match']['Calendario']);

                                            if (is_array($giaVotato) && count($giaVotato))
                                                $title = 'Voto: ' . $options[$giaVotato['LdaVote']['ranking']];
                                            else
                                                $title = 'Arbitro';
                                            ?>
                                            <td>
                                                <? if ($vote_allow && $match['Lda']['Arbitro'] != $match['Lda']['Delegato']): ?>

                                                    <? if (!$giaVotato): ?>

                                                        <? if ($match['Match']['NomeArbitro'] != ''): ?>

                                                            Arbitro
                                                            (<a class="not-rate vote" href="javascript:;" data-type="arbitro" data-id="<?= $match['Lda']['Arbitro']; ?>" title="<?= $title; ?>">vota</a>)
                                                        <? else: ?>

                                                            &nbsp;

                                                        <? endif; ?>

                                                    <? else: ?>

                                                        <span class="rated" title="<?= $title; ?>" rel="timmytip">Arbitro</span>

                                                    <? endif; ?> 

                                                    </a>
                                                <? else: ?>

                                                    Arbitro

                                                <? endif; ?>
                                            </td>
                                            <?
                                            if (!empty($match['Lda']['DelegatoA']))
                                            {
                                                $match['Match']['NomeDelegato'] = $match['Match']['NomeDelegatoA'];
                                                $match['Lda']['Delegato'] = $match['Lda']['DelegatoA'];
                                            }

                                            $giaVotato = $this->requestAction('/lda_votes/giaVotato/' . $this->Session->read('Login.data.id') . '/' . $match['Lda']['Delegato'] . '/' . $match['Match']['Calendario']);
                                            if (is_array($giaVotato) && count($giaVotato))
                                                $title = 'Voto: ' . $options[$giaVotato['LdaVote']['ranking']];
                                            else
                                                $title = 'Delegato';
                                            ?>	
                                            <td>

                                                <? if ($vote_allow && $match['Lda']['Arbitro'] != $match['Lda']['Delegato']): ?>

                                                    <? if (!$giaVotato): ?>

                                                        <? if ($match['Match']['NomeDelegato'] != ''): ?>

                                                            Delegato

                                                            (<a class="not-rate vote" href="javascript:;" data-type="delegato" data-id="<?= $match['Lda']['Delegato']; ?>" title="<?= $title; ?>">vota</a>)

                                                        <? else: ?>

                                                            &nbsp;

                                                        <? endif; ?>

                                                    <? else: ?>

                                                        <span class="rated" title="<?= $title; ?>" rel="timmytip">Delegato</span>

                                                    <? endif; ?>

                                                <? else: ?>
                                                    Delegato
                                                <? endif; ?>																		
                                            </td>									

                                        </tr>

                                        <? $j++; ?>

                                    <? endforeach; ?>

                                </table>

                            <? endforeach; ?>	

                        </div>	

                    <? else: ?>
                        <div class="alert alert-danger">
                            Nessuna gara arbitrata nella stagione corrente.
                        </div>
                    <? endif; ?>	


                </div>
            </div>

            <div class="col-md-3">
                <aside class="sidebar">
                    <h4 class="heading-primary">Gestione account</h4>
                    <ul class="nav nav-list narrow">
                        <li><a href="/gestione/profilo/<?= $this->Session->read('Login.data.id'); ?>/Athlete" title="Informazioni personali">Informazioni personali</a></li>
                        <li class="active"><a href="/gestione/vota/<?= $sport ?>" title="Votazioni">Votazioni</a></li>
                        <li><a href="/gestione/squadre" title="Gestione squadre">Gestione squadre</a></li>
                        <? if ($sport == "TENNIS"): ?>
                            <li><a href="/gestione/tennis_points" title="Gestione squadre">Partite tennis</a></li
                        <? endif; ?>

                    </ul>
                </aside>
            </div>
        </div>
    </div>