<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js', CClientScript::POS_HEAD);
?>
<div class="medium-4 large-3 columns">
    <form>
        <div class="row collapse">
            <div class="small-3 large-2 columns">
                <span class="prefix"><i class="fa fa-barcode fa-2x"></i></span>
            </div>
            <div class="small-9 large-10 columns">
                <input id="scan" type="text"  placeholder="Scan [B]arcode" accesskey="b" autofocus="autofocus" autocomplete="off"/>
            </div>
        </div>
</div>
<div class="medium-4 large-2 columns">
    <div class="row collapse">
        <div class="small-3 large-3 columns">
            <span class="prefix huruf"><b>Q</b>ty</span>
        </div>
        <div class="small-6 large-4 columns">
            <input id="qty" type="text"  value="1" placeholder="[Q]ty" accesskey="q" autocomplete="off"/>
        </div>
        <div class="small-3 large-5 columns">
            <a id="tombol-tambah" href="#" class="button postfix">Tambah</a>
        </div>
    </div>
</form>
</div>
<div class="medium-4 column">
    <div class="row collapse">
        <div class="small-3 large-2 columns">
            <span class="prefix"><i class="fa fa-search fa-2x"></i></span>
        </div>
        <div class="small-6 large-6 columns">
            <input id="namabarang" type="text"  placeholder="[C]ari Barang" accesskey="c"/>
        </div>
        <div class="small-3 large-4 columns">
            <a href="#" id="cari" class="button postfix">Cari</a>
        </div>
    </div>
</div>
<script>
    $("#tombol-tambah").click(function () {
        var barcode = $("#scan").val();
        var qty = $("#qty").val();
        var datakirim = {
            'tambah_barang': true,
            'barcode': barcode,
            'qty': qty
        };
        var dataurl = "<?php echo $this->createUrl('tambahdetail', array('id' => $penjualan->id)) ?>";

        $.ajax({
            data: datakirim,
            url: dataurl,
            type: "POST",
            success: function (data) {
                if (data.sukses) {
                    $.fn.yiiGridView.update('penjualan-detail-grid');
                } else {
                    $.gritter.add({
                        title: 'Error ' + data.error.code,
                        text: data.error.msg,
                        time: 3000,
                    });
                }
                updateTotal();
            }
        });
    });

    $("#scan").keyup(function (e) {
        if (e.keyCode === 13) {
            $("#qty").focus();
            $("#qty").select();
        }
    });

    $("#qty").keyup(function (e) {
        if (e.keyCode === 13) {
            $("#tombol-tambah").click();
        }
    });
<?php
/*
  $(document).ready(function() {
  $("#scan").focus();
  });
 */
?>
    function updateTotal() {
        var dataurl = "<?php echo $this->createUrl('total', array('id' => $penjualan->id)); ?>";
        $.ajax({
            url: dataurl,
            type: "GET",
            dataType: "json",
            success: function (data) {
                if (data.sukses) {
                    $("#total-penjualan").text(data.totalF);
                    console.log(data.totalF);
                }
            }
        });
        $("#scan").val("");
        $("#scan").focus();
        $("#qty").val("1");
    }

    $("#cari").click(function () {
        var datakirim = {
            'cariBarang': true,
            'namaBarang': $("#namabarang").val()
        };
        $('#barang-grid').yiiGridView('update', {
            data: datakirim
        });
        $("#penjualan-detail").hide(100, function () {
            $("#barang-list").show(100, function () {
                $("#namabarang").val("");
                $("#cari").focus();
            });

        });
        return false;
    });

    $("#namabarang").keyup(function (e) {
        if (e.keyCode === 13) {
            $("#cari").click();
        }
        return false;
    });
</script>

