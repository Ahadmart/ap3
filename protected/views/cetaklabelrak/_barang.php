<?php

$this->widget('BGridView', array(
    'id' => 'label-rak-cetak-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        //'barang_id',
        array(
            'value' => '$data->barang->barcode'
        ),
        array(
            'value' => '$data->barang->nama'
        ),
        //'updated_at',
        //'updated_by',
        //'created_at',
        array(
            'class' => 'BButtonColumn',
        ),
    ),
));

