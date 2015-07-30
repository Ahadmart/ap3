<?php
/* @var $this CustomerController */
/* @var $model Profil */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'profil-form',
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
            <?php echo $form->textField($model, 'nama', array('size' => 60, 'maxlength' => 100, 'autofocus' => 'autofocus')); ?>
            <?php echo $form->error($model, 'nama', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'alamat1'); ?>
            <?php echo $form->textField($model, 'alamat1', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($model, 'alamat1', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'alamat2'); ?>
            <?php echo $form->textField($model, 'alamat2', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($model, 'alamat2', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'alamat3'); ?>
            <?php echo $form->textField($model, 'alamat3', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($model, 'alamat3', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'telp'); ?>
            <?php echo $form->textField($model, 'telp', array('size' => 20, 'maxlength' => 20)); ?>
            <?php echo $form->error($model, 'telp', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'keterangan'); ?>
            <?php echo $form->textField($model, 'keterangan', array('size' => 60, 'maxlength' => 1000)); ?>
            <?php echo $form->error($model, 'keterangan', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', array('class' => 'tiny bigfont button')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>