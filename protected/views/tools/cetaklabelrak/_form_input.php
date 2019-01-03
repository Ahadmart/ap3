<?php
/* @var $this CetaklabelrakController */
/* @var $model CetakLabelRakForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'cetak-label-rak-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
        ));
?>
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>

<?php echo $form->hiddenField($model, 'profilId'); ?>
<?php echo $form->hiddenField($model, 'rakId'); ?>
<div class="row">
    <div class="small-12 large-6 columns">
        <div class="row collapse">
            <label>Profil (Supplier)</label>
            <div class="small-9 columns">
                <?php echo CHtml::textField('profil', empty($model->profilId) ? '' : $model->namaProfil, array('size' => 60, 'maxlength' => 500, 'disabled' => 'disabled')); ?>
            </div>
            <div class="small-3 columns">
                <a class="tiny bigfont button postfix" id="tombol-browse-profil" accesskey="p"><span class="ak">P</span>ilih..</a>
            </div>
        </div>
    </div>
    <div class="small-12 large-6 columns">
        <div class="row collapse">
            <label>Rak</label>
            <div class="small-9 columns">
                <?php echo CHtml::textField('rak', empty($model->rakId) ? '' : $model->namaRak, array('size' => 60, 'maxlength' => 500, 'disabled' => 'disabled')); ?>
            </div>
            <div class="small-3 columns">
                <a class="tiny bigfont button postfix" id="tombol-browse-rak" accesskey="h">Pili<span class="ak">h</span>..</a>
            </div>
        </div>
    </div>
    <div class="small-12 large-6 columns">
        <?php echo $form->labelEx($model, 'dari'); ?>
        <?php echo $form->textField($model, 'dari', array('class' => 'tanggal-waktu', 'value' => empty($model->dari) ? '' : $model->dari)); ?>
        <?php echo $form->error($model, 'dari', array('class' => 'error')); ?>
    </div>
    <div class="small-12 large-6 columns">
        <?php echo $form->labelEx($model, 'barcode'); ?>
        <div class="row collapse">
            <div class="small-2 columns">
                <?php
                /* https://github.com/zxing/zxing/wiki/Scanning-From-Web-Pages */
                /* http://stackoverflow.com/questions/26356626/using-zxing-barcode-scanner-within-a-web-page */
                /*
                  <!--<span class="prefix" id="scan-icon"><i class="fa fa-barcode fa-2x"></i></span>-->
                 */
                ?>
                <a class="prefix secondary button" href="zxing://scan/?ret=<?= $this->createAbsoluteUrl('index', ['barcodescan' => '{CODE}']) ?>"><i class="fa fa-barcode fa-2x"></i></a>

            </div>
            <div class="small-10 columns">
                <?php echo $form->textField($model, 'barcode', ['autofocus' => 'autofocus', 'accesskey' => 'b']); ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="small-12 columns">
        <?php echo CHtml::submitButton('Tambahkan', array('id' => 'tombol-submit', 'class' => 'tiny bigfont button right')); ?>
    </div>
</div>

<?php
$this->endWidget();
?>

<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-ui-ac.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/jquery-ui.min-ac.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/dt/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/dt/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/dt/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);
?>
<script>
    $(function () {
        $('.tanggal-waktu').fdatepicker({
            format: 'dd-mm-yyyy  hh:ii',
            disableDblClickSelection: true,
            language: 'id',
            pickTime: true
        });

        $("#cetak-label-rak-form").submit(function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            dataUrl = '<?php echo $this->createUrl('tambahkanbarang'); ?>';
            dataKirim = $(this).serializeArray();
            console.log(dataKirim);
            $.ajax({
                type: 'POST',
                url: dataUrl,
                data: dataKirim,
                success: function (data) {
                    if (data.sukses) {
                        $.gritter.add({
                            title: 'Sukses',
                            text: data.rowAffected + ' barang ditambahkan',
                            time: 3000
                        });
                        $.fn.yiiGridView.update('label-rak-cetak-grid');
                    } else {
                        $.gritter.add({
                            title: 'Error ' + data.error.code,
                            text: data.error.msg,
                            time: 3000
                        });
                    }

                    $("#CetakLabelRakForm_barcode").val("");
                    $("#CetakLabelRakForm_barcode").focus();
                }
            });
            return false;
        });
    });
    //CetakLabelRakForm_barcode

    $("#CetakLabelRakForm_barcode").autocomplete({
        source: "<?php echo $this->createUrl('/tools/cekharga/caribarang'); ?>",
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
                setTimeout(function () {
                    $("#tombol-submit").click();
                }, 500);
            }
        }
    }).autocomplete("instance")._renderItem = function (ul, item) {
        return $("<li style='clear:both'>")
                .append("<a><span class='ac-nama'>" + item.label + "</span> <span class='ac-barcode'><i>" + item.value + "</i></span></a>")
                .appendTo(ul);
    };
<?php
if (!is_null($scanBarcode)) {
    ?>
        $(function () {
            $("#CetakLabelRakForm_barcode").val(<?= $scanBarcode ?>);
            setTimeout(function () {
                $("#cetak-label-rak-form").submit();
            }, 500);
        });
    <?php
}
?>
</script>