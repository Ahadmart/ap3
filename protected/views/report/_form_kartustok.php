<?php
/* @var $this ReportController */
/* @var $model ReportPenjualanForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'report-penjualan-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
        ));
?>
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>
<div class="row">
    <div class="large-6 columns">
        <div class="small-12 columns">
            <div class="row collapse">
                <div class="small-2 medium-1 columns">
                    <span class="prefix" id="scan-icon"><i class="fa fa-barcode fa-2x"></i></span>
                </div>
                <div class="small-6 medium-9 columns">
                    <input id="scan" type="text"  placeholder="Scan [B]arcode / Input nama" accesskey="b" autofocus="autofocus"/>
                </div>
                <div class="small-2 medium-1 columns">
                    <a href="#" class="button postfix" id="tombol-tambah-barang"><i class="fa fa-level-down fa-2x fa-rotate-90"></i></a>
                </div>

                <div class="small-2 medium-1 columns">
                    <a href="#" class="success button postfix" id="tombol-cari-tabel" accesskey="c"><i class="fa fa-search-plus fa-2x"></i></a>
                </div>
                </di>
            </div>
        </div>

        <div class="large-6 columns">
            <?php echo $form->labelEx($model, 'dari'); ?>
            <?php echo $form->textField($model, 'dari', array('class' => 'tanggalan', 'value' => empty($model->dari) ? date('d-m-Y') : $model->dari)); ?>
            <?php echo $form->error($model, 'dari', array('class' => 'error')); ?>
        </div>
        <div class="large-6 columns">
            <?php echo $form->labelEx($model, 'sampai'); ?>
            <?php echo $form->textField($model, 'sampai', array('class' => 'tanggalan', 'value' => empty($model->sampai) ? date('d-m-Y') : $model->sampai)); ?>
            <?php echo $form->error($model, 'sampai', array('class' => 'error')); ?>
        </div>
        <div class="small-12 columns">
            <?php echo CHtml::submitButton('Submit', array('class' => 'tiny bigfont button right')); ?>
        </div>
    </div>
    <div class="large-6 columns">

    </div>
</div>
<div class="row">   
</div>

<div class="row">
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