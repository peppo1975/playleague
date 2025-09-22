<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Iscrizione campionati http://www.midlandsport.it</title>
    </head>
    <body>
        <table align="center" cellspacing="0"  style="margin 10px auto; width:700px;" >
            <thead>
                <tr>
                    <td>
                        <a href="http://midlandsport.it/contenuti/80/prossime-manifestazioni" style="vertical-align: middle;" title="http://www.midlandsport.it">
                            <img src="http://www.midlandsport.it/img/ijhdhbbb.jpg" alt=""/>
                        </a>
                    </td>
                </tr>
            </thead>

            <tbody style="background-color: #efefef; width:700px;" >
                <tr>
                    <td align="left">
                        <div style="padding: 10px 20px; width:640px;" >
                            <h4>RIEPILOGO DATI TESSERAMENTO/I:</h4>
                            <? foreach ($tesserati['atleti'] as $tesserato): ?>
                                <p>
                                    <? foreach ($tesserato as $key => $value): ?>
                                        <? if ($key != "totale" && $key != "Atleta"): ?>
                                            <b><?= ucfirst($key); ?>:</b> <?= $value; ?><br />
                                        <? endif; ?>
                                    <? endforeach; ?>
                                </p>
                                <hr />

                            <? endforeach; ?>

                            <ul style="font-size: 11px; padding: 10px 0;">
                                <li>Il presente tesseramento assicurativo è valido per la stagione sportiva in corso, con validità dal 01/09 al 31/08.</li>
                                <li>Il presente tesseramento è valido per ogni manifestazione Midland GS della stagione.</li>
                                <li>L'emissione della tessera fisica con foto è gratuita, previo consegna della foto stessa presso la sede. Ogni tesserato che non possiede la tessera con foto dovrà così presentarsi alla gara con un documento di identità valido.</li>
                                <li>Si ricorda che nei periodi di fasi finali, sia dei Campionati che dei Tornei, quando si chiudono i periodi di tesseramento  (ogni manifestazione ha il suo periodo, consultare il regolamento) il tesseramento effettuato on line, pur essendo valido, non permette di prendere parte a quelle determinate partite, la partecipazione comporterà la sconfitta a tavolino e la relativa sanzione.</li>
                                <li>Per rivedere le condizioni della copertura assicurativa scelta tra Base, Medium e Full, consultare anche il sito internet sul manuale "Strutturazione delle manifestazioni" disponibile nella sezione Download.</li>
                                <li>Si informa inoltre che la copertura Base è attiva dal giorno stesso, mentre le integrative Medium e Full si attivano dopo 7/10 giorni dalla sottoscrizione.</li>
                                <li>Per ogni altra info sul tesseramento consultare il manuale "Strutturazione delle manifestazioni" disponibile nella sezione Download.</li>
                            </ul>
                        </div>
                    </td>
                </tr>			
            </tbody>

            <tfoot style="width:700px;" >
                <tr> <!-- footer -->
                    <td>
                        <p>
                            <a href="http://midlandsport.it/contatti" style="text-decoration: none;">
                                <img src="http://www.midlandsport.it/img/signupft.jpg" alt="" />
                            </a>
                        </p>
                    </td>
                </tr>
            </tfoot>

        </table>


    </body>
</html>