<!-- // GIUSEPPE 2022-08-23-->  
<? $grp = $this->Session->read('User.group_id'); ?>
<? if ($grp != 15): ?>
    <!-- ------------------- -->  
    <li class="dashboard"><a href="/admin/dashboards/index" title="Dashboard">Dashboard</a></li>
    <li>
        <a href="#" title="Gestione pubblicit&agrave;">Gestione ADS e Slider</a>
        <ul class="submenu">
            <? if (isAllowed('Headers', 'admin_index')): ?><li><a href="/admin/headers/index" title="ADS immagini sfondo">ADS immagini sfondo</a></li><? endif; ?>

            <? if (isAllowed('Headers', 'admin_index')): ?><li><a href="/admin/slides/index" title=" immagini sfondo">Gestione slides home</a></li><? endif; ?>

        </ul>
    </li>
    <li>
        <a href="#" title="Gestione contenuti">Contenuti</a>
        <ul class="submenu">
            <? if (isAllowed('Pages', 'admin_index')): ?><li><a href="/admin/pages/index" title="Gestione contenuti">Gestione contenuti</a></li><? endif; ?>
            <? if (isAllowed('Block', 'admin_index')): ?><li><a href="/admin/blocks/index" title="Blocchi">Gestione blocchi</a></li><? endif; ?>
            <!-- GIUSEPPE //2018-08-20 -->
            <? if (isAllowed('Fixeds', 'admin_index')): ?><li><a href="/admin/fixeds/index" title="Fissi">Gestione contenuti fissi</a></li><? endif; ?>
        </ul>
    </li>
    <li>
        <a href="#" title="Gestione sito">Gestione sito</a>
        <ul class="submenu">
            <? if (isAllowed('BannersRows', 'admin_index')): ?><li><a href="/admin/banners_rows/index" title="Gestione banner (spazi)">Gestione banner (spazi)</a></li><? endif; ?> 
            <? if (isAllowed('Banners', 'admin_index')): ?><li><a href="/admin/banners/index" title="Gestione banner">Gestione banner</a></li><? endif; ?>  
            <? if (isAllowed('Streams', 'admin_index')): ?><li><a href="/admin/streams/index" title="Gestione streaming">Gestione streaming</a></li><? endif; ?>
            <? if (isAllowed('Sliders', 'admin_index')): ?><li><a href="/admin/sliders/index" title="Gestione slider">Gestione slider</a></li><? endif; ?>
        </ul>
    </li>   
    <li>
        <? if ((strtolower($currentUser['User']['Nomegruppo']) != 'youtube')): ?>
            <a href="#" title="Gestione ">Newsletter</a>
            <ul class="submenu">
                <? if (isAllowed('Newsletters', 'admin_index')): ?><li><a href="/admin/newsletters/index" title="Gestione newsletter">Gestione newsletter</a></li><? endif; ?>
                <? if (isAllowed('Nlayouts', 'admin_index')): ?><li><a href="/admin/nlayouts/index" title="Gestione grafiche">Gestione grafiche newsletter</a></li><? endif; ?>
                <? if (isAllowed('NewsletterUsers', 'admin_index')): ?><li><a href="/admin/newsletter_users/index" title="Gestione utenti">Gestione utenti</a></li><? endif; ?>
                <? if (isAllowed('NewsletterGroups', 'admin_index')): ?><li><a href="/admin/newsletter_groups/index" title="Gestione gruppi">Gestione gruppi</a></li><? endif; ?>
                <? if (isAllowed('NewsletterConfigs', 'admin_index')): ?><li><a href="/admin/newsletter_configs/index" title="Gestione configurazioni">Gestione configurazioni</a></li><? endif; ?>
                <? if (isAllowed('NewsletterAccounts', 'admin_index')): ?><li><a href="/admin/newsletter_accounts/index" title="Gestione account email">Gestione account email</a></li><? endif; ?>
            </ul>           
        </li>                               
    <? endif; ?>
    <!-- // GIUSEPPE 2022-08-23 --> 
<? endif; ?>

<!-- ------------------- --> 