<?php
/* @var $this ReportController */
/* @var $model ReportRekapPenjualanForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'report-rekap-penjualan-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
        ));
?>
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>

<?php echo $form->hiddenField($model, 'profilId'); ?>
<?php echo $form->hiddenField($model, 'userId'); ?>
<div class="row">
    <div class="small-12 medium-4 large-2 columns">
        <?php echo $form->labelEx($model, 'dariBulan'); ?>
        <?php echo $form->textField($model, 'dariBulan', array('class' => 'tanggalan', 'value' => empty($model->dariBulan) ? '01/' . date('Y') : $model->dari, 'data-start-view' => 'year', 'data-min-view' => 'year')); ?>
        <?php echo $form->error($model, 'dariBulan', array('class' => 'error')); ?>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?php echo $form->labelEx($model, 'sampaiBulan'); ?>
        <?php echo $form->textField($model, 'sampaiBulan', array('class' => 'tanggalan', 'value' => empty($model->sampaiBulan) ? date('m/Y') : $model->sampai, 'data-start-view' => 'year', 'data-min-view' => 'year')); ?>
        <?php echo $form->error($model, 'sampaiBulan', array('class' => 'error')); ?>
    </div>
    <div class="medium-6 large-5 columns">
        <div class="row collapse">
            <label>Profil</label>
            <div class="small-9 columns">
                <?php echo CHtml::textField('profil', empty($model->profilId) ? '' : $model->namaProfil, array('size' => 60, 'maxlength' => 500, 'disabled' => 'disabled')); ?>
            </div>
            <div class="small-3 columns">
                <a class="tiny bigfont button postfix" id="tombol-browse-profil" accesskey="p"><span class="ak">P</span>ilih..</a>
            </div>
        </div>
    </div>
    <div class="medium-6 large-3 columns">
        <div class="row collapse">
            <label>User</label>
            <div class="small-9 columns">
                <?php echo CHtml::textField('user', empty($model->userId) ? '' : $model->namaUser, array('size' => 60, 'maxlength' => 500, 'disabled' => 'disabled')); ?>
            </div>
            <div class="small-3 columns">
                <a class="tiny bigfont button postfix" id="tombol-browse-user" accesskey="h">Pili<span class="ak">h</span>..</a>
            </div>
        </div>
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
            format: 'mm/yyyy',
            language: 'id'
        });
    });
</script>