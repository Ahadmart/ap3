<div id="pilih-barang" class="medium-6 large-5 columns">
   <div class="panel">
      <h5>Pilih Barang:</h5>
      <?php echo CHtml::label('<span class="ak">1</span> Barcode', 'barcode'); ?>
      <div class="row collapse">
         <div class="medium-10 columns">
            <?php echo CHtml::dropDownList('barcode', '', $barangBarcode, array('accesskey' => '1', 'id' => 'barcode-pilih')); ?>
         </div>
         <div class="medium-2 columns">
            <a href="#" id="pilih-barcode" class="button postfix tombol-pilih" accesskey="2"><span class="ak">2</span> Pilih</a>
         </div>
      </div>
      <?php echo CHtml::label('<span class="ak">3</span> Nama', 'nama'); ?>
      <div class="row collapse">
         <div class="medium-10 columns">
            <?php echo CHtml::dropDownList('nama', '', $barangNama, array('accesskey' => '3', 'id' => 'nama-pilih')); ?>
         </div>
         <div class="medium-2 columns">
            <a href="#" id="pilih-nama" class="button postfix tombol-pilih" accesskey="4" ><span class="ak">4</span> Pilih</a>
         </div>
      </div>
      <!--<div class="row">-->
      <?php
//			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
//				 'name' => 'barangSearch',
//				 'sourceUrl' => $this->createUrl('caribarang', array('profilId' => $model->profil_id)),
//				 // additional javascript options for the autocomplete plugin
//				 'options' => array(
//					  'minLength' => '2',
//					  'select' => 'function (event, ui) {
//										  console.log(ui.item ?
//													 "Nama: " + ui.item.value + "; Barcode " + ui.item.id :
//													 "Nothing selected, input was " + this.value);
//										  if (ui.item) {
//												$("#barcode").val(ui.item.id);
//										  }
//									 }'
//				 ),
//				 'htmlOptions' => array(
//				 //'style' => 'height:20px;',
//				 ),
//			));
      ?>
      <!--</div>-->
   </div>
</div>
<script>
   $("#barcode-pilih").keyup(function (e) {
      if (e.keyCode === 13) {
         $("#pilih-barcode").click();
      }
   });

   $("#nama-pilih").keyup(function (e) {
      if (e.keyCode === 13) {
         $("#pilih-nama").click();
      }
   });

   $(".tombol-pilih").click(function () {
      var barangId = $(this).parent('div').parent('div').find('select').val();
      var datakirim = {
         'barangId': barangId
      };

      $.fn.yiiGridView.update('inventory-balance-grid', {
         type: 'POST',
         data: datakirim,
         success: updateInfo(barangId)
      })
   });

   function updateInfo(barangId) {
      $("#barang-info").load("<?php echo $this->createUrl('getbaranginfo', array('id' => '')) ?>" + barangId);
      $("#retur-qty").focus();
   }

   function clearInfo() {
      $("#barang-info").html("");
      $("#retur-qty").val("");
   }
</script>

<form method="POST">
   <div id="input-ret-inv-balance" class="medium-6 large-7 columns">
      <div class="panel">
         <div class="row small-collapse">
            <div class="small-12 columns">
               <h4 id="barang-info"></h4>
               <?php
               $this->renderPartial('_inventory_balance', array(
                   'inventoryBalance' => $inventoryBalance,
                   'model' => $model,
               ))
               ?>
            </div>
         </div>
         <div class="row small-collapse">
            <div class="small-12 medium-6 large-4 columns">
               <label for="retur-qty">Qty</label>
               <div class="row collapse">
                  <div class="medium-8 columns">
                     <?php echo CHtml::textField('retur-qty', '', array('id' => 'retur-qty')); ?>
                  </div>
                  <div class="medium-4 columns">
                     <?php
                     echo CHtml::ajaxSubmitButton('Tambah', $this->createUrl('pilihinv', array('id' => $model->id)), array(
                         'success' => "function () {
										  clearInfo();
                                $.fn.yiiGridView.update('inventory-balance-grid');
                                $.fn.yiiGridView.update('retur-pembelian-detail-grid');
                            }"), array(
                         'class' => 'button postfix',
                         'id' => 'tombol-tambah'));
                     ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</form>

