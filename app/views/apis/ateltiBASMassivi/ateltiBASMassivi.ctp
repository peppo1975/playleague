<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Other/html.html to edit this template
-->
<html>

<head>
    <title>Atleti BAS</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <style type="text/css">
        body {
            margin: 0;
            padding: 40px;
            font-family: sans-serif;
        }

        h2 {
            border-bottom: 2px solid #000;
            color: #000;
        }

        h4 {
            font-size: 19px;
            text-transform: capitalize;
            color: #000;
            border-bottom: 1px dotted;
            padding: 10px;
            margin: 0 10px;
        }


        ul {
            list-style: none;
            margin: 0 0 30px;
            padding: 0 10px;
        }

        .row-div {
            padding: 10px 20px;
            margin-bottom: 1px;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
        }

        li:first-child div {
            border-radius: 10px 10px 0 0;
        }

        li:last-child div {
            border-radius: 0 0 10px 10px;
            border-bottom: none;
        }

        button {
            padding: 10px 30px;
            margin: 10px;
            clear: both;
            border: 1px solid #16cb5b;
            border-radius: 5px;
            cursor: pointer;
            background: #16cb5b;
            color: #fff;
        }

        button:hover {
            background: #111;
            color: #fff;
            border: 1px solid #111;
        }
    </style>


</head>

<body>
    <!--<div>TODO write content</div>-->
    <h2>Stagione/Anno sportivo: <?= $api->annoSportivo()['current']['year'] ?></h2>
    <? // print_r($res) 
    ?>
    <? $for_squadra = []; ?>
    <? foreach ($res as $squadra => $atleta_squadra): ?>

        <h4><?= $atleta_squadra['nome'] ?> / <i>client_id: <?= $atleta_squadra['client_id'] ?></i></h4>

        <? if ($atleta_squadra['client_id'] > 0): ?>
            <button class="button-tessera"
                id="button-<?= $squadra ?>"
                value="<?= trim($squadra) ?>"
                client-id="<?= $atleta_squadra['client_id'] ?>">TESSERA ATLETI <b><?= $atleta_squadra['nome'] ?></b>
            </button>
        <? else: ?>
            <h5 style="background-color: #ffa50044">La BAS della squadra non è stata creata</h5>
        <? endif; ?>

        <ul>
            <? foreach ($atleta_squadra['atleti'] as $atleta): ?>
                <?
                $message = "";
                $card_id_div = "-1";
                $subscriber_id_div = "-1";
                $style = '';

                if (isset($atleta['BAS'])):
                    $message = "Numero tessera: " . $atleta['BAS']['card_id'] . " / Subscriber Id: " . $atleta['BAS']['subscriber_id'];
                    $card_id_div = $atleta['BAS']['card_id'];
                    $subscriber_id_div = $atleta['BAS']['subscriber_id'];
                    $style = 'background-color: #adff2f44';

                    if ($atleta['BAS']['card_id'] == 0) {
                        $style = 'background-color: #ffa50044;';
                    }

                endif;
                if ($atleta['CityNascita'] == 0):

                    $style = 'background-color: #ff380b44; color: #000';
                    $message = " - Manca il comune di nascita";

                endif;

                if ($atleta['BAS']['card_id'] == "0" && $atleta['BAS']['subscriber_id'] !== "0") {
                    $style = 'background-color: #fcff6275;';
                }

                $atletaInfo = "<strong>{$atleta['Cognome']} {$atleta['Nome']}</strong> / <small>Id Playleague: {$atleta['Atleta']}</small> / <small>Data di nascita: {$atleta['DataNascita']}</small> / <small>Num. associazioni alla BAS : {$atleta['contaAnniTesseramenti']}</small>";
                ?>
                <li>
                    <div
                        style="<?= $style ?>"
                        class="squadra-<?= $atleta['Squadra'] ?> row-div"
                        id="<?= $atleta['Squadra'] ?>-<?= $atleta['Atleta'] ?>"
                        atleta="<?= $atleta['Atleta'] ?>"
                        squadra="<?= $atleta['Squadra'] ?>"
                        cognome="<?= $atleta['Cognome'] ?>"
                        nome="<?= $atleta['Nome'] ?>"
                        sesso="<?= strtolower($atleta['Sesso'][0]) ?>"
                        city="<?= ($atleta['CityNascita']) ?>"
                        data-nascita="<?= ($atleta['DataNascita']) ?>"
                        card-id="<?= $card_id_div ?>" s
                        subscriber-id="<?= $subscriber_id_div ?>">
                        <?= $atletaInfo ?>

                        <div class="message" id="message-<?= $atleta['Squadra'] ?>-<?= $atleta['Atleta'] ?>">
                            <small><?= $message ?></small>
                        </div>
                    </div>
                </li>
            <? endforeach; ?>
        </ul>
    <? endforeach; ?>
</body>

</html>
<script>
    var index_invio = 0;
    var array_id_atleti = [];
    var atleti = {};


    document.addEventListener("DOMContentLoaded", () => {


        setTimeout(() => {
            button_tessera = document.getElementsByClassName("button-tessera");

            Object.keys(button_tessera).forEach((bt) => {

                button_tessera[bt].addEventListener('click', (e) => {
                    //            alert(bt)
                    //console.log(e);

                    var squadra = e.srcElement.value;
                    var client_id = button_tessera[bt].getAttribute('client-id');;
                    scorriClasseAtleta(squadra, client_id);
                });
            });
        }, 1000);


    });





    function scorriClasseAtleta(squadra, client_id) {
        var atleti_list = document.getElementsByClassName(`squadra-${squadra}`);

        atleti = {};
        index_invio = 0
        array_id_atleti = [];
        var link = "https://<?= $_SERVER['HTTP_HOST'] ?>/apis/tesseraAtletaBAS";


        Object.keys(atleti_list).forEach(async (a) => {
            const atletaInfo = atleti_list[a];

            //            console.log(atletaInfo);
            console.log(atletaInfo.getAttribute('atleta'));

            var atleta = atletaInfo.getAttribute('atleta');
            var cognome = atletaInfo.getAttribute('cognome');
            var nome = atletaInfo.getAttribute('nome');
            var sesso = atletaInfo.getAttribute('sesso');
            var city = parseInt(atletaInfo.getAttribute('city'));
            var data_nascita = atletaInfo.getAttribute('data-nascita');
            var card_id = parseInt(atletaInfo.getAttribute('card-id'));
            var subscriber_id = parseInt(atletaInfo.getAttribute('subscriber-id'));

            if ((card_id == -1 || card_id == 0) && (city > 0)) {
                const a = {
                    squadra,
                    atleta,
                    cognome,
                    nome,
                    sesso,
                    city,
                    data_nascita,
                    card_id,
                    client_id,
                    subscriber_id
                };
                atleti[atleta] = a;
                array_id_atleti.push(atleta);

                await send(link, a)
            }


        });

        // console.log(atleti);
        // console.log(array_id_atleti);
        // send(link);

    }

    async function send(link, a) {
        var atletaId = array_id_atleti[index_invio];
        var to_send = a;
        const res = await httpPost(link, to_send);

        var atleta = res.atleta;
        var squadra = res.squadra;

        var li = document.getElementById(`${squadra}-${atleta}`);
        var message = document.getElementById(`message-${squadra}-${atleta}`);


        if ((typeof res.response == 'undefined') || (res.response == null)) {
            li.style.backgroundColor = 'orange';
            message.innerHTML = `- NULL RESPONSE`
            //            console.log(res.response.data);
        } else {
            if (typeof res.response.data !== 'undefined') {
                li.style.backgroundColor = 'greenyellow';

                if (res.response.data.card_id == "") {
                    li.style.backgroundColor = 'grey';
                }

                message.innerHTML = `- card id: ${res.response.data.card_id}`
                console.log(res.response.data);

            } else if (typeof res.response.message !== 'undefined') {
                li.style.backgroundColor = 'yellow';
                message.innerHTML = `- ${res.response.message}`
                //                console.log(res.response.data);

            } else if (typeof res.response.errors !== 'undefined') {
                li.style.backgroundColor = 'yellow';
                var errorList = [];
                Object.keys(res.response.errors).forEach((i) => {
                    console.log(i);
                    errorList.push(`- ${i}: ${res.response.errors[i]}`);
                });
                message.innerHTML = errorList.join("<br>");
                //                console.log(res.response.data);

            } else {
                li.style.backgroundColor = 'orange';
                //                console.log(res.response.data);
            }

        }



        // if (index_invio == array_id_atleti.length - 1) {
        //     return 0;
        // }


        // index_invio++;
        // send(link);


    }
</script>
<script>
    function httpPost(link, to_send) {
        return new Promise((resolve, reject) => {

            const xhr = new XMLHttpRequest();

            xhr.open("POST", link);

            xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");

            const body = JSON.stringify(to_send);

            xhr.send(body);

            xhr.onload = () => {

                if (xhr.readyState == 4 && xhr.status == 200) {
                    var arr = JSON.parse(xhr.response);
                    resolve(arr);
                } else {
                    reject(new Error(xhr.statusText));
                }
            };
        });
    }
</script>