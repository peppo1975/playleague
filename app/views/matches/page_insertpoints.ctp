<script type="text/javascript" src="/js/jQuery-Mask-Plugin/dist/jquery.mask.min.js"></script>
<script>

    var id_squadra_campionato_casa = '<?= $match['Match']['Casa'] ?>';

    var id_squadra_campionato_trasferta = '<?= $match['Match']['Trasferta'] ?>';

    var id_calendario = '<?= $match['Match']['Calendario'] ?>';

    var object_points = {};

    var point_to_text_window = {};

    var point_to_transfert = {};

    var class_box = "";

    $(document).ready(function ()
    {

        var i, j;


        for (i = 1; i <= 6; i++)
        {
            for (j = 1; j <= 3; j++)
            {

                class_box = '.s_' + i + '_' + j;

                //console.log(class_box);

                $(class_box).mask('000');

                $(class_box).css("text-align", "center");

            }
        }

        createSetPoint();

    });




    $("#defaultModal").click(function () {

        //nell'uscita devo eseguire il reload della pagina

        var stringa_uscita = "modal fade";

        var stringa_modal = $(this)["0"].className;

        //console.log(stringa_modal);

        //console.log(stringa_modal.localeCompare(stringa_uscita))

        if (stringa_modal.localeCompare(stringa_uscita) == 0)
        {
            location.reload();
        }

    });



    function createSetPoint()
    {
        $link = "/matches/tennispoint/" + id_calendario + "/" + id_squadra_campionato_casa + "/" + id_squadra_campionato_trasferta;

        $.get($link, function (data) {

            point_to_text_window = JSON.parse(data);

            point_to_transfert = JSON.parse(data);

            read_teams();

        });
    }

    function read_teams()
    {
        var link = '/matches/searchTeam/' + id_squadra_campionato_casa + '/' + id_calendario + '/?tt=1'

        //console.log(link);

        $.get(link, function (ret) {
            //console.log(ret);

            squadra_casa = ret;


            link = '/matches/searchTeam/' + id_squadra_campionato_trasferta + '/' + id_calendario + '/?tt=1'

            $.get(link, function (ret) {

                squadra_trasferta = ret;

                //console.log(ret);

                insert_input_select(squadra_casa, squadra_trasferta);

            }, 'json');

        }, 'json');

    }
    ;




    function insert_input_select(squadra_casa, squadra_trasferta)
    {
        var stringToTeam = "<option value='0'>In Sospeso </option>";

        for (var prop in squadra_casa)
        {
            stringToTeam += "<option value='" + squadra_casa[prop].id + "'>" + squadra_casa[prop].nome + "</option>";

            object_points[squadra_casa[prop].id] = 0;
        }
        ;

        //console.log(stringToTeam);

        $(".casa_s1  option").remove();

        $(".casa_s1").append(stringToTeam);


        $(".casa_s2  option").remove();

        $(".casa_s2").append(stringToTeam);



        $(".casa_d1  option").remove();

        $(".casa_d1").append(stringToTeam);


        $(".casa_d2  option").remove();

        $(".casa_d2").append(stringToTeam);




        stringToTeam = "<option value='0'>In Sospeso </option>";

        for (var prop in squadra_trasferta)
        {
            //console.log(prop);

            stringToTeam += "<option value='" + squadra_trasferta[prop].id + "'>" + squadra_trasferta[prop].nome + "</option>"

            object_points[squadra_trasferta[prop].id] = 0;

        }

        $(".trasferta_s1  option").remove();

        $(".trasferta_s1").append(stringToTeam);


        $(".trasferta_s2  option").remove();

        $(".trasferta_s2").append(stringToTeam);



        $(".trasferta_d1  option").remove();

        $(".trasferta_d1").append(stringToTeam);


        $(".trasferta_d2  option").remove();

        $(".trasferta_d2").append(stringToTeam);
    }



    function sendpoints()
    {
        
        /* 
        if (read_athletes() == false)
        {
            alert("DEVI INSERIRE TUTTI GLI ATLETI");
            return;
        }*/


        if (read_points() == false)
        {
            alert("DEVI INSERIRE TUTTI I PUNTI");

            return;
        }

        if (read_check() == true)
        {
            var question = confirm("CONFERMI L'OPERAZIONE?\n\nCONFERMANDO NON SARA' POSSIBILE MODIFICARE IL RISULTATO\n\n");

            if (question === true)
            {
                insertPointsHome();

                insertPointsTransfert();

                console.log(point_to_text_window);

                console.log(point_to_transfert);

                send_data(JSON.stringify(point_to_text_window), JSON.stringify(point_to_transfert));
            }

        }
        else
        {
            alert("Non hai inserito tutte le squadre vincenti");

        }
    }





    function insertPointsHome()
    {
        for (var prop in point_to_text_window.points)
        {

            point_to_text_window.points[prop] = $("." + prop).val();

        }

        for (var id_athlete in point_to_text_window.athletes)
        {
            point_to_text_window.athletes[id_athlete] = $("." + id_athlete).val();
        }


        for (var check in point_to_text_window.check_win)
        {

            //console.log($("#"+check).is(":checked"));
            var val_check;
            switch ($("." + check).is(":checked"))
            {
                case true:
                    val_check = 1;
                    break;

                case false:
                    val_check = 0;
                    break;
            }

            point_to_text_window.check_win[check] = val_check.toString(); //imposta true o false

        }
    }

    function insertPointsTransfert()
    {
        //set1
        point_to_transfert['points']['s_1_1'] = $(".s_2_1").val();
        point_to_transfert['points']['s_2_1'] = $(".s_1_1").val();

        point_to_transfert['points']['s_1_2'] = $(".s_2_2").val();
        point_to_transfert['points']['s_2_2'] = $(".s_1_2").val();

        point_to_transfert['points']['s_1_3'] = $(".s_2_3").val();
        point_to_transfert['points']['s_2_3'] = $(".s_1_3").val();


        //set 2
        point_to_transfert['points']['s_3_1'] = $(".s_4_1").val();
        point_to_transfert['points']['s_4_1'] = $(".s_3_1").val();

        point_to_transfert['points']['s_3_2'] = $(".s_4_2").val();
        point_to_transfert['points']['s_4_2'] = $(".s_3_2").val();

        point_to_transfert['points']['s_3_3'] = $(".s_4_3").val();
        point_to_transfert['points']['s_4_3'] = $(".s_3_3").val();


        //doppio
        point_to_transfert['points']['s_5_1'] = $(".s_6_1").val();
        point_to_transfert['points']['s_6_1'] = $(".s_5_1").val();

        point_to_transfert['points']['s_5_2'] = $(".s_6_2").val();
        point_to_transfert['points']['s_6_2'] = $(".s_5_2").val();

        point_to_transfert['points']['s_5_3'] = $(".s_6_3").val();
        point_to_transfert['points']['s_6_3'] = $(".s_5_3").val();


        //WIN
        point_to_transfert['check_win']['s_1_4'] = val_check($(".s_1_4").is(":checked"));

        point_to_transfert['check_win']['s_2_4'] = val_check($(".s_2_4").is(":checked"));

        point_to_transfert['check_win']['s_3_4'] = val_check($(".s_3_4").is(":checked"));

        point_to_transfert['check_win']['s_4_4'] = val_check($(".s_4_4").is(":checked"));

        point_to_transfert['check_win']['s_5_4'] = val_check($(".s_5_4").is(":checked"));

        point_to_transfert['check_win']['s_6_4'] = val_check($(".s_6_4").is(":checked"));


        //atleti
        //s1
        point_to_transfert['athletes']['casa_s1'] = $(".trasferta_s1").val();

        point_to_transfert['athletes']['trasferta_s1'] = $(".casa_s1").val();


        //atleti
        //s2
        point_to_transfert['athletes']['casa_s2'] = $(".trasferta_s2").val();

        point_to_transfert['athletes']['trasferta_s2'] = $(".casa_s2").val();


        //atleti
        //doppio
        point_to_transfert['athletes']['casa_d1'] = $(".trasferta_d1").val();

        point_to_transfert['athletes']['trasferta_d1'] = $(".casa_d1").val();

        point_to_transfert['athletes']['casa_d2'] = $(".trasferta_d2").val();

        point_to_transfert['athletes']['trasferta_d2'] = $(".casa_d2").val();


        for (var prop in point_to_text_window.points)
        {

            if (point_to_text_window.points[prop] == "")
            {
                point_to_text_window.points[prop] = "0";
            }


        }


        function val_check(val)
        {
            var result;

            switch (val)
            {
                case true:
                    result = "0";
                    break;
                case false:
                    result = "1";
                    break;
            }
            return result;
        }
    }




    function send_data(data_json, data_transfert_json)
    {
        $link = "/matches/insertpoint/";

        console.log($link);

        //console.log(data_transfert_json);

        $.post($link,
                {
                    id_calendario: id_calendario
                    , id_squadra_campionato_casa: id_squadra_campionato_casa
                    , id_squadra_campionato_trasferta: id_squadra_campionato_trasferta
                    , json_data: data_json
                    , json_transfert: data_transfert_json
                }
        , function (data, status)
        {
            console.log("punti : " + data);

            location.reload();
        });
    }

    function read_check()
    {
        var sum_check = 0;

        var result = false;

        for (i in point_to_text_window["check_win"])
        {
            //console.log("chech -> " + i + " val -> " + $("." + i).is(':checked'));

            if ($("." + i).is(':checked') === true)
            {
                sum_check += 1;
            }

        }

        if (sum_check == 3)
        {
            result = true;
        }

        //console.log("SOMMA -> " + sum_check);

        return result;
    }


    function read_athletes()
    {
        var id_listbox = ["casa_s1", "casa_s2", "trasferta_s1", "trasferta_s2", "casa_d1", "casa_d2", "trasferta_d1", "trasferta_d2"];

        result = true;

        for (var i in id_listbox)
        {
            //console.log($("." + id_listbox[i]).val());
            if ($("." + id_listbox[i]).val() == '0')
            {
                result = false;

                selDiv = document.getElementById(id_listbox[i]);
                //selDiv.style.backgroundColor = "#ff0";
                selDiv.style.border = "thick solid red";

                // console.log("esco");
            }
            else
            {
                selDiv = document.getElementById(id_listbox[i]);
                //selDiv.style.backgroundColor = "#fff";
                selDiv.style.border = "";
            }

        }

        return result;
    }



    function read_points()
    {
        var i, j;

        result = true;

        for (i = 1; i <= 6; i++)
        {
            for (j = 1; j <= 3; j++)
            {
                id_box = ".s_" + i + "_" + j;

                id_b = "s_" + i + "_" + j;

                if ($(id_box).val() == "")
                {
                    

                    selDiv = document.getElementById(id_b);
                    //selDiv.style.backgroundColor = "#ff0";
                    selDiv.style.border = "thick solid red";

                    result = false;
                }
                else
                {
                    selDiv = document.getElementById(id_b);
                    //selDiv.style.backgroundColor = "#fff";
                    selDiv.style.border = "";

                    //result = true;
                }
            }
        }

        return result;
    }

    $("#casa_d1").change(function ()
    {
        var d1 = $('.casa_d1').val();

        var d2 = $('.casa_d2').val();

        var result_compare;

        //console.log("d1 -> " + d1);
        //console.log("d2 -> " + d2);

        if (d1 !== '0' && d2 !== '0')
        {
            result_compare = d1.localeCompare(d2);

            if (result_compare == 0)
            {
                alert("Nominativo gia utilizzato nel doppio");

                $('.casa_d1').val('0');
            }

        }


    });


    $(".casa_d2").change(function ()
    {
        var d1 = $(".casa_d1").val();

        var d2 = $(".casa_d2").val();

        var result_compare;

        // console.log("d1 -> " + d1);
        // console.log("d2 -> " + d2);


        if (d1 !== "0" && d2 !== "0")
        {
            result_compare = d1.localeCompare(d2);

            if (result_compare == 0)
            {
                alert("Nominativo gia utilizzato nel doppio");

                $(".casa_d2").val("0");
            }

        }

    });



    $(".trasferta_d1").change(function ()
    {
        var d1 = $(".trasferta_d1").val();

        var d2 = $(".trasferta_d2").val();

        var result_compare;

        // console.log("d1 -> " + d1);
        // console.log("d2 -> " + d2);


        if (d1 !== "0" && d2 !== "0")
        {
            result_compare = d1.localeCompare(d2);

            if (result_compare == 0)
            {
                alert("Nominativo gia utilizzato nel doppio");

                $(".trasferta_d1").val("0");
            }

        }


    });



    $(".trasferta_d2").change(function ()
    {
        var d1 = $(".trasferta_d1").val();

        var d2 = $(".trasferta_d2").val();

        var result_compare;

        // console.log("d1 -> " + d1);
        // console.log("d2 -> " + d2);


        if (d1 !== "0" && d2 !== "0")
        {
            result_compare = d1.localeCompare(d2);

            if (result_compare == 0)
            {
                alert("Nominativo gia utilizzato nel doppio");

                $(".trasferta_d2").val("0");
            }

        }


    });



    // });

</script>

<h1 class="modal-title">Punti partita</h1>
<table class="table table-striped table-condensed">
    <tr>
        <th style="border-top: 0">Data</th>
        <td style="border-top: 0"><?= $match['Match']['Data_it']; ?></td>
    </tr>
    <tr>
        <th>Squadre</th>
        <td><?= $match['Match']['CasaNome']; ?>  <small><strong>vs</strong></small> <?= $match['Match']['TrasfertaNome']; ?></td>
    </tr>
</table>
<div class="booking-pad" class="table-matches">
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="alert alert-warning">
                Inserisci qui i punti partita.<br>Una volta confermata l'operazione, <br>non sarà possibile effettuare modifiche.
            </div>
            <strong>SINGOLO 1</strong>
            <table style="width:100%" class="table table-striped table-condensed">
                <tr>
                    <th>Squadra</th>
                    <th>Atleta</th> 
                    <th>1 set</th>
                    <th>2 set</th>
                    <th>3 set</th>
                    <th>Vincitore</th>
                </tr>
                <tr>
                    <td align="left"><?= $match['Match']['CasaNome']; ?></td>
                    <td align="left"><select class="casa_s1" id="casa_s1"><option value=''>...loading</option></select></td> 
                    <td><input  type="text" class="s_1_1" id="s_1_1" maxlength="1" size="3"></td>
                    <td><input  type="text" class="s_1_2"  id="s_1_2" maxlength="1" size="3"></td>
                    <td><input  type="text" class="s_1_3"  id="s_1_3" maxlength="2" size="3"></td> 
                    <td><input type="radio" name="c_1" class="s_1_4" id="s_1_4" value="c_1"></td>
                </tr>
                <tr>
                    <td align="left"><?= $match['Match']['TrasfertaNome']; ?></td>
                    <td align="left"><select class="trasferta_s1" id="trasferta_s1" onclick="reading(value)"><option value=''>...loading</option></select></td> 
                    <td><input  type="text" class="s_2_1" id="s_2_1" maxlength="1" size="3"></td>
                    <td><input  type="text" class="s_2_2" id="s_2_2" maxlength="1" size="3"></td>
                    <td><input  type="text" class="s_2_3" id="s_2_3" maxlength="2" size="3"></td> 
                    <td><input type="radio" name="c_1" class="s_2_4" id="s_2_4" value="c_1"></td>
                </tr>
            </table>

            <strong>SINGOLO 2</strong>
            <table style="width:100%" class="table table-striped table-condensed">
                <tr>
                    <th>Squadra</th>
                    <th>Atleta</th> 
                    <th>1 set</th>
                    <th>2 set</th>
                    <th>3 set</th>
                    <th>Vincitore</th>
                </tr>
                <tr>
                    <td align="left"><?= $match['Match']['CasaNome']; ?></td>
                    <td align="left"><select class="casa_s2"  id="casa_s2"><option value=''>...loading</option></select></td> 
                    <td><input  type="text" class="s_3_1" id="s_3_1" maxlength="1" size="3"></td>
                    <td><input  type="text" class="s_3_2" id="s_3_2" maxlength="1" size="3"></td>
                    <td><input  type="text" class="s_3_3" id="s_3_3" maxlength="2" size="3"></td> 
                    <td><input type="radio" name="c_2" class="s_3_4" id="s_3_4" value="c_2"></td>
                </tr>
                <tr>
                    <td align="left"><?= $match['Match']['TrasfertaNome']; ?></td>
                    <td align="left"><select class="trasferta_s2"  id="trasferta_s2"><option value=''>...loading</option></select></td> 
                    <td><input  type="text" class="s_4_1" id="s_4_1" maxlength="1" size="3"></td>
                    <td><input  type="text" class="s_4_2" id="s_4_2" maxlength="1" size="3"></td>
                    <td><input  type="text" class="s_4_3" id="s_4_3" maxlength="2" size="3"></td> 
                    <td><input type="radio" name="c_2" class="s_4_4" id="s_4_4" value="c_2"></td>
                </tr>
            </table>

            <strong>DOPPIO</strong>
            <table style="width:100%" class="table table-striped table-condensed">
                <tr>
                    <th>Squadra</th>
                    <th>Atleta</th> 
                    <th>1 set</th>
                    <th>2 set</th>
                    <th>3 set</th>
                    <th>Vincitore</th>
                </tr>
                <tr>
                    <td align="left" rowspan="2"><?= $match['Match']['CasaNome']; ?></td>

                    <td align="left"><!-- prima riga splittata -->
                        <select class="casa_d1"  id="casa_d1"><option value=''>...loading</option></select>

                    </td>

                    <td><input  type="text" class="s_5_1" id="s_5_1" maxlength="1" size="3"></td>
                    <td><input  type="text" class="s_5_2" id="s_5_2" maxlength="1" size="3"></td>
                    <td><input  type="text" class="s_5_3" id="s_5_3" maxlength="2" size="3"></td> 
                    <td><input type="radio" name="c_3" class="s_5_4" id="s_5_4" value="c_3"></td>
                </tr>

                <tr>
                    <td align="left"><!-- seconda riga splittata -->
                        <select class="casa_d2"  id="casa_d2"><option value=''>...loading</option></select>
                    </td>
                </tr>


                <tr>
                    <td align="left"  rowspan="2"><?= $match['Match']['TrasfertaNome']; ?></td>

                    <td align="left" ><!-- terza riga splittata -->
                        <select class="trasferta_d1"  id="trasferta_d1"><option value=''>...loading</option></select>
                    </td>

                    <td><input  type="text" class="s_6_1" id="s_6_1" maxlength="1" size="3"></td>
                    <td><input  type="text" class="s_6_2" id="s_6_2" maxlength="1" size="3"></td>
                    <td><input  type="text" class="s_6_3" id="s_6_3" maxlength="2" size="3"></td> 
                    <td><input type="radio" name="c_3" class="s_6_4" id="s_6_4" value="c_3"></td>
                </tr>


                <tr>
                    <td align="left"><!-- quarta riga splittata -->
                        <select class="trasferta_d2"  id="trasferta_d2"><option value=''>...loading</option></select>
                    </td>
                </tr>

            </table>

            <div class="row">
                <div class="col-md-10">
                    <? //= $this->Form->input('ranking', array('type' => 'select', 'div' => false, 'class' => 'from-control select2 pull-left', 'options' => $options, 'label' => '')); ?>
                </div>
                <div class="col-md-2">
                    <? //= $this->Form->submit('conferma', array('type' => 'submit', 'div' => false, "style" => "padding: 8px 17px !important", 'class' => 'btn btn-success btn-md pull-right')); ?>
                <!--<input type="submit" style="padding: 8px 17px !important" class="btn btn-success btn-md pull-right" value="conferme">-->
                    <input type="" style="padding: 8px 17px !important" class="btn btn-success btn-md pull-right" value="Conferma" onclick="sendpoints()">
                </div>
            </div>
        </div>
    </div>									
</div>