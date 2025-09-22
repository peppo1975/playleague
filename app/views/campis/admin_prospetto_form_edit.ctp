<!-- The Modal -->
<div id="myModalEdit" class="modal" style="display: none">

    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-edit">&times;</span>
            <h2>Modifica prenotazione campo: <span><a id="campo_modal_edit"></a></span></h2>
        </div>
        <div class="modal-body">
            <div class="booking-info">
                <div class="booking-info_date">
                    <strong>Data pren.:</strong> <a id="data_modal_edit"></a>
                </div>
                <div class="booking-info_price">
                    <!--<strong>Importo:</strong> <a id="importo_modal_edit"></a> €-->
                    <strong>Importo:</strong> <input id="importo_modal_edit"> €
                </div>
                <div class="booking-info_hour">
                    <strong>Ora pren.:</strong> <a id="ora_modal_edit"></a>
                </div>
            </div>

            <input class="edit_modal_prenotazione tags" id="editBookerId" style="display: none"/>

            <div class="booking-form">                                
                <div class="input_box left-input">
                    <label>Cognome*</label>
                    <input class="edit_modal_prenotazione tags" id="editBookerCognome"/>
                </div>
                <div class="input_box right-input">
                    <label>Nome*</label>
                    <input class="edit_modal_prenotazione" id="editBookerNome" />
                </div>
                <div class="clear"></div>

                <div class="input_box left-input">
                    <label>Email*</label>
                    <input class="edit_modal_prenotazione" id="editBookerEmail" />
                </div>
                <div class="input_box right-input">
                    <label>Telefono*</label>
                    <input class="edit_modal_prenotazione" id="editBookerTelefono" />
                </div>
                <div class="clear"></div>


                <div class="input_box left-input">
                    <label>Note</label>
                    <textarea class="edit_modal_prenotazione" id="editNote" rows="4" ></textarea>
                </div>
                <div class="input_box right-input book-fee">
                    <label>Pagato</label>
                    <input class="edit_modal_prenotazione" type="checkbox" id="editPagato">
                </div>
                <div class="clear"></div>


                <!-- //GIUSEPPE 2023-01-17 -->
                <!--<button id="annullaInserimentoDaBooking" style="display: none">CANCELLA</button>-->
                <!--<button id="clear-booker" style="display: none">CLEAR</button>-->
                <!-- -------------------- -->

                <div class="input_box">
                    <div class="black-list" style="color: red; display: none">
                        Attenzione!!! Questo utente è in BLACK LIST<br>
                        La prenotazione è bloccata<br>

                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="booking-confirm">  
                <button id="send_modal_edit">Salva modifiche</button>
            </div>
            <div class="booking-delete">  
                <button id="send_modal_delete">Cancella prenotazione</button>
            </div>

        </div>
    </div>

</div>
