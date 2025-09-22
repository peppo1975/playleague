<?
if ($session->check('User.group_id'))
{

    $grp = $session->read('User.group_id');
}
else
{

    $grp = 0;
}
$admin_type = 'champ';

if ($session->check('admin_type'))
{

    $admin_type = $session->read('admin_type');
}


if ($grp != 12)
{
    ?>	

    <?
    if ($currentUser['User']['Nomegruppo'] == 'News')
        $admin_type = 'web';
    ?>				

    <?
    switch ($admin_type)
    {

        case 'champ':
            ?>

            <div class="block_box">
                <h1>Gestione atleti</h1>
                <img src="/img/gestione_atleti.png" width="100" height="100" alt="Gestione atleti" />
                <div class="block_box_links">
                    <ul>
                        <? if (isAllowed('Athletes', 'admin_index')): ?><li><a href="/admin/athletes/index" title="Anagrafica">Anagrafica</a></li><? endif; ?>
                        <? if (isAllowed('Yearbooks', 'admin_index')): ?><li><a href="/admin/yearbooks/index" title="Annuario atleti">Annuario atleti</a></li><? endif; ?>
                        <? if (isAllowed('Teambooks', 'admin_index')): ?><li><a href="/admin/teambooks/index" title="Annuario Squadre">Annuario squadre</a></li><? endif; ?>
                        <? if (isAllowed('SummaryLdas', 'admin_index')): ?><li><a href="/admin/summary_ldas/index" title="Riepilogo LDA">Riepilogo LDA</a></li><? endif; ?>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>



            <div class="block_box">
                <h1>Gestione campionati</h1>

                <img src="/img/gestione_campionati.png" width="100" height="100" alt="Gestione campionati" />
                <div class="block_box_links">

                    <ul>
                        <? if (isAllowed('Matches', 'admin_index')): ?><li><a href="/admin/matches/index" title="Gestione campionati">Gestione campionati</a></li><? endif; ?>
                        <? if (isAllowed('Matchgoals', 'admin_index')): ?><li><a href="/admin/matchgoals/index" title="Gestione campionati">Gestione espulsi</a></li><? endif; ?>
                        <? if (isAllowed('Rankings', 'admin_index')): ?><li><a href="/admin/rankings/index" title="Classifiche">Classifiche</a></li><? endif; ?>
                    </ul>
                </div>
                <div class="clear"></div>

            </div>
            <div class="block_box calendari">
                <h1>Gestione calendari</h1>
                <img src="/img/gestione_calendari.png" width="100" height="100" alt="Gestione calendari" />
                <div class="block_box_links">
                    <ul>
                        <? if (isAllowed('Notgames', 'admin_index')): ?><li><a href="/admin/notgames/index" title="Elenco giorni di non gioco">Elenco giorni di non gioco</a></li><? endif; ?>
                        <? if (isAllowed('Matches', 'admin_refresh')): ?><li><a id="refresh_champ" href="javascript:;">Generazione calendario</a></li><? endif; ?>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>						
            <div class="block_box">
                <h1>Tabelle</h1>
                <img src="/img/gestione_tabelle.png" width="100" height="100" alt="Tabelle" />
                <div class="block_box_links">							
                    <ul>
                        <? if (isAllowed('AnniSportivis', 'admin_index')): ?><li><a href="/admin/anni_sportivis/index" title="Tabella anni sportivi">Tabella anni sportivi</a></li><? endif; ?>
                        <? if (isAllowed('Campis', 'admin_index')): ?><li><a href="/admin/campis/index" title="Tabella campi">Tabella campi</a></li><? endif; ?>
                        <? if (isAllowed('Campis', 'prospetto')): ?><li><a href="/admin/campis/prospetto" title="PLANNING campi"><strong>PLANNING campi</strong></a></li><? endif; ?> 
                                     <!-- //GIUSEPPE 2022-08-23 -->
                        <? if (isAllowed('ChampCategories', 'admin_index')): ?><li><a href="/admin/champ_categories/index" title="Tabella categorie campionati">Tabella categ. campionati</a></li><? endif; ?>
                        <? if (isAllowed('Campionatis', 'admin_index')): ?><li><a href="/admin/campionatis/index" title="Tabella campionati">Tabella campionati</a></li><? endif; ?>


                        <? if (isAllowed('Campionatis', 'admin_index')): ?><li><a href="/admin/subscriptions/index" title="Tabella orari iscrizioni">Tabella orari iscrizioni</a></li><? endif; ?>


                        <? if (isAllowed('Expulsions', 'admin_index')): ?><li><a href="/admin/expulsions/index" title="Tabella espulsioni">Tabella espulsioni</a></li><? endif; ?>
                        <? if (isAllowed('Causalresults', 'admin_index')): ?><li><a href="/admin/causalresults/index" title="Tabella causali risultati">Tabella causali risultati</a></li><? endif; ?>
                        <? if (isAllowed('Disciplines', 'admin_index')): ?><li><a href="/admin/disciplines/index" title="Tabella disciplinari">Tabella disciplinari</a></li><? endif; ?>
                        <? if (isAllowed('Squadres', 'admin_index')): ?><li><a href="/admin/squadres/index" title="Tabella squadre">Tabella squadre</a></li><? endif; ?>
                        <? if (isAllowed('Comunications', 'admin_index')): ?><li><a href="/admin/comunications/index" title="Tabella comunicazioni">Tabella comunicazioni</a></li><? endif; ?>
                        <? if (isAllowed('TipiAssicuraziones', 'admin_index')): ?><li><a href="/admin/tipi_assicuraziones/index" title="Tabella tipi assicurazione">Tabella tipi assicurazione</a></li><? endif; ?>
                        <? if (isAllowed('Cauzionis', 'admin_index')): ?><li><a href="/admin/cauzionis/index" title="Tabella cauzioni">Tabella cauzioni</a></li><? endif; ?>
                        <? if (isAllowed('Users', 'admin_index')): ?><li><a href="/admin/users/index" title="Tabella utenti">Tabella utenti</a></li><? endif; ?>
                        <? if (isAllowed('LdaWalls', 'admin_index')): ?><li><a href="/admin/lda_walls/index" title="Tabella bacheca lda">Tabella bacheca lda</a></li><? endif; ?>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>

            <div class="block_box stampe">
                <h1>Stampe</h1>
                <img src="/img/gestione_stampe.png" width="100" height="100" alt="Stampe" />
                <div class="block_box_links">	
                    <ul>
                        <? if (isAllowed('Prints', 'admin_bullettins')): ?><li><a href="javascript:;" id="print_bullettins" data-tab="1" title="Stampa bollettino">Stampa bollettino</a></li><? endif; ?>
                        <? if (isAllowed('Prints', 'admin_calendars')): ?><li><a href="javascript:;" id="print_bullettins" data-tab="2" title="Stampa calendario">Stampa calendario</a></li><? endif; ?>
                        <? if (isAllowed('Prints', 'admin_responsible_index')): ?><li><a href="javascript:;" id="print_responsible" data-tab="3" title="Stampa responsabili">Stampa responsabili</a></li><? endif; ?>
                        <? if (isAllowed('Prints', 'admin_single_lda')): ?><li><a href="javascript:;" id="print_bullettins" data-tab="3" title="Riepilogo LDA singolo">Riepilogo LDA singolo</a></li><? endif; ?>
                        <? if (isAllowed('Prints', 'admin_ldaMounth')): ?><li><a href="javascript:;" id="print_bullettins" data-tab="6" title="Riepilogo LDA mensile">Riepilogo LDA mensile</a></li><? endif; ?>
                        <? if (isAllowed('Prints', 'admin_general_lda')): ?><li><a href="javascript:;" id="print_bullettins" data-tab="4" title="Riepilogo LDA generale">Riepilogo LDA generale</a></li><? endif; ?>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>

            <? if (isAllowed('Spools', 'admin_mail')): ?>
                <div class="block_box">
                    <h1>Tabelle spooler</h1>
                    <img src="/img/gestione_tabelle.png" width="100" height="100" alt="Tabelle" />
                    <div class="block_box_links">							
                        <ul>
                            <? if (isAllowed('Spools', 'admin_index')): ?><li><a href="/admin/spools/index" title="Tabella spooler">Tabella spooler</a></li><? endif; ?>

                            <? if (isAllowed('Spools', 'admin_sms')): ?><li><a href="/admin/spools/sms" title="Tabella sms">Tabella sms</a></li><? endif; ?>
                            <? if (isAllowed('Spools', 'admin_mail')): ?><li><a href="/admin/spools/mail" title="Tabella mail">Tabella mail</a></li><? endif; ?>
                            <? if (isAllowed('Spools', 'admin_newsletter')): ?><li><a href="/admin/spools/newsletter" title="Tabella mail">Tabella newsletter</a></li><? endif; ?>

                        </ul>
                    </div>
                <? endif; ?>
                <!--	-->					


                <div class="clear"></div>

            </div>

            <? break; ?>


        <? case 'web': ?>

            <div class="block_box">
                <h1>Gestione ADS e Slider</h1>
                <img src="/img/icon_adv.png" width="100" height="100" alt="Gestione header" />
                <div class="block_box_links">
                    <ul>

                        <? if (isAllowed('Headers', 'admin_index')): ?><li><a href="/admin/headers/index" title="ADS immagini sfondo">ADS immagini sfondo</a></li><? endif; ?>

                        <? if (isAllowed('Headers', 'admin_index')): ?><li><a href="/admin/slides/index" title=" immagini sfondo">Gestione slides home</a></li><? endif; ?>

                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <div class="block_box">
                <h1>Gestione contenuti</h1>
                <img src="/img/icon_content.png" width="100" height="100" alt="Gestione contenuti" />
                <div class="block_box_links">
                    <ul>
                        <? if (isAllowed('Pages', 'admin_index')): ?><li><a href="/admin/pages/index" title="Contenuti">Gestione men&ugrave;</a></li><? endif; ?>
                        <? if (isAllowed('Block', 'admin_index')): ?><li><a href="/admin/blocks/index" title="Blocchi">Gestione blocchi</a></li><? endif; ?>
                        <!-- GIUSEPPE //2018-08-20 -->
                        <? if (isAllowed('Fixeds', 'admin_index')): ?><li><a href="/admin/fixeds/index" title="Fissi">Gestione contenuti fissi</a></li><? endif; ?>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>	
            <div class="block_box">
                <h1>Gestione sito</h1>
                <img src="/img/icon_site.png" width="100" height="100" alt="Gestione sito" />
                <div class="block_box_links">
                    <ul>
                        <? if (isAllowed('BannersRows', 'admin_index')): ?><li><a href="/admin/banners_rows/index" title="Gestione banner (spazi)">Gestione banner (spazi)</a></li><? endif; ?>	
                        <? if (isAllowed('Banners', 'admin_index')): ?><li><a href="/admin/banners/index" title="Gestione banner">Gestione banner</a></li><? endif; ?>	
                        <? if (isAllowed('Streams', 'admin_index')): ?><li><a href="/admin/streams/index" title="Gestione streaming">Gestione streaming</a></li><? endif; ?>
                        <? if (isAllowed('Sliders', 'admin_index')): ?><li><a href="/admin/sliders/index" title="Gestione slider">Gestione slider</a></li><? endif; ?>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>			

            <div class="block_box">
                <h1>Gestione newsletter</h1>
                <img src="/img/timmyshare/gestione_newsletter.png" width="100" height="100" alt="Gestione Newsletter" />							
                <div class="block_box_links">
                    <ul>
                        <? if (isAllowed('Newsletters', 'admin_index')): ?><li><a href="/admin/newsletters/index" title="Gestione newsletter">Gestione newsletter</a></li><? endif; ?>
                        <? if ((strtolower($currentUser['User']['Nomegruppo']) != 'youtube')): ?><li><a href="/admin/nlayouts/index" title="Gestione grafiche">Gestione grafiche newsletter</a></li><? endif; ?>
                        <? if (isAllowed('NewsletterUsers', 'admin_index')): ?><li><a href="/admin/newsletter_users/index" title="Gestione utenti">Gestione utenti</a></li><? endif; ?>
                        <? if (isAllowed('NewsletterGroups', 'admin_index')): ?><li><a href="/admin/newsletter_groups/index" title="Gestione gruppi">Gestione gruppi</a></li><? endif; ?>
                        <? if (isAllowed('NewsletterConfigs', 'admin_index')): ?><li><a href="/admin/newsletter_configs/index" title="Gestione configurazioni">Gestione configurazioni</a></li><? endif; ?>
                        <? if (isAllowed('NewsletterAccounts', 'admin_index')): ?><li><a href="/admin/newsletter_accounts/index" title="Gestione account email">Gestione account email</a></li><? endif; ?>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>		



            <?
            break;
    }
} else
{
    ?>
    <div class="block_box">
        <h1>Tabelle</h1>
        <img src="/img/gestione_tabelle.png" width="100" height="100" alt="Tabelle" />
        <div class="block_box_links">							
            <ul>
                <? if (isAllowed('Campis', 'admin_index')): ?><li><a href="/admin/campis/index" title="Tabella campi">Tabella campi</a></li><? endif; ?>
            </ul>
        </div>
        <div class="clear"></div>
    </div>	   

    <?
}
?>
