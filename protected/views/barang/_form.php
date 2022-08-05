<?php
/* @var $this BarangController */
/* @var $model Barang */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', [
        'id'                   => 'barang-form',
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
            <?php echo $form->labelEx($model, 'nama'); ?>
            <?php echo $form->textField($model, 'nama', ['size' => 45, 'maxlength' => 45, 'autofocus' => 'autofocus']); ?>
            <?php echo $form->error($model, 'nama', ['class' => 'error']); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 medium-6 columns">
            <?php echo $form->labelEx($model, 'barcode'); ?>
            <?php echo $form->textField($model, 'barcode', ['size' => 30, 'maxlength' => 30]); ?>
            <?php echo $form->error($model, 'barcode', ['class' => 'error']); ?>

            <?php echo $form->labelEx($model, 'kategori_id'); ?>
            <?php
            echo $form->dropDownList($model, 'kategori_id', CHtml::listData(KategoriBarang::model()->findAll(['order' => 'nama']), 'id', 'nama'), [
                'empty' => 'Pilih satu..',
            ]);
            ?>
            <?php echo $form->error($model, 'kategori_id', ['class' => 'error']); ?>

            <?php echo $form->labelEx($model, 'satuan_id'); ?>
            <?php
            echo $form->dropDownList($model, 'satuan_id', CHtml::listData(SatuanBarang::model()->findAll(['order' => 'nama']), 'id', 'nama'), [
                'empty' => 'Pilih satu..',
            ]);
            ?>
            <?php echo $form->error($model, 'satuan_id', ['class' => 'error']); ?>


            <?php echo $form->labelEx($model, 'rak_id'); ?>
            <?php
            echo $form->dropDownList($model, 'rak_id', CHtml::listData(RakBarang::model()->findAll(['order' => 'nama']), 'id', 'nama'), [
                'empty' => 'Pilih satu..',
            ]);
            ?>
            <?php echo $form->error($model, 'rak_id', ['class' => 'error']); ?>

        </div>

        <div class="small-12 medium-6 columns">
            <?php echo $form->labelEx($model, 'restock_min'); ?>
            <?php echo $form->textField($model, 'restock_min', ['size' => 10, 'maxlength' => 10]); ?>
            <?php echo $form->error($model, 'restock_min', ['class' => 'error']); ?>
            <?php echo $form->labelEx($model, 'variant_coefficient'); ?>
            <?php echo $form->textField($model, 'variant_coefficient', ['size' => 10, 'maxlength' => 10]); ?>
            <?php echo $form->error($model, 'variant_coefficient', ['class' => 'error']); ?>
            <?php
            /*
                <?php echo $form->labelEx($model, 'restock_point'); ?>
                <?php echo $form->textField($model, 'restock_point', array('size' => 10, 'maxlength' => 10)); ?>
                <?php echo $form->error($model, 'restock_point', array('class' => 'error')); ?>

                <?php echo $form->labelEx($model, 'restock_level'); ?>
                <?php echo $form->textField($model, 'restock_level', array('size' => 10, 'maxlength' => 10)); ?>
                <?php echo $form->error($model, 'restock_level', array('class' => 'error')); ?>
                */
            ?>
            <?php echo $form->labelEx($model, 'status'); ?>
            <?php echo $form->dropDownList($model, 'status', ['1' => 'Aktif', '0' => 'Non Aktif']); ?>
            <?php echo $form->error($model, 'status', ['class' => 'error']); ?>

        </div>
    </div>
    <?php
    if ($model->isNewRecord) {
    ?>
        <div class="row">
            <div class="small-12 columns">
                <label>Struktur: <span id="nama-struktur"><?= $model->getNamaStruktur() ?></span></label>
            </div>
            <div class="medium-4 columns" id="grid1-container">
                <?php
                $this->renderPartial('_grid1', [
                    'lv1' => $lv1,
                ]); ?>
            </div>
            <div class="medium-4 columns" id="grid2-container">
                <?php
                $this->renderPartial('_grid2', [
                    'lv2' => $strukturDummy,
                ]); ?>
            </div>
            <div class="medium-4 columns" id="grid3-container"">
        <?php
        $this->renderPartial('_grid3', [
            'lv3' => $strukturDummy,
        ]); ?>
    </div>
    <?= $form->hiddenField($model, 'struktur_id') ?>
        </div>
        <?php
    }
        ?>


        <div class=" row">
                <div class="small-12 columns">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', ['class' => 'tiny bigfont success button right']); ?>
                    <?php echo CHtml::link('Kembali', Yii::app()->request->urlReferrer, ['class' => 'tiny bigfont button']); ?>
                </div>
            </div>

            <?php $this->endWidget(); ?>

        </div>
        <script>
            $("#Barang_barcode").keydown(function(e) {
                if (e.keyCode == 13) {
                    //$("#Barang_kategori_id").focus();
                    $(this).next().focus(); //Use whatever selector necessary to focus the 'next' input
                    return false;
                }
            });

            <?php
            if ($model->isNewRecord) {
            ?>

                function lv1Dipilih(id) {
                    var lv1Id = $('#' + id).yiiGridView('getSelection');
                    if (!Array.isArray(lv1Id) || !lv1Id.length) {
                        console.log("1 tidak dipilih");
                        <?php /* render nothing */ ?>
                        $("#grid2-container").load("<?= $this->createUrl('renderstrukturgrid') ?>", {
                            level: 2,
                            parent: 0
                        });
                        $('#Barang_struktur_id').val("");
                    } else {
                        console.log(lv1Id[0] + ":1 dipilih");
                        $("#grid2-container").load("<?= $this->createUrl('renderstrukturgrid') ?>", {
                            level: 2,
                            parent: lv1Id[0]
                        });
                    }
                    $("#grid3-container").load("<?= $this->createUrl('renderstrukturgrid') ?>", {
                        level: 3,
                        parent: 0
                    });
                }

                function lv2Dipilih(id) {
                    var lv2Id = $('#' + id).yiiGridView('getSelection');
                    if (!Array.isArray(lv2Id) || !lv2Id.length) {
                        console.log("2 tidak dipilih");
                        <?php /* render nothing */ ?>
                        $("#grid3-container").load("<?= $this->createUrl('renderstrukturgrid') ?>", {
                            level: 3,
                            parent: 0
                        });
                        $('#Barang_struktur_id').val("");
                    } else {
                        console.log(lv2Id[0] + ":2 dipilih");
                        $("#grid3-container").load("<?= $this->createUrl('renderstrukturgrid') ?>", {
                            level: 3,
                            parent: lv2Id[0]
                        });
                    }
                }

                function lv3Dipilih(id) {
                    var lv3Id = $('#' + id).yiiGridView('getSelection');
                    if (!Array.isArray(lv3Id) || !lv3Id.length) {
                        console.log("3 tidak dipilih");
                        $('#Barang_struktur_id').val("");
                    } else {
                        console.log(lv3Id[0] + ":3 dipilih");
                        $('#Barang_struktur_id').val(lv3Id[0]);
                    }
                }
            <?php
            }
            ?>
        </script>