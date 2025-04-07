<?php
// Bisa Edit Qty jika masih draft
if ($pembelian->status == Pembelian::STATUS_DRAFT) :
    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.poshytip.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery-editable-poshytip.min.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-editable.css');
endif;
?>

<div class="small-12  columns">
    <?php
    $this->widget('BGridView', [
        'id'           => 'pembelian-detail-grid',
        'dataProvider' => $pembelianDetail->search($pilihBarang ? null : 't.id'),
        //'filter' => $pembelianDetail,
        'rowCssClassExpression' => function ($row, $data) {
            if ($data->isBarangBaru()) {
                return 'baru';
            } elseif ($data->isMarginMin()) {
                return 'margin-min';
            } elseif ($data->isHargaJualBerubah()) {
                return 'hj-berubah';
            }
        },
        'summaryText' => '{start}-{end} dari {count}, Total: <span class="label-total">' . $pembelian->total . '</span>',
        'columns'     => [
            [
                'name'  => 'barcode',
                'value' => '$data->barang->barcode',
            ],
            [
                'name'  => 'namaBarang',
                'value' => '$data->barang->nama',
            ],
            [
                'name'  => 'qty',
                'value' => function ($data) {
                    return '<a href="#" class="editable-qty" data-type="text" data-pk="' . $data->id . '" data-url="' . Yii::app()->controller->createUrl('updateqty') . '">' .
                        $data->qty . '</a>';
                },
                'type'              => 'raw',
                'headerHtmlOptions' => ['style' => 'width:75px', 'class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
            ],
            [
                'name'              => 'harga_beli',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
                'value'             => 'number_format($data->harga_beli, 0, ",", ".")'
            ],
            [
                'name'              => 'harga_jual',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
                'value'             => 'number_format($data->harga_jual, 0, ",", ".")'
            ],
            [
                'name'              => 'subTotal',
                'header'            => 'Total',
                'value'             => '$data->total',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
                'filter'            => false
            ],
            [
                'header' => 'Rak=NULL',
                'value'  => function ($data) {
                    if (is_null($data->barang->rak_id)) {
                        return '<a href="#" class="editable-rak" data-type="select" data-pk="' . $data->barang_id . '" data-url="' . Yii::app()->controller->createUrl('updaterak') . '">NULL</a>';
                    } else {
                        /* Uncomment jika ingin ditampilkan nama rak */
                        //return $data->barang->rak->nama;
                    }
                },
                'type'              => 'raw',
                'headerHtmlOptions' => ['class' => 'rata-tengah'],
                'htmlOptions'       => ['class' => 'rata-tengah'],
            ],
            // Jika pembelian masih draft tampilkan tombol hapus
            [
                'class'           => 'BButtonColumn',
                'template'        => $pembelian->status == 0 ? '{delete}' : '',
                'deleteButtonUrl' => 'Yii::app()->controller->createUrl("pembelian/hapusdetail", array("id"=>$data->primaryKey))',
                'afterDelete'     => 'function(link,success,data){ if(success) updateTotal(); }',
            ],
        ],
    ]);
    ?>
</div>

<script>
    function enableEditable() {
        $(".editable-qty").editable({
            mode: "inline",
            inputclass: "input-editable-qty",
            success: function(response, newValue) {
                if (response.sukses) {
                    $.fn.yiiGridView.update("pembelian-detail-grid");
                    updateTotal();
                }
            }
        });
        $(".editable-rak").editable({
            mode: "inline",
            //inputclass: "input-editable-qty",
            success: function(response, newValue) {
                if (response.sukses) {
                    $.fn.yiiGridView.update("pembelian-detail-grid");
                }
            },
            source: [
                <?php
                $listRak  = CHtml::listData(RakBarang::model()->findAll(['select' => 'id,nama', 'order' => 'nama']), 'id', 'nama');
                $firstRow = true;
                foreach ($listRak as $key => $value) :
                ?>
                    <?php
                    if (!$firstRow) {
                        echo ',';
                    }
                    $firstRow = false;
                    ?> {
                        value: <?php echo $key; ?>,
                        text: "<?php echo $value; ?>"
                    }
                <?php
                endforeach;
                ?>
            ]
        });
    }
    $(function() {
        enableEditable();
    });
    $(document).ajaxComplete(function() {
        enableEditable();
    });
</script>