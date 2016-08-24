<?php
/* @var $this ReportController */
/* @var $model ReportUmurBarangForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', [
    'id' => 'report-umurbarang-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
        ]);
?>
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>

<div class="row">
    <div class="medium-4 large-3 columns">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'bulan'); ?>
            <?php echo $form->dropDownList($model, 'bulan', $model->opsiUmurBulan(), ['empty' => 'Pilih satu..']); ?>
            <?php echo $form->error($model, 'bulan', array('class' => 'error')); ?>
        </div>   
        <div class="small-12 columns">
            <p>Atau pilih tanggal pembelian:</p>
        </div>     
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'dari'); ?>
            <?php echo $form->textField($model, 'dari', array('class' => 'tanggalan', 'value' => empty($model->dari) ? '' : $model->dari)); ?>
            <?php echo $form->error($model, 'dari', array('class' => 'error')); ?>
        </div>
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'sampai'); ?>
            <?php echo $form->textField($model, 'sampai', array('class' => 'tanggalan', 'value' => empty($model->sampai) ? '' : $model->sampai)); ?>
            <?php echo $form->error($model, 'sampai', array('class' => 'error')); ?>
        </div>
    </div>
    <div class="medium-4 large-3 end columns">
         <div class="small-12 columns">
                 <?php echo $form->labelEx($model, 'kategoriId'); ?>
                 <?php echo $form->dropDownList($model, 'kategoriId', $model->filterKategori()); ?>
                 <?php echo $form->error($model, 'kategoriId', array('class' => 'error')); ?>
        </div>
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'limit'); ?>
            <?php echo $form->textField($model, 'limit'); ?>
            <?php echo $form->error($model, 'limit', array('class' => 'error')); ?>
        </div>
    </div>
    <div class="medium-4 large-3 end columns">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'sortBy0'); ?>
            <?php echo $form->dropDownList($model, 'sortBy0', $model->listSortBy()); ?>
            <?php echo $form->error($model, 'sortBy0', array('class' => 'error')); ?>
        </div>
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'sortBy1'); ?>
            <?php echo $form->dropDownList($model, 'sortBy1', $model->listSortBy()); ?>
            <?php echo $form->error($model, 'sortBy1', array('class' => 'error')); ?>
        </div>
    <div class="small-12 columns">
        <?php echo CHtml::submitButton('Submit', array('class' => 'tiny bigfont button right')); ?>
    </div>
    </div>
</div>


<?php
$this->endWidget();

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
?>
<script>
    $(function () {
        $('.tanggalan').fdatepicker({
            format: 'dd-mm-yyyy'
        });
    });
</script>