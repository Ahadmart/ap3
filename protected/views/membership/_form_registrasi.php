<?php
/* @var $this MembershipController */
/* @var $model MembershipRegistrationForm */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);
?>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', [
        'id'                   => 'barang-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ]);
    ?>
    <?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']); ?>

    <div class="row">
        <div class="small-12 large-6 columns">
            <?php echo $form->labelEx($model, 'noTelp'); ?>
            <?php echo $form->textField($model, 'noTelp', ['size' => 45, 'maxlength' => 45, 'autofocus' => 'autofocus']); ?>
            <?php echo $form->error($model, 'noTelp', ['class' => 'error']); ?>
        </div>
        <div class="small-12 large-6 columns">
            <?php echo $form->labelEx($model, 'namaLengkap'); ?>
            <?php echo $form->textField($model, 'namaLengkap', ['size' => 45, 'maxlength' => 45]); ?>
            <?php echo $form->error($model, 'namaLengkap', ['class' => 'error']); ?>
        </div>
    </div>
    <div class="row">
        <div class="small-12 large-6 columns">
            <?php echo $form->labelEx($model, 'tanggalLahir'); ?>
            <?php echo $form->textField($model, 'tanggalLahir', ['class' => 'tanggalan']); ?>
            <?php echo $form->error($model, 'tanggalLahir', ['class' => 'error']); ?>
        </div>
        <div class="small-12 large-6 columns">
            <?php echo $form->labelEx($model, 'pekerjaan'); ?>
            <?php echo $form->textField($model, 'pekerjaan', ['maxlength' => 255]); ?>
            <?php echo $form->error($model, 'pekerjaan', ['class' => 'error']); ?>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'alamat'); ?>
            <?php echo $form->textField($model, 'alamat', ['maxlength' => 500]); ?>
            <?php echo $form->error($model, 'alamat', ['class' => 'error']); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo CHtml::submitButton('Submit', ['class' => 'tiny bigfont success button right']); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>
</div>
<script>
    $(function() {
        $('.tanggalan').fdatepicker({
            format: 'dd-mm-yyyy',
            language: 'id'
        });
    });
</script>