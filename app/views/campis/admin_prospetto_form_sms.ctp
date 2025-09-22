<!-- The Modal -->
<div id="myModalSms" class="modal" style="display: none">

    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-sms">&times;</span>
            <h2>SMS: <span><a id="campo_modal_sms"></a></span></h2>
        </div>
        <div class="modal-body">
            <div class="booking-info">
                <div class="booking-info_date">
                    <strong>Data pren.:</strong> <a id="data_modal_sms"></a>
                </div>
                <div class="booking-info_price">
                    <strong>Importo:</strong> <a id="importo_modal_sms"></a> €
                </div>
                <div class="booking-info_hour">
                    <strong>Ora pren.:</strong> <a id="ora_modal_sms"></a>
                </div>
            </div>

            <input class="modal_prenotazione_sms tags" id="smsBookerId" style="display: none"/>

            <div class="booking-form">                                
                <div class="input_box sms-input">
                    <label class="sms-label">Cognome:</label> <a id="smsBookerCognome"></a><br />
                    <label class="sms-label">Nome:</label> <a id="smsBookerNome"></a><br />
                    <label class="sms-label">Email:</label> <a id="smsBookerEmail"></a><br />
                    <label class="sms-label">Telefono:</label> <a id="smsBookerTelefono"></a>
                    <input  class="modal_prenotazione_sms" id="smsBookerTelefonoInput" style="display: none"/>
                </div>

                <div class="input_box sms-input">
                    <label>Testo SMS (max 160 caratteri):</label>
                    <textarea maxlength="160" class="modal_prenotazione_sms" id="smsNote" rows="4" ></textarea>
                </div>

                <div class="input_box sms-input" style="text-align: center;">
                    <a id="smsResponse"></a>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <div class="booking-confirm">  
                <button id="send_modal_sms">INVIA SMS</button>
            </div>
            <div class="booking-delete">  
                <button id="close_modal_sms" class="close-sms">ESCI</button>
            </div>

        </div>



    </div>

</div>
