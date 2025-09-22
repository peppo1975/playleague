<style>
    .not-style{
        color:black;
        font-weight: bold;
    }

    .is-exist{
        color: green;
    }

    .not-exist{
        color: red;
        font-weight: bold;
        background-color: yellow;
    }

    .undefined{
        background-color: grey;
    }
</style>

<table>
    <? foreach ($cw as $r => $row): ?>
        <tr>
            <? foreach ($row as $c => $column): ?>
                <?
                $class = "not-style";
                $class_td = "not-style";

                if ($r > 1)
                {
                    $class = "is-exist";
                }

                if (isset($analizza['twin'][$c][$r]))
                {
                    if ($analizza['twin'][$c][$r] == 0)
                    {
                        $response['exist'] = false;
                        $class = "not-exist";
                        $class_td = "not-exist";
                    }
                    if ($analizza['twin'][$c][$r] == -1)
                    {
                        $response['exist'] = false;
                        $class_td = "undefined";
                    }
                }
                else
                {
                    $class = "not-style";
                    $class_td = "not-style";
                }
                ?>

                <td class="<?= $class_td ?>"><div class="<?= $class ?>"><?= "{$column}" ?></div></td>

            <? endforeach; ?>
        </tr>
    <? endforeach; ?>
</table>