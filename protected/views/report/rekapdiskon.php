<?php
/* @var $this ReportController */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);

$this->breadcrumbs = [
    'Laporan' => ['index'],
    'Rekap Diskon',
];

$this->boxHeader['small']  = 'Rkp Diskon';
$this->boxHeader['normal'] = 'Laporan Rekap Diskon';

$this->renderPartial('_form_rekap_diskon', ['model' => $model]);

if (isset($report['rekap']) && $report['rekap']) {
?>
    <div class="row">
        <div class="small-12 columns rata-kanan">
            <h6>Total : <?= number_format($report['rekap']['total'], 0, ',', '.'); ?></h6>
        </div>
    </div>
<?php
}
if (!empty($report['detail'])) :
?>
    <div class="row">
        <div class="small-6 columns">
            <?php
            $this->renderPartial('_form_rekap_diskon_cetak', [
                'model'     => $model,
                'printers'  => $printers,
                'kertasPdf' => $kertasPdf,
            ]);
            ?>
        </div>
        <div class="small-12 columns">
            <table class="tabel-index responsive">
                <thead>
                    <tr>
                        <th class="rata-kanan">No</th>
                        <th>Barcode</th>
                        <th>Nama</th>
                        <th class="rata-kanan">Harga Normal</th>
                        <th class="rata-kanan">Harga Jual</th>
                        <th class="rata-kanan">Qty</th>
                        <th class="rata-kanan">HPP</th>
                        <th class="rata-kanan">Margin</th>
                        <th>Jenis Diskon</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i              = 1;
                    $tipeDiskon     = DiskonBarang::listNamaTipe();
                    $totalPenjualan = 0;
                    $totalHPP       = 0;
                    $totalMargin    = 0;
                    foreach ($report['detail'] as $barisReport) :
                    ?>
                        <tr>
                            <td class="rata-kanan"><?= $i ?></td>
                            <td><?= $barisReport['barcode']; ?> </td>
                            <td><?= $barisReport['nama']; ?> </td>
                            <td class="rata-kanan"><?= number_format($barisReport['harga_normal'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?= number_format($barisReport['harga_jual'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?= number_format($barisReport['qty'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?= number_format($barisReport['hpp'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan<?= $barisReport['margin'] < 0 ? ' angka-negatif' : '' ?>"><?= number_format($barisReport['margin'], 0, ',', '.'); ?></td>
                            <td><?= $tipeDiskon[$barisReport['tipe_diskon_id']]; ?> <?= $barisReport['banyak_tipe_diskon_id'] > 1 ? '...' : '' ?> </td> <?php /* FixMe: jika banyak_tipe_diskon_id > 1 : Maka tampilkan semua tipe-tipe diskon nya */ ?>
                        </tr>
                    <?php
                        $totalPenjualan += $barisReport['harga_jual'];
                        $totalHPP += $barisReport['hpp'];
                        $i++;
                    endforeach;
                    $totalMargin = $totalPenjualan - $totalHPP;
                    ?>
                    <tr>
                        <td colspan="4" class="rata-tengah text-total">TOTAL</td>
                        <td class="rata-kanan text-total"><?= number_format($totalPenjualan, 0, ',', '.'); ?></td>
                        <td></td>
                        <td class="rata-kanan text-total"><?= number_format($totalHPP, 0, ',', '.'); ?></td>
                        <td class="rata-kanan text-total<?= $totalMargin < 0 ? ' angka-negatif' : '' ?>"><?= number_format($totalMargin, 0, ',', '.'); ?></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php
endif;
?>
<script>
    $(function() {
        $('.tanggalan').fdatepicker({
            format: 'dd-mm-yyyy',
            language: 'id'
        });
    });

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
        $("#ReportDiskonForm_profilId").val(data.id);
    }

    function isiUser(data) {
        console.log(data);
        $("#user").val(data.namaLengkap + ' (' + data.nama + ')');
        $("#tabel-user").slideUp(500);
        $("#ReportDiskonForm_userId").val(data.id);
    }

    $("body").on("focusin", "a.pilih", function() {
        $(this).parent('td').parent('tr').addClass('pilih');
    });

    $("body").on("focusout", "a.pilih", function() {
        $(this).parent('td').parent('tr').removeClass('pilih');
    });
</script>