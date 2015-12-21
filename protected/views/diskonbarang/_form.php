<?php
/* @var $this DiskonbarangController */
/* @var $model DiskonBarang */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'diskon-barang-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>

    <?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'tipe_diskon_id'); ?>
            <?php
            echo $form->dropDownList($model, 'tipe_diskon_id', $model->listTipe(), array(
                'prompt' => 'Pilih Satu..',
                'autofocus' => 'autofocus'));
            ?>
            <?php echo $form->error($model, 'tipe_diskon_id', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->hiddenField($model, 'barang_id'); ?>
            <input type="hidden" id="harga-jual-raw" />
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <label for="scan" class="required">Barang <span class="required">*</span></label>
            <div class="row collapse">
                <div class="small-3 large-2 columns">
                    <span class="prefix" id="scan-icon"><i class="fa fa-barcode fa-2x"></i></span>
                </div>
                <div class="small-6 large-8 columns">
                    <input id="scan" type="text"  placeholder="Scan [B]arcode / Input nama" accesskey="b"/>
                </div>
                <div class="small-3 large-2 columns">
                    <a href="#" class="button postfix" id="tombol-scan-ok"><i class="fa fa-level-down fa-2x fa-rotate-90"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel" id="info-barang" style="display: none">

        </div>
    </div>

    <div class="row">
        <div class="small-6 columns">
            <?php echo $form->labelEx($model, 'nominal'); ?>
            <?php echo $form->textField($model, 'nominal', array('size' => 18, 'maxlength' => 18)); ?>
            <?php echo $form->error($model, 'nominal', array('class' => 'error')); ?>
        </div>
        <div class="small-6 columns">
            <label for="harga_net">Harga Net</label>
            <input size="18" maxlength="18" id="harga_net" type="text" disabled="disabled">
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'persen'); ?>
            <?php echo $form->textField($model, 'persen'); ?>
            <?php echo $form->error($model, 'persen', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'dari'); ?>
            <?php echo $form->textField($model, 'dari'); ?>
            <?php echo $form->error($model, 'dari', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'sampai'); ?>
            <?php echo $form->textField($model, 'sampai'); ?>
            <?php echo $form->error($model, 'sampai', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'qty'); ?>
            <?php echo $form->textField($model, 'qty', array('size' => 10, 'maxlength' => 10)); ?>
            <?php echo $form->error($model, 'qty', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'qty_min'); ?>
            <?php echo $form->textField($model, 'qty_min', array('size' => 10, 'maxlength' => 10)); ?>
            <?php echo $form->error($model, 'qty_min', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'qty_max'); ?>
            <?php echo $form->textField($model, 'qty_max', array('size' => 10, 'maxlength' => 10)); ?>
            <?php echo $form->error($model, 'qty_max', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'status'); ?>
            <?php echo $form->dropDownList($model, 'status', $model->listStatus()); ?>
            <?php echo $form->error($model, 'status', array('class' => 'error')); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', array('class' => 'tiny bigfont button')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-ui-ac.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/jquery-ui.min-ac.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js', CClientScript::POS_HEAD);
?>
<script>
    $("#scan").autocomplete({
        source: "<?php echo $this->createUrl('caribarang'); ?>",
        minLength: 3,
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
            }
        }
    }).autocomplete("instance")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.label + "<br /><small>" + item.value + " [" + item.stok + "][" + item.harga + "]</small></a>")
                .appendTo(ul);
    };

    $(function () {
        $(document).on('click', "#tombol-scan-ok", function () {
            dataUrl = '<?php echo $this->createUrl('getdatabarang'); ?>';
            dataKirim = {barcode: $("#scan").val()};
            console.log(dataUrl);
            /* Jika tidak ada barang, keluar! */
            if ($("#scan").val() === '') {
                return false;
            }

            $.ajax({
                type: 'POST',
                url: dataUrl,
                data: dataKirim,
                success: function (data) {
                    if (data.sukses) {
                        var hasil = "<h5>" + data.barcode +
                                " " + data.nama + "</h5>" +
                                "<h6><small>Stok</small>" + data.stok +
                                " <small>Harga</small>" + data.hargaJual + "</h6>";
                        $("#info-barang").html(hasil);
                        $("#info-barang").show();
                        $("#harga-jual-raw").val(data.hargaJualRaw);
                        $("#DiskonBarang_barang_id").val(data.barangId);
                    } else {
                        $.gritter.add({
                            title: 'Error ' + data.error.code,
                            text: data.error.msg,
                            time: 3000,
                            //class_name: 'gritter-center'
                        });
                    }

                    $("#DiskonBarang_nominal").focus();
                }
            });
            return false;
        });
    });

    $("#scan").keyup(function (e) {
        if (e.keyCode === 13) {
            $("#tombol-scan-ok").click();
        }
    });

    function kalkulasiDiskonDariNominal() {
        var hargaNet = $("#harga-jual-raw").val() - $("#DiskonBarang_nominal").val();
        var persen = $("#DiskonBarang_nominal").val() / $("#harga-jual-raw").val() * 100;
        $("#harga_net").val(hargaNet);
        $("#DiskonBarang_persen").val(persen);
    }

    function kalkulasiDiskonDariPersen() {
        var hargaNet = $("#harga-jual-raw").val() - $("#DiskonBarang_persen").val() / 100 * $("#harga-jual-raw").val();
        var nominal = $("#DiskonBarang_persen").val() / 100 * $("#harga-jual-raw").val();
        $("#harga_net").val(hargaNet);
        $("#DiskonBarang_nominal").val(nominal);
    }

    $("#DiskonBarang_nominal").change(function () {
        kalkulasiDiskonDariNominal();
    });

    $("#DiskonBarang_persen").change(function () {
        kalkulasiDiskonDariPersen();
    });
</script>