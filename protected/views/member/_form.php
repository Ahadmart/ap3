<?php
/* @var $this MemberController */
/* @var $model MemberPeriodePoin */
/* @var $form CActiveForm */
?>
<div class="row">
    <div class="small-12">

        <div class="form">

            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'member-periode-poin-form',
                // Please note: When you enable ajax validation, make sure the corresponding
                // controller action is handling ajax validation correctly.
                // There is a call to performAjaxValidation() commented in generated controller code.
                // See class documentation of CActiveForm for details on this.
                'enableAjaxValidation' => false,
            ));
            ?>

            <?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>

            <div class="row">
                <div class="small-12 medium-4 columns">
                    <?php echo $form->labelEx($model, 'nama'); ?>
                    <?php echo $form->textField($model, 'nama', array('size' => 45, 'maxlength' => 45)); ?>
                    <?php echo $form->error($model, 'nama', array('class' => 'error')); ?>
                </div>
                <div class="small-12 medium-4 columns">
                    <?php echo $form->labelEx($model, 'awal'); ?>
                    <?php echo $form->dropDownList($model, 'awal', $model->listNamaBulan()); ?>
                    <?php echo $form->error($model, 'awal', array('class' => 'error')); ?>
                </div>
                <div class="small-12 medium-4 columns">
                    <?php echo $form->labelEx($model, 'akhir'); ?>
                    <?php echo $form->dropDownList($model, 'akhir', $model->listNamaBulan()); ?>
                    <?php echo $form->error($model, 'akhir', array('class' => 'error')); ?>
                </div>
            </div>

            <div class="row">
                <div class="small-12 columns">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', array('class' => 'tiny bigfont button right')); ?>
                </div>
            </div>

            <?php $this->endWidget(); ?>

        </div>
    </div>
</div>