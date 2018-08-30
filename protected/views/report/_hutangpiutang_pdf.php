<?php

function namaBulan($i)
{
    static $bulan = array(
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember"
    );
    return $bulan[$i - 1];
}

function toIndoDate($timeStamp)
{
    $tanggal = date_format(date_create($timeStamp), 'j');
    $bulan = date_format(date_create($timeStamp), 'n');
    $namabulan = namaBulan($bulan);
    $tahun = date_format(date_create($timeStamp), 'Y');
    return $tanggal . ' ' . $namabulan . ' ' . $tahun;
}

function namaHari($timeStamp)
{
    static $hari = array(
        'Ahad',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu'
    );
    return $hari[date('w', strtotime($timeStamp))];
}
?>
<html>
    <head>
        <title>Hutang Piutang : <?php echo $config['toko.nama'] . ' | ' . $waktuCetak; ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pdf-tabel.css" />
    </head>
    <body>
        <!--mpdf
            <htmlpagefooter name="footer">
                <table style="border-top:thin solid black">
                    <tr>
                        <td style="text-align:left">Hutang Piutang | <?php
        echo $config['toko.nama'];
        ?> | <?php echo $waktuCetak; ?>
                        </td>
                        <td style="text-align:center">
                        </td>
                        <td style="text-align:right">{PAGENO}{nb}
                        </td>
                    </tr>
                 </table>
            </htmlpagefooter>
            <sethtmlpagefooter name="footer" value="on" />
          mpdf-->
        <div id="header1">
            <div>Hutang Piutang <?php echo $config['toko.nama']; ?></div>
            <div id="tanggal"><?php echo namaHari($waktu) . ', ' . toIndoDate($waktu); ?></div>
        </div>
        <br />
        <br />
        Profil: <em><?= $profil->nama; ?></em>
        <?php
        if (!empty($report['rekapHutang'])) {
            ?>
            <br />
            <b>Hutang:</b> Total <b><?= number_format($report['rekapHutang']['jumlah'], 0, ',', '.'); ?></b> Bayar <b><?= number_format($report['rekapHutang']['jumlah_bayar'], 0, ',', '.'); ?></b> Sisa <b><?= number_format($report['rekapHutang']['jumlah'] - $report['rekapHutang']['jumlah_bayar'], 0, ',', '.'); ?></b>
            <?php
        }
        ?>
        <?php
        if (!empty($report['dataHutang'])) {
            ?>
            <table style="margin:0 auto" class="table-bordered bordered">
                <thead>
                    <tr>
                        <th class="rata-kanan">No</th>
                        <th>Tanggal</th>
                        <th>Dokumen</th>
                        <th class="rata-kanan">Jumlah</th>
                        <th class="rata-kanan">Bayar</th>
                        <th class="rata-kanan">Sisa</th>
                    </tr>
                </thead>
                <?php
                $i = 1;
                foreach ($report['dataHutang'] as $baris):
                    /* Tambahan untuk pembelian mencari nomor referensi
                     * untuk ditampilkan
                     */
                    if ($baris['asal'] == HutangPiutang::DARI_PEMBELIAN) {
                        $noRef = Pembelian::model()->find("hutang_piutang_id={$baris['id']}")->referensi;
                    }
                    if ($baris['asal'] == HutangPiutang::DARI_RETUR_JUAL){
                        $noRef = ReturPenjualan::model()->find("hutang_piutang_id={$baris['id']}")->referensi;
                    }
                    ?>
                    <tr class="<?php echo $i % 2 === 0 ? '' : 'alt'; ?>">
                        <td class="rata-kanan"><?= $i; ?></td>
                        <td><?= date_format(date_create_from_format('Y-m-d H:i:s', $baris['created_at']), 'd-m-Y H:i:s'); ?></td>
                        <td>
                        <?= $listAsalHP[$baris['asal']] . ': ' . $baris['nomor_dokumen_asal'] ?>
                        <?php
                        if (!is_null($noRef)){
                            ?>
                            | R: <?= $noRef ?>
                            <?php
                        }
                        ?>
                        </td>
                        <td class="rata-kanan"><?= number_format($baris['jumlah'], 0, ',', '.'); ?></td>
                        <td class="rata-kanan"><?= number_format($baris['jumlah_bayar'], 0, ',', '.'); ?></td>
                        <td class="rata-kanan"><?= number_format($baris['jumlah'] - $baris['jumlah_bayar'], 0, ',', '.'); ?></td>
                    </tr>

                    <?php
                    $i++;
                endforeach;
                ?>                
                <tfoot>
                    <tr>
                        <td colspan="3" class="rata-tengah">Total</td>
                        <td class="rata-kanan"><?= number_format($report['rekapHutang']['jumlah'], 0, ',', '.'); ?></td>
                        <td class="rata-kanan"><?= number_format($report['rekapHutang']['jumlah_bayar'], 0, ',', '.'); ?></td>
                        <td class="rata-kanan"><?= number_format($report['rekapHutang']['jumlah'] - $report['rekapHutang']['jumlah_bayar'], 0, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>
            <?php
        }
        ?>

        <?php
        if (!empty($report['rekapPiutang'])) {
            ?>
            <br />
            <b>Piutang:</b> Total <b><?= number_format($report['rekapPiutang']['jumlah'], 0, ',', '.'); ?></b> Bayar <b><?= number_format($report['rekapPiutang']['jumlah_bayar'], 0, ',', '.'); ?></b> Sisa <b><?= number_format($report['rekapPiutang']['jumlah'] - $report['rekapPiutang']['jumlah_bayar'], 0, ',', '.'); ?></b>
            <?php
        }
        ?>
        <?php
        if (!empty($report['dataPiutang'])) {
            ?>
            <table style="margin:0 auto" class="table-bordered bordered">
                <thead>
                    <tr>
                        <th class="rata-kanan">No</th>
                        <th>Tanggal</th>
                        <th>Dokumen</th>
                        <th class="rata-kanan">Jumlah</th>
                        <th class="rata-kanan">Bayar</th>
                        <th class="rata-kanan">Sisa</th>
                    </tr>
                </thead>
                <?php
                $i = 1;
                foreach ($report['dataPiutang'] as $baris):
                    ?>
                    <tr class="<?php echo $i % 2 === 0 ? '' : 'alt'; ?>">
                        <td class="rata-kanan"><?= $i; ?></td>
                        <td><?= date_format(date_create_from_format('Y-m-d H:i:s', $baris['created_at']), 'd-m-Y H:i:s'); ?></td>
                        <td><?= $listAsalHP[$baris['asal']] . ': ' . $baris['nomor_dokumen_asal']; ?></td>
                        <td class="rata-kanan"><?= number_format($baris['jumlah'], 0, ',', '.'); ?></td>
                        <td class="rata-kanan"><?= number_format($baris['jumlah_bayar'], 0, ',', '.'); ?></td>
                        <td class="rata-kanan"><?= number_format($baris['jumlah'] - $baris['jumlah_bayar'], 0, ',', '.'); ?></td>
                    </tr>

                    <?php
                    $i++;
                endforeach;
                ?>                
                <tfoot>
                    <tr>
                        <td colspan="3" class="rata-tengah">Total</td>
                        <td class="rata-kanan"><?= number_format($report['rekapPiutang']['jumlah'], 0, ',', '.'); ?></td>
                        <td class="rata-kanan"><?= number_format($report['rekapPiutang']['jumlah_bayar'], 0, ',', '.'); ?></td>
                        <td class="rata-kanan"><?= number_format($report['rekapPiutang']['jumlah'] - $report['rekapPiutang']['jumlah_bayar'], 0, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>
            <?php
        }
        ?>
    </body>
</html>