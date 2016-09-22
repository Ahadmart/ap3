<?php
/* @var $this ReportController */
/* @var $model ReporPoinMemberForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'report-poin-member-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
        //'htmlOptions' => array('target' => '_blank'),
        ));
?>
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>

<div class="row">
    <div class="small-12 medium-2 large-1 columns">
        <?php echo $form->labelEx($model, 'tahun'); ?>
        <?php echo $form->textField($model, 'tahun', array('class' => 'rata-kanan', 'value' => empty($model->tahun) ? date('Y') : $model->tahun)); ?>
        <?php echo $form->error($model, 'tanggal', array('class' => 'error')); ?>
    </div>
    <div class="small-12 medium-4 large-3 columns">
        <?php echo $form->labelEx($model, 'periodeId'); ?>
        <?php echo $form->dropDownList($model, 'periodeId', $listPeriode); ?>
        <?php echo $form->error($model, 'periodeId', array('class' => 'error')); ?>
    </div>
    <div class="small-12 medium-3 columns">
        <?php echo $form->labelEx($model, 'sortBy'); ?>
        <?php echo $form->dropDownList($model, 'sortBy', $listSortBy); ?>
        <?php echo $form->error($model, 'sortBy', array('class' => 'error')); ?>
    </div>

    <div class="small-12 medium-2 large-1 columns">
        <?php echo $form->labelEx($model, 'jumlahDari'); ?>
        <?php echo $form->textField($model, 'jumlahDari', array('class' => 'rata-kanan')); ?>
        <?php echo $form->error($model, 'jumlahDari', array('class' => 'error')); ?>
    </div>
    <div class="small-12 medium-2 large-1 columns">
        <?php echo $form->labelEx($model, 'jumlahSampai'); ?>
        <?php echo $form->textField($model, 'jumlahSampai', array('class' => 'rata-kanan')); ?>
        <?php echo $form->error($model, 'jumlahSampai', array('class' => 'error')); ?>
    </div>
    <div class="small-12 medium-2 large-1 end columns">
        <label for="tombol-submit">&nbsp;</label>
        <?php echo CHtml::submitButton('Submit', array('name' => 'submit', 'id' => 'tombol-submit', 'class' => 'tiny bigfont button right')); ?>
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