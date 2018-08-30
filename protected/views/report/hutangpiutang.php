<?php
/* @var $this ReportController */

$this->breadcrumbs = array(
    'Laporan' => array('index'),
    'Hutang Piutang',
);

$this->boxHeader['small']  = 'Hutang Piutang';
$this->boxHeader['normal'] = '<i class="fa fa-database fa-lg"></i> Laporan Hutang Piutang';

$this->renderPartial('_form_hutangpiutang', array('model' => $model));
?>

<div class="row">
    <div class="small-12 columns">
        <div id="tabel-profil" style="display: none">
            <?php $this->renderPartial('_profil', array('profil' => $profil)); ?>
        </div>
    </div>
</div>
<?php
if (isset($report)):
    ?>
    <div class="row">
        <div class="small-12 columns">
            <hr>
        </div>
    </div>
    <?php
    $this->renderPartial('_form_hutangpiutang_cetak', array(
        'model'     => $model,
        'printers'  => $printers,
        'kertasPdf' => $kertasPdf
    ));
    ?>
    <div class="row">
        <div class="small-12 columns">
            <?php
            if (!empty($report['rekapHutang'])) {
                ?>
                <h3>Hutang</h3>
                <h4><small>Total</small> <?= number_format($report['rekapHutang']['jumlah'], 0, ',', '.'); ?> <small>Bayar</small> <?= number_format($report['rekapHutang']['jumlah_bayar'], 0, ',', '.'); ?> <small>Sisa</small> <?= number_format($report['rekapHutang']['jumlah'] - $report['rekapHutang']['jumlah_bayar'], 0, ',', '.'); ?></h4>
                <?php
            }
            ?>
            <?php
            if (!empty($report['dataHutang'])):
                ?>

                <table class="tabel-index responsive">
                    <thead>
                        <tr>
                            <th class="rata-kanan">No</th>
                            <th>No H/P</th>
                            <th>Tanggal</th>
                            <th>Dokumen Asal</th>
                            <th>No Dokumen</th>
                            <th>No Ref</th>
                            <th class="rata-kanan">Jumlah</th>
                            <th class="rata-kanan">Bayar</th>
                            <th class="rata-kanan">Sisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //print_r($report);

                        $i = 1;
                        foreach ($report['dataHutang'] as $baris):
                            ?>
                            <tr>
                                <td class="rata-kanan"><?php echo $i; ?></td>
                                <td><?php echo $baris['nomor']; ?></td>
                                <td><?php echo $baris['created_at']; ?></td>
                                <td><?php echo $listAsalHP[$baris['asal']]; ?></td>
                                <td><?php echo $baris['nomor_dokumen_asal']; ?></td>
                                <?php
                                if ($baris['asal'] == HutangPiutang::DARI_PEMBELIAN) {
                                    $dokAsal = Pembelian::model()->find("hutang_piutang_id={$baris['id']}");
                                }
                                if ($baris['asal'] == HutangPiutang::DARI_RETUR_JUAL) {
                                    $dokAsal = ReturPenjualan::model()->find("hutang_piutang_id={$baris['id']}");
                                }
                                ?>
                                <td><?php echo $dokAsal->referensi; ?></td>
                                <td class="rata-kanan"><?php echo number_format($baris['jumlah'], 0, ',', '.'); ?></td>
                                <td class="rata-kanan"><?php echo number_format($baris['jumlah_bayar'], 0, ',', '.'); ?></td>
                                <td class="rata-kanan"><?php echo number_format($baris['jumlah'] - $baris['jumlah_bayar'], 0, ',', '.'); ?></td>
                            </tr>
                            <?php
                            $i++;
                        endforeach;
                        ?>
                    </tbody>
                </table>
                <?php
            endif;
            ?>
        </div>
    </div><div class="row">
        <div class="small-12 columns">
            <?php
            if (!empty($report['rekapPiutang'])) {
                ?>
                <h3>Piutang</h3>
                <h4><small>Total</small> <?= number_format($report['rekapPiutang']['jumlah'], 0, ',', '.'); ?> <small>Bayar</small> <?= number_format($report['rekapPiutang']['jumlah_bayar'], 0, ',', '.'); ?> <small>Sisa</small> <?= number_format($report['rekapPiutang']['jumlah'] - $report['rekapPiutang']['jumlah_bayar'], 0, ',', '.'); ?></h4>
                <?php
            }
            ?>
            <?php
            if (!empty($report['dataPiutang'])):
                ?>
                <table class="tabel-index responsive">
                    <thead>
                        <tr>
                            <th class="rata-kanan">No</th>
                            <th>No H/P</th>
                            <th>Tanggal</th>
                            <th>Dokumen Asal</th>
                            <th>No Dokumen</th>
                            <th>No Ref</th>
                            <th class="rata-kanan">Jumlah</th>
                            <th class="rata-kanan">Bayar</th>
                            <th class="rata-kanan">Sisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //print_r($report);

                        $i = 1;
                        foreach ($report['dataPiutang'] as $baris):
                            ?>
                            <tr>
                                <td class="rata-kanan"><?php echo $i; ?></td>
                                <td><?php echo $baris['nomor']; ?></td>
                                <td><?php echo $baris['created_at']; ?></td>
                                <td><?php echo $listAsalHP[$baris['asal']]; ?></td>
                                <td><?php echo $baris['nomor_dokumen_asal']; ?></td>
                                <?php
                                if ($baris['asal'] == HutangPiutang::DARI_PENJUALAN) {
                                    $dokAsal = Penjualan::model()->find("hutang_piutang_id={$baris['id']}");
                                }
                                if ($baris['asal'] == HutangPiutang::DARI_RETUR_BELI) {
                                    $dokAsal = ReturPembelian::model()->find("hutang_piutang_id={$baris['id']}");
                                }
                                ?>
                                <td><?php echo!empty($dokAsal->referensi) ? $dokAsal->referensi : ''; ?></td>
                                <td class="rata-kanan"><?php echo number_format($baris['jumlah'], 0, ',', '.'); ?></td>
                                <td class="rata-kanan"><?php echo number_format($baris['jumlah_bayar'], 0, ',', '.'); ?></td>
                                <td class="rata-kanan"><?php echo number_format($baris['jumlah'] - $baris['jumlah_bayar'], 0, ',', '.'); ?></td>
                            </tr>
                            <?php
                            $i++;
                        endforeach;
                        ?>
                    </tbody>
                </table>
                <?php
            endif;
            ?>
        </div>
    </div>
    <?php
endif;
?>
<script>

    $("#tombol-browse-profil").click(function () {
        $("#tabel-profil").slideToggle(500);
        $("input[name='Profil[nama]']").focus();
    });

    $("body").on("click", "a.pilih.profil", function () {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: isiProfil
        });
        return false;
    });

    function isiProfil(data) {
        console.log(data);
        $("#profil").val(data.nama);
        $("#tabel-profil").slideUp(500);
        $("#ReportHutangPiutangForm_profilId").val(data.id);
        $("#ReportHutangPiutangForm_showDetail").focus();
    }

    $("body").on("focusin", "a.pilih", function () {
        $(this).parent('td').parent('tr').addClass('pilih');
    });

    $("body").on("focusout", "a.pilih", function () {
        $(this).parent('td').parent('tr').removeClass('pilih');
    });
</script>