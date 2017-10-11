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
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']); ?>

<?php echo $form->hiddenField($model, 'profilId'); ?>
<?php echo $form->hiddenField($model, 'userId'); ?>
<div class="row">
    <div class="small-12 medium-4 large-2 columns">
        <?php echo $form->labelEx($model, 'dari'); ?>
        <?php echo $form->textField($model, 'dari', ['class' => 'tanggal-waktu', 'value' => empty($model->dari) ? date('d-m-Y') . ' 00:00' : $model->dari]); ?>
        <?php echo $form->error($model, 'dari', ['class' => 'error']); ?>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?php echo $form->labelEx($model, 'sampai'); ?>
        <?php echo $form->textField($model, 'sampai', ['class' => 'tanggal-waktu', 'value' => empty($model->sampai) ? date('d-m-Y') . ' 23:59' : $model->sampai]); ?>
        <?php echo $form->error($model, 'sampai', ['class' => 'error']); ?>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?php echo $form->labelEx($model, 'tipeDiskonId'); ?>
        <?php echo $form->dropDownList($model, 'tipeDiskonId', DiskonBarang::listNamaTipe(),['prompt'=>'[SEMUA]']); ?>
        <?php echo $form->error($model, 'tipeDiskonId', ['class' => 'error']); ?>
    </div>
    <div class="medium-6 large-3 columns">
        <div class="row collapse">
            <label>Profil</label>
            <div class="small-9 columns">
                <?php echo CHtml::textField('profil', empty($model->profilId) ? '' : $model->namaProfil, ['size' => 60, 'maxlength' => 500, 'disabled' => 'disabled']); ?>
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
                <?php echo CHtml::textField('user', empty($model->userId) ? '' : $model->namaUser, ['size' => 60, 'maxlength' => 500, 'disabled' => 'disabled']); ?>
            </div>
            <div class="small-3 columns">
                <a class="tiny bigfont button postfix" id="tombol-browse-user" accesskey="h">Pili<span class="ak">h</span>..</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="small-12 columns">
        <?php echo CHtml::submitButton('Submit', ['class' => 'tiny bigfont button right']); ?>
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
        $('.tanggal-waktu').fdatepicker({
            format: 'dd-mm-yyyy  hh:ii',
            disableDblClickSelection: true,
            language: 'id',
            pickTime: true
        });
    });
</script>