<div class="row">
    <div class="medium-7 large-8 columns">
        <div class="row">
            <div class="small-12 columns">
                <div class="panel" style="padding: 0.75rem;">
                    <h4>&nbsp;
                        <span class="" id="view-nama"></span>
                    </h4>
                    <h4>&nbsp;
                        <span class="" id="view-barcode"></span>
                    </h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <div class="panel" style="padding: 0.75rem;">
                    <h1><span class="" id="view-harga"></span></h1>
                </div>
            </div>
        </div>
    </div>
    <div class="medium-5 large-4 columns">
        <!--        <div class="row" style="margin-bottom: 20px">
                    <div class="small-12 columns">
                        <input id="scan" type="text" placeholder="Scan Barcode" autofocus="autofocus" autocomplete="off"/>
                    </div>
                </div>-->

        <div class="row collapse">
            <div class="small-3 medium-2 columns">
                <?php
                if (isset($urlCallback) && !empty($urlCallback)) {
                ?>
                    <a class="prefix" href="zxing://scan/?ret=<?= $urlCallback ?>?barcodescan={CODE}"><i class="fa fa-barcode fa-2x"></i></a>
                <?php
                } else {
                ?>
                    <span class="prefix" id="scan-icon"><i class="fa fa-barcode fa-2x"></i></span>
                <?php
                }
                ?>


            </div>
            <div class="small-9 medium-10 columns">
                <input id="scan" type="text" placeholder="Scan [B]arcode / Input nama" accesskey="b" autofocus="autofocus" />
            </div>
        </div>

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
                <a href="#" class="alert large button expand keynum"><i class="fa fa-arrow-left"></i></a>
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
                <a href="#" class="warning large button expand keynum" id="enter">ENTER</a>
            </div>
        </div>
    </div>
</div>
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-ui-ac.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/jquery-ui.min-ac.js', CClientScript::POS_HEAD);
?>
<script>
    function isiView(data) {
        $("#view-barcode").html(data.barcode);
        $("#view-nama").html(data.nama);
        $("#view-harga").html(data.harga);
        $("#view-harga").append('<span style="float: right"><small>' + data.stok + '</small></span>');
        var i = 0,
            len = data.hj_multi.length;
        while (i < len) {
            $("#view-harga").append('<br /><small>' +
                new Intl.NumberFormat('id-ID').format(data.hj_multi[i].harga * data.hj_multi[i].qty) + ' / ' + data.hj_multi[i].nama_satuan + ' (' + data.hj_multi[i].qty + ' pcs), @' + new Intl.NumberFormat('id-ID').format(data.hj_multi[i].harga) +
                '</small>')
            i++
        }
    }

    function kirimBarcode(barcode) {
        var dataKirim = {
            cekharga: true,
            barcode: barcode
        };
        $.ajax({
            type: "POST",
            url: '<?php echo Yii::app()->controller->createUrl('tools/cekharga/cekbarcode'); ?>',
            data: dataKirim,
            dataType: "json",
            success: function(data) {
                if (data.sukses) {
                    isiView(data);
                    $("#scan").val("");
                    $("#scan").focus();
                }
                $("#enter").html('ENTER');
                $("#enter").removeClass('disable');
            }
        });
    }

    $('a.keynum').click(function(e) {
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

    $("#scan").keyup(function(e) {
        if (e.keyCode === 13) {
            $("#enter").html('Proses..');
            $("#enter").addClass('disable');
            var barcode = $(this).val();
            kirimBarcode(barcode);
        }
        return false;
    });

    $("#scan").autocomplete({
        source: "<?php echo $this->createUrl('/tools/cekharga/caribarang'); ?>",
        minLength: 3,
        delay: 1000,
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
        return $("<li style='clear:both'>")
            .append("<a><span class='ac-nama'>" + item.label + "</span> <span class='ac-barcode'><i>" + item.value + "</i></span> <span class='ac-stok'>" + item.stok + "</stok></a>")
            .appendTo(ul);
    };
    <?php
    if (isset($scanBarcode) && !is_null($scanBarcode)) {
    ?>
        $(function() {
            $("#scan").val("<?= $scanBarcode ?>");
            kirimBarcode("<?= $scanBarcode ?>");
        });
    <?php
    }
    ?>
</script>
<style>
    <?php /* Override Width */ ?>.ac-stok {
        width: 25%;
    }
</style>