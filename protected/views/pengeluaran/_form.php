<?php
/* @var $this PengeluaranController */
/* @var $model Pengeluaran */
/* @var $form CActiveForm */
?>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'pengeluaran-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>

    <?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>
    <?php echo $form->hiddenField($model, 'profil_id'); ?>
    <div class="row">
        <div class="large-5 columns">

            <div class="row collapse">
                <label>Kepada</label>
                <div class="small-9 columns">
                    <?php echo CHtml::textField('profil', isset($model->profil_id) ? $model->profil->nama : '', array('size' => 60, 'maxlength' => 500, 'disabled' => 'disabled')); ?>
                </div>
                <div class="small-3 columns">
                    <a class="tiny bigfont button postfix" id="tombol-browse" accesskey="p"><span class="ak">P</span>ilih..</a>
                </div>
            </div>

            <?php echo $form->labelEx($model, 'keterangan'); ?>
            <?php echo $form->textField($model, 'keterangan', array('size' => 60, 'maxlength' => 500, 'autofocus' => 'autofocus')); ?>
            <?php echo $form->error($model, 'keterangan', array('class' => 'error')); ?>
        </div>
        <div class="small-12 medium-4 large-2 columns">
            <?php echo $form->labelEx($model, 'tanggal'); ?>
            <?php echo $form->textField($model, 'tanggal', array('class' => 'tanggalan', 'value' => $model->isNewRecord ? date('d-m-Y') : $model->tanggal)); ?>
            <?php echo $form->error($model, 'tanggal', array('class' => 'error')); ?>

            <?php echo $form->labelEx($model, 'kas_bank_id'); ?>
            <?php
            echo $form->dropDownList($model, 'kas_bank_id', CHtml::listData(KasBank::model()->findAll(array('order' => 'nama')), 'id', 'nama'), array(
                'empty' => 'Pilih satu..',
            ));
            ?>
            <?php echo $form->error($model, 'kas_bank_id', array('class' => 'error')); ?>
        </div>
        <div class="small-12 medium-4 large-3 columns">
            <?php echo $form->labelEx($model, 'kategori_id'); ?>
            <?php
            echo $form->dropDownList($model, 'kategori_id', CHtml::listData(KategoriPengeluaran::model()->findAll(array('order' => 'nama')), 'id', 'nama'), array(
                'empty' => 'Pilih satu..',
            ));
            ?>
            <?php echo $form->error($model, 'kategori_id', array('class' => 'error')); ?>

            <?php echo $form->labelEx($model, 'jenis_transaksi_id'); ?>
            <?php
            echo $form->dropDownList($model, 'jenis_transaksi_id', CHtml::listData(JenisTransaksi::model()->findAll(array('order' => 'nama')), 'id', 'nama'), array(
                'empty' => 'Pilih satu..',
            ));
            ?>
            <?php echo $form->error($model, 'jenis_transaksi_id', array('class' => 'error')); ?>
        </div>
        <div class="small-12 medium-4 large-2 columns">
            <?php echo $form->labelEx($model, 'referensi'); ?>
            <?php echo $form->textField($model, 'referensi', array('size' => 45, 'maxlength' => 45)); ?>
            <?php echo $form->error($model, 'referensi', array('class' => 'error')); ?>

            <?php echo $form->labelEx($model, 'tanggal_referensi'); ?>
            <?php echo $form->textField($model, 'tanggal_referensi', array('class' => 'tanggalan', 'value' => $model->isNewRecord ? '' : $model->tanggal_referensi)); ?>
            <?php echo $form->error($model, 'tanggal_referensi', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', array('class' => 'tiny bigfont button right')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>

<div class="row">
    <div class="small-12 columns">
        <div id="tabel-profil" style="display: none">
            <?php $this->renderPartial('_profil', array('profil' => $profil)); ?>
        </div>
    </div>
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

    $("#tombol-browse").click(function () {
        $("#tabel-profil").slideToggle(500);
        $("input[name='Profil[nama]']").focus();
    });

    $("body").on("click", "a.pilih.profil", function () {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: isiProfil
        });
        return false;
    });

    function isiProfil(data) {
        console.log(data);
        $("#profil").val(data.nama);
        $("#tabel-profil").slideUp(500);
        $("#Pengeluaran_profil_id").val(data.id);
        $("#Pengeluaran_keterangan").focus();
    }

    $("body").on("focusin", "a.pilih", function () {
        $(this).parent('td').parent('tr').addClass('pilih');
    });

    $("body").on("focusout", "a.pilih", function () {
        $(this).parent('td').parent('tr').removeClass('pilih');
    });
</script>