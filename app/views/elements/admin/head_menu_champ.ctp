
<? $grp = $this->Session->read('User.group_id'); ?>
<? // if ($grp != 12):                     ?>
<!-- // GIUSEPPE 2022-08-23-->  
<? if ($grp != 12 && $grp != 15): ?>
    <li class="dashboard"><a href="/admin/dashboards/index" title="Dashboard">Dashboard</a></li>
    <li>
        <a href="#" title="Gestione atleti">Gestione atleti</a>
        <ul class="submenu">
            <? if (isAllowed('Athletes', 'admin_index')): ?><li><a href="/admin/athletes/index" title="Anagrafica">Anagrafica</a></li><? endif; ?>
            <? if (isAllowed('Yearbooks', 'admin_index')): ?><li><a href="/admin/yearbooks/index" title="Annuario atleti">Annuario atleti</a></li><? endif; ?>
            <? if (isAllowed('Teambooks', 'admin_index')): ?><li><a href="/admin/teambooks/index" title="Annuario Squadre">Annuario squadre</a></li><? endif; ?>
            <? if (isAllowed('SummaryLdas', 'admin_index')): ?><li><a href="/admin/summary_ldas/index" title="Riepilogo LDA">Riepilogo LDA</a></li><? endif; ?>
        </ul>
    </li>

    <li>
        <a href="#" title="Gestione campionati">Gestione campionati</a>
        <ul class="submenu">
            <? if (isAllowed('Matches', 'admin_index')): ?><li><a href="/admin/matches/index" title="Gestione campionati">Gestione campionati</a></li><? endif; ?>
            <? if (isAllowed('Matchgoals', 'admin_index')): ?><li><a href="/admin/matchgoals/index" title="Gestione campionati">Gestione espulsi</a></li><? endif; ?>
            <? if (isAllowed('Rankings', 'admin_index')): ?><li><a href="/admin/rankings/index" title="Classifiche">Classifiche</a></li><? endif; ?>
        </ul>
    </li>

    <li>
        <a href="#" title="Gestione calendari">Gestione calendari</a>
        <ul class="submenu">
            <? if (isAllowed('Notgames', 'admin_index')): ?><li><a href="/admin/notgames/index" title="Elenco giorni di non gioco">Elenco giorni di non gioco</a></li><? endif; ?>
            <? if (isAllowed('Matches', 'admin_refresh')): ?><li><a id="refresh_champ" href="javascript:;">Generazione calendario</a></li><? endif; ?>
            <? if (isAllowed('Matches', 'admin_load_calendario')): ?><li><a href="/admin/matches/load_calendario" title="Load calendario">Load Calendario</a></li><? endif; ?> <!-- //GIUSEPPE 2022-10-15 -->
        </ul>
    </li>


    <li>
        <a href="#" title="Tabelle">Tabelle</a>
        <ul class="submenu">

            <li><a href="/admin/events/index" title="Tabella prossime manifestazioni">Tabella prossime manifestazioni</a></li>

            <li><a href="/admin/types/index" title="Tabella prossime manifestazioni">Tabella tipologie manifestazioni</a></li>


            <? if (isAllowed('AnniSportivis', 'admin_index')): ?><li><a href="/admin/anni_sportivis/index" title="Tabella anni sportivi">Tabella anni sportivi</a></li><? endif; ?>
            <? if (isAllowed('Campis', 'admin_index')): ?><li><a href="/admin/campis/index" title="Tabella campi">Tabella campi</a></li><? endif; ?>

            <? if (isAllowed('Campis', 'prospetto')): ?><li><a href="/admin/campis/prospetto" title="Tabella campi">Tabella <strong>NOLEGGIO CAMPI</strong></a></li><? endif; ?> <!-- //GIUSEPPE 2022-08-23 -->
            <? if (isAllowed('Campis', 'bookers')): ?><li><a href="/admin/campis/bookers" title="Tabella noleggiatori">Tabella noleggiatori</a></li><? endif; ?> <!-- //GIUSEPPE 2023-01-17 -->

            <? if (isAllowed('ChampCategories', 'admin_index')): ?><li><a href="/admin/champ_categories/index" title="Tabella categorie campionati">Tabella categ. campionati</a></li><? endif; ?>
            <? if (isAllowed('Campionatis', 'admin_index')): ?><li><a href="/admin/campionatis/index" title="Tabella campionati">Tabella campionati</a></li><? endif; ?>

            <? if (isAllowed('Campionatis', 'admin_index')): ?><li><a href="/admin/subscriptions/index" title="Tabella orari iscrizioni">Tabella orari iscrizioni</a></li><? endif; ?>

            <? if (isAllowed('Expulsions', 'admin_index')): ?><li><a href="/admin/expulsions/index" title="Tabella espulsioni">Tabella espulsioni</a></li><? endif; ?>
            <? if (isAllowed('Causalresults', 'admin_index')): ?><li><a href="/admin/causalresults/index" title="Tabella causali risultati">Tabella causali risultati</a></li><? endif; ?>
            <? if (isAllowed('Disciplines', 'admin_index')): ?><li><a href="/admin/disciplines/index" title="Tabella disciplinari">Tabella disciplinari</a></li><? endif; ?>
            <? if (isAllowed('Squadres', 'admin_index')): ?><li><a href="/admin/squadres/index" title="Tabella squadre">Tabella squadre</a></li><? endif; ?>
			<? if (isAllowed('Squadres', 'admin_squadre_bas')): ?><li><a href="/admin/squadres/squadre_bas" title="Tabella squadre BAS">Tabella squadre <strong>BAS</strong></a></li><? endif; ?> <!-- //GIUSEPPE 2024-05-23 -->
            <? if (isAllowed('Squadres', 'admin_id_squadre_id_campionati')): ?><li><a href="/admin/squadres/id_squadre_id_campionati" title="Tabella squadre ID">Tabella squadre <strong>ID</strong></a></li><? endif; ?> <!-- //GIUSEPPE 2024-05-10 -->
            <? if (isAllowed('Comunications', 'admin_index')): ?><li><a href="/admin/comunications/index" title="Tabella comunicazioni">Tabella comunicazioni</a></li><? endif; ?>
            <? if (isAllowed('TipiAssicuraziones', 'admin_index')): ?><li><a href="/admin/tipi_assicuraziones/index" title="Tabella tipi assicurazione">Tabella tipi assicurazione</a></li><? endif; ?>
            <? if (isAllowed('Cauzionis', 'admin_index')): ?><li><a href="/admin/cauzionis/index" title="Tabella cauzioni">Tabella cauzioni</a></li><? endif; ?>
            <? if (isAllowed('Users', 'admin_index')): ?><li><a href="/admin/users/index" title="Tabella utenti">Tabella utenti</a></li><? endif; ?>
            <? if (isAllowed('LdaWalls', 'admin_index')): ?><li><a href="/admin/lda_walls/index" title="Tabella bacheca lda">Tabella bacheca lda</a></li><? endif; ?>
        </ul>
    </li>


    <li>
        <a href="#" title="Tabelle">Spooler</a>
        <ul class="submenu">
            <? if (isAllowed('Spools', 'admin_index')): ?><li><a href="/admin/spools/index" title="Tabella spooler">Tabella spooler</a></li><? endif; ?>
            <? if (isAllowed('Spools', 'admin_sms')): ?><li><a href="/admin/spools/sms" title="Tabella sms">Tabella sms</a></li><? endif; ?>
            <? if (isAllowed('Spools', 'admin_mail')): ?><li><a href="/admin/spools/mail" title="Tabella mail">Tabella mail</a></li><? endif; ?>
            <? if (isAllowed('Spools', 'admin_newsletter')): ?><li><a href="/admin/spools/newsletter" title="Tabella newsletter">Tabella newsletter</a></li><? endif; ?>
            <? if (isAllowed('Spools', 'admin_newsletter')): ?><li><a href="/admin/spools/bullettin" title="Tabella bollettini">Tabella bollettini</a></li><? endif; ?>

        </ul>
    </li>

    <!-- // GIUSEPPE 2022-08-23-->  
<? elseif ($grp == 12): ?>
    <!-- --------------------- -->  
    <? // else?>
    <li class="dashboard"><a href="/admin/dashboards/index" title="Dashboard">Dashboard</a></li>

    <li>
        <a href="#" title="Tabelle">Tabelle</a>
        <ul class="submenu">
            <? if (isAllowed('Campis', 'admin_index')): ?><li><a href="/admin/campis/index" title="Tabella campi">Tabella campi</a></li><? endif; ?>
        </ul>
    </li>

    <!-- // GIUSEPPE 2022-08-23-->  
<? elseif ($grp == 15): ?>

    <li>
        <a href="#" title="Tabelle">Tabelle</a>
        <ul class="submenu">

            <? if (isAllowed('Campis', 'prospetto')): ?><li><a href="/admin/campis/prospetto" title="Tabella campi">Tabella campi - PROSPETTO</a></li><? endif; ?> <!-- //GIUSEPPE 2022-08-23 -->

        </ul>
    </li>

    <script>

        let site = location.href;

        if (!site.includes("campis/prospetto"))
        {
            let arr = site.split("/admin");
            console.log(arr);
            let redirect = `${arr[0]}/admin/campis/prospetto`;
            location.href = redirect;
        }

    </script>

<? else: ?>
    <!-- --------------------- --> 
<? endif; ?>
