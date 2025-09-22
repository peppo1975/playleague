<script type="text/javascript">
    if (typeof $ != "undefined")
    {
        var match_id = new Array;

        $(function () {

            //GIUSEPPE 2017-06-03 - - - - - - - - - - - - - - - -
            $("#timmy_close").click(function () {


                if (!$('.sendButton').is(':visible'))
                {
                    location.reload();
                }

            });

            $("#timmy_overlay").click(function () {


                if (!$('.sendButton').is(':visible'))
                {
                    location.reload();
                }

            });
            //- - - - - - - - - - - - - - - - - - - - - - - - - -

            $(".index-select-checkbox:checked").each(function () {

                match_id.push($(this).val());

            });

            if (match_id.length == 0)
            {

                alert('Selezionare almeno una gara.');
                $("#timmy_overlay").remove();

            }

            $("#ComldaAdminSendLdaIndexForm").submit(function () {

                if ($(".checkboxType:checked").length == 0)
                {
                    alert('Selezionare modalità di invio');
                    return false;
                }
                if ($(".checkboxMode:checked").length == 0)
                {
                    alert('Selezionare a chi inviare');
                    return false;
                }

                var data = new Object;

                $("#ComldaAdminSendLdaIndexForm").find('input:hidden').each(function (index) {

                    if ($(this).attr('id').length > 0)
                    {

                        var attr_id = $(this).attr('id');

                        data[attr_id] = $(this).val();

                    }

                });

                $.post('/admin/matches/sendLda', {"Match": match_id, "Data": data}, function (ret) {

                    console.log(ret);



//                    if (ret.ok == 0)
//                    {
//
//                        alert('Lista email salvata correttamente. Le email verranno inviate automaticamente.');
//
//                    }
//                    else
//                    {
//
//                        alert('Errore, nessun\' atleta trovato nelle gare selezionate. Riprovare.');
//
//                    }



                    //GIUSEPPE 2017-06-03 avviso di inserimento coda messaggi

                    var email = ret.email;

                    var sms = ret.sms;



                    //var response = email + " EMAIL<br>" + sms + " SMS<br>" + "in coda nello spool e pronti per l'invio.";

                    $("#list_LDA").html('');
                    $("#table_response").show();

                    //$("#list_LDA").html(response);

                    $("#email").html(email);
                    $("#sms").html(sms);
                    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -



                    $("#timmybox").remove();

                }, 'json');

                return false;

            });

        });
    }
</script>

<?= $this->Form->create('Comlda'); ?>

<script type="text/javascript">
    if (typeof $ != "undefined")
    {
        $(function () {

            $("#checkSms").change(function () {

                if ($(this).is(':checked'))
                {

                    $("#ComldaIsSms").val(1);

                }
                else
                {

                    $("#ComldaIsSms").val(0);

                }

                //GIUSEPPE 2017-06-03 - - - - - - - - - - - -
                // read_check();
                //- - - - - - - - - - - - - - - - - - - - - -

            });

            $("#checkEmail").change(function () {

                if ($(this).is(':checked'))
                {

                    $("#ComldaIsEmail").val(1);

                }
                else
                {

                    $("#ComldaIsEmail").val(0);

                }

                //GIUSEPPE 2017-06-03 - - - - - - - - - - - -
                //read_check();
                //- - - - - - - - - - - - - - - - - - - - - -

            });

            $("#checkDelegato").change(function () {

                if ($(this).is(':checked'))
                {

                    $("#ComldaIsDelegato").val(1);

                }
                else
                {

                    $("#ComldaIsDelegato").val(0);

                }

                //GIUSEPPE 2017-06-03 - - - - - - - - - - - -
                //read_check();
                //- - - - - - - - - - - - - - - - - - - - - -

            });

            $("#checkArbitro").change(function () {

                if ($(this).is(':checked'))
                {

                    $("#ComldaIsArbitro").val(1);

                }
                else
                {

                    $("#ComldaIsArbitro").val(0);

                }

                //GIUSEPPE 2017-06-03 - - - - - - - - - - - -
                // read_check();
                //- - - - - - - - - - - - - - - - - - - - - -
            });

            //GIUSEPPE 2017-06-03 - - - - - - - - - - - - non serve piu
            function read_check()
            {

                var is_check = ($("#checkArbitro").is(':checked') || $("#checkDelegato").is(':checked'))
                        && ($("#checkEmail").is(':checked') || $("#checkSms").is(':checked'));
                if (is_check)
                {
                    $('.sendButton').attr('disabled', false);
                }

                else
                {
                    $('.sendButton').attr('disabled', true);
                }
            }
            //- - - - - - - - - - - - - - - - - - - - - -

        });
    }
</script>

<div id="table_response" hidden>
    <!--<div id="table_response">-->
<!--    <table >
        <tr><td><div id="email"></div></td><td>EMAIL</td></tr>
        <tr><td><div id="sms"></div></td><td>SMS</td></tr>
    </table>
    in coda nello spool e pronti per l'invio.-->


    <table border="0">
        <tr>
            <td><div id="email" align="right"></div></td><td>EMAIL</td>
        </tr>
        <tr>
            <td><div id="sms" align="right"></div></td> <td>SMS</td>
        </tr>
        <tr>
            <td colspan="2"><br>in coda nello spool e pronti per l'invio.</td>
        </tr>
    </table>

</div>

<div id="list_LDA">

    <div class="input">
        <label for="checkEmail">Invia email</label>
        <input class="checkboxType" type="checkbox" id="checkEmail" />
    </div>

    <div class="input" <? if ($this->Session->read('User.group_id') == 4): ?>style="display: none;";<? endif; ?>>
        <label for="checkSms">Invia sms</label>
        <input class="checkboxType" type="checkbox" id="checkSms" />
    </div>					

    <?= $this->Form->input('isEmail', array('type' => 'hidden', 'value' => 0)); ?>
    <?= $this->Form->input('isSms', array('type' => 'hidden', 'value' => 0)); ?>

    <div class="clear"></div>

    <div class="input">
        <label for="checkDelegato">Invia a delegati</label>
        <input class="checkboxMode" type="checkbox" id="checkDelegato" />
    </div>
    <div class="input">
        <label for="checkArbitro">Invia ad arbitri</label>
        <input class="checkboxMode" type="checkbox" id="checkArbitro" />
    </div>					

    <?= $this->Form->input('isDelegato', array('type' => 'hidden', 'value' => 0)); ?>
    <?= $this->Form->input('isArbitro', array('type' => 'hidden', 'value' => 0)); ?>

    <div class="clear"></div>

    <?= $this->Form->button('invia', array('type' => 'submit', 'class' => 'sendButton', 'style' => 'margin-top: 10px')); ?>

    <?= $this->Form->end(); ?>

    <? //= $this->Form->end('Invia');  ?>

</div>