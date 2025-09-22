<!-- The Modal -->
<div id="myModal" class="modal" style="display: none">

    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2>Prenotazione campo: <span><a id="campo_modal"></a></span></h2>
        </div>
        <div class="modal-body">
            <div class="booking-info">
                <div class="booking-info_date">
                    <strong>Data pren.:</strong> <a id="data_modal"></a>
                </div>
                <div class="booking-info_price">
                    <!--<strong>Importo:</strong> <a id="importo_modal"></a> €-->
                    <strong>Importo:</strong> <input id="importo_modal"> €
                </div>
                <div class="booking-info_hour">
                    <strong>Ora pren.:</strong> <a id="ora_modal"></a>
                </div>
            </div>

            <div class="booking-form">                                
                <div class="input_box left-input">
                    <label>Cognome*</label>
                    <input class="modal_prenotazione tags" id="bookerCognome"/>
                </div>
                <div class="input_box right-input">
                    <label>Nome*</label>
                    <input class="modal_prenotazione" id="bookerNome" />
                </div>
                <div class="clear"></div>

                <div class="input_box left-input">
                    <label>Email*</label>
                    <input class="modal_prenotazione" id="bookerEmail" />
                </div>
                <div class="input_box right-input">
                    <label>Telefono*</label>
                    <input class="modal_prenotazione" id="bookerTelefono" />
                </div>
                <div class="clear"></div>

                <div class="input_box left-input">
                    <label>Note</label>
                    <textarea class="modal_prenotazione" id="Note" rows="4" ></textarea>
                </div>

                
                <div class="input_box  right-input" id="box-info-date-occupate" style="display: none;color: red">
                    <label>Date occupate da altri eventi</label>
                    <!--<textarea class="modal_prenotazione" id="Note" rows="4" ></textarea>-->
                    <div id="info-date-occupate">

                    </div>

                </div>

                <div class="input_box  right-input" id="box-info-date-libere">
                    <label style="color: green">Date libere</label>
                    <div style="border: 1px solid green; border-radius: 5px; padding: 10px;">  
                            <div id="info-date-libere"></div>
                    </div>
                </div>         
                <div class="clear"></div>



                <div class="input_box left-input book-fee" style="padding: 0 0 20px;">
                    <div style="border: 1px solid #666; border-radius: 5px; padding: 10px;"> 
                        <input style="padding: 10px 0; float:left; margin:0 7px 0 0;" class="edit_modal_prenotazione" id="check_recursive" type="checkbox">
                        <span  style="font-size: 14px; float:left; padding-top: 4px;"><b>Noleggio ricorsivo</b></span>
                        <div class="clear"></div>
                        <!--<input class="edit_modal_prenotazione" type="checkbox" id="editPagato">-->
                    </div>
                </div>
                <div class="clear"></div>
               
                <div id="date_recursive" class="left-input" style="display: none; margin-bottom:20px;">
                    <div style="border: 1px solid #666; border-radius: 5px; padding: 10px;"> 
                        <span style="font-size: 14px; display: block; padding-bottom:10px;">Dal <b><a id="data_modal_init"></a></b> ogni <b><a style="text-transform:lowercase;" id="giorno_settimana"></a></b> alle ore: <b><a id="ora_giorno"></a></b></span>
                        <span style="font-size: 14px;">Fino al:</span>
                        <input style="font-size: 14px;" type="date" id="weekpicker_to" min="<?= date("Y-m-d") ?>" class="date-interval" campo_id="" ora="" >
                    </div>
                </div>

                <div class="clear"></div>

                <!-- //GIUSEPPE 2023-01-17 -->
                <!--<button id="annullaInserimentoDaBooking" style="display: none">CANCELLA</button>-->

                <!-- -------------------- -->

                <div class="input_box">
                    <!--<div class="black-list" style="color: red; display: none">-->
                    <div class="black-list" style="display: none">
                        Attenzione!
                        <br>
                        L'utente selezionato è in blacklist.
                    </div>

                     <div id="alert-prenotazione">
                        
                    </div>                   
                </div>
            </div>
        </div>
        <div class="modal-footer">

            <div class="booking-confirm">  
                <button id="send_modal">Conferma prenotazione</button>
            </div>
            <div class="booking-delete">
                <button id="clear-booker" style="display: none">Reset campi</button>
            </div>

        </div>
    </div>

</div>
