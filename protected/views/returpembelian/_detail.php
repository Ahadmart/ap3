<?php

// Bisa Edit Qty jika masih draft
if ($returPembelian->status == 0):
    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.poshytip.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery-editable-poshytip.min.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-editable.css');
    /*
     * 	Menambahkan rutin pada saat edit qty
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

$this->widget('BGridView', array(
    'id' => 'retur-pembelian-detail-grid',
    'dataProvider' => $returPembelianDetail->search(),
    // 'filter' => $returPembelianDetail,
    'enableSorting' => false,
    'columns' => array(
        array(
            'name' => 'barcode',
            'value' => '$data->inventoryBalance->barang->barcode'
        ),
        array(
            'name' => 'namaBarang',
            'value' => '$data->inventoryBalance->barang->nama'
        ),
        array(
            'name' => 'pembelian',
            'value' => '$data->inventoryBalance->pembelianDetail == null ? "":$data->inventoryBalance->pembelianDetail->pembelian->nomor'
        ),
        array(
            'name' => 'tglPembelian',
            'value' => '$data->inventoryBalance->pembelianDetail == null ? "":$data->inventoryBalance->pembelianDetail->pembelian->tanggal'
        ),
        array(
            'name' => 'faktur',
            'value' => '$data->inventoryBalance->pembelianDetail == null ? "":$data->inventoryBalance->pembelianDetail->pembelian->referensi'
        ),
        array(
            'name' => 'tglFaktur',
            'value' => '$data->inventoryBalance->pembelianDetail == null ? "":$data->inventoryBalance->pembelianDetail->pembelian->tanggal_referensi'
        ),
        array(
            'name' => 'hargaBeli',
            'value' => 'number_format($data->inventoryBalance->harga_beli,0,",",".")',
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan'),
        ),
        array(
            'name' => 'qty',
            'value' => function($data) {
                return '<a href="#" class="editable-qty" data-type="text" data-pk="' . $data->id . '" data-url="' . Yii::app()->controller->createUrl('updateqty') . '">' .
                        $data->qty . '</a>';
            },
            'type' => 'raw',
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan'),
        ),
        array(
            'header' => 'Sub Total',
            'value' => '$data->subTotal',
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan'),
        ),
        array(
            'class' => 'BButtonColumn',
            'template' => $returPembelian->status == 0 ? '{delete}' : '',
            'deleteButtonUrl' => 'Yii::app()->controller->createUrl("returpembelian/hapusdetail", array("id"=>$data->primaryKey))',
            'afterDelete' => 'function(link,success,data){ if(success) updateTotal();}',
        ),
    ),
));
