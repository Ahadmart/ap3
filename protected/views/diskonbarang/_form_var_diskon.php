<?php
/* @var $this DiskonBarangVarianDetailController */
/* @var $model DiskonBarangVarianDetail */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'barang-diskon-varian-detail-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    )); ?>

    <?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>
    <?php echo $form->hiddenField($model, 'tipe', ['value' => DiskonBarangVarianDetail::TIPE_BARANG_DISKON]); ?>
    <?php
    /*
    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'barang_diskon_id'); ?>
            <?php echo $form->textField($model, 'barang_diskon_id', array('size' => 10, 'maxlength' => 10)); ?>
            <?php echo $form->error($model, 'barang_diskon_id', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'tipe'); ?>
            <?php echo $form->textField($model, 'tipe'); ?>
            <?php echo $form->error($model, 'tipe', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'barang_id'); ?>
            <?php echo $form->textField($model, 'barang_id', array('size' => 10, 'maxlength' => 10)); ?>
            <?php echo $form->error($model, 'barang_id', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'nominal'); ?>
            <?php echo $form->textField($model, 'nominal', array('size' => 18, 'maxlength' => 18)); ?>
            <?php echo $form->error($model, 'nominal', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'persen'); ?>
            <?php echo $form->textField($model, 'persen'); ?>
            <?php echo $form->error($model, 'persen', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'qty'); ?>
            <?php echo $form->textField($model, 'qty', array('size' => 10, 'maxlength' => 10)); ?>
            <?php echo $form->error($model, 'qty', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'qty_min'); ?>
            <?php echo $form->textField($model, 'qty_min', array('size' => 10, 'maxlength' => 10)); ?>
            <?php echo $form->error($model, 'qty_min', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'qty_max'); ?>
            <?php echo $form->textField($model, 'qty_max', array('size' => 10, 'maxlength' => 10)); ?>
            <?php echo $form->error($model, 'qty_max', array('class' => 'error')); ?>
        </div>
    </div>
*/
    ?>
    <div class="row">
        <div class="small-12 columns">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah Varian Barang' : 'Simpan', array('class' => 'tiny bigfont button')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>

<?= CHtml::link('&#215;', '', ['class' => 'close-reveal-modal', 'aria-label' => 'Close']) ?>