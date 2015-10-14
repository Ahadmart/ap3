<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/vendor/jquery.poshytip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/vendor/jquery-editable-poshytip.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/jquery-editable.css');

$this->widget('BGridView', array(
    'id' => 'penjualan-detail-grid',
    'dataProvider' => $penjualanDetail->search(),
    //'filter' => $penjualanDetail,
    //'summaryText' => false,
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
            'name' => 'qty',
            'header' => '<span class="ak">Q</span>ty',
            'type' => 'raw',
            'value' => array($this, 'renderQtyLinkEditable'),
            'headerHtmlOptions' => array('style' => 'width:75px;', 'class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan'),
        ),
        array(
            'name' => 'harga_jual',
            'header' => 'harga',
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan'),
            'value' => function($data) {return number_format($data->harga_jual, 0, ',', '.');}
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