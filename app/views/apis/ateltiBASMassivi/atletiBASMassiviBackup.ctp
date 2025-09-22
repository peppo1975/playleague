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
</head>

<body>
    <!--<div>TODO write content</div>-->
    <h2><?= $api->annoSportivo()['current']['year'] ?></h2>
    <? // print_r($res) 
    ?>
    <? $for_squadra = []; ?>
    <? foreach ($res as $squadra => $atleta_squadra): ?>
        
        <h4><?= $atleta_squadra['nome'] ?> (client_id: <?= $atleta_squadra['client_id'] ?>)</h4>
    
        <? if ($atleta_squadra['client_id'] > 0): ?>
            <button class="button-tessera" id="button-<?= $squadra ?>" value="<?= $squadra ?>" client-id="<?= $atleta_squadra['client_id'] ?>">TESSERA ATLETI</button>
        <? else: ?>
            <h5 style="background-color: orange">NON E' STATA CREATA LA BAS DELLA SQUADRA</h5>
        <? endif; ?>

        <ul>
            <? foreach ($atleta_squadra['atleti'] as $atleta): ?>
                <?
                $message = "";
                $card_id_div = "-1";
                $subscriber_id_div = "-1";
                $style = '';

                if (isset($atleta['BAS'])):
                    $message = "&emsp; - card id: " . $atleta['BAS']['card_id'] . " -- subscriber_id: " . $atleta['BAS']['subscriber_id'];
                    $card_id_div = $atleta['BAS']['card_id'];
                    $subscriber_id_div = $atleta['BAS']['subscriber_id'];
                    $style = 'background-color: greenyellow';

                    if ($atleta['BAS']['card_id'] == 0) {
                        $style = 'background-color: orange;';
                    }

                endif;
                if ($atleta['CityNascita'] == 0):

                    $style = 'background-color: red; color: white';
                    $message = " - MANCA COMUNE DI NASCITA";

                endif;

                if ($atleta['BAS']['card_id'] == "0" && $atleta['BAS']['subscriber_id'] !== "0") {
                    $style = 'background-color: #9d9de1;';
                }

                $atletaInfo = "<strong>{$atleta['Cognome']} {$atleta['Nome']}</strong> (id: {$atleta['Atleta']} - data nascita: {$atleta['DataNascita']} - associazioni Squadra: {$atleta['contaAnniTesseramenti']})";
                ?>
                <li>
                    <div
                        style="<?= $style ?>"
                        class="squadra-<?= $atleta['Squadra'] ?>"
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
                        <div id="message-<?= $atleta['Squadra'] ?>-<?= $atleta['Atleta'] ?>">
                            <?= $message ?>
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

    button_tessera = document.getElementsByClassName("button-tessera");

    Object.keys(button_tessera).forEach((bt) => {
        button_tessera[bt].addEventListener('click', (e) => {
            //            alert(bt)
            console.log(e);

            var squadra = e.srcElement.value;
            var client_id = button_tessera[bt].getAttribute('client-id');;
            scorriClasseAtleta(squadra, client_id);
        });
    });


    function scorriClasseAtleta(squadra, client_id) {
        var atleti_list = document.getElementsByClassName(`squadra-${squadra}`);

        atleti = {};
        index_invio = 0
        array_id_atleti = [];
        var link = "https://<?= $_SERVER['HTTP_HOST'] ?>/apis/tesseraAtletaBAS";


        Object.keys(atleti_list).forEach((a) => {
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
            }


        });

        console.log(atleti);
        console.log(array_id_atleti);
        send(link);

    }

    async function send(link) {
        var atletaId = array_id_atleti[index_invio];
        var to_send = atleti[atletaId];
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



        if (index_invio == array_id_atleti.length - 1) {
            return 0;
        }


        index_invio++;
        send(link);


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