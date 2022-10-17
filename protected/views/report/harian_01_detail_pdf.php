<?php

function toIndoDate($timeStamp)
{
    $tanggal   = date_format(date_create($timeStamp), 'j');
    $bulan     = date_format(date_create($timeStamp), 'n');
    $namabulan = namaBulan($bulan);
    $tahun     = date_format(date_create($timeStamp), 'Y');
    return $tanggal . ' ' . $namabulan . ' ' . $tahun;
}

function namaHari($timeStamp)
{
    static $hari = [
        'Ahad',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu',
    ];
    return $hari[date('w', strtotime($timeStamp))];
}

function namaBulan($i)
{
    static $bulan = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
    ];
    return $bulan[$i - 1];
}
?>
<html>

<head>
    <title>Laporan Harian : <?php echo $report['kodeToko'] . ' ' . $report['namaToko'] . ' ' . $report['tanggal']; ?>
    </title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pdf-laporan.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.css" />
</head>

<body>
    <!--mpdf
            <htmlpagefooter name="footer">
                <table style="border-top:thin solid black">
                    <tr>
                        <td style="text-align:left">Laporan Harian: <?php
                                                                    echo $report['namaToko'] . ' ' . $report['tanggal'];
                                                                    ?>
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
    <div id="header1" style="border-bottom: 1px solid black; padding-bottom: 5px">
        <div><?php echo $report['namaToko']; ?>
        </div>
        <div><small><?php echo $report['alamatToko'] ?></small></div>
        <div id="tanggal"><?php echo namaHari($report['tanggal']) . ', ' . $report['tanggal']; ?>
        </div>
    </div>
    <br />
    <br />
    <table width="70%" style="left:30px; margin:0 auto" class="table-bordered">
        <tr>
            <td class="tebal">Saldo Kemarin</td>
            <td></td>
            <td class="kanan tebal"><?php echo number_format($report['saldoAwal'], 0, ',', '.'); ?>
            </td>
        </tr>
        <tr>
            <td class="trx-header tebal">Omzet</td>
            <td></td>
            <td class="kanan tebal trx-header"><?php echo number_format($report['totalPenjualanTunai'] + $report['totalPenjualanPiutang'], 0, ',', '.'); ?>
            </td>
        </tr>
        <tr>
            <td class="tebal">Profit/KT</td>
            <td></td>
            <td class="kanan tebal"><?php echo number_format($report['totalMarginPenjualan'], 0, ',', '.'); ?>
            </td>
        </tr>
        <tr>
            <td class="trx-header tebal">Kas Buku</td>
            <td></td>
            <td class="kanan tebal trx-header"><?php echo number_format($report['saldoAkhir'], 0, ',', '.'); ?>
            </td>
        </tr>
        <tr>
            <td class="tebal">Kas Asli</td>
            <td></td>
            <td class="kanan tebal"><?php echo number_format($report['saldoAkhirAsli'], 0, ',', '.'); ?>
            </td>
        </tr>
        <tr>
            <td class="tebal">Selisih</td>
            <td></td>
            <td class="kanan tebal"><?php echo number_format($report['saldoAkhirAsli'] - $report['saldoAkhir'], 0, ',', '.'); ?>
            </td>
        </tr>
        <tr>
            <td class="tebal trx-header">RINCIAN PENGELUARAN</td>
            <td></td>
            <td></td>
        </tr>
        <?php
        $totalPengeluaran = 0;
        /* Retur Jual tunai */
        if (!empty($report['returJualTunai'])) {
            foreach ($report['returJualTunai'] as $returJual) {
        ?>
                <tr>
                    <td class="level-1">Retur Jual
                        <?php
                        echo isset($returJual['nomor']) ? $returJual['nomor'] : '';
                        echo " [{$returJual['nama']}]";
                        ?>
                    </td>
                    <td class="kanan"><?php echo number_format($returJual['jumlah'], 0, ',', '.'); ?></td>
                    <td></td>
                </tr>
        <?php
                $totalPengeluaran += $returJual['jumlah'];
            }
        }
        ?>
        <?php
        /* Pembayaran hutang retur jual */
        if (!empty($report['returJualBayar'])) :
            foreach ($report['returJualBayar'] as $pembayaran) {
        ?>
                <tr>
                    <td class="level-1">Pembayaran retur jual
                        <?php
                        echo isset($pembayaran['nomor']) ? $pembayaran['nomor'] : '';
                        echo " [{$pembayaran['nama']}]";
                        ?>
                    </td>
                    <td class="kanan"><?php echo number_format($pembayaran['jumlah_bayar'], 0, ',', '.'); ?></td>
                </tr>
            <?php
                $totalPengeluaran += $pembayaran['jumlah_bayar'];
            }
            ?>
        <?php
        endif;
        ?>
        <?php
        foreach ($report['itemPengeluaran'] as $pengeluaran) {
            if (!empty($pengeluaran['items'])) {
        ?>
                <!-- <tr> -->
                <!-- <td class="tebal trx-header"><?php //echo strtoupper($pengeluaran['nama']); 
                                                    ?> (-)</td> -->
                <!-- <td class="kanan tebal trx-header"><?php //echo number_format($pengeluaran['total'], 0, ',', '.'); 
                                                        ?></td> -->
                <!-- </tr> -->
                <?php
                foreach ($pengeluaran['items'] as $items) {
                ?>
                    <tr>
                        <?php $itemKeterangan = isset($items['keterangan']) ? $items['keterangan'] : '' ?>
                        <td class="level-1"><?php echo "[{$items['akun']}] [{$items['nama']}] {$itemKeterangan}"; ?></td>
                        <td class="kanan"><?php echo number_format($items['jumlah'], 0, ',', '.'); ?></td>
                        <td></td>
                    </tr>
                <?php
                    $totalPengeluaran += $items['jumlah'];
                }
            }
        }
        if (!empty($report['pembelianBayar'])) {
            foreach ($report['pembelianBayar'] as $pembayaran) {
                ?>
                <tr>
                    <td class="level-1">Pembayaran pembelian
                        <?php echo isset($pembayaran['nomor']) ? $pembayaran['nomor'] : ''; ?>
                        <?php echo " [{$pembayaran['nama']}] "; ?>
                        <?php echo isset($pembayaran['tanggal']) ? date('d-m-Y', strtotime($pembayaran['tanggal'])) : ''; ?>
                    </td>
                    <td class="kanan"><?php echo number_format($pembayaran['total_bayar'], 0, ',', '.'); ?></td>
                    <td></td>
                </tr>
        <?php
                $totalPengeluaran += $pembayaran['total_bayar'];
            }
        }
        ?>
        <?php if (!empty($report['pembelianTunai'])) :
        ?>
            <?php
            foreach ($report['pembelianTunai'] as $pembelianTunai) {
                $totalPengeluaran += $pembelianTunai['jumlah'];
            ?>
                <tr>
                    <td class="level-1">
                        <?php
                        echo isset($pembelianTunai['nomor']) ? $pembelianTunai['nomor'] : '';
                        echo " {$pembelianTunai['nama']}";
                        ?>
                    </td>
                    <td class="kanan"><?php echo number_format($pembelianTunai['jumlah'], 0, ',', '.'); ?></td>
                    <td></td>
                </tr>
            <?php
            }
            ?>
        <?php
        endif;
        ?>
        <tr>
            <td class="level-1 tebal">Total</td>
            <td></td>
            <td class="kanan tebal"><?= number_format($totalPengeluaran, 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td class="tebal trx-header">RINCIAN PEMASUKAN LAIN-LAIN</td>
            <td></td>
            <td></td>
        </tr>
        <?php
        if (!empty($report['penjualanBayar'])) :
            foreach ($report['penjualanBayar'] as $penerimaanPiutang) :
        ?>
                <tr>
                    <td class="level-1"> Penerimaan Piutang Penjualan
                        <?php
                        echo isset($penerimaanPiutang['nomor']) ? $penerimaanPiutang['nomor'] : "";
                        echo " [{$penerimaanPiutang['nama']}]";
                        ?>
                    </td>
                    <td class="kanan"><?php echo number_format($penerimaanPiutang['jumlah_bayar'], 0, ',', '.'); ?></td>
                    <td></td>
                </tr>
            <?php
            endforeach;
            ?>
        <?php
        endif;
        ?>
        <?php
        if (!empty($report['returBeliTunai'])) :
            foreach ($report['returBeliTunai'] as $returBeli) :
        ?>
                <tr>
                    <td class="level-1">Retur beli
                        <?php
                        echo isset($returBeli['nomor']) ? $returBeli['nomor'] : '';
                        echo " [{$returBeli['nama']}]";
                        ?>
                    </td>
                    <td class="kanan"><?php echo number_format($returBeli['jumlah'], 0, ',', '.'); ?></td>
                    <td></td>
                </tr>
            <?php
            endforeach;
            ?>
        <?php
        endif;
        ?>
        <?php
        if (!empty($report['returBeliBayar'])) :
            foreach ($report['returBeliBayar'] as $penerimaanPiutangReturBeli) :
        ?>
                <tr>
                    <td class="level-1">Penerimaan piutang retur beli
                        <?php
                        echo isset($penerimaanPiutangReturBeli['nomor']) ? $penerimaanPiutangReturBeli['nomor'] : '';
                        echo " [{$penerimaanPiutangReturBeli['nama']}]";
                        ?>
                    </td>
                    <td class="kanan"><?php echo number_format($penerimaanPiutangReturBeli['jumlah_bayar'], 0, ',', '.'); ?></td>
                    <td></td>
                </tr>
            <?php
            endforeach;
            ?>
        <?php
        endif;
        ?>
        <?php
        foreach ($report['itemPenerimaan'] as $penerimaan) {
            if (!empty($penerimaan['items'])) {
                /*
                <tr>
                    <td class="tebal trx-header"><?php echo strtoupper($penerimaan['nama']); ?> (+)</td>
                    <td class="kanan tebal trx-header"><?php echo number_format($penerimaan['total'], 0, ',', '.'); ?></td>
                </tr>
                */
                foreach ($penerimaan['items'] as $items) :
        ?>
                    <tr>
                        <?php $itemKeterangan = isset($items['keterangan']) ? $items['keterangan'] : '' ?>
                        <td class="level-1"><?php echo "[{$items['akun']}] [{$items['nama']}] {$itemKeterangan}"; ?></td>
                        <td class="kanan"><?php echo number_format($items['jumlah'], 0, ',', '.'); ?></td>
                        <td></td>
                    </tr>
        <?php
                endforeach;
            }
        }
        ?>
    </table>
    <?php
    /*
if ($report['totalPembelianBayar']) {
?>
<tr>
<td class="tebal trx-header">PEMBAYARAN PEMBELIAN (-)</td>
<td class="kanan tebal trx-header"><?php echo number_format($report['totalPembelianBayar'], 0, ',', '.'); ?></td>
</tr>
<?php
}

if (!empty($report['pembelianBayar'])) :
foreach ($report['pembelianBayar'] as $pembayaran) :
?>
<tr>
<td class="level-1">
<?php
$pembayaranNomor = isset($pembayaran['nomor']) ? $pembayaran['nomor'] : '';
?>
<?php echo "{$pembayaranNomor} {$pembayaran['nama']} "; ?>
<?php echo isset($pembayaran['tanggal']) ? date('d-m-Y', strtotime($pembayaran['tanggal'])) : ''; ?>
</td>
<td class="kanan"><?php echo number_format($pembayaran['total_bayar'], 0, ',', '.'); ?></td>
</tr>
<?php
endforeach;
?>
<?php
endif;
?>
<?php if ($report['totalPembelianTunai']) :
?>
<tr>
<td class="tebal trx-header">PEMBELIAN TUNAI (-)</td>
<td class="kanan tebal trx-header"><?php echo number_format($report['totalPembelianTunai'], 0, ',', '.'); ?></td>
</tr>
<?php
endif;
?>
<?php if (!empty($report['pembelianTunai'])) :
?>
<?php
foreach ($report['pembelianTunai'] as $pembelianTunai) :
?>
<tr>
<?php
$pembelianTunaiNomor = isset($pembelianTunai['nomor']) ? $pembelianTunai['nomor'] : '';
?>
<td class="level-1"><?php echo "{$pembelianTunaiNomor} {$pembelianTunai['nama']}"; ?></td>
<td class="kanan"><?php echo number_format($pembelianTunai['jumlah'], 0, ',', '.'); ?></td>
</tr>
<?php
endforeach;
?>
<?php
endif;
?>
<?php
foreach ($report['itemPengeluaran'] as $pengeluaran) {
if (!empty($pengeluaran['items'])) {
?>
<tr>
<td class="tebal trx-header"><?php echo strtoupper($pengeluaran['nama']); ?> (-)</td>
<td class="kanan tebal trx-header"><?php echo number_format($pengeluaran['total'], 0, ',', '.'); ?></td>
</tr>
<?php
foreach ($pengeluaran['items'] as $items) :
?>
<tr>
<?php $itemKeterangan = isset($items['keterangan']) ? $items['keterangan'] : '' ?>
<td class="level-1"><?php echo "[{$items['akun']}] [{$items['nama']}] {$itemKeterangan}"; ?></td>
<td class="kanan"><?php echo number_format($items['jumlah'], 0, ',', '.'); ?></td>
</tr>
<?php
endforeach;
}
}
?>
<?php
foreach ($report['itemPenerimaan'] as $penerimaan) {
if (!empty($penerimaan['items'])) {
?>
<tr>
<td class="tebal trx-header"><?php echo strtoupper($penerimaan['nama']); ?> (+)</td>
<td class="kanan tebal trx-header"><?php echo number_format($penerimaan['total'], 0, ',', '.'); ?></td>
</tr>
<?php
foreach ($penerimaan['items'] as $items) :
?>
<tr>
<?php $itemKeterangan = isset($items['keterangan']) ? $items['keterangan'] : '' ?>
<td class="level-1"><?php echo "[{$items['akun']}] [{$items['nama']}] {$itemKeterangan}"; ?></td>
<td class="kanan"><?php echo number_format($items['jumlah'], 0, ',', '.'); ?></td>
</tr>
<?php
endforeach;
}
}
?>
<tr>
<td class="trx-header tebal">SALDO AKHIR BUKU</td>
<td class="kanan tebal trx-header"><?php echo number_format($report['saldoAkhir'], 0, ',', '.'); ?></td>
</tr>
<tr>
<td class="tebal">SALDO AKHIR ASLI</td>
<td class="kanan tebal"><?php echo number_format($report['saldoAkhirAsli'], 0, ',', '.'); ?></td>
</tr>
<?php
if ($report['totalPenjualanTunai']) {
?>
<tr>
<td class="trx-header tebal">PENJUALAN TUNAI (+)</td>
<td class="kanan tebal trx-header"><?php echo number_format($report['totalPenjualanTunai'], 0, ',', '.'); ?></td>
</tr>
<?php
}
?>
<?php
if (!empty($report['penjualanTunai'])) :
$headerAkun   = '';
$totalPerAkun = [];
foreach ($report['totalPenjualanTunaiPerAkun'] as $perAkun) {
$totalPerAkun[$perAkun['nama_akun']] = $perAkun['jumlah'];
}
foreach ($report['penjualanTunai'] as $penjualanTunai) :
?>
<?php
if ($headerAkun != $penjualanTunai['nama_akun']) {
?>
<tr>
<td class="level-1 tebal"><?= "{$penjualanTunai['nama_akun']}"; ?></td>
<td class="kanan tebal"><?= number_format($totalPerAkun[$penjualanTunai['nama_akun']], 0, ',', '.') ?></td>
</tr>
<?php
$headerAkun = $penjualanTunai['nama_akun'];
}
?>
<tr>
<?php
$penjualanTunaiNomor   = isset($penjualanTunai['nomor']) ? $penjualanTunai['nomor'] : '';
?>
<td class="level-2"><?php echo "{$penjualanTunaiNomor} {$penjualanTunai['nama']}  "; ?></td>
<td class="kanan"><?php echo number_format($penjualanTunai['jumlah'], 0, ',', '.'); ?></td>
</tr>
<?php
endforeach;
endif;
?>
<?php
if ($report['totalMargin']) {
?>
<tr>
<td class="trx-header tebal">GROSS PROFIT (MARGIN)</td>
<td class="kanan tebal trx-header"><?php echo number_format($report['totalMargin'], 0, ',', '.'); ?></td>
</tr>
<?php
}
?>
<?php
if (!empty($report['margin'])) :
foreach ($report['margin'] as $marginPenjualan) :
?>
<tr>
<?php
$marginPenjualanNomor = isset($marginPenjualan['nomor']) ? $marginPenjualan['nomor'] : '';
?>
<td class="level-1"><?php echo "{$marginPenjualanNomor} {$marginPenjualan['nama']}"; ?></td>
<td class="kanan"><?php echo number_format($marginPenjualan['margin'], 0, ',', '.'); ?></td>
</tr>
<?php
endforeach;
?>
<?php
endif;
?>
<?php
if ($report['totalPenjualanBayar']) {
?>
<tr>
<td class="trx-header tebal">PENERIMAAN PIUTANG PENJUALAN (+)</td>
<td class="kanan tebal trx-header"><?php echo number_format($report['totalPenjualanBayar'], 0, ',', '.'); ?></td>
</tr>
<?php
}
?>
<?php
if (!empty($report['penjualanBayar'])) :
foreach ($report['penjualanBayar'] as $penerimaanPiutang) :
?>
<tr>
<?php
$penerimaanPiutangNomor = isset($penerimaanPiutang['nomor']) ? $penerimaanPiutang['nomor'] : '';
?>
<td class="level-1"><?php echo "{$penerimaanPiutangNomor} {$penerimaanPiutang['nama']}"; ?></td>
<td class="kanan"><?php echo number_format($penerimaanPiutang['jumlah_bayar'], 0, ',', '.'); ?></td>
</tr>
<?php
endforeach;
?>
<?php
endif;
?>
<?php
if ($report['totalPenjualanPiutang']) {
?>
<tr>
<td class="trx-header tebal">PIUTANG PENJUALAN</td>
<td class="kanan tebal trx-header"><?php echo number_format($report['totalPenjualanPiutang'], 0, ',', '.'); ?></td>
</tr>
<?php
}
?>
<?php
if (!empty($report['penjualanPiutang'])) :
foreach ($report['penjualanPiutang'] as $piutangPenjualan) :
?>
<tr>
<?php
$piutangPenjualanNomor = isset($piutangPenjualan['nomor']) ? $piutangPenjualan['nomor'] : '';
?>
<td class="level-1"><?php echo "{$piutangPenjualanNomor} {$piutangPenjualan['nama']}"; ?></td>
<td class="kanan"><?php echo number_format(abs($piutangPenjualan['jml_bayar'] - $piutangPenjualan['jumlah']), 0, ',', '.'); ?></td>
</tr>
<?php
endforeach;
?>
<?php
endif;
?>
<?php
if ($report['totalReturJualTunai']) {
?>
<tr>
<td class="trx-header tebal">RETUR PENJUALAN (-)</td>
<td class="kanan tebal trx-header"><?php echo number_format($report['totalReturJualTunai'], 0, ',', '.'); ?></td>
</tr>
<?php
}
?>
<?php
if (!empty($report['returJualTunai'])) :
foreach ($report['returJualTunai'] as $returJual) :
?>
<tr>
<?php
$returJualNomor = isset($returJual['nomor']) ? $returJual['nomor'] : '';
?>
<td class="level-1"><?php echo "{$returJualNomor} {$returJual['nama']}"; ?></td>
<td class="kanan"><?php echo number_format($returJual['jumlah'], 0, ',', '.'); ?></td>
</tr>
<?php
endforeach;
?>
<?php
endif;
?>
<?php
if ($report['totalReturBeliTunai']) {
?>
<tr>
<td class="trx-header tebal">RETUR PEMBELIAN (+)</td>
<td class="kanan tebal trx-header"><?php echo number_format($report['totalReturBeliTunai'], 0, ',', '.'); ?></td>
</tr>
<?php
}
?>
<?php
if (!empty($report['returBeliTunai'])) :
foreach ($report['returBeliTunai'] as $returBeli) :
?>
<tr>
<?php
$returBeliNomor = isset($returBeli['nomor']) ? $returBeli['nomor'] : '';
?>
<td class="level-1"><?php echo "{$returBeliNomor} {$returBeli['nama']}"; ?></td>
<td class="kanan"><?php echo number_format($returBeli['jumlah'], 0, ',', '.'); ?></td>
</tr>
<?php
endforeach;
?>
<?php
endif;
?>
<?php
if ($report['totalReturBeliBayar']) {
?>
<tr>
<td class="trx-header tebal">PENERIMAAN PIUTANG RETUR PEMBELIAN (+)</td>
<td class="kanan tebal trx-header"><?php echo number_format($report['totalReturBeliBayar'], 0, ',', '.'); ?></td>
</tr>
<?php
}

if (!empty($report['returBeliBayar'])) :
foreach ($report['returBeliBayar'] as $penerimaanPiutangReturBeli) :
?>
<tr>
<?php
$penerimaanPiutangReturBeliNomor = isset($penerimaanPiutangReturBeli['nomor']) ? $penerimaanPiutangReturBeli['nomor'] : '';
?>
<td class="level-1"><?php echo "{$penerimaanPiutangReturBeliNomor} {$penerimaanPiutangReturBeli['nama']}"; ?></td>
<td class="kanan"><?php echo number_format($penerimaanPiutangReturBeli['jumlah_bayar'], 0, ',', '.'); ?></td>
</tr>
<?php
endforeach;
?>
<?php
endif;
?>
<?php
if ($report['totalReturJualBayar']) {
?>
<tr>
<td class="trx-header tebal">PEMBAYARAN HUTANG RETUR PENJUALAN (-)</td>
<td class="kanan tebal trx-header"><?php echo number_format($report['totalReturJualBayar'], 0, ',', '.'); ?></td>
</tr>
<?php
}

if (!empty($report['returJualBayar'])) :
foreach ($report['returJualBayar'] as $pembayaran) :
?>
<tr>
<?php
$pembayaranNomor = isset($pembayaran['nomor']) ? $pembayaran['nomor'] : '';
?>
<td class="level-1"><?php echo "{$pembayaranNomor} {$pembayaran['nama']}"; ?></td>
<td class="kanan"><?php echo number_format($pembayaran['jumlah_bayar'], 0, ',', '.'); ?></td>
</tr>
<?php
endforeach;
?>
<?php
endif;
?>
<?php
if ($report['totalReturBeliPiutang']) {
?>
<tr>
<td class="trx-header tebal">PIUTANG RETUR PEMBELIAN</td>
<td class="kanan tebal trx-header"><?php echo number_format($report['totalReturBeliPiutang'], 0, ',', '.'); ?></td>
</tr>
<?php
}

if (!empty($report['returBeliPiutang'])) :
foreach ($report['returBeliPiutang'] as $piutangReturBeli) :
?>
<tr>
<?php
$piutangReturBeliNomor = isset($piutangReturBeli['nomor']) ? $piutangReturBeli['nomor'] : '';
?>
<td class="level-1"><?php echo "{$piutangReturBeliNomor} {$piutangReturBeli['nama']}"; ?></td>
<td class="kanan"><?php echo number_format($piutangReturBeli['jumlah'], 0, ',', '.'); ?></td>
</tr>
<?php
endforeach;
?>
<?php
endif;
?>
<?php
if ($report['totalReturJualHutang']) {
?>
<tr>
<td class="trx-header tebal">HUTANG RETUR PENJUALAN</td>
<td class="kanan tebal trx-header"><?php echo number_format($report['totalReturJualHutang'], 0, ',', '.'); ?></td>
</tr>
<?php
}

if (!empty($report['returJualHutang'])) :
foreach ($report['returJualHutang'] as $hutangReturJual) :
?>
<tr>
<?php
$hutangReturJualNomor = isset($hutangReturJual['nomor']) ? $hutangReturJual['nomor'] : '';
?>
<td class="level-1"><?php echo "{$hutangReturJualNomor} {$hutangReturJual['nama']}"; ?></td>
<td class="kanan"><?php echo number_format($hutangReturJual['jumlah'], 0, ',', '.'); ?></td>
</tr>
<?php
endforeach;
?>
<?php
endif;
?>
<?php
if ($report['totalPembelianHutang']) {
?>
<tr>
<td class="trx-header tebal">HUTANG PEMBELIAN</td>
<td class="kanan tebal trx-header"><?php echo number_format($report['totalPembelianHutang'], 0, ',', '.'); ?></td>
</tr>
<?php
}

if (!empty($report['pembelianHutang'])) :
foreach ($report['pembelianHutang'] as $hutang) :
?>
<tr>
<?php
$hutangNomor = isset($hutang['nomor']) ? $hutang['nomor'] : '';
?>
<td class="level-1"><?php echo "{$hutangNomor} {$hutang['nama']}"; ?></td>
<td class="kanan"><?php echo number_format($hutang['jumlah'], 0, ',', '.'); ?></td>
</tr>
<?php
endforeach;
?>
<?php
endif;
?>
<?php
if ($report['totalTarikTunai']) {
?>
<tr>
<td class="trx-header tebal">TARIK TUNAI</td>
<td class="kanan tebal trx-header"><?php echo number_format($report['totalTarikTunai'], 0, ',', '.'); ?></td>
</tr>
<?php
}
?>
<?php
if (!empty($report['tarikTunai'])) :
$headerAkun   = '';
$totalPerAkun = [];
foreach ($report['totalTarikTunaiPerAkun'] as $perAkun) {
$totalPerAkun[$perAkun['nama_akun']] = $perAkun['jumlah'];
}
foreach ($report['tarikTunai'] as $tarikTunai) :
?>
<?php
if ($headerAkun != $tarikTunai['nama_akun']) {
?>
<tr>
<td class="level-1 tebal"><?= "{$tarikTunai['nama_akun']}"; ?></td>
<td class="kanan tebal"><?= number_format($totalPerAkun[$tarikTunai['nama_akun']], 0, ',', '.') ?></td>
</tr>
<?php
$headerAkun = $tarikTunai['nama_akun'];
}
?>
<tr>
<?php
$penjualanNomor   = isset($tarikTunai['nomor']) ? $tarikTunai['nomor'] : '';
?>
<td class="level-2"><?php echo "{$penjualanNomor} {$tarikTunai['nama']}  "; ?></td>
<td class="kanan"><?php echo number_format($tarikTunai['jumlah'], 0, ',', '.'); ?></td>
</tr>
<?php
endforeach;
endif;
?>
</table>
<?php
if ($report['keterangan'] > '') :
?>
<div class="remarks-h">Remarks:</div>
<div class="remarks-d"><?php echo nl2br($report['keterangan']); ?></div>
<?php
endif;
?>
 */ ?>
</body>

</html>