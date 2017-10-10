<?php
/* @var $this ReportController */
/* @var $model ReportPenjualanForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', [
    'id' => 'report-penjualan-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
        ]);
?>
<?= $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']); ?>

<div class="row">
    <div class="small-12 medium-4 large-2 columns">
        <?= $form->labelEx($model, 'dari'); ?>
        <?= $form->textField($model, 'dari', ['class' => 'tanggalan', 'value' => empty($model->dari) ? date('d-m-Y') : $model->dari]); ?>
        <?= $form->error($model, 'dari', ['class' => 'error']); ?>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?= $form->labelEx($model, 'sampai'); ?>
        <?= $form->textField($model, 'sampai', ['class' => 'tanggalan', 'value' => empty($model->sampai) ? date('d-m-Y') : $model->sampai]); ?>
        <?= $form->error($model, 'sampai', ['class' => 'error']); ?>
    </div>
    <div class="small-12 medium-4 large-2 end columns">
        <?= $form->labelEx($model, 'tipeDiskonId'); ?>
        <?= $form->dropDownList($model, 'tipeDiskonId', DiskonBarang::listNamaTipe(), ['prompt' => '[SEMUA]']); ?>
        <?= $form->error($model, 'tipeDiskonId', ['class' => 'error']); ?>
    </div>    
</div>

<div class="row">
    <div class="small-12 columns">
        <?= CHtml::submitButton('Submit', ['class' => 'tiny bigfont button right']); ?>
    </div>
</div>

<?php
$this->endWidget();

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);
?>
<script>
    $(function () {
        $('.tanggalan').fdatepicker({
            format: 'dd-mm-yyyy',
            language: 'id'
        });
    });
</script>