<?php
$barang = new Barang();

$this->widget('BGridView', [
    'id'           => 'sku-detail-grid',
    'dataProvider' => $modelDetail->search(),
    'filter'       => null, // $modelDetail,
    'columns'      => [
        [
            'name'  => 'barcode',
            'value' => '$data->barang->barcode',
        ],
        [
            'name' => 'namaBarang',
            'value' => '$data->barang->nama'
        ],
        [
            'name' => 'namaSatuan',
            'value' => '$data->barang->satuan->nama',
            'filter' => $barang->filterSatuan()
        ],
        [
            'class' => 'BButtonColumn',
            // 'template' => $penjualan->status == 0 ? '{delete}' : '',
            'deleteButtonUrl' => 'Yii::app()->controller->createUrl("sku/hapusdetail", ["id"=>$data->primaryKey])',
            // 'afterDelete' => 'function(link,success,data){ if(success) updateTotal();}',
        ],
    ],
]);
