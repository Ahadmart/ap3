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
        "Desember"
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
                    <th style="text-align: left">Barcode</th>
                    <th style="text-align: left">Nama</th>
                    <th class="rata-kanan">Qty</th>
                    <th class="rata-kanan">Omzet</th>
                    <th class="rata-kanan">Margin (%)</th>
                    <th class="rata-kanan">Stok</th>
                </tr>
            </thead>
            <?php
            $gTQty          = 0;
            $gTOmzet        = 0;
            $gTStok         = 0;
            $gTMargin       = 0;
            $gTProfitMargin = 0;
            $jmlStruktur    = 0;
            $jmlItem        = 0;
            foreach ($report as $key => $data) {
                $struktur          = StrukturBarang::model()->findByPk($key);
                $i                 = 1;
                $totalQty          = 0;
                $totalOmzet        = 0;
                $totalProfitMargin = 0;
                $totalStok         = 0;
                if (!empty($data)) {
                    $jmlStruktur++;
                    foreach ($data as $barang):
                        $jmlItem++;
                        ?>
                        <tr class="<?php echo $i % 2 === 0 ? '' : 'alt'; ?>">
                            <td class="rata-kanan"><?= $i; ?></td>
                            <td><?= $barang['barcode']; ?></td>
                            <td><?= $barang['nama']; ?></td>
                            <td class="rata-kanan"><?= number_format($barang['qty'], 0, ',', '.') ?></td>
                            <td class="rata-kanan"><?= number_format($barang['omzet'], 0, ',', '.') ?></td>
                            <td class="rata-kanan"><?= number_format($barang['margin'] / $barang['omzet'] * 100, 2, ',', '.') ?></td>
                            <td class="rata-kanan"><?= number_format($barang['stok'], 0, ',', '.') ?></td>
                        </tr>

                        <?php
                        $totalQty          += $barang['qty'];
                        $totalOmzet        += $barang['omzet'];
                        $totalProfitMargin += $barang['margin'] / $barang['omzet'] * 100;
                        $totalStok         += $barang['stok'];
                        $i++;
                    endforeach;
                    $avgProfitMargin = $totalProfitMargin / ($i - 1);

                    $gTQty          += $totalQty;
                    $gTOmzet        += $totalOmzet;
                    $gTProfitMargin += $totalProfitMargin;
                    $gTStok         += $totalStok;
                    ?>
                    <tr>
                    <tr>
                        <td colspan="3" style="font-weight: bold">TOTAL (<?= $struktur->nama ?>)</td>
                        <td class="rata-kanan" style="font-weight: bold"><?= number_format($totalQty, 0, ',', '.') ?></td>
                        <td class="rata-kanan" style="font-weight: bold"><?= number_format($totalOmzet, 0, ',', '.') ?></td>
                        <td class="rata-kanan" style="font-weight: bold"><?= number_format($avgProfitMargin, 2, ',', '.') ?></td>
                        <td class="rata-kanan" style="font-weight: bold"><?= number_format($totalStok, 0, ',', '.') ?></td>
                    </tr>
                </tr>
                <tr>
                    <td colspan="7">&nbsp;</td>
                </tr>
                <?php
            }
        }
        if ($jmlStruktur > 1) {
            $avgGtMargin = $gTProfitMargin / $jmlItem;
            ?>
            <tr>
                <td colspan="3" style="font-weight: bold">GRAND TOTAL</td>
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