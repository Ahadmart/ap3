<?php
/* Tidak perlu pakai ini (zxing.js) jika:
 * Browser di android diset default chrome
 */
//Yii::app()->clientScript->registerScriptFile($this->createAbsoluteUrl('/js') . '/zxing.js', CClientScript::POS_HEAD);
?>

<div class="small-12 columns">
    <div class="panel" id="barang-info" style="display: none; padding-top: 0">
        <!--<div class="row">-->
        <p></p>
        <!--</div>-->
    </div>
</div>
<div id="input-barang">
    <div class="medium-4 large-3 columns">
        <form id="form-scan">
            <div class="row collapse">
                <div class="small-3 columns">
                    <?php
                    /* https://github.com/zxing/zxing/wiki/Scanning-From-Web-Pages */
                    /* http://stackoverflow.com/questions/26356626/using-zxing-barcode-scanner-within-a-web-page */
                    /*
                      <a class="prefix secondary button" onclick="getZxing()"><i class="fa fa-barcode fa-2x"></i></a>
                     */
                    ?>
                    <a class="prefix secondary button" href="zxing://scan/?ret=<?= $this->createAbsoluteUrl('ubah', ['id' => $model->id, 'barcodescan' => '{CODE}']) ?>"><i class="fa fa-barcode fa-2x"></i></a>
                </div>
                <div class="small-6 columns">
                    <input id="scan" type="text" placeholder="Scan [B]arcode" accesskey="b" autofocus="autofocus" autocomplete="off" />
                </div>
                <div class="small-3 columns">
                    <a id="tombol-ok-scan" href="" class="button postfix">OK</a>
                </div>
            </div>
        </form>
    </div>
    <div class="medium-4 column">
        <form id="form-caribarang">
            <div class="row collapse">
                <div class="small-3 large-2 columns">
                    <span class="prefix"><i class="fa fa-search fa-2x"></i></span>
                </div>
                <div class="small-6 large-6 columns">
                    <input id="namabarang" type="text" placeholder="[C]ari Barang" accesskey="c" />
                </div>
                <div class="small-3 large-4 columns">
                    <a href="" id="tombol-cari" class="button postfix">Cari</a>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="input-qty" style="display: none">
    <div class="small-12 medium-6 large-4 columns">
        <form>
            <label for="qty">&nbsp;</label>
            <div class="row collapse">
                <div class="small-4 columns">
                    <span class="prefix huruf"><b>Q</b>ty Asli</span>
                </div>
                <div class="small-4 columns">
                    <input id="qty" type="number" accesskey="q" autocomplete="off" />
                </div>
                <div class="small-4 columns">
                    <a id="tombol-ok-tambah" href="" class="button postfix">Tambah</a>
                </div>
            </div>

        </form>
        <a href="#" class="tiny bigfont button gantiinput">Ganti Input</a> menjadi Selisih
    </div>
    <div class="small-6 large-4 columns">

        <?= CHtml::label('Pindahkan barang ke rak:', 'rak-dropdown') ?>
        <?= CHtml::dropDownList('rak-dropdown', null, CHtml::listData(
            RakBarang::model()->findAll(array('order' => 'nama')),
            'id',
            'nama'
        ), ['empty' => 'Tidak dipindahkan']); ?>

    </div>
    <div class="small-6 large-4 columns">
        <label>&nbsp;</label>
        <?= CHtml::checkBox('set_inaktif', false, ['']) ?>
        <?= CHtml::label('Non aktifkan barang', 'set_inaktif') ?>
    </div>
</div>
<div id="input-selisih" style="display: none">
    <div class="small-12 medium-6 large-4 columns">
        <form>
            <div class="row collapse">
                <div class="small-4 columns">
                    <span class="prefix huruf">Se<b>l</b>isih</span>
                </div>
                <div class="small-4 columns">
                    <input id="selisih" type="number" accesskey="l" autocomplete="off" />
                </div>
                <div class="small-4 columns">
                    <a id="tombol-ok-tambah-s" href="" class="button postfix">Tambah</a>
                </div>
            </div>
        </form>
        <a href="#" class="tiny bigfont button gantiinput">Ganti Input</a> ke Qty Asli
    </div>
</div>

<script>
    function isiBarangInfo(data) {
        $("#barang-info").show();
        text = data.nama + ' <small>' + data.barcode + '</small><br />';
        text += '<small>Qty</small> ' + data.stok;
        if (data.qtyReturBeliPosted > 0) {
            text += '  <small>Stok Retur Beli</small> ' + data.qtyReturBeliPosted;
        }
        text += '  <small>Qty SO</small> ' + data.qtySudahSo;
        text += ' <a href="<?= $this->createUrl('ubah', ['id' => $model->id]) ?>"> Kembali </a>';
        $("#barang-info p").html(text);
    }

    function resetInput() {
        $("#barang-info p").html();
        $("#barang-info").hide();
        $("#qty").val('');
        $("#selisih").val('');
        $("#input-qty").hide();
        $("#input-selisih").hide();
        $("#input-barang").show();
        $("#namabarang").val('');
        $("#set_inaktif").prop('checked', false);
        $("#rak-dropdown").prop('selectedIndex', 0);
        $("#scan").val('');
        $("#scan").focus();
    }

    $("#tombol-ok-scan").click(function() {
        kirimBarcode($("#scan").val());
        return false;
    });

    function kirimBarcode(b) {
        var barcode = b;
        var datakirim = {
            'scan': true,
            'barcode': barcode
        };
        var dataurl = "<?php echo $this->createUrl('scanbarcode', array('id' => $model->id)) ?>";

        $.ajax({
            data: datakirim,
            url: dataurl,
            type: "POST",
            success: function(data) {
                if (data.sukses) {
                    $("#input-barang").hide();
                    if (data.inputselisih == 1) {
                        $("#input-selisih").show(100, function() {
                            isiBarangInfo(data);
                            $("#selisih").focus();
                        });
                    } else {
                        $("#input-qty").show(100, function() {
                            isiBarangInfo(data);
                            $("#qty").focus();
                        });
                    }
                }
            }
        });
    }

    $(".gantiinput").click(function() {
        var datakirim = {
            'gantiinput': true,
        };
        var dataurl = "<?php echo $this->createUrl('gantiinput', array('id' => $model->id)) ?>";
        $.ajax({
            data: datakirim,
            url: dataurl,
            type: "POST",
            success: function(data) {
                if (data.sukses) {
                    $("#input-barang").hide();
                    if (data.inputselisih) {
                        $("#input-qty").hide(100, function() {
                            $("#qty").val('');
                        });
                        $("#input-selisih").show(100, function() {
                            $("#selisih").focus();
                        });
                    } else {
                        $("#input-selisih").hide(100, function() {
                            $("#selisih").val('');
                        });
                        $("#input-qty").show(100, function() {
                            $("#qty").focus();
                        });
                    }
                }
            }
        });
    });

    $("#scan").keyup(function(e) {
        if (e.keyCode === 13) {
            $("#tombol-ok-scan").click();
        }
        return false;
    });

    $("#form-scan").on('submit', function() {
        return false;
    });

    $("#form-caribarang").on('submit', function() {
        return false;
    });

    $("#qty").keyup(function(e) {
        if (e.keyCode === 13) {
            $("#tombol-ok-tambah").click();
        }
        return false;
    });

    $("#selisih").keyup(function(e) {
        if (e.keyCode === 13) {
            $("#tombol-ok-tambah-s").click();
        }
        return false;
    });

    $("#input-qty").on('submit', function() {
        return false;
    });

    $("#input-selisih").on('submit', function() {
        return false;
    });

    $("#tombol-ok-tambah").click(function() {
        var barcode = $("#scan").val();
        var qty = $("#qty").val();
        var rak = $("#rak-dropdown").val();
        var setinaktif = $("#set_inaktif").is(":checked");
        var datakirim = {
            'tambah': true,
            'barcode': barcode,
            'qty': qty,
            'rak': rak,
            'setinaktif': setinaktif
        };
        var dataurl = "<?php echo $this->createUrl('tambahdetail', array('id' => $model->id)) ?>";

        $.ajax({
            data: datakirim,
            url: dataurl,
            type: "POST",
            success: function(data) {
                if (data.sukses) {
                    $("#so-detail-grid").yiiGridView('update');
                    resetInput();
                }
            }
        });
        return false;
    });

    $("#tombol-ok-tambah-s").click(function() {
        var barcode = $("#scan").val();
        var selisih = $("#selisih").val()
        var datakirim = {
            'tambah': true,
            'barcode': barcode,
            'selisih': selisih
        };
        var dataurl = "<?php echo $this->createUrl('tambahdetail', array('id' => $model->id)) ?>";
        $.ajax({
            data: datakirim,
            url: dataurl,
            type: "POST",
            success: function(data) {
                if (data.sukses) {
                    $("#so-detail-grid").yiiGridView('update');
                    resetInput();
                }
            }
        });
        return false;
    });

    $("#namabarang").keyup(function(e) {
        if (e.keyCode === 13) {
            $("#tombol-cari").click();
            $("#tombol-cari").focus();
        }
        return false;
    });

    $("#tombol-cari").click(function() {
        var datakirim = {
            'cariBarang': true,
            'namaBarang': $("#namabarang").val()
        };
        $('#barang-grid').yiiGridView('update', {
            data: datakirim
        });
        $("#so-detail").hide(100, function() {
            $("#barang-list").show(100, function() {
                // Dipilih, dipilih, dipilih..
            });

        });
        return false;
    });

    <?php
    /* Proses barcode yang didapat dari scan zxing */
    /*
  function processBarcode(b) {
  $("#scan").val(b);
  kirimBarcode(b);
  }
 * 
 */
    ?>
    <?php
    if (!is_null($scanBarcode)) {
    ?>
        $(function() {
            $("#scan").val(<?= $scanBarcode ?>);
            kirimBarcode(<?= $scanBarcode ?>);
        });
    <?php
    }
    ?>
</script>