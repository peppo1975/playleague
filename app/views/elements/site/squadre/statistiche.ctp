<?
//GIUSEPPE  20/10/2016 -> filtra la classe
$classPage = $this->requestAction('sections/className/' . $_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 
$nameClass = $classPage["Name"];

$type_sport = array("primary" => "CALCIO", "secondary" => "CALCIO", "quaternary" => "TENNIS");

$sport = $type_sport[$nameClass];
?>
<div class="tab-squadra">
    <div class="list-tab text-center">
        <ul class="pagination pagination-sm">
            <? if (!empty($squadra['Squadre']['Storia']) || !empty($uploads['Squadra'])): ?>
                <li><a href="/squadre/<?= $squadra['Squadre']['Squadra']; ?>/1/<?= strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'], '-')); ?>" title="<?= $squadra['Squadre']['Denominazione']; ?>">Squadra</a></li>
            <? endif; ?>
            <? if (!empty($squadra['SquadreAlbo']) && !empty($uploads['Trofeo'])): ?>
                <li><a href="/squadre/<?= $squadra['Squadre']['Squadra']; ?>/2/<?= strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'], '-')); ?>" title="albo d'oro <?= $squadra['Squadre']['Denominazione']; ?>">Albo d'oro - trofei</a></li>
            <? endif; ?>
            <li class="active"><a href="/squadre/<?= $squadra['Squadre']['Squadra']; ?>/3/<?= strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'], '-')); ?>" title="giocatori / statistiche <?= $squadra['Squadre']['Denominazione']; ?>">Giocatori / Statistiche</a></li>
            <? if (!empty($uploads['Gallery'])): ?>
                <li><a href="/squadre/<?= $squadra['Squadre']['Squadra']; ?>/4/<?= strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'], '-')); ?>" title="galleria foto <?= $squadra['Squadre']['Denominazione']; ?>">Galleria</a></li>
            <? endif; ?>
        </ul>
        <div class="clear"></div>
    </div>
    <div class="clear"></div> 
    <div class="content-tab">

        <script type="text/javascript" src="/js/layout.js"></script>
        <div class="filters-element"><!-- filters-element -->

            <div id="filter-pad">
                <div id="wrapper-select" class="row">

                    <div class="select-filter col-md-6">

                        <div class="select-box little-select selcect-year">
                            <div class="content-select">
                                <span class="active-value">Stagione di riferimento...</span>


                                <select class="select-year form-control" autocomplete="off" name="anno_id" data-squadra="<?= $this->params['pass'][0]; ?>">
                                    <option value="" selected>Stagione di riferimento...</option>

                                    <? foreach ($anni as $anno): ?>

                                        <option value="<?= $anno; ?>"><?= $anno; ?></option>

                                    <? endforeach; ?>



                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="select-filter filter-campionato hidden col-md-6">

                        <div class="select-box select-girone middle-select">
                            <span class="active-value">Torneo di riferimento...</span>

                            <select class="select-year form-control" autocomplete="off" name="campionati_id" data-squadra="<?= $this->params['pass'][0]; ?>">
                                <option value="">Seleziona torneo di riferimento...</option>



                            </select>

                        </div><!-- close select-box -->
                    </div><!-- close select filter -->

                    <div class="select-filter select-squadre hidden col-md-4">
                        <h3 class="no-required">Seleziona squadra di appartenenza</h3>
                        <div class="select-box middle-select yellow">
                            <input name="filter_team" type="hidden" value="true" autocomplete="off" />
                            <span class="checkbox-unset hidden"></span>
                            <div class="content-select">
                                <span class="active-value">Seleziona squadra di appartenenza...</span>


                                <input type="hidden" name="squadra_id" class="select-value" autocomplete="off" />
                                <input type="hidden" name="girone_id" class="select-value" autocomplete="off" />

                                <div class="values-of-select">
                                    <ul>

                                    </ul>
                                </div><!-- close values-of-select -->
                            </div>
                            <div class="close-select"></div>
                            <div class="clear"></div>
                        </div><!-- close select-box -->
                    </div><!-- close select filter -->

                </div><!-- wrapper-select -->
                <div class="clear"></div>
                <div class="text-center">
                    <ul id="switch-button-logged" class="switch-button switch-filters hidden pagination pagination-sm">

                        <? if ($sport == "CALCIO"): ?>
                            <li class="yellow team-button-edit-logged" id="team-button-edit" data-value="squadra_logged"><a href="javascript:;">Modifica atleti</a></li>
                            <li class="switch-value hidden"><input type="hidden" value="calendario" autocomplete="off" name="filter_select" /></li>
                            <li data-value="calendario"><a href="javascript:;">Calendario</a></li>
                            <li data-value="classifica"><a href="javascript:;">Classifica</a></li>
                            <li data-value="marcatori"><a href="javascript:;">Marcatori</a></li>
                            <li data-value="diffidati"><a href="javascript:;">Diffidati</a></li>
                            <li data-value="espulsi"><a href="javascript:;">Espulsi</a></li>
                            <li data-value="squalificati"><a href="javascript:;">Squalificati</a></li>
                            <li data-value="disciplinari"><a href="javascript:;">Sanzioni</a></li>
                            <li data-value="squadra_annuario"><a href="javascript:;">Annuario squadra</a></li>
                        <? elseif ($sport == "TENNIS"): ?>
                            <li class="yellow team-button-edit-logged" id="team-button-edit" data-value="squadra_logged"><a href="javascript:;">Atleti</a></li>
                            <li class="switch-value hidden"><input type="hidden" value="calendario" autocomplete="off" name="filter_select" /></li>
                            <li data-value="calendario"><a href="javascript:;">Calendario</a></li>
                            <li data-value="classifica"><a href="javascript:;">Classifica</a></li>
                            <li data-value="squadra_annuario"><a href="javascript:;">Annuario squadra</a></li>
                        <? endif; ?>
                    </ul>
                </div>
                <!--
                <ul class="switch-button switch-checkbox hidden" id="team-button">
                
                        
                                <li class="yellow"><input name="filter_team" type="hidden" value="true" autocomplete="off" /><span class="checkbox-unset"></span><span class="checkbox-label">Squadra/Atleti</span></li>
                
                </ul>
                -->
                <div class="clear"></div>
            </div><!-- close filter-pad -->

            <div class="table-container">
                <ul class="switch-table-menu pagination pagination-sm">

                </ul>
                <div id="results-box">


                </div><!-- close results-box -->
            </div><!-- close table-container -->

        </div><!-- close filters-element -->			
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>	