<?php
$this->widget('BGridView', [
    'id'           => 'sku-detail-grid',
    'dataProvider' => $modelDetail->search(),
    'filter'       => $modelDetail,
    'columns'      => [
        [
            'name'  => 'barcode',
            'value' => '$data->barang->barcode',
        ]
    ],
]);
