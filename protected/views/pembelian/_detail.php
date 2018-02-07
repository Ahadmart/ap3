<?php
// Bisa Edit Qty jika masih draft
if ($pembelian->status == Pembelian::STATUS_DRAFT):
    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.poshytip.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery-editable-poshytip.min.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-editable.css');
endif;
?>

<div class="small-12  columns">
    <?php
    $this->widget('BGridView', array(
        'id' => 'pembelian-detail-grid',
        'dataProvider' => $pembelianDetail->search($pilihBarang ? NULL : 't.id'),
        //'filter' => $pembelianDetail,
        'rowCssClassExpression' => function($row, $data) {
            if ($data->isBarangBaru()) {
                return 'baru';
            } else if ($data->isHargaJualBerubah()) {
                return 'hj-berubah';
            }
        },
        'summaryText' => '{start}-{end} dari {count}, Total: ' . $pembelian->total,
        'columns' => array(
            array(
                'name' => 'barcode',
                'value' => '$data->barang->barcode',
            ),
            array(
                'name' => 'namaBarang',
                'value' => '$data->barang->nama',
            ),
            array(
                'name' => 'qty',
                'value' => function($data) {
                    return '<a href="#" class="editable-qty" data-type="text" data-pk="' . $data->id . '" data-url="' . Yii::app()->controller->createUrl('updateqty') . '">' .
                            $data->qty . '</a>';
                },
                'type' => 'raw',
                'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
                'htmlOptions' => array('class' => 'rata-kanan'),
            ),
            array(
                'name' => 'harga_beli',
                'headerHtmlOptions' => array('class' => 'rata-kanan'),
                'htmlOptions' => array('class' => 'rata-kanan'),
                'value' => 'number_format($data->harga_beli, 0, ",", ".")'
            ),
            array(
                'name' => 'harga_jual',
                'headerHtmlOptions' => array('class' => 'rata-kanan'),
                'htmlOptions' => array('class' => 'rata-kanan'),
                'value' => 'number_format($data->harga_jual, 0, ",", ".")'
            ),
            array(
                'name' => 'subTotal',
                'header' => 'Total',
                'value' => '$data->total',
                'headerHtmlOptions' => array('class' => 'rata-kanan'),
                'htmlOptions' => array('class' => 'rata-kanan'),
                'filter' => false
            ),
            [
                'header' => 'Rak=NULL',
                'value' => function($data) {
                    if (is_null($data->barang->rak_id)) {
                        return '<a href="#" class="editable-rak" data-type="select" data-pk="' . $data->barang_id . '" data-url="' . Yii::app()->controller->createUrl('updaterak') . '">NULL</a>';
                    } else {
                        /* Uncomment jika ingin ditampilkan nama rak */
                        //return $data->barang->rak->nama;
                    }
                },
                'type' => 'raw',
                'headerHtmlOptions' => array('class' => 'rata-tengah'),
                'htmlOptions' => array('class' => 'rata-tengah'),
            ],
            // Jika pembelian masih draft tampilkan tombol hapus
            array(
                'class' => 'BButtonColumn',
                'template' => $pembelian->status == 0 ? '{delete}' : '',
                'deleteButtonUrl' => 'Yii::app()->controller->createUrl("pembelian/hapusdetail", array("id"=>$data->primaryKey))',
                'afterDelete' => 'function(link,success,data){ if(success) updateTotal(); }',
            ),
        ),
    ));
    ?>
</div>

<script>
    function enableEditable() {
        $(".editable-qty").editable({
            mode: "inline",
            inputclass: "input-editable-qty",
            success: function (response, newValue) {
                if (response.sukses) {
                    $.fn.yiiGridView.update("pembelian-detail-grid");
                    updateTotal();
                }
            }
        });
        $(".editable-rak").editable({
        mode: "inline",
                //inputclass: "input-editable-qty",
                success: function (response, newValue) {
                    if (response.sukses) {
                        $.fn.yiiGridView.update("pembelian-detail-grid");
                    }
                },
                source: [
<?php
$listRak = CHtml::listData(RakBarang::model()->findAll(array('select' => 'id,nama', 'order' => 'nama')), 'id', 'nama');
$firstRow = TRUE;
foreach ($listRak as $key => $value):
    ?>
    <?php
    if (!$firstRow) {
        echo ',';
    }
    $firstRow = false;
    ?>
                    {value : <?php echo $key; ?>, text : "<?php echo $value; ?>"}
    <?php
endforeach;
?>
                ]
        });
    }
    $(function () {
        enableEditable();
    });
    $(document).ajaxComplete(function () {
        enableEditable();
    });
</script>