<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.poshytip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery-editable-poshytip.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-editable.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/responsive-tables.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/responsive-tables.js', CClientScript::POS_HEAD);

$this->widget('BGridView', array(
    'id' => 'penjualan-detail-grid',
    'dataProvider' => $penjualanDetail->search(),
    //'filter' => $penjualanDetail,
    'summaryText' => 'Poin: '.$penjualan->getCurPoin().' | {start}-{end} dari {count}',
    'itemsCssClass' => 'tabel-index responsive',
    'template' => '{items}{summary}{pager}',
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
            'header' => 'Harga',
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan'),
            'value' => function($data) { return rtrim(rtrim(number_format($data->harga_jual + $data->diskon, 2, ',', '.'),'0'),','); }
        ),
        array(
            'name' => 'diskon',
            'header' => 'Diskon',
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan'),
            'value' => function($data) { return rtrim(rtrim(number_format($data->diskon, 2, ',', '.'),'0'),','); }
        ),
        array(
            'name' => 'harga_jual',
            'header' => 'Net',
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan'),
            'value' => function($data) { return rtrim(rtrim(number_format($data->harga_jual, 2, ',', '.'),'0'),','); }
        ),
        array(
            'name' => 'qty',
            'header' => '<span class="ak">Q</span>ty',
            'type' => 'raw',
            'value' => array($this, 'renderQtyLinkEditable'),
            'headerHtmlOptions' => array('style' => 'width:75px;', 'class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan'),
        ),
        array(
            'type' => 'raw',
            'value' => '"<span class=\"info label\">".$data->barang->satuan->nama."</label>"',
            'htmlOptions' => array('style' => 'padding-left:0'),
        ),
        array(
            'name' => 'subTotal',
            'value' => '$data->total',
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan'),
            'filter' => false
        ),
    ),
));
        echo $penjualan->getCurPoin();
?>
<script>

    function enableEditable() {
        $(".editable-qty").editable({
            mode: "inline",
            success: function (response, newValue) {
                if (response.sukses) {
                    $.fn.yiiGridView.update("penjualan-detail-grid");
                    updateTotal();
                }
            }
        });
        $('.editable-qty').on('shown', function (e, editable) {
            setTimeout(function () {
                editable.input.$input.select();
            }, 0);
        });
        $('.editable-qty').on('hidden', function (e, reason) {
            // focus on input barcode
            $("#scan").focus();
        });
//      $(".editable-qty").keyup(function (e) {
//         console.log(e);
//         if (e.keyCode === 13) {
//            
//         }
//      });

    }

    $(document).on('keydown', ".editable-input input", function (event) {
        // console.log(event.which);
        if (event.which === 40) {
            $(this).closest('tr').next().find('.editable').editable('show');
        } else if (event.which === 38) {
            $(this).closest('tr').prev().find('.editable').editable('show');
        }
    });

    $(function () {
        enableEditable();
    });

    $(document).ajaxComplete(function () {
        enableEditable();
    });
</script>