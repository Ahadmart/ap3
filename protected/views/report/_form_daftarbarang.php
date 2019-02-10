<?php
/* @var $this ReportController */
/* @var $model ReportDaftarBarangForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', [
    'id' => 'report-daftarbarang-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
        ]);
?>
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>
<?php echo $form->hiddenField($model, 'profilId'); ?>
<div class="row">
    <div class="medium-6 columns">
        <div class="row">
            <div class="small-12 columns">
                <?= $form->labelEx($model, 'filterNama'); ?>
                <?= $form->textField($model, 'filterNama') ?>
            </div>
            <div class="small-12 columns">
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
            <div class="small-12 columns">
                <?php echo $form->checkBox($model, 'hanyaDefault',['checked' => 'checked']); ?>
                <?php echo $form->labelEx($model, 'hanyaDefault'); ?>
            </div>
        </div>

    </div>   
<!--    <div class="medium-4 large-3 end columns">
        <div class="row collapse">
            <div class="small-12 columns">
                <?php // echo $form->labelEx($model, 'kategoriId'); ?>
                <?php // echo $form->dropDownList($model, 'kategoriId', $model->filterKategori()); ?>
                <?php // echo $form->error($model, 'kategoriId', array('class' => 'error')); ?>
            </div>
        </div>
    </div>-->
    <div class="medium-6 columns">
        <div class="row collapse">
            <div class="small-12 columns">
                <?php echo $form->labelEx($model, 'sortBy0'); ?>
                <?php
                echo $form->dropDownList($model, 'sortBy0', $model->listSortBy(), [
                    'options' => [
                        isset($model->sortBy0) ? $model->sortBy0 : ReportDaftarBarangForm::SORT_BY_NAMA => ['selected' => 'selected']
                    ]
                ]);
                ?>
                <?php echo $form->error($model, 'sortBy0', array('class' => 'error')); ?>
            </div>
            <div class="small-12 columns">
                <?php echo $form->labelEx($model, 'sortBy1'); ?>
                <?php
                echo $form->dropDownList($model, 'sortBy1', $model->listSortBy(), [
                    'options' => [
                        isset($model->sortBy1) ? $model->sortBy1 : ReportDaftarBarangForm::SORT_BY_BARCODE => ['selected' => 'selected']
                    ]
                ]);
                ?>
                <?php echo $form->error($model, 'sortBy1', array('class' => 'error')); ?>
            </div>
<!--            <div class="small-12 columns">
                <?php // echo CHtml::submitButton('Submit', array('class' => 'tiny bigfont button right')); ?>
            </div>-->
        </div>
    </div>
</div>


<?php
$this->endWidget();
