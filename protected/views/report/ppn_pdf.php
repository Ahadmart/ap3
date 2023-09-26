<html>

<head>
    <title>Laporan PPN : <?= $periode; ?>
    </title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pdf-laporan.css" />
    <!-- <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.css" /> -->
</head>

<body>
    <!--mpdf
            <htmlpagefooter name="footer">
                <table style="border-top:thin solid black">
                    <tr>
                        <td style="text-align:left">Laporan PPN: <?= $periode ?>
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
        <div>Laporan PPN</div>
        <div id="periode"><?= $periode ?></div>
    </div>
    <br />
    <br />
    <?php
    // print_r($report);
    $formatter = new BFormatter;
    ?>
    <table style="width:300px" class="table-bordered bordered">
        <thead>
            <tr>
                <th>Rekap PPN</td>
                <th>Jumlah</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total PPN Penjualan</td>
                <td class="kanan"><?= $formatter->formatUang($report['totalPpnPenjualan']) ?></td>
            </tr>
            <tr>
                <td>Total PPN Pembelian Valid</td>
                <td class="kanan"><?= $formatter->formatUang($report['totalPpnPembelianValid']) ?></td>
            </tr>
            <tr>
                <td>PPN Terhutang</td>
                <td class="kanan"><?= $formatter->formatUang($report['totalPpnPenjualan'] - $report['totalPpnPembelianValid']) ?></td>
            </tr>
            <tr>
                <td>Total PPN Pembelian Pending</td>
                <td class="kanan"><?= $formatter->formatUang($report['totalPpnPembelianPending']) ?></td>
            </tr>
        </tbody>
    </table>
    <?php
    if (!empty($report['detailPpnPembelianValid'])) {
    ?>
        <br />
        <br />
        <table class="table-bordered bordered">
            <caption>Detail PPN Pembelian Valid</caption>
            <thead>
                <tr>
                    <th>Supplier</th>
                    <th>Faktur Pajak</th>
                    <th>Pembelian</th>
                    <th>PPN</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($report['detailPpnPembelianValid'] as $data) {
                ?>
                    <tr class="<?= $i % 2 === 0 ? '' : 'alt' ?>">
                        <td><?= $data['nama'] ?></td>
                        <td><?= $data['no_faktur_pajak'] ?></td>
                        <td><?= $data['nomor'] ?></td>
                        <td class="kanan"><?= $formatter->formatUang($data['jumlah']) ?></td>
                    </tr>
                <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
    <?php
    }
    ?>
    <?php
    if (!empty($report['detailPpnPembelianPending'])) {
    ?>
        <br />
        <br />
        <table class="table-bordered bordered">
            <caption>Detail PPN Pembelian Pending</caption>
            <thead>
                <tr>
                    <th>Supplier</th>
                    <th>Pembelian</th>
                    <th>PPN</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($report['detailPpnPembelianPending'] as $data) {
                ?>
                    <tr class="<?= $i % 2 === 0 ? '' : 'alt' ?>">
                        <td><?= $data['nama'] ?></td>
                        <td><?= $data['nomor'] ?></td>
                        <td class="kanan"><?= $formatter->formatUang($data['jumlah']) ?></td>
                    </tr>
                <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
    <?php
    }
    ?>
</body>