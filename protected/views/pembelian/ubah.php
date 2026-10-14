<?php

/**
 * @var PembelianController  $this
 * @var Pembelian $model
 * @var boolean $pilihBarang
 * @var string $barangBarcode
 * @var string $barangNama
 * @var PembelianDetail $pembelianDetail
 * @var integer $pembulatan
 * @var Barang $barangList
 * @var integer $tipeCari
 * @var CDbCriteria $curSupplierCr
 * @var StrukturBarang $lv1
 * @var StrukturBarang $strukturDummy
 * @var integer @warningHbBerubah
 */
$this->breadcrumbs = [
    'Pembelian' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Ubah',
];

$this->boxHeader['small'] = 'Ubah';
$this->boxHeader['normal'] = "Pembelian: {$model->nomor}";
?>
<div id="konfirmasi-simpan" class="medium reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true"
    role="dialog">
    <?= CHtml::link('&#215;', '', ['class' => 'close-reveal-modal', 'aria-label' => 'Close']) ?>
    <h6>Checking..</h6>
    <br />
    <table id="tabel-konfirmasi" class="tabel-index">
    </table>
    <div class="row">
        <div class="small-12 columns rata-kanan">
            <?= CHtml::link('Batal', '#', ['class' => 'tiny bigfont warning button kanan', 'id' => 'tombol-konfirmasi-batal']) ?>
            <?= CHtml::link('Simpan', '#', ['class' => 'tiny bigfont button kanan', 'id' => 'tombol-konfirmasi-simpan']) ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="medium-5 large-5 columns">
        <a id="tombol-simpan" class="tiny bigfont button" accesskey="s"><i class="fa fa-floppy-o"></i> <span class="ak">S</span>impan Pembelian</a>
        <?php
        echo CHtml::link('<i class="fa fa-times"></i> Bata<span class="ak">l</a>', $this->createUrl('hapus', ['id' => $model->id]), [
            'class' => 'alert tiny bigfont button',
            'accesskey' => 'l',
        ]);
        ?>
    </div>
    <div class="medium-7 large-7 columns header" style="text-align: right">
        <span class="secondary label">Supplier</span><span class="label"><?php echo $model->profil->nama; ?></span>
        <span class="secondary label">Reff</span><span class="label"><?php echo empty($model->referensi) ? '-' : $model->referensi; ?></span><span class="success label"><?php echo empty($model->tanggal_referensi) ? '-' : $model->tanggal_referensi; ?></span>
        <br />
        <span class="secondary label label-total">Total</span><span class="label label-total" id="total-pembelian"><?php echo $model->total; ?></span>
    </div>
</div>
<div class="row">
    <?php
    if ($pilihBarang) {
        $this->renderPartial('_pilih_barang', [
            'pembelianModel' => $model,
            'barangBarcode' => $barangBarcode,
            'barangNama' => $barangNama,
            'barang' => $barang,
            'pembulatan' => $pembulatan,
            'barangList' => $barangList,
            'curSupplierCr' => $curSupplierCr,
            'tipeCari' => $tipeCari,
            'lv1' => $lv1,
            'strukturDummy' => $strukturDummy,
        ]);
    }
    ?>
    <div class="small-12 columns">
        <span class="margin-min label" style="font-weight: bold">MARGIN ≤ 0</span>&nbsp;&nbsp;
        <span class="beli-berubah label" style="font-weight: bold">BELI NAIK - JUAL TETAP</span>&nbsp;&nbsp;
        <span class="barang-baru label" style="font-weight: bold">BARU</span>&nbsp;&nbsp;
        <span class="not-aktif label" style="font-weight: bold">TIDAK AKTIF</span>
    </div>
</div>

<div class="row">
    <?php
    $this->renderPartial('_detail', [
        'pembelian' => $model,
        'pembelianDetail' => $pembelianDetail,
        'pilihBarang' => $pilihBarang,
        'tipeCari' => $tipeCari
    ]);
    ?>
</div>
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js', CClientScript::POS_HEAD);
?>
<script>
    function updateTotal() {
        var dataurl = "<?php echo $this->createUrl('total', ['id' => $model->id]); ?>";
        $.ajax({
            url: dataurl,
            type: "GET",
            success: function(data) {
                if (data.sukses) {
                    $("#total-pembelian").text(data.totalF);
                    console.log(data.totalF);
                }
            }
        });
    } $(document).on('opened.fndtn.reveal', '#konfirmasi-simpan', function() {
        $('#tombol-konfirmasi-batal').focus();
    });

    $("#tombol-simpan").on("click", function() {
        <?php
        switch ($warningHbBerubah) {
            case Pembelian::HB_BERUBAH_WARNING || Pembelian::HB_BERUBAH_BLOKIR:
                ?>
                var hby_hjn = $("#pembelian-detail-grid tr.hb-y-hj-n");
                if (hby_hjn.length > 0) {
                    $("#konfirmasi-simpan h6").text(
                        "Barang ini mengalami kenaikan harga beli, tapi harga jual belum disesuaikan");
                    var target = $("#tabel-konfirmasi");
                    target.empty();
                    target.append(
                        "<thead><tr><th>Barcode</th><th>Nama</th><th class='rata-kanan'>Harga Beli</th><th class='rata-kanan'>Harga Jual</th></tr></thead>");
                    hby_hjn.each(function(i, tr) {
                        var cols = $(tr).children(
                            'td:nth-child(1), td:nth-child(2), td:nth-child(4), td:nth-child(5)'
                        );

                        var newRow = $('<tr></tr>')
                            .append(cols.clone()) // clone so source table keeps its cells
                            .append($('<td></td>'));

                        target.append(newRow);
                    })
                    $('#konfirmasi-simpan').foundation('reveal', 'open');
                    setTimeout(function() {
                        $('#tombol-konfirmasi-batal').focus();
                    }, 500);
                } else {
                    simpan();
                }
            <?php
            break;
        default:
            ?>
                simpan();
        <?php
                break;
        }
        ?>
    })

    $("#tombol-konfirmasi-batal").on("click", function() {
        $('#konfirmasi-simpan').foundation('reveal', 'close');
    })

    function simpan() {
        var dataKirim = {
            'simpan': true,
        };
        $.ajax({
            type: "POST",
            url: '<?php echo $this->createUrl('simpanpembelian', ['id' => $model->id]); ?>',
            data: dataKirim,
            dataType: "json",
            beforeSend: function() {
                $("#tombol-simpan").addClass("warning");
                $("#tombol-simpan").html(
                    "<i class=\"fa fa-floppy-o fa-spin\"></i> <span class=\"ak\">S</span>impan Pembelian"
                );
            },
            complete: function() {
                $("#tombol-simpan").removeClass("warning");
            },
            success: function(data) {
                if (data.sukses) {
                    location.reload();
                } else {
                    $.gritter.add({
                        title: 'Error ' + data.error.code,
                        text: data.error.msg,
                        time: 5000,
                    });
                    $('#konfirmasi-simpan').foundation('reveal', 'close');
                    $("#tombol-simpan").html(
                        "<i class=\"fa fa-floppy-o\"></i> <span class=\"ak\">S</span>impan Pembelian"
                    );
                }
            }
        });
    }

    $("#tombol-konfirmasi-simpan").on("click", function() {
        simpan();
    })
</script>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    [
        'itemOptions' => ['class' => 'has-form hide-for-small-only'],
        'label' => false,
        'items' => [
            ['label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                'class' => 'button',
                'accesskey' => 't',
            ]],
            ['label' => '<i class="fa fa-times"></i> <span class="ak">H</span>apus', 'url' => $this->createUrl('hapus', ['id' => $model->id]), 'linkOptions' => [
                'class' => 'alert button',
                'accesskey' => 'h',
                'submit' => ['hapus', 'id' => $model->id],
                'confirm' => 'Anda yakin?',
            ]],
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
                'accesskey' => 'i',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
    [
        'itemOptions' => ['class' => 'has-form show-for-small-only'],
        'label' => false,
        'items' => [
            ['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                'class' => 'button',
            ]],
            ['label' => '<i class="fa fa-times"></i>', 'url' => $this->createUrl('hapus', ['id' => $model->id]), 'linkOptions' => [
                'class' => 'alert button',
                'submit' => ['hapus', 'id' => $model->id],
                'confirm' => 'Anda yakin?',
            ]],
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
];
?>