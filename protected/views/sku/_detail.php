<?php
$this->widget('BGridView', [
    'id'           => 'sku-detail-grid',
    'dataProvider' => $modelDetail->search(),
    // 'filter'       => $modelDetail,
    'columns'      => [
        [
            'name'   => 'barcode',
            'header' => 'Barcode',
            'value'  => '$data->barang->barcode',
        ],
        [
            'name'  => 'namaBarang',
            'value' => '$data->barang->nama',
        ],
        [
            'name'  => 'level',
            'value' => '$data->skuLevel->level ?? ""',
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
    ],
]);
