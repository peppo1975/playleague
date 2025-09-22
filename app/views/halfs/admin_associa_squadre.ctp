<script type="text/javascript">
    if (typeof $ != "undefined")
    {
        $(function ()
        {
            console.log('qui -half');
            var tot_squadre = '';
            var nr_squadre = '';
            var edit = 'aggiunto';
            var exit = 0;

            $('#table_form_squadre').delegate('.SquadraAdd', 'click', function ()
            {


                //GIUSEPPE 2023-10-10  -----------------------------------------------------------------------------------
                var Squadra_id = $("#Squadra").val();
                var Campionato_id = $("#Campionato").attr('data-id');

                bas(Squadra_id, Campionato_id);
                // --------------------------------------------------------------------------------------------------------

            });







            //GIUSEPPE 2023-10-10  --------------------------------------------------------------------------------------------------------
            function SquadraAdd()
            {
                if (exit != 0)
                    return;

                tot_squadre = $("#table_form_squadre").attr('data-tot');
                nr_squadre = $("#table_form_squadre tr[data-squadre]").size();

                var Squadra_id = $("#Squadra").val();
                var Squadra = $("#SquadraSearch").val();
                var Campionato_id = $("#Campionato").attr('data-id');
                var Campionato = $("#Campionato").val();
                var Girone_id = $("#Girone").attr('data-id');
                var Girone = $("#Girone").val();
                var Campo_id = $("#Campo").val();
                var Campo = $("#CampoSearch").val();
                var Ora = $("#Ora").val();
                var Giorno = $("#Giorno").val();
                var Pagato = $("#Pagato").val();

                var CampionatoPrecedente = $("#CampionatoPrecedente").val();

                $('.error_campo').html('');
                $('.error_squadra').html('');
                $('.error_giorno').html('');
                $('.error_ora').html('');

                if (Squadra == '')
                {

                    $('.error_squadra').html('Squadra obbligatoria.');
                    return false;

                }

                if (Campo == '')
                {

                    $('.error_campo').html('Campo obbligatorio.');
                    return false;

                }

                if (Ora == '')
                {

                    $('.error_ora').html('Ora obbligatoria.');
                    return false;

                }

                if (Giorno == '')
                {

                    $('.error_giorno').html('Giorno obbligatorio');
                    return false;

                }

                if (nr_squadre >= tot_squadre && edit == 'aggiunto')
                {
                    alert('Girone pieno.');
                    return false;
                }

                var new_SquadraCampionato = {SquadreCampionati: {"Squadra": Squadra_id, "Campionato": Campionato_id, "GironeCampionato": Girone_id, "Campo": Campo_id, "Ora": Ora, "Giorno": Giorno, "Pagato": Pagato}}

                exit = 1;

                $.post('/admin/halfs/associaSquadreAdd/' + edit + '/' + CampionatoPrecedente, new_SquadraCampionato, function (ret)
                {

                    if (ret.error == 'not_error')
                    {

                        if (ret.aggiunto == 'aggiunto')
                        {

                            nr_squadre = nr_squadre + 1;

                            $(
                                    '<tr data-squadre="' + ret.last_id + '">' +
                                    '<td data-squadra-id="' + ret.last_id + '">' + Squadra + '</td>' +
                                    '<td style="display: none;" data-squadra_id="' + ret.last_id + '">' + Squadra_id + '</td>' +
                                    '<td data-campionato-id="' + ret.last_id + '">' + Campionato + '</td>' +
                                    '<td style="display: none;" data-campionato_id="' + ret.last_id + '">' + Campionato_id + '</td>' +
                                    '<td data-girone-id="' + ret.last_id + '">' + Girone + '</td>' +
                                    '<td style="display: none;" data-girone_id="' + ret.last_id + '">' + Girone_id + '</td>' +
                                    '<td data-campo-id="' + ret.last_id + '">' + Campo + '</td>' +
                                    '<td style="display: none;" data-campo_id="' + ret.last_id + '">' + Campo_id + '</td>' +
                                    '<td data-ora="' + ret.last_id + '">' + Ora + '</td>' +
                                    '<td data-giorno="' + ret.last_id + '">' + Giorno + '</td>' +
                                    '<td data-pagato="' + ret.last_id + '">' + Pagato + '</td>' +
                                    '<td>' +
                                    '<a href="javascript:;" data-edit="' + ret.last_id + '" class="SquadraEdit"><img src="/img/timmyshare/icon_edit.png" /></a>' +
                                    '<a href="javascript:;" data-delete="' + ret.last_id + '" class="SquadraDelete"><img src="/img/timmyshare/icon_delete.png" /></a>' +
                                    '<a href="javascript:;" data-delete="' + ret.last_id + '" class="SquadraDeleteYearbooks" title="Rimuovi tutti i tesserati"><img src="/img/icon_delete_match.png" /></a>' +
                                    '</td>' +
                                    '</tr>'

                                    ).insertAfter('.squadre_append');

                            $("#Squadra").val('');
                            $("#SquadraSearch").val('');
                            $("#Campo").val('');
                            $("#CampoSearch").val('');
                            $("#Ora").val('');
                            $("#Giorno").val('');
                            $("#Pagato").val('No');

                            $("#PagatoCheck").attr('checked', false);

                            $('.error-message').each(function ()
                            {

                                $(this).hide();

                            });

                            edit_campo = 'aggiunto';

                            alert('Squadra aggiunta.');

                        }
                        else
                        {

                            $('.data-squadre').find('td[data-squadra_id = ' + ret.aggiunto + ']').html(Squadra_id);
                            $('.data-squadre').find('td[data-squadra-id = ' + ret.aggiunto + ']').html(Squadra);
                            $('.data-squadre').find('td[data-campionato_id = ' + ret.aggiunto + ']').html(Campionato_id);
                            $('.data-squadre').find('td[data-campionato-id = ' + ret.aggiunto + ']').html(Campionato);
                            $('.data-squadre').find('td[data-girone_id = ' + ret.aggiunto + ']').html(Girone_id);
                            $('.data-squadre').find('td[data-girone-id = ' + ret.aggiunto + ']').html(Girone);
                            $('.data-squadre').find('td[data-campo_id = ' + ret.aggiunto + ']').html(Campo_id);
                            $('.data-squadre').find('td[data-campo-id = ' + ret.aggiunto + ']').html(Campo);
                            $('.data-squadre').find('td[data-ora = ' + ret.aggiunto + ']').html(Ora);
                            $('.data-squadre').find('td[data-giorno = ' + ret.aggiunto + ']').html(Giorno);
                            $('.data-squadre').find('td[data-pagato = ' + ret.aggiunto + ']').html(Pagato);

                            $('.reset_edit_ass').hide();
                            $('.SquadraAdd').html('<img src="/img/timmyshare/icon_add.png" />');

                            $("#Squadra").val('');
                            $("#SquadraSearch").val('');
                            $("#Campo").val('');
                            $("#CampoSearch").val('');
                            $("#Ora").val('');
                            $("#Giorno").val('');
                            $("#PagatoCheck").attr('checked', false);
                            $("#Pagato").val('No');

                            $('.error-message').each(function ()
                            {

                                $(this).hide();

                            });

                            $('.annuario_atleti').hide();
                            $('.nuovo_annuario').hide();

                            edit_campo = 'aggiunto';

                            alert('Squadra modificata.');

                        }

                    }
                    else
                    {

                        if (ret.error == 'campo')
                        {

                            $('.error_campo').html('Campo occupato (Ora: ' + Ora + ' Giorno: ' + Giorno + ')');

                        }
                        if (ret.error == 'squadra')
                        {
                            $('.error_squadra').html('Squadra gi&agrave presente nel girone o nel campionato.');
                        }

                    }

                    exit = 0;

                }, 'json');
            }

            // --------------------------------------------------------------------------------------------------------

            $("#table_form_squadre").delegate("#CampoSearch", "focusout", function ()
            {

                var obj = $(this);
                var campo_id = $("#Campo").val();

                if (campo_id == '')
                {

                    obj.val('');
                    timmyloader('hide');

                }

            });

            $('#table_form_squadre').delegate('.SquadraDelete', 'click', function ()
            {

                var delete_id = $(this).attr('data-delete');

                if (confirm('Eliminare record?'))
                {

                    $.get('/admin/halfs/associaSquadreDelete/' + delete_id, function (ret)
                    {

                        if (ret.delete == 1)
                        {

                            $("tr[data-squadre='" + delete_id + "']").remove();
                            nr_squadre = nr_squadre - 1;
                            alert('Record eliminato con successo.');

                        }
                        else
                        {

                            alert('Impossibile cancellare, eliminare prima i tesserati.');

                        }

                    }, 'json');

                }

            });

            /* NEW */

            $('#table_form_squadre').delegate('.SquadraDeleteYearbooks', 'click', function ()
            {

                var delete_id = $(this).attr('data-delete');

                if (confirm('Eliminare record?'))
                {

                    $.get('/admin/halfs/deleteAllYearbooks/' + delete_id, function (ret)
                    {

                        if (ret.remove == 1)
                        {

                            alert('Tesserati eliminati correttamente.');

                        }
                        else
                        {

                            alert('Impossibile cancellare i tesserati.');

                        }

                    }, 'json');

                }

            });

            /**/

            $('#table_form_squadre').delegate('.SquadraEdit', 'click', function ()
            {

                var edit_id = $(this).attr('data-edit');

                edit = edit_id;

                var Squadra_id = $(this).closest('tr').find('td[data-squadra_id = ' + edit_id + ']').html();
                var Squadra = $(this).closest('tr').find('td[data-squadra-id = ' + edit_id + ']').html();
                var Campionato_id = $(this).closest('tr').find('td[data-campionato_id = ' + edit_id + ']').html();
                var Campionato = $(this).closest('tr').find('td[data-campionato-id = ' + edit_id + ']').html();
                var Girone_id = $(this).closest('tr').find('td[data-girone_id = ' + edit_id + ']').html();
                var Girone = $(this).closest('tr').find('td[data-girone-id = ' + edit_id + ']').html();
                var Campo_id = $(this).closest('tr').find('td[data-campo_id = ' + edit_id + ']').html();
                var Campo = $(this).closest('tr').find('td[data-campo-id = ' + edit_id + ']').html();
                var Ora = $(this).closest('tr').find('td[data-ora = ' + edit_id + ']').html();
                var Giorno = $(this).closest('tr').find('td[data-giorno = ' + edit_id + ']').html();
                var Pagato = $(this).closest('tr').find('td[data-pagato = ' + edit_id + ']').text();

                $("#Squadra").val(Squadra_id);
                $("#SquadraSearch").val(Squadra);
                $("#Campionato").attr('data-id', Campionato_id);
                $("#Campionato").val(Campionato);
                $("#Girone").attr('data-id', Girone_id);
                $("#Girone").val(Girone);
                $("#Campo").val(Campo_id);
                $("#CampoSearch").val(Campo);
                $("#Ora").val(Ora);
                $("#Giorno").val(Giorno);

                $("#Pagato").val(Pagato);

                if ($("#Pagato").val() == 'Si')
                {

                    $("#PagatoCheck").attr('checked', true);

                }
                else
                    $("#PagatoCheck").attr('checked', false);

                $(".nuovo_annuario").show();

                $('.SquadraAdd').html('<img src="/img/timmyshare/icon_edit.png" />');
                $('.reset_edit_ass').show();

                $.get('/admin/halfs/associaSquadreAnnuario/' + Squadra_id + '/' + Girone_id + '/' + Campionato_id, function (ret)
                {

                    var k = 0;

                    $('.annuario_atleti').html(
                            '<table class="form_table form_table_full" id="table_annuario">' +
                            '<tr class="table_header" data-squadracampionato="' + edit_id + '">' +
                            '<th>Anno sportivo</th>' +
                            '<th>Tessera</th>' +
                            '<th>Data vidimazione</th>' +
                            '<th>Atleta</th>' +
                            '<th>Tipo assicurazione</th>' +
                            '<th>Note</th>' +
                            '<th>Opzioni</th>' +
                            '</tr>' +
                            '</table>'

                            );

                    while (k < ret.length)
                    {

                        if (ret[k].Yearbook.Note == null)
                            ret[k].Yearbook.Note = '';
                        if (ret[k].Yearbook.NomeAssicurazione == null)
                            ret[k].Yearbook.NomeAssicurazione = '';
                        if (k == 0)
                            var first_annuario = ret[k].Yearbook.Annuario;

                        $(
                                '<tr annuario-id="' + ret[k].Yearbook.Annuario + '">' +
                                '<td>' + ret[k].Yearbook.AnnoSportivo + '</td>' +
                                //GIUSEPPE 2023-07-28 -----------------------------------------------                              
//                                '<td>' + ret[k].Yearbook.Tessera + '</td>' +
                                '<td>' + ret[k].Yearbook.card_id + '</td>' +
                                // ----------------------------------------------
                                '<td>' + ret[k].Yearbook.DataVidimazione_it + '</td>' +
                                '<td>' + ret[k].Yearbook.NomeAtleta + '</td>' +
                                '<td>' + ret[k].Yearbook.NomeAssicurazione + '</td>' +
                                '<td>' + ret[k].Yearbook.Note + '</td>' +
                                '<td><a href="javascript:;" data-delete="' + ret[k].Yearbook.Annuario + '" class="AnnuarioDelete"><img src="/img/timmyshare/icon_delete.png" /></a></td>' +
                                '</tr>'

                                ).insertAfter('.table_header');

                        k++;

                    }

                    //$('<tr><td>Ciao</td></tr>').insertAfter('tr[annuario-id=' + first_annuario + ']');

                }, 'json');

            });

            $('#nuovo_annuario').delegate('.AnnuarioAdd', 'click', function ()
            {

                if (exit != 0)
                    return;

                var AnnoSportivo = $("#AnnoSportivo").val();
                var Tessera = $("#Tessera").val();
                var DataVidimazione = $("#DataVidimazione").val();
                var Atleta_id = $("#Atleta").val();
                var Atleta = $("#AtletaSearch").val();
                var TipoAssicurazione = $("#TipoAssicurazione").val();
                var TipoAssicurazione_name = $("#TipoAssicurazione :selected").html();
                var Note = $("#Note").val();
                var SquadraCampionato = $('.table_header').attr('data-squadracampionato');

                var Annuario = {Yearbook: {"AnnoSportivo": AnnoSportivo, "Tessera": Tessera, "DataVidimazione": DataVidimazione, "SquadraCampionato": SquadraCampionato, "Atleta": Atleta_id, "TipoAssicurazione": TipoAssicurazione, "Note": Note}}

                exit = 1;

                $.post('/admin/halfs/associaSquadreAnnuarioAdd', Annuario, function (retrn)
                {

                    if (retrn.add != '')
                    {

                        $(
                                '<tr annuario-id="' + retrn.add + '">' +
                                '<td>' + AnnoSportivo + '</td>' +
                                '<td>' + Tessera + '</td>' +
                                '<td>' + DataVidimazione + '</td>' +
                                '<td>' + Atleta + '</td>' +
                                '<td>' + TipoAssicurazione_name + '</td>' +
                                '<td>' + Note + '</td>' +
                                '<td><a href="javascript:;" data-delete="' + retrn.add + '" class="AnnuarioDelete"><img src="/img/timmyshare/icon_delete.png" /></a></td>' +
                                '</tr>'

                                ).insertAfter('.table_header');

                        $.get("/admin/yearbooks/tesseraGen/" + $("#AnnoSportivo").val(), function (ret)
                        {

                            $("#Tessera").val(ret.tessera);

                        }, 'json');

                        alert('Annuario aggiornato con successo.');

                    }

                    exit = 0;

                }, 'json');

            });

            $('.annuario_atleti').delegate('.AnnuarioDelete', 'click', function ()
            {

                var delete_id = $(this).attr('data-delete');

                if (confirm('Eliminare record?'))
                {

                    $.get('/admin/halfs/associaSquadreAnnuarioDelete/' + delete_id, function (ret)
                    {

                        if (ret == null)
                            ret = 2;

                        if (ret.delete == 1 || ret == 2)
                        {

                            $("tr[annuario-id='" + delete_id + "']").remove();
                            alert('Record eliminato con successo.');

                        }
                        else
                        {

                            alert('Manifestazione in corso, impossibile cancellare.');

                        }

                    }, 'json');

                }

            });

            $('.reset_edit_ass').live('click', function ()
            {

                $("#Squadra").val('');
                $("#SquadraSearch").val('');
                $("#Campo").val('');
                $("#CampoSearch").val('');
                $("#Ora").val('');
                $("#Giorno").val('Lunedì');
                $("#Pagato").val('No');

                $("#PagatoCheck").attr('checked', false);

                $('.annuario_atleti').html('');
                $(".nuovo_annuario").hide();

                $('.error-message').each(function ()
                {

                    $(this).hide();

                });

                $('.SquadraAdd').html('<img src="/img/timmyshare/icon_add.png" />');
                $('.reset_edit_ass').hide();

                edit_campo = 'aggiunto';
                edit = 'aggiunto';

            });

            //SCRIPT GENERAZIONE TESSERA //

            $.get("/admin/yearbooks/tesseraGen/" + $("#AnnoSportivo").val(), function (ret)
            {

                $("#Tessera").val(ret.tessera);


            }, 'json');

            $("#AnnoSportivo").change(function ()
            {


                $.get("/admin/yearbooks/tesseraGen/" + $("#AnnoSportivo").val(), function (ret)
                {

                    $("#Tessera").val(ret.tessera);


                }, 'json');


            });

            /* Input pagato */

            $("#PagatoCheck").live('change', function ()
            {

                if ($(this).is(':checked'))
                {

                    $("#Pagato").val('Si');

                }
                else
                {

                    $("#Pagato").val('No');

                }

            });






            //GIUSEPPE 2023-07-28 -----------------------------------------------
            var bas = async (squadra, campionato) => {

                SquadraAdd(); //2024-09-10 modifica fatta per evitare di generare bas involontarie

                // var res = await httpPost('/admin/halfs/associaBas/', {squadra, campionato});

                // var continueInsert = await responseBAS(res);

                // if (continueInsert)
                // {
                    // SquadraAdd();
                // }


            };


            function responseBAS(res) // non serve piu
            {
                return new Promise((resolve, reject) => {
                    responseBASMessage = document.getElementById("responseBASMessage");
                    responseBASMessage.innerHTML = "";

                    var info = res.info;
                    var timer = 0;
                    var continueForIsert = true;

                    try
                    {
                        if (res['response'] == "EXIST_BAS")
                        {
                            timer = 5000;

                            if (info.info == "Non playleague")
                            {

                            }
                            if (info.info == "Bas presente")
                            {
                                responseBASMessage.innerHTML = "BAS GENERATA IN PRECEDENZA";
                                responseBASMessage.style.backgroundColor = "green";
                                responseBASMessage.style.color = "white";
                            }

                        }
                        else if (res['response'] == "NEW_BAS")
                        {
                            //                    alert('BAS creato correttamente');

                            var ul = document.createElement("ul");

                            var documents = {
                                'AFFILIATION_REQUEST': "STATUTO",
                                'MEMORANDUM_ARTICLES_ASSOCIATION': "RICHIESTA AFFILIAZIONE",
                                'PRESIDENT_ID': "DOCUMENTO D'IDENTITA' RESPONSABILE"
                            };

                            Object.keys(documents).forEach((i) => {
                                const li = document.createElement("li");
                                const message = document.createTextNode(documents[i]);
                                li.appendChild(message);
                                ul.appendChild(li);

                                if (parseInt(res.info[i]) == 1)
                                {
                                    li.style.backgroundColor = "green";
                                    li.style.color = "white";
                                }
                                else
                                {
                                    li.style.backgroundColor = "orange";
                                    li.style.color = "white";
                                }
                            });

                            timer = 5000;
                            responseBASMessage.appendChild(ul);
//                            responseBASMessage.style.color = "white";

                        }
                        else if (res['response'] == "FILES_BAS")
                        {
                            var ul = document.createElement("ul");

                            Object.keys(res.info.errors).forEach((i) => {



                                const li = document.createElement("li");
                                const message = document.createTextNode(res.info.errors[i]);
                                li.appendChild(message);
                                ul.appendChild(li);

                            });

                            responseBASMessage.appendChild(ul);
                            responseBASMessage.style.backgroundColor = "orange";
                            responseBASMessage.style.color = "white";

                            timer = 20000;
                            continueForIsert = true;
                        }
                        else if (res['response'] == "ERROR_BAS")
                        {

                            var ul = document.createElement("ul");
                            Object.keys(res.info.errors).forEach((i) => {

                                Object.keys(res.info.errors[i]).forEach((j) => {
                                    const li = document.createElement("li");
                                    const message = document.createTextNode(res.info.errors[i][j]);
                                    li.appendChild(message);
                                    ul.appendChild(li);
                                });
                            });

                            responseBASMessage.appendChild(ul);
                            responseBASMessage.style.backgroundColor = "orange";
                            responseBASMessage.style.color = "white";

                            timer = 20000;
                            continueForIsert = false;
                        }
                        else
                        {
                            alert('Problemi con il bas');
                            continueForIsert = false;
                        }
                    }
                    catch (exception)
                    {
                        alert("PROBLEMI CON IL DB e la creazione del BAS");
                        continueForIsert = false;
                    }

                    setTimeout(() => {
                        responseBASMessage.innerHTML = "";
                    }, timer);

                    resolve(continueForIsert);

                });
            }

            function httpPost(link, to_send)
            {

                return new Promise((resolve, reject) => {

                    const xhr = new XMLHttpRequest();

                    xhr.open("POST", link);

                    xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");

                    const body = JSON.stringify(to_send);

                    xhr.send(body);

                    xhr.onload = () => {

                        if (xhr.readyState == 4 && xhr.status == 200)
                        {
                            var arr = JSON.parse(xhr.response);
                            resolve(arr);
                        }
                        else
                        {
                            reject(new Error(xhr.statusText));
                        }
                    };
                });
            }
            //-------------------------------------------------------------------

        });
    }




</script>



<?= $this->element("/backend/add_edit_scripts"); ?>

<table data-tot="<?= $nr_squadre; ?>" class="form_table form_table_full" id="table_form_squadre">

    <tr class="squadre_append">
        <th>Squadra</th>
        <th>Campionato</th>
        <th>Girone</th>
        <th>Campo</th>
        <th>Ora</th>
        <th>Giorno</th>
        <th>Pagamento anticipato</th>
        <th>Opzioni</th>
    </tr>

    <? foreach ($teams as $team): ?>

        <tr class="data-squadre" data-squadre="<?= $team['SquadreCampionati']['SquadraCampionato']; ?>">
            <td data-squadra-id="<?= $team['SquadreCampionati']['SquadraCampionato']; ?>"><?= $team['Squadre']['Denominazione']; ?></td>
            <td style="display: none;" data-squadra_id="<?= $team['SquadreCampionati']['SquadraCampionato']; ?>"><?= $team['Squadre']['Squadra']; ?></td>
            <td data-campionato-id="<?= $team['SquadreCampionati']['SquadraCampionato']; ?>"><?= $team['Campionati']['Nome']; ?></td>
            <td style="display: none;" data-campionato_id="<?= $team['SquadreCampionati']['SquadraCampionato']; ?>"><?= $team['Campionati']['Campionato']; ?></td>
            <td data-girone-id="<?= $team['SquadreCampionati']['SquadraCampionato']; ?>"><?= $team['Half']['Descrizione']; ?></td>
            <td style="display: none;" data-girone_id="<?= $team['SquadreCampionati']['SquadraCampionato']; ?>"><?= $team['Half']['GironeCampionato']; ?></td>
            <td data-campo-id="<?= $team['SquadreCampionati']['SquadraCampionato']; ?>"><?= $team['Campi']['Descrizione']; ?></td>
            <td style="display: none;" data-campo_id="<?= $team['SquadreCampionati']['SquadraCampionato']; ?>"><?= $team['Campi']['Campo']; ?></td>
            <td data-ora="<?= $team['SquadreCampionati']['SquadraCampionato']; ?>"><?= $team['SquadreCampionati']['Ora']; ?></td>
            <td data-giorno="<?= $team['SquadreCampionati']['SquadraCampionato']; ?>"><?= $team['SquadreCampionati']['Giorno']; ?></td>
            <td data-pagato="<?= $team['SquadreCampionati']['SquadraCampionato']; ?>"><?= $team['SquadreCampionati']['Pagato']; ?></td>
            <td>
                <a href="javascript:;" data-edit="<?= $team['SquadreCampionati']['SquadraCampionato']; ?>" class="SquadraEdit"><img src="/img/timmyshare/icon_edit.png" /></a>
                <a href="javascript:;" data-delete="<?= $team['SquadreCampionati']['SquadraCampionato']; ?>" class="SquadraDelete"><img src="/img/timmyshare/icon_delete.png" /></a>
                <a href="javascript:;" data-delete="<?= $team['SquadreCampionati']['SquadraCampionato']; ?>" class="SquadraDeleteYearbooks" title="Rimuovi tutti i tesserati"><img src="/img/icon_delete_match.png" /></a>
            </td>
        </tr>

    <? endforeach; ?>

    <? if (!isset($campionato_precedente)) $campionato_precedente = $campionato['Campionati']['CampionatoPrecedente']; ?>

    <tr>
        <td>
            <? //=$this->Form->input('SquadraSearch',array('label' => '', 'div' => false, 'class' => 'autoComplete','data-url' => '/admin/matches/searchSquadra','data-dest' => 'Squadra')); ?>
            <?= $this->Form->input('SquadraSearch', array('label' => '', 'div' => false, 'class' => 'autoComplete', 'data-url' => '/admin/matches/searchSquadra/' . $campionato['Campionati']['id_sport'], 'data-dest' => 'Squadra')); //GIUSEPPE 10/10/2016 nel filtro di ricerca invia l'id_sport ?>
            <?= $this->Form->input('Squadra', array('type' => 'hidden')); ?>
            <div class="error_squadra error-message"></div>
        </td>
        <td>
            <?= $this->Form->input('Campionato', array('label' => '', 'div' => false, 'type' => 'text', 'class' => 'big', 'disabled' => 'disabled', 'value' => $campionato['Campionati']['Nome'], 'data-id' => $campionato['Campionati']['Campionato'])); ?>
            <?= $this->Form->input('CampionatoPrecedente', array('div' => false, 'type' => 'hidden', 'value' => $campionato_precedente)); ?>
        </td>

        <td><?= $this->Form->input('Girone', array('label' => '', 'div' => false, 'type' => 'text', 'disabled' => 'disabled', 'value' => $girone['Half']['Descrizione'], 'data-id' => $girone['Half']['GironeCampionato'])); ?></td>
        <td>
            <?= $this->Form->input('CampoSearch', array('label' => '', 'div' => false, 'class' => 'autoComplete', 'data-url' => '/admin/halfs/searchCampo', 'data-dest' => 'Campo')); ?>
            <?= $this->Form->input('Campo', array('type' => 'hidden')); ?>
            <div class="error_campo error-message"></div>
        </td>
        <td><?= $this->Form->input('Ora', array('label' => '', 'class' => 'control_ora', 'div' => false)); ?></td>
    <div class="error_ora error-message"></div>
    <td>				
        <?
        $options = array();
        $options['Lunedì'] = 'Lunedì';
        $options['Martedì'] = 'Martedì';
        $options['Mercoledì'] = 'Mercoledì';
        $options['Giovedì'] = 'Giovedì';
        $options['Venerdì'] = 'Venerdì';
        $options['Sabato'] = 'Sabato';
        $options['Domenica'] = 'Domenica';
        ?>
        <?= $this->Form->input('Giorno', array('label' => '', 'type' => 'select', 'options' => $options, 'div' => false, 'empty' => true)); ?>
        <div class="error_giorno error-message"></div>
    </td>
    <td>
        <?= $this->Form->input('PagatoCheck', array('label' => '', 'type' => 'checkbox', 'div' => false)); ?>
        <?= $this->Form->input('Pagato', array('type' => 'hidden', 'value' => 'No')); ?>

    </td>
    <td>
        <a href="javascript:;" class="SquadraAdd"><img src="/img/timmyshare/icon_add.png" /></a>
        <a style="display: none;" href="javascript:;" class="reset_edit_ass"><img src="/img/timmyshare/icon_reset_quick_search.png" /></a>
    </td>
</tr>

</table>
<!-- GIUSEPPE 2023-10-10 --> 
<div id="responseBASMessage">

</div>
<!-- ------------------- --> 

<div class="annuario_atleti"></div>

<div class="nuovo_annuario" style="display: none;">

    <table class="form_table form_table_full" id="nuovo_annuario">

        <tr>

            <?
            $options = array();
            foreach ($AnniSportivi as $AnnoSportivo)
            {
                $options[$AnnoSportivo['AnniSportivi']['AnnoSportivo']] = $AnnoSportivo['AnniSportivi']['AnnoSportivo'];
            }
            ?>

            <td>
                <?= $this->Form->input('AnnoSportivo', array('type' => 'select', 'label' => '', 'div' => false, 'default' => '1', 'options' => $options)); ?>
            </td>

            <td>
                <?= $this->Form->input('Tessera', array('readonly' => 'readonly', 'label' => '')); ?>
            </td>

            <td>
                <?= $this->Form->input('DataVidimazione', array('label' => '', 'div' => false, 'type' => 'text', 'class' => 'datePicker')); ?>	
            </td>

            <td>
                <?= $this->Form->input('AtletaSearch', array('label' => '', 'div' => false, 'class' => 'autoComplete', 'data-url' => '/admin/yearbooks/searchAtleta', 'data-dest' => 'Atleta')); ?>
                <? //=$this->Form->input('AtletaSearch',array('label' => 'Atleta', 'class' => 'searchAthlete', 'data-url' => '/admin/athletes/searchAthlete','data-dest' => 'YearbookAtleta'));   ?>
                <?= $this->Form->input('Atleta', array('type' => 'hidden', 'div' => false,)); ?>
            </td>

            <td>			
                <?
                $options1 = array();
                $options1[0] = '';
                foreach ($TipiAssicurazione as $TipoAssicurazione)
                {
                    $options1[$TipoAssicurazione['TipiAssicurazione']['TipoAssicurazione']] = $TipoAssicurazione['TipiAssicurazione']['Descrizione'];
                }
                ?>

                <?= $this->Form->input('TipoAssicurazione', array('type' => 'select', 'label' => '', 'div' => false, 'default' => '0', 'options' => $options1)); ?>
            </td>

            <td>
                <?= $this->Form->input('Note', array('label' => '', 'div' => false)); ?>
            </td>

            <td>
                <a href="javascript:;" class="AnnuarioAdd"><img src="/img/timmyshare/icon_add.png" /></a>
            </td>

        </tr>

    </table>

</div>