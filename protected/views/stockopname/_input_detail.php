<?php
Yii::app()->clientScript->registerScriptFile($this->createAbsoluteUrl('/js') . '/zxing.js', CClientScript::POS_HEAD);
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
                    ?>
                    <a class="prefix secondary button" onclick="getZxing()"><i class="fa fa-barcode fa-2x"></i></a>
                </div>
                <div class="small-6 columns">
                    <input id="scan" type="text"  placeholder="Scan [B]arcode" accesskey="b" autofocus="autofocus" autocomplete="off"/>
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
                    <input id="namabarang" type="text"  placeholder="[C]ari Barang" accesskey="c"/>
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
            <div class="row collapse">
                <div class="small-4 columns">
                    <span class="prefix huruf"><b>Q</b>ty sebenarnya</span>
                </div>
                <div class="small-4 columns">
                    <input id="qty" type="text" accesskey="q" autocomplete="off"/>
                </div>
                <div class="small-4 columns">
                    <a id="tombol-ok-tambah" href="" class="button postfix">Tambah</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function isiBarangInfo(data) {
        $("#barang-info").show();
        text = data.nama + ' <small>' + data.barcode + '</small><br />';
        text += '<small>Qty</small> ' + data.stok + '  <small>Qty SO</small> ' + data.qtySudahSo;
        text += ' <a href="" onclick="location.reload()"> Kembali </a>';
        $("#barang-info p").html(text);
    }

    function resetInput() {
        $("#barang-info p").html();
        $("#barang-info").hide();
        $("#qty").val('');
        $("#input-qty").hide();
        $("#input-barang").show();
        $("#namabarang").val('');
        $("#scan").val('');
        $("#scan").focus();
    }

    $("#tombol-ok-scan").click(function () {
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
            success: function (data) {
                if (data.sukses) {
                    $("#input-barang").hide();
                    $("#input-qty").show(100, function () {
                        isiBarangInfo(data);
                        $("#qty").focus();
                    });
                }
            }
        });
    }

    $("#scan").keyup(function (e) {
        if (e.keyCode === 13) {
            $("#tombol-ok-scan").click();
        }
        return false;
    });

    $("#form-scan").on('submit', function () {
        return false;
    });

    $("#form-caribarang").on('submit', function () {
        return false;
    });

    $("#qty").keyup(function (e) {
        if (e.keyCode === 13) {
            $("#tombol-ok-tambah").click();
        }
        return false;
    });

    $("#input-qty").on('submit', function () {
        return false;
    });

    $("#tombol-ok-tambah").click(function () {
        var barcode = $("#scan").val();
        var qty = $("#qty").val()
        var datakirim = {
            'tambah': true,
            'barcode': barcode,
            'qty': qty
        };
        var dataurl = "<?php echo $this->createUrl('tambahdetail', array('id' => $model->id)) ?>";

        $.ajax({
            data: datakirim,
            url: dataurl,
            type: "POST",
            success: function (data) {
                if (data.sukses) {
                    $("#so-detail-grid").yiiGridView('update');
                    resetInput();
                }
            }
        });
        return false;
    });

    $("#namabarang").keyup(function (e) {
        if (e.keyCode === 13) {
            $("#tombol-cari").click();
            $("#tombol-cari").focus();
        }
        return false;
    });

    $("#tombol-cari").click(function () {
        var datakirim = {
            'cariBarang': true,
            'namaBarang': $("#namabarang").val()
        };
        $('#barang-grid').yiiGridView('update', {
            data: datakirim
        });
        $("#so-detail").hide(100, function () {
            $("#barang-list").show(100, function () {
                // Dipilih, dipilih, dipilih..
            });

        });
        return false;
    });

<?php /* Proses barcode yang didapat dari scan zxing */ ?>
    function processBarcode(b) {
        $("#scan").val(b);
        kirimBarcode(b);
    }
</script>