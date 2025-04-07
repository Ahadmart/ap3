<?php
/*
$this->widget('BGridView', [
'id'           => 'sku-transfer-detail-grid',
'dataProvider' => $model->search(),
// 'filter'       => $model,
'columns'      => [
[
'name' => 'from_barang_id',
'value' => '$data->fromBarang->barcode." ".$data->fromBarang->nama'
],
'from_qty',
[
'name' => 'from_satuan_id',
'value' => '$data->fromSatuan->nama'
],
[
'name' => 'to_barang_id',
'value' => '$data->toBarang->nama'
],
'to_qty',
[
'name' => 'to_satuan_id',
'value' => '$data->toSatuan->nama'
],
// 'updated_at',
// 'updated_by',
// 'created_at',
],
]);
 */
?>
<table class="tabel-index">
    <thead>
        <tr>
            <th>Dari:</th>
            <th>Menjadi:</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($skuTransferDetail as $detail) {
        ?>
            <tr>
                <td>
                    <?= $detail->fromBarang->barcode ?><br />
                    <?= $detail->fromBarang->nama ?> <br />
                    <h5><?= $detail->from_qty ?> <?= $detail->fromSatuan->nama ?></h5>
                </td>
                <td>
                    <?= $detail->toBarang->barcode ?><br />
                    <?= $detail->toBarang->nama ?> <br />
                    <h5><?= $detail->to_qty ?> <?= $detail->toSatuan->nama ?></h5>
                </td>
            </tr>
        <?php
        }
        ?>

    </tbody>
</table>