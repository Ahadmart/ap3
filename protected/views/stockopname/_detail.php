<div class="small-12  columns">
    <?php
    $this->widget(
        'BGridView',
        [
            'id'                    => 'so-detail-grid',
            'dataProvider'          => $modelDetail->search(),
            'rowCssClassExpression' => function ($row, $data) {
                // if ($data->bedaRaknya()) {
                //     return 'baru'; // Rak beda, diberi tanda beda warna di barisnya
                // }
                // Beda warna untuk barang diset non aktif
                return $data->set_inaktif == 1 ? 'inaktif' : '';
            },
            'columns'               => [
                // [
                //     'name'              => 'barcode',
                //     'value'             => '$data->barang->barcode',
                //     'headerHtmlOptions' => ['class' => 'hide-for-small-only'],
                //     'htmlOptions'       => ['class' => 'hide-for-small-only'],
                // ],
                [
                    'name'   => 'namaBarang',
                    'type'   => 'raw',
                    'value'  => [$this, 'renderBarang'],
                    'header' => 'Barang',
                ],
                // [
                //     'value' => '$data->barang->rak->nama',
                // ],
                [
                    'name'              => 'qty_tercatat',
                    'headerHtmlOptions' => ['style' => 'width:75px;', 'class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                ],
                // [
                //     'header'            => 'Draft Retur Beli',
                //     'value'             => '$data->barang->qtyReturBeli',
                //     'headerHtmlOptions' => ['style' => 'width:75px;', 'class' => 'rata-kanan'],
                //     'htmlOptions'       => ['class' => 'rata-kanan'],
                //     'visible'           => $showQtyReturBeli,
                // ],
                [
                    'name'              => 'qty_sebenarnya',
                    'headerHtmlOptions' => ['style' => 'width:75px;', 'class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                ],
                [
                    'header'            => 'Selisih',
                    'value'             => '$data->selisih',
                    'headerHtmlOptions' => ['style' => 'width:75px;', 'class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                ],
                [
                    'class'           => 'BButtonColumn',
                    'template'        => '{delete}',
                    'deleteButtonUrl' => 'Yii::app()->controller->createUrl("stockopname/hapusdetail", array("id"=>$data->primaryKey))',
                    'afterDelete'     => 'function(link,success,data){ if(success) $.fn.yiiGridView.update("barang-grid");}',
                ],
            ],
        ]
    );
    ?>
</div>