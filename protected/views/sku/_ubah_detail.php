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
            'name'  => 'namaBarang',
            'value' => '$data->barang->nama',
        ],
        [
            'name'  => 'level',
            'value' => '$data->skuLevel->level ?? ""',
            'htmlOptions'       => ['class' => 'rata-tengah'],
            'headerHtmlOptions' => ['class' => 'rata-tengah'],
        ],
        [
            'name'  => 'namaSatuan',
            'value' => '$data->barang->satuan->nama',
            // 'filter' => $barang->filterSatuan()
        ],
        [

            'name'  => 'Konversi',
            'type'  => 'raw',
            'value' => [$this, 'renderQtyPerUnit'],
        ],
        [
            'header'            => 'HB',
            'value'             => '$data->barang->hargaBeli',
            'htmlOptions'       => ['class' => 'rata-kanan'],
            'headerHtmlOptions' => ['class' => 'rata-kanan'],
        ],
        [
            'header'            => 'HJ',
            'value'             => '$data->barang->hargaJual',
            'htmlOptions'       => ['class' => 'rata-kanan'],
            'headerHtmlOptions' => ['class' => 'rata-kanan'],
        ],
        [
            'header'            => 'Stok',
            'value'             => '$data->barang->stok',
            'htmlOptions'       => ['class' => 'rata-kanan'],
            'headerHtmlOptions' => ['style' => 'width:75px', 'class' => 'rata-kanan'],
        ],
        [
            'class'           => 'BButtonColumn',
            'deleteButtonUrl' => 'Yii::app()->controller->createUrl("sku/hapusdetail", ["id"=>$data->primaryKey])',
        ],
    ],
]);
