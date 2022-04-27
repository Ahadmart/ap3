<?php

// Bisa Edit Qty jika masih draft
if ($returPembelian->status == 0) :
    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.poshytip.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery-editable-poshytip.min.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-editable.css');
    /*
     *     Menambahkan rutin pada saat edit qty
     * 1. Update Grid Retur Pembelian detail
     * 2. Update Total Retur Pembelian
     */
    Yii::app()->clientScript->registerScript('editableQty', ''
        . '$( document ).ajaxComplete(function() {'
        . '$(".editable-qty").editable({'
        . '	success: function(respon, newValue) {'
        . '					if (respon.sukses) {'
        . '						$.fn.yiiGridView.update("retur-pembelian-detail-grid");'
        . '						updateTotal();'
        . '					}'
        . '				}  '
        . '});'
        . '});'
        . '$(".editable-qty").editable({'
        . '	success: function(respon, newValue) {'
        . '					if (respon.sukses) {'
        . '						$.fn.yiiGridView.update("retur-pembelian-detail-grid");'
        . '						updateTotal();'
        . '					}'
        . '				}  '
        . '});', CClientScript::POS_END);
endif;
?>

<?php

$this->widget('BGridView', [
    'id'            => 'retur-pembelian-detail-grid',
    'dataProvider'  => $returPembelianDetail->search(),
    // 'filter' => $returPembelianDetail,
    'enableSorting' => false,
    'columns'       => [
        [
            'name'              => 'barang',
            'type'              => 'raw',
            'value'             => [$this, 'renderBarang'],
            'headerHtmlOptions' => ['class' => 'small-only'],
            'htmlOptions'       => ['class' => 'small-only'],
        ],
        [
            'name'  => 'barcode',
            'value' => '$data->inventoryBalance->barang->barcode',
            'headerHtmlOptions' => ['class' => 'show-for-medium-up'],
            'htmlOptions'       => ['class' => 'show-for-medium-up'],
        ],
        [
            'name'  => 'namaBarang',
            'value' => '$data->inventoryBalance->barang->nama',
            'headerHtmlOptions' => ['class' => 'show-for-medium-up'],
            'htmlOptions'       => ['class' => 'show-for-medium-up'],
        ],
        [
            'name'  => 'pembelian',
            'value' => '$data->inventoryBalance->pembelianDetail == null ? "":$data->inventoryBalance->pembelianDetail->pembelian->nomor',
            'headerHtmlOptions' => ['class' => 'show-for-medium-up'],
            'htmlOptions'       => ['class' => 'show-for-medium-up'],
        ],
        [
            'name'  => 'tglPembelian',
            'value' => '$data->inventoryBalance->pembelianDetail == null ? "":$data->inventoryBalance->pembelianDetail->pembelian->tanggal',
            'headerHtmlOptions' => ['class' => 'show-for-medium-up'],
            'htmlOptions'       => ['class' => 'show-for-medium-up'],
        ],
        [
            'name'   => 'faktur',
            'value'  => '$data->inventoryBalance->pembelianDetail == null ? "":$data->inventoryBalance->pembelianDetail->pembelian->referensi',
            'header' => 'Ref',
            'headerHtmlOptions' => ['class' => 'show-for-medium-up'],
            'htmlOptions'       => ['class' => 'show-for-medium-up'],
        ],
        [
            'name'   => 'tglFaktur',
            'value'  => '$data->inventoryBalance->pembelianDetail == null ? "":$data->inventoryBalance->pembelianDetail->pembelian->tanggal_referensi',
            'header' => 'Tgl Ref',
            'headerHtmlOptions' => ['class' => 'show-for-medium-up'],
            'htmlOptions'       => ['class' => 'show-for-medium-up'],
        ],
        [
            'name'              => 'hargaBeli',
            'value'             => 'number_format($data->inventoryBalance->harga_beli,0,",",".")',
            'headerHtmlOptions' => ['class' => 'rata-kanan show-for-medium-up'],
            'htmlOptions'       => ['class' => 'rata-kanan show-for-medium-up'],
        ],
        [
            'header'            => 'Stok',
            'value'             => '$data->inventoryBalance->barang->stok',
            'headerHtmlOptions' => ['class' => 'rata-kanan show-for-medium-up'],
            'htmlOptions'       => ['class' => 'rata-kanan show-for-medium-up'],
        ],
        [
            'name'              => 'qty',
            'value'             => function ($data) {
                return '<a href="#" class="editable-qty" data-type="text" data-pk="' . $data->id . '" data-url="' . Yii::app()->controller->createUrl('updateqty') . '">' .
                    $data->qty . '</a>';
            },
            'type'              => 'raw',
            'headerHtmlOptions' => ['class' => 'rata-kanan show-for-medium-up'],
            'htmlOptions'       => ['class' => 'rata-kanan show-for-medium-up'],
        ],
        [
            'header'            => 'Sub Total',
            'value'             => '$data->subTotal',
            'headerHtmlOptions' => ['class' => 'rata-kanan show-for-medium-up'],
            'htmlOptions'       => ['class' => 'rata-kanan show-for-medium-up'],
        ],
        [
            'class'           => 'BButtonColumn',
            'template'        => $returPembelian->status == 0 ? '{delete}' : '',
            'deleteButtonUrl' => 'Yii::app()->controller->createUrl("returpembelian/hapusdetail", array("id"=>$data->primaryKey))',
            'afterDelete'     => 'function(link,success,data){ if(success) updateTotal();}',
        ],
    ],
]);
