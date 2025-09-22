<?
//GIUSEPPE 2024-06-09
$json = file_get_contents('php://input');
$_post = json_decode($json, true);

if (isset($_post['quickSearch'])) {
    $_POST['quickSearch'] = $_post['quickSearch'];
}
;
// ----------------------

print $backend->formIndex(
    'Squadre',
    array(
        'Denominazione' =>
            array(
                'field' => 'Squadre.Denominazione',
                'order' => true,
            ),
        'SPORT' =>
            array(
                'field' => 'Squadre.sport',
                'order' => true,
            ),
    )
    ,
    array(
        'defaultOrder' => 'Squadre.Denominazione',
        'defaultDir' => 'ASC',
        'pageTitle' => 'Tabella squadre',
        'quickSearch' => array('Squadre.Denominazione')
    )
);
?>

<script src="./js/script_my.js" type="text/javascript"></script>
<script>


    // document.addEventListener("DOMContentLoaded", function () {
    class Operazioni {

        squadraInfo;

        constructor(squadraInfo) {
            //alert("test");
            //this.squadraInfo = <?//= json_encode($this->data['Squadre']) ?>;
            this.squadraInfo = squadraInfo;
            //this.controllaRinnovo();
            console.log(this.squadraInfo);
            this.infoFileLoaded();
        }

        async controllaRinnovo() { // forse non serve
            var squadra = this.squadraInfo.Squadra;
            const res = await httpPost('/squadres/analizzaRinnovo', { squadra });
            console.log(res);
            //            {renewal: true, AnnoSportivo: 2025}
            if (res.renewal == true) {
                var renevalDiv = document.getElementById('buttonRenewal');
                var buttonRenewal = document.createElement('a');
                buttonRenewal.setAttribute('type', 'button');
                buttonRenewal.setAttribute('id', 'buttonRinnova');
                buttonRenewal.innerText = "RINNOVA BAS PER L' ANNO " + res.anno;
                renevalDiv.appendChild(buttonRenewal);

                this.mettiInListaRinnovo(squadra, res.anno);
            }
        }


        mettiInListaRinnovo(squadra, anno, buttonRenewal) { // forse non serve
            var buttonRinnova = document.getElementById('buttonRinnova');
            buttonRinnova.addEventListener('click', async () => {
                const res = await httpPost('/squadres/mettiInListaRinnovo', { squadra, anno });

                if (parseInt(res.result) > 0) {
                    var messageRenewal = document.getElementById('messageRenewal');
                    var buttonRenewal = document.getElementById('buttonRinnova');
                    buttonRenewal.innerText = `SQUADRA INSERITA IN LISTA PER RINNOVO ${res.anno}`;
                    buttonRenewal.style.backgroundColor = '#2fe5ff';
                    messageRenewal.innerHTML = "Vai in Tabelle/Tabella squadre BAS → tasto VEDI RINNOVI"
                }
            });
        }

        async infoFileLoaded() {
            var squadra = this.squadraInfo;
            console.log(squadra)
            const res = await httpPost('/squadres/infoFileLoaded', { ...squadra });

            Object.keys(res).forEach((key) => {
                console.log(key);
                document.getElementById(key + "_DATE").innerHTML = res[key]['date'] == "" ? "" : `(${res[key]['date']}) `;
                if (res[key] !== "") {
                    var id = squadra.Squadra;
                    var a = document.getElementById(key);
                    a.innerHTML = res[key]['name'];
                    a.setAttribute('href', res[key]['file']);
                    a.setAttribute('target', '_blank');
                }
            });
        }


    }




    // });
</script>