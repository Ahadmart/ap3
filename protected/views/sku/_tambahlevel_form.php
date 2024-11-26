<?php
/* @var $this SkuController */
/* @var $model SkuLevel */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', [
        'id'                   => 'sku-level-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ]); ?>

    <?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']); ?>
    <?= $form->hiddenField($model, 'sku_id', ['value' => $skuModel->id]) ?>
    <?= $form->hiddenField($model, 'level', ['value' => $levelSekarang]) ?>

    <div class="row">
        <div class="small-12 medium-4 columns">
            <?php echo $form->labelEx($model, 'level'); ?>
            <?php echo $form->textField($model, 'level', ['size' => 10, 'maxlength' => 10, 'disabled' => 'disabled', 'value' => $levelSekarang]); ?>
            <?php echo $form->error($model, 'level', ['class' => 'error']); ?>
        </div>

        <div class="small-12 medium-4 columns">
            <?php echo $form->labelEx($model, 'satuan_id'); ?>
            <?php echo $form->dropDownList($model, 'satuan_id', CHtml::listData(SatuanBarang::model()->findAll(['order' => 'nama']), 'id', 'nama'), [
                'empty' => 'Pilih satu..',
            ]); ?>
            <?php echo $form->error($model, 'satuan_id', ['class' => 'error']); ?>
        </div>

        <div class="small-12 medium-4 columns">
            <div class="row collapse">
                <?php echo $form->labelEx($model, 'rasio_konversi'); ?>
                <div class="small-9 columns">
                    <?php
                    if (empty($satuanTerakhir)) {
                        echo $form->textField($model, 'rasio_konversi', ['accesskey' => 'r', 'disabled' => 'disabled']);
                    } else {
                        echo $form->textField($model, 'rasio_konversi', ['accesskey' => 'r']);
                    }
                    ?>
                </div>
                <div class="small-3 columns">
                    <span class="postfix"><b><span id="satuan-dibawah"><?= $satuanTerakhir ?></span></b></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', ['class' => 'right tiny bigfont button']); ?>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>

</div>

<?= CHtml::link('&#215;', '', ['class' => 'close-reveal-modal', 'aria-label' => 'Close']) ?>