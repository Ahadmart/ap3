<?php

Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/vendor/jquery.poshytip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/vendor/jquery-editable-poshytip.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/jquery-editable.css');
/*
 * 	Menambahkan rutin pada saat edit qty
 * 1. Update Grid Pembelian detail
 * 2. Update Total Pembelian
 */
Yii::app()->clientScript->registerScript('editableQty', ''
        .'$( document ).ajaxComplete(function() {'
        .'$(".editable-qty").editable({'
        . ' mode: "inline",'
        .'	success: function(response, newValue) {'
        .'					if (response.sukses) {'
        .'						$.fn.yiiGridView.update("penjualan-detail-grid");'
        .'						updateTotal();'
        .'					}'
        .'				}  '
        .'});'
        .'});'
        .'$(".editable-qty").editable({'
        . ' mode: "inline",'
        .'	success: function(response, newValue) {'
        .'					if (response.sukses) {'
        .'						$.fn.yiiGridView.update("penjualan-detail-grid");'
        .'						updateTotal();'
        .'					}'
        .'				}  '
        .'});', CClientScript::POS_END);

$this->widget('BGridView', array(
    'id' => 'penjualan-detail-grid',
    'dataProvider' => $penjualanDetail->search(),
    //'filter' => $penjualanDetail,
//       'summaryText' => false,
    'enableSorting' => false,
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
            'type' => 'raw',
            //'value' => 'CHtml::activeTextField($data, "[$row]qty", array("class"=>"rata-kanan edit-input"))',
//            'value' => function($data, $row) {
//               $ak = '';
//               if ($row == 0) {
//                  $ak = 'q';
//               }
//               return CHtml::activeTextField($data, "[$data->id]qty", array("class" => "rata-kanan edit-input", 'accesskey'=> $ak));
//            },
            'value' => array($this, 'renderQtyLinkEditable'),
            'headerHtmlOptions' => array('style' => 'width:75px;', 'class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan'),
        ),
        array(
            'name' => 'harga_jual',
            'header' => 'harga',
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan'),
            'value' => function($data) {
       return number_format($data->harga_jual, 0, ',', '.');
    }
        ),
        array(
            'name' => 'subTotal',
            'value' => '$data->total',
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan'),
            'filter' => false
        ),
        // Jika penjualan masih draft tampilkan tombol hapus
        array(
            'class' => 'BButtonColumn',
            'template' => $penjualan->status == Penjualan::STATUS_DRAFT ? '{delete}' : '',
            'deleteButtonUrl' => 'Yii::app()->controller->createUrl("penjualan/hapusdetail", array("id"=>$data->primaryKey))',
            'afterDelete' => 'function(link,success,data){ if(success) updateTotal();}',
        ),
    ),
));
