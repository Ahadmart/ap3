<?php
/* @var $this ReportController */
/* @var $model ReportPpnForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', [
    'id'                   => 'report-ppn-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
    'htmlOptions'          => ['target' => '_blank'],
    'method'               => 'GET',
]);
?>
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']); ?>

<div class="row">
    <div class="small-12 columns">
        <?php echo $form->labelEx($model, 'periode'); ?>
        <?php echo $form->textField($model, 'periode', ['class' => 'periode rata-kanan', 'value' => empty($model->tanggal) ? date('Y-m') : $model->tanggal]); ?>
        <?php echo $form->error($model, 'periode', ['class' => 'error']); ?>
    </div>
    <div class="small-12 medium-6 columns">
        <div class="row">
            <?php echo $form->checkBox($model, 'detailPpnPembelianValid'); ?>
            <?php echo $form->labelEx($model, 'detailPpnPembelianValid'); ?>
            <?php echo $form->error($model, 'detailPpnPembelianValid', ['class' => 'error']); ?>
        </div>
        <div class="row">
            <?php echo $form->checkBox($model, 'detailPpnPembelianPending'); ?>
            <?php echo $form->labelEx($model, 'detailPpnPembelianPending'); ?>
            <?php echo $form->error($model, 'detailPpnPembelianPending', ['class' => 'error']); ?>
        </div>
    </div>

    <div class="small-12 columns">
        <?php echo CHtml::submitButton('Submit', ['class' => 'tiny bigfont button right tombol-submit']); ?>
    </div>
</div>
<?php
$this->endWidget();

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);
?>
<script>
    $(function() {
        $('.periode').fdatepicker({
            format: 'yyyy-mm',
            startView: 'year',
            minView: 'year',
            language: 'id'
        });
    });

    $(".tombol-submit").click(function() {
        var dataKirim = {
            'periode': $("#ReportPpnForm_periode").val(),
            'detailValid': $("#ReportPpnForm_detailPpnPembelianValid").is(':checked') ? 1 : 0,
            'detailPending': $("#ReportPpnForm_detailPpnPembelianPending").is(':checked') ? 1 : 0
        };
        $("#rekap").hide();
        $("#detail-valid").hide();
        $("#detail-pending").hide();
        $("#tombol-cetak").hide();
        $.ajax({
            type: "POST",
            url: '<?php echo $this->createUrl('getppn'); ?>',
            data: dataKirim,
            dataType: "json",
            success: function(hasil) {
                if (hasil.sukses) {
                    $("#tombol-cetak").show();
                    $("#rekap").show();
                    isiTabelPpn(hasil.data);
                    if (hasil.data.detailPpnPembelianValid.length > 0) {
                        $("#detail-valid").show();
                        isiDetailValid(hasil.data.detailPpnPembelianValid)
                    }
                    if (hasil.data.detailPpnPembelianPending.length > 0) {
                        $("#detail-pending").show();
                        isiDetailPending(hasil.data.detailPpnPembelianPending)
                    }
                }
            }
        });
        return false;
    });
</script>