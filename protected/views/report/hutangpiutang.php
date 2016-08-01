<?php
/* @var $this ReportController */

$this->breadcrumbs = array(
    'Laporan' => array('index'),
    'Hutang Piutang',
);

$this->boxHeader['small'] = 'Hutang Piutang';
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
    /*
      $this->renderPartial('_form_hutangpiutang_cetak', array(
      'model' => $model,
      'kertasPdf' => $kertasPdf
      ));
     */
    ?>
    <div class="row">
        <div class="small-12 columns">
            <h3>Hutang</h3>
            <h4><small>Total</small> <?= number_format($report['rekapHutang']['jumlah'], 0, ',', '.'); ?> <small>Bayar</small> <?= number_format($report['rekapHutang']['jumlah_bayar'], 0, ',', '.'); ?> <small>Sisa</small> <?= number_format($report['rekapHutang']['jumlah'] - $report['rekapHutang']['jumlah_bayar'], 0, ',', '.'); ?></h4>
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
            <h3>Piutang</h3>
            <h4><small>Total</small> <?= number_format($report['rekapPiutang']['jumlah'], 0, ',', '.'); ?> <small>Bayar</small> <?= number_format($report['rekapPiutang']['jumlah_bayar'], 0, ',', '.'); ?> <small>Sisa</small> <?= number_format($report['rekapPiutang']['jumlah'] - $report['rekapPiutang']['jumlah_bayar'], 0, ',', '.'); ?></h4>
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