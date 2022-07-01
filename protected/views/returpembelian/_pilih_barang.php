<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-ui-ac.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/jquery-ui.min-ac.js', CClientScript::POS_HEAD);
?>

<div id="pilih-barang" class="medium-6 large-5 columns">
    <div class="panel">
        <h5>Pilih Barang:</h5>

        <div class="row collapse">
            <div class="small-2 medium-1 columns">
                <!-- <span class="prefix" id="scan-icon"><i class="fa fa-barcode fa-2x"></i></span> -->
                <a class="prefix" href='zxing://scan/?ret=<?= $this->createAbsoluteUrl('ubah', ['id' => $model->id, 'barcodescan' => '{CODE}']) ?>'><i class="fa fa-barcode fa-2x"></i></a>
            </div>
            <div class="small-6 medium-9 columns">
                <input id="scan" type="text" placeholder="Scan [B]arcode / Input nama" accesskey="b" autofocus="autofocus" />
            </div>
            <div class="small-2 medium-1 columns">
                <a href="#" class="success button postfix" id="tombol-cari-barang" accesskey="c"><i class="fa fa-search fa-2x"></i></a>
            </div>
            <div class="small-2 medium-1 columns">
                <a href="#" class="button postfix" id="tombol-pilih"><i class="fa fa-arrow-right fa-2x"></i></a>
            </div>
        </div>

        <?php
        /*

<?php echo CHtml::label('<span class="ak">1</span> Barcode', 'barcode'); ?>
<div class="row collapse">
<div class="medium-10 columns">
<?php echo CHtml::dropDownList('barcode', '', $barangBarcode, array('accesskey' => '1', 'id' => 'barcode-pilih')); ?>
</div>
<div class="medium-2 columns">
<a href="#" id="pilih-barcode" class="button postfix tombol-pilih" accesskey="2"><span class="ak">2</span> Pilih</a>
</div>
</div>
<?php echo CHtml::label('<span class="ak">3</span> Nama', 'nama'); ?>
<div class="row collapse">
<div class="medium-10 columns">
<?php echo CHtml::dropDownList('nama', '', $barangNama, array('accesskey' => '3', 'id' => 'nama-pilih')); ?>
</div>
<div class="medium-2 columns">
<a href="#" id="pilih-nama" class="button postfix tombol-pilih" accesskey="4" ><span class="ak">4</span> Pilih</a>
</div>
</div>
 *
 */
        ?>
    </div>
</div>
<script>
    $("#tombol-pilih").click(function() {
        var barcode = $("#scan").val();
        var datakirim = {
            'barcode': barcode
        };
        $.fn.yiiGridView.update('inventory-balance-grid', {
            type: 'POST',
            data: datakirim,
            success: updateInfo(barcode)
        })
        $("#scan").autocomplete("disable");
        return false;
    });

    function updateInfo(barcode) {
        $("#barang-info").load(
            "<?php echo $this->createUrl('getbaranginfo', ['barcode' => '']) ?>" +
            barcode);
        $("#retur-qty").focus();
    }

    function clearInfo() {
        $("#barang-info").html("");
        $("#retur-qty").val("");
    }

    $(function() {
        $("#scan").autocomplete("disable");

        $(document).on('click', "#tombol-cari-barang", function() {
            $("#scan").autocomplete("enable");
            var nilai = $("#scan").val();
            $("#scan").autocomplete("search", nilai);
            $("#scan").focus();
        });
    });

    $("#scan").keydown(function(e) {
        if (e.keyCode === 13) {
            $("#tombol-pilih").click();
            return false;
        }
    });

    $("#scan").autocomplete({
        source: "<?php echo $this->createUrl('caribarang', ['profilId' => $pembelianModel->profil_id]); ?>",
        minLength: 3,
        delay: 1000,
        search: function(event, ui) {
            $("#scan-icon").html(
                '<img src="<?php echo Yii::app()->theme->baseUrl; ?>/css/3.gif" />'
            );
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
            .append("<a><span class='ac-nama'>" + item.label + "</span> [<span class='ac-barcode'>" + item.value +
                "</span>]</a>")
            .appendTo(ul);
    };
    <?php
    if (!is_null($scanBarcode)) {
    ?>
        $(function() {
            $("#scan").val("<?= $scanBarcode ?>");
            setTimeout(function() {
                $("#tombol-pilih").click()
            }, 500);
        });
    <?php
    }
    ?>
</script>

<form method="POST">
    <div id="input-ret-inv-balance" class="medium-6 large-7 columns">
        <div class="panel">
            <div class="row small-collapse">
                <div class="small-12 columns">
                    <h4 id="barang-info"></h4>
                    <?php
                    $this->renderPartial('_inventory_balance', [
                        'inventoryBalance' => $inventoryBalance,
                        'model'            => $model,
                    ])
                    ?>
                </div>
            </div>
            <div class="row small-collapse">
                <div class="small-12 medium-6 large-4 columns">
                    <label for="retur-qty">Qty</label>
                    <div class="row collapse">
                        <div class="small-8 columns">
                            <?php echo CHtml::textField('retur-qty', '', ['id' => 'retur-qty']); ?>
                        </div>
                        <div class="small-4 columns">
                            <?php
                            echo CHtml::ajaxSubmitButton('Tambah', $this->createUrl('pilihinv', ['id' => $model->id]), [
                                'success' => "function () {
								clearInfo();
                                $.fn.yiiGridView.update('inventory-balance-grid');
                                $.fn.yiiGridView.update('retur-pembelian-detail-grid');
                                updateTotal();
                                $('#scan').val('');
                                var lebarLayar = $(window).width();
                                if (lebarLayar >= 640){
                                    $('#scan').focus();
                                };
                            }",
                            ], [
                                'class' => 'button postfix',
                                'id'    => 'tombol-tambah',
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>