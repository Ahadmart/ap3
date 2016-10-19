<?php
/* @var $this ReportController */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);

$this->breadcrumbs = array(
    'Laporan' => array('index'),
    'Kartu Stok',
);

$this->boxHeader['small'] = 'Kartu Stok';
$this->boxHeader['normal'] = '<i class="fa fa-database fa-lg"></i> Laporan Kartu Stok';

$this->renderPartial('_form_kartustok', array('model' => $model));
?>
<?php
if (!empty($report['detail'])):
    ?>
    <div class="row">
        <div class="small-12 columns">
            <table class="tabel-index responsive">
                <thead>
                    <tr>
                        <th class="rata-kanan">No</th>
                        <th>Tanggal</th>
                        <th>Nomor</th>
                        <th>Tipe</th>
                        <th class="rata-kanan">In</th>
                        <th class="rata-kanan">Out</th>
                        <th class="rata-kanan">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;

                    $balanceIn = 0;
                    $balanceOut = 0;
                    foreach ($report['detail'] as $barisReport):
                        $in = in_array($barisReport['kode'], [KodeDokumen::PEMBELIAN, KodeDokumen::RETUR_PENJUALAN]) ? $barisReport['qty'] : 0;
                        $out = in_array($barisReport['kode'], [KodeDokumen::PENJUALAN, KodeDokumen::RETUR_PEMBELIAN]) ? $barisReport['qty'] : 0;
                        /* Jika SO lihat tanda nya */
                        if ($barisReport['kode'] == KodeDokumen::SO) {
                            if ($barisReport['qty'] > 0) {
                                $in = $barisReport['qty'];
                            } else {
                                $out = abs($barisReport['qty']);
                            }
                        }
                        ?>
                        <tr>
                            <td class="rata-kanan"><?= $i ?></td>
                            <td><?= $barisReport['tanggal']; ?></td>
                            <td><?= $barisReport['nomor']; ?></td>
                            <td><?= $barisReport['kode']; ?> </td>
                            <td class="rata-kanan">
                                <?= $in > 0 ? number_format($in, 0, ',', '.') : ''; ?>
                            </td>
                            <td class="rata-kanan">
                                <?= $out > 0 ? number_format($out, 0, ',', '.') : ''; ?>
                            </td>
                            <td>

                            </td>
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
?>
<script>
    $(function () {
        $('.tanggalan').fdatepicker({
            format: 'dd-mm-yyyy',
            language: 'id'
        });
    });

    $("#tombol-browse-profil").click(function () {
        $("#tabel-profil").slideToggle(500);
        $("input[name='Profil[nama]']").focus();
    });

    $("#tombol-browse-user").click(function () {
        $("#tabel-user").slideToggle(500);
        $("input[name='User[nama_lengkap]']").focus();
    });

    $("body").on("click", "a.pilih.profil", function () {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: isiProfil
        });
        return false;
    });

    $("body").on("click", "a.pilih.user", function () {
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

    $("body").on("focusin", "a.pilih", function () {
        $(this).parent('td').parent('tr').addClass('pilih');
    });

    $("body").on("focusout", "a.pilih", function () {
        $(this).parent('td').parent('tr').removeClass('pilih');
    });
</script>