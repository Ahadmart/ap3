<?php
/* @var $this DeviceController */
/* @var $model Device */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'device-form',
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
            <?php echo $form->labelEx($model, 'tipe_id'); ?>
            <?php echo $form->dropDownList($model, 'tipe_id', $model->listTipe()); ?>
            <?php echo $form->error($model, 'tipe_id', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'nama'); ?>
            <?php echo $form->textField($model, 'nama', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($model, 'nama', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'keterangan'); ?>
            <?php echo $form->textField($model, 'keterangan', array('size' => 60, 'maxlength' => 500)); ?>
            <?php echo $form->error($model, 'keterangan', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'address'); ?>
            <?php echo $form->textField($model, 'address', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($model, 'address', array('class' => 'error')); ?>
        </div>
    </div>
    <?php
    /* Tampilan tambahan ketika ubah (setelah tambah) */
    if (!$model->isNewRecord) {
        if ($model->tipe_id == Device::TIPE_POS_CLIENT) {
            ?>
            <div class="row">
                <div class="small-12 columns">
                    <?php echo $form->labelEx($model, 'default_printer_id'); ?>
                    <?php
                    echo $form->dropDownList($model, 'default_printer_id', $model->listPrinter(), array('prompt' => 'Pilih satu..'));
                    ?>
                    <?php echo $form->error($model, 'default_printer_id', array('class' => 'error')); ?>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="row">
                <div class="small-12 columns">
                    <?php echo $form->labelEx($model, 'lf_sebelum'); ?>
                    <?php echo $form->textField($model, 'lf_sebelum'); ?>
                    <?php echo $form->error($model, 'lf_sebelum', array('class' => 'error')); ?>
                </div>
            </div>

            <div class="row">
                <div class="small-12 columns">
                    <?php echo $form->labelEx($model, 'lf_setelah'); ?>
                    <?php echo $form->textField($model, 'lf_setelah'); ?>
                    <?php echo $form->error($model, 'lf_setelah', array('class' => 'error')); ?>
                </div>
            </div>
            <?php
        }
        if ($model->tipe_id == Device::TIPE_LPR) {
            ?>
            <div class="row">
                <div class="small-6 columns">
                    <?php echo $form->labelEx($model, 'paper_autocut'); ?>
                    <?php echo $form->dropDownList($model, 'paper_autocut', [1 => 'Ya'], ['prompt' => 'Tidak']); ?>
                    <?php echo $form->error($model, 'paper_autocut', array('class' => 'error')); ?>
                </div>
                <div class="small-6 columns">
                    <?php echo $form->labelEx($model, 'cashdrawer_kick'); ?>
                    <?php echo $form->dropDownList($model, 'cashdrawer_kick', [1 => 'Ya'], ['prompt' => 'Tidak']); ?>
                    <?php echo $form->error($model, 'cashdrawer_kick', array('class' => 'error')); ?>
                </div>
            </div>
            <?php
        }
    }
    ?>

    <div class="row">
        <div class="small-12 columns">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', array('class' => 'tiny bigfont button')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>