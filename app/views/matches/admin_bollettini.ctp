<script type="text/javascript">
    var is_append;//GIUSEPPE 2018-05-10
    $(function ()
    {

        $("#PrintCampionato").unbind('change').live('change', function ()
        {

            //console.log("fengul");

            if ($(this).val() == 'default')
            {

                $('.gironi').hide();
                $('.giornate').hide();

                is_append = false;//GIUSEPPE 2018-05-10

                return false;

            }

            is_append = true;//GIUSEPPE 2018-05-10

            var par = $(this).closest('.champ-box');

            if (par.length == 0)
                par = $(this).closest('.champ-box-2');

            var da = "";
            var a = "";

            if ($("#PrintDataIns").val() != "")
            {


                da = $("#PrintDataIns").val();
                da = da.replace(/\//g, "-");

            }


            if ($("#PrintDataOuts").val() != "")
            {


                a = $("#PrintDataOuts").val();
                a = a.replace(/\//g, "-");

            }



            $.get("/admin/prints/getDay/" + $(this).val() + "/" + da + "/" + a, function (ret)
            {

                if (ret.find == undefined)
                    var giornate = 0;
                else
                    var giornate = ret.find;

                var selecteds = ret.selecteds;

                par.find(".giornate div.input .checkbox").remove();

                var selected = "";

                for (var i = 0; i < giornate; i++)
                {

                    selected = "";
                    for (var j = 0; j < selecteds.length; j++)
                    {


                        if ((i + 1) == selecteds[j])
                            selected = "checked='checked'";

                    }

                    par.find(".giornate div.input").append('<div class="checkbox"><label>' + (i + 1) + '</label><input type="checkbox" name="data[Print][Giornate][]" class="checkGiornate" value="' + (i + 1) + '" ' + selected + '/></div>');

                }

                $(".giornate").show();


            }, 'json');

            $.get("/admin/prints/getHalf/" + $(this).val(), function (ret)
            {

                if (ret != undefined && ret.length > 0)
                {

                    par.find(".gironi div.input .checkbox").remove();

                    for (var i = 0; i < ret.length; i++)
                    {

                        par.find(".gironi div.input").append('<div class="checkbox"><label>' + ret[i].Half.Descrizione + '</label><input type="checkbox" name="data[Print][Gironi][]" class="checkGironi" value="' + ret[i].Half.GironeCampionato + '" /></div>');

                    }

                    par.find(".gironi").show();

                }


            }, 'json');

        });

        $("#PrintAdminIndexForm").height(400).css('overflow-y', 'scroll');

        $("#PrintAdminNotesForm").height(400).css('overflow-y', 'scroll');
        $("#timmybox_container").css('margin-top', 10);

        $(".printButton").click(function ()
        {

            /*
             var data = $("#PrintAdminIndexForm").serialize();
             
             var cb = 0;
             */


            var arr = new Array;




            $(".champ-box-2").each(function ()
            {

                var champ = new Array;
                var giornate = $(this).find(".checkGiornate:checked");

                champ["0"] = $(this).find('#PrintCampionato').val();
                champ["1"] = new Array;

                var me = $(this);
                var stampavar = 1;

                if ($("#PrintStampa2").is(':checked'))
                    stampavar = 2;
                var skipme = 0;
                giornate.each(function ()
                {


                    var me2 = $(this);
                    var gironi = $(me).find('.checkGironi:checked');
                    gironi.each(function (index)
                    {

                        if (skipme == 0)
                        {
                            var json = new Object;
                            json.campionato = me.find('#PrintCampionato').val();
                            json.giornata = me2.val();
                            if (stampavar == 1)
                            {
                                json.girone = $(this).val();
                            }
                            else
                            {

                                json.girone = [$(this).val(), $(gironi.get(index + 1)).val()];
                                skipme = 1;
                            }
                            json.stampa = stampavar;
                            json.exp = $("#PrintExportPdf").val();


                            arr.push(json);

                        }
                        else
                        {

                            skipme = 0;

                        }
                    });



                });





            });



            $(".ret-box-2").html("<b>Generazione PDF In corso, Attendere... 0%</b>");
            var files = new Array;
            for (var k = 0; k < arr.length; k++)
            {

                $.ajaxSetup({async: false});

                $.post('/admin/prints/bullettins2/', arr[k], function (ret)
                {

                    files.push(ret);

                    var perc = ((k + 1) / arr.length) * 100;
                    perc = perc.toFixed(2);

                    $(".ret-box-2").html("<b>Stato generazione: " + perc + "%</b>");


                }, 'html');

            }

            $.ajaxSetup({async: true});

            $(".ret-box-2").html("<b>Generazione completata... ATTENDERE unione dei PDF in corso...</b>");
            var data = JSON.stringify(files);

            $.post('/admin/prints/merge', {"data": data}, function (ret)
            {

                location.href = ret;


            }, 'html');
            return;


        });





        $(".printButton2").click(function (e)
        {

            e.stopPropagation();
            e.preventDefault();

            /*
             var data = $("#PrintAdminIndexForm").serialize();
             
             var cb = 0;
             */


            var arr = new Array;




            $(".champ-box").each(function ()
            {

                var champ = new Array;
                var giornate = $(this).find(".checkGiornate:checked");
                champ["0"] = $(this).find('#PrintCampionato').val();
                champ["1"] = new Array;
                giornate.each(function ()
                {

                    champ["1"].push($(this).val());

                });

                champ["2"] = new Array;

                var gironi = $(this).find('.checkGironi:checked');
                gironi.each(function ()
                {

                    champ["2"].push($(this).val());

                });



                if (champ["1"].length > 0 && champ["2"].length > 0)
                    arr.push(champ);


                champ["3"] = 0;
                champ["4"] = $("#PrintExportPdf").val();







            });

            var data = JSON.stringify(arr);


            $(".ret-box").html("Generazione PDF In corso, Attendere...");

            $.post('/admin/prints/notes/', {"data": data}, function (ret)
            {

                location.href = ret;


            }, 'html');


            return false;


        });

        $(".checkGironi, .checkGiornate").live('change', function ()
        {

            if ($(".checkGironi:checked").length > 0 && $(".checkGiornate:checked").length > 0)
                $(".printButton").removeAttr('disabled');
            else
                $(".printButton").attr('disabled', 'disabled');

        });

        $(".checkGironi").live('change', function ()
        {

            if ($(".checkGironi:checked").length > 1)
            {

                $(".tip_stampa").show();

            }
            else
            {

                $(".tip_stampa").hide();

            }

        });


    });

</script>
<div style="padding: 50px;"><? //=FULL_ABSOLUTE_URL//getcwd()   ?>
    <? //=getcwd() ?>
    <?= $this->Form->create('Print'); ?>



    <?= $this->Form->input('DataIns', array('type' => 'text', 'label' => 'Dal', 'class' => 'datePicker', 'div' => false)); ?>
    <?= $this->Form->input('DataOuts', array('type' => 'text', 'label' => 'Al', 'class' => 'datePicker', 'div' => false)); ?>

    <div class="clear"></div>   
    <!--        <br />
            <input type="button" id="searchChamp" value="Trova campionati old" />
            <br />    
            <br />   -->
    <br />    
    <input type="button" id="searchChampNew" value="Trova campionati" />
    <br />
    <div class="clear"></div>   


    <script type="text/javascript">

        $("#searchChamp").unbind('click').live('click', function ()
        {

            var datain;
            var dataout;
            $(".ret-box-2").html('');
            console.log('qui');
            datain = $("#PrintDataIns").val();
            dataout = $("#PrintDataOuts").val();

            $.post('/admin/prints/searchgiornate', {"datain": datain, "dataout": dataout}, function (ret)
            {

                var cbox = $(".champ-box-2").clone();
                for (var i = 0; i < ret.length; i++)
                {
                    var tmp = cbox.clone().show();
                    tmp.find('input,select').each(function ()
                    {


                        var name = $(this).attr('name');
                        name = name.replace('[x]', '[' + i + ']');
                        $(this).attr('name', name);
                    });
                    tmp.find('#PrintCampionato').val(ret[i]).show().trigger('change');
                    console.log(is_append);
                    tmp.appendTo($(".ret-box-2"));
                }


            }, 'json');

        });



        //GIUSEPPE ---------------------------------------------------------------------------------

        var global_res = {};

        var array_points = {};

        $("#searchChampNew").unbind('click').live('click', function ()
        {

            var datain;

            var dataout;

            $(".ret-box-2").html('');

            datain = $("#PrintDataIns").val();

            dataout = $("#PrintDataOuts").val();

            $.post('/admin/prints/searchgiornatenew', {"datain": datain, "dataout": dataout}, function (ret)
            {

                global_res = JSON.parse(JSON.stringify(ret)); // mi serve come riferimento per le selezioni
                console.log(global_res);
                //buffer_res = JSON.parse(JSON.stringify(ret)); // mi serve per le selezioni e quindi per inviare in stamp pdf

                var cbox = $(".champ-box-2").clone();

                $(".ret-box-2").append('<br><input type="checkbox" name="" id="all" class="checkNew" value=""  ><strong>SELECT ALL</strong><br>');

                for (i in ret)//GIUSEPPE 2018-05-10
                {
                    var tmp = cbox.clone().show();

                    var value_option = ret[i];//GIUSEPPE 2018-05-10

                    $(".ret-box-2").append('<br><hr  size="2" color="blue">');

                    // console.log(i);

                    $(".ret-box-2").append('<br><h3><input type="checkbox" name="" id="' + i + '" class="checkNew" value="' + i + '"  ><strong>' + value_option['NomeCampionato'] + '</strong></h3><br>');

                    delete value_option['NomeCampionato']; //abbiamo solo gli indici dei gironi
                    delete value_option['Italiana']; //abbiamo solo gli indici dei gironi

                    for (half in value_option)
                    {

                        $(".ret-box-2").append('<br>&emsp;<input type="checkbox" name="" id="' + i + '-' + half + '" class="checkNew" value="' + i + '-' + half + '"  > - <strong><u>' + value_option[half]['NomeGirone'] + '</u></strong><br>');

                        for (day in value_option[half]['Giornata'])
                        {
                            //var round = value_option[half][day]['NomeGirone'];

                            $(".ret-box-2").append('<br>&emsp;&emsp;&emsp;&emsp;  <input type="checkbox" name="" id="' + i + '-' + half + '-' + day + '" class="checkNew" value="' + i + '-' + half + '-' + day + '"  ><strong>' + day + '° Giornata</strong><br><br>');


                        }
                    }
                }

                $(".ret-box-2").append('<br><hr  size="2" color="blue">');

            }, 'json');

        });

        $(".checkNew").live('change', function ()
        {
            //console.log(global_res);

            console.log($(this).val());

            var id = $(this).val(); // id del check selezionato-deselezionato

            var state_check = $(this).attr("checked"); // stato del check selezionato-deselezionato

            var state_all = true; // stato iniziale di "SELEZIONA TUTTO"

            var state_one = true; // mi serve per capire se c'è almeno un check

            $(".checkNew").each(function (i)
            {
                var value = $(this).val();

                // seleziona-deseleziona tutto ciò che è sotto. es: se seleziono il campionato avrò lo stesso effetto su giornate e gironi; 
                if (value.indexOf(id) === 0) // se seleziono una giornata, avrà lo stesso effetto sui gironi associati; se seleziono il girone, avrò l'effetto solo sul girone    
                {
                    $(this).attr("checked", state_check);
                }

                if ($(this).attr("checked") && i != 0) //esclude il primo check
                {
                    state_one = false;
                }


                if (!$(this).attr("checked") && i != 0) //esclude il primo check
                {
                    state_all = false;
                }

                //console.log(i + " " + $(this).val() + " check: " + $(this).attr("checked"));
            });

            $("#all").attr("checked", state_all); //check all

            $('.printButtonNew').attr("disabled", state_one);

            check_sup();

        });


        function check_sup() // se faccio il check di un girone, mette il check alla relativa giornata e campionato
        {
            //creo array con gli id selezionati:
            var array_check = [];

            //creo array con gli id NON selezionati:
            var array_uncheck = [];
            $(".checkNew").each(function (i)
            {
                //console.log(i + " " + $(this).val() + " check: " + $(this).attr("checked"));

                if (i != 0 && $(this).attr("checked")) // prendo gli id check
                {
                    var to_push = $(this).val();
                    var scompact = to_push.split("-");
                    if (scompact.length == 3) // prendo solo i gironi, visto che contengo ange sia l'id del campionato, che sia l'id della giornata
                        array_check.push($(this).val());
                }

                if (i != 0 && !$(this).attr("checked")) // prendo gli id uncheck
                {
                    var to_push = $(this).val();
                    var scompact = to_push.split("-");
                    if (scompact.length == 3) // prendo solo i gironi, visto che contengo ange sia l'id del campionato, che sia l'id della giornata
                        array_uncheck.push($(this).val());
                }
            });

            //riconfermo i check
            read_id(array_check, array_uncheck);

        }

        function read_id(array_check, array_uncheck) // il check o l'uncheck lo stabilisco dai gironi
        {
            var id_check;
            var arr_id = [];
            var id_val = [];
            var state_one = true; // mi serve per capire se c'è almeno un check
            array_points = {};

            for (i in array_uncheck) //prima confermo gli uncheck
            {
                id_check = array_uncheck[i];
                //console.log(id_check);
                arr_id = id_check.split("-"); // scompatto l'id in base al "-"

                id_val = [];

                for (j in arr_id)
                {
                    id_val.push(arr_id[j]);
                    $("#" + id_val.join("-")).attr("checked", false);
                }

            }


            for (i in array_check) // poi confermo i check
            {
                state_one = false;

                id_check = array_check[i];
                //console.log(id_check);
                arr_id = id_check.split("-"); // scompatto l'id in base al "-"

                id_val = [];

                for (j in arr_id)
                {
                    id_val.push(arr_id[j]);
                    $("#" + id_val.join("-")).attr("checked", true);
                }

                //var match = {};

                //array_points[arr_id[0]] = {};

                if (!array_points.hasOwnProperty(arr_id[0]))
                    array_points[arr_id[0]] = {};


                if (!array_points[arr_id[0]].hasOwnProperty(arr_id[1]))
                    array_points[arr_id[0]][arr_id[1]] = {};


                if (!array_points[arr_id[0]][arr_id[1]].hasOwnProperty(arr_id[2]))
                    array_points[arr_id[0]][arr_id[1]][arr_id[2]] = {};


                console.log(array_points.hasOwnProperty(arr_id[0]));

                array_points[arr_id[0]]['NomeCampionato'] = global_res[arr_id[0]]['NomeCampionato'];
                array_points[arr_id[0]]['Italiana'] = global_res[arr_id[0]]['Italiana'];
                //array_points[arr_id[0]][arr_id[1]] = {};
                array_points[arr_id[0]][arr_id[1]]['NomeGirone'] = global_res[arr_id[0]][arr_id[1]]['NomeGirone'];
                //array_points[arr_id[0]][arr_id[1]][arr_id[2]] = {};
                array_points[arr_id[0]][arr_id[1]][arr_id[2]] = global_res[arr_id[0]][arr_id[1]][arr_id[2]];

            }
            $('.printButtonNew').attr("disabled", state_one);
            console.log("----");
            console.log(array_points);
            console.log("----");
        }
        $(function ()
        {
            $('.printButtonNew').click(function ()
            {
                $("#im_load").show();
                $.post('/admin/prints/bullettins_new', {array_points: array_points}, function (res)
                {
                    //console.log(JSON.parse(res));
                    
                    console.log(res);
                    
                    var expl = res.split(".pdf"); //GIUSEPPE 2022-12-14 -----------------------
                    
                    var linkPdf = `${expl[0]}.pdf`; //GIUSEPPE 2022-12-14 -----------------------
                    
                    $("#im_load").hide();
                    //window.open(res, '_blank');
                    window.open(linkPdf, '_blank'); //GIUSEPPE 2022-12-14 -----------------------

                });
            });
        });
        //------------------------------------------------------------------------------------------

    </script>

    <div class="ret-box-2">

    </div>

    <div class="champ-box-2" style="display: none;">
        <!--//GIUSEPPE 2018-05-09 linea di separazione -->
        <br>
        <br>
        <hr size="2" color="blue">
        <!-- ------------------- -->

        <?= $this->Form->input('Campionato', array('type' => 'select', 'name' => 'data[Print][Campionato]', 'label' => 'Campionato', 'options' => $campionati, 'div' => false)); ?>

        <div class="clear"></div>

        <div class="giornate" style="display: none;">

            <?=
            $this->Form->input('Giornate', array(
                'type' => 'select',
                'label' => 'Giornate',
                'multiple' => 'checkbox',
                'name' => 'data[Print][Giornate]',
                'options' => array(
                )
            ));
            ?>

            <div class="clear"></div>
            <a style="padding-left: 10px; color: #000;" onclick="$(this).parent('.giornate').find('.checkGiornate').attr('checked', true);
                    if ($('.checkGironi:checked').length > 0 && $('.checkGiornate:checked').length > 0)
                        $('.printButton').removeAttr('disabled');

                    else
                        $('.printButton').attr('disabled', 'disabled');" href="javascript:;" class="select-all-day">seleziona tutte</a>             

        </div>

        <div class="gironi" style="display: none;">

            <?=
            $this->Form->input('Girone', array(
                'type' => 'select',
                'label' => 'Gironi',
                'multiple' => 'checkbox',
                'name' => 'data[Print][Girone]',
                'options' => array(
                )
            ));
            ?>

            <div class="clear"></div>
            <a style="padding-left: 10px; color: #000;" onclick="$(this).parent('.gironi').find('.checkGironi').attr('checked', true);
                    if ($('.checkGironi:checked').length > 0 && $('.checkGiornate:checked').length > 0)
                        $('.printButton').removeAttr('disabled');

                    else
                        $('.printButton').attr('disabled', 'disabled');
                    if ($('.checkGironi:checked').length > 1)
                    {
                        $('.tip_stampa').show();
                    }
                    else
                    {
                        $('.tip_stampa').hide();
                    }" href="javascript:;" class="select-all-day">seleziona tutti</a>               

        </div>

        <div class="clear"></div>

    </div>

    <div class="tip_stampa" style="display: none;">

        <?=
        str_replace('fieldset', 'fieldset style="width: 250px;"', $this->Form->input('Stampa', array(
                    'type' => 'radio',
                    'label' => 'Modalità di stampa',
                    'options' => array(
                        '1' => '1 girone per pagina',
                        '2' => '2 gironi per pagina',
                    ),
                    'value' => '1'
        )));
        ?>

    </div>

    <div class="clear"></div>

    <div class="tip_export">

        <?=
        $this->Form->input('Export', array(
            'type' => 'radio',
            'label' => 'Modalità di esportazione',
            'options' => array(
                'pdf' => 'PDF',
            ),
            'value' => 'pdf'
        ));
        ?>

    </div>


    <div class="clear"></div>

    <? //= $this->Form->button('StampaOld', array('type' => 'button', 'class' => 'printButton', 'disabled' => 'disabled', 'div' => true, 'label' => '')); ?>
    <!--    <br>-->
    <br>
    <?= $this->Form->button('Stampa', array('type' => 'button', 'class' => 'printButtonNew', 'disabled' => 'disabled', 'div' => true, 'label' => '')); ?>
    &emsp; <img id="im_load" src="/porto_admin/vendor/owl-carousel/AjaxLoader.gif" alt="" hidden=""/>
    <?= $this->Form->end(); ?>
</div>