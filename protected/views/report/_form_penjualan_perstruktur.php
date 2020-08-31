<?php
/* @var $this ReportController */
/* @var $model ReportPenjualanForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', [
    'id'                   => 'report-penjualan-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
    'action'               => $this->createUrl('printpenjualanstruktur'),
    'htmlOptions'          => [
        'target' => '_blank',
    ],
]);
?>
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']); ?>

<?php echo $form->hiddenField($model, 'profilId'); ?>
<?php echo $form->hiddenField($model, 'userId'); ?>
<div class="row">
    <div class="small-12 medium-6 large-3 columns">
        <?php echo $form->labelEx($model, 'dari'); ?>
        <?php echo $form->textField($model, 'dari', ['class' => 'tanggal-waktu', 'value' => empty($model->dari) ? date('d-m-Y') . ' 00:00' : $model->dari]); ?>
        <?php echo $form->error($model, 'dari', ['class' => 'error']); ?>
    </div>
    <div class="small-12 medium-6 large-3 columns">
        <?php echo $form->labelEx($model, 'sampai'); ?>
        <?php echo $form->textField($model, 'sampai', ['class' => 'tanggal-waktu', 'value' => empty($model->sampai) ? date('d-m-Y') . ' 23:59' : $model->sampai]); ?>
        <?php echo $form->error($model, 'sampai', ['class' => 'error']); ?>
    </div>
    <div class="medium-6 large-3 columns">
        <div class="row collapse">
            <label>Customer</label>
            <div class="small-9 columns">
                <?php echo CHtml::textField('profil', empty($model->profilId) ? '' : $model->namaProfil, ['size' => 60, 'maxlength' => 500, 'disabled' => 'disabled']); ?>
            </div>
            <div class="small-3 columns">
                <a class="tiny bigfont button postfix" id="tombol-browse-profil" accesskey="p"><span class="ak">P</span>ilih..</a>
            </div>
        </div>
    </div>
    <div class="medium-6 large-3 columns">
        <div class="row collapse">
            <label>User</label>
            <div class="small-9 columns">
                <?php echo CHtml::textField('user', empty($model->userId) ? '' : $model->namaUser, ['size' => 60, 'maxlength' => 500, 'disabled' => 'disabled']); ?>
            </div>
            <div class="small-3 columns">
                <a class="tiny bigfont button postfix" id="tombol-browse-user" accesskey="h">Pili<span class="ak">h</span>..</a>
            </div>
        </div>
    </div>
    <div class="small-12 medium-4 columns">
        <?php echo $form->labelEx($model, 'strukLv1'); ?>
        <?php echo $form->dropDownList($model, 'strukLv1', $model->listStrukLv1()); ?>
        <?php echo $form->error($model, 'strukLv1', ['class' => 'error']); ?>
    </div>
    <div class="small-12 medium-4 columns">
        <?php echo $form->labelEx($model, 'strukLv2'); ?>
        <?php echo $form->dropDownList($model, 'strukLv2', [], ['prompt' => '[SEMUA]']); ?>
        <?php echo $form->error($model, 'strukLv2', ['class' => 'error']); ?>
    </div>
    <div class="small-12 medium-4 columns">
        <?php echo $form->labelEx($model, 'strukLv3'); ?>
        <?php echo $form->dropDownList($model, 'strukLv3', [], ['prompt' => '[SEMUA]']); ?>
        <?php echo $form->error($model, 'strukLv3', ['class' => 'error']); ?>
    </div>
</div>
<hr />

<div class="row">
    <?php
    /*
<div class="small-12 medium-2 columns">
<?php echo $form->labelEx($model, 'kertas'); ?>
<?php echo $form->dropDownList($model, 'kertas', $kertasPdf); ?>
<?php echo $form->error($model, 'kertas', ['class' => 'error']); ?>
</div>
 */
    ?>
    <div class="small-6 medium-2 large-1 columns">
        <?php echo $form->labelEx($model, 'printer'); ?>
        <?php echo $form->dropDownList($model, 'printer', $optionPrinters); ?>
        <?php echo $form->error($model, 'printer', ['class' => 'error']); ?>
    </div>
    <div class="small-6 medium-2 large-1 columns end">
        <label for="tombol-cetak">&nbsp;</label>
        <?php echo CHtml::submitButton('Submit', ['name' => 'cetak', 'id' => 'tombol-cetak', 'class' => 'tiny bigfont success button']); ?>
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
        $('.tanggalan').fdatepicker({
            format: 'dd-mm-yyyy',
            language: 'id'
        });
        $('.tanggal-waktu').fdatepicker({
            format: 'dd-mm-yyyy  hh:ii',
            disableDblClickSelection: true,
            language: 'id',
            pickTime: true
        });
    });

    $("#ReportPenjualanPerStrukturForm_strukLv1").change(function() {
        var parentId = $(this).val()
        $("label[for='ReportPenjualanPerStrukturForm_strukLv2']").text('Loading..');
        $("#ReportPenjualanPerStrukturForm_strukLv2").load("<?= $this->createUrl('ambilstrukturlv2', ['parent-id' => '']); ?>" + parentId, function() {
            $("label[for='ReportPenjualanPerStrukturForm_strukLv2']").text('Struktur Level 2');
        })
        $("#ReportPenjualanPerStrukturForm_strukLv3").html("<option value=''>[SEMUA]</option>")
    })

    $("#ReportPenjualanPerStrukturForm_strukLv2").change(function() {
        var parentId = $(this).val()
        $("label[for='ReportPenjualanPerStrukturForm_strukLv3']").text('Loading..');
        $("#ReportPenjualanPerStrukturForm_strukLv3").load("<?= $this->createUrl('ambilstrukturlv3', ['parent-id' => '']); ?>" + parentId, function() {
            $("label[for='ReportPenjualanPerStrukturForm_strukLv3']").text('Struktur Level 3');
        })
    })
</script>