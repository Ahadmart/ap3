<?php
/* @var $this KasirController */
/* @var $model Kasir */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'kasir-form',
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
            <?php echo $form->labelEx($model, 'user_id'); ?>
            <?php
            echo $form->dropDownList($model, 'user_id', $listKasir, array(
                'prompt' => 'Pilih satu..'
            ));
            ?>
            <?php echo $form->error($model, 'user_id', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'device_id'); ?>
            <?php
            echo $form->dropDownList($model, 'device_id', $listPosClient, array(
                'prompt' => 'Pilih satu..'
            ));
            ?>
            <?php echo $form->error($model, 'device_id', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'saldo_awal'); ?>
            <?php echo $form->textField($model, 'saldo_awal', array('size' => 18, 'maxlength' => 18)); ?>
            <?php echo $form->error($model, 'saldo_awal', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo CHtml::submitButton('Buka', array('class' => 'tiny bigfont button')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>