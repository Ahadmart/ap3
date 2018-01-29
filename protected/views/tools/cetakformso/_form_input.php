<?php
    /* @var $this CetaklabelrakController */
    /* @var $model CetakLabelRakForm */
    /* @var $form CActiveForm */
?>
    <?php
    $form = $this->beginWidget('CActiveForm', [
        'id'                   => 'cetak-label-rak-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // See class documentation of CActiveForm for details on this,
        // you need to use the performAjaxValidation()-method described there.
        'enableAjaxValidation' => false,
        'htmlOptions'          => ['target' => '_blank'],
    ]);
?>
        <?php
    echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']);

    echo $form->hiddenField($model, 'rakId');
    //echo $form->hiddenField($model, 'kategoriId');
 ?>
            <div class="row">
                <div class="small-12 large-6 columns">
                    <div class="row collapse">
                        <label>Rak</label>
                        <div class="small-9 columns">
                            <?php echo CHtml::textField('rak', empty($model->rakId) ? '' : $model->namaRak, ['size' => 60, 'maxlength' => 500, 'disabled' => 'disabled']); ?>
                        </div>
                        <div class="small-3 columns">
                            <a class="tiny bigfont button postfix" id="tombol-browse-rak" accesskey="h">Pili<span class="ak">h</span>..</a>
                        </div>
                    </div>
                    <?php echo $form->error($model, 'rakId', ['class' => 'error']); ?>
                </div>
                <div class="small-12 large-6 columns">
                    <?php
                        echo $form->labelEx($model, 'kategoriId');
                        echo $form->dropDownList($model, 'kategoriId', empty($model->rakId) ? [] : CetakStockOpnameForm::getKategoriRak($model->rakId));
                        echo $form->error($model, 'kategoriId', ['class' => 'error']);
                    ?>
                </div>
                <div class="small-12 large-6 columns">
                    <?php
                        echo $form->labelEx($model, 'filterNama');
                        echo $form->textField($model, 'filterNama');
                        echo $form->error($model, 'filterNama', ['class' => 'error']);
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="small-12 large-6 columns">
                    <?php
                        echo $form->labelEx($model, 'sortBy');
                        echo $form->dropDownList($model, 'sortBy', CetakStockOpnameForm::listOfSortBy());
                        echo $form->error($model, 'sortBy', ['class' => 'error']);
                    ?>
                </div>
                <div class="small-12 large-6 columns">
                    <?php
                        echo $form->labelEx($model, 'kertas');
                        echo $form->dropDownList($model, 'kertas', CetakStockOpnameForm::listKertas());
                        echo $form->error($model, 'kertas', ['class' => 'error']);
                    ?>
                </div>
                <div class="small-12 large-6 columns">
                    <label>Barang yang Aktif saja</label>
                    <div class="small switch">
                        <?php
                            echo $form->checkBox($model, 'aktifSaja');
                            echo $form->labelEx($model, 'aktifSaja');
                        ?>
                    </div>
                </div>
                <div class="small-12 large-6 columns">
                    <label>Kecuali Stok 0 (Nol)</label>
                    <div class="small switch">
                        <?php
                            echo $form->checkBox($model, 'kecualiStokNol');
                            echo $form->labelEx($model, 'kecualiStokNol');
                        ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="small-12 columns">
                    <?php echo CHtml::submitButton('Cetak', ['id' => 'tombol-submit', 'name' => 'cetak', 'class' => 'tiny bigfont button right']); ?>
                </div>
            </div>

            <?php
            $this->endWidget();