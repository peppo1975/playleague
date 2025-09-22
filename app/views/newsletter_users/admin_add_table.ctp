<? ob_start(); ?>

<br>

<? if (isset($res['to_edit'])): ?>
    <table>  

        <tr>
            <td>
                <b style="color: orange">EMAIL GIA PRESENTI</b>
            </td>
            <td></td>
            <td></td>
        </tr>

        <? foreach ($res['to_edit'] as $key => $value): ?>
            <?
            $email = $value['email'];
            $old_groups = array();
            foreach ($value['old_groups'] as $old_group)
            {
                $old_groups[] = $old_group['newsletter_group_id'];
            }
            ?>
            <tr>
                <td>&emsp;<b><?= $email ?></b></td>
                <td>sostituire i vecchi gruppi (<strong><?= implode(", ", $old_groups) ?></strong>)</td>
                <td>con i nuovi gruppi (<strong><?= implode(", ", $value['groups']) ?></strong>)</td>
            </tr>

        <? endforeach; ?>
    </table>
    <hr size="1" >
<? endif; ?>


<? if (isset($res['not_valid'])): ?>

    <table>
        <tr>
            <td>
                <b style="color: red">EMAIL NON VALIDE</b>
            </td>

        </tr>
        <? foreach ($res['not_valid'] as $key => $value): ?>
            <tr>
                <td>
                    &emsp;<strong><?= $value ?></strong>
                </td>

            </tr>
        <? endforeach; ?>
    </table>  
    <hr size="1" >
<? endif; ?>


<? if (isset($res['to_insert'])): ?>

    <table>
        <tr>
            <td>
                <b style="color: green">EMAIL VALIDE</b>
            </td>
            <td></td>

        </tr>
        <? foreach ($res['to_insert'] as $key => $value): ?>
            <tr>
                <td>
                    &emsp;<strong><?= $value['email'] ?></strong>
                </td>
                <td>gruppi (<strong><?= implode(", ", $value['groups']) ?></strong>)</td>

            </tr>
        <? endforeach; ?>
    </table>    
    <hr size="1" >
<? endif; ?>





<? $html = ob_get_clean(); ?>
