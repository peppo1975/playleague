<? ob_start(); ?>
<br>
<table>
    <? if (isset($res['tabella']['non_inseriti'])): ?>
        <? $non_inseriti = $res['tabella']['non_inseriti']; ?>
        <tr>
            <td>
                <b style="color: red">NON INSERITI</b>
            </td>
        </tr>

        <? foreach ($non_inseriti as $key => $value): ?>

            <tr>
                <td>&emsp;<b><?= str_replace('_', ' ', $key) ?></b></td>
            </tr>

            <? foreach ($value as $persona): ?>

                <tr>
                    <td>
                        &emsp;&emsp;<?= implode(" ", $persona); ?>
                    </td>
                </tr> 

            <? endforeach; ?>

        <? endforeach; ?>

    <? endif; ?>

    <? if (isset($res['tabella']['inseriti'])): ?>
        <? $inseriti = $res['tabella']['inseriti']; ?>
        <tr>
            <td>
                <b style="color: green">INSERITI</b>
            </td>
        </tr>
        <? foreach ($inseriti as $persona): ?>
            <tr>
                <td>
                    &emsp;<? print_r(($persona)); ?>
                </td>
            </tr>
        <? endforeach; ?>
    <? endif; ?>
</table>
<? $html = ob_get_clean(); ?>
