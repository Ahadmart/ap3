<?php

function toIndoDate($timeStamp)
{
    $tanggal   = date_format(date_create($timeStamp), 'j');
    $bulan     = date_format(date_create($timeStamp), 'n');
    $namabulan = namaBulan($bulan);
    $tahun     = date_format(date_create($timeStamp), 'Y');
    return $tanggal . ' ' . $namabulan . ' ' . $tahun . ' ' . date_format(date_create($timeStamp), 'H:i');
}

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
        "Desember",
    );
    return $bulan[$i - 1];
}
?>
<html>

<head>
    <title>Penjualan per Struktur : <?php echo $config['toko.nama'] . ' | ' . $waktuCetak; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pdf-tabel.css" />
</head>

<body>
    <!--mpdf
            <htmlpagefooter name="footer">
                <table style="border-top:thin solid black">
                    <tr>
                        <td style="text-align:left">Penjualan per Struktur | <?php
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
    <div id="header1" style="text-align: left">
        <div>Penjualan per Struktur <?php echo $config['toko.nama']; ?></div>
        <div><small>Struktur</small> <?php echo $namaStruktur ?></div>
        <div><small>Periode</small> <?php echo toIndoDate($dari) ?> - <?php echo toIndoDate($sampai) ?></div>
        <?php
        if (!empty($profilId)) {
            $customer = Profil::model()->findByPk($profilId);
        ?>
            <div><small>Profil Customer</small> <?php echo $customer->nama ?></div>
        <?php
        }
        ?>
        <?php
        if (!empty($userId)) {
            $user = User::model()->findByPk($userId);
        ?>
            <div><small>User</small> <?php echo $user->nama_lengkap ?></div>
        <?php
        }
        ?>
    </div>
    <br />
    <br />
    <table style="margin:0 auto" class="table-bordered bordered">
        <thead>
            <tr>
                <th class="rata-kanan">No</th>
                <th style="text-align: left">Nama</th>
                <th class="rata-kanan">Qty Terjual</th>
                <th class="rata-kanan">Omzet</th>
                <th class="rata-kanan">Margin (%)</th>
                <th class="rata-kanan">Stok</th>
            </tr>
        </thead>
        <?php
        $gTQty       = 0;
        $gTOmzet     = 0;
        $gTStok      = 0;
        $gTMargin    = 0;
        $jmlStruktur = 0;
        foreach ($report as $key => $data) {
            $struktur    = StrukturBarang::model()->findByPk($key);
            $i           = 1;
            $totalQty    = 0;
            $totalOmzet  = 0;
            $totalMargin = 0;
            $totalStok   = 0;
            if (!empty($data)) {
                $jmlStruktur++;
                foreach ($data as $row) :
                    // Yii::log(print_r($row, true));
        ?>
                    <tr class="<?php echo $i % 2 === 0 ? '' : 'alt'; ?>">
                        <td class="rata-kanan"><?= $i; ?></td>
                        <td><?= $row['lv2_nama']; ?></td>
                        <td class="rata-kanan"><?= number_format($row['qty'], 0, ',', '.') ?></td>
                        <td class="rata-kanan"><?= number_format($row['omzet'], 0, ',', '.') ?></td>
                        <td class="rata-kanan"><?= number_format($row['profit_margin'], 2, ',', '.') ?></td>
                        <td class="rata-kanan"><?= number_format($row['stok'], 0, ',', '.') ?></td>
                    </tr>

                <?php
                    $totalQty += $row['qty'];
                    $totalOmzet += $row['omzet'];
                    $totalMargin += $row['profit_margin'];
                    $totalStok += $row['stok'];
                    $i++;
                endforeach;
                $avgMargin = $totalMargin / ($i - 1);

                $gTQty += $totalQty;
                $gTOmzet += $totalOmzet;
                $gTMargin += $avgMargin;
                $gTStok += $totalStok;
                ?>
                <tr>
                <tr>
                    <td colspan="2" style="font-weight: bold">TOTAL (<?= $struktur->nama ?? 'Tanpa Struktur' ?>)</td>
                    <td class="rata-kanan" style="font-weight: bold"><?= number_format($totalQty, 0, ',', '.') ?></td>
                    <td class="rata-kanan" style="font-weight: bold"><?= number_format($totalOmzet, 0, ',', '.') ?></td>
                    <td class="rata-kanan" style="font-weight: bold"><?= number_format($avgMargin, 2, ',', '.') ?></td>
                    <td class="rata-kanan" style="font-weight: bold"><?= number_format($totalStok, 0, ',', '.') ?></td>
                </tr>
                </tr>
                <tr>
                    <td colspan="6">&nbsp;</td>
                </tr>
            <?php
            }
        }
        if ($jmlStruktur > 1) {
            $avgGtMargin = $gTMargin / $jmlStruktur;
            ?>
            <tr>
                <td colspan="2" style="font-weight: bold">GRAND TOTAL</td>
                <td class="rata-kanan" style="font-weight: bold"><?= number_format($gTQty, 0, ',', '.') ?></td>
                <td class="rata-kanan" style="font-weight: bold"><?= number_format($gTOmzet, 0, ',', '.') ?></td>
                <td class="rata-kanan" style="font-weight: bold"><?= number_format($avgGtMargin, 2, ',', '.') ?></td>
                <td class="rata-kanan" style="font-weight: bold"><?= number_format($gTStok, 0, ',', '.') ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
</body>

</html>