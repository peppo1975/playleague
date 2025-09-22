<script type="text/javascript" src="/js/jQuery-Mask-Plugin/dist/jquery.mask.min.js"></script>
<script type="text/javascript">
    /* Javascript Goal */
    if (typeof $ != "undefined")
    {
        $(function () {

            //Check ammonizione / espulsione

            $('.formAdd').delegate("#MatchAmmonizioneSi", "click", function () {

                if ($(this).is(':checked'))
                {
                    $("#MatchEspulsioneNo").trigger('click');
                    $("#MatchEspulsioneNo").trigger('change');
                    $("#MatchEspulsioneGiornate").val('');
                    $("#MatchEspulsioneFine").val('');
                }

            });

            $('.formAdd').delegate("#MatchEspulsioneSi", "click", function () {

                if ($(this).is(':checked'))
                {
                    $("#MatchAmmonizioneNo").trigger('click');
                }

            });

            var exit = 0;

            function getRisultato(Calendario) {

                $.get('/admin/matches/getRisultato/' + Calendario, function (retu) {

                    $("span#matchRisultato").empty().text(retu.risultato);

                }, 'json');

            }

            function getAtleti(SquadraCampionato, Calendario) {

                console.log('executed');

                console.log(Calendario);

                $.get('/admin/matches/goalSearchAthleteByTeam/' + SquadraCampionato + '/' + Calendario + '?tt=1', function (ret) {

                    var newOptions = ret;

                    var select = $('.athleteSelect');
                    if (select.prop)
                    {
                        var options = select.prop('options');
                    }
                    else
                    {
                        var options = select.attr('options');
                    }
                    $('option', select).remove();
                    /*
                     $.each(newOptions, function(val, text) {
                     options[options.length] = new Option(text, val);
                     });
                     */

                    for (i in ret)
                    {
                        select.append('<option value="' + ret[i].id + '">' + ret[i].nome + '</option>');

                    }


                    select.prepend($('<option>').val(0).text('In sospeso'));

                }, 'json');

            }

<? if ($group_id == 3): ?>
/* GIUSEPPE 2022-12-12 commentato perchè il designatore ha un load molto lento
                $("#MatchEditForm").find(':input').each(function () {


                    if ($(this).parents('.form_header').length == 0)
                        $(this).not('#MatchArbitroSearch,#MatchArbitro2Search,#MatchDelegatoSearch,#MatchDelegatoASearch').attr('readonly', true);

                              });*/

<? endif; ?>

            $("#MatchSquadraCampionato").ready(function () {

                var SquadraCampionato = $("#MatchSquadraCampionato").val();
                var Calendario = $("#MatchCalendario").val();

                getAtleti(SquadraCampionato, Calendario);

            });

            $('.formAdd').delegate('#MatchSquadraCampionato', 'change', function (e, id_atleta) {

                var SquadraCampionato = $(this).val();
                var Calendario = $("#MatchCalendario").val();

                $.get('/admin/matches/goalSearchAthleteByTeam/' + SquadraCampionato + '/' + Calendario + '/?tt=1', function (ret) {

                    var newOptions = ret;

                    var select = $('.athleteSelect');
                    if (select.prop)
                    {
                        var options = select.prop('options');
                    }
                    else
                    {
                        var options = select.attr('options');
                    }
                    $('option', select).remove();

                    /*
                     $.each(newOptions, function(val, text) {
                     options[options.length] = new Option(text, val);
                     
                     
                     
                     if(id_atleta != undefined) $("#MatchAtleta").val(id_atleta);
                     
                     });
                     
                     */


                    for (i in ret)
                    {
                        select.append('<option value="' + ret[i].id + '">' + ret[i].nome + '</option>');

                        if (id_atleta != undefined)
                            $("#MatchAtleta").val(id_atleta);



                    }


                    select.prepend($('<option>').val(0).text('In sospeso'));

                }, 'json');

            });

            $(".formAdd").delegate('.GoalDelete', 'click', function () {

                var delete_id = $(this).closest('tr').attr('data-id');

                if (confirm('Eliminare record?'))
                {

                    $.get("/admin/matches/goaldelete/" + delete_id, function (ret) {

                        if (ret.delete == 1)
                        {

                            $(".goal-row-delete[data-id='" + delete_id + "']").remove();
                            alert('Record eliminato con successo.');

                            getRisultato($("#MatchCalendario").val());

                        }

                    }, 'json');

                }

            });


            $('.formAdd').delegate('#GoalAdd', 'click', function () {

                if (exit != 0)
                    return;

                if ($("#MatchPartita").val() != '')
                    var edit_id = $("#MatchGoalPartita").val();
                else
                    var edit_id = '';

                var Calendario = $("#MatchCalendario").val();

                var Squadra = $("#MatchSquadraCampionato option:selected").html();
                var Squadra_id = $("#MatchSquadraCampionato option:selected").val();

                var Atleta = $("#MatchAtleta option:selected").html();
                var Atleta_id = $("#MatchAtleta option:selected").val();

                var Goal = $("#MatchGoal").val();
                var Autogoal = $("#MatchAutogoal").val();

                var Ammonizione = $(".Ammonizione:checked").val();
                var Espulsione = $(".Espulsione:checked").val();

                var EspulsioneGiornate = $("#MatchEspulsioneGiornate").val();
                var EspulsioneFine = $("#MatchEspulsioneFine").val();

                var Motivo = $("#MatchMotivo").val();

                var goal_add = {"Matchgoal": {"GoalPartita": edit_id, "Calendario": Calendario, "SquadraCampionato": Squadra_id, "Atleta": Atleta_id, "Goal": Goal, "Autogoal": Autogoal, "Ammonizione": Ammonizione, "Espulsione": Espulsione, "EspulsioneGiornate": EspulsioneGiornate, "EspulsioneFine": EspulsioneFine, "Motivo": Motivo}};

                var Delete = '<a class="GoalDelete" href="javascript:;"><img src="/img/timmyshare/icon_delete.png"/></a>';

                exit = 1;

                $.post("/admin/matches/goaladd", goal_add, function (ret) {

                    if (ret.aggiunto != 0)
                    {

                        var Edit = '<a class="GoalEdit" data-id="' + ret.aggiunto + '" href="javascript:;"><img src="/img/timmyshare/icon_edit.png"/></a>';

                        if (edit_id != ret.aggiunto)
                        {

                            $(
                                    '<tr class="goal-row-delete" data-id="' + ret.aggiunto + '">' +
                                    '<td data-squadra="' + Squadra + '">' + Squadra + '</td>' +
                                    '<td data-Atleta="' + Atleta + '__' + Atleta_id + '">' + Atleta + '</td>' +
                                    '<td data-goal="' + Goal + '">' + Goal + '</td>' +
                                    '<td data-autogoal="' + Autogoal + '">' + Autogoal + '</td>' +
                                    '<td data-ammonizione="' + Ammonizione + '">' + Ammonizione + '</td>' +
                                    '<td data-espulsione="' + Espulsione + '">' + Espulsione + '</td>' +
                                    '<td data-espulsioneGiornate="' + EspulsioneGiornate + '">' + EspulsioneGiornate + '</td>' +
                                    '<td data-espulsioneFine="' + EspulsioneFine + '">' + EspulsioneFine + '</td>' +
                                    '<td data-motivo="' + Motivo + '">' + Motivo + '</td>' +
                                    '<td>' + Delete + Edit + '</td>' +
                                    '</tr>'
                                    ).insertAfter('#tr_header');

                            $('form').resetForm();
                            getAtleti($("#MatchSquadraCampionato").val(), $("#MatchCalendario").val());
                            $("#MatchAmmonizioneNo").attr('checked', true);
                            $("#MatchEspulsioneNo").attr('checked', true);
                            alert('Record aggiunto con successo.');

                        }
                        else
                        {


                            $("tr[data-id=" + edit_id + "]").each(function () {

                                $(
                                        '<tr class="goal-row-delete" data-id="' + ret.aggiunto + '">' +
                                        '<td data-squadra="' + Squadra + '">' + Squadra + '</td>' +
                                        '<td data-Atleta="' + Atleta + '__' + id_atleta + '">' + Atleta + '</td>' +
                                        '<td data-goal="' + Goal + '">' + Goal + '</td>' +
                                        '<td data-autogoal="' + Autogoal + '">' + Autogoal + '</td>' +
                                        '<td data-ammonizione="' + Ammonizione + '">' + Ammonizione + '</td>' +
                                        '<td data-espulsione="' + Espulsione + '">' + Espulsione + '</td>' +
                                        '<td data-espulsioneGiornate="' + EspulsioneGiornate + '">' + EspulsioneGiornate + '</td>' +
                                        '<td data-espulsioneFine="' + EspulsioneFine + '">' + EspulsioneFine + '</td>' +
                                        '<td data-motivo="' + Motivo + '">' + Motivo + '</td>' +
                                        '<td>' + Delete + Edit + '</td>' +
                                        '</tr>'
                                        ).insertAfter(this);
                                $(this).remove();

                            });

                            alert('Record modificato con successo.');

                            $("#MatchGoalPartita").val('');

                            $('form').resetForm();

                            getAtleti($("#MatchSquadraCampionato").val(), $("#MatchCalendario").val());

                        }



                        $('.error_atleta').html('');
                        $('.error_goal').html('');
                        $('.error_autogoal').html('');
                        $("#tr_header").removeClass('tr_last');


                    }
                    else
                    {

                        if (ret.errori == '')
                        {

                            alert('Atleta gia esistente.');

                        }

                        $('.error_atleta').html(ret.errori.Atleta);
                        $('.error_goal').html(ret.errori.Goal);
                        $('.error_autogoal').html(ret.errori.Autogoal);

                    }

                    exit = 0;

                    getRisultato(Calendario);

                }, 'json');


            });

            $(".formAdd").delegate('.GoalEdit', 'click', function () {

                var edit_id = $(this).closest('a').attr('data-id');

                var Squadra = '';
                var Atleta = '';
                var Goal = '';
                var Autogoal = '';
                var Ammonizione = '';
                var Espulsione = '';
                var EspulsioneGiornate = '';
                var EspulsioneFine = '';
                var Motivo = '';

                var i = 0;

                $(this).closest('tr').find('td').each(function (index) {

                    i = 0;

                    if ($(this).closest('tr').attr('data-id') == edit_id)
                    {

                        if (Squadra == '' && i == 0)
                        {
                            Squadra = $(this).closest('td').attr('data-squadra');
                            i = 1;
                        }
                        if (Atleta == '' && i == 0)
                        {
                            Atleta = $(this).closest('td').attr('data-atleta');
                            i = 1;
                            arrAtleta = Atleta.split('__');
                            Atleta = arrAtleta[0];
                            id_atleta = arrAtleta[1];
                        }
                        if (Goal == '' && i == 0)
                        {
                            Goal = $(this).closest('td').attr('data-goal');
                            i = 1;
                        }
                        if (Autogoal == '' && i == 0)
                        {
                            Autogoal = $(this).closest('td').attr('data-autogoal');
                            i = 1;
                        }
                        if (Ammonizione == '' && i == 0)
                        {
                            Ammonizione = $(this).closest('td').attr('data-ammonizione');
                            i = 1;
                        }
                        if (Espulsione == '' && i == 0)
                        {
                            Espulsione = $(this).closest('td').attr('data-espulsione');
                            i = 1;
                        }
                        if (EspulsioneGiornate == '' && i == 0)
                        {
                            EspulsioneGiornate = $(this).closest('td').attr('data-espulsioneGiornate');
                            i = 1;
                        }
                        if (EspulsioneFine == '' && i == 0)
                        {
                            EspulsioneFine = $(this).closest('td').attr('data-espulsioneFine');
                            i = 1;
                        }
                        if (Motivo == '' && i == 0)
                        {
                            Motivo = $(this).closest('td').attr('data-motivo');
                            i = 1;
                        }

                    }

                });

                console.log(Espulsione);
                console.log(Ammonizione);
                console.log(EspulsioneGiornate);

                $("select#MatchSquadraCampionato option").each(function () {

                    this.selected = (this.text == Squadra);

                });

                $("#MatchSquadraCampionato").trigger('change', [id_atleta]);

                //$("#MatchAtletaSearch").val(Atleta);
                //$("#MatchAtleta").val(id_atleta);
                $("#MatchGoal").val(Goal);
                $("#MatchAutogoal").val(Autogoal);

                if (Ammonizione == 'Si')
                    $("#MatchAmmonizioneSi").attr('checked', 'checked');
                else if (Ammonizione == 'No')
                    $("#MatchAmmonizioneNo").attr('checked', 'checked');

                if (Espulsione == 'Si')
                    $("#MatchEspulsioneSi").attr('checked', 'checked');
                else if (Espulsione == 'No')
                    $("#MatchEspulsioneNo").attr('checked', 'checked');

                $("#MatchMotivo").val(Motivo);
                $("#MatchEspulsioneGiornate").val(EspulsioneGiornate);

                if (EspulsioneGiornate > 0)
                    $(".EspulsioneGiornate").show();
                else
                {
                    $(".EspulsioneGiornate").hide();
                }

                $("#MatchEspulsioneFine").val(EspulsioneFine);

                $(".reset_field").css('display', 'block');

                $("#MatchGoalPartita").val(edit_id);

            });

            $('.reset_field').live('click', function () {

                $('form').resetForm();
                getAtleti($("#MatchSquadraCampionato").val(), $("#MatchCalendario").val());
                $(".reset_field").css('display', 'none');
                $("#MatchgoalGoalPartita").val('');

                $("#MatchAmmonizioneNo").attr('checked', true);
                $("#MatchEspulsioneNo").attr('checked', true);

            });

            /* Fine javascript Goal */


            /* Javascript Disciplinare */

            $(".formAdd").delegate('.disciplinareAdd', 'click', function () {

                $('.error-punti').html('');
                $('.error-descrizione').html('');

                var data = $("#MatchEditForm").serialize();

                var edit = $("#DisciplinariDisciplinare").val();

                $.post('/admin/matches/disciplinareAdd', data, function (ret) {

                    if (ret.error == 0)
                    {

                        if (edit != '')
                        {

                            $("#disciplinareTable").find('tr[data-disciplinare=' + edit + ']').remove();

                        }

                        $("#disciplinareTable").append(
                                '<tr class="disciplinari-row-delete" data-disciplinare="' + ret.add.Disciplinari.Disciplinare + '">' +
                                '<td data-squadra="' + ret.add.Disciplinari.NomeSquadra + '">' + ret.add.Disciplinari.NomeSquadra + '</td>' +
                                '<td style="display: none;" data-disciplinareChiave="' + ret.add.Disciplinari.DisciplinareChiave + '">' + ret.add.Disciplinari.DisciplinareChiave + '</td>' +
                                '<td data-descrizione="' + ret.add.Disciplinari.Descrizione + '">' + ret.add.Disciplinari.Descrizione + '</td>' +
                                '<td data-punti="' + ret.add.Disciplinari.Punti + '">' + ret.add.Disciplinari.Punti + '</td>' +
                                '<td data-sanzione="' + ret.add.Disciplinari.Sanzione + '">' + ret.add.Disciplinari.Sanzione + '</td>' +
                                '<td data-option="">' +
                                '<a class="DisciplineDelete" href="javascript:;"><img src="/img/timmyshare/icon_delete.png"/></a>' +
                                '<a class="DisciplineEdit" data-disciplinare="' + ret.add.Disciplinari.Disciplinare + '" href="javascript:;"><img src="/img/timmyshare/icon_edit.png"/></a>' +
                                '</td>' +
                                '</tr>'
                                );

                        alert('Disciplinari aggiornate con successo.');

                        $("#DisciplinariDisciplinare").val('');
                        $("#DisciplinariDisciplinareChiave").val('');
                        $("#DisciplinariDisciplinareSearch").val('');
                        $("#DisciplinariDescrizione").val('');
                        $("#DisciplinariPunti").val('');
                        $("#DisciplinariSanzione").val('');

                    }
                    else
                    {

                        $('.error-punti').html(ret.add.Punti);
                        $('.error-descrizione').html(ret.add.Descrizione);

                    }

                }, 'json');

            });

            $("#DisciplinariDisciplinareChiave").live('change', function () {

                var id_disciplinare = $("#DisciplinariDisciplinareChiave").val();

                //alert(id_disciplinare);

                if (id_disciplinare != '')
                {

                    $.get('/admin/matches/findDisciplinare/' + id_disciplinare, function (ret) {

                        $("#DisciplinariDescrizione").val(ret.Discipline.Descrizione);
                        $("#DisciplinariPunti").val(ret.Discipline.Punti);
                        $("#DisciplinariSanzione").val(ret.Discipline.Sanzione);

                    }, 'json');

                }

            });

            $(".formAdd").delegate('.DisciplineDelete', 'click', function () {

                var delete_id = $(this).closest('tr').attr('data-disciplinare');

                if (confirm('Eliminare record?'))
                {

                    $.get("/admin/matches/disciplinareDelete/" + delete_id, function (ret) {

                        if (ret.delete == 1)
                        {

                            $(".disciplinari-row-delete[data-disciplinare='" + delete_id + "']").remove();

                            alert('Record eliminato con successo.');

                        }

                    }, 'json');

                }

            });

            $('.formAdd').delegate("#MatchData", "change", function () {


                var data = $(this).val();
                var me = $(this);
                $.post('/admin/matches/checkdate', {'data': data}, function (ret) {


                    var ret = parseInt(ret);

                    if (ret > 0)
                    {

                        if (confirm('La data scelta e\' un giorno di non gioco, vuoi procedere comunque?'))
                        {

                        }
                        else
                        {

                            me.val('');
                        }

                    }

                }, 'html');

            });

            $(".formAdd").delegate('.DisciplineEdit', 'click', function () {

                $('.error-punti').html('');
                $('.error-descrizione').html('');


                var edit_id = $(this).closest('a').attr('data-disciplinare');

                var Squadra = '';
                var DisciplinareChiave = '';
                var Descrizione = '';
                var Punti = '';
                var Sanzione = '';

                var i = 0;

                $(this).closest('tr').find('td').each(function (index) {

                    i = 0;

                    if ($(this).closest('tr').attr('data-disciplinare') == edit_id)
                    {

                        if (Squadra == '' && i == 0)
                        {
                            Squadra = $(this).closest('td').attr('data-squadra');
                            i = 1;
                        }
                        if (DisciplinareChiave == '' && i == 0)
                        {
                            DisciplinareChiave = $(this).closest('td').attr('data-disciplinareChiave');
                            i = 1;
                        }
                        if (Descrizione == '' && i == 0)
                        {
                            Descrizione = $(this).closest('td').attr('data-descrizione');
                            i = 1;
                        }
                        if (Punti == '' && i == 0)
                        {
                            Punti = $(this).closest('td').attr('data-punti');
                            i = 1;
                        }
                        if (Sanzione == '' && i == 0)
                        {
                            Sanzione = $(this).closest('td').attr('data-sanzione');
                            i = 1;
                        }

                    }

                });

                $("select#MatchSquadraCampionato option").each(function () {

                    this.selected = (this.text == Squadra);

                });

                $("#DisciplinariDisciplinare").val(edit_id);
                $("#DisciplinariDisciplinareChiave").val(DisciplinareChiave);
                $("#DisciplinariDisciplinareSearch").val(Descrizione);
                $("#DisciplinariDescrizione").val(Descrizione);
                $("#DisciplinariPunti").val(Punti);
                $("#DisciplinariSanzione").val(Sanzione);

                $(".reset_disc").show();

            });

            $(".reset_disc").bind('click', function () {

                $("#DisciplinariDisciplinare").val('');
                $("#DisciplinariDisciplinareChiave").val('');
                $("#DisciplinariDisciplinareSearch").val('');
                $("#DisciplinariDescrizione").val('');
                $("#DisciplinariPunti").val('');
                $("#DisciplinariSanzione").val('');

                $(this).hide();

            });

            /* js espulsione */

            $("#MatchEspulsioneSi").live('change', function () {

                $('.hidden').show();

            });

            $("#MatchEspulsioneNo").live('change', function () {

                $('.hidden').hide();
                $("#MatchEspulsioneFine").val('');
                $("#MatchEspulsioneGiornate").val('');

            });

            $('.formAdd').delegate("#MatchCampionato", "change", function () {

                var val = $(this).val();

                if (val != '')
                {

                    $("#MatchCampo").attr('data-url', '');
                    $("#MatchCampo").attr('data-url', '/admin/matches/searchCampoByCampionato/' + val);
                    $("#MatchCampo").attr('disabled', false);

                    $("#MatchOra").attr('data-url', '');
                    $("#MatchOra").attr('data-url', '/admin/matches/getOre/' + val);
                    $("#MatchOra").attr('disabled', false);

                    getCampi();

                }

            });

            function getCampi() {

                var campo_id = '<?= (isset($this->data['Match']['Campo']) ? $this->data['Match']['Campo'] : ''); ?>';

                $("#MatchCampo").empty();

                $.get($("#MatchCampo").attr('data-url'), function (data) {

                    for (i in data)
                    {

                        if (data[i].id == campo_id)
                            var selected = 'SELECTED';
                        else
                            var selected = '';

                        var option = $('<option ' + selected + '>').attr('value', data[i].id).text(data[i].label);
                        $("#MatchCampo").append(option);

                    }

                }, 'json');

            }

            getCampi();

        });

        /* Fine javascript Disciplinari */
    }
</script>
<script>

    //GIUSEPPE 10/11/2016------------------------------------------------

    $(document).ready(function () {

        var id_campionato = '<?= $this->data['Match']['Campionato'] ?>';

        var id_calendario = '<?= $this->data['Match']['Calendario'] ?>';

        var id_squadra_campionato_casa = $("#MatchCasa").val();
        '<? //=$this->data['Casa']['SquadraCampionato']                           ?>';

        var id_squadra_campionato_trasferta = $("#MatchTrasferta").val();
        '<? //=$this->data['Trasferta']['SquadraCampionato']                           ?>';

        console.log($("#MatchCasa").val());

        console.log(id_squadra_campionato_casa);

        console.log($("#MatchTrasferta").val());

        console.log(id_squadra_campionato_trasferta);

        var squadra_casa;

        var squadra_trasferta;

        //----------------------------------------------------
        //$('#SetTennisTable').append('<tr><th>1</th><th>2</th><th>3</th></tr>');
        //----------------------------------------------------

        //console.log("...->" + id_squadra_campionato_trasferta );

        $.get("/admin/matches/idsport/" + id_campionato, function (data, status) {
            //alert( data );
            switch (parseInt(data))
            {
                case 0 || 2:
                    $("#settore_calcio").show();
                    $("#settore_tennis_all").hide();
                    console.log("calcio");
                    break;

                case 1:
                    $("#settore_tennis_all").show();
                    $("#settore_calcio").hide();

                    console.log("tennis");
                    read_points(id_calendario, id_squadra_campionato_casa, id_squadra_campionato_trasferta);

                    break;
            }
        });

//        for (var i = 1; i <= 6; i++)
//        {
//            for (var j = 1; j <= 3; j++)
//            {
//                var id_textbox = '.s_' + i + '_' + j;
//
//                console.log(id_textbox);
//
//                $(id_textbox).mask('00');
//            }
//        }

        var point_to_text_window; //questa viene generata in controllers/matches_controller.php -> function admin_tennispoint ed è del tipo { points:{...}, check_win{...}}

        var point_to_transfert;

        $("#settore_tennis").keyup(function () { // questo entra in funzione quando scrivo i punti nelle text box

            console.log("settore tennis");

            //point_to_transfert = point_to_text_window;

            for (var prop in point_to_text_window.points)
            {

                point_to_text_window.points[prop] = $("#" + prop).val();

            }
            ;
            //console.log(JSON.stringify(point_to_text_window));

            invert_for_trasfert();

            send_data(JSON.stringify(point_to_text_window));

        });

        $("#settore_tennis").click(function () {

            for (var check in point_to_text_window.check_win)
            {

                //console.log($("#"+check).is(":checked"));
                var val_check;
                switch ($("#" + check).is(":checked"))
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

            console.log("TRANSFERT");

            console.log(invert_for_trasfert());

            for (var id_atleth in point_to_text_window.athletes)
            {
                /*console.log("-----");
                 console.log(id_atleth);*/
                point_to_text_window.athletes[id_atleth] = $("#" + id_atleth).val();
            }

            console.log(point_to_text_window);

            send_data(JSON.stringify(point_to_text_window), JSON.stringify(invert_for_trasfert()));
        });




        // confronta gli atleti nel doppio // ------------------------------------------

        // --- casa

        $("#casa_d1").change(function () {
            //alert("The text has been changed.");
            console.log(parseInt($("#casa_d1").val) + " --------- " + parseInt($("#casa_d2").val()));
            if (parseInt($("#casa_d1").val()) == parseInt($("#casa_d2").val()))
            {
                alert("atleta gia inserito nel doppio");
                $("#casa_d1").val("0");
            }
        });

        $("#casa_d2").change(function () {
            //alert("The text has been changed.");
            console.log(parseInt($("#casa_d1").val) + " --------- " + parseInt($("#casa_d2").val()));
            if (parseInt($("#casa_d1").val()) == parseInt($("#casa_d2").val()))
            {
                alert("atleta gia inserito nel doppio");
                $("#casa_d2").val("0");
            }
        });

        // --- trasferta

        $("#trasferta_d1").change(function () {
            //alert("The text has been changed.");
            console.log(parseInt($("#casa_d1").val) + " --------- " + parseInt($("#casa_d2").val()));
            if (parseInt($("#trasferta_d1").val()) == parseInt($("#trasferta_d2").val()))
            {
                alert("atleta gia inserito nel doppio");
                $("#trasferta_d1").val("0");
            }
        });

        $("#trasferta_d2").change(function () {
            //alert("The text has been changed.");
            console.log(parseInt($("#trasferta_d1").val) + " --------- " + parseInt($("#trasferta_d2").val()));
            if (parseInt($("#trasferta_d1").val()) == parseInt($("#trasferta_d2").val()))
            {
                alert("atleta gia inserito nel doppio");
                $("#trasferta_d2").val("0");
            }
        });

        // -----------------------------------------------------------------------------





        function invert_for_trasfert()
        {
            for (i = 1; i <= 3; i++) // inverto il json per il punteggio dell'avversario
            {
                dispari = (2 * i - 1);
                pari = (2 * i);
                //console.log("i disp ..->" + dispari + " ---- i pari ..->" + pari);
                point_to_transfert.points["s_" + pari + "_1"] = point_to_text_window.points["s_" + dispari + "_1"];
                point_to_transfert.points["s_" + pari + "_2"] = point_to_text_window.points["s_" + dispari + "_2"];
                point_to_transfert.points["s_" + pari + "_3"] = point_to_text_window.points["s_" + dispari + "_3"];

                point_to_transfert.points["s_" + dispari + "_1"] = point_to_text_window.points["s_" + pari + "_1"];
                point_to_transfert.points["s_" + dispari + "_2"] = point_to_text_window.points["s_" + pari + "_2"];
                point_to_transfert.points["s_" + dispari + "_3"] = point_to_text_window.points["s_" + pari + "_3"];

                point_to_transfert.check_win["s_" + pari + "_4"] = point_to_text_window.check_win["s_" + dispari + "_4"];

                point_to_transfert.check_win["s_" + dispari + "_4"] = point_to_text_window.check_win["s_" + pari + "_4"];
            }

            // devo invertire anche i risultati la "trasferta" deve risultare "casa" (mi serve per il punteggio del ranking)
            // non mi serve il for.. faccio prima cambiando direttamente

            point_to_transfert.athletes["casa_s1"] = point_to_text_window.athletes["trasferta_s1"];
            point_to_transfert.athletes["casa_s2"] = point_to_text_window.athletes["trasferta_s2"];
            point_to_transfert.athletes["casa_d1"] = point_to_text_window.athletes["trasferta_d1"];
            point_to_transfert.athletes["casa_d2"] = point_to_text_window.athletes["trasferta_d2"];
            point_to_transfert.athletes["trasferta_s1"] = point_to_text_window.athletes["casa_s1"];
            point_to_transfert.athletes["trasferta_s2"] = point_to_text_window.athletes["casa_s2"];
            point_to_transfert.athletes["trasferta_d1"] = point_to_text_window.athletes["casa_d1"];
            point_to_transfert.athletes["trasferta_d2"] = point_to_text_window.athletes["casa_d2"];

            return point_to_transfert;
        }

        function read_points(id_calendario, id_squadra_campionato_casa, id_squadra_campionato_trasferta) // 
        {
            $link = "/admin/matches/tennispoint/" + id_calendario + "/" + id_squadra_campionato_casa + "/" + id_squadra_campionato_trasferta;

            console.log($link);

            $.get($link, function (data, status) {
                //console.log((data));
                point_to_text_window = JSON.parse(data);

                if (!point_to_text_window.hasOwnProperty('athletes') || Object.keys(point_to_text_window.athletes).length == 0)
                {
                    //faccio questa operazione perchè l'oggetto "athletes" è stato aggiunto in seguito
                    point_to_text_window.athletes =
                            {
                                "casa_s1": "0"
                                , "casa_s2": "0"
                                , "casa_d1": "0"
                                , "casa_d2": "0"
                                , "trasferta_s1": "0"
                                , "trasferta_s2": "0"
                                , "trasferta_d1": "0"
                                , "trasferta_d2": "0"
                            }
                    //console.log("no proprietà");
                    //console.log(point_to_text_window);
                }

                point_to_transfert = JSON.parse(data); // questi valori li invertirò quando updaterò i valori

                if (!point_to_transfert.hasOwnProperty('athletes') || Object.keys(point_to_transfert.athletes).length == 0)
                {
                    //faccio questa operazione perchè l'oggetto "athletes" è stato aggiunto in seguito
                    point_to_transfert.athletes =
                            {
                                "casa_s1": "0"
                                , "casa_s2": "0"
                                , "casa_d1": "0"
                                , "casa_d2": "0"
                                , "trasferta_s1": "0"
                                , "trasferta_s2": "0"
                                , "trasferta_d1": "0"
                                , "trasferta_d2": "0"
                            }
                    //console.log("no proprietà");
                    //console.log(point_to_text_window);
                }

                console.log(point_to_text_window);

                for (var prop in point_to_text_window.points)
                { // qui ricavo le proprietà dell'oggetto json      (punteggi)      
                    //console.log("prop " + prop);
                    $("#" + prop).val(point_to_text_window.points[prop]);
                }

                for (var prop in point_to_text_window.check_win)
                { // qui ricavo le proprietà dell'oggetto json  (checkbox)  
                    //console.log("prop " + prop);

                    val_check = parseInt(point_to_text_window.check_win[prop])

                    $("#" + prop).attr('checked', val_check);

                }

                read_teams();

            });
        }

        function send_data(data_json, data_transfert_json)
        {
            $link = "/admin/matches/insertpoint/";

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
            , function (data, status) {
                console.log("punti : " + data);
                //$("#matchRisultato").val(data);
                $("span#matchRisultato").empty().text(data);
                //alert($("#matchRisultato").val());
            });
        }


        function read_teams()
        {
            var link = '/admin/matches/goalSearchAthleteByTeam/' + id_squadra_campionato_casa + '/' + id_calendario + '/?tt=1'
            console.log(link);
            $.get(link, function (ret) {
                console.log(ret);

                squadra_casa = ret;


                link = '/admin/matches/goalSearchAthleteByTeam/' + id_squadra_campionato_trasferta + '/' + id_calendario + '/?tt=1'

                $.get(link, function (ret) {

                    squadra_trasferta = ret;

                    console.log(ret);

                    insert_input_select(squadra_casa, squadra_trasferta);

                    for (var prop in point_to_text_window.athletes) // le squadre vengono lette alla fine e poi
                    {
                        $("#" + prop + " option[value=" + point_to_text_window.athletes[prop] + "]").attr('selected', 'selected');
                    }

                }, 'json');

            }, 'json');

        }

        var object_points = {};

        function insert_input_select(squadra_casa, squadra_trasferta)
        {
            var stringToTeam = "<option value='0'>In Sospeso </option>";

            for (var prop in squadra_casa)
            {

                //console.log("-----------------");
                //console.log(prop);

                stringToTeam += "<option value='" + squadra_casa[prop].id + "'>" + squadra_casa[prop].nome + "</option>";

                object_points[squadra_casa[prop].id] = 0;


            }
            $("#casa_s1  option").remove();

            $("#casa_s1").append(stringToTeam);


            $("#casa_s2  option").remove();

            $("#casa_s2").append(stringToTeam);



            $("#casa_d1  option").remove();

            $("#casa_d1").append(stringToTeam);


            $("#casa_d2  option").remove();

            $("#casa_d2").append(stringToTeam);




            stringToTeam = "<option value='0'>In Sospeso </option>";

            for (var prop in squadra_trasferta)
            {
                //console.log(prop);

                stringToTeam += "<option value='" + squadra_trasferta[prop].id + "'>" + squadra_trasferta[prop].nome + "</option>"

                object_points[squadra_trasferta[prop].id] = 0;

            }

            $("#trasferta_s1  option").remove();

            $("#trasferta_s1").append(stringToTeam);


            $("#trasferta_s2  option").remove();

            $("#trasferta_s2").append(stringToTeam);



            $("#trasferta_d1  option").remove();

            $("#trasferta_d1").append(stringToTeam);


            $("#trasferta_d2  option").remove();

            $("#trasferta_d2").append(stringToTeam);

            console.log(squadra_casa);

            calcola_punti();

        }



        function calcola_punti()
        {

            var set_1;
            var set_1_p; // squadra non vincente
            if (parseInt(point_to_text_window.check_win["s_1_4"]))
            {

                set_1 = point_to_text_window.athletes["casa_s1"];
                set_1_p = point_to_text_window.athletes["trasferta_s1"];
            }
            if (parseInt(point_to_text_window.check_win["s_2_4"]))
            {

                set_1 = point_to_text_window.athletes["trasferta_s1"];
                set_1_p = point_to_text_window.athletes["casa_s1"];

            }

            object_points[set_1] += 3;
            object_points[set_1_p] += 1;

            //console.log("set 1 -> " + set_1);
            ///////////////

            var set_2;
            var set_2_p;
            if (parseInt(point_to_text_window.check_win["s_3_4"]))
            {

                set_2 = point_to_text_window.athletes["casa_s2"];
                set_2_p = point_to_text_window.athletes["trasferta_s2"];
            }
            if (parseInt(point_to_text_window.check_win["s_4_4"]))
            {
                set_2 = point_to_text_window.athletes["trasferta_s2"];
                set_2_p = point_to_text_window.athletes["casa_s2"];
            }

            object_points[set_2] += 3;
            object_points[set_2_p] += 1;

            //console.log("set 2 -> " + set_2);
            ///////////////

            var set_3 = [0, 0];
            var set_3_p = [0, 0];
            if (parseInt(point_to_text_window.check_win["s_5_4"]))
            {
                //squadra_trasferta.punti
                set_3[0] = point_to_text_window.athletes["casa_d1"];
                set_3[1] = point_to_text_window.athletes["casa_d2"];
                set_3_p[0] = point_to_text_window.athletes["trasferta_d1"];
                set_3_p[1] = point_to_text_window.athletes["trasferta_d2"];

            }
            if (parseInt(point_to_text_window.check_win["s_6_4"]))
            {
                //squadra_trasferta.punti
                set_3[0] = point_to_text_window.athletes["trasferta_d1"];
                set_3[1] = point_to_text_window.athletes["trasferta_d2"];
                set_3_p[0] = point_to_text_window.athletes["casa_d1"];
                set_3_p[1] = point_to_text_window.athletes["casa_d2"];
            }

            object_points[set_3[0]] += 2;

            object_points[set_3[1]] += 2;

            object_points[set_3_p[0]] += 1;

            object_points[set_3_p[1]] += 1;

            //console.log("set 3 -> ");
            //console.log(set_3);

            //console.log("object points -> ");
            //console.log(object_points);

            var string_to_table = "";

            for (var prop in squadra_casa)
            {
                if (object_points[squadra_casa[prop].id] > 0)
                {
                    string_to_table += '<tr><td>' + $("#MatchSquadraCasaSearch").val() + '</td><td>' + squadra_casa[prop].nome + '</td><td>' + object_points[squadra_casa[prop].id] + '</td></tr>';
                }

            }

            $('#SetTennisTable').append(string_to_table);

            var string_to_table = "";

            for (var prop in squadra_trasferta)
            {
                if (object_points[squadra_trasferta[prop].id] > 0)
                {
                    string_to_table += '<tr><td>' + $("#MatchSquadraTrasfertaSearch").val() + '</td><td>' + squadra_trasferta[prop].nome + '</td><td>' + object_points[squadra_trasferta[prop].id] + '</td></tr>';
                }

            }

            $('#SetTennisTable').append(string_to_table);
        }



        $("#reset_points_tennis").click(function ()
        {
            var domanda = confirm("Sicuro di voler resettare i punteggi?\nUna volta confermata operazione\nnon sarà possibile\nrecuperare i punteggi precedenti");

            if (domanda === false)
            {
                return;
            }

            for (var i = 1; i <= 6; i++)
            {
                for (var j = 1; j <= 3; j++)
                {
                    $("#s_" + i + "_" + j).val('');
                }
            }

            for (var i = 1; i <= 6; i++)
            {
                $("#s_" + i + "_4").attr('checked', false);
            }

            $("#casa_s1").val("0");
            $("#trasferta_s1").val("0");

            $("#casa_s2").val("0");
            $("#trasferta_s2").val("0");


            $("#casa_d1").val("0");
            $("#casa_d2").val("0");

            $("#trasferta_d1").val("0");
            $("#trasferta_d2").val("0");

            $link = "/admin/matches/resetpoints/";

            console.log($link);

            //console.log(data_transfert_json);

            $.post($link,
                    {
                        id_calendario: id_calendario
                        , id_squadra_campionato_casa: id_squadra_campionato_casa
                        , id_squadra_campionato_trasferta: id_squadra_campionato_trasferta
                    }
            , function (data, status) {
                console.log(data);

                setTimeout(function () {
                    alert(data);
                }, 1000)

            });

        });
    });


    //-------------------------------------------------------------------
</script>

<?= $this->element("/backend/edit_scripts"); ?>
<?= $this->element("/backend/add_edit_scripts"); ?>

<?= $this->Form->create('Match', array('url' => '/admin/matches/edit/' . $this->data['Match']['Calendario'] . '?modded=true', 'class' => 'formAdd', 'type' => 'file', 'id' => 'MatchEditForm')); ?>

<div class="form_header">
    <? //echo json_encode($this->data);//print_r($this->data);  ?>

    <h2 id="matchH2">Modifica gara <span><?= $this->data['Match']['CasaNome']; ?> - <?= $this->data['Match']['TrasfertaNome']; ?></span> <? if (!empty($this->data['Match']['Risultato'])): ?>Risultato: <span id="matchRisultato"><?= $this->data['Match']['Risultato']; ?></span><? else: ?>Risultato: <span id="matchRisultato"></span><? endif; ?></h2>
    <ul>

        <li><?= $this->Form->submit('reset campi', array('type' => 'reset', 'div' => false)); ?></li>
        <li><?= $this->Form->submit('annulla', array('type' => 'button', 'div' => false, 'id' => 'formReset')); ?></li>
        <? if (isset($_GET['modded'])): ?>
            <li><?= $this->Form->submit('modifica', array('type' => 'submit', 'div' => false)); //tasto "salva" //                          ?></li>
            <li><input type="button" value="finito" onclick="location.href = '/admin/matches/index'"/></li>
        <? else: ?>
            <li><input type="submit" value="modifica" onClick="$('a.index-row-edit[data-id=<?= $this->data['Match']['Calendario']; ?>]').trigger('click');" />
            <? endif; ?>
    </ul>
    <div class="clear"></div>

</div><!-- close form_header -->
<div class="clear"></div>   

<h3>Creazione gara</h3>

<?= $this->Form->input('Risultato', array('type' => 'hidden', 'value' => $this->data['Match']['Risultato'])); ?>
<?= $this->Form->input('CasaNome', array('type' => 'hidden', 'value' => $this->data['Match']['CasaNome'])); ?>
<?= $this->Form->input('TrasfertaNome', array('type' => 'hidden', 'value' => $this->data['Match']['TrasfertaNome'])); ?>

<?= $this->Form->input('Calendario'); ?>
<?= $this->Form->input('Data', array('label' => 'Data', 'type' => 'text', 'class' => 'datePicker')); ?>
<?
$tmp_data = $this->data['Match']['Data'];
$field = $tmp_data;
$data = array();
if (preg_match('~(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.]((19|20)[0-9]{2})~Ui', $field, $data))
{

    $tmp_data = $data[3] . "-" . $data[2] . "-" . $data[1];
}
$days = array('Domenica', 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato');

$time = strtotime($tmp_data);
$tmp = date("w", $time);

$giorno = $days[$tmp];
?>  
<?= $this->Form->input('Giorno', array('label' => 'Giorno', 'readonly' => true, 'value' => $giorno, 'class' => 'small')); ?>        
<?= $this->Form->input('Ora', array('label' => 'Ora', 'disabled' => false, 'type' => 'text', 'class' => 'autoComplete control_ora small', 'data-url' => '/admin/matches/getOre/' . $this->data['Match']['Campionato'], 'data-dest' => 'MatchOraFittizio')); ?>
<?= $this->Form->input('CampionatoSearch', array('label' => 'Campionato', 'readonly' => true, 'class' => 'autoComplete big', 'data-url' => '/admin/matches/searchCampionato', 'data-dest' => 'MatchCampionato')); ?>
<?= $this->Form->input('Campionato', array('type' => 'hidden', 'readonly' => true,)); ?>
<?= $this->Form->input('GironeSearch', array('label' => 'Girone', 'readonly' => true, 'class' => 'autoComplete', 'data-url' => '/admin/matches/searchGirone', 'data-dest' => 'MatchGironeCampionato')); ?>
<?= $this->Form->input('GironeCampionato', array('type' => 'hidden', 'readonly' => true,)); ?>


<?= $this->Form->input('SquadraCasaSearch', array('label' => 'Casa', 'readonly' => true, 'class' => 'autoComplete', 'data-url' => '/admin/matches/searchSquadraCampionato/' . $this->data['Match']['Campionato'] . '/' . $this->data['Match']['GironeCampionato'], 'data-dest' => 'MatchCasa')); ?>
<?= $this->Form->input('Casa', array('type' => 'hidden', 'readonly' => true,)); ?>
<?= $this->Form->input('SquadraTrasfertaSearch', array('label' => 'Trasferta', 'readonly' => true, 'class' => 'autoComplete', 'data-url' => '/admin/matches/searchSquadraCampionato/' . $this->data['Match']['Campionato'] . '/' . $this->data['Match']['GironeCampionato'], 'data-dest' => 'MatchTrasferta')); ?>
<?= $this->Form->input('Trasferta', array('type' => 'hidden', 'readonly' => true)); ?>

<div class="clear"></div>   

<?= $this->Form->input('Giornata', array('label' => 'Giornata', 'type' => 'text', 'readonly' => true)); ?>
<?= $this->Form->input('Partita', array('label' => 'Partita', 'type' => 'text')); ?>

<?= $this->Form->input('Campo', array('type' => 'select', 'label' => 'Nome campo', 'empty' => true, 'data-url' => '/admin/matches/searchCampoByCampionato/' . $this->data['Match']['Campionato'])); ?>


<?=
$this->Form->input('Bloccato', array(
    'type' => 'radio',
    'options' => array('S' => 'Si', 'N' => 'No'),
));
?>
<?=
$this->Form->input('Festivo', array(
    'type' => 'radio',
    'options' => array('S' => 'Si', 'N' => 'No'),
));
?>


<?= $this->Form->input('NomeGara', array('label' => 'Nome gara', 'type' => 'text')); ?>


<?
$options = array();
$options[''] = '';
foreach ($causali as $causale)
{
    $options[$causale['Causalresult']['CausaleRisultato']] = $causale['Causalresult']['Descrizione'];
}
?>

<?= $this->Form->input('CausaleRisultato', array('type' => 'select', 'options' => $options)); ?>

<div class="clear"></div>

<div id="settore_tennis_all" style="display: none">
    <br>
    <br>
    <button type="button" id="reset_points_tennis">RESET PUNTI</button>
    <br>
    <div id="settore_tennis">  
        <h3>SINGOLO 1</h3>
        <table id="TennisPointTable1" class="form_table ">
            <tr >
                <th>Squadra</th>
                <th>Atleta</th>
                <th>1 set</th>
                <th>2 set</th>
                <th>3 set</th>
                <th>vincitore</th>
            </tr>

            <tr>
                <td><?= $this->Form->input('SquadraCasaSearch', array('label' => 'squadra 1', 'readonly' => true, 'data-url' => '/admin/matches/searchSquadraCampionato/' . $this->data['Match']['Campionato'] . '/' . $this->data['Match']['GironeCampionato'])); ?></td>
                <td><?= $this->Form->input('casa', array('type' => 'select', 'empty' => true, 'id' => 'casa_s1')); ?><!--<select id="squadra_casa"></select>--></td>
                <td><?= $this->Form->input('set1', array('type' => 'text', 'id' => 's_1_1', 'class' => 's_1_1', 'size' => '3', 'maxlength' => '1', 'style' => 'text-align:center')); ?></td>
                <td><?= $this->Form->input('set2', array('type' => 'text', 'id' => 's_1_2', 'class' => 's_1_2', 'size' => '3', 'maxlength' => '1', 'style' => 'text-align:center')); ?></td>
                <td><?= $this->Form->input('set3', array('type' => 'text', 'id' => 's_1_3', 'class' => 's_1_3', 'size' => '3', 'maxlength' => '2', 'style' => 'text-align:center')); ?></td>
                <td><input type="radio" name="c_1" id="s_1_4" value="c_1"></td>
            </tr>


            <tr>
                <td><?= $this->Form->input('SquadraTrasfertaSearch', array('label' => 'squadra 2', 'readonly' => true, 'data-url' => '/admin/matches/searchSquadraCampionato/' . $this->data['Match']['Campionato'] . '/' . $this->data['Match']['GironeCampionato'])); ?></td>
                <td><?= $this->Form->input('trasf', array('type' => 'select', 'empty' => true, 'id' => 'trasferta_s1')); ?> </td>
                <td><?= $this->Form->input('set1', array('type' => 'text', 'id' => 's_2_1', 'class' => 's_2_1', 'size' => '3', 'maxlength' => '1', 'style' => 'text-align:center')); ?></td>
                <td><?= $this->Form->input('set2', array('type' => 'text', 'id' => 's_2_2', 'class' => 's_2_2', 'size' => '3', 'maxlength' => '1', 'style' => 'text-align:center')); ?></td>
                <td><?= $this->Form->input('set3', array('type' => 'text', 'id' => 's_2_3', 'class' => 's_2_3', 'size' => '3', 'maxlength' => '2', 'style' => 'text-align:center')); ?></td>
                <td><input type="radio" name="c_1" id="s_2_4" value="c_1"></td>
            </tr>



        </table>
        <br>
        <br>

        <h3>SINGOLO 2</h3>
        <table id="TennisPointTable1" class="form_table ">
            <tr >
                <th>Squadra</th>
                <th>Atleta</th>
                <th>1 set</th>
                <th>2 set</th>
                <th>3 set</th>
                <th>vincitore</th>
            </tr>

            <tr>
                <td><?= $this->Form->input('SquadraCasaSearch', array('label' => 'squadra 1', 'readonly' => true, 'data-url' => '/admin/matches/searchSquadraCampionato/' . $this->data['Match']['Campionato'] . '/' . $this->data['Match']['GironeCampionato'])); ?></td>
                <td><?= $this->Form->input('casa', array('type' => 'select', 'empty' => true, 'id' => 'casa_s2')); ?> </td>
                <td><?= $this->Form->input('set1', array('type' => 'text', 'id' => 's_3_1', 'class' => 's_3_1', 'size' => '3', 'maxlength' => '1', 'style' => 'text-align:center')); ?></td>
                <td><?= $this->Form->input('set2', array('type' => 'text', 'id' => 's_3_2', 'class' => 's_3_2', 'size' => '3', 'maxlength' => '1', 'style' => 'text-align:center')); ?></td>
                <td><?= $this->Form->input('set3', array('type' => 'text', 'id' => 's_3_3', 'class' => 's_3_3', 'size' => '3', 'maxlength' => '2', 'style' => 'text-align:center')); ?></td>
                <td><input type="radio" name="c_2" id="s_3_4" value="c_2"></td>
                <!--<td><? //=$this->Form->radio('published', ['type' => 'checkbox']);                           ?></td>-->
            </tr>


            <tr>
                <td><?= $this->Form->input('SquadraTrasfertaSearch', array('label' => 'squadra 2', 'readonly' => true, 'data-url' => '/admin/matches/searchSquadraCampionato/' . $this->data['Match']['Campionato'] . '/' . $this->data['Match']['GironeCampionato'])); ?></td>
                <td><?= $this->Form->input('traf', array('type' => 'select', 'empty' => true, 'id' => 'trasferta_s2')); ?> </td>
                <td><?= $this->Form->input('set1', array('type' => 'text', 'id' => 's_4_1', 'class' => 's_4_1', 'size' => '3', 'maxlength' => '1', 'style' => 'text-align:center')); ?></td>
                <td><?= $this->Form->input('set2', array('type' => 'text', 'id' => 's_4_2', 'class' => 's_4_2', 'size' => '3', 'maxlength' => '1', 'style' => 'text-align:center')); ?></td>
                <td><?= $this->Form->input('set3', array('type' => 'text', 'id' => 's_4_3', 'class' => 's_4_3', 'size' => '3', 'maxlength' => '2', 'style' => 'text-align:center')); ?></td>
                <td><input type="radio" name="c_2" id="s_4_4" value="c_2"></td>
            </tr>

        </table>
        <br>
        <br>

        <h3>DOPPIO</h3>
<!--        <table id="TennisPointTable1" class="form_table form_table_full">   -->
        <table id="TennisPointTable1" class="form_table">
            <tr >
                <th>Squadra</th>
                <th>Atleta</th>
                <th>1 set</th>
                <th>2 set</th>
                <th>3 set</th>
                <th>vincitore</th>
            </tr>

            <tr>
                <td><?= $this->Form->input('SquadraCasaSearch', array('label' => 'squadra 1', 'readonly' => true, 'data-url' => '/admin/matches/searchSquadraCampionato/' . $this->data['Match']['Campionato'] . '/' . $this->data['Match']['GironeCampionato'])); ?></td>
                <td><?= $this->Form->input('casa', array('type' => 'select', 'empty' => true, 'id' => 'casa_d1')); ?><div class="clear"></div>
                    <?= $this->Form->input('casa', array('type' => 'select', 'empty' => true, 'id' => 'casa_d2')); ?></td>
                <td><?= $this->Form->input('set1', array('type' => 'text', 'id' => 's_5_1', 'class' => 's_5_1', 'size' => '3', 'maxlength' => '1', 'style' => 'text-align:center')); ?></td>
                <td><?= $this->Form->input('set2', array('type' => 'text', 'id' => 's_5_2', 'class' => 's_5_2', 'size' => '3', 'maxlength' => '1', 'style' => 'text-align:center')); ?></td>
                <td><?= $this->Form->input('set3', array('type' => 'text', 'id' => 's_5_3', 'class' => 's_5_3', 'size' => '3', 'maxlength' => '2', 'style' => 'text-align:center')); ?></td>
                <td><input type="radio" name="c_3" id="s_5_4" value="c_3"></td>
            </tr>


            <tr>
                <td><?= $this->Form->input('SquadraTrasfertaSearch', array('label' => 'squadra 2', 'readonly' => true, 'data-url' => '/admin/matches/searchSquadraCampionato/' . $this->data['Match']['Campionato'] . '/' . $this->data['Match']['GironeCampionato'])); ?></td>
                <td><?= $this->Form->input('trasf', array('type' => 'select', 'empty' => true, 'id' => 'trasferta_d1')); ?><div class="clear"></div>
                    <?= $this->Form->input('trasf', array('type' => 'select', 'empty' => true, 'id' => 'trasferta_d2')); ?></td>
                <td><?= $this->Form->input('set1', array('type' => 'text', 'id' => 's_6_1', 'class' => 's_6_1', 'size' => '3', 'maxlength' => '1', 'style' => 'text-align:center')); ?></td>
                <td><?= $this->Form->input('set2', array('type' => 'text', 'id' => 's_6_2', 'class' => 's_6_2', 'size' => '3', 'maxlength' => '1', 'style' => 'text-align:center')); ?></td>
                <td><?= $this->Form->input('set3', array('type' => 'text', 'id' => 's_6_3', 'class' => 's_6_3', 'size' => '3', 'maxlength' => '2', 'style' => 'text-align:center')); ?></td>
                <td><input type="radio" name="c_3" id="s_6_4" value="c_3"></td>
            </tr>

        </table>

        <h3>PUNTEGGI</h3>
        <!--<table id="SetTennisTable" class="form_table form_table_full">
                <tr id="tr_header" class="tr_last">-->
        <table id="SetTennisTable" class="form_table">
            <tr>
                <th>Squadra</th>
                <th>Atleta</th>
                <th>Punti</th>
            </tr>

        </table>
    </div>
</div>


<div id="settore_calcio">
    <h3>Creazione LDA (non obbligatorio) </h3>

    <?= $this->Form->input('LDA', array('type' => 'hidden')); ?>

    <div class="input text">
        <?= $this->Form->input('ArbitroSearch', array('div' => false, 'label' => 'Arbitro', 'class' => 'searchAthlete', 'data-id' => $this->data['Match']['Arbitro'], 'data-arbitro' => 1, 'data-url' => '/admin/athletes/searchAthlete', 'data-dest' => 'MatchArbitro')); ?>
        <?= $this->Form->input('Arbitro', array('type' => 'hidden')); ?>
        <?
        if (isset($ArbitroSearchError) && !empty($ArbitroSearchError)):
            ?>
            <div class="error-message"><?= $ArbitroSearchError; ?></div>
            <?
        endif;
        ?>
    </div>  

    <?= $this->Form->input('Arbitro2Search', array('label' => 'Arbitro Singolo', 'class' => 'searchAthlete', 'data-id' => $this->data['Match']['Arbitro2'], 'data-arbitro' => 1, 'data-url' => '/admin/athletes/searchAthlete', 'data-dest' => 'MatchArbitro2')); ?>
    <?= $this->Form->input('Arbitro2', array('type' => 'hidden')); ?>

    <?= $this->Form->input('DelegatoSearch', array('label' => 'Delegato', 'class' => 'searchAthlete', 'data-id' => $this->data['Match']['Delegato'], 'data-arbitro' => 1, 'data-url' => '/admin/athletes/searchAthlete', 'data-dest' => 'MatchDelegato')); ?>
    <?= $this->Form->input('Delegato', array('type' => 'hidden')); ?>

    <?= $this->Form->input('DelegatoASearch', array('label' => 'Delegato Singolo', 'class' => 'searchAthlete', 'data-id' => $this->data['Match']['DelegatoA'], 'data-arbitro' => 1, 'data-url' => '/admin/athletes/searchAthlete', 'data-dest' => 'MatchDelegatoA')); ?>
    <?= $this->Form->input('DelegatoA', array('type' => 'hidden')); ?>

    <div class="clear"></div>   

    <h3>Goal</h3>

    <?
    $squadre = array();

    $squadre[$calendario['Casa']['SquadraCampionato']] = $calendario['Match']['CasaNome'];
    $squadre[$calendario['Trasferta']['SquadraCampionato']] = $calendario['Match']['TrasfertaNome'];
    ?>
    <!-- //GIUSEPPE -> $this->set('goals', $data); in matches_controller.php -->
    <table id="GoalTable" class="form_table form_table_full">

        <? $n = count($goals); ?>
        <? $i = 1; ?>

        <tr id="tr_header" <? if ($goals == array()): ?>class="tr_last"<? endif; ?>>
            <th>Squadra</th>
            <th>Atleta</th>
            <th>Goal</th>
            <th>Autogoal</th>
            <th>Ammonizione</th>
            <th>Espulsione</th>
            <th>Giornate</th>
            <th>Fine</th>
            <th>Motivo</th>
            <th>Opzioni</th>
        </tr>

        <? foreach ($goals as $goal): ?>

            <tr class="goal-row-delete <? if ($i == $n): ?>tr_last<? endif; ?>" data-id="<?= $goal['Matchgoal']['GoalPartita']; ?>">

                <td data-squadra="<?= $goal['Matchgoal']['NomeSquadra']; ?>"><?= $goal['Matchgoal']['NomeSquadra']; ?></td>
                <td data-atleta="<?= (($goal['Athlete']['Anagrafica'] != '') ? $goal['Athlete']['Anagrafica'] : 'In sospeso') . '__' . (($goal['Athlete']['Atleta'] != '') ? $goal['Athlete']['Atleta'] : 0); ?>"><?= ($goal['Athlete']['Anagrafica'] != '') ? $goal['Athlete']['Anagrafica'] : 'In sospeso'; ?></td>
                <td data-goal="<?= $goal['Matchgoal']['Goal']; ?>"><?= $goal['Matchgoal']['Goal']; ?></td>
                <td data-autogoal="<?= $goal['Matchgoal']['Autogoal']; ?>"><?= $goal['Matchgoal']['Autogoal']; ?></td>
                <td data-ammonizione="<?= $goal['Matchgoal']['Ammonizione']; ?>"><?= $goal['Matchgoal']['Ammonizione']; ?></td>
                <td data-espulsione="<?= $goal['Matchgoal']['Espulsione']; ?>"><?= $goal['Matchgoal']['Espulsione']; ?></td>
                <td data-espulsioneGiornate="<?= $goal['Matchgoal']['EspulsioneGiornate']; ?>"><?= $goal['Matchgoal']['EspulsioneGiornate']; ?></td>
                <td data-espulsioneFine="<?= $goal['Matchgoal']['EspulsioneFine_it']; ?>"><?= $goal['Matchgoal']['EspulsioneFine_it']; ?></td>
                <td data-motivo="<?= $goal['Matchgoal']['Motivo']; ?>"><?= $goal['Matchgoal']['Motivo']; ?></td>
                <td data-option="">
                    <a class="GoalDelete" href="javascript:;"><img src="/img/timmyshare/icon_delete.png"/></a>
                    <a class="GoalEdit" data-id="<?= $goal['Matchgoal']['GoalPartita']; ?>" href="javascript:;"><img src="/img/timmyshare/icon_edit.png"/></a>
                </td>

            </tr>

            <? $i++; ?>

        <? endforeach; ?>

        <? if ($group_id != 3 || true): // GIUSEPPE 2022-12-12 ?>
        <?// if ($group_id != 3 ): ?>

            <tr>
                <td>
                    <?= $this->Form->input('GoalPartita', array('type' => 'hidden', 'div' => false)); ?>
                    <?= $this->Form->input('Calendario', array('label' => '', 'div' => false, 'type' => 'hidden', 'value' => $calendario['Match']['Calendario'])); ?>
                    <?= $this->Form->input('SquadraCampionato', array('label' => '', 'div' => false, 'type' => 'select', 'options' => $squadre)); ?>
                </td>
                <td>
                    <?= $this->Form->input('Atleta', array('label' => '', 'class' => 'athleteSelect', 'type' => 'select', 'div' => false)); ?>
                    <div class="error_atleta error-message"></div>
                </td>
                <td>
                    <?= $this->Form->input('Goal', array('label' => '', 'class' => 'goal-input', 'div' => false, 'type' => 'text', 'value' => 0)); ?>
                    <div class="error_goal error-message"></div>
                </td>
                <td>
                    <?= $this->Form->input('Autogoal', array('label' => '', 'class' => 'autogoal-input', 'div' => false, 'type' => 'text', 'value' => 0)); ?>
                    <div class="error_autogoal error-message"></div>
                </td>
                <td>
                    <?=
                    $this->Form->input('Ammonizione', array(
                        'class' => 'Ammonizione',
                        'legend' => false,
                        'type' => 'radio',
                        'options' => array('Si' => 'Si', 'No' => 'No'),
                        'default' => 'No',
                        'hiddenField' => false,
                        'div' => false
                    ));
                    ?>
                </td>
                <td>
                    <?=
                    $this->Form->input('Espulsione', array(
                        'class' => 'Espulsione',
                        'legend' => false,
                        'type' => 'radio',
                        'options' => array('Si' => 'Si', 'No' => 'No'),
                        'default' => 'No',
                        'hiddenField' => false,
                        'div' => false
                    ));
                    ?>
                </td>
                <td>
                    <? $opzioni = array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7'); ?>
                    <div class="EspulsioneGiornate hidden" style="display: none">
                        <?= $this->Form->input('EspulsioneGiornate', array('label' => '', 'div' => false, 'type' => 'select', 'options' => $opzioni, 'empty' => true)); ?>
                    </div>
                </td>
                <td>
                    <div class="EspulsioneFine hidden" style="display: none">
                        <?= $this->Form->input('EspulsioneFine', array('label' => '', 'div' => false, 'type' => 'text', 'class' => 'datePicker')); ?>
                    </div>
                </td>
                <td>
                    <?= $this->Form->input('Motivo', array('label' => '', 'div' => false, 'class' => 'autoComplete', 'data-url' => '/admin/expulsions/searchExpulsion', 'data-dest' => 'MatchFittizio')); ?>
                </td>
                <td>
                    <a class="goal_add" id="GoalAdd" href="javascript:;"><img src="/img/timmyshare/icon_add.png"/></a>
                    <a style="display: none;" class="reset_field" id="ResetGoal" href="javascript:;"><img src="/img/timmyshare/icon_reset_quick_search.png"/></a>
                </td>

            </tr>

        <? endif; ?>

    </table>

    <div class="clear"></div>

    <? if (count($squalificati)): ?>

        <? if (count($squalificati['squalificati'])): ?>

            <h3>Squalificati</h3>   

            <table id="disciplinareTable" class="form_table form_table_full">

                <tr>
                    <th>Atleta (Squadra)</th>
                </tr>

                <? foreach ($squalificati['squalificati'] as $espulso): ?>

                    <tr>

                        <td><div class="error-message"><?= $espulso['Anagrafica']; ?> (<?= $espulso['Squadra']; ?>)</div></td>

                    </tr>

                <? endforeach; ?>

            </table>

        <? endif; ?>

        <? if (count($squalificati['espulsi'])): ?>

            <h3>Espulsi</h3>    

            <table id="disciplinareTable" class="form_table form_table_full">

                <tr>
                    <th>Atleta (Squadra) - Periodo</th>
                </tr>

                <? foreach ($squalificati['espulsi'] as $espulso): ?>

                    <tr>

                        <td><div class="error-message"><?= $espulso['Anagrafica']; ?> (<?= $espulso['Squadra']; ?>) - <?= $espulso['Periodo']; ?></div></td>

                    </tr>

                <? endforeach; ?>

            </table>            

        <? endif; ?>        

    <? endif; ?>    

    <div class="clear"></div>

    <h3>Disciplinari</h3>   

    <table id="disciplinareTable" class="form_table form_table_full">

        <tr>
            <th>Squadra</th>
            <th>Descrizione</th>
            <th>Punti</th>
            <th>Sanzione</th>
            <th>Opzioni</th>
        </tr>

        <? foreach ($disciplinari as $disciplinare => $value): ?>

            <tr class="disciplinari-row-delete" data-disciplinare="<?= $value['Disciplinari']['Disciplinare']; ?>">
                <td data-squadra="<?= $value['Disciplinari']['NomeSquadra']; ?>"><?= $value['Disciplinari']['NomeSquadra']; ?></td>
                <td style="display: none;" data-disciplinareChiave="<?= $value['Disciplinari']['DisciplinareChiave']; ?>"><?= $value['Disciplinari']['DisciplinareChiave']; ?></td>
                <td data-descrizione="<?= $value['Disciplinari']['Descrizione']; ?>"><?= $value['Disciplinari']['Descrizione']; ?></td>
                <td data-punti="<?= $value['Disciplinari']['Punti']; ?>"><?= $value['Disciplinari']['Punti']; ?></td>
                <td data-sanzione="<?= $value['Disciplinari']['Sanzione']; ?>"><?= $value['Disciplinari']['Sanzione']; ?></td>
                <td data-option="">
                    <a class="DisciplineDelete" href="javascript:;"><img src="/img/timmyshare/icon_delete.png"/></a>
                    <a class="DisciplineEdit" data-disciplinare="<?= $value['Disciplinari']['Disciplinare']; ?>" href="javascript:;"><img src="/img/timmyshare/icon_edit.png"/></a>
                </td>
            </tr>

        <? endforeach; ?>

    </table>    

    <?// if ($group_id != 3): ?>                
    <? if ($group_id != 3 || true): // GIUSEPPE 2022-12-12 ?>               

        <?= $this->Form->input('Disciplinari.Disciplinare', array('type' => 'hidden')); ?>

        <?= $this->Form->input('Disciplinari.Calendario', array('type' => 'hidden', 'value' => $this->data['Match']['Calendario'])); ?>

        <?
        $options = array();

        $options[$this->data['Match']['Casa']] = $this->data['Match']['SquadraCasaSearch'];
        $options[$this->data['Match']['Trasferta']] = $this->data['Match']['SquadraTrasfertaSearch'];
        ?>

        <?= $this->Form->input('Disciplinari.SquadraCampionato', array('label' => 'Squadra', 'type' => 'select', 'options' => $options)); ?>

        <?= $this->Form->input('Disciplinari.DisciplinareSearch', array('label' => 'Disciplinare Chiave', 'class' => 'autoComplete', 'data-url' => '/admin/matches/searchDisciplinare', 'data-dest' => 'DisciplinariDisciplinareChiave')); ?>

        <?= $this->Form->input('Disciplinari.DisciplinareChiave', array('type' => 'hidden')); ?>

        <div class="input text required">
            <?= $this->Form->input('Disciplinari.Descrizione', array('div' => false)); ?>
            <div class="error-descrizione error-message"></div>
        </div>

        <div class="input text required">
            <?= $this->Form->input('Disciplinari.Punti', array('div' => false)); ?>
            <div class="error-punti error-message"></div>
        </div>

        <?= $this->Form->input('Disciplinari.Sanzione'); ?>

        <div class="input text" style="margin-top:25px;">           
            <a href="javascript:;" class="disciplinareAdd">
                <img src="/img/timmyshare/icon_add.png" />
            </a>
            <a href="javascript:;" class="reset_disc" style="display: none">
                <img src="/img/timmyshare/icon_filter_delete.png" />
            </a>
        </div>

    <? endif; ?>
</div>
<?= $this->Form->end(); ?>