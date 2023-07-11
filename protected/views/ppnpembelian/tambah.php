<?php
/* @var $this PpnpembelianController */
/* @var $model PembelianPpn */

$this->breadcrumbs = [
    'Pembelian Ppn' => ['index'],
    'Tambah',
];

$this->boxHeader['small']  = 'Tambah';
$this->boxHeader['normal'] = 'Tambah Pembelian Ppn';
?>
<div class="row">
    <div class="small-12 columns">
        <?php $this->renderPartial('_form', [
            'model'          => $model,
            'pembelianModel' => $pembelianModel,
        ]); ?>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <div id="tabel-pembelian" style="display: none">
            <?php $this->renderPartial('_pembelian', ['model' => $pembelianModel]); ?>
        </div>
    </div>
</div>
<script>
    $("#tombol-browse").click(function() {
        $("#tabel-pembelian").slideToggle(500);
        $("input[name='Pembelian[nomor]']").focus();
    });
    $("body").on("click", "a.pilih.pembelian", function() {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: isiPembelian
        });
        return false;
    });

    function isiPembelian(data) {
        console.log(data);
        $("#nomorpembelian").val(data.nomor + " | " + data.profil);
        $("#tabel-pembelian").slideUp(500);
        $("#PembelianPpn_pembelian_id").val(data.id);
        var totalPpn = parseFloat(data.totalPpn);
        var lang = 'id-ID'
        var options = {
            maximumFractionDigits: 2
        }
        // $("#PembelianPpn_total_ppn_hitung").val(totalPpn.toLocaleString(lang, options));
        $("#PembelianPpn_total_ppn_hitung").val(totalPpn);
    }
</script>
<?php

$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    [
        'itemOptions'    => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'          => [
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
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
];
?>