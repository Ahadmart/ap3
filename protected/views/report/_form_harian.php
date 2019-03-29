<?php
/* @var $this ReportController */
/* @var $model ReportPenjualanForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'report-harian-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
    'htmlOptions' => array('target' => '_blank'),
    'method' => 'GET'
        ));
?>
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>

<div class="row">
    <div class="small-12 columns">
        <?php echo $form->labelEx($model, 'tanggal'); ?>
        <?php echo $form->textField($model, 'tanggal', array('class' => 'tanggalan rata-kanan', 'value' => empty($model->tanggal) ? date('d-m-Y') : $model->tanggal)); ?>
        <?php echo $form->error($model, 'tanggal', array('class' => 'error')); ?>
    </div>
    <div class="small-12 medium-6 columns">
        <div class="row">
            <?php echo $form->checkBox($model, 'trxInvGroupByProfil'); ?>
            <?php echo $form->labelEx($model, 'trxInvGroupByProfil'); ?>
            <?php echo $form->error($model, 'trxInvGroupByProfil', array('class' => 'error')); ?>
        </div>
        <div class="row">
            <?php echo $form->checkBox($model, 'trxKeuGroupByProfil'); ?>
            <?php echo $form->labelEx($model, 'trxKeuGroupByProfil'); ?>
            <?php echo $form->error($model, 'trxKeuGroupByProfil', array('class' => 'error')); ?>
        </div>
    </div>
    <div class="small-12 medium-6 columns">
        <ul class="button-group right">
            <li>
                <a href="#" accesskey="p" data-dropdown="print" aria-controls="print" aria-expanded="false" class="tiny bigfont success button dropdown"><i class="fa fa-print fa-fw"></i> <span class="ak">C</span>etak</a>
                <ul id="print" data-dropdown-content class="small f-dropdown content" aria-hidden="true">
                    <?php
                    foreach ($printers as $printer) {
                        ?>
                        <?php
                        if ($printer['tipe_id'] == Device::TIPE_PDF_PRINTER) {
                            /* Jika printer pdf, tambahkan pilihan ukuran kertas */
                            ?>
                            <span class="sub-dropdown"><?= $printer['nama']; ?> <small><?= $printer['keterangan']; ?></small></span>
                            <ul>
                                <?php
                                foreach ($kertasPdf as $key => $value):
                                    ?>
                                    <li><a target="blank" class="tombol-cetak" href="<?=
                                        $this->createUrl($printHandle, [
                                            'printId' => $printer['id'],
                                            'kertas' => $key,
                                        ])
                                        ?>"><?= $value; ?></a></li>
                                        <?php
                                    endforeach;
                                    ?>
                            </ul>
                            <?php
                        } else {
                            ?>
                            <li>
                                <a class="tombol-cetak" href="<?=
                                   $this->createUrl($printHandle, [
                                       'printId' => $printer['id'],
                                   ])
                                   ?>">
                                    <?= $printer['nama']; ?> <small><?= $printer['keterangan']; ?></small></a>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>
                </ul>
            </li>
        </ul>  
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
    });

    $(".tombol-cetak").click(function () {
        var dataKirim = {
            'tanggal': $("#ReportHarianForm_tanggal").val(),
            'invGroup': $("#ReportHarianForm_trxInvGroupByProfil").is(':checked') ? 1 : 0,
            'keuGroup': $("#ReportHarianForm_trxKeuGroupByProfil").is(':checked') ? 1 : 0
        };
        var dataUrl = $(this).attr('href');
        window.open(dataUrl + '&' + $.param(dataKirim), '_blank');
        return false;
    });
</script>