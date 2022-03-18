<?php
/* @var $this PosController */
/* @var $model So */
/* @var $modelDetail SoDetail */

/* $this->breadcrumbs = array(
'Penjualan' => array('index'),
$model->id => array('view', 'id' => $model->id),
'Ubah',
); */

$this->boxHeader['small']  = 'Pesanan';
$this->boxHeader['normal'] = "Pesanan (Sales Order): {$model->nomorF}";
?>
<div class="row collapse">
    <div class="small-12 columns"">
    <?php $nomor = empty($model->nomorF) ? 'DRAFT' : $model->nomorF; ?>
    <h4><small>Pesanan (Sales Order) :</small> <?= $nomor ?></h4>
    <div class=" row collapse">
        <div class="small-2 medium-1 columns">
            <a class="prefix" href="zxing://scan/?ret=<?= $this->createAbsoluteUrl('pesananubah', ['id' => $model->id, 'barcodescan' => '{CODE}']) ?>"><i class="fa fa-barcode fa-2x"></i></a>
            <!-- <span class="prefix" id="scan-icon"><i class="fa fa-barcode fa-2x"></i></span> -->
        </div>
        <div class="small-6 medium-9 columns">
            <input id="scan" type="text" placeholder="Scan [B]arcode / Input nama" accesskey="b" autofocus="autofocus" />
        </div>
        <div class="small-2 medium-1 columns">
            <a href="#" class="button postfix" id="tombol-tambah-barang"><i class="fa fa-level-down fa-2x fa-rotate-90"></i></a>
        </div>
        <?php
        switch ($tipeCari):
            case Pos::CARI_AUTOCOMPLETE:
        ?>
                <div class="small-2 medium-1 columns">
                    <a href="#" class="success button postfix" id="tombol-cari-barang" accesskey="c"><i class="fa fa-search fa-2x"></i></a>
                </div>
            <?php
                break;

            case Pos::CARI_TABLE:
            ?>
                <div class="small-2 medium-1 columns">
                    <a href="#" class="success button postfix" id="tombol-cari-tabel" accesskey="c"><i class="fa fa-search-plus fa-2x"></i></a>
                </div>
        <?php
                break;
        endswitch;
        ?>
    </div>
    <div id="transaksi">
        <?php
        $this->renderPartial(
            '_pesanan_detail',
            [
                'model'       => $model,
                'modelDetail' => $modelDetail,
            ]
        );
        ?>
    </div>
    <div id="barang-list" style="display:none">
        <?php
        $this->renderPartial(
            '_barang_list',
            [
                'barang' => $barang,
            ]
        );
        ?>
    </div>
</div>
<div style="display: none" id="total-pesanan-h"><?php echo $model->ambilTotal(); ?></div>

<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(
    Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js',
    CClientScript::POS_HEAD
);
?>
<script>
    function kirimBarcode() {
        dataUrl =
            '<?php echo $this->createUrl('pesanantambahbarang', ['id' => $model->id]); ?>';
        dataKirim = {
            barcode: $("#scan").val()
        };
        console.log(dataUrl);
        /* Jika tidak ada barang, keluar! */
        if ($("#scan").val() === '') {
            $("#barang-list:visible").hide(100, function() {
                $("#transaksi").show(100);
            });
            $("#scan").focus();
            return false;
        }

        $.ajax({
            type: 'POST',
            url: dataUrl,
            data: dataKirim,
            success: function(data) {
                if (data.sukses) {
                    $.fn.yiiGridView.update('pesanan-detail-grid');
                    updateTotal();
                } else {
                    $.gritter.add({
                        title: 'Error ' + data.error.code,
                        text: data.error.msg,
                        time: 3000,
                        //class_name: 'gritter-center'
                    });
                }
                $("#scan").val("");
                $("#scan").focus();
                $("#scan").autocomplete("disable");
            }
        });
    }

    $(function() {
        $("#scan").autocomplete("disable");
        $(document).on('click', "#tombol-tambah-barang", function() {
            kirimBarcode();
            return false;
        });
        $(document).on('click', "#tombol-cari-barang", function() {
            $("#scan").autocomplete("enable");
            var nilai = $("#scan").val();
            $("#scan").autocomplete("search", nilai);
            $("#scan").focus();
        });
        $(document).on('click', "#tombol-cari-tabel", function() {
            var datakirim = {
                'cariBarang': true,
                'namaBarang': $("#scan").val(),
                'Barang_page': 1
            };
            $('#barang-grid').yiiGridView('update', {
                data: datakirim
            });
            $("#transaksi").hide(0, function() {
                $("#barang-list").show(100, function() {
                    $("#scan").val("");
                    $("#tombol-cari-tabel").focus();
                });

            });
            return false;
        });
    });

    $("#scan").keydown(function(e) {
        if (e.keyCode === 13) {
            $("#tombol-tambah-barang").click();
        }
    });

    $("#scan").autocomplete({
        source: "<?php echo $this->createUrl('caribarang'); ?>",
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
            .append(item.status == <?= Barang::STATUS_AKTIF ?> ?
                "<a><span class='ac-nama'>" + item.label + "</span> <span class='ac-harga'>" + item.harga +
                "</span> <span class='ac-barcode'><i>" + item.value + "</i></span> <span class='ac-stok'>" + item
                .stok + "</stok></a>" :
                "<span class='ac-nama'><s>" + item.label +
                "</s></span> <span class='ac-harga'>N/A</span> <span class='ac-barcode'><s><i>" + item.value +
                "</i></s></span> <span class='ac-stok'>N/A</stok>")
            .appendTo(ul);
    };

    function updateTotal() {
        var dataurl =
            "<?php echo Yii::app()->createUrl('salesorder/total', ['id' => $model->id]); ?>";
        $.ajax({
            url: dataurl,
            type: "GET",
            success: function(data) {
                if (data.sukses) {
                    $("#total-pesanan-h").text(data.total);
                    $("#total-belanja").text(data.totalF);
                    console.log(data.totalF);
                }
            }
        });
    }

    $("#tombol-simpan").click(function() {
        $(this).unbind("click").html("Proses..").attr("class", "alert bigfont tiny button");

        dataUrl =
            '<?php echo $this->createUrl('pesanansimpan', ['id' => $model->id]); ?>';
        dataKirim = {
            'pesan': true,
        };
        console.log(dataUrl);
        //printWindow = window.open('about:blank', '', 'left=20,top=20,width=400,height=600,toolbar=0,resizable=1');
        $.ajax({
            type: 'POST',
            url: dataUrl,
            data: dataKirim,
            success: function(data) {
                if (data.sukses) {
                    if (data.penjualanId > 0) {
                        window.location.href =
                            "<?= $this->createUrl('ubah', ['id' => '']); ?>" +
                            data.penjualanId;
                    } else {
                        location.reload();
                    }
                } else {
                    $.gritter.add({
                        title: 'Error ' + data.error.code,
                        text: data.error.msg,
                        time: 3000,
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
                $("#scan").val("");
                $("#scan").focus();
            }
        });
        return false;
    });
    <?php
    if (!is_null($scanBarcode)) {
    ?>
        $(function() {
            $("#scan").val("<?= $scanBarcode ?>");
            kirimBarcode("<?= $scanBarcode ?>");
        });
    <?php
    }
    ?>
</script>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    [
        'itemOptions'    => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'          => [
            ['label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                'class'     => 'button',
                'accesskey' => 't',
            ]],
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class'     => 'success button',
                'accesskey' => 'i',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
    [
        'itemOptions'    => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items'          => [
            ['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                'class' => 'button',
            ]],
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
];
