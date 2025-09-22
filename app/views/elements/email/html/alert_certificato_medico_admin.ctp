<?
$now = date('Y-m-d');
?>


<p><strong>Scadenza certificato medico</strong></p>

<p>Prossime scadenze</p>

<table>
    <? foreach ($res_for_admin as $key_date => $date): ?>
        <?
        $data_scadenza = $key_date;

        $sc = explode("-", $data_scadenza);

        $nw = explode("-", $now);

        $data1 = gregoriantojd($sc[1], $sc[2], $sc[0]);

        $data2 = gregoriantojd($nw[1], $nw[2], $nw[0]);

        $days = $data1 - $data2;
        ?>

        <thead>
            <tr>

                <td style="text-align: right"><strong><?= $days; ?> Giorni</strong></td>
                <td>(<?= $sc[2] . "/" . $sc[1] . "/" . $sc[0] ?>)</td>
                <td></td>
                <td></td>
            </tr>
        </thead>

        <tbody>
            <? foreach ($date as $key_people => $people): ?>
                <? $nascita = explode("-", $people['Nascita']); ?>
                <tr>
                    <td></td>
                    <td><?= $people['Nominativo']; ?></td>
                    <td><?= $nascita[2] . "/" . $nascita[1] . "/" . $nascita[0]; ?>&emsp;</td>
                    <td><?= $people['Email']; ?></td>
                </tr>
            <? endforeach; ?>
            <tr>
                <td>&emsp;</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>

    <? endforeach; ?>
</table>
