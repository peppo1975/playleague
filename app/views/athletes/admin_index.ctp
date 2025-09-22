<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/ckeditor/adapters/jquery.js"></script>

<? $html->script('/js/script_layout.js', false); ?>
<?
print $backend->formIndex('Athlete',
                array(
                    'Cognome' =>
                    array(
                        'field' => 'Athlete.Cognome',
                        'order' => true
                    ),
                    'Nome' =>
                    array(
                        'field' => 'Athlete.Nome',
                        'order' => true
                    ),
                    'Indirizzo' =>
                    array(
                        'field' => 'Athlete.Indirizzo',
                        'order' => true
                    ),
                    'Cap' =>
                    array(
                        'field' => 'Athlete.Cap',
                        'order' => true
                    ),
                    'Località' =>
                    array(
                        'field' => 'Athlete.Localita',
                        'order' => true
                    ),
                    'Provincia' =>
                    array(
                        'field' => 'Athlete.Provincia',
                        'order' => true
                    ),
                    'Tel.' =>
                    array(
                        'field' => 'Athlete.Telefono',
                        'order' => true
                    ),
                    'Tel.' =>
                    array(
                        'field' => 'Athlete.Telefono',
                        'order' => false
                    ),
                    'Cel.' =>
                    array(
                        'field' => 'Athlete.Cellulare',
                        'order' => false
                    ),
                    'Fax' =>
                    array(
                        'field' => 'Athlete.Fax',
                        'order' => false
                    ),
                    'E-mail' =>
                    array(
                        'field' => 'Athlete.Email',
                        'order' => true
                    ),
                    'Luogo di nascita' =>
                    array(
                        'field' => 'Athlete.LuogoNascita',
                        'order' => true
                    ),
                    'Data di nascita' =>
                    array(
                        'field' => 'Athlete.DataNascita',
                        'order' => true,
                        'afterRender' => 'make_date',
                        'afterSearch' => 'invert_date'
                    ),
                    'Sesso' =>
                    array(
                        'field' => 'Athlete.Sesso',
                        'order' => false
                    ),
                    'Tipo di documento' =>
                    array(
                        'field' => 'Athlete.TipoDocumento',
                        'order' => true
                    ),
                    'Num. documento' =>
                    array(
                        'field' => 'Athlete.NumeroDocumento',
                        'order' => true
                    ),
                    'Scad. documento' =>
                    array(
                        'field' => 'Athlete.ScadenzaDocumento',
                        'order' => true,
                        'afterRender' => 'make_date',
                        'afterSearch' => 'invert_date'
                    ),
                    //GIUSEPPE 2020-11-20 ------------------------------
                    'Scad. cert. medico' =>
                    array(
                        'field' => 'Athlete.ScadenzaCertificatoMedico',
                        'order' => true,
                        'afterRender' => 'make_date',
                        'afterSearch' => 'invert_date'
                    ),
                    //--------------------------------------------------
                    'Responsabile' =>
                    array(
                        'field' => 'Athlete.Responsabile',
                        'order' => false
                    ),
                    'Arbitro' =>
                    array(
                        'field' => 'Athlete.Arbitro',
                        'order' => false
                    ),
                    'Sportivo' =>
                    array(
                        'field' => 'Athlete.Sportivo',
                        'order' => false
                    ),
                )
                , array(
            'defaultOrder' => 'Athlete.Cognome',
            'defaultDir' => 'ASC',
            'pageTitle' => 'Gestione Atleti',
            'conditions' => $conditions,
            'quickSearch' => array('Athlete.Nome', 'Athlete.Cognome', 'Athlete.Anagrafica', 'Athlete.reverseAnagrafica', 'Athlete.DataNascita'),
            'besideQuickSearch' => '
        
            <ul>
                <li><a href="javascript:;" title="Stampa etichette" rel="timmytip" id="print_etichette_full"><img src="/img/icon_print_responsible.png" width="16" height="16" alt="" /></a></li>
                <li><a href="javascript:;" title="Invia E-Mail o SMS" rel="timmytip" id="sendMailSms"><img src="/img/icon_mail_sms.png" width="16" height="16" alt="" /></a></li>
                <li><a href="javascript:;" title="Aggiungi contatti" rel="timmytip" data-index="athlete" id="add_contacts"><img src="/img/icon_mail_addcontact.png" width="16" height="16" alt="" /></a></li>
            </ul>
        
        '
        ));
?>
<!-- //GIUSEPPE 2022-10-15 ---------------------------------------------- -->
<script>
    $(() => {
        $(".index-row-delete").hide();
    });
</script>
<!-- -------------------------------------------------------------------- -->