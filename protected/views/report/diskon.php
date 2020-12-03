<?php
/* @var $this ReportController */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);

$this->breadcrumbs = [
    'Laporan' => ['index'],
    'Diskon',
];

$this->boxHeader['small'] = 'Diskon';
$this->boxHeader['normal'] = 'Laporan Diskon';

$this->renderPartial('_form_diskon', ['model' => $model]);
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
if (isset($report['rekap']) && $report['rekap']) {
?>
    <div class="row">
        <div class="small-12 columns rata-kanan">
            <h6>Total : <?= number_format($report['rekap']['total'], 0, ',', '.'); ?></h6>
            <?php
            if (!empty($report['detail'])) :
            ?>
                <h6><?= count($report['detail']) ?> Transaksi</h6>
            <?php
            endif;
            ?>
        </div>
    </div>
<?php
}
if (!empty($report['detail'])) :
?>
    <div class="row">
        <div class="small-6 columns">
            <?php
            $this->renderPartial('_form_diskon_cetak', [
                'model'    => $model,
                'printers' => $printers,
                //'kertasPdf' => $kertasPdf
            ]);
            ?>
        </div>
        <div class="small-12 columns">
            <table class="tabel-index responsive">
                <thead>
                    <tr>
                        <th rowspan="2" class="rata-kanan">No</th>
                        <th rowspan="2">No Penjualan</th>
                        <th rowspan="2">Barcode</th>
                        <th rowspan="2">Nama</th>
                        <th rowspan="2" class="rata-kanan">Harga Normal</th>
                        <th rowspan="2" class="rata-kanan">Harga Jual</th>
                        <th rowspan="2" class="rata-kanan">Qty</th>
                        <th colspan="3" class="rata-tengah">Total</th>
                        <th rowspan="2">Jenis Diskon</th>
                        <!--<th>User</th>-->
                    </tr>
                    <tr>
                        <th class="rata-kanan">Harga Jual</th>
                        <th class="rata-kanan">HPP</th>
                        <th class="rata-kanan">Margin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $tipeDiskon = DiskonBarang::listNamaTipe();
                    $totalPenjualan = 0;
                    $totalHPP = 0;
                    $totalMargin = 0;
                    foreach ($report['detail'] as $barisReport) :
                    ?>
                        <tr>
                            <td class="rata-kanan"><?= $i ?></td>
                            <td><a href="<?= Yii::app()->createUrl('penjualan/view', ['id' => $barisReport['penjualan_id']]); ?>"><?= $barisReport['nomor_penjualan']; ?></a></td>
                            <td><?= $barisReport['barcode']; ?> </td>
                            <td><?= $barisReport['nama']; ?> </td>
                            <td class="rata-kanan"><?= number_format($barisReport['harga_normal'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?= number_format($barisReport['harga_jual'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?= number_format($barisReport['qty'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?= number_format($barisReport['total'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?= number_format($barisReport['hpp'], 0, ',', '.'); ?></td>
                            <?php $margin = $barisReport['total'] - $barisReport['hpp']; ?>
                            <td class="rata-kanan<?= $margin < 0 ? ' angka-negatif' : '' ?>"><?= number_format($margin, 0, ',', '.'); ?></td>
                            <td><?= $barisReport['tipe_diskon_nama'] ?></td>
                        </tr>
                    <?php
                        $totalPenjualan += $barisReport['total'];
                        $totalHPP += $barisReport['hpp'];
                        $i++;
                    endforeach;
                    $totalMargin = $totalPenjualan - $totalHPP;
                    ?>
                    <tr>
                        <td colspan="7" class="rata-tengah text-total">TOTAL</td>
                        <td class="rata-kanan text-total"><?= number_format($totalPenjualan, 0, ',', '.'); ?></td>
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