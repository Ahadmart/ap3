<?php
/*
$this->widget('BGridView', [
'id'           => 'barang-asal-grid',
'dataProvider' => $barangAsal->search(),
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
[
'header' => 'Pilih',
'type'   => 'raw',
'value'  => 'CHtml::radioButton("selected_dari", false, array("value" => $data->id))',
],
],
]);*/
?>
<?php
$this->widget('BGridView', [
    'id'               => 'barang-asal-grid',
    'dataProvider'     => $barangAsal->search(),
    'selectionChanged' => 'asalDipilih',
    'selectableRows'   => 1,
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
?>