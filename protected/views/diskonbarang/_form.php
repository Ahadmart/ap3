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
            <input type="hidden" id="harga-jual-raw" <?php echo $model->isNewRecord ? '' : 'value="' . $model->barang->getHargaJualRaw() . '"' ?>/>
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
                    <input id="scan" type="text"  placeholder="Scan [B]arcode / Input nama" accesskey="b"<?php echo $model->isNewRecord ? '' : 'value="' . $model->barang->barcode . '"' ?>/>
                </div>
                <div class="small-3 large-2 columns">
                    <a href="#" class="button postfix" id="tombol-scan-ok"><i class="fa fa-level-down fa-2x fa-rotate-90"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel" id="info-barang" style="display: none; padding-bottom: 15px; margin-left: none; margin-right: none">

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
        <div class="small-6 columns">
            <?php echo $form->labelEx($model, 'persen'); ?>
            <?php echo $form->textField($model, 'persen'); ?>
            <?php echo $form->error($model, 'persen', array('class' => 'error')); ?>
        </div>
        <div class="small-6 columns">
            <div  id="row-qty" style="display: none">
                <?php echo $form->labelEx($model, 'qty'); ?>
                <?php echo $form->textField($model, 'qty', array('size' => 10, 'maxlength' => 10)); ?>
                <?php echo $form->error($model, 'qty', array('class' => 'error')); ?>
            </div>
            <div  id="row-qty-min" style="display: none">
                <?php echo $form->labelEx($model, 'qty_min'); ?>
                <?php echo $form->textField($model, 'qty_min', array('size' => 10, 'maxlength' => 10)); ?>
                <?php echo $form->error($model, 'qty_min', array('class' => 'error')); ?>
            </div>
            <div  id="row-qty-max" style="display: none">
                <?php echo $form->labelEx($model, 'qty_max'); ?>
                <?php echo $form->textField($model, 'qty_max', array('size' => 10, 'maxlength' => 10)); ?>
                <?php echo $form->error($model, 'qty_max', array('class' => 'error')); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="small-6 columns">
            <?php echo $form->labelEx($model, 'dari'); ?>
            <?php echo $form->textField($model, 'dari', array('class' => 'tanggal-waktu', 'value' => $model->isNewRecord ? date('d-m-Y H:i') : $model->dari)); ?>
            <?php echo $form->error($model, 'dari', array('class' => 'error')); ?>
        </div>
        <div class="small-6 columns">
            <?php echo $form->labelEx($model, 'sampai'); ?>
            <?php //echo $form->textField($model, 'sampai', array('class' => 'tanggal-waktu', 'value' => $model->isNewRecord ? '' : date_format(date_create_from_format('Y-m-d', $model->sampai), 'd-m-Y'))); ?>
            <?php echo $form->textField($model, 'sampai', array('class' => 'tanggal-waktu', 'value' => empty($model->sampai) ? '' : $model->sampai)); ?>
            <?php echo $form->error($model, 'sampai', array('class' => 'error')); ?>
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

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/dt/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/dt/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/dt/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);
?>

<script>
    $("#DiskonBarang_tipe_diskon_id").change(function () {
        console.log($(this).val());
        var tipeId = $(this).val();
        shField(tipeId);
    });

    function shField(tipeId) {
        switch (tipeId) {
            case '<?php echo DiskonBarang::TIPE_PROMO; ?>':
                promoFields();
                break;
            case '<?php echo DiskonBarang::TIPE_GROSIR; ?>':
                grosirFields();
                break;
            case '<?php echo DiskonBarang::TIPE_BANDED; ?>':
                bandedFields();
                break;
            case '<?php echo DiskonBarang::TIPE_PROMO_MEMBER; ?>':
                promoFields();
                break;
        }
    }

    function promoFields() {
        $("#row-qty").hide(500);
        $("#row-qty-min").hide(500);
        $("#row-qty-max").show(500);
    }

    function grosirFields() {
        $("#row-qty").hide(500);
        $("#row-qty-max").hide(500);
        $("#row-qty-min").show(500);
    }

    function bandedFields() {
        $("#row-qty-min").hide(500);
        $("#row-qty-max").hide(500);
        $("#row-qty").show(500);
    }

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

    function tampilkanHargaBanded() {
        var hargaNet = $("#harga-jual-raw").val() - $("#DiskonBarang_persen").val() / 100 * $("#harga-jual-raw").val();
        var qty = $("#DiskonBarang_qty").val();
        var hargaBanded = hargaNet * qty;
        if (qty > 0) {
            $("#harga_net").val($("#harga_net").val() + " [ " + number_format(hargaBanded, 0, ',', '.') + "/" + qty + " ]");
        }
    }

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
                                "<h6><small>Stok</small> " + data.stok + " " + data.satuan +
                                " <small>Harga</small> " + data.hargaJual + " / " + data.satuan + "</h6>";
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
        });
        $(document).on('change', '#DiskonBarang_qty', function () {
            kalkulasiDiskonDariNominal();
            tampilkanHargaBanded();
        });
    });

    $("#scan").on("keydown", function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            $("#tombol-scan-ok").click();
        }
    });

    function kalkulasiDiskonDariNominal() {
        var hargaNet = $("#harga-jual-raw").val() - $("#DiskonBarang_nominal").val();
        var persen = $("#DiskonBarang_nominal").val() / $("#harga-jual-raw").val() * 100;
        $("#harga_net").val(number_format(hargaNet, 2, ',', '.'));
        $("#DiskonBarang_persen").val(persen);
    }

    function kalkulasiDiskonDariPersen() {
        var hargaNet = $("#harga-jual-raw").val() - $("#DiskonBarang_persen").val() / 100 * $("#harga-jual-raw").val();
        var nominal = $("#DiskonBarang_persen").val() / 100 * $("#harga-jual-raw").val();
        $("#harga_net").val(number_format(hargaNet, 2, ',', '.'));
        $("#DiskonBarang_nominal").val(nominal);
    }

    $("#DiskonBarang_nominal").change(function () {
        kalkulasiDiskonDariNominal();
        tampilkanHargaBanded();// Jalan jika qty > 0
    });

    $("#DiskonBarang_persen").change(function () {
        kalkulasiDiskonDariPersen();
        tampilkanHargaBanded();// Jalan jika qty > 0
    });

    $(function () {
        shField($("#DiskonBarang_tipe_diskon_id").val());
<?php
if (!$model->isNewRecord) {
    ?>
            kalkulasiDiskonDariNominal();
            tampilkanHargaBanded();// Jalan jika qty > 0
            $("#tombol-scan-ok").click();
    <?php
}
?>
        $('.tanggal-waktu').fdatepicker({
            format: 'dd-mm-yyyy  hh:ii',
            disableDblClickSelection: true,
            language: 'id',
            pickTime: true
        });
    });

    function number_format(number, decimals, dec_point, thousands_sep) {
        //  discuss at: http://phpjs.org/functions/number_format/
        // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
        // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // improved by: davook
        // improved by: Brett Zamir (http://brett-zamir.me)
        // improved by: Brett Zamir (http://brett-zamir.me)
        // improved by: Theriault
        // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // bugfixed by: Michael White (http://getsprink.com)
        // bugfixed by: Benjamin Lupton
        // bugfixed by: Allan Jensen (http://www.winternet.no)
        // bugfixed by: Howard Yeend
        // bugfixed by: Diogo Resende
        // bugfixed by: Rival
        // bugfixed by: Brett Zamir (http://brett-zamir.me)
        //  revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
        //  revised by: Luke Smith (http://lucassmith.name)
        //    input by: Kheang Hok Chin (http://www.distantia.ca/)
        //    input by: Jay Klehr
        //    input by: Amir Habibi (http://www.residence-mixte.com/)
        //    input by: Amirouche
        //   example 1: number_format(1234.56);
        //   returns 1: '1,235'
        //   example 2: number_format(1234.56, 2, ',', ' ');
        //   returns 2: '1 234,56'
        //   example 3: number_format(1234.5678, 2, '.', '');
        //   returns 3: '1234.57'
        //   example 4: number_format(67, 2, ',', '.');
        //   returns 4: '67,00'
        //   example 5: number_format(1000);
        //   returns 5: '1,000'
        //   example 6: number_format(67.311, 2);
        //   returns 6: '67.31'
        //   example 7: number_format(1000.55, 1);
        //   returns 7: '1,000.6'
        //   example 8: number_format(67000, 5, ',', '.');
        //   returns 8: '67.000,00000'
        //   example 9: number_format(0.9, 0);
        //   returns 9: '1'
        //  example 10: number_format('1.20', 2);
        //  returns 10: '1.20'
        //  example 11: number_format('1.20', 4);
        //  returns 11: '1.2000'
        //  example 12: number_format('1.2000', 3);
        //  returns 12: '1.200'
        //  example 13: number_format('1 000,50', 2, '.', ' ');
        //  returns 13: '100 050.00'
        //  example 14: number_format(1e-8, 8, '.', '');
        //  returns 14: '0.00000001'

        number = (number + '')
                .replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + (Math.round(n * k) / k)
                            .toFixed(prec);
                };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
                .split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '')
                .length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1)
                    .join('0');
        }
        return s.join(dec);
    }

</script>