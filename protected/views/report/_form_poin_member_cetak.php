<?php
/* @var $this ReportController */
/* @var $model ReporPoinMemberForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'poin-member-cetak-form',
    'action' => $this->createUrl('poinmemberpdf'),
// Please note: When you enable ajax validation, make sure the corresponding
// controller action is handling ajax validation correctly.
// See class documentation of CActiveForm for details on this,
// you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'target' => '_blank',
    )
        ));

echo $form->hiddenField($model, 'tahun');
echo $form->hiddenField($model, 'periodeId');
echo $form->hiddenField($model, 'sortBy');
echo $form->hiddenField($model, 'jumlahDari');
echo $form->hiddenField($model, 'jumlahSampai');
?>
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>
<div class="row">
    <div class="small-12 medium-2 columns">
        <?php echo $form->labelEx($model, 'kertas'); ?>
        <?php echo $form->dropDownList($model, 'kertas', $kertasPdf); ?>
        <?php echo $form->error($model, 'kertas', array('class' => 'error')); ?>
    </div>
    <div class="small-12 medium-2 large-1 end columns">
        <label for="tombol-cetak">&nbsp;</label>
        <?php echo CHtml::submitButton('Cetak', array('name' => 'cetak', 'id' => 'tombol-cetak', 'class' => 'tiny success bigfont button right')); ?>
    </div>
</div>
<?php
$this->endWidget();

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);
?>
<script>
</script>