<?php
/* @var $this ReportController */
/* @var $model ReportPenjualanForm */
/* @var $form CActiveForm */
?>
<?php
if (is_null($scanBarcode)) {
    $initBarcode = '';
} else {
    $initBarcode = $scanBarcode;
}
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
    <div class="small-12 medium-8 large-12 columns">
        <?php echo $form->labelEx($model, 'barcode'); ?>
        <div class="row collapse">
            <div class="small-2 medium-1 large-2 columns">
                <?php
                /* https://github.com/zxing/zxing/wiki/Scanning-From-Web-Pages */
                /* http://stackoverflow.com/questions/26356626/using-zxing-barcode-scanner-within-a-web-page */
                ?>
                <a class="prefix secondary button" href="zxing://scan/?ret=<?= $this->createAbsoluteUrl('kartustok', ['barcodescan' => '{CODE}']) ?>"><i class="fa fa-barcode fa-2x"></i></a> 
            </div>
            <div class="small-10 medium-11 large-10 columns">
                <!--<input id="scan" type="text" placeholder="Scan [B]arcode / Input nama" accesskey="b" autofocus="autofocus"/>-->
                <?=
                $form->textField($model, 'barcode', [
                    'placeholder' => 'Scan [B]arcode / Input nama',
                    'accesskey' => 'b',
                    'autofocus' => 'autofocus',
                    'id' => 'scan',
                    'value' => $initBarcode,
                ]);
                ?>
            </div>
            </di>
        </div>
    </div>
    <?php echo $form->hiddenField($model, 'barangId'); ?>
    <div class="medium-2 large-6 columns">
        <?php echo $form->labelEx($model, 'dari'); ?>
        <?php echo $form->textField($model, 'dari', array('class' => 'tanggalan dari', 'value' => empty($model->dari) ? '' : $model->dari)); ?>
        <?php echo $form->error($model, 'dari', array('class' => 'error')); ?>
    </div>
    <div class="medium-2 large-6 columns">
        <?php echo $form->labelEx($model, 'sampai'); ?>
        <?php echo $form->textField($model, 'sampai', array('class' => 'tanggalan sampai', 'value' => empty($model->sampai) ? date('d-m-Y') : $model->sampai)); ?>
        <?php echo $form->error($model, 'sampai', array('class' => 'error')); ?>
    </div>
    <div class="small-12 columns">
        <?php echo CHtml::submitButton('Submit', array('class' => 'tiny bigfont button right')); ?>
    </div>
</div>

<?php
$this->endWidget();

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-ui-ac.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/jquery-ui.min-ac.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js', CClientScript::POS_HEAD);
?>
<script>
    $(function () {
        $('.tanggalan.dari').fdatepicker({
            format: 'dd-mm-yyyy',
            initialDate: '<?= date('d-m-Y', strtotime(date('Y-m-d') . '-30 day')) ?>',
            language: 'id'
        });
        $('.tanggalan.sampai').fdatepicker({
            format: 'dd-mm-yyyy',
            language: 'id'
        });
        
        $("#scan").select();

        $("#scan").autocomplete({
            source: "<?php echo $this->createUrl('caribarang'); ?>",
            minLength: 3,
            delay: 1000,
            search: function (event, ui) {
                $("#scan-icon").html('<img src="<?php echo Yii::app()->theme->baseUrl; ?>/css/3.gif" />');
            },
            response: function (event, ui) {
                $("#scan-icon").html('<i class="fa fa-barcode fa-2x"></i>');
            },
            select: function (event, ui) {
                console.log(ui.item ?
                        "Nama: " + ui.item.label + "; Barcode " + ui.item.value :
                        "Nothing selected, input was " + this.value);
                if (ui.item) {
                    $("#scan").val(ui.item.value);
                    $("#ReportKartuStokForm_barangId").val(ui.item.id);
                }
            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li style='clear:both'>")
                    .append("<a><span class='ac-nama'>" + item.label + "</span> <span class='ac-harga'>" + item.harga + "</span> <span class='ac-barcode'><i>" + item.value + "</i></span> <span class='ac-stok'>" + item.stok + "</stok></a>")
                    .appendTo(ul);
        };
    });
</script>