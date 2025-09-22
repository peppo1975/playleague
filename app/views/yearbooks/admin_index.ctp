<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/ckeditor/adapters/jquery.js"></script>

<? $html->script('/js/script_layout.js', false); ?>
<? $html->script('/js/script_for_annuario.js', false); ?>
<?
print $backend->formIndex('Yearbook', array(
            'Anno S.' =>
            array(
                'field' => 'Yearbook.AnnoSportivo',
                'order' => true,
            ),
    
    //GIUSEPPE 2023-07-28 ------------------------------------------
//            'Tessera' =>
//            array(
//                'field' => 'Yearbook.Tessera',
//                'order' => true
//            ),
            'Tessera' =>
            array(
                'field' => 'Yearbook.card_id',
                'order' => true
            ),
    // -----------------------------------------------------------
            'Data vidimazione' =>
            array(
                'field' => 'Yearbook.DataVidimazione',
                'order' => true,
                'afterRender' => 'make_date',
                'afterSearch' => 'invert_date'
            ),
            'Squadra/Campionato' =>
            array(
                'field' => 'Yearbook.NomeSquadraCampionato',
                'order' => true
            ),
            'Atleta' =>
            array(
                'field' => 'Yearbook.NomeAtleta',
                'order' => true
            ),
            'Data nasc.' =>
            array(
                'field' => 'Athlete.DataNascita',
                'afterRender' => 'make_date',
                'afterSearch' => 'invert_date'
            ),
            //GIUSEPPE 2020-11-20 ------------------------------
            'Scad. cert. medico' =>
            array(
                'field' => 'Athlete.ScadenzaCertificatoMedico',
                'afterRender' => 'make_date',
                'afterSearch' => 'invert_date'
            ),
            //--------------------------------------------------
            'Cod. Fisc.' =>
            array(
                'field' => 'Athlete.CodiceFiscale',
                'afterSearch' => 'invert_date'
            ),
            'Resp.' =>
            array(
                'field' => 'Athlete.Responsabile',
            ),
            'Telefono' =>
            array(
                'field' => 'Athlete.Telefono',
            ),
            'Cellulare' =>
            array(
                'field' => 'Athlete.Cellulare',
            ),
            'Email' =>
            array(
                'field' => 'Athlete.Email',
            ),
            'Amm.' =>
            array(
                'field' => 'Yearbook.isAdmin',
                'afterRender' => 'isAdmin'
            ),
            'Girone' =>
            array(
                'field' => 'Yearbook.NomeGirone',
            ),
            'Indirizzo' =>
            array(
                'field' => 'Athlete.Indirizzo',
            ),
            'Cap' =>
            array(
                'field' => 'Athlete.Cap',
            ),
            'Località' =>
            array(
                'field' => 'Athlete.Localita',
            ),
            'Provincia' =>
            array(
                'field' => 'Athlete.Provincia',
            ),
            'Luogo nascita' =>
            array(
                'field' => 'Athlete.LuogoNascita',
            ),
            'Sesso' =>
            array(
                'field' => 'Athlete.Sesso',
            ),
            'Arbitro' =>
            array(
                'field' => 'Athlete.Arbitro',
            ),
            'Resp. A' => array(
                'field' => 'Yearbook.Responsabile',
                'order' => true
            ),
            'Tipo assicurazione' =>
            array(
                'field' => 'Yearbook.NomeAssicurazione',
                'order' => true
            ),
            //GIUSEPPE 2018-05-04 -----
            'Costo' =>
            array(
                'field' => 'Yearbook.CostoAssicurazione',
                'order' => true
            ),
                //-------------------------
                )
                ,
                //GIUSEPPE 2018-05-04 ----- aggiunta icona di stampa
                array(
                    'defaultOrder' => 'Yearbook.NomeAtleta',
                    'defaultDir' => 'ASC',
                    'pageTitle' => 'Gestione annuari',
                    'conditions' => array('0=1'),
                    'quickSearch' => array('Yearbook.AnnoSportivo', 'Yearbook.NomeSquadra', 'Yearbook.NomeAtleta', 'Yearbook.Tessera', 'Yearbook.DataVidimazione_it'),
                    'besideQuickSearch' => '

                               <ul>
                                       <li><a href="javascript:;" title="Stampe" rel="timmytip" id="print_responsible"><img src="/img/icon_print_responsible.png" width="16" height="16" alt="" /></a></li>
                                       <li><a href="javascript:;" title="Invia E-Mail o SMS" rel="timmytip" id="sendMailSms"><img src="/img/icon_mail_sms.png" width="16" height="16" alt="" /></a></li>
                                       <li><a href="javascript:;" title="Aggiungi contatti" data-index="yearbook" rel="timmytip" id="add_contacts"><img src="/img/icon_mail_addcontact.png" width="16" height="16" alt="" /></a></li>
                                       <li><a href="javascript:;" title="Stampa ricevuta tesserati" data-index="yearbook" rel="timmytip" id="print_contacts"><img src="/img/icon-print-receipt.png" width="16" height="16" alt="" /></a></li>
                               </ul>

                       '
                )
                //-------------------------
//                array(
//  
//      'defaultOrder' => 'Yearbook.NomeAtleta',
//      'defaultDir'   => 'ASC',
//      'pageTitle' =>  'Gestione annuari',
//      'conditions' => array('0=1'),
//      'quickSearch' => array('Yearbook.AnnoSportivo','Yearbook.NomeSquadra','Yearbook.NomeAtleta','Yearbook.Tessera','Yearbook.DataVidimazione_it'),
//      'besideQuickSearch' => '
//      
//          <ul>
//              <li><a href="javascript:;" title="Stampe" rel="timmytip" id="print_responsible"><img src="/img/icon_print_responsible.png" width="16" height="16" alt="" /></a></li>
//              <li><a href="javascript:;" title="Invia E-Mail o SMS" rel="timmytip" id="sendMailSms"><img src="/img/icon_mail_sms.png" width="16" height="16" alt="" /></a></li>
//              <li><a href="javascript:;" title="Aggiungi contatti" data-index="yearbook" rel="timmytip" id="add_contacts"><img src="/img/icon_mail_addcontact.png" width="16" height="16" alt="" /></a></li>
//          </ul>
//      
//      '
//
//  )
);
?>
