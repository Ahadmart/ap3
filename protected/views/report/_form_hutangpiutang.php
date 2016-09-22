<?php
/* @var $this ReportController */
/* @var $model ReportHutangPiutangForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'report-hutangpiutang-form',
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
    <div class="medium-6 large-4 columns">
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
</div>
<div class="row">
    <div class="small-12 medium-3 large-2 columns">
        <?php echo $form->checkBoxList($model, 'showDetail', [true => 'Tampilkan Detail']); ?>
    </div>
    <div class="small-12 medium-3 large-2 end columns">
        <?php echo $form->checkBoxList($model, 'pilihCetak', ['hutang' => 'Hutang', 'piutang' => 'Piutang']); ?>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <?php echo CHtml::submitButton('Submit', array('class' => 'tiny bigfont button')); ?>
    </div>
</div>

<?php
$this->endWidget();
