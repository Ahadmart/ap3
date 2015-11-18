<?php
/* @var $this AuthitemController */
/* @var $model AuthItem */
/* @var $form CActiveForm */
?>

<div class="form">

   <?php
   $form = $this->beginWidget('CActiveForm', array(
       'id' => 'auth-item-form',
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
         <?php echo $form->labelEx($model, 'type'); ?>
         <?php echo $form->dropDownList($model, 'type', array('0' => 'Operation', '1' => 'Task', '2' => 'Role'), array('autofocus' => 'autofocus')); ?>
         <?php echo $form->error($model, 'type', array('class' => 'error')); ?>
      </div>
   </div>

   <div class="row">
      <div class="small-12 columns">
         <?php echo $form->labelEx($model, 'name'); ?>
         <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 64)); ?>
         <?php echo $form->error($model, 'name', array('class' => 'error')); ?>
      </div>
   </div>


   <div class="row">
      <div class="small-12 columns">
         <?php echo $form->labelEx($model, 'description'); ?>
         <?php echo $form->textArea($model, 'description', array('rows' => 6, 'cols' => 50)); ?>
         <?php echo $form->error($model, 'description', array('class' => 'error')); ?>
      </div>
   </div>

   <div class="row">
      <div class="small-12 columns">
         <?php echo $form->labelEx($model, 'bizrule'); ?>
         <?php echo $form->textArea($model, 'bizrule', array('rows' => 6, 'cols' => 50)); ?>
         <?php echo $form->error($model, 'bizrule', array('class' => 'error')); ?>
      </div>
   </div>

   <div class="row">
      <div class="small-12 columns">
         <?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', array('class' => 'tiny bigfont button')); ?>
      </div>
   </div>

   <?php $this->endWidget(); ?>

</div><!-- form -->