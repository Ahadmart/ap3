<?php
/* @var $this JenistransaksiController */
/* @var $model JenisTransaksi */
/* @var $form CActiveForm */
?>

<div class="form">

   <?php
   $form = $this->beginWidget('CActiveForm', array(
       'id' => 'jenis-transaksi-form',
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
      <div class="small-12 columns">
         <?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', array('class' => 'tiny bigfont button')); ?>
      </div>
   </div>

   <?php $this->endWidget(); ?>

</div>