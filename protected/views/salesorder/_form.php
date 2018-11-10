<?php
/* @var $this PesananpenjualanController */
/* @var $model PesananPenjualan */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm',
            [
        'id'                   => 'sales-order-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ]);
    ?>

    <?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']); ?>


    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'profil_id'); ?>
            <?php
            echo $form->dropDownList($model, 'profil_id', CHtml::listData($customerList, 'id', 'nama'),
                    [
                'empty'     => 'UMUM',
                'autofocus' => 'autofocus'
            ]);
            ?>
            <?php echo $form->error($model, 'profil_id', ['class' => 'error']); ?>
        </div>
    </div>
    <div class="row">
        <div class="small-12 medium-6 columns">
            <input id="checkbox_profil" type="checkbox" name="semua_profil"><label for="checkbox_profil">Tampilkan semua profil</label>
        </div>
    </div>
    <script>
        $("#checkbox_profil").change(function () {
            if (this.checked) {
                console.log('semua');
                $("#PesananPenjualan_profil_id").load("<?php
            echo $this->createUrl('ambilprofil', ['tipe' => $this::PROFIL_ALL]);
            ?>");
            } else {
                console.log('supplier');
                $("#PesananPenjualan_profil_id").load("<?php
            echo $this->createUrl('ambilprofil', ['tipe' => $this::PROFIL_CUSTOMER]);
            ?>");
            }
        });
    </script>

    <div class="row">
        <div class="small-12 columns">
            <?php
            echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', ['class' => 'tiny bigfont button']);
            ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>