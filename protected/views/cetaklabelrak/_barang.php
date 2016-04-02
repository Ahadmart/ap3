<?php

$this->widget('BGridView', array(
    'id' => 'label-rak-cetak-grid',
    'dataProvider' => $model->search(),
    'filter' => null,
    'columns' => array(
        //'barang_id',
        array(
            'header' => 'Barcode',
            'value' => '$data->barang->barcode'
        ),
        array(
            'header' => 'Nama',
            'value' => '$data->barang->nama'
        ),
        array(
            'header' => 'Kategori',
            'value' => '$data->barang->kategori->nama'
        ),
        array(
            'header' => 'Satuan',
            'value' => '$data->barang->satuan->nama'
        ),
        array(
            'header' => 'Harga Jual',
            'value' => '$data->barang->hargajual'
        ),
        //'updated_at',
        //'updated_by',
        //'created_at',
        array(
            'class' => 'BButtonColumn',
        ),
    ),
));

