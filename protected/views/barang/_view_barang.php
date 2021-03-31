<h4><small>Data</small> Barang</h4>
<hr />
<?php
$this->widget('BDetailView', array(
    'data'       => $model,
    'attributes' => array(
        'nama',
        'barcode',
        array(
            'name'  => 'satuan.nama',
            'label' => 'Satuan',
        ),
        array(
            'name'  => 'kategori.nama',
            'label' => 'Kategori',
        ),
        [
            'name'  => 'namaStruktur',
            'label' => 'Struktur',
        ],
        array(
            'name'  => 'rak.nama',
            'label' => 'Rak',
        ),
        'restock_min',
        'variant_coefficient',
        /*
        'restock_point',
        'restock_level',
         *
         */
        array(
            'label' => 'Status',
            'value' => $model->namaStatus,
        ),
    ),
));
