<?php
/* @var $this ReportController */
/* @var $model ReportHutangPiutangForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'report-rekaphutangpiutang-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
        ));
?>
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>

<div class="row">
    <div class="small-12 columns">
        <?php echo CHtml::submitButton('Tampilkan', array('name'=>'tombol_submit', 'class' => 'tiny bigfont button')); ?>
    </div>
</div>

<?php
$this->endWidget();
