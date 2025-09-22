<? $is_admin = isset($_SESSION['User']) && ($_SESSION['User']['group_id'] == 1) ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Info prenotazione</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <style>
        body{
            padding: 50px 20px;
            margin: 0 auto;
            max-width: 740px;
            background: #fafafa;
        } 

        ul {
            list-style: none;
            padding: 0;
            margin: 20px 0 40px;
            max-width: 700px;
        }

        ul li{
            border-bottom: 1px solid #eee;
            padding: 10px 0;
            font-size: 15px;
        }
    </style>

    <body>
        <div class="row">
            <div class="col-lg">
                <img src="/img/playleague-logo_x2.png" width="180" style="margin-bottom: 20px;">
                <h3>Conferma prenotazione campo  <strong><?= $info_prenotazione['Campo'] ?></strong></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg">
                <p style="padding-top: 20px;">
                    Gentile <b><?= $info_prenotazione['Booker'] ?></b>,<br>
                    confermiamo la prenotazione del campo <b><?= $info_prenotazione['Campo'] ?></b> per <?= count($info_prenotazione['Giorni']) == 1 ? "la seguente data:" : "le seguenti date:" ?>
                </p>
            </div>
        </div>


        <? if ($is_admin): ?>
            <div class="row">
                <div class="col-lg" style="padding: 10px; border: 1px solid #eee; border-radius: 5px; background: #fff;">
                    <input type="checkbox" class="input-pagato form-check-input" id="check-delete-all">
                    <label class="form-check-label" for="flexCheckDefault">
                        Seleziona tutti per l'eliminazione
                    </label>
                </div>
            </div>
        <? endif; ?>


        <div class="row">

            <div class="col-lg">
                <ul>
                    <? foreach ($info_prenotazione['Giorni'] as $giorno): ?>
                        <li><b><?= $giorno['Data'] ?></b> / ore <?= $giorno['Ora'] ?> / Quota allenamento <?= $giorno['Importo'] ?> €

                            <? if ($is_admin): ?>

                                <br>
                                <input style="max-width: 100px; margin-top: 10px;" type="number" class="input-costo" index="<?= $giorno['id'] ?>" id="<?= $giorno['id'] ?>" value="<?= str_replace(",", ".", $giorno['Importo']) ?>">


                                <input style="margin-top: 18px; margin-left: 30px;" type="checkbox" class="input-pagato form-check-input"  index="<?= $giorno['id'] ?>" id="check_<?= $giorno['id'] ?>"> <label class="form-check-label" for="flexCheckDefault"> Pagato</label>

                                <input style="margin-top: 18px; margin-left: 30px;" type="checkbox" class="input-elimina form-check-input"  index="<?= $giorno['id'] ?>" id="check_<?= $giorno['id'] ?>">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Elimina prenotazione
                                </label>

                            <? endif; ?>

                        </li>
                    <? endforeach; ?>
                </ul>
            </div>
        </div>

        <? if ($is_admin): ?>
            <div class="row">

                <div class="col-lg-6">
                    <button type="button" class="btn btn-primary btn-lg btn-block" id="save">SALVA</button>
                </div>
            </div>
        <? endif; ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" ></script>



        <? if ($is_admin): ?>
            <script>

                var save = document.querySelector("#save");
                var info_prenotazione = <?= json_encode($info_prenotazione['Giorni']) ?>;
                var check_delete_all = document.querySelector("#check-delete-all");
                var del = document.getElementsByClassName("input-elimina");
                var pay = document.getElementsByClassName("input-pagato");
                var values = document.getElementsByClassName("input-costo");

                insertPriceFirefox(info_prenotazione);
                save.addEventListener('click', MySave, false);
                check_delete_all.addEventListener('change', deleteAll, false);

                /* *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** */

                function MySave()
                {
                    var to_send = {};
                    to_send['edit_page_prenotazione'] = true;
                    to_send['prezzo'] = {};
                    to_send['pagato'] = {};
                    to_send['elimina'] = {};

                    Object.keys(values).map((i) => {
                        var prezzo = values[i].value;
                        var id = values[i].id;
                        console.log(values[i]);
                        to_send['prezzo'][id] = prezzo;
                    });

                    Object.keys(pay).map((i) => {
                        var pagato = pay[i].checked;
                        var index = pay[i].getAttribute("index");
                        console.log(pay[i]);
                        console.log(index);
                        to_send['pagato'][index] = pagato == true ? 1 : 0;
                    });

                    Object.keys(del).map((i) => {
                        var elimina = del[i].checked;
                        var index = del[i].getAttribute("index");
                        console.log(del[i]);
                        console.log(index);

                        if (elimina)
                        {
                            to_send['elimina'][index] = elimina;
                            delete to_send['prezzo'][index];
                            delete to_send['pagato'][index];
                        }
                    });


                    console.log(to_send);

                    const xhr = new XMLHttpRequest();
                    //      xhr.open("POST", "https://jsonplaceholder.typicode.com/todos");
                    xhr.open("POST", "/apis/editImportCampiBooking");
                    xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
                    const body = JSON.stringify(to_send);
                    xhr.onload = () => {

                        if (xhr.readyState == 4 && xhr.status == 200)
                        {
                            console.log(xhr.responseText);

                        }
                        else
                        {
                            console.log(`Error: ${xhr.status}`);
                        }

                        location.reload();
                    };
                    xhr.send(body);
                }


                function insertPriceFirefox(info_prenotazione)
                {
                    Object.keys(info_prenotazione).map((i) => {
                        console.log(info_prenotazione[i]);
                        var id = info_prenotazione[i].id;
                        var Importo = info_prenotazione[i].Importo;
                        var Pagato = info_prenotazione[i].Pagato;
                        document.getElementById(id).value = Importo.replace(",", ".");
                        document.getElementById("check_" + id).checked = parseInt(Pagato);
                    });
                }


                function deleteAll()
                {
                    var eliminaAllCheck = this.checked;

                    Object.keys(del).map((i) => {
                        del[i].checked = eliminaAllCheck;
                    });
                }
            </script>
        <? endif; ?>
    </body>
</html>