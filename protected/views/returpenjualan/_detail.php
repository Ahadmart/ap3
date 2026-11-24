<div class="small-12  columns">
    <?php
    $this->widget('BGridView', [
        'id' => 'retur-penjualan-detail-grid',
        'dataProvider' => $returPenjualanDetail->search(),
        // 'filter' => $penjualanDetail,
        'enableSorting' => false,
        'columns' => [
            [
                'name' => 'barcode',
                'value' => '$data->penjualanDetail->barang->barcode',
            ],
            [
                'name' => 'namaBarang',
                'value' => '$data->penjualanDetail->barang->nama',
            ],
            [
                'header' => 'Penjualan',
                'value' => '$data->penjualanDetail->penjualan->nomor',
                'value' => function ($data) {
                    $r = $data->penjualanDetail->penjualan->nomor;
                    if (!empty($data->penjualanDetail->penjualan->nomor_publik)) {
                        $r .= '<br />' . $data->penjualanDetail->penjualan->nomor_publik;
                    }
                    return $r;
                },
                'type' => 'raw',
            ],
            [
                'header' => 'Tanggal Penjualan',
                'value' => '$data->penjualanDetail->penjualan->tanggal',
            ],
            [
                'header' => 'Harga Jual',
                'value' => 'number_format($data->penjualanDetail->harga_jual,0,",",".")',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions' => ['class' => 'rata-kanan']
            ],
            [
                'name' => 'qty',
                'headerHtmlOptions' => ['style' => 'width:75px;', 'class' => 'rata-kanan'],
                'htmlOptions' => ['class' => 'rata-kanan'],
            ],
            [
                'name' => 'subTotal',
                'value' => 'number_format($data->total,0,",",".")',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions' => ['class' => 'rata-kanan'],
                'filter' => false
            ],
            // Jika masih draft tampilkan tombol hapus
            [
                'class' => 'BButtonColumn',
                'template' => $returPenjualan->status == 0 ? '{delete}' : '',
                'deleteButtonUrl' => 'Yii::app()->controller->createUrl("returPenjualan/hapusdetail", array("id"=>$data->primaryKey))',
                'afterDelete' => 'function(link,success,data){ if(success) updateTotal();}',
            ],
        ],
    ]);
    ?>
</div>