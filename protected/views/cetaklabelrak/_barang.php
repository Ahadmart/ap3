<?php
$this->widget('BGridView', array(
    'id' => 'label-rak-cetak-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'barcode',
            'value' => '$data->barang->barcode',
            'filter' => false,
        ),
        array(
            'name' => 'namaBarang',
            'value' => '$data->barang->nama',
            'filter' => false,
        ),
        array(
            'name' => 'kategoriId',
            'value' => '$data->barang->kategori->nama',
            'filter' => LabelRakCetak::model()->filterKategori()
        ),
        array(
            'header' => 'Satuan',
            'value' => '$data->barang->satuan->nama'
        ),
        array(
            'header' => 'Harga Jual',
            'value' => '$data->barang->hargajual'
        ),
        array(
            'class' => 'BButtonColumn',
        ),
    ),
));