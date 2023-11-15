<?php
/* @var $this ReportController */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/responsive-tables.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/responsive-tables.js', CClientScript::POS_HEAD);

$this->breadcrumbs = [
    'Laporan' => ['index'],
    'Kartu Stok',
];

$this->boxHeader['small']  = 'Kartu Stok';
$this->boxHeader['normal'] = '<i class="fa fa-database fa-lg"></i> Laporan Kartu Stok';
?>

<div class="row">
    <div class="large-4 columns" style="padding-left: 0; padding-right: 0">
        <?php
        $this->renderPartial('_form_kartustok', ['model' => $model, 'scanBarcode' => $scanBarcode]);
        ?>
    </div>

    <?php
    if (!empty($report['detail'])) :
    ?>
        <div class="small-12 large-8 columns">
            <h6><?= $model->namaBarang ?> <small><?= $model->barcode ?></small></h6>
            <table class="tabel-index responsive">
                <thead>
                    <tr>
                        <th class="rata-kanan">No</th>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Profil</th>
                        <th>Nomor</th>
                        <th class="rata-kanan">In</th>
                        <th class="rata-kanan">Out</th>
                        <th class="rata-kanan">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $balance  = $report['balance'];
                    $totalIn  = 0;
                    $totalOut = 0;
                    ?>
                    <tr>
                        <td></td>
                        <td colspan="5" style="font-weight: bold">
                            < <?= $model->dari ?></td>
                        <td class="rata-kanan" style="font-weight: bold"><?= number_format($balance, 0, ',', '.') ?></td>
                    </tr>
                    <?php
                    $i = 1;
                    foreach ($report['detail'] as $barisReport) :
                        $in  = in_array($barisReport['kode'], [KodeDokumen::PEMBELIAN, KodeDokumen::RETUR_PENJUALAN]) ? $barisReport['qty'] : 0;
                        $out = in_array($barisReport['kode'], [KodeDokumen::PENJUALAN]) ? $barisReport['qty'] : 0;
                        /* Jika Retur Beli lihat tanda nya */
                        if ($barisReport['kode'] == KodeDokumen::RETUR_PEMBELIAN) {
                            if ($barisReport['qty'] > 0) {
                                $out = $barisReport['qty'];
                            } else {
                                $in = abs($barisReport['qty']);
                            }
                        }
                        /* Jika SO lihat tanda nya */
                        if ($barisReport['kode'] == KodeDokumen::SO) {
                            if ($barisReport['qty'] > 0) {
                                $in = $barisReport['qty'];
                            } else {
                                $out = abs($barisReport['qty']);
                            }
                        }
                        $balance += $in;
                        $balance -= $out;
                        $totalIn += $in;
                        $totalOut += $out;
                    ?>
                        <tr>
                            <td class="rata-kanan"><?= $i ?></td>
                            <td><?= date_format(date_create_from_format('Y-m-d H:i:s', $barisReport['tanggal']), 'd-m-Y H:i:s'); ?></td>
                            <td><?= KodeDokumen::model()->getNamaDokumen($barisReport['kode']); ?> </td>
                            <td><?= $barisReport['profil']; ?></td>
                            <td><?= $barisReport['nomor']; ?></td>
                            <td class="rata-kanan">
                                <?= $in > 0 ? number_format($in, 0, ',', '.') : ''; ?>
                            </td>
                            <td class="rata-kanan">
                                <?= $out > 0 ? number_format($out, 0, ',', '.') : ''; ?>
                            </td>
                            <td class="rata-kanan">
                                <?= number_format($balance, 0, ',', '.') ?>
                            </td>
                        </tr>
                    <?php
                        $i++;
                    endforeach;
                    ?>
                    <tr>
                        <td></td>
                        <td colspan="4" style="font-weight: bold">Total / Balance</td>
                        <td class="rata-kanan" style="font-weight: bold">
                            <?= number_format($totalIn, 0, ',', '.') ?>
                        </td>
                        <td class="rata-kanan" style="font-weight: bold">
                            <?= number_format($totalOut, 0, ',', '.') ?>
                        </td>
                        <td class="rata-kanan" style="font-weight: bold">
                            <?= number_format($balance, 0, ',', '.') ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php
    endif;
    ?>
</div>
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
        $("#ReportKartuStokForm_profilId").val(data.id);
    }

    function isiUser(data) {
        console.log(data);
        $("#user").val(data.namaLengkap + ' (' + data.nama + ')');
        $("#tabel-user").slideUp(500);
        $("#ReportKartuStokForm_userId").val(data.id);
    }

    $("body").on("focusin", "a.pilih", function() {
        $(this).parent('td').parent('tr').addClass('pilih');
    });

    $("body").on("focusout", "a.pilih", function() {
        $(this).parent('td').parent('tr').removeClass('pilih');
    });
</script>