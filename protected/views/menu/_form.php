<?php
/* @var $this MenuController */
/* @var $model Menu */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', [
        'id' => 'menu-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ]);
    ?>

    <?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']); ?>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'nama'); ?>
            <?php echo $form->textField($model, 'nama', ['size' => 128, 'maxlength' => 128]); ?>
            <?php echo $form->error($model, 'nama', ['class' => 'error']); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'keterangan'); ?>
            <?php echo $form->textField($model, 'keterangan', ['size' => 512, 'maxlength' => 512]); ?>
            <?php echo $form->error($model, 'keterangan', ['class' => 'error']); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', ['class' => 'tiny bigfont expand button']); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>