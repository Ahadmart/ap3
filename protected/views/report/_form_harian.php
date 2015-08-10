<?php
/* @var $this ReportController */
/* @var $model ReportPenjualanForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'report-harian-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
        ));
?>
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>

<div class="row">
   <div class="small-12 medium-4 large-2 columns">
      <?php echo $form->labelEx($model, 'tanggal'); ?>
      <?php echo $form->textField($model, 'tanggal', array('class' => 'tanggalan', 'value' => empty($model->tanggal) ? date('d-m-Y') : $model->tanggal)); ?>
      <?php echo $form->error($model, 'tanggal', array('class' => 'error')); ?>
   </div>
</div>
<div class="row">
   <div class="small-12 medium-4 large-2 columns">
      <?php echo CHtml::submitButton('Submit', array('class' => 'tiny bigfont button right')); ?>
   </div>
</div>

<?php
$this->endWidget();

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/foundation-datepicker.js', CClientScript::POS_HEAD);
?>
<script>
   $(function () {
      $('.tanggalan').fdatepicker({
         format: 'dd-mm-yyyy'
      });
   });
</script>