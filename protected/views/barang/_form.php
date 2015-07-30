<?php
/* @var $this BarangController */
/* @var $model Barang */
/* @var $form CActiveForm */
?>

<div class="form">

   <?php
   $form = $this->beginWidget('CActiveForm', array(
       'id' => 'barang-form',
       // Please note: When you enable ajax validation, make sure the corresponding
       // controller action is handling ajax validation correctly.
       // There is a call to performAjaxValidation() commented in generated controller code.
       // See class documentation of CActiveForm for details on this.
       'enableAjaxValidation' => false,
   ));
   ?>

   <?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>

   <div class="row">
      <div class="small-12 columns">
         <?php echo $form->labelEx($model, 'nama'); ?>
         <?php echo $form->textField($model, 'nama', array('size' => 45, 'maxlength' => 45, 'autofocus' => 'autofocus')); ?>
         <?php echo $form->error($model, 'nama', array('class' => 'error')); ?>
      </div>
   </div>

   <div class="row">
      <div class="small-12 medium-6 columns">
         <?php echo $form->labelEx($model, 'barcode'); ?>
         <?php echo $form->textField($model, 'barcode', array('size' => 30, 'maxlength' => 30)); ?>
         <?php echo $form->error($model, 'barcode', array('class' => 'error')); ?>

         <?php echo $form->labelEx($model, 'kategori_id'); ?>
         <?php
         echo $form->dropDownList($model, 'kategori_id', CHtml::listData(KategoriBarang::model()->findAll(array('order' => 'nama')), 'id', 'nama'), array(
             'empty' => 'Pilih satu..'
         ));
         ?>
         <?php echo $form->error($model, 'kategori_id', array('class' => 'error')); ?>

         <?php echo $form->labelEx($model, 'satuan_id'); ?>
         <?php
         echo $form->dropDownList($model, 'satuan_id', CHtml::listData(SatuanBarang::model()->findAll(array('order' => 'nama')), 'id', 'nama'), array(
             'empty' => 'Pilih satu..'
         ));
         ?>
         <?php echo $form->error($model, 'satuan_id', array('class' => 'error')); ?>


         <?php echo $form->labelEx($model, 'rak_id'); ?>
         <?php
         echo $form->dropDownList($model, 'rak_id', CHtml::listData(RakBarang::model()->findAll(array('order' => 'nama')), 'id', 'nama'), array(
             'empty' => 'Pilih satu..'
         ));
         ?>
         <?php echo $form->error($model, 'rak_id', array('class' => 'error')); ?>

      </div>

      <div class="small-12 medium-6 columns">
         <?php echo $form->labelEx($model, 'restock_point'); ?>
         <?php echo $form->textField($model, 'restock_point', array('size' => 10, 'maxlength' => 10)); ?>
         <?php echo $form->error($model, 'restock_point', array('class' => 'error')); ?>

         <?php echo $form->labelEx($model, 'restock_level'); ?>
         <?php echo $form->textField($model, 'restock_level', array('size' => 10, 'maxlength' => 10)); ?>
         <?php echo $form->error($model, 'restock_level', array('class' => 'error')); ?>

         <?php echo $form->labelEx($model, 'status'); ?>
         <?php echo $form->dropDownList($model, 'status', array('1' => 'Aktif', '0' => 'Non Aktif')); ?>
         <?php echo $form->error($model, 'status', array('class' => 'error')); ?>

      </div>
   </div>

   <div class="row">
      <div class="small-12 columns">
         <?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', array('class' => 'tiny bigfont success button right')); ?>
         <?php echo CHtml::link('Kembali', Yii::app()->request->urlReferrer, array('class' => 'tiny bigfont button')); ?>
      </div>
   </div>

   <?php $this->endWidget(); ?>

</div>
<script>
   $("#Barang_barcode").keydown(function (e) {
      if (e.keyCode == 13) {
         //$("#Barang_kategori_id").focus();
         $(this).next().focus();  //Use whatever selector necessary to focus the 'next' input
         return false;
      }
   });
</script>