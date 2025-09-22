
<?= $this->element("/backend/edit_scripts"); ?>

<?= $this->Form->create('Campi', array('action' => 'edit', 'prefix' => 'admin', 'class' => 'formAdd', 'type' => 'file')); ?>

<div class="form_header">

    <h2>Modifica campo: <span><?= $this->data['Campi']['Descrizione']; ?></span></h2>
    <ul>
        <li><?= $this->Form->submit('reset campi', array('type' => 'reset', 'div' => false)); ?></li>
        <li><?= $this->Form->submit('annulla', array('type' => 'button', 'div' => false, 'id' => 'formReset')); ?></li>
        <li><?= $this->Form->submit('modifica', array('type' => 'submit', 'div' => false)); ?></li>								
    </ul>
    <div class="clear"></div>

</div><!-- close form_header -->
<?= $this->Form->input('Campo'); ?>	

<div class="tab-container">

    <ul class="tab-selector">

        <li data-index="1" class="selected"><a href="javascript:;">Gestione campo</a></li>
        <li data-index="2"><a href="javascript:;">Gestione orari</a></li>
        <li data-index="3"><a href="javascript:;">Gestione prenotazioni</a></li>			

    </ul>

    <div class="tab-page table-container table-affitti" data-index="3" style="overflow-x: scroll; overflow-y: hidden;">	
        <?
        $campo = $booking['campo'];
        $giorni = $booking['giorni'];

        $dow['1'] = 'Lunedì';
        $dow['2'] = 'Martedì';
        $dow['3'] = "Mercoledì";
        $dow['4'] = 'Giovedì';
        $dow['5'] = 'Venerdì';
        $dow['6'] = 'Sabato';
        $dow['7'] = 'Domenica';
        ?>

        <script type="text/javascript">

            function isValidEmail(str)
            {
                return (str.indexOf(".") > 2) && (str.indexOf("@") > 0);
            }


            var global_booking = null;

            $(function ()
            {

                $(".switch-day").click(function ()
                {


                    var day = $(this).attr('data-date');
                    var id = $(this).attr('data-id');

                    var me = $(this);

                    $.get('/admin/campis/switchday/' + id + '/' + day, function (ret)
                    {


                        me.attr('title', ret.text).attr('data-tip-title', ret.text);
                        me.find('img').attr('src', ret.img);

                    }, 'json');

                });

                $(".js_booking").click(function ()
                {

                    if (!$('#booking-box').is(':visible'))
                        $('#booking-box').slideDown('fast');
                    else
                        $('#booking-box').slideUp('fast');


                });

                $(".booking-allowed").die('click').live('click', function ()
                {


                    global_booking = $(this);


                    $(".booking-allowed").not($(this)).removeAttr('data-selected');
                    $(this).attr('data-selected', '1');

                    // $(".selected-importo").text($(this).attr('data-importo'));
                    // $(".selected-data").text($(this).attr('data-giorno-it'));

                    // $(".selected-impianto").text('<?= $campo['Campi']['Descrizione']; ?> dalle ore ' + $(this).attr('data-ora') + " alle ore " + $(this).attr('data-ora-plus'));

                    // $(".booking-data").fadeIn(500);

                    $.post('/campis/saveBookingSession', {

                        'importo': $(this).attr('data-importo'),
                        'data': $(this).attr('data-giorno-it'),
                        'campo': '<?= $campo['Campi']['Campo']; ?>',
                        'impianto': '<?= $campo['Campi']['Descrizione']; ?>',
                        'ora': 'dalle ore ' + $(this).attr('data-ora') + " alle ore " + $(this).attr('data-ora-plus'),
                        'ora_real': $(this).attr('data-ora'),
                        'data_real': $(this).attr('data-giorno')

                    }, function ()
                    {

                        timmy_load('/admin/campis/booking_timmy');

                    });



                });

                $("#bookingData").submit(function ()
                {
                    $(this).find('.errors').css('opacity', 0);
                    var error = 0;

                    var email = $(this).find('input[name="bookerEmail"]').val();

                    if (!isValidEmail(email))
                        error = 2;

                    $(this).find(".required input").each(function ()
                    {

                        if ($.trim($(this).val()) == '')
                            error = 1;

                    });

                    if (error == 2)
                        $(this).find('.errors').html('Inserire un indirizzo e-mail valido!').animate({'opacity': 1}, 500);
                    if (error == 1)
                        $(this).find('.errors').html('Compilare tutti i campi obbligatori!').animate({'opacity': 1}, 500);

                    if (error == 0)
                    {

                        $.post(
                                '/campis/bookingSend', {

                                    'bookerNome': $(this).find('input[name="bookerNome"]').val(),
                                    'bookerCognome': $(this).find('input[name="bookerCognome"]').val(),
                                    'bookerEmail': $(this).find('input[name="bookerEmail"]').val(),
                                    'bookerTelefono': $(this).find('input[name="bookerTelefono"]').val(),
                                    'Data': $(".booking-allowed[data-selected=1]").attr('data-giorno'),
                                    'Ora': $(".booking-allowed[data-selected=1]").attr('data-ora'),
                                    'campo_id': <?= $campo['Campi']['Campo']; ?>,
                                    'Importo': $(".booking-allowed[data-selected=1]").attr('data-importo')


                                }, function (data)
                        {

                            $(".bookingResult").fadeOut(200, function ()
                            {

                                $(this).html(data).fadeIn(200, function ()
                                {


                                });

                            });

                            return false;

                        }
                        , 'html');

                    }

                    return false;

                });

                $('#export-hours').die('click').live('click', function ()
                {

                    var me = $(this);

                    $.get('/admin/campis/exportHour/' + me.attr('data-id') + '/' + $('#CampiDateFilterStart').val() + '/' + $('#CampiDateFilterEnd').val(), function (data)
                    {

                        location.href = data.link;

                    }, 'json');

                    return false;

                });

                $('#CampiDateFilterEnd, #CampiDateFilterStart').die('change').live('change', function ()
                {

                    var me = $(this);
                    var start = $('#CampiDateFilterStart');

                    if (me.val() != "" && start.val() != "")
                    {

                        $('#booking').find('th,td').each(function ()
                        {

                            var element = $(this);
                            var time = parseInt(element.attr('data-id'));

                            if (time >= parseInt(start.val()) && time <= parseInt(me.val()))
                            {

                                element.show();

                            } else
                            {

                                element.hide();

                            }

                        });

                    }

                });

                $('.booking-disabled').die('click').live('click', function (data)
                {

                    var me = $(this);

                    if (me.attr('data-id') == 0)
                    {
                        alert('Impossibile rimuovere la prenotazione');
                        return false;
                    }

                    if (confirm('Sei sicuro di voler eliminare la prenotazione nell\'ora corrente intestata a ' + me.attr('data-nome') + ' - ' + me.attr('data-email') + '?'))
                    {

                        $.get('/admin/campis/deleteBooking/' + me.attr('data-id'), function ()
                        {

                            //if(data.deleted == 1) {

                            me.addClass('booking-allowed').removeClass('booking-disabled');

                            /*
                             } else {
                             
                             alert('Impossibile rimuovere la prenotazione, riprovare');
                             
                             }*/

                        }, 'json');

                    }

                });

            });

        </script>

        <?
        $options = array();

        foreach ($giorni as $i => $giorno)
        {

            $options[strtotime($giorno['Data'] . " 00:00:01")] = substr($dow[$giorno['DayOfWeek']], 0, 3) . " " . date("d/m", strtotime($giorno['Data'] . " 00:00:01"));
        }
        ?>

        <?= $this->Form->input('date_filter_start', array('label' => 'Data inizio', 'type' => 'select', 'options' => $options, 'empty' => true)); ?>			
        <?= $this->Form->input('date_filter_end', array('label' => 'Data fine', 'type' => 'select', 'options' => $options, 'empty' => true)); ?>						

        <div class="input" style="margin-top: 22px;">			
            <a href="#" data-id="<?= $this->data['Campi']['Campo']; ?>" id="export-hours">Esporta</a>			
        </div>

        <div class="input" style="margin-left: 10px; margin-top: 22px;">			
            <a href="#" id="goCentr">Vai alla settimana corrente</a>			
        </div>

        <script type="text/javascript">

            $(function ()
            {


                $("#goCentr").click(function ()
                {


                    var w = $("table#booking").width();

                    $(".table-affitti").scrollLeft((w / 2) - (933 / 2) - ((28 * 7) / 2));

                });


            });

        </script>

        <div class="clear"></div>

        <table id="booking" class="">
            <tr class="table-header">

                <? $max = 0; ?>

                <? foreach ($giorni as $i => $giorno): ?>

                    <th data-id="<?= strtotime($giorno['Data'] . " 00:00:01"); ?>">

                        <?= substr($dow[$giorno['DayOfWeek']], 0, 3); ?><br />
                        <?= date("d/m", strtotime($giorno['Data'] . " 00:00:01")); ?>



                        <? $disabled = 0; ?>
                        <?
                        foreach ($campo['CampiDisabled'] as $date)
                        {

                            if ($date['giorno'] == $giorno['Data'])
                                $disabled = 1;
                        }
                        ?>


                        <? if (count($giorno['Orari']) > $max) $max = count($giorno['Orari']); ?>

                        <? $disabled_text[0] = 'Giorno abilitato alle prenotazioni'; ?>
                        <? $disabled_text[1] = 'Giorno disabilitato alle prenotazioni'; ?>
                        <a data-top="-25px;" href="#" class="switch-day" data-id="<?= $campo['Campi']['Campo']; ?>" data-date="<?= $giorno['Data']; ?>" rel="timmytip" title="<?= $disabled_text[$disabled]; ?>" >
                            <img src="/img/timmyshare/icon_disabled_<?= $disabled; ?>.gif" alt="" />
                        </a>
                    </th>

                    </th>

                <? endforeach; ?>

            </tr>


            <? for ($i = 0; $i < $max; $i++): ?>



                <tr class="<?= (!($i % 2)) ? 'alternate' : ''; ?>" >

                    <? foreach ($giorni as $k => $giorno): ?>

                        <td data-id="<?= strtotime($giorno['Data'] . " 00:00:01"); ?>">

                            <? if (isset($giorno['Orari'][$i])): ?>

                                <? if ($giorno['Orari'][$i]['Occupato'] == 1): ?>

                                    <? if ($giorno['Orari'][$i]['Info'] != ""): ?>

                                    <? endif; ?>

                                    <span class="booking-disabled" data-id="<?= (isset($giorno['Orari'][$i]['bookerId'])) ? $giorno['Orari'][$i]['bookerId'] : 0; ?>" data-nome="<?= (isset($giorno['Orari'][$i]['bookerNome'])) ? $giorno['Orari'][$i]['bookerNome'] : ""; ?> <?= (isset($giorno['Orari'][$i]['bookerCognome'])) ? $giorno['Orari'][$i]['bookerCognome'] : ""; ?>" data-email="<?= (isset($giorno['Orari'][$i]['bookerEmail'])) ? $giorno['Orari'][$i]['bookerEmail'] : ""; ?>" data-giorno="<?= $giorno['Data']; ?>" data-giorno-it="<?= $giorno['Data_it']; ?>"  data-ora="<?= substr($giorno['Orari'][$i]['Ora'], 0, -3); ?>" data-ora-plus="<?= date("H:i", strtotime("+1 hour", strtotime("2011-01-01 " . $giorno['Orari'][$i]['Ora']))); ?>" data-importo="<?= $giorno['Orari'][$i]['Importo']; ?>">					
                                        <?= substr($giorno['Orari'][$i]['Ora'], 0, -3); ?>
                                    </span>

                                    <? if ($giorno['Orari'][$i]['Info'] != ""): ?>

                                        <a data-top="-25px;" href="#" rel="timmytip" title="<?= htmlentities($giorno['Orari'][$i]['Info']); ?>" >
                                            <img src="/img/website/icon_goals.png" width="16" haight=="16" alt="" />
                                            <a>

                                            <? endif; ?>

                                            <? if ($giorno['Orari'][$i]['Info'] != ""): ?>

                                            <? endif; ?>

                                        <? else: ?>


                                            <span class="<? if (strtotime($giorno['Data']) >= strtotime(date("Y-m-d"))): ?>booking-allowed<? endif; ?>" data-oggi="<?= date("Y-m-d"); ?> data-giorno="<?= $giorno['Data']; ?>" data-giorno-it="<?= $giorno['Data_it']; ?>"  data-ora="<?= substr($giorno['Orari'][$i]['Ora'], 0, -3); ?>" data-ora-plus="<?= date("H:i", strtotime("+1 hour", strtotime("2011-01-01 " . $giorno['Orari'][$i]['Ora']))); ?>" data-importo="<?= $giorno['Orari'][$i]['Importo']; ?>"><?= substr($giorno['Orari'][$i]['Ora'], 0, -3); ?></span>


                                        <? endif; ?>

                                    <? else: ?>

                                        &nbsp;

                                    <? endif; ?>
                                    </td>

                                <? endforeach; ?>

                                </tr>



                            <? endfor; ?>


                            </table>

                            </div>

                            <div class="tab-page tab-selected" data-index="1">	

                                <?= $this->Form->input('Descrizione', array('label' => 'Nome campo', 'type' => 'text')); ?>
                                <div class="clear"></div>


                                <?= $this->Form->input('claim', array('label' => 'Breve descrizione per prossime manifestazioni', 'type' => 'text', 'class' => 'large')); ?>

                                <div class="clear"></div>
                                <script type="text/javascript">
                                    if (typeof $ != "undefined")
                                    {
                                        $(function ()
                                        {

                                            $("#checkMidland").change(function ()
                                            {
                                                if ($(this).is(':checked'))
                                                {
                                                    $("#CampiIsMidland").val(1);
                                                } else
                                                {
                                                    $("#CampiIsMidland").val(0);
                                                }
                                            });
                                            $("#check5").change(function ()
                                            {
                                                if ($(this).is(':checked'))
                                                {
                                                    $("#CampiIs5").val(1);
                                                } else
                                                {
                                                    $("#CampiIs5").val(0);
                                                }
                                            });
                                            $("#check7").change(function ()
                                            {
                                                if ($(this).is(':checked'))
                                                {
                                                    $("#CampiIs7").val(1);
                                                } else
                                                {
                                                    $("#CampiIs7").val(0);
                                                }
                                            });
                                            $("#check11").change(function ()
                                            {
                                                if ($(this).is(':checked'))
                                                {
                                                    $("#CampiIs11").val(1);
                                                } else
                                                {
                                                    $("#CampiIs11").val(0);
                                                }
                                            });

                                            //GIUSEPPE 2016-12-13
                                            $("#checkTennis").change(function ()
                                            {
                                                if ($(this).is(':checked'))
                                                {
                                                    $("#CampiIsTennis").val(1);
                                                } else
                                                {
                                                    $("#CampiIsTennis").val(0);
                                                }
                                            });


                                            $("#checkEsclusive").change(function ()
                                            {
                                                if ($(this).is(':checked'))
                                                {
                                                    $("#CampiIsEsclusive").val(1);
                                                } else
                                                {
                                                    $("#CampiIsEsclusive").val(0);
                                                }
                                            });

                                        });
                                    }
                                </script>			

                                <div class="input">
                                    <?
                                    if ($this->data['Campi']['isMidland'] == 1)
                                    {
                                        $checked = 'checked="checked"';
                                    }
                                    else
                                    {
                                        $checked = '';
                                    }
                                    ?>			
                                    <label for="checkMidland">Campo midland</label>
                                    <input type="checkbox" <?= $checked; ?> id="checkMidland" />
                                </div>
                                <div class="input">
                                    <?
                                    if ($this->data['Campi']['is5'] == 1)
                                    {
                                        $checked = 'checked="checked"';
                                    }
                                    else
                                    {
                                        $checked = '';
                                    }
                                    ?>			
                                    <label for="check5">Campo a 5</label>
                                    <input type="checkbox" <?= $checked; ?> id="check5" />
                                </div>
                                <div class="input">
                                    <?
                                    if ($this->data['Campi']['is7'] == 1)
                                    {
                                        $checked = 'checked="checked"';
                                    }
                                    else
                                    {
                                        $checked = '';
                                    }
                                    ?>			
                                    <label for="check7">Campo a 7</label>
                                    <input type="checkbox" <?= $checked; ?> id="check7" />
                                </div>	

                                <div class="input">
                                    <?
                                    if ($this->data['Campi']['is11'] == 1)
                                    {
                                        $checked = 'checked="checked"';
                                    }
                                    else
                                    {
                                        $checked = '';
                                    }
                                    ?>			
                                    <label for="check11">Campo a 11</label>
                                    <input type="checkbox" <?= $checked; ?> id="check11" />
                                </div>	

                                <!-- //GIUSEPPE 2016-12-13-->
                                <div class="input">
                                    <?
                                    if ($this->data['Campi']['isTennis'] == 1)
                                    {
                                        $checked = 'checked="checked"';
                                    }
                                    else
                                    {
                                        $checked = '';
                                    }
                                    ?>			
                                    <label for="checkTennis">Campo Tennis</label>
                                    <input type="checkbox" <?= $checked; ?> id="checkTennis" />
                                </div>
                                <!-- //------------- -->

                                <div class="input">
                                    <?
                                    if ($this->data['Campi']['isEsclusive'] == 1)
                                    {
                                        $checked = 'checked="checked"';
                                    }
                                    else
                                    {
                                        $checked = '';
                                    }
                                    ?>			
                                    <label for="checkEsclusive">In esclusiva</label>
                                    <input type="checkbox" <?= $checked; ?> id="checkEsclusive" />
                                </div>				

                                <?= $this->Form->input('isMidland', array('type' => 'hidden')); ?>
                                <?= $this->Form->input('is5', array('type' => 'hidden')); ?>
                                <?= $this->Form->input('is11', array('type' => 'hidden')); ?>	

                                <?= $this->Form->input('is7', array('type' => 'hidden')); ?>	

                                <?= $this->Form->input('isTennis', array('type' => 'hidden')); //GIUSEPPE 2016-12-13 ?>	

                                <?= $this->Form->input('isEsclusive', array('type' => 'hidden')); ?>			

                                <div class="clear"></div>

                                <?= $this->Form->input('Importo', array('label' => 'Importo', 'type' => 'text')); ?>

                                <div class="clear"></div>		

                                <h4>Tipologia sport</h4><div class="clear"></div>

                                <div class="input">
                                    <label for="check0">Calcio</label>
                                    <input type="checkbox" id="check0" name="data[Campi][check0]" value="1" <? if ($this->data['Campi']['check0'] == 1): ?>checked="checked"<? endif; ?> />
                                </div>
                                <div class="input">
                                    <label for="check1">Tennis</label>
                                    <input type="checkbox" id="check1" name="data[Campi][check1]" value="1" <? if ($this->data['Campi']['check1'] == 1): ?>checked="checked"<? endif; ?>/>
                                </div>
                                <div class="input">
                                    <label for="check2">Pallavolo</label>
                                    <input type="checkbox" id="check2" name="data[Campi][check2]" value="1" <? if ($this->data['Campi']['check2'] == 1): ?>checked="checked"<? endif; ?>/>
                                </div>	

                                <div class="input">
                                    <label for="check5">Basket</label>
                                    <input type="checkbox" id="check5" name="data[Campi][check5]" value="1" <? if ($this->data['Campi']['check5'] == 1): ?>checked="checked"<? endif; ?>/>
                                </div>	

                                <div class="clear"></div>
                                <h4>Tipologia impianto<br class="clear"></h4><div class="clear"></div>


                                <div class="input">
                                    <label for="checkEsclusive">All'aperto</label>
                                    <input type="checkbox" id="check3" name="data[Campi][check3]" value="1" <? if ($this->data['Campi']['check3'] == 1): ?>checked="checked"<? endif; ?> />
                                </div>					


                                <div class="input">
                                    <label for="checkEsclusive">Al chiuso</label>
                                    <input type="checkbox" id="check4" name="data[Campi][check4]" value="1" <? if ($this->data['Campi']['check4'] == 1): ?>checked="checked"<? endif; ?> />
                                </div>		


                                <div class="clear"></div><br />	

                                <? /*
                                  <?=$this->Form->input('Importo', array('label' => 'Importo', 'type' => 'text'));?>
                                  <? if($this->data['Campi']['check1'] == 1): ?>
                                  <?=$this->Form->input('Importo1', array('label' => 'Importo (Tennis)', 'type' => 'text'));?>
                                  <? endif; ?>
                                  <? if($this->data['Campi']['check2'] == 1): ?>
                                  <?=$this->Form->input('Importo2', array('label' => 'Importo (Pallavolo)', 'type' => 'text'));?>
                                  <? endif; ?>
                                 */ ?>



                                <div class="clear"></div>
                                <div class="post_content">

                                    <?= $this->element('/backend/ckeditor', array('name' => 'descrizione_campo', 'title' => 'Descrizione campo')); ?>

                                </div>

                                <div class="clear"></div>				

                                <? //=$this->Form->input('Importo', array('label' => 'Importo quota', 'type' => 'text'));?>

                                <?= $this->Form->input('Indirizzo', array('label' => 'Indirizzo', 'type' => 'text')); ?>
                                <?= $this->Form->input('Citta', array('label' => 'Città', 'type' => 'text')); ?>
                                <?= $this->Form->input('Provincia', array('label' => 'Provincia', 'type' => 'text')); ?>
                                <?= $this->Form->input('Telefono', array('label' => 'Telefono', 'type' => 'text')); ?>
                                <?= $this->Form->input('Email', array('label' => 'Email', 'type' => 'text')); ?>

                                <div class="clear"></div>	

                                <h3>Google map</h3>

                                <?= $this->Form->input('latitudine', array('label' => 'Latitudine', 'type' => 'text')); ?>
                                <?= $this->Form->input('longitudine', array('label' => 'Longitudine', 'type' => 'text')); ?>

                                <? if ($group_id == 5): ?>

                                    <div class="clear"></div>

                                    <?= $this->Form->input('Campi.link', array('label' => 'Google link', 'type' => 'text', 'class' => 'big')); ?>

                                <? endif; ?>			

                                <div class="clear"></div>			

                                <h3>Gestore campo</h3>

                                <?= $this->Form->input('CognomeGestore', array('label' => 'Cognome', 'type' => 'text')); ?>

                                <?= $this->Form->input('NomeGestore', array('label' => 'Nome', 'type' => 'text')); ?>

                                <?= $this->Form->input('EmailGestore', array('label' => 'Email', 'type' => 'text')); ?>

                                <?= $this->Form->input('CellulareGestore', array('label' => 'Cellulare', 'type' => 'text')); ?>

                                <div class="clear"></div>
                                <h3>Gestore campo 2 (opzionale)</h3>

                                <?= $this->Form->input('CognomeGestore2', array('label' => 'Cognome', 'type' => 'text')); ?>

                                <?= $this->Form->input('NomeGestore2', array('label' => 'Nome', 'type' => 'text')); ?>

                                <?= $this->Form->input('EmailGestore2', array('label' => 'Email', 'type' => 'text')); ?>

                                <?= $this->Form->input('CellulareGestore2', array('label' => 'Cellulare', 'type' => 'text')); ?>
                                <div class="clear"></div>

                                <h3>Allegati</h3>

                                <div id="formUploadContainer">	

                                    <script type="text/javascript">
                                        if (typeof $ != "undefined")
                                        {
                                            $(function ()
                                            {
                                                var upload = $("#UploadTag");
                                                var desc = $("#UploadDescription");
                                                upload.change(function ()
                                                {
                                                    if (upload.val() == '')
                                                    {
                                                        desc.parent('div').find('label').text('Descrizione');
                                                        desc.removeClass('big');
                                                        desc.val('');
                                                    } else
                                                    {
                                                        desc.parent('div').find('label').text('Link');
                                                        desc.addClass('big');
                                                        desc.val('http://');
                                                    }
                                                });
                                            });
                                        }
                                    </script>			

                                    <?=
                                    $backend->getFiles('campi_id', $this->data['Campi']['Campo'], array(
                                        'tag' => array('' => 'Allegato', 'link' => 'Collegamento'),
                                    ));
                                    ?>

                                </div>				

                            </div>

                            <div class="tab-page" data-index="2">	

                                <?
                                //debug($orari);

                                $giorni = array(
                                    1 => 'Lunedì',
                                    2 => 'Martedì',
                                    3 => 'Mercoledì',
                                    4 => 'Giovedì',
                                    5 => 'Venerdì',
                                    6 => 'Sabato',
                                    7 => 'Domenica',
                                );
                                ?>

                                <div class="new_hour">

                                    <script type="text/javascript">
                                        if (typeof $ != "undefined")
                                        {
                                            $(function ()
                                            {

                                                $("#CampiOrariImporto").keyup(function ()
                                                {
                                                    $(this).val($(this).val().replace(',', '.'));
                                                });

                                                $('.new_hour').delegate("#orarioAdd", "click", function ()
                                                {

                                                    $('.new_hour').find('.error-message').remove();

                                                    var myString = $("#CampiOrariImporto").val();

                                                    currency = parseFloat(myString.replace(/^[^\d\.]*/, ''));
                                                    valid = !isNaN(currency);

                                                    if (!valid)
                                                    {

                                                        $("#CampiOrariImporto").parent('div').append('<div class="error-message">Campo errato.</div>');

                                                        return false;

                                                    }

                                                    var data = $("input.orariClass, select.orariClass").serialize();

                                                    $.post('/admin/campis/orariAdd', data, function (ret)
                                                    {

                                                        if (ret.error == 0)
                                                        {

                                                            if ($("#CampiOrariId").val() == undefined)
                                                            { //ADD

                                                                var tr = $('<tr>').attr('data-id', ret.data.CampiOrari['id']);
                                                                var add_edit = 'aggiunto';

                                                            } else
                                                            {//EDIT

                                                                var tr = $('#orari').find('tr[data-id=' + $("#CampiOrariId").val() + ']').empty();
                                                                var add_edit = 'modificato';

                                                            }

//                                                            var field = ["Giorno", "Ora", "Importo"];
                                                            var field = ["Giorno", "Ora", "Durata", "OraFine", "Importo"];

                                                            for (i = 0; i < field.length; i++)
                                                            {

                                                                var td = $('<td>').text(ret.data.CampiOrari[field[i]]);

                                                                tr.append(td);

                                                            }

                                                            /*td opzioni*/

                                                            var td_option = $('<td>').html(
                                                                    '<a href="javascript:;" class="orariEdit">' +
                                                                    '<img src="/img/timmyshare/icon_edit.png" />' +
                                                                    '</a>' +
                                                                    '&nbsp;' +
                                                                    '<a href="javascript:;" class="orariDelete">' +
                                                                    '<img src="/img/timmyshare/icon_delete.png" />' +
                                                                    '</a>'
                                                                    );
                                                            tr.append(td_option);

                                                            alert('Orario ' + add_edit + ' con successo.');

                                                            tr.insertAfter($("#orari").find('tr:first'));

                                                            setDay();

                                                            reset();

                                                        } else
                                                        {

                                                            if (ret.error == 'occupato')
                                                            {

                                                                var error = $('<div>').addClass('error-message').text('Campo gia occupato nell\' ora seguente.');
                                                                $('.new_hour').find('.time').append(error);

                                                            } else
                                                            {

                                                                for (field in ret.data)
                                                                {

                                                                    var error = $('<div>').addClass('error-message').text(ret.data[field]);
                                                                    $("#CampiOrari" + field).parent('div').append(error);

                                                                }

                                                            }

                                                        }

                                                    }, 'json');

                                                });

                                                //Delete

                                                $('.formAdd').delegate(".orariDelete", "click", function ()
                                                {

                                                    var tr = $(this).parents('tr');
                                                    var delete_id = tr.attr('data-id');

                                                    if (confirm("Sei sicuro di voler eliminare?"))
                                                    {

                                                        $.get('/admin/campis/orariDelete/' + delete_id, function (ret)
                                                        {

                                                            if (ret.delete == 1)
                                                            {

                                                                alert('Orario eliminato con successo.');
                                                                tr.remove();

                                                            } else
                                                            {

                                                                alert('Impossibile eliminare orario');

                                                            }

                                                        }, 'json');

                                                    }

                                                });

                                                //Edit Form

                                                $('.orariEdit').live('click', function ()
                                                {

                                                    $('.inputShort').remove();

                                                    var tr = $(this).parents('tr');
                                                    var edit_id = tr.attr('data-id');

                                                    tr.find('td:not(:last)').each(function (index)
                                                    {

                                                        var td = $(this);

                                                        var col = td.prevAll().length;
                                                        var headerObj = td.parents('table').find('th').eq(col);

                                                        console.log(headerObj.text());

                                                        if (headerObj.text() == 'Ora')
                                                        {

                                                            var tmp = td.text().split(':');

                                                            $("#CampiOrariOraHour").val(tmp[0] + ":" + tmp[1]); //GIUSEPPE 2023-04-10

//                                                            $("#CampiOrariOraHour").val(tmp[0]);
//                                                            $("#CampiOrariOraMin").val(tmp[1]);

                                                        } else if (headerObj.text() == 'Giorno')
                                                        {

                                                            var days = {"Lunedì": "1", "Martedì": "2", "Mercoledì": "3", "Giovedì": "4", "Venerdì": "5", "Sabato": "6", "Domenica": "7"}
                                                            $("#CampiOrariGiorno").val(days[td.text()]);

                                                        } else if (headerObj.text() == 'Durata') {  //GIUSEPPE 2023-04-10
                                                            // console.log(headerObj.text());
                                                            var durata = td.text();
                                                            $("#CampiOrariDurata").val(durata);
//                                                             console.log(tmp);
                                                        } //GIUSEPPE 2023-04-10
                                                        else
                                                        {

                                                            $("#CampiOrari" + headerObj.text()).val(td.text());

                                                        }

                                                    });


                                                    //Input id spesa
                                                    var input_edit = $('<input value="' + edit_id + '">').addClass('hidden')
                                                            .addClass('orariClass')
                                                            .addClass('editStatus')
                                                            .addClass('inputShort')
                                                            .attr('id', 'CampiOrariId')
                                                            .attr('name', 'data[CampiOrari][id]');

                                                    $('.new_hour').prepend(input_edit);

                                                    //Input reset
                                                    var submit = $('.new_hour').find('div.submit');
                                                    var reset = submit.clone();
                                                    reset.addClass('input').addClass('inputShort').removeClass('submit');
                                                    reset.find('input').attr('id', 'orariReset')
                                                            .addClass('editStatus')
                                                            .val('annulla');

                                                    reset.insertAfter(submit);

                                                    //Button edit

                                                    $("#orariAdd").val('modifica');

                                                });

                                                $('.new_hour').delegate("#orariReset", "click", function ()
                                                {

                                                    reset();

                                                });

                                                //Reset

                                                function reset()
                                                {

                                                    $("#CampiOrariGiorno").val(1);
                                                    $("#CampiOrariImporto").val('');
                                                    $('.editStatus').remove();

                                                    $("#orarioAdd").val('aggiungi');

                                                }

                                                //Converti giorni in char

                                                function setDay()
                                                {

                                                    var days = {"1": "Lunedì", "2": "Martedì", "3": "Mercoledì", "4": "Giovedì", "5": "Venerdì", "6": "Sabato", "7": "Domenica"}

                                                    $("#orari tr").find('td:first').each(function ()
                                                    {

                                                        var val = $(this).text();
                                                        var new_val = days[val];

                                                        $(this).text(new_val);

                                                    });

                                                }

                                                setDay();

                                            });
                                        }
                                    </script>

                                    <?= $this->Form->input('CampiOrari.Giorno', array('type' => 'select', 'class' => 'orariClass', 'options' => $giorni)); ?>

                                    <? //=$this->Form->input('CampiOrari.Ora', array('timeFormat' => 24, 'class' => 'orariClass')); ?>

                                    <div class="input text required">
                                        <label for="CampiOrariImporto">Ora</label>
                                        <input name="data[CampiOrari][Ora][hour]" type="time" class="orariClass" maxlength="2" id="CampiOrariOraHour" min="0" max="23">
                                    </div>

                                    <div class="input text required">
                                        <label for="CampiOrariImporto">Durata min</label>
                                        <!--<input name="data[CampiOrari][Ora][min]" type="number" class="orariClass" maxlength="2" id="CampiOrariOraMin" value="60">-->
                                        <input name="data[CampiOrari][Ora][durata]" type="number" class="orariClass" maxlength="2" id="CampiOrariDurata" value="60">
                                    </div>


                                    <?= $this->Form->input('CampiOrari.Importo', array('class' => 'orariClass')); ?>

                                    <?= $this->Form->input('CampiOrari.campo_id', array('type' => 'text', 'class' => 'orariClass hidden', 'div' => false, 'label' => false, 'value' => $this->data['Campi']['Campo'])); ?>

                                    <div class="clear"></div>

                                    <?= $this->Form->submit('aggiungi', array('type' => 'button', 'id' => 'orarioAdd', 'div' => true)); ?>

                                </div>

                                <table id="orari" class="form_table form_table_full">

                                    <tr>
                                        <th>Giorno</th>
                                        <th>Ora</th>
                                        <th>Durata</th><!-- //GIUSEPPE 2023-04-10 -->
                                        <th>Ora Fine</th><!-- //GIUSEPPE 2023-04-10 -->
                                        <th>Importo</th>				
                                        <th>Opzioni</th>
                                    </tr>

                                    <? foreach ($orari as $orario): ?>

                                        <tr data-id="<?= $orario['CampiOrari']['id']; ?>">
                                            <td class="td_giorno"><?= $orario['CampiOrari']['Giorno']; ?></td>
                                            <!--<td><?= $orario['CampiOrari']['Ora']; ?></td>-->
                                            <td><?= date('H:i',strtotime($orario['CampiOrari']['Ora'])); ?></td>
                                            <td><?= $orario['CampiOrari']['Durata']; ?></td><!-- //GIUSEPPE 2023-04-10 -->
                                                <?
                                                $timestamp=strtotime($orario['CampiOrari']['OraFine']."+1 minute");
                                                ?>
                                            <td><?= date('H:i',$timestamp); ?></td><!-- //GIUSEPPE 2023-04-10 -->
                                            <td><?= $orario['CampiOrari']['Importo']; ?></td>				
                                            <td>
                                                <a href="javascript:;" class="orariEdit">
                                                    <img src="/img/timmyshare/icon_edit.png" />
                                                </a>							
                                                <a href="javascript:;" class="orariDelete">
                                                    <img src="/img/timmyshare/icon_delete.png" />
                                                </a>								
                                            </td>						
                                        </tr>

                                    <? endforeach; ?>

                                </table>

                            </div>

                            </div>

                            <?= $this->Form->end(); ?>
					
