<?php
/* @var $this ReportController */
/* @var $model ReportPembelianForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'report-pembelian-form-_form_pembelian-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
        ));
?>
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>

<?php echo $form->hiddenField($model, 'profilId'); ?>
<div class="row">
    <div class="large-8 columns">
        <div class="row collapse">
            <label>Profil</label>
            <div class="small-8 columns">
                <?php echo CHtml::textField('profil', empty($model->profilId) ? '' : $model->namaProfil, array('size' => 60, 'maxlength' => 500, 'disabled' => 'disabled')); ?>
            </div>
            <div class="small-1 columns">
                <a class="tiny bigfont secondary button postfix" id="tombol-hapusprofil"><i class="fa fa-eraser"></i></a>
            </div>
            <div class="small-3 columns">
                <a class="tiny bigfont button postfix" id="tombol-browse" accesskey="p"><span class="ak">P</span>ilih..</a>
            </div>
        </div>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?php echo $form->labelEx($model, 'dari'); ?>
        <?php echo $form->textField($model, 'dari', array('class' => 'tanggalan')); ?>
        <?php echo $form->error($model, 'dari', array('class' => 'error')); ?>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?php echo $form->labelEx($model, 'sampai'); ?>
        <?php echo $form->textField($model, 'sampai', array('class' => 'tanggalan')); ?>
        <?php echo $form->error($model, 'sampai', array('class' => 'error')); ?>
    </div>
</div>

<div class="row">
    <div class="small-12 columns">
        <?php echo CHtml::submitButton('Submit', array('class' => 'tiny bigfont button right')); ?>
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