<?php
/* @var $this PoController */
/* @var $model PoDetail */

Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.poshytip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery-editable-poshytip.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-editable.css');
?>
<div class="row" style="overflow: auto">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', [
            'id' => 'pls-detail-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => [
                //'barang_id',
                'barcode',
                'nama',
                //'qty',
                // [
                //     'name' => 'qty',
                //     'filter' => false,
                //     'headerHtmlOptions' => ['class' => 'rata-kanan'],
                //     'htmlOptions' => ['class' => 'rata-kanan'],
                // ],
                //'harga_beli',
                [
                    'name' => 'harga_beli',
                    'filter' => false,
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions' => ['class' => 'rata-kanan'],
                    'value' => 'number_format($data->harga_beli,0,",",".")'
                ],
                [
                    'name' => 'harga_jual',
                    'filter' => false,
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions' => ['class' => 'rata-kanan'],
                    'value' => 'number_format($data->harga_jual,0,",",".")'
                ],
                //'harga_beli_terakhir',
                // [
                //     'name' => 'harga_beli_terakhir',
                //     'filter' => false,
                //     'headerHtmlOptions' => ['class' => 'rata-kanan'],
                //     'htmlOptions' => ['class' => 'rata-kanan'],
                //     'value' => 'number_format($data->harga_beli_terakhir,0,",",".")'
                // ],
                //'ads',
                [
                    'name' => 'ads',
                    'filter' => false,
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions' => ['class' => 'rata-kanan'],
                ],
                //'stok',
                [
                    'name' => 'stok',
                    'filter' => false,
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions' => ['class' => 'rata-kanan'],
                ],
                //'est_sisa_hari',
                [
                    'name' => 'est_sisa_hari',
                    'filter' => false,
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions' => ['class' => 'rata-kanan'],
                ],
                //'saran_order',
                [
                    'name' => 'saran_order',
                    'filter' => false,
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions' => ['class' => 'rata-kanan'],
                ],
                //'order',
                [
                    'name' => 'qty_order',
                    'filter' => false,
                    'header' => 'O<span class="ak">r</span>der',
                    'type' => 'raw',
                    'value' => [$this, 'renderOrderEditable'],
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions' => ['class' => 'rata-kanan'],
                ],
                [
                    'header' => 'Set Order',
                    'type' => 'raw',
                    'value' => [$this, 'renderTombolSetOrder'],
                    'headerHtmlOptions' => ['class' => 'rata-tengah'],
                    'htmlOptions' => ['class' => 'rata-tengah'],
                    'header' => '<a id="tombol-order-semua" href="' . $this->createUrl('ordersemua', ['id' => $poModel->id]) . '"><i class="fa fa-plus"></i> All</a>'
                ],
                [
                    'class' => 'BButtonColumn',
                    'deleteButtonUrl' => 'Yii::app()->controller->createUrl("po/hapusplsdetail", array("id"=>$data->primaryKey))',
                ],
            ],
        ]);
        ?>
    </div>
</div>
<script>
    $(function () {
        $(document).on('click', ".tombol-setorder", function () {
            dataUrl = '<?php echo $this->createUrl('setorder', ['id' => $poModel->id]); ?>';
            dataKirim = {detailId: $(this).data('detailid')};
            console.log(dataUrl);

            $.ajax({
                type: 'POST',
                url: dataUrl,
                data: dataKirim,
                success: function (data) {
                    if (data.sukses) {
                        $.fn.yiiGridView.update("pls-detail-grid");
                        $.fn.yiiGridView.update("po-detail-grid");
                        ambilTotal();
                    }
                }
            });
            return false;
        });
    });

    function enableQtyOrderEditable() {
        $(".editable-order").editable({
            mode: "inline",
            inputclass: "input-editable-qty",
            success: function (response, newValue) {
                if (response.sukses) {
                    $.fn.yiiGridView.update("pls-detail-grid");
                    $.fn.yiiGridView.update("po-detail-grid");
                    ambilTotal();
                }
            }
        });
        $('.editable-order').on('shown', function (e, editable) {
            setTimeout(function () {
                editable.input.$input.select();
            }, 0);
<?php /* Menambahkan selector agar width bisa diatur */ ?>
            //$(".input-editable-qty").parent('.editable-input').addClass('input-editable-qty-p');
        });
    }

    $(function () {
        enableQtyOrderEditable();
    });
    $(document).ajaxComplete(function () {
        enableQtyOrderEditable();
    });

    $("body").on("click", "#tombol-order-semua", function () {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: function (data) {
                if (data.sukses) {
                    ambilTotal();
                    $.fn.yiiGridView.update("pls-detail-grid");
                    $.fn.yiiGridView.update("po-detail-grid");
                }
            }
        });
        return false;
    });
</script>