<div class="row">
    <div class="medium-6 large-8 columns">
        <div class="row">
            <div class="small-12 columns">
                <div class="panel">
                    <h4>Total: <span id="total" class="rata-kanan"><?= $model->total ?></span></h4>
                </div>
            </div>
        </div>
        <?php
        $this->renderPartial('_detail', [
            'akm' => $model,
            'akmDetail' => $akmDetail
        ]);
        ?>
    </div>
    <div class="medium-6 large-4 columns"> 
        <div class="row collapse">
            <div class="small-12 columns" style="text-align: center; background-color: rgba(255, 255, 255, 0.875);">
                <img style=" padding: 10px" src="<?= Yii::app()->theme->baseUrl; ?>/img/logo_akm.png" />
            </div>
        </div>
        <div class="row" style="margin-top: 20px;margin-bottom: 20px">
            <div class="small-12 columns">
                <input id="scan" type="text" placeholder="Scan Barcode"/>
            </div>
        </div>
        <!--<span class="label sc-nomor">Self Checkout # 98374</span>-->
        <div class="panel" style="padding-bottom: 0px">
            <div class="row">
                <div class="small-3 columns">
                    <a href="#" class="large button expand keynum">7</a>
                </div>
                <div class="small-3 columns">
                    <a href="#" class="large button expand keynum">8</a>
                </div>
                <div class="small-3 columns">
                    <a href="#" class="large button expand keynum">9</a>
                </div>
                <div class="small-3 columns">
                    <a href="#" class="alert large button expand keynum">C</a>
                </div>
            </div>
            <div class="row">
                <div class="small-3 columns">
                    <a href="#" class="large button expand keynum">4</a>
                </div>
                <div class="small-3 columns">
                    <a href="#" class="large button expand keynum">5</a>
                </div>
                <div class="small-3 columns">
                    <a href="#" class="large button expand keynum">6</a>
                </div>
                <div class="small-3 columns">
                    <a href="#" class="alert large button expand keynum">DEL</a>
                </div>
            </div>
            <div class="row">
                <div class="small-3 columns">
                    <a href="#" class="large button expand keynum">1</a>
                </div>
                <div class="small-3 columns">
                    <a href="#" class="large button expand keynum">2</a>
                </div>
                <div class="small-3 columns">
                    <a href="#" class="large button expand keynum">3</a>
                </div>
                <div class="small-3 columns">
                    <a href="#" class="disabled secondary large button expand">&nbsp;</a>
                </div>
            </div>
            <div class="row">
                <div class="small-6 columns">
                    <a href="#" class="large button expand keynum">0</a>
                </div>
                <div class="small-6 columns">
                    <a href="#" class="success large button expand keynum" id="enter">ENTER</a>
                </div>
            </div>
        </div>
        <div class="row">            
            <div class="small-6 columns">
                <a href="<?= $this->createUrl('batal', ['id' => $model->id]) ?>" id="tombol-batal" class="warning large button expand"><i class="fa fa-times"></i> BATAL</a>
            </div>
            <div class="small-6 columns">
                <a href="<?= $this->createUrl('selesai', ['id' => $model->id]) ?>" class="success large button expand" id="tombol-selesai"><i class="fa fa-print"></i> SELESAI</a> 
            </div>
        </div>
    </div>
</div>
<script>

    function updateTotal() {
        $("#total").load("<?php echo $this->createUrl('total', array('id' => $model->id)); ?>");
    }

    function kirimBarcode(barcode) {
        var dataUrl = '<?php echo $this->createUrl('tambahbarang', ['id' => $model->id]); ?>';
        var dataKirim = {
            tambah: true,
            barcode: barcode
        };
        $.ajax({
            type: "POST",
            url: dataUrl,
            data: dataKirim,
            dataType: "json",
            success: function (data) {
                if (data.sukses) {
                    $.fn.yiiGridView.update('akm-detail-grid');
                    updateTotal();
                    $("#enter").removeClass('warning');
                    $("#enter").addClass('success');
                    $("#enter").html('ENTER');
                } else {
                    $("#enter").removeClass('success');
                    $("#enter").addClass('warning');
                    $("#enter").html('<i class="fa fa-exclamation-circle"></i> GAGAL');
                    setTimeout(function () {
                        $("#enter").removeClass('warning');
                        $("#enter").addClass('success');
                        $("#enter").html('ENTER');
                    }, 2000);

                }
                $("#enter").removeClass('disable');
                $("#scan").val("");
                $("#scan").focus();
            }
        });
    }

    function deleteBarcode(barcode) {
        var dataKirim = {
            hapus: true,
            barcode: barcode
        };
        $.ajax({
            type: "POST",
            url: 'aksi.php',
            data: dataKirim,
            dataType: "json",
            success: function (data) {
                if (data.sukses) {
                    updateTabelDetail();
                    updateTotal();
                    $("#scan").val("");
                    $("#scan").focus();
                }
            }
        });
    }
    function selesaiTransaksi() {
        $("#tombol-selesai").text('Simpan..');
        var dataKirim = {
            selesai: true
        };
        $.ajax({
            type: "POST",
            url: 'aksi.php',
            data: dataKirim,
            dataType: "json",
            success: function (data) {
                if (data.sukses) {
                    window.location.replace("splash.php?sum=selesai&struk=" + data.strukId);
                }
            }
        });
    }

    $(document).ready(function () {
        $("#scan").val("");
        $("#scan").focus();
    });

    $("#tombol-batal").click(function () {
        $(this).text('Batalkan..');
        window.location = "index.php?sum=batal";
    });

    $("#tombol-selesai").click(function () {
        selesaiTransaksi();
    });

    $("#scan").keyup(function (e) {
        if (e.keyCode === 13) {
            $("#enter").html('Proses..');
            $("#enter").addClass('disable');
            var barcode = $(this).val();
            kirimBarcode(barcode);
        }
        return false;
    });

    $('a.keynum').click(function (e) {
        var nilai = $(e.target).text();
        console.log(nilai);
        var barcode = $("#scan").val();
        if (nilai >= 0) {
            $("#scan").val(barcode + nilai);
        } else if (nilai === 'DEL') {
            $("#scan").val(barcode.substring(0, barcode.length - 1));
        } else if (nilai === 'C') {
            $("#scan").val("");
        } else if (nilai === 'ENTER') {
            $(this).html('Proses..');
            $(this).addClass('disable');
            kirimBarcode(barcode);
        }
        $("#scan").focus();
        return false;
    });

    $(document).on("click", ".button.kecil.hapus", function () {
        var barcode = $(this).attr('id');
        deleteBarcode(barcode);
        return false;
    });

</script>