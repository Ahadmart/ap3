<?php
/* @var $this StockopnameController */
/* @var $model StockOpname */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'stock-opname-form',
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
            <?php echo $form->labelEx($model, 'keterangan'); ?>
            <?php echo $form->textField($model, 'keterangan', array('size' => 60, 'maxlength' => 500)); ?>
            <?php echo $form->error($model, 'keterangan', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'rak_id'); ?>
            <?php
            echo $form->dropDownList($model, 'rak_id', CHtml::listData(RakBarang::model()->findAll(array('order' => 'nama')), 'id', 'nama'), array(
                'empty' => ''
            ));
            ?>
            <?php echo $form->error($model, 'rak_id', array('class' => 'error')); ?>
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
    $(document).ready(function () {
        $(":submit").click(function () {
            $(this).prop('disabled', true).val("Tambah..").addClass("warning");
            this.form.submit();
            return false;
        });
    });
</script>