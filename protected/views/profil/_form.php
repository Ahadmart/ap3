<?php
/* @var $this ProfilController */
/* @var $model Profil */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'profil-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>

    <?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'tipe_id'); ?>
            <?php echo $form->dropDownList($model, 'tipe_id', CHtml::listData(TipeProfil::model()->findAll(), 'id', 'nama')); ?>
            <?php echo $form->error($model, 'tipe_id', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'nama'); ?>
            <?php echo $form->textField($model, 'nama', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($model, 'nama', array('class' => 'error')); ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="small-12 medium-6 columns">
            <?php echo $form->labelEx($model, 'nomor'); ?>
            <?php echo $form->textField($model, 'nomor', array('size' => 45, 'maxlength' => 45)); ?>
            <?php echo $form->error($model, 'nomor', array('class' => 'error')); ?>
        </div>
        <div class="small-12 medium-6  columns">
            <?php echo $form->labelEx($model, 'identitas'); ?>
            <?php echo $form->textField($model, 'identitas', array('size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'identitas', array('class' => 'error')); ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'alamat1'); ?>
            <?php echo $form->textField($model, 'alamat1', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($model, 'alamat1', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'alamat2'); ?>
            <?php echo $form->textField($model, 'alamat2', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($model, 'alamat2', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'alamat3'); ?>
            <?php echo $form->textField($model, 'alamat3', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($model, 'alamat3', array('class' => 'error')); ?>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="small-12 medium-4 columns">
            <?php echo $form->labelEx($model, 'telp'); ?>
            <?php echo $form->textField($model, 'telp', array('size' => 20, 'maxlength' => 20)); ?>
            <?php echo $form->error($model, 'telp', array('class' => 'error')); ?>
        </div>
        <div class="small-12 medium-4  columns">
            <?php echo $form->labelEx($model, 'hp'); ?>
            <?php echo $form->textField($model, 'hp', array('size' => 20, 'maxlength' => 20)); ?>
            <?php echo $form->error($model, 'hp', array('class' => 'error')); ?>
        </div>
        <div class="small-12 medium-4  columns">
            <?php echo $form->labelEx($model, 'surel'); ?>
            <?php echo $form->textField($model, 'surel', array('size' => 40, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'surel', array('class' => 'error')); ?>
        </div>
        <div class="small-12 medium-4  columns">
            <?php echo $form->labelEx($model, 'jenis_kelamin'); ?>
            <?php echo $form->dropDownList($model, 'jenis_kelamin', $model->listJenisKelamin(), array('prompt' => 'Pilih satu..')); ?>
            <?php echo $form->error($model, 'jenis_kelamin', array('class' => 'error')); ?>
        </div>

        <div class="small-12 medium-4 columns end">
            <?php echo $form->labelEx($model, 'tanggal_lahir'); ?>
            <?php echo $form->textField($model, 'tanggal_lahir', array('class' => 'tanggalan', 'value' => $model->isNewRecord ? '' : $model->tanggal_lahir)); ?>
            <?php echo $form->error($model, 'tanggal_lahir', array('class' => 'error')); ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'keterangan'); ?>
            <?php echo $form->textField($model, 'keterangan', array('size' => 60, 'maxlength' => 1000)); ?>
            <?php echo $form->error($model, 'keterangan', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', array('class' => 'tiny bigfont button')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>
</div>

<?php
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