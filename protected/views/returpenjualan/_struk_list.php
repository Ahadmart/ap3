<div class="small-12  columns">
   <?php
   $this->widget('BGridView', array(
       'id' => 'penjualan-detail-grid',
       'dataProvider' => $penjualanDetail->search(),
		 'enableSorting' => false,
//       'filter' => $penjualanDetail,
       'columns' => array(
           array(
               'class' => 'BDataColumn',
               'name' => 'nomorPenjualan',
               'value' => '$data->penjualan->nomor',
//               'header' => 'Pen<span class="ak">j</span>ualan',
//               'accesskey' => 'j',
           ),
           array(
               'class' => 'BDataColumn',
               'header' => 'Profil',
               'value' => '$data->penjualan->profil->nama',
           ),
           array(
               'name' => 'qty',
               'headerHtmlOptions' => array('class' => 'rata-kanan'),
               'htmlOptions' => array('class' => 'rata-kanan'),
               'filter' => false
           ),
           array(
               'name' => 'harga_jual',
               'value' => 'number_format($data->harga_jual,0,",",".")',
               'headerHtmlOptions' => array('class' => 'rata-kanan'),
               'htmlOptions' => array('class' => 'rata-kanan'),
               'filter' => false
           ),
            array(
                'class' => 'BButtonColumn',
                'htmlOptions' => array('style' => 'text-align:center'),
                // Pakai template delete untuk pilih :) biar gampang
                'deleteButtonUrl' => '$data->id',
                'deleteButtonImageUrl' => false,
                'deleteButtonLabel' => '<i class="fa fa-check"></i>',
                'deleteButtonOptions' => array('title' => 'Pilih', 'class' => 'pilih struk'),
                'deleteConfirmation' => false,
            ),
//           array(
//               'header' => 'Pilih',
//               'type' => 'raw',
//               'value' => array($this, 'renderRadioButton')
//           )
       ),
   ));
   ?>
</div>
<script>
<?php
/*
 * Memberi class berbeda pada baris yang disorot
 */
?>
   $("body").on("focusin", "a.pilih", function () {
      $(this).parent('td').parent('tr').addClass('pilih');
   });

   $("body").on("focusout", "a.pilih", function () {
      $(this).parent('td').parent('tr').removeClass('pilih');
   });

<?php
/*
 * Jika dipilih, id penjualan detail disimpan, cursor ke scan barcode, update retur-penjualan-detail-grid
 */
?>
   $("body").on("click", "a.pilih.struk", function () {
      //console.log($(this).attr("href"));
      var datakirim = {
         'penjualanDetailId': $(this).attr("href"),
         'qty': $("#qty").val()
      }
      var dataurl = "<?php echo $this->createUrl('tambahdetail', array('id' => $returPenjualan->id)); ?>";
      $.ajax({
         url: dataurl,
         data: datakirim,
         type: "POST",
         dataType: "json",
         success: function (data) {
            if (data.sukses) {
               $("#struk-list").hide(100, function () {
                  $("#retur-penjualan-detail").show(100, function () {
                     $("#scan").val("");
                     $("#qty").val("1");
                     $("#scan").focus();
                     $('#retur-penjualan-detail-grid').yiiGridView('update');
                  });

               });
               updateTotal();
            }
         }
      });
      return false;
   });
</script>