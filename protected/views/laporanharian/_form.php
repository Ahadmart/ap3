<?php
/* @var $this LaporanharianController */
/* @var $model LaporanHarian */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'laporan-harian-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>

    <?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>

    <div class="row">
        <div class="small-12 medium-6 columns">
            <?php echo $form->labelEx($model, 'tanggal'); ?>
            <?php echo $form->textField($model, 'tanggal', array('class' => 'tanggalan')); ?>
            <?php echo $form->error($model, 'tanggal', array('class' => 'error')); ?>
        </div>
        <div class="small-12 medium-6 columns">
            <?php echo $form->labelEx($model, 'saldo_akhir'); ?>
            <?php echo $form->textField($model, 'saldo_akhir', array('size' => 18, 'maxlength' => 18)); ?>
            <?php echo $form->error($model, 'saldo_akhir', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'keterangan'); ?>
            <?php echo $form->textArea($model, 'keterangan', array('size' => 60, 'maxlength' => 5000)); ?>
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

<script>
    $(function () {
        $('.tanggalan').fdatepicker({
            format: 'dd-mm-yyyy',
            language: 'id'
        });
    });

    $("#LaporanHarian_tanggal").change(function () {
        var url = "<?php echo $this->createUrl('cari', array('tanggal' => '')); ?>" + $(this).val();
        window.location = url;
    });
</script>