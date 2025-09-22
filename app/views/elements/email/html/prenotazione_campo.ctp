<h2>Conferma prenotazione campo <?= $nome_campo ?></h2>
<p>
    Gentile <?= $booker ?><br>
    <!--confermiamo la prenotazione del <strong><?= $nome_campo ?></strong> per il giorno <strong><?= $data ?></strong> alle ore <strong><?= $ora ?></strong>.-->
    confermiamo la prenotazione del <strong><?= $nome_campo ?></strong> per <?= count($giorni) == 1 ? "la data:" : "le date:" ?>
</p>


<ul>
    <? foreach ($giorni as $giorno): ?>
        <li>
            <?= $giorno['Data'] ?> - <?= $giorno['Ora'] ?>
            <br>
            La quota di allenamento è di <strong><?= $giorno['Importo'] ?> €</strong>
        </li>
    <? endforeach; ?>
</ul>



<p>
        <!--La quota di allenamento è di <strong><?= $importo ?> €</strong>-->
</p>
<p>
    pagina dettagli:<br>
    <a href="<?= $link ?>">Dettagli</a>

</p>