  <?php
    $this->widget('BGridView', [
        'id'           => 'diskon-barang-varian-detail-grid',
        'dataProvider' => $varianDiskon->search(),
        // 'filter'       => $varianDiskon,
        'columns'      => [
            // 'id',
            // 'barang_diskon_id',
            'tipe',
            'barang_id',
            // 'nominal',
            // 'persen',
            'qty',
            // 'qty_min',
            'qty_max',
            /*
        'updated_at',
        'updated_by',
        'created_at',
         */
            [
                'class' => 'BButtonColumn',
            ],
        ],
    ]);
    ?>