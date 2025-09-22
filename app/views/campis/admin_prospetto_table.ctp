<? ob_start() ?>

<style>

    .intro.pointer{
        margin-top: 30px;
    }

    .pointer h3{
        border-bottom: 1px dotted #CCC;
        margin-top: 10px;
        padding: 10px 20px;
        font-size: 20px;
        background: #eee;
        color: #000;
        border-radius: 5px 5px 0 0;
        border: 1px solid #ccc;
    }

    .pointer h3 span{
        color: #ff2ce2;
        font-size: 14px;
    }

    .cella{
        width: 190px;
        overflow: hidden;
    }

    .cella a{
        color: #fff;
    }

    .cella p{
        padding: 10px 20px;
        word-wrap: break-word;
    }

    span.book-day{
        text-transform: uppercase;
        padding-left: 20px;
    }

    tr:hover{
        background-color: #fff !important;
    }

    .libero,
    .privato,
    .campionato{
        border-radius: 10px;
        margin-bottom: 5px;
        color: #fff;
        font-size: 14px;
    }

    .libero{
        background-color: #6fb406;
        cursor:pointer;
        border: 1px solid #6fb406;
    }
    .libero:hover{
        background-color: greenyellow;
        color: black;
    }

    .privato{
        background-color: #000db4;
        border: 1px solid #000db4;
        /* cursor:pointer; */
    }
    .privato:hover{
        background-color: #5561ff;
        /* color: #000; */
    }

    .privato:hover a{
        /* color: #000; */
    }

    .campionato{
        background-color: #eee;
        border: 1px solid #ccc;
        color: #333 !important;
    }


    .privato a{
        text-decoration:none;
    }

    .privato a:hover{
        /* color: #000; */
        text-decoration: underline;
    }


    .status-span,
    .book-name,
    .book-email,
    .squad-name,
    .book-price,
    .book-telefono{
        font-size: 12px;
    }

    .status-span.c-status{
        background: #ff00e5;
        padding: 3px 6px;
        border-radius: 5px;
        color: #fff;
    }

    .squad-name{
        font-size: 11px;
    }

    .competition-name{
        display: block;
        padding-top: 5px;
        padding-bottom: 5px;
        border-bottom: 1px solid #ccc;
        margin: 5px 0;
        font-size: 13px;
    }


    .campis-table td:nth-child(even),
    .campis-table th:nth-child(even)  {
        background-color: #eafff4;
        border-left: 1px solid #ccc;
        border-right: 1px solid #ccc;
    }

    .result-value{
        border: 1px solid #ccc;
        padding: 10px 20px;
        border-radius: 10px;
        margin-top: 30px;
        background: #ff0;
        max-width: 45%;
    }









    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
        position: relative;
        background-color: #fefefe;
        margin: auto;
        padding: 0;
        border: 1px solid #888;
        width: 60%;
        min-width: 400px;
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
        -webkit-animation-name: animatetop;
        -webkit-animation-duration: 0.4s;
        animation-name: animatetop;
        animation-duration: 0.4s;
        border-radius: 10px;
    }

    /* Add Animation */
    @-webkit-keyframes animatetop {
        from {
            top:-300px;
            opacity:0
        }
        to {
            top:0;
            opacity:1
        }
    }

    @keyframes animatetop {
        from {
            top:-300px;
            opacity:0
        }
        to {
            top:0;
            opacity:1
        }
    }

    /* The Close Button */
    .close {
        color: white;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
    .close-edit {
        color: white;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close-edit:hover,
    .close-edit:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

    .close-sms {
        color: white;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close-sms:hover,
    .close-sms:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

    .modal-header {
        padding: 20px;
        border-radius: 10px 10px 0 0;
        background-color: #eee;
        border-bottom: 1px solid #ddd;
        color: #000;
        font-size: 18px;
        text-align: center;
    }

    .modal-header span{
        color: #ff2ce2;
    }

    .modal-body {
        padding: 0;
    }

    .modal-footer {
        display: flex;
        justify-content: center;
        padding: 20px;
        border-radius: 0 0 10px 10px;
        border-top: 1px solid #ddd;
        background-color: #eee;
    }

    .booking-info{
        border-bottom: 1px solid #ddd;
        background: aliceblue;
        padding: 20px;
        font-size: 16px;
        display: flex;
        justify-content: space-evenly;
    }

    .booking-form{
        width: auto;
        margin: 3% 3% 0;
        padding: 0;
    }

    .input_box{
        width: 48%;
        padding-bottom: 2%;
    }

    .input_box label{
        display: block;
        font-weight: bold;
        font-size: 15px;
        padding-bottom: 5px;
    }

    .input_box input,
    .input_box textarea{
        width: 96%;
        height: auto;
        font-size: 14px;
        border: 1px solid #666;
        border-radius: 5px;
        padding: 8px 5px;
        color: #666;
    }

    .input_box input:focus,
    .input_box textarea:focus{
        color: #000;
        font-weight: bold;
        border: 2px solid #0019ff;
        background: #fffb2545;

    }

    .input_box.book-fee{
        /* margin-bottom: 10px;
        width: auto; */
    }

    .input_box.book-fee label{
        border-bottom: 1px solid #666;
        margin-bottom: 15px;
    }

    .input_box input[type="checkbox"]{
        width: 25px;
        height: 25px;
        margin: 0 15px;
        padding: 0;
    }

    .input_box.sms-input{
        margin: 0 auto;
        line-height: 1.9em;
    }

    .input_box.sms-input .sms-label{
        display: inline;
    }

    .input_box.sms-input a{
        font-size: 15px;
    }

    .booking-confirm{
        width: auto;
        text-align: center;
        /* margin: 0 auto; */
    }

    .booking-confirm #send_modal{
        padding: 10px 20px;
        font-size: 12px;
        color: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        background: #6fb406;
        cursor: pointer;
        text-transform: uppercase;
        margin: 0 20px;
    }

    .booking-confirm #send_modal_edit{
        padding: 10px 20px;
        font-size: 12px;
        color: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        background: #6fb406;
        cursor: pointer;
        text-transform: uppercase;
        margin: 0 20px;
    }

    .booking-confirm #send_modal:hover,
    .booking-confirm #send_modal_edit:hover{
        background: #548b00;
    }


    #send_modal_sms{
        padding: 10px 20px;
        font-size: 12px;
        color: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        background: #6fb406;
        cursor: pointer;
        text-transform: uppercase;
        margin: 0 20px;
    }
    #send_modal_sms:hover{
        background: #548b00;
    }

    .booking-delete #send_modal_delete,#close_modal_sms,
    .booking-delete #clear-booker{
        padding: 10px 20px;
        font-size: 12px;
        color: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        background: #D2312D;
        cursor: pointer;
        text-transform: uppercase;
        margin: 0 20px;
    }

    .booking-delete #send_modal_delete,#close_modal_sms:hover,
    .booking-delete #clear-booker:hover{
        background: #ff3e39;
    }



    .black-list,
    #alert-prenotazione{
        color: #000;
        background: #ff00004a;
        padding: 10px;
        border-radius: 10px;
        font-size: 13px;
        text-align: left;
        margin-bottom: 10px;
        border: 1px solid #f00;
    }

    .book-box{
        padding: 10px 10px 6px;
        line-height: 1.2em;
        font-size: 1.1em;
        position: relative;
    }

    .campionato .book-box{
        line-height: 0.9em;
    }


    .book-info-box span{
        display: block;
    }

    .divider-line{
        height: 4px;
        border-bottom: 1px solid #5561ff;
        margin-bottom: 3px;
    }


    .note-bookmark{
        position: absolute;
        top: -12px;
        right: -5px;
    }

    .book-functions-box{
        margin-top: 10px;
    }


    .button_booked{
        width: 27px;
        height: 27px;
        border: 1px solid #5561ff;
        border-radius: 100px;
        cursor: pointer;
        background-color: #5561ff;

    }

    .button_booked:hover{
        background-color: #000db4;
    }

    .button_booked.edit-booking{
        background-image: url("../../files/icons/edit_hover-svgrepo-com.png");
        background-repeat: no-repeat;
        background-size: 21px;
        background-position-x: center;
        background-position-y: center;
    }

    .button_booked.edit-booking:hover{
        background-image: url("../../files/icons/edit_hover-svgrepo-com.png");
    }

    .button_booked.send-sms{
        background-image: url("../../files/icons/sms_hover-svgrepo-com.png");
        background-repeat: no-repeat;
        background-size: 17px;
        background-position-x: center;
        background-position-y: center;
    }

    .button_booked.send-sms:hover{
        background-image: url("../../files/icons/sms_hover-svgrepo-com.png");
    }

    .left-input {
        float: left;
        margin-right: 2%;
    }

    .right-input{
        float: left;
        margin-left: 2%;
    }

    .clear{
        content:"";
        height: 0;
        clear: both;
        display: block;
    }


</style>

<h2 class="result-value"><?= count($res) ?> campi trovati</h2>

<? foreach ($res as $champ => $value): ?>
    <div class="intro pointer">
        <h3>
            <?= $value['Descrizione'] ?> / <span><?= $value['is5'] ? " Calcio a 5" : "" ?><?= $value['is7'] ? " Calcio a 7" : "" ?></span>
        </h3>
    </div>
    <div id="champ_<?= $champ ?>" style="display: none"><?= $value['Descrizione'] ?></div>
    <table class="campis-table">
        <tbody>


            <tr class="table-header days-table">
                <th class="cella">
                    <span class="book-day">Lun - <?= $range_week['regular'][1] ?></span>
                </th>
                <th class="cella">
                    <span class="book-day">Mar - <?= $range_week['regular'][2] ?></span>
                </th>
                <th class="cella">
                    <span class="book-day">Mer - <?= $range_week['regular'][3] ?></span>
                </th>
                <th class="cella">
                    <span class="book-day">Gio - <?= $range_week['regular'][4] ?></span>
                </th>
                <th class="cella">
                    <span class="book-day">Ven - <?= $range_week['regular'][5] ?></span>
                </th>
                <th class="cella">
                    <span class="book-day">Sab - <?= $range_week['regular'][6] ?></span>
                </th>
                <th class="cella">
                    <span class="book-day">Dom - <?= $range_week['regular'][7] ?></span>
                </th>
            </tr>
            <tr>
                <? foreach ($value['Giorni'] as $day => $giorni): ?>
                    <td>
                        <? foreach ($giorni as $ora => $stato): ?>

                            <? $idBooking = $stato['stato'] == "P" ? $stato['values']['id'] : ""; ?>
                            <?
                            $ora_expl = explode(":", $ora);
                            $ora = "{$ora_expl[0]}:{$ora_expl[1]}";
                            ?>

                            <div class="cella <?= $this->viewState($stato['stato']); ?> tabella" 
                                 campo_id="<?= $champ ?>" 
                                 ora="<?= $ora ?>" 
                                 data="<?= $range_week['timestamp'][$day] ?>" 
                                 stato="<?= $stato['stato'] ?>" 
                                 importo="<?= str_replace(".", ",", $stato['importo']) ?>" 
                                 id_booking="<?= $idBooking ?>"
                                 note="<?= $stato['values']['Note'] ?>"
                                 cognome="<?= $stato['values']['bookerCognome'] ?>"
                                 nome="<?= $stato['values']['bookerNome'] ?>"
                                 email="<?= $stato['values']['bookerEmail'] ?>"
                                 telefono="<?= $stato['values']['bookerTelefono'] ?>"
                                 >

                                <div class="book-box">
                                    <strong>
                                        <?= $ora ?>
                                    </strong> / 
                                    <? if ($stato['stato'] <> "C"): ?>
                                        <?= $stato['stato'] == "P" ? str_replace(".", ",", $stato['values']['Importo']) : str_replace(".", ",", $stato['importo']) ?> €
                                    <? endif; ?>
                                    <? if ($stato['stato'] == "C"): ?>
                                        <span class="status-span c-status">
                                            <?= $stato['text'] ?>
                                        </span>
                                    <? endif; ?> 



                                    <? if ($stato['stato'] == "C"): ?><br />
                                        <span class="competition-name"><strong><?= $stato['values']['NomeCampionato'] ?></strong></span>
                                        <span class="squad-name"><?= $stato['values']['SquadraCasa'] ?> - <?= $stato['values']['SquadraTrasferta'] ?></span>
                                    <? endif; ?>

                                    <? if ($stato['stato'] == "P"): ?><br />

                                        <div class="divider-line"></div>

                                        <div class="book-info-box">
                                            <span class="book-name"><strong><?= $stato['values']['bookerNome'] ?> <?= $stato['values']['bookerCognome'] ?></strong></span>
                                            <span class="book-email"><a href="mailto: <?= $stato['values']['bookerEmail'] ?>"><?= $stato['values']['bookerEmail'] ?></a></span>      
                                            <span class="book-telefono">Tel: <a href="tel: +39<?= $stato['values']['bookerTelefono'] ?>"><?= $stato['values']['bookerTelefono'] ?></a></span>
                                            <span class="book-name"><a target="_blank" href="https://<?= $_SERVER['HTTP_HOST'] ?>/apis/viewBookingCampi?prenotazione=<?= $stato['values']['Prenotazione'] ?>"><strong>Prenotazione (<?= $stato['values']['MULTI'] ?>)</strong></a></span>
                                        </div>

                                        <div class="note-bookmark">
                                            <? $hidden = trim($stato['values']['Note']) == "" ? "hidden" : "" ?>                                           
                                            <img  <?= $hidden ?> src="/files/icons/bookmark-svgrepo-com.png" alt="Visualizza nota" width="45"/>
                                        </div>

                                        <div class="divider-line"></div>

                                        <div class="book-functions-box">


                                            <? if ($stato['stato'] == "P"): ?>

                                                <button class="button_booked edit-booking" 
                                                        window="edit"
                                                        campo_id="<?= $champ ?>" 
                                                        ora="<?= $ora ?>" 
                                                        data="<?= $range_week['timestamp'][$day] ?>" 
                                                        stato="<?= $stato['stato'] ?>" 
                                                        importo="<?= str_replace(".", ",", $stato['values']['Importo']) ?>" 
                                                        id_booking="<?= $idBooking ?>"
                                                        note="<?= $stato['values']['Note'] ?>"
                                                        cognome="<?= $stato['values']['bookerCognome'] ?>"
                                                        nome="<?= $stato['values']['bookerNome'] ?>"
                                                        email="<?= $stato['values']['bookerEmail'] ?>"
                                                        telefono="<?= $stato['values']['bookerTelefono'] ?>"
                                                        pagato="<?= $stato['values']['Pagato'] ?>"
                                                        >
                                                </button>

                                                <button class="button_booked send-sms" 
                                                        window="sms"
                                                        campo_id="<?= $champ ?>" 
                                                        ora="<?= $ora ?>" 
                                                        data="<?= $range_week['timestamp'][$day] ?>" 
                                                        stato="<?= $stato['stato'] ?>" 
                                                        importo="<?= str_replace(".", ",", $stato['values']['Importo']) ?>" 
                                                        id_booking="<?= $idBooking ?>"
                                                        note="<?= $stato['values']['Note'] ?>"
                                                        cognome="<?= $stato['values']['bookerCognome'] ?>"
                                                        nome="<?= $stato['values']['bookerNome'] ?>"
                                                        email="<?= $stato['values']['bookerEmail'] ?>"
                                                        telefono="<?= $stato['values']['bookerTelefono'] ?>"
                                                        >
                                                </button>

                                            <? endif; ?>

                                            <? if ($stato['values']['Pagato']): ?>
                                                <img align="right" style="cursor: pointer" class="payed" pagato="0"  id_booking="<?= $idBooking ?>" src="/files/icons/euro-svgrepo-com.png" alt="Visualizza nota" width="25"/>
                                              
                                            <? else: ?>
                                                <img align="right" style="cursor: pointer" class="payed" pagato="1"  id_booking="<?= $idBooking ?>" src="/files/icons/euro-svgrepo-gray-com.png" alt="Visualizza nota" width="25"/>
                                            <? endif; ?>

                                        </div>



                                    <? endif; ?>

                                </div>

                            </div>
                        <? endforeach; ?>
                    </td>
                <? endforeach; ?>

            </tr>
        </tbody>
    </table>

<? endforeach; ?>




<? $html = ob_get_clean() ?>










