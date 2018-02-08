<?php
    // Bisa Edit Qty jika masih draft
    if ($po->status == Pembelian::STATUS_DRAFT):
        Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.poshytip.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery-editable-poshytip.min.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-editable.css');
    endif;
?>

<div class="small-12  columns">
    <?php
        $this->widget('BGridView', [
            'id'           => 'po-detail-grid',
            'dataProvider' => $PODetail->search($pilihBarang ? null : 't.id'),
            //'filter' => $PODetail,
            'summaryText'  => '{start}-{end} dari {count}, Total: ' . $po->total,
            'columns'      => [
                'barcode',
                'nama',
                [
                    'name'              => 'qty_order',
                    'value'             => function ($data) {
                        return '<a href="#" class="editable-qty" data-type="text" data-pk="' . $data->id . '" data-url="' . Yii::app()->controller->createUrl('updateqty') . '">' .
                        $data->qty_order . '</a>';
                    },
                    'type'              => 'raw',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                ],
                [
                    'name'              => 'harga_beli',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'value'             => 'number_format($data->harga_beli, 0, ",", ".")',
                ],
                [
                    'name'              => 'harga_jual',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'value'             => 'number_format($data->harga_jual, 0, ",", ".")',
                ],
                [
                    'name'              => 'subTotal',
                    'header'            => 'Total',
                    'value'             => '$data->total',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'filter'            => false,
                ],
                // Jika po masih draft tampilkan tombol hapus
                [
                    'class'           => 'BButtonColumn',
                    'template'        => $po->status == Po::STATUS_DRAFT ? '{delete}' : '',
                    'deleteButtonUrl' => 'Yii::app()->controller->createUrl("po/hapusdetail", array("id"=>$data->primaryKey))',
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
            success: function (response, newValue) {
                if (response.sukses) {
                    $.fn.yiiGridView.update("po-detail-grid");
                    updateTotal();
                }
            }
        });
    }
    $(function () {
        enableEditable();
    });
    $(document).ajaxComplete(function () {
        enableEditable();
    });
</script>