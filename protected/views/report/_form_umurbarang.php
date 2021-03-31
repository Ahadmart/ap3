<?php
/* @var $this ReportController */
/* @var $model ReportUmurBarangForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', [
    'id'                   => 'report-umurbarang-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
]);
?>
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']); ?>

<div class="row">
    <div class="medium-4 large-3 columns">
        <div class="row collapse">
            <div class="small-12 columns">
                <?php echo $form->labelEx($model, 'bulan'); ?>
                <?php
                echo $form->dropDownList($model, 'bulan', $model->opsiUmurBulan(), [
                    'prompt'  => '',
                    'options' => [
                        isset($model->bulan) ? $model->bulan : ReportUmurBarangForm::OPSI_BULAN_3 => ['selected' => 'selected'],
                    ],
                ]);
                ?>
                <?php echo $form->error($model, 'bulan', ['class' => 'error']); ?>
            </div>
        </div>
    </div>
    <div class="medium-4 large-3 end columns">
        <div class="row collapse">
            <div class="small-12 columns">
                <?php echo $form->labelEx($model, 'dari'); ?>
                <?php echo $form->textField($model, 'dari', ['class' => 'tanggalan dari', 'value' => empty($model->dari) ? '' : $model->dari]); ?>
                <?php echo $form->error($model, 'dari', ['class' => 'error']); ?>
            </div>
            <div class="small-12 columns">
                <?php echo $form->labelEx($model, 'sampai'); ?>
                <?php echo $form->textField($model, 'sampai', ['class' => 'tanggalan sampai', 'value' => empty($model->sampai) ? '' : $model->sampai]); ?>
                <?php echo $form->error($model, 'sampai', ['class' => 'error']); ?>
            </div>
        </div>
    </div>
    <div class="medium-4 large-3 end columns">
        <div class="row collapse">
            <div class="small-12 columns">
                <?php echo $form->labelEx($model, 'kategoriId'); ?>
                <?php echo $form->dropDownList($model, 'kategoriId', $model->filterKategori()); ?>
                <?php echo $form->error($model, 'kategoriId', ['class' => 'error']); ?>
            </div>
            <div class="small-12 columns">
                <?php echo $form->labelEx($model, 'limit'); ?>
                <?php echo $form->textField($model, 'limit'); ?>
                <?php echo $form->error($model, 'limit', ['class' => 'error']); ?>
            </div>
        </div>
    </div>
    <div class="medium-4 large-3 columns">
        <div class="row collapse">
            <div class="small-12 columns">
                <?php echo $form->labelEx($model, 'sortBy0'); ?>
                <?php
                echo $form->dropDownList($model, 'sortBy0', $model->listSortBy(), [
                    'options' => [
                        isset($model->sortBy0) ? $model->sortBy0 : ReportUmurBarangForm::SORT_BY_UMUR_DSC => ['selected' => 'selected'],
                    ],
                ]);
                ?>
                <?php echo $form->error($model, 'sortBy0', ['class' => 'error']); ?>
            </div>
            <div class="small-12 columns">
                <?php echo $form->labelEx($model, 'sortBy1'); ?>
                <?php
                echo $form->dropDownList($model, 'sortBy1', $model->listSortBy(), [
                    'options' => [
                        isset($model->sortBy1) ? $model->sortBy1 : ReportUmurBarangForm::SORT_BY_NILAISTOK_DSC => ['selected' => 'selected'],
                    ],
                ]);
                ?>
                <?php echo $form->error($model, 'sortBy1', ['class' => 'error']); ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="small-12 medium-4 columns">
        <?php echo $form->labelEx($model, 'strukLv1'); ?>
        <?php echo $form->dropDownList($model, 'strukLv1', StrukturBarang::listStrukLv1()); ?>
        <?php echo $form->error($model, 'strukLv1', ['class' => 'error']); ?>
    </div>
    <div class="small-12 medium-4 columns">
        <?php echo $form->labelEx($model, 'strukLv2'); ?>
        <?php
        $strukLv2List = ['' => '[SEMUA]'];
        if (!empty($model->strukLv1)) {
            $strukLv2List = StrukturBarang::listStrukLv2($model->strukLv1);
            // var_dump($strukLv2List);
        }
        ?>
        <?php echo $form->dropDownList($model, 'strukLv2', $strukLv2List); ?>
        <?php echo $form->error($model, 'strukLv2', ['class' => 'error']); ?>
    </div>
    <div class="small-12 medium-4 columns">
        <?php echo $form->labelEx($model, 'strukLv3'); ?>
        <?php
        $strukLv3List = ['' => '[SEMUA]'];
        if (!empty($model->strukLv2)) {
            $strukLv3List = StrukturBarang::listStrukLv3($model->strukLv2);
        }
        ?>
        <?php echo $form->dropDownList($model, 'strukLv3', $strukLv3List); ?>
        <?php echo $form->error($model, 'strukLv3', ['class' => 'error']); ?>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <?php echo CHtml::submitButton('Submit', ['class' => 'tiny bigfont button right']); ?>
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
        $('.tanggalan.dari').fdatepicker({
            format: 'dd-mm-yyyy',
            initialDate: '01-01-2000',
            language: 'id'

        });
        $('.tanggalan.sampai').fdatepicker({
            format: 'dd-mm-yyyy',
            initialDate: '<?= date('d-m-Y', strtotime(date('Y-m-d') . '-91 day')) ?>',
            language: 'id'

        });
    });

    $("#ReportUmurBarangForm_strukLv1").change(function() {
        var parentId = $(this).val()
        $("label[for='ReportUmurBarangForm_strukLv2']").text('Loading..');
        $("#ReportUmurBarangForm_strukLv2").load("<?= $this->createUrl('ambilstrukturlv2', ['parent-id' => '']); ?>" + parentId, function() {
            $("label[for='ReportUmurBarangForm_strukLv2']").text('Struktur Level 2');
        })
        $("#ReportUmurBarangForm_strukLv3").html("<option value=''>[SEMUA]</option>")
    })

    $("#ReportUmurBarangForm_strukLv2").change(function() {
        var parentId = $(this).val()
        $("label[for='ReportUmurBarangForm_strukLv3']").text('Loading..');
        $("#ReportUmurBarangForm_strukLv3").load("<?= $this->createUrl('ambilstrukturlv3', ['parent-id' => '']); ?>" + parentId, function() {
            $("label[for='ReportUmurBarangForm_strukLv3']").text('Struktur Level 3');
        })
    })
</script>