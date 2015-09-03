<?php
/* @var $this PengeluaranController */
/* @var $model PengeluaranDetail */
/* @var $form CActiveForm */
?>

<div class="form">

   <?php
   $form = $this->beginWidget('CActiveForm', array(
       'id' => 'pengeluaran-detail-form',
       // Please note: When you enable ajax validation, make sure the corresponding
       // controller action is handling ajax validation correctly.
       // There is a call to performAjaxValidation() commented in generated controller code.
       // See class documentation of CActiveForm for details on this.
       'enableAjaxValidation' => false,
           //'action' => $this->createUrl('tambahdetail')
   ));
   ?>

   <?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>

   <div class="row">
      <!--        <div class="small-12 medium-2 columns">
      <?php // echo $form->labelEx($model, 'item_id'); ?>
      <?php // echo $form->textField($model, 'item_id', array('size' => 10, 'maxlength' => 10)); ?>
      <?php // echo $form->error($model, 'item_id', array('class' => 'error')); ?>
              </div>-->

      <div class="small-12 medium-2 columns">
         <div class="row collapse">
            <?php //echo $form->labelEx($model, 'item_id'); ?>
            <?php echo CHtml::label('Ite<span class="ak">m</span>', 'tombol-pilih-item'); ?>
            <div class="small-9 columns">
               <?php echo $form->textField($model, 'item_id', array('size' => 10, 'maxlength' => 10, 'disabled' => 'disabled')); ?>
               <?php echo $form->hiddenField($model, 'item_id', array('size' => 10, 'maxlength' => 10, 'id' => 'itemId')); ?>
            </div>
            <div class="small-3 columns">
               <a class="tiny bigfont button postfix" id="tombol-pilih-item" accesskey="m"><i class="fa fa-search"></i></a>
            </div>
         </div>
         <?php echo $form->error($model, 'item_id', array('class' => 'error')); ?>
      </div>

      <div class="small-12 medium-2 columns">
         <div class="row collapse">
            <?php //echo $form->labelEx($model, 'item_id'); ?>
            <?php echo CHtml::label('<span class="ak">D</span>okumen', 'tombol-pilih-dokumen'); ?>
            <div class="small-9 columns">
               <?php echo $form->textField($model, 'hutang_piutang_id', array('size' => 10, 'maxlength' => 10, 'disabled' => 'disabled')); ?>
               <?php echo $form->hiddenField($model, 'hutang_piutang_id', array('size' => 10, 'maxlength' => 10, 'id' => 'nomorDokumen')); ?>
            </div>
            <div class="small-3 columns">
               <a class="tiny bigfont button postfix" id="tombol-pilih-dokumen" accesskey="d"><i class="fa fa-search"></i></a>
            </div>
         </div>
         <?php echo $form->error($model, 'hutang_piutang_id', array('class' => 'error')); ?>
      </div>
      <?php
      /*
        <div class="small-12 medium-2 columns">
        <?php echo $form->labelEx($model, 'nomor_dokumen'); ?>
        <?php echo $form->textField($model, 'nomor_dokumen', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'nomor_dokumen', array('class' => 'error')); ?>
        </div>
       */
      ?>
      <div class="small-12 medium-4 columns">
         <?php echo $form->labelEx($model, 'keterangan'); ?>
         <?php echo $form->textField($model, 'keterangan', array('size' => 60, 'maxlength' => 255)); ?>
         <?php echo $form->error($model, 'keterangan', array('class' => 'error')); ?>
      </div>

      <div class="small-12 medium-2 columns">
         <?php echo $form->labelEx($model, 'jumlah'); ?>
         <?php echo $form->textField($model, 'jumlah', array('size' => 18, 'maxlength' => 18, 'autocomplete' => 'off')); ?>
         <?php echo $form->error($model, 'jumlah', array('class' => 'error')); ?>
      </div>
      <!--    </div>
          <div class="row">-->
      <div class="small-12 medium-2 columns">
         <?php echo CHtml::label('&nbsp', 'tombol-tambah'); ?>
         <?php
         echo CHtml::ajaxSubmitButton('Tambah', $this->createUrl('tambahdetail', array('id' => $headerModel->id)), array(
             'success' => "function () {
                                $.fn.yiiGridView.update('pengeluaran-detail-grid');
                                $.fn.yiiGridView.update('hutang-piutang-grid');
                                kosongkanItem();
                                kosongkanDokumen();
                            }"
                 ), array(
             'class' => 'tiny bigfont button',
             'id' => 'tombol-tambah'));
         ?>
         <?php //echo CHtml::label('&nbsp', 'tombol-tambah'); ?>
         <?php //echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', array('id' => 'tombol-tambah', 'class' => 'small-12 tiny bigfont button')); ?>
      </div>
   </div>

   <?php $this->endWidget(); ?>

</div>

<div id="tabel-item" style="display: none">
   <?php $this->renderPartial('_item', array('itemKeuangan' => $itemKeuangan)); ?>
</div>

<div id="tabel-dokumen" style="display: none">
   <?php
   $this->renderPartial('_dokumen', array(
       'hutangPiutang' => $hutangPiutang,
       'listNamaAsalHutangPiutang' => $listNamaAsalHutangPiutang,
       'listNamaTipe' => $listNamaTipe
   ));
   ?>
</div>

<script>
   $("body").on("click", "a.pilih.item", function () {
      var dataurl = $(this).attr('href');
      $.ajax({
         url: dataurl,
         success: isiItem
      });
      return false;
   });

   function isiItem(data) {
      console.log(data);
      $("label[for='tombol-pilih-item']").html('Ite<span class="ak">m</span> ' + data.namaParent);
      $("#itemId").val(data.id);
      $("#PengeluaranDetail_item_id").val(data.nama);
      $("#tabel-item").slideUp(500);
      $("#PengeluaranDetail_keterangan").focus();
      kosongkanDokumen();
   }

   function kosongkanItem() {
      $("label[for='tombol-pilih-item']").html('Ite<span class="ak">m</span> ');
      $("#itemId").val("");
      $("#PengeluaranDetail_item_id").val("");
   }

   $("#tombol-pilih-item").click(function () {
      $("#tabel-item").slideToggle(500);
      $("input[name='ItemKeuangan[nama]']").focus();
   });

   $("#tombol-pilih-dokumen").click(function () {
      $("#tabel-dokumen").slideToggle(500);
      $("input[name='HutangPiutang[namaProfil]']").focus();
   });

   $("body").on("click", "a.pilih.dokumen", function () {
      var dataurl = $(this).attr('href');
      $.ajax({
         url: dataurl,
         success: isiDokumen
      });
      return false;
   });

   function isiDokumen(data) {
      console.log(data);
      //$("label[for='tombol-pilih-dokumen']").html('<span class="ak">D</span>okumen ' + data.nomor);
      $("#nomorDokumen").val(data.id);
      $("#PengeluaranDetail_hutang_piutang_id").val(data.nomor);
      $("#PengeluaranDetail_keterangan").val(data.keterangan);
      $("#PengeluaranDetail_jumlah").val(data.jumlah);

      $("label[for='tombol-pilih-item']").html('Ite<span class="ak">m</span> ' + data.itemParent);
      $("#itemId").val(data.itemId);
      $("#PengeluaranDetail_item_id").val(data.itemNama);

      $("#tabel-dokumen").slideUp(500);
      $("#PengeluaranDetail_keterangan").focus();
      //kosongkanItem();
   }

   function kosongkanDokumen() {
      //$("label[for='tombol-pilih-dokumen']").html('<span class="ak">D</span>okumen');
      $("#nomorDokumen").val("");
      $("#PengeluaranDetail_hutang_piutang_id").val("");
      $("#PengeluaranDetail_keterangan").val("");

      $("#PengeluaranDetail_jumlah").val("");
   }
</script>
