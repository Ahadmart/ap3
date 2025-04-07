<?php
// Bisa Edit Qty jika masih draft
if ($po->status == Po::STATUS_DRAFT) :
    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.poshytip.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery-editable-poshytip.min.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-editable.css');
endif;
?>

<div class="small-12  columns">
    <?php
    $this->widget('BGridView', [
        'id'           => 'po-detail-grid',
        'dataProvider' => $PODetail->search(),
        //'filter' => $PODetail,
        'summaryText'  => '{start}-{end} dari {count}, Total: <span class="label-total">' . $po->total . '</span>',
        'columns'      => [
            'barcode',
            'nama',
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
                'name'              => 'ads',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
            ],
            [
                'name'              => 'stok',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
            ],
            [
                'name'              => 'est_sisa_hari',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
            ],
            [
                'name'              => 'restock_min',
                'value'             => function ($data) {
                    return '<a href="#" class="editable-qty" data-type="text" data-pk="' . $data->id . '" data-url="' . Yii::app()->controller->createUrl('editrestockmin') . '">' .
                        $data->restock_min . '</a>';
                },
                'type'              => 'raw',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
            ],
            [
                'name'              => 'saran_order',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
            ],
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
                'name'              => 'subTotal',
                'header'            => 'Total',
                'value'             => '$data->total',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
                'filter'            => false,
            ],
            [
                'class'    => 'BButtonColumn',
                'template' => '{unset}',
                'buttons'  => [
                    'unset' => [
                        'options'  => ['title' => 'Unset Order'],
                        'label'    => '<i class="fa fa-minus-square"></i>',
                        'imageUrl' => false,
                        'url'      => 'Yii::app()->controller->createUrl("po/unsetorder", array("id"=>$data->primaryKey))',
                        'visible'  => '$data->saran_order > 0',
                        'click'    => "function(){
                                $.fn.yiiGridView.update('po-detail-grid', {
                                    type:'POST',
                                    url:$(this).attr('href'),
                                    success:function(data) {
                                          $.fn.yiiGridView.update('pls-detail-grid');
                                          $.fn.yiiGridView.update('po-detail-grid');
                                          updateTotal();
                                    }
                                })
                                return false;
                              }
                            ",

                    ],
                ],
                'header'   => '<a id="tombol-unorder-semua" class="delete" href="' . $this->createUrl('unordersemua', ['id' => $po->id]) . '"><i class="fa fa-minus"></i> All</a>',

            ],
            // [
            //     'class'               => 'BButtonColumn',
            //     'template'            => $po->status == Po::STATUS_DRAFT ? '{delete}' : '',
            //     'deleteConfirmation'  => false,
            //     'deleteButtonLabel'   => '<i class="fa fa-minus-square"></i>',
            //     'deleteButtonOptions' => ['title' => 'Unorder'],
            //     'deleteButtonUrl'     => 'Yii::app()->controller->createUrl("po/unsetorder", array("id"=>$data->primaryKey))',
            //     'afterDelete'         => 'function(link,success,data){ if(success) updateTotal(); }',
            //     'visible'             => '$data->saran_order > 0',
            //     'header'              => '<a id="tombol-unorder-semua" class="delete" href="' . $this->createUrl('unordersemua', ['id' => $po->id]) . '"><i class="fa fa-minus"></i> All</a>'
            // ],

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
            success: function(response, newValue) {
                if (response.sukses) {
                    $.fn.yiiGridView.update("po-detail-grid");
                    updateTotal();
                }
            }
        });
    }
    $(function() {
        enableEditable();
    });
    $(document).ajaxComplete(function() {
        enableEditable();
    });

    $("body").on("click", "#tombol-unorder-semua", function() {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: function(data) {
                if (data.sukses) {
                    ambilTotal();
                    $.fn.yiiGridView.update("po-detail-grid");
                    $.fn.yiiGridView.update("pls-detail-grid");
                }
            }
        });
        return false;
    });
</script>