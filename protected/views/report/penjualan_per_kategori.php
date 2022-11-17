<?php
/* @var $this ReportController */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);

$this->breadcrumbs = [
    'Laporan' => ['index'],
    'Penjualan',
];

$this->boxHeader['small']  = 'Penjualan per Kategori';
$this->boxHeader['normal'] = '<i class="fa fa-file-text fa-lg"></i> Laporan Penjualan per Item per Kategori';

$this->renderPartial('_form_penjualan_per_kategori', ['model' => $model]);
?>
<div class="row">
    <div class="small-12 columns">
        <div id="tabel-profil" style="display: none">
            <?php $this->renderPartial('_profil', ['profil' => $profil]); ?>
        </div>
        <div id="tabel-user" style="display: none">
            <?php $this->renderPartial('_user', ['user' => $user]); ?>
        </div>
    </div>
</div>
<?php
if ($pesan1) {
?>
    <div class="row">
        <div class="small-12 columns">
            <div data-alert="" class="alert-box radius">
                <span>Sebagian penjualan tidak ditampakkan. Tutup akun kasir yang masih aktif untuk menampakkan seluruh penjualan</span>
                <a href="#" class="close button">×</a>
            </div>
        </div>
    </div>
<?php
}
?>
<?php
/*
if (isset($report['rekap']) && $report['rekap']) {
    ?>
    <div class="row">
        <div class="small-6 columns">
            <?php
            $this->renderPartial('_form_penjualan_cetak', [
                'model'    => $model,
                'printers' => $printers,
                    //'kertasPdf' => $kertasPdf
            ]);
            ?>
        </div>
        <div class="small-6 columns rata-kanan">
            <h6>Total : <?php echo number_format($report['rekap']['total'], 0, ',', '.'); ?></h6>
            <h6>Margin : <?php echo number_format($report['rekap']['margin'], 0, ',', '.'); ?></h6>
            <?php if ($report['rekap']['total'] != 0) {
                ?>
                <h6>Profit Margin: <?php echo number_format($report['rekap']['margin'] / $report['rekap']['total'] * 100, 2, ',', '.'); ?>%</h6>
                <?php }
            ?>
            <?php
            if (!empty($report['detail'])):
                ?>
                <h6><?= count($report['detail']) ?> Transaksi</h6>
                <?php endif;
            ?>
        </div>
    </div>
    <?php
}
if (!empty($report['detail'])):
    ?>
    <div class="row">
        <div class="small-12 columns">
            <table class="tabel-index responsive">
                <thead>
                    <tr>
                        <th class="rata-kanan">No</th>
                        <th>Kategori</th>
                        <th>Barcode</th>
                        <th>Nama</th>
                        <th class="rata-kanan">Satuan</th>
                        <th class="rata-kanan">Qty Penjualan</th>
                        <th class="rata-kanan">Penjualan</th>
                        <th class="rata-kanan">Pembelian</th>
                        <th class="rata-kanan">Margin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($report['detail'] as $barisReport):
                        ?>
                        <tr>
                            <td class="rata-kanan"><?= $i ?></td>
                            <td><?= $barisReport['kategori']; ?></td>
                            <td><?= $barisReport['barcode']; ?> </td>
                            <td><?= $barisReport['nama']; ?> </td>
                            <td><?= $barisReport['satuan']; ?> </td>
                            <td class="rata-kanan"><?= number_format($barisReport['qty_penjualan'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?= number_format($barisReport['penjualan'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?= number_format($barisReport['pembelian'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?= number_format($barisReport['margin'], 0, ',', '.'); ?></td>
                        </tr>
                        <?php
                        $i++;
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
endif;
 * 
 */
?>
<script>
    $("#tombol-browse-profil").click(function() {
        $("#tabel-profil").slideToggle(500);
        $("input[name='Profil[nama]']").focus();
    });

    $("#tombol-browse-user").click(function() {
        $("#tabel-user").slideToggle(500);
        $("input[name='User[nama_lengkap]']").focus();
    });

    $("body").on("click", "a.pilih.profil", function() {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: isiProfil
        });
        return false;
    });

    $("body").on("click", "a.pilih.user", function() {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: isiUser
        });
        return false;
    });

    function isiProfil(data) {
        console.log(data);
        $("#profil").val(data.nama);
        $("#tabel-profil").slideUp(500);
        $("#ReportPenjualanPerKategoriForm_profilId").val(data.id);
    }

    function isiUser(data) {
        console.log(data);
        $("#user").val(data.namaLengkap + ' (' + data.nama + ')');
        $("#tabel-user").slideUp(500);
        $("#ReportPenjualanPerKategoriForm_userId").val(data.id);
    }

    $("body").on("focusin", "a.pilih", function() {
        $(this).parent('td').parent('tr').addClass('pilih');
    });

    $("body").on("focusout", "a.pilih", function() {
        $(this).parent('td').parent('tr').removeClass('pilih');
    });
</script>