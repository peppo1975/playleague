<? $fpdf->setup('P', 'mm', 'a4'); ?>
<? $fpdf->SetMargins('10.2', '5.0'); ?>
<? $fpdf->AddFont('Calibri', '', 'calibri.php'); ?>
<? $fpdf->AddFont('CalibriB', '', 'calibrib.php'); ?>
<? $fpdf->SetFont('Helvetica', '', 8); ?>
<? //$fpdf->AddPage();      ?>
<? //$fpdf->Ln();      ?>
<? //file_get_contents("test.txt", print_r($campionati, true))      ?>
<? $squadre = array(); ?>

<? foreach ($campionati as $champ => $campionato): ?>
    <? if ($champ != "Global"): ?>
        <? $squadreCampionato = array(); ?>
        <? foreach ($campionato as $half => $girone): ?>
            <? $oldY = 0; ?>

            <? if ($half == "NomeCampionato"): ?>
                <? continue; ?>
            <? endif; ?>

            <? if ($half == "Italiana"): ?>
                <? continue; ?>
            <? endif; ?>

            <? $teams_to_day = array() ?>

            <? foreach ($girone as $day => $day_game): ?>

                <? if ($day == "NomeGirone"): ?>
                    <? continue; ?>
                <? endif; ?>

                <? $fpdf->AddPage(); //<!-- nuova di pagina -->            ?>

                <? $fpdf->Ln(); ?>

                <? ob_start(); //<!-- OGGETTO TABELLA --> ?>

                <table border="1" width="100%" >
                    <tr bgcolor="#ffff99">
                        <td align="center" style="bold" size="10"><b><?= $campionato['NomeCampionato'] ?> --- <?= $girone['NomeGirone'] ?></b></td>
                    </tr>
                </table>
                <? $table = ob_get_clean(); ?>
                <? $fpdf->htmltable($table); //<!-- FINE OGGETTO TABELLA -->?>
                <? $fpdf->Cell(189, 6, "RISULTATI " . $day . "° GIORNATA", 0, 1, 'C'); ?>
                <? $fpdf->setX(30); ?>
                <? ob_start(); ?>
                <? $array_disciplinari = array() ?>
                <? $array_squalificati = array() ?>
                <? $array_diffidati = array() ?>
                <? $array_espulsi = array() ?>
                <? $array_comunicazioni = array() ?>
                <?
                if ($day_game['Comunicazioni'] != "null")
                    $array_comunicazioni[] = $day_game['Comunicazioni'];
                ?>
                <? $all_team_champ_half = array(); ?>
                <? $all_team_champ_half = $campionati['Global']['AllTeamsChampHalf'][$champ][$half]; ?>
                <table border="1" width="80%" align="center">
                    <? foreach ($day_game['Date'] as $time => $day_detail_index): ?>




                        <? foreach ($day_detail_index as $day_detail): ?>
                            <? $keys_teams = array_keys($day_detail['SquadreCampionato']) ?>
                            <? unset($all_team_champ_half[$keys_teams[0]]); // = array_keys($day_detail['SquadreCampionato'])?>
                            <? unset($all_team_champ_half[$keys_teams[1]]); // = array_keys($day_detail['SquadreCampionato'])?>
                        <? endforeach; ?>

                        <? foreach ($day_detail_index as $day_detail): ?>

                            <? foreach ($day_detail['Disciplinari'] as $disciplinare): ?>
                                <? $array_disciplinari['Descrizione'][] = ($disciplinare['Descrizione']); ?>
                                <? $array_disciplinari['Sanzione'][] = ($disciplinare['Sanzione']); ?>
                                <? $array_disciplinari['Squadra'][] = $day_detail['SquadreCampionato'][$disciplinare['SquadraCampionato']]; ?>
                            <? endforeach; ?>






                            <? // -----------------------------------------------------------------------------  ?>

                            <?
                            $calendario = $day_detail['Calendario'];

                            $key_champ_team = array_keys($day_detail['SquadreCampionato']);

                            $squadreCampionato[] = $key_champ_team[0];
                            $squadreCampionato[] = $key_champ_team[1];
                            $squadre[$key_champ_team[0]] = $day_detail['SquadreCampionato'][$key_champ_team[0]];
                            $squadre[$key_champ_team[1]] = $day_detail['SquadreCampionato'][$key_champ_team[1]];


                            foreach ($key_champ_team as $key_team)
                            {

                                if (isset($campionati['Global']['AmmonizioniEspulsioni']['CalendarioAtleti']['AmmonitiTotali']['SquadraCampionato'][$key_team]))
                                {
                                    $ammonizioni_squadra = $campionati['Global']['AmmonizioniEspulsioni']['CalendarioAtleti']['AmmonitiTotali']['SquadraCampionato'][$key_team];

                                    foreach ($ammonizioni_squadra['Atleta'] as $key_atleta_ammonito => $single_ammonito)
                                    {

                                        $ammonito = $campionati['Global']['AmmonizioniEspulsioni']['Atleti'][$key_atleta_ammonito];
                                        //                     
                                        $ammonizioni = 0;

                                        foreach ($ammonito['Calendario'] as $key_calendar => $tot_amm)
                                        {

                                            if ((((int) $key_calendar) <= ((int) $calendario)) && isset($tot_amm['Ammonizioni']))
                                            {
                                                $ammonizioni = $tot_amm['Ammonizioni'];
                                            }
                                        }



                                        if ((((int) $ammonizioni) + 1) % 3 == 0) // 2 , 5 ,8 , 11, 14
                                        {
                                            $d['Name'] = $ammonito['Name'];
                                            $d['Team'] = $key_team;
                                            $d['Ammonizioni'] = $ammonizioni;
                                            $array_diffidati[] = $d;
                                        }


                                        $ammonizioni = $ammonito['TotaleAmmonizioni'];

                                        if ((((int) $ammonizioni)) % 3 == 0) // 3 , 6 ,9 , 12, 15
                                        {
                                            if (isset($ammonito['Calendario'][$calendario]))
                                            {

                                                $d['Name'] = $ammonito['Name'];
                                                $d['Team'] = $key_team;
                                                $d['Ammonizioni'] = $ammonizioni;
                                                $array_squalificati[] = $d;
                                                //$array_diffidati[] = $ammonito['Name'];
                                            }
                                        }
                                    }
                                }




                                if (isset($campionati['Global']['AmmonizioniEspulsioni']['CalendarioAtleti']['Espulsi']))
                                {
                                    $espulsi = $campionati['Global']['AmmonizioniEspulsioni']['CalendarioAtleti']['Espulsi'];

                                    $espulso = array();

                                    foreach ($espulsi as $calendario_espulsione => $info_espulso)
                                    {

                                        $id_espulso = 0;

                                        if ($calendario_espulsione == $calendario)
                                        {
                                            //$id_espulso = array_keys($info_espulso)[0];

                                            foreach ($info_espulso as $id_espulso)
                                            {
                                                //$id_espulso = array_keys($info_espulso)[$id_e];

                                                $id_squadra = $campionati['Global']['AmmonizioniEspulsioni']['Atleti'][$id_espulso]['SquadraCampionato'];

                                                $espulso['Nome'] = $campionati['Global']['AmmonizioniEspulsioni']['Atleti'][$id_espulso]['Name'];
                                                $espulso['Squadra'] = $squadre[$id_squadra]; //$campionati['Global']['AmmonizioniEspulsioni']['Atleti'][$id_espulso]['SquadraCampionato'];
                                                $espulso['Inizio'] = $campionati['Global']['AmmonizioniEspulsioni']['Atleti'][$id_espulso]['Calendario'][$calendario_espulsione]['Inizio'];
                                                $espulso['Fine'] = $campionati['Global']['AmmonizioniEspulsioni']['Atleti'][$id_espulso]['Calendario'][$calendario_espulsione]['Fine'];
                                                $espulso['Giornate'] = $campionati['Global']['AmmonizioniEspulsioni']['Atleti'][$id_espulso]['Calendario'][$calendario_espulsione]['Giornate'];

                                                //echo "<br>" . $espulso['Inizio'] . "<br>" . $espulso['Fine'] . "<br>" . $espulso['Giornate'];
                                                //print_r($espulso);

                                                $espulso['Periodo'] = "";

                                                if ($espulso['Fine'] == "0/0/0")
                                                {
                                                    $espulso['Fine'] = "";
                                                    $espulso['Inizio'] = "";

                                                    $espulso['Periodo'] = $espulso['Giornate'] . " gg.";
                                                }
                                                else
                                                {
                                                    $espulso['Giornate'] = "-";

                                                    $espulso['Periodo'] = $espulso['Inizio'] . " - " . $espulso['Fine'];
                                                }

                                                $array_espulsi[$id_espulso] = $espulso;
                                            }
                                        }
                                    }
                                }
                            }
                            ?>


                            <? // -----------------------------------------------------------------------------           ?>



                            <? $teams = explode(" vs ", $day_detail['Squadre']) ?>
                            <? if ($day_detail['CausaleRisultato'] == "null" || $day_detail['CausaleRisultato'] == ""): ?>
                                <? $day_detail['CausaleRisultato'] = ""; ?>
                            <? endif; ?>
                            <tr>
                                <td  width="26">
                                    <?= parse_date($time); ?>
                                </td>
                                <td nowrap>
                                    <?= $teams[0]; ?>
                                </td>
                                <td nowrap>
                                    <?= $teams[1]; ?>
                                </td>
                                <td  align="center" style="bold" width="12">
                                    <?= $day_detail['Punti']; ?>
                                </td>
                                <td  align="center" nowrap>
                                    <?= $day_detail['CausaleRisultato']; ?>
                                </td>
                            </tr>
                            <? $teams_to_day[] = $teams[0]; ?>
                            <? $teams_to_day[] = $teams[1]; ?>
                            <? //$keys_teams = array_keys($day_detail['SquadreCampionato']) ?>
                            <? //unset($all_team_champ_half[$keys_teams[0]]); // = array_keys($day_detail['SquadreCampionato']) ?>
                            <? //unset($all_team_champ_half[$keys_teams[1]]); // = array_keys($day_detail['SquadreCampionato']) ?>



                        <? endforeach; ?>

                    <? endforeach; ?>

                    <? if ($campionato['Italiana'] == "No"): ?>

                        <? if (count($all_team_champ_half)): ?>

                            <? $sleep = array(); ?>

                            <? foreach ($all_team_champ_half as $team_single): ?>
                                <? $sleep[] = $team_single['Denominazione'] ?>
                            <? endforeach; ?>

                            <tr>
                                <td  width="26">
                                    Riposa:
                                </td>
                                <td colspan="4">
                                    <?= implode(", ", $sleep) ?>
                                </td>

                            </tr>

                        <? endif; ?>
                    <? endif; ?>
                </table>

                <? $table = ob_get_clean(); ?>
                <? $fpdf->htmltable($table); ?>



                <? $fpdf->SetX(10.2); ?>
                <? $fpdf->setY($fpdf->getY() + 3); ?>
                <? $oldY = $fpdf->getY(); ?>

                <? ob_start(); ?>




                <table border="1" width="100%">
                    <? // if (count($campionati['Global']['Classifica'][$champ][$half]['classifica'][$day]['squadre'])): ?>
                    <? if (count($classifica_json[$champ]['Gironi'][$half]['Giornata'][$day]['Classifica'])): ?>
                        <tr bgcolor="#ffff99">
                            <td align="center" colspan="10"  style="bold" size="10">
                                CLASSIFICA

                            </td>
                        </tr>
                    <? else: ?>
                        <tr bgcolor="#ece8e8">
                            <td align="center" colspan="10"  style="bold" size="10">
                                CLASSIFICA
                            </td>
                        </tr> 
                    <? endif; ?>
                    <tr>
                    </tr>
                    <? // if (count($campionati['Global']['Classifica'][$champ][$half]['classifica'][$day]['squadre'])): ?>
                    <? if (count($classifica_json[$champ]['Gironi'][$half]['Giornata'][$day]['Classifica'])): ?>
                        <tr bgcolor="#ffff99">
                    <!--                        <td align="center" width="55">Società</td>-->
                            <td width="60">Squadra</td>
                            <td align="center">Pt</td>
                            <td align="center">PG</td>
                            <td align="center">V</td>
                            <td align="center">P</td>
                            <td align="center">S</td>
                            <td align="center">GF</td>
                            <td align="center">GS</td>
                            <td align="center">DR</td>
                            <td align="center">Coppa disc.</td>
                        </tr>
                    <? endif; ?>
                    <? // $ranking = order_point($campionati['Global']['Classifica'][$champ][$half]['classifica'][$day]['squadre']); ?>

                    <? // foreach ($ranking as $key=> $team): ?>
                    <? foreach ($classifica_json[$champ]['Gironi'][$half]['Giornata'][$day]['Classifica'] as $key => $team): ?>
                        <tr>


                            <?
//                            $punti = $campionati['Global']['Classifica'][$champ][$half]['classifica'][$day]['squadre'][$team]['punti'];
//                            $giocate = $campionati['Global']['Classifica'][$champ][$half]['classifica'][$day]['squadre'][$team]['giocate'];
//                            $totali_vinte = $campionati['Global']['Classifica'][$champ][$half]['classifica'][$day]['squadre'][$team]['totali_vinte'];
//                            $totali_perse = $campionati['Global']['Classifica'][$champ][$half]['classifica'][$day]['squadre'][$team]['totali_perse'];
//                            $nulle = $campionati['Global']['Classifica'][$champ][$half]['classifica'][$day]['squadre'][$team]['nulle'];
//                            $goal_totali_fatti = $campionati['Global']['Classifica'][$champ][$half]['classifica'][$day]['squadre'][$team]['goal_totali_fatti'];
//                            $goal_totali_subiti = $campionati['Global']['Classifica'][$champ][$half]['classifica'][$day]['squadre'][$team]['goal_totali_subiti'];
//                            $coppa_disciplina = $campionati['Global']['Classifica'][$champ][$half]['classifica'][$day]['squadre'][$team]['coppa_disciplina'];
//
//
//                            if ($coppa_disciplina == "")
//                                $coppa_disciplina = 0;
//
//                            if ($nulle == "")
//                                $nulle = 0;
                            ?>
                            <td><?= $team['Nome'] ?></td>
                            <td align="center"><?= $team['Punti'] ?></td>
                            <td align="center"><?= $team['Giocate'] ?></td>
                            <td align="center"><?= $team['Vinte'] ?></td>
                            <td align="center"><?= $team['Nulle'] ?></td>  
                            <td align="center"><?= $team['Perse'] ?></td>                                                  
                            <td align="center"><?= $team['GoalFatti'] ?></td>
                            <td align="center"><?= $team['GoalSubiti'] ?></td>
                            <td align="center"><?= $team['DifferenzaReti'] ?></td>
                            <td align="center"><?= round(($team['CoppaDisciplina'] / $day) * 100) ?></td>


                        </tr>
                    <? endforeach; ?>

                </table>

                <? $table = ob_get_clean(); ?>
                <? $fpdf->htmltable($table); ?> 

                <? $end_classifica = $fpdf->getY() ?>

                <? $end_next_1 = $end_classifica; ?>
                <? $end_next_2 = $end_classifica; ?>
                <? if (isset($campionati['Global']['NextDays'][$champ][$half][$day]['next_1'])): ?>

                    <? $fpdf->SetX(10.2); ?>
                    <? $fpdf->setY($end_classifica + 3); ?>
                    <? ob_start(); ?>

                    <? $all_team_champ_half = $campionati['Global']['AllTeamsChampHalf'][$champ][$half]; ?>

                    <table border="1" width="100%">
                        <tr bgcolor="#ffff99">
                            <td align="center" colspan="9"  style="bold" size="10">
                                Prossima giornata
                            </td>
                        </tr>


                        <? foreach ($campionati['Global']['NextDays'][$champ][$half][$day]['next_1'] as $matches): ?>
                            <? $result = order_next($matches); ?>

                            <? unset($all_team_champ_half[$result['CasaCampionato']]) ?>
                            <? unset($all_team_champ_half[$result['TrasfertaCampionato']]) ?>

                            <tr>
                                <td width="12"><?= $result['Giorno'] ?></td>
                                <td width="26"><?= $result['DataOra'] ?></td>
                                <td nowrap><?= $result['Casa'] ?></td>
                                <td nowrap><?= $result['Trasferta'] ?></td>
                                <td nowrap><?= $result['CampoGioco'] ?></td>
                            </tr>
                        <? endforeach; ?>

                        <? if ($campionato['Italiana'] == "No"): ?>
                            <? if (count($all_team_champ_half) > 0) : ?>
                                <?
                                $sleep_next_1 = array();
                                foreach ($all_team_champ_half as $sleep_team)
                                {
                                    $sleep_next_1[] = $sleep_team['Denominazione'];
                                }
                                ?>
                                <tr>
                                    <td>Riposa:</td>
                                    <td colspan="4"><?= implode(", ", $sleep_next_1) ?></td>
                                </tr>
                            <? endif; ?>
                        <? endif; ?>


                    </table>
                    <? $table = ob_get_clean(); ?>
                    <? $xls_table .= $table; ?>
                    <? $fpdf->htmltable($table); ?> 
                    <? $end_next_1 = $fpdf->getY() ?>
                    <? $end_next_2 = $fpdf->getY() ?>

                <? endif; ?>




                <? if (isset($campionati['Global']['NextDays'][$champ][$half][$day]['next_2'])): ?>

                    <? $fpdf->SetX(10.2); ?>
                    <? $fpdf->setY($end_next_1 + 3); ?>
                    <? ob_start(); ?>

                    <? $all_team_champ_half = $campionati['Global']['AllTeamsChampHalf'][$champ][$half]; ?>

                    <table border="1" width="100%">
                        <tr bgcolor="#ffff99">
                            <td align="center" colspan="9"  style="bold" size="10">
                                <?= ($day + 2) ?>° giornata
                            </td>
                        </tr>


                        <? foreach ($campionati['Global']['NextDays'][$champ][$half][$day]['next_2'] as $matches): ?>
                            <? $result = order_next($matches); ?>

                            <? unset($all_team_champ_half[$result['CasaCampionato']]) ?>
                            <? unset($all_team_champ_half[$result['TrasfertaCampionato']]) ?>

                            <tr>
                                <td width="12"><?= $result['Giorno'] ?></td>
                                <td width="26"><?= $result['DataOra'] ?></td>
                                <td nowrap><?= $result['Casa'] ?></td>
                                <td nowrap><?= $result['Trasferta'] ?></td>
                                <td nowrap><?= $result['CampoGioco'] ?></td>
                            </tr>
                        <? endforeach; ?>

                        <? if ($campionato['Italiana'] == "No"): ?>
                            <? if (count($all_team_champ_half) > 0) : ?>
                                <?
                                $sleep_next_1 = array();
                                foreach ($all_team_champ_half as $sleep_team)
                                {
                                    $sleep_next_1[] = $sleep_team['Denominazione'];
                                }
                                ?>
                                <tr>
                                    <td>Riposa:</td>
                                    <td colspan="4"><?= implode(", ", $sleep_next_1) ?></td>
                                </tr>
                            <? endif; ?>
                        <? endif; ?>


                    </table>

                    <? $table = ob_get_clean(); ?>
                    <? $xls_table .= $table; ?>
                    <? $fpdf->htmltable($table); ?> 

                    <? $end_next_2 = $fpdf->getY() ?>

                <? endif; ?>






                <? $fpdf->SetX(10.2); ?>
                <? $fpdf->setY($end_next_2 + 3); ?>
                <? // $fpdf->setY($end_classifica + 3);  ?>


                <? ob_start(); ?>

                <table border="1" width="90">
                    <? if ($array_diffidati): ?>
                        <tr bgcolor="#ffff99">
                            <td align="center" colspan="9"  style="bold" size="10">
                                Diffidati
                            </td>
                        </tr>
                    <? else : ?>
                        <tr bgcolor="#ece8e8">
                            <td align="center" colspan="9"  style="bold" size="10">
                                Diffidati
                            </td>
                        </tr>
                    <? endif; ?>
                    <? if ($array_diffidati): ?>
                        <tr  bgcolor="#ffff99">
                            <td align="center" nowrap >
                                Nominativo
                            </td>
                            <td align="center"  nowrap >
                                Squadra
                            </td>
                        </tr>
                        <? foreach ($array_diffidati as $key => $ammonito): ?>
                            <tr> 
                                <td nowrap>
                                    <? //= $ammonito['Ammonizioni']          ?>
                                    <?= $ammonito['Name'] ?>
                                </td>
                                <td nowrap>
                                    <?= $squadre[$ammonito['Team']] ?>
                                </td>

                            </tr>
                        <? endforeach; ?>
                    <? endif; ?>
                </table>

                <? $table = ob_get_clean(); ?>
                <? $xls_table .= $table; ?>
                <? $fpdf->htmltable($table); ?> 
                <? $end_diffidati = $fpdf->getY() ?>



                <? $_oldY = $fpdf->getY(); ?>
                <? // $fpdf->setY($end_classifica + 3);   ?>
                <? $fpdf->setY($end_next_2 + 3); ?>
                <? $fpdf->setX(109); ?>

                <? ob_start(); ?>

                <table border="1" width="90">
                    <tr bgcolor="#ffff99">
                        <td align="center" colspan="9"  style="bold" size="10">
                            Classifica Marcatori
                        </td>
                    </tr>
                    <tr bgcolor="#ffff99">
                        <td align="left" >Goal</td>
                        <td align="center" >Nominativo</td>
                        <td align="center" >Società</td>
                    </tr>
                    <? $index_marcatori = 1; ?>

                    <? foreach ($campionati['Global']['Marcatori'][$champ][$half][$day] as $marcatori): ?>
                        <? //$index_marcatori++;     ?>
                        <? if ($index_marcatori > 5): ?>
                            <? break; ?>
                        <? endif; ?>
                        <? // if (in_array($marcatori['0']['anagrafica'], $array_marcatori)): ?>
                        <? if (in_array($marcatori['s']['NomeSquadra'], $teams_to_day)): ?>
                            <? $index_marcatori++; ?>
                            <tr >
                                <td align="center" width="8"><?= $marcatori['0']['goals'] ?></td>
                                <td nowrap><?= $marcatori['0']['anagrafica'] ?></td>
                                <td nowrap><?= $marcatori['s']['NomeSquadra'] ?></td>
                            </tr>
                        <? endif; ?>
                    <? endforeach; ?>
                </table>

                <? $table = ob_get_clean(); ?>
                <? $xls_table .= $table; ?>
                <? $fpdf->htmltable($table); ?> 
                <? $end_marcatori = $fpdf->getY() ?>

                <? $end_diff_marc = max($end_diffidati, $end_marcatori) ?>

                <?
                $fpdf->setY($end_diff_marc + 3);
                $oldY = $fpdf->getY();
                ?>


                <? ob_start(); ?>

                <table border="1" width="90">
                    <? if ($array_squalificati): ?>
                        <tr bgcolor="#ffff99">
                            <td align="center" colspan="9"  style="bold" size="10">
                                Squalificati - 1 gg.
                            </td>
                        </tr>
                    <? else : ?>
                        <tr bgcolor="#ece8e8">
                            <td align="center" colspan="9"  style="bold" size="10">
                                Squalificati - 1 gg.
                            </td>
                        </tr>
                    <? endif; ?>
                    <? if ($array_squalificati): ?>
                        <tr  bgcolor="#ffff99">
                            <td align="center" >
                                Nominativo
                            </td>
                            <td align="center"   >
                                Società
                            </td>
                        </tr>
                        <? foreach ($array_squalificati as $key => $ammonito): ?>
                            <tr> 
                                <td nowrap>
                                    <? //= $ammonito['Ammonizioni']             ?>
                                    <?= $ammonito['Name'] ?>
                                </td>
                                <td nowrap>
                                    <?= $squadre[$ammonito['Team']] ?>
                                </td>

                            </tr>
                        <? endforeach; ?>
                    <? endif; ?>
                </table>

                <? $table = ob_get_clean(); ?>
                <? $xls_table .= $table; ?>
                <? $fpdf->htmltable($table); ?> 


                <? $firstColumnY = $fpdf->getY(); ?>
                <? $fpdf->setY($end_diff_marc + 3); ?>
                <? $fpdf->setX(109); ?>

                <? ob_start(); ?>

                <table border="1" width="90">
                    <? if ($array_espulsi): ?>
                        <tr bgcolor="#ffff99">
                            <td align="center" colspan="9"  style="bold" size="10">
                                Espulsi
                            </td>
                        </tr>
                    <? else: ?>
                        <tr bgcolor="#ece8e8">
                            <td align="center" colspan="9"  style="bold" size="10">
                                Espulsi
                            </td>
                        </tr>
                    <? endif; ?>
                    <? if ($array_espulsi): ?>
                        <tr  bgcolor="#ffff99">
                            <td align="center" >
                                Nominativo
                            </td>


                            <td align="center"   >
                                Società
                            </td>
                        </tr>
                        <? foreach ($array_espulsi as $key => $espulso): ?>

                            <tr> 
                                <td nowrap>
                                    <?= $espulso['Nome'] . " (" . $espulso['Periodo'] . ")" ?>
                                </td>


                                <td nowrap>
                                    <?= $espulso['Squadra'] ?>

                                </td>
                            </tr>
                        <? endforeach; ?>
                    <? endif; ?>
                </table>

                <? $table = ob_get_clean(); ?>
                <? $xls_table .= $table; ?>
                <? $fpdf->htmltable($table); ?>


                <?
                if ($firstColumnY >= $fpdf->getY())
                {
                    $newY = $firstColumnY + 5;
                }
                else
                {
                    $newY = $fpdf->getY();
                }
                ?>              



                <? $fpdf->setY($newY + 3); ?>

                <? ob_start(); ?>

                <table border="1" width="100%">
                    <? if ($array_disciplinari): ?>
                        <tr bgcolor="#ffff99">
                            <td align="center" colspan="9"  style="bold" size="10">
                                Provvedimenti disciplinari
                            </td>
                        </tr>
                    <? else: ?>
                        <tr bgcolor="#ece8e8">
                            <td align="center" colspan="9"  style="bold" size="10">
                                Provvedimenti disciplinari
                            </td>
                        </tr>
                    <? endif; ?>
                    <? if ($array_disciplinari): ?>
                        <? foreach ($array_disciplinari['Descrizione'] as $key => $single_disciplinare): ?>
                            <tr> 
                                <td >
                                    <?= $array_disciplinari['Squadra'][$key] ?> - <?= $single_disciplinare ?>
                                </td>
                                <td width="15">
                                    € <?= sprintf("%01.2f", $array_disciplinari['Sanzione'][$key]); ?>
                                </td>
                            </tr>
                        <? endforeach; ?>
                    <? endif; ?>
                </table>

                <? $table = ob_get_clean(); ?>
                <? $xls_table .= $table; ?>
                <? $fpdf->htmltable($table); ?> 

                <? $fpdf->setY($fpdf->getY() + 3); ?>

                <? ob_start(); ?>

                <table border="1" width="100%">
                    <? if ($array_comunicazioni): ?>
                        <tr bgcolor="#ffff99">
                            <td align="center" colspan="9"  style="bold" size="10">
                                Comunicazioni
                            </td>
                        </tr>
                    <? else: ?>
                        <tr bgcolor="#ece8e8">
                            <td align="center" colspan="9"  style="bold" size="10">
                                Comunicazioni
                            </td>
                        </tr>
                    <? endif; ?>
                    <? if ($array_comunicazioni): ?>
                        <? foreach ($array_comunicazioni as $key => $comunicazione): ?>
                            <tr> 
                                <td >
                                    <?= $comunicazione ?>
                                </td>
                            </tr>
                        <? endforeach; ?>
                    <? endif; ?>
                </table>

                <? $table = ob_get_clean(); ?>
                <? $xls_table .= $table; ?>
                <? $fpdf->htmltable($table); ?> 

            <? endforeach; ?>


        <? endforeach; ?>

    <? endif; ?>

<? endforeach; ?>
<? //-- GIUSEPPE 2022-10-15 ----------------------------------------------------- --> ?>
<? $fpdf->AddPage(); //<!-- nuova di pagina -->            ?>

<? $fpdf->Ln(); ?>

<? ob_start(); //<!-- OGGETTO TABELLA --> ?>

<table border="1" width="100%" >
    <tr bgcolor="#ffff99">
        <td align="center" colspan="4"  style="bold" size="10"><b>SQUALIFICATI A TEMPO</b></td>
    </tr>
    <tr bgcolor="#ffff99">
        <td>Nominativo</td>
        <td>Squadra</td>
        <td>Motivo</td>
        <td>Periodo</td>
    </tr>
    <? foreach ($squalificatiATempo as $key => $espulso): ?>
        <tr>
            <td><?= $espulso['Cognome'] . " " . $espulso['Nome']; ?></td>
            <td><?= $espulso['NomeSquadra']; ?></td>
            <td><?= $espulso['Motivo']; ?></td>
            <td>Squalificato fino al <?= sprintf("%s", date_format(date_create($espulso['EspulsioneFine']), "d/m/Y")); ?></td>
        </tr>
    <? endforeach; ?>
</table>
<? $table = ob_get_clean(); ?>
<? $fpdf->htmltable($table); //<!-- FINE OGGETTO TABELLA -->?>
<? //-- ------------------------------------------------------------------------- -- ?>
<? // $name_file = 'bollettini_' . date("d") . "_" . date("m") . "_" . date("Y") . '_' . uniqid() . '.pdf';       ?>
<? $fpdf->output('files/pdf/' . $name_file, 'F'); ?>
<?

function parse_date($time)
{
// es: 2018-05-02 : 20.30

    $d_h = explode(" : ", $time);

    $date = explode("-", $d_h[0]);

    $date = array_reverse($date);

    $date = implode("/", $date);

    return implode(" : ", array($date, $d_h[1]));
}

function order_point($giornata)
{
    $array_indexed = array();

    foreach ($giornata as $key_team => $values)
    {
        $diff_goals = $values['goal_totali_fatti'] - $values['goal_totali_subiti'];

        $array_indexed[] = array(
            "team" => $key_team,
            "cup_discipline" => $values['coppa_disciplina'],
            "goals" => $values['goal_totali_fatti'],
            "diff_goals" => $diff_goals,
            "points" => $values['punti'],
        );
    }

    $num_teams = count($array_indexed);


    //NAME - ordine crescente
    do // bubble sort name
    {

        $switch = false;

        for ($index = 0; $index < $num_teams - 1; $index++)
        {
            if ($array_indexed[$index]['cup_discipline'] > $array_indexed[$index + 1]['cup_discipline'])
            {
                $switch = true;

                order_array($array_indexed, $index);
            }
        }

        if (!$switch)
        {
            break;
        }
    }
    while (true);


    //COPPA DISCIPLINA - ordine crescente
    do // bubble sort coppa_disc
    {

        $switch = false;

        for ($index = 0; $index < $num_teams - 1; $index++)
        {
            if ($array_indexed[$index]['cup_discipline'] > $array_indexed[$index + 1]['cup_discipline'])
            {
                $switch = true;

                order_array($array_indexed, $index);
            }
        }

        if (!$switch)
        {
            break;
        }
    }
    while (true);


    //GOAL TOTALI - ordine decrescente
    do // bubble sort goals
    {

        $switch = false;

        for ($index = 0; $index < $num_teams - 1; $index++)
        {
            if ($array_indexed[$index]['goal_totali_fatti'] < $array_indexed[$index + 1]['goal_totali_fatti'])
            {
                $switch = true;

                order_array($array_indexed, $index);
            }
        }

        if (!$switch)
        {
            break;
        }
    }
    while (true);


    //DIFFERENZA RETI - ordine decrescente
    do // bubble sort goals
    {

        $switch = false;

        for ($index = 0; $index < $num_teams - 1; $index++)
        {
            if ($array_indexed[$index]['diff_goals'] < $array_indexed[$index + 1]['diff_goals'])
            {
                $switch = true;

                order_array($array_indexed, $index);
            }
        }

        if (!$switch)
        {
            break;
        }
    }
    while (true);


    //PUNTI - ordine decrescente
    do // bubble sort points
    {

        $switch = false;

        for ($index = 0; $index < $num_teams - 1; $index++)
        {
            if ($array_indexed[$index]['points'] < $array_indexed[$index + 1]['points'])
            {
                $switch = true;

                order_array($array_indexed, $index);
            }
        }

        if (!$switch)
        {
            break;
        }
    }
    while (true);


    //SQUADRE SENZA PUNTI - vengono messe in basso
    /* do // bubble sort "-"
      {

      $switch = false;

      for ($index = 0; $index < $num_teams - 1; $index++)
      {
      if (($array_indexed[$index]['points'] === "-") && ($array_indexed[$index + 1]['points'] !== "-"))
      {
      $switch = true;

      order_array($array_indexed, $index);
      }
      }

      if (!$switch)
      {
      break;
      }
      }
      while (true); */



    $result = array();

    foreach ($array_indexed as $index => $team_points)
    {
        $result[] = $team_points['team'];
    }
    return $result;
}

function order_array(&$array_indexed, $index)
{
    $temp['team'] = $array_indexed[$index]['team'];
    $temp['points'] = $array_indexed[$index]['points'];
    $temp['cup_discipline'] = $array_indexed[$index]['cup_discipline'];
    $temp['goal_totali_fatti'] = $array_indexed[$index]['goal_totali_fatti'];
    $temp['diff_goals'] = $array_indexed[$index]['diff_goals'];

    $array_indexed[$index]['team'] = $array_indexed[$index + 1]['team'];
    $array_indexed[$index]['points'] = $array_indexed[$index + 1]['points'];
    $array_indexed[$index]['cup_discipline'] = $array_indexed[$index + 1]['cup_discipline'];
    $array_indexed[$index]['goal_totali_fatti'] = $array_indexed[$index + 1]['goal_totali_fatti'];
    $array_indexed[$index]['diff_goals'] = $array_indexed[$index + 1]['diff_goals'];

    $array_indexed[$index + 1]['team'] = $temp['team'];
    $array_indexed[$index + 1]['points'] = $temp['points'];
    $array_indexed[$index + 1]['cup_discipline'] = $temp['cup_discipline'];
    $array_indexed[$index + 1]['goal_totali_fatti'] = $temp['goal_totali_fatti'];
    $array_indexed[$index + 1]['diff_goals'] = $temp['diff_goals'];
}

function order_next($array_next)
{
    $data_explode = explode("-", $array_next['Data']);
    $data = implode("/", array_reverse($data_explode));

    $data_ora = $data . " : " . $array_next['Ora'];
    $giorno = $array_next['Giorno'];
    $casa = $array_next['SquadraCasa'];
    $trasferta = $array_next['SquadraTrasferta'];
    $campo_gioco = $array_next['CampoGioco'];

    $casa_campionato = $array_next['CasaCampionato'];
    $trasferta_campionato = $array_next['TrasfertaCampionato'];

    $result['DataOra'] = $data_ora;
    $result['Giorno'] = $giorno;
    $result['Casa'] = $casa;
    $result['Trasferta'] = $trasferta;
    $result['CampoGioco'] = $campo_gioco;

    $result['CasaCampionato'] = $casa_campionato;
    $result['TrasfertaCampionato'] = $trasferta_campionato;

    return $result;
}
?>