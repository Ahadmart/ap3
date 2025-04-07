<?php
$this->widget('BGridView', [
    'id'               => 'barang-tujuan-grid',
    'dataProvider'     => $barangTujuan->search($criteria ?? null),
    'selectionChanged' => 'tujuanDipilih',
    'columns'          => [
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
            'header'            => 'Stok',
            'value'             => '$data->barang->stok',
            'htmlOptions'       => ['class' => 'rata-kanan'],
            'headerHtmlOptions' => ['style' => 'width:75px', 'class' => 'rata-kanan'],
        ],
        [
            'name'  => 'namaSatuan',
            'value' => '$data->barang->satuan->nama',
        ],
        [
            'name'  => 'namaRak',
            'value' => 'is_null($data->barang->rak) ? "" : $data->barang->rak->nama',
        ],
    ],
]);
