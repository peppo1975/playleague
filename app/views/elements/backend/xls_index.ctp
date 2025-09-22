<?
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment;filename=" . urlencode($pageTitle) . "_" . date("d_m_Y") . ".xls");
?>		

<!--//timmytag 2022-12-09-->
<?
//https://corsidia.com/materia/web-design/caratterispecialihtml
$special_char = ["→", "á", "à"];
$convert_char = ["&#8594;", "&aacute;", "&agrave;"];
?>
<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>
<!-- ################### -->

<table id="index_table" class="index_table index_<?= strtolower($this->name); ?>" bo>
    <tbody class="content">
        <tr class="th_row sortable_disabled">


            <? $i = 0; ?>

            <? foreach ($fields as $key => $value): ?>

                <? if ($value['field'] != "$model.disabled"): ?>
                    <!--<th class="<?= (($i == count($fields) - 1) ? 'last' : '') ?> th_<?= strtolower(Inflector::slug($key)); ?>"><?= strip_tags($backend->getOrder($value, $key)); ?></th>-->
                    <!--//timmytag 2022-12-09-->
                    <th class="<?= (($i == count($fields) - 1) ? 'last' : '') ?> th_<?= strtolower(Inflector::slug($key)); ?>"><?= utf8_decode(strip_tags($backend->getOrder($value, $key))); ?></th>
                    <!-- ################### -->
                <? else: ?>
                    <? $show_status = $key; ?>
                <? endif; ?>

                <? $i++; ?>

            <? endforeach; ?>

        </tr>
        <? $j = 0; ?>
        <? foreach ($data as $row): ?> 

            <tr id="<?= $row[$model][$pk]; ?>" class="index-row switch <?= (($j == 1) ? 'alterna' : '') ?> " data-dest="view_mode" data-ajax="<?= $html->url(array('controller' => $this->params['controller'], 'prefix' => $this->params['prefix'], 'action' => 'edit', $row[$model][$pk])); ?>">



                <? $i = 0; ?>


                <? foreach ($fields as $key => $value): ?>
                    <? if ($value['field'] != "$model.disabled"): ?>
                        <? if (!isset($value['afterRender'])): ?>
                            <!--<td class="<?= (($i == count($fields) - 1) ? 'last' : '') ?> td_<?= strtolower(Inflector::slug($key)); ?>"><?= $backend->getField($row, $value['field']); ?></td>-->
                            <!--//timmytag 2022-12-09-->
                            <td class="<?= (($i == count($fields) - 1) ? 'last' : '') ?> td_<?= strtolower(Inflector::slug($key)); ?>"><?= str_replace($special_char, $convert_char, $backend->getField($row, $value['field'])); ?></td>
                            <!-- ################### -->
                        <? else: ?>
                            <!--<td class="<?= (($i == count($fields) - 1) ? 'last' : '') ?> td_<?= strtolower(Inflector::slug($key)); ?>"><?= $value['afterRender']($backend->getField($row, $value['field'])); ?></td>-->

                            <!--//timmytag 2022-12-09-->
                            <td class="<?= (($i == count($fields) - 1) ? 'last' : '') ?> td_<?= strtolower(Inflector::slug($key)); ?>"><?= str_replace($special_char, $convert_char, $value['afterRender']($backend->getField($row, $value['field']))); ?></td>
                            <!-- ################### -->
                        <? endif; ?>
                    <? endif; ?>
                    <? $i++; ?>

                <? endforeach; ?>


            </tr>
            <? $j = ($j == 1) ? 0 : 1; ?>
        <? endforeach; ?>
    </tbody>
</table>
