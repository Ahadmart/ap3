<?php
/* @var $this DiskonbarangController */
/* @var $model DiskonBarang */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', [
        'id'                   => 'diskon-barang-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ]);
    ?>

    <?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']); ?>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'tipe_diskon_id'); ?>
            <?php
            echo $form->dropDownList($model, 'tipe_diskon_id', $model->listTipe(), [
                'prompt'    => 'Pilih Satu..',
                'autofocus' => 'autofocus'
            ]);
            ?>
            <?php echo $form->error($model, 'tipe_diskon_id', ['class' => 'error']); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->hiddenField($model, 'barang_id'); ?>
            <?php
            if (!$model->isNewRecord) {
                $hargaJualRaw            = is_null($model->barang) ? 0 : $model->barang->getHargaJualRaw();
                $barangBonusHargaJualRaw = is_null($model->barangBonus) ? 0 : $model->barangBonus->getHargaJualRaw();
            }
            ?>
            <input type="hidden" id="harga-jual-raw" <?php echo $model->isNewRecord ? '' : 'value="' . $hargaJualRaw . '"' ?> />
            <?php echo $form->hiddenField($model, 'barang_bonus_id'); ?>
            <input type="hidden" id="barang-bonus-harga-jual-raw" <?php echo $model->isNewRecord ? '' : 'value="' . $barangBonusHargaJualRaw . '"' ?> />
        </div>
    </div>

    <div class="row" id="cb_semua_barang" style="display: none">
        <div class="small-12 columns">
            <?php echo $form->checkBox($model, 'semua_barang'); ?>
            <?php echo $form->labelEx($model, 'semua_barang'); ?>
        </div>
    </div>
    <div class="row" id="input-perbarang">
        <div class="small-12 columns">
            <label for="scan" class="required">Barang <span class="required">*</span></label>
            <div class="row collapse">
                <div class="small-3 large-2 columns">
                    <span class="prefix" id="scan-icon"><i class="fa fa-barcode fa-2x"></i></span>
                </div>
                <div class="small-6 large-8 columns">
                    <?php
                    $barcode = !is_null($model->barang) ? $model->barang->barcode : ''
                    ?>
                    <input id="scan" type="text" placeholder="Scan [B]arcode / Input nama" accesskey="b" <?php echo $model->isNewRecord ? '' : 'value="' . $barcode . '"' ?> />
                </div>
                <div class="small-3 large-2 columns">
                    <a href="#" class="button postfix" id="tombol-scan-ok"><i class="fa fa-level-down fa-2x fa-rotate-90"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="list-kategori" style="display:none">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'barang_kategori_id'); ?>
            <?php
            echo $form->dropDownList(
                $model,
                'barang_kategori_id',
                CHtml::listData(
                    KategoriBarang::model()->orderByNama()->FindAll(['select' => 'id, nama']),
                    'id',
                    'nama'
                ),
                [
                    'prompt'    => 'Pilih Satu..',
                    'autofocus' => 'autofocus'
                ]
            );
            ?>
            <?php echo $form->error($model, 'barang_kategori_id', ['class' => 'error']); ?>
        </div>
    </div>

    <?php echo $form->hiddenField($model, 'barang_struktur_id') ?>
    <div id="list-struktur" style="display: none">
        <div class="row">
            <div class="small-12 columns">
                <label>Struktur</label>
            </div>
        </div>
        <div class="row">
            <div class="medium-4 columns" id="grid1-container">
                <?php
                $this->renderPartial('_grid1', [
                    'lv1' => $lv1
                ]);
                ?>
            </div>
            <div class="medium-4 columns" id="grid2-container">
                <?php
                $this->renderPartial('_grid2', [
                    'lv2' => $strukturDummy
                ]);
                ?>
            </div>
            <div class="medium-4 columns" id="grid3-container"">
                <?php
                $this->renderPartial('_grid3', [
                    'lv3' => $strukturDummy
                ]);
                ?>  
            </div>
            <!-- <input type=" hidden" id="input-struktur" /> -->
        </div>
        <script>
            function lv1Dipilih(id) {
                var lv1Id = $('#' + id).yiiGridView('getSelection');
                if (!Array.isArray(lv1Id) || !lv1Id.length) {
                    console.log("1 tidak dipilih");
                    <?php /* render nothing */ ?>
                    $("#grid2-container").load("<?= $this->createUrl("renderstrukturgrid") ?>", {
                        level: 2,
                        parent: 0
                    });
                    // $('#input-struktur').val("");
                } else {
                    console.log(lv1Id[0] + ":1 dipilih");
                    $("#grid2-container").load("<?= $this->createUrl("renderstrukturgrid") ?>", {
                        level: 2,
                        parent: lv1Id[0]
                    });
                }
                $("#grid3-container").load("<?= $this->createUrl("renderstrukturgrid") ?>", {
                    level: 3,
                    parent: 0
                });
            }

            function lv2Dipilih(id) {
                var lv2Id = $('#' + id).yiiGridView('getSelection');
                if (!Array.isArray(lv2Id) || !lv2Id.length) {
                    console.log("2 tidak dipilih");
                    <?php /* render nothing */ ?>
                    $("#grid3-container").load("<?= $this->createUrl("renderstrukturgrid") ?>", {
                        level: 3,
                        parent: 0
                    });
                    // $('#input-struktur').val("");
                } else {
                    console.log(lv2Id[0] + ":2 dipilih");
                    $("#grid3-container").load("<?= $this->createUrl("renderstrukturgrid") ?>", {
                        level: 3,
                        parent: lv2Id[0]
                    });
                }
            }

            function lv3Dipilih(id) {
                var lv3Id = $('#' + id).yiiGridView('getSelection');
                if (!Array.isArray(lv3Id) || !lv3Id.length) {
                    console.log("3 tidak dipilih");
                    $('#DiskonBarang_barang_struktur_id').val("");
                } else {
                    console.log(lv3Id[0] + ":3 dipilih");
                    $('#DiskonBarang_barang_struktur_id').val(lv3Id[0]);
                }
            }
        </script>
    </div>
    <div class="row">
        <div class="panel" id="info-barang" style="display: none; padding-bottom: 15px; margin-left: none; margin-right: none">

        </div>
    </div>

    <div class="row">
        <div class="small-6 columns">
            <div class="row-nilai-diskon" id="nominal-diskon" style="display: none">
                <?php echo $form->labelEx($model, 'nominal'); ?>
                <?php echo $form->textField($model, 'nominal', ['size' => 18, 'maxlength' => 18, 'autocomplete' => 'off']); ?>
                <?php echo $form->error($model, 'nominal', ['class' => 'error']); ?>
            </div>
        </div>
        <div class="small-6 columns">
            <div class="row-nilai-diskon" style="display: none">
                <label for="harga_net">Harga Net</label>
                <input size="18" maxlength="18" id="harga_net" type="text" disabled="disabled">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="small-6 columns">
            <div class="row-nilai-diskon" style="display: none">
                <?php echo $form->labelEx($model, 'persen'); ?>
                <?php echo $form->textField($model, 'persen', ['autocomplete' => 'off']); ?>
                <?php echo $form->error($model, 'persen', ['class' => 'error']); ?>
            </div>
        </div>
        <div class="small-6 columns">
            <div id="row-qty" style="display: none">
                <?php echo $form->labelEx($model, 'qty'); ?>
                <?php echo $form->textField($model, 'qty', ['size' => 10, 'maxlength' => 10, 'autocomplete' => 'off']); ?>
                <?php echo $form->error($model, 'qty', ['class' => 'error']); ?>
            </div>
            <div id="row-qty-min" style="display: none">
                <?php echo $form->labelEx($model, 'qty_min'); ?>
                <?php echo $form->textField($model, 'qty_min', ['size' => 10, 'maxlength' => 10, 'autocomplete' => 'off']); ?>
                <?php echo $form->error($model, 'qty_min', ['class' => 'error']); ?>
            </div>
            <div id="row-qty-max" style="display: none">
                <?php echo $form->labelEx($model, 'qty_max'); ?>
                <?php echo $form->textField($model, 'qty_max', ['size' => 10, 'maxlength' => 10, 'autocomplete' => 'off']); ?>
                <?php echo $form->error($model, 'qty_max', ['class' => 'error']); ?>
            </div>
        </div>
    </div>
    <div id="input-barang-bonus" style="display: none">

        <div class="row" id="row-barang-bonus">
            <div class="small-12 columns">
                <label for="scan" class="required">Barang Bonus<span class="required">*</span></label>
                <div class="row collapse">
                    <div class="small-3 large-2 columns">
                        <span class="prefix" id="scan-icon-bonus"><i class="fa fa-barcode fa-2x"></i></span>
                    </div>
                    <div class="small-6 large-8 columns">
                        <?php
                        $barcodeBonus = !is_null($model->barang_bonus_id) ? $model->barangBonus->barcode : ''
                        ?>
                        <input id="scan-barang-bonus" type="text" placeholder="S[c]an Barcode / Input nama" accesskey="c" <?php echo $model->isNewRecord ? '' : 'value="' . $barcodeBonus . '"' ?> />
                    </div>
                    <div class="small-3 large-2 columns">
                        <a href="#" class="button postfix" id="tombol-scan-bonus"><i class="fa fa-level-down fa-2x fa-rotate-90"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="panel" id="info-barang-bonus" style="display: none; padding-bottom: 15px; margin-left: none; margin-right: none">

            </div>
        </div>
        <div class="row">
            <div class="small-6 columns">
                <div class="row-bonus-nilai" id="bonus-nominal-diskon" style="display: none">
                    <?php echo $form->labelEx($model, 'barang_bonus_diskon_nominal'); ?>
                    <?php echo $form->textField($model, 'barang_bonus_diskon_nominal', ['size' => 18, 'maxlength' => 18, 'autocomplete' => 'off']); ?>
                    <?php echo $form->error($model, 'barang_bonus_diskon_nominal', ['class' => 'error']); ?>
                </div>
            </div>
            <div class="small-6 columns">
                <div class="row-bonus-nilai" style="display: none">
                    <label for="bonus_harga_net">Harga Net</label>
                    <input size="18" maxlength="18" id="bonus_harga_net" type="text" disabled="disabled">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="small-6 columns">
                <div class="row-bonus-nilai" style="display: none">
                    <?php echo $form->labelEx($model, 'barang_bonus_diskon_persen'); ?>
                    <?php echo $form->textField($model, 'barang_bonus_diskon_persen', ['autocomplete' => 'off']); ?>
                    <?php echo $form->error($model, 'barang_bonus_diskon_persen', ['class' => 'error']); ?>
                </div>
            </div>
            <div class="small-6 columns">
                <div id="row-qty-bonus">
                    <?php echo $form->labelEx($model, 'barang_bonus_qty'); ?>
                    <?php echo $form->textField($model, 'barang_bonus_qty', ['size' => 10, 'maxlength' => 10, 'autocomplete' => 'off']); ?>
                    <?php echo $form->error($model, 'barang_bonus_qty', ['class' => 'error']); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="small-6 columns">
            <?php echo $form->labelEx($model, 'dari'); ?>
            <?php echo $form->textField($model, 'dari', ['class' => 'tanggal-waktu', 'value' => $model->isNewRecord ? date('d-m-Y H:i') : $model->dari]); ?>
            <?php echo $form->error($model, 'dari', ['class' => 'error']); ?>
        </div>
        <div class="small-6 columns">
            <?php echo $form->labelEx($model, 'sampai'); ?>
            <?php echo $form->textField($model, 'sampai', ['class' => 'tanggal-waktu', 'value' => empty($model->sampai) ? '' : $model->sampai]); ?>
            <?php echo $form->error($model, 'sampai', ['class' => 'error']); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'status'); ?>
            <?php echo $form->dropDownList($model, 'status', $model->listStatus()); ?>
            <?php echo $form->error($model, 'status', ['class' => 'error']); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', ['class' => 'tiny bigfont button']); ?>
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
    $("#DiskonBarang_tipe_diskon_id").change(function() {
        console.log($(this).val());
        var tipeId = $(this).val();
        shField(tipeId);
        $("#scan").focus();
    });

    $("#DiskonBarang_semua_barang").change(function() {
        enDisScan(this.checked);
    });

    function enDisScan(enable) {
        if (enable) {
            $("#input-perbarang").hide(500);
        } else {
            $("#input-perbarang").show(500);
            $("#scan").focus();
        }
    }

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
                promoMemberFields();
                break;
            case '<?= DiskonBarang::TIPE_QTY_GET_BARANG; ?>':
                qtyGetBarangFields();
                break;
            case '<?= DiskonBarang::TIPE_NOMINAL_GET_BARANG; ?>':
                nominalGetBarangFields();
                break;
            case '<?= DiskonBarang::TIPE_PROMO_PERKATEGORI; ?>':
                promoPerKategoriFields();
                break;
            case '<?= DiskonBarang::TIPE_PROMO_PERSTRUKTUR ?>':
                promoPerStrukturFields();
                break;
        }
    }

    function promoFields() {
        $("#list-kategori").hide(500);
        $("#list-struktur").hide(500);
        $("#row-qty").hide(500);
        $("#row-qty-min").hide(500);
        $("#cb_semua_barang").hide(500);
        $("#row-qty-max").show(500);
        $(".row-nilai-diskon").show(500);
        $("#input-barang-bonus").hide(500);
        enDisScan(false);
    }

    function promoPerKategoriFields() {
        $("#row-qty").hide(500);
        $("#row-qty-min").hide(500);
        $("#cb_semua_barang").hide(500);
        $("#row-qty-max").show(500);
        $(".row-nilai-diskon").show(500);
        $("#list-kategori").show(500);
        $("#list-struktur").hide(500);
        $("#input-barang-bonus").hide(500);
        enDisScan(true);
    }

    function promoPerStrukturFields() {
        $("#row-qty").hide(500);
        $("#row-qty-min").hide(500);
        $("#cb_semua_barang").hide(500);
        $("#row-qty-max").show(500);
        $(".row-nilai-diskon").show(500);
        $("#list-kategori").hide(500);
        $("#list-struktur").show(500);
        $("#input-barang-bonus").hide(500);
        enDisScan(true);
    }

    function promoMemberFields() {
        $("#list-kategori").hide(500);
        $("#list-struktur").hide(500);
        $("#row-qty").hide(500);
        $("#row-qty-min").hide(500);
        $("#row-qty-max").show(500);
        $("#cb_semua_barang").show(500);
        $("#DiskonBarang_semua_barang").prop("checked", false);
        $(".row-nilai-diskon").show(500);
        $("#input-barang-bonus").hide(500);
        enDisScan(false);
    }

    function grosirFields() {
        $("#list-kategori").hide(500);
        $("#list-struktur").hide(500);
        $("#row-qty").hide(500);
        $("#cb_semua_barang").hide(500);
        $("#row-qty-max").hide(500);
        $("#row-qty-min").show(500);
        $(".row-nilai-diskon").show(500);
        $("#input-barang-bonus").hide(500);
        enDisScan(false);
    }

    function bandedFields() {
        $("#list-kategori").hide(500);
        $("#list-struktur").hide(500);
        $("#row-qty-min").hide(500);
        $("#row-qty-max").hide(500);
        $("#cb_semua_barang").hide(500);
        $("#row-qty").show(500);
        $(".row-nilai-diskon").show(500);
        $("#input-barang-bonus").hide(500);
        enDisScan(false);
    }

    function qtyGetBarangFields() {
        $("#list-kategori").hide(500);
        $("#list-struktur").hide(500);
        $("#row-qty-min").hide(500);
        $("#row-qty-max").show(500);
        $("#cb_semua_barang").hide(500);
        $("#row-qty").show(500);
        $(".row-nilai-diskon").hide(500);
        $("#input-barang-bonus").show(500);
        $(".row-bonus-nilai").hide(500);
        enDisScan(false);
    }

    function nominalGetBarangFields() {
        $("#list-kategori").hide(500);
        $("#list-struktur").hide(500);
        $("#row-qty-min").hide(500);
        $("#row-qty-max").hide(500);
        $("#row-qty").hide(500);
        $(".row-nilai-diskon").hide(100);
        $("#nominal-diskon").show(400, function() {
            $("#DiskonBarang_nominal").focus();
        });
        $("#cb_semua_barang").show(500);
        $("#DiskonBarang_semua_barang").prop("checked", true);
        $("#input-barang-bonus").show(500);
        $("#row-barang-bonus").show(500);
        $(".row-bonus-nilai").show(500);
        enDisScan(true);
    }

    $("#scan").autocomplete({
        source: "<?php echo $this->createUrl('caribarang'); ?>",
        minLength: 3,
        search: function(event, ui) {
            $("#scan-icon").html('<img src="<?php echo Yii::app()->theme->baseUrl; ?>/css/3.gif" />');
        },
        response: function(event, ui) {
            $("#scan-icon").html('<i class="fa fa-barcode fa-2x"></i>');
        },
        select: function(event, ui) {
            console.log(ui.item ?
                "Nama: " + ui.item.label + "; Barcode " + ui.item.value :
                "Nothing selected, input was " + this.value);
            if (ui.item) {
                $("#scan").val(ui.item.value);
            }
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li>")
            .append("<a>" + item.label + "<br /><small>" + item.value + " [" + item.stok + "][" + item.harga + "]</small></a>")
            .appendTo(ul);
    };

    $("#scan-barang-bonus").autocomplete({
        source: "<?php echo $this->createUrl('caribarang'); ?>",
        minLength: 3,
        search: function(event, ui) {
            $("#scan-icon-bonus").html('<img src="<?php echo Yii::app()->theme->baseUrl; ?>/css/3.gif" />');
        },
        response: function(event, ui) {
            $("#scan-icon-bonus").html('<i class="fa fa-barcode fa-2x"></i>');
        },
        select: function(event, ui) {
            console.log(ui.item ?
                "Nama: " + ui.item.label + "; Barcode " + ui.item.value :
                "Nothing selected, input was " + this.value);
            if (ui.item) {
                $("#scan-barang-bonus").val(ui.item.value);
            }
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
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

    $(function() {
        $(document).on('click', "#tombol-scan-ok", function() {
            dataUrl = '<?php echo $this->createUrl('getdatabarang'); ?>';
            dataKirim = {
                barcode: $("#scan").val()
            };
            console.log(dataUrl);
            /* Jika tidak ada barang, keluar! */
            if ($("#scan").val() === '') {
                return false;
            }

            $.ajax({
                type: 'POST',
                url: dataUrl,
                data: dataKirim,
                success: function(data) {
                    if (data.sukses) {
                        var hasil = "<h5>" + data.barcode +
                            " " + data.nama + "</h5>" +
                            "<h6><small>Stok</small> " + data.stok + " " + data.satuan +
                            " <small>Harga</small> " + data.hargaJual + " / " + data.satuan +
                            " <small>Harga Beli</small> " + data.hargaBeli + "</h6>";
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
                    if ($("#DiskonBarang_nominal").is(":visible")) {
                        $("#DiskonBarang_nominal").focus();
                    } else {
                        $("#DiskonBarang_qty").focus();
                    }
                }
            });
        });
        $(document).on('change', '#DiskonBarang_qty', function() {
            kalkulasiDiskonDariNominal();
            tampilkanHargaBanded();
        });
        $(document).on('click', "#tombol-scan-bonus", function() {
            dataUrl = '<?php echo $this->createUrl('getdatabarang'); ?>';
            dataKirim = {
                barcode: $("#scan-barang-bonus").val()
            };
            console.log(dataUrl);
            /* Jika tidak ada barang, keluar! */
            if ($("#scan-barang-bonus").val() === '') {
                return false;
            }

            $.ajax({
                type: 'POST',
                url: dataUrl,
                data: dataKirim,
                success: function(data) {
                    if (data.sukses) {
                        var hasil = "<h5>" + data.barcode +
                            " " + data.nama + "</h5>" +
                            "<h6><small>Stok</small> " + data.stok + " " + data.satuan +
                            " <small>Harga</small> " + data.hargaJual + " / " + data.satuan +
                            " <small>Harga Beli</small> " + data.hargaBeli + "</h6>";
                        $("#info-barang-bonus").html(hasil);
                        $("#info-barang-bonus").show();
                        $("#barang-bonus-harga-jual-raw").val(data.hargaJualRaw);
                        $("#DiskonBarang_barang_bonus_id").val(data.barangId);
                    } else {
                        $.gritter.add({
                            title: 'Error ' + data.error.code,
                            text: data.error.msg,
                            time: 3000,
                            //class_name: 'gritter-center'
                        });
                    }
                    if ($("#DiskonBarang_barang_bonus_diskon_nominal").is(":visible")) {
                        $("#DiskonBarang_barang_bonus_diskon_nominal").focus();
                    }
                }
            });
        });
    });

    $("#scan").on("keydown", function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            $("#tombol-scan-ok").click();
        }
    });

    $("#scan-barang-bonus").on("keydown", function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            $("#tombol-scan-bonus").click();
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

    $("#DiskonBarang_nominal").change(function() {
        kalkulasiDiskonDariNominal();
        tampilkanHargaBanded(); // Jalan jika qty > 0
    });

    $("#DiskonBarang_persen").change(function() {
        kalkulasiDiskonDariPersen();
        tampilkanHargaBanded(); // Jalan jika qty > 0
    });

    function bonusKalkulasiDiskonDariNominal() {
        var hargaNet = $("#barang-bonus-harga-jual-raw").val() - $("#DiskonBarang_barang_bonus_diskon_nominal").val();
        var persen = $("#DiskonBarang_barang_bonus_diskon_nominal").val() / $("#barang-bonus-harga-jual-raw").val() * 100;
        $("#bonus_harga_net").val(number_format(hargaNet, 2, ',', '.'));
        $("#DiskonBarang_barang_bonus_diskon_persen").val(persen);
    }

    function bonusKalkulasiDiskonDariPersen() {
        var hargaNet = $("#barang-bonus-harga-jual-raw").val() - $("#DiskonBarang_barang_bonus_diskon_persen").val() / 100 * $("#barang-bonus-harga-jual-raw").val();
        var nominal = $("#DiskonBarang_barang_bonus_diskon_persen").val() / 100 * $("#barang-bonus-harga-jual-raw").val();
        $("#bonus_harga_net").val(number_format(hargaNet, 2, ',', '.'));
        $("#DiskonBarang_barang_bonus_diskon_nominal").val(nominal);
    }

    $("#DiskonBarang_barang_bonus_diskon_nominal").change(function() {
        bonusKalkulasiDiskonDariNominal();
    });

    $("#DiskonBarang_barang_bonus_diskon_persen").change(function() {
        bonusKalkulasiDiskonDariPersen();
    });

    $(function() {
        shField($("#DiskonBarang_tipe_diskon_id").val());
        <?php
        if (!$model->isNewRecord) {
        ?>
            enDisScan(<?php echo $model->semua_barang; ?>);
            <?php
            if ($model->nominal > 0) {
            ?>
                kalkulasiDiskonDariNominal();
            <?php
            } else {
            ?>
                kalkulasiDiskonDariPersen();
            <?php
            }
            ?>
            tampilkanHargaBanded(); // Jalan jika qty > 0
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
            toFixedFix = function(n, prec) {
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