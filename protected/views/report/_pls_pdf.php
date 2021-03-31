<html>

<head>
    <title>PLS : <?php echo $config['toko.nama'] . ' | ' . $waktuCetak; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pdf-tabel.css" />
</head>

<body>
    <!--mpdf
            <htmlpagefooter name="footer">
                <table style="border-top:thin solid black">
                    <tr>
                        <td style="text-align:left">PLS | <?php
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
        <div>POTENSI LOST SALES <?php echo $config['toko.nama']; ?></div>
        <div>Range Analisa: <?= $model->jumlahHari ?> hari terakhir</div>
        <div>Limit: Sisa hari <= <?= $model->orderPeriod ?> hari</div>
        </div>
        <br />
        <br />
        <?php
        if (!empty($model->profilId)) {
        ?>
            Profil: <em><?= $model->namaProfil; ?></em>
        <?php
        }
        ?>
        <table style="margin:0 auto" class="table-bordered bordered">
            <thead>
                <tr>
                    <th class="rata-kanan">No</th>
                    <th>Barcode</th>
                    <th>Nama</th>
                    <th>Penjualan</th>
                    <th>Penjualan/Hari (ADS)</th>
                    <th>Stok</th>
                    <th>Estimasi Sisa Hari</th>
                </tr>
            </thead>
            <?php
            $i = 1;
            foreach ($report as $strukturLv3) :
                foreach ($strukturLv3 as $barang) :
            ?>
                    <tr class="<?php echo $i % 2 === 0 ? '' : 'alt'; ?>">
                        <td class="rata-kanan"><?= $i; ?></td>
                        <td><?= $barang['barcode'] ?></td>
                        <td><?= $barang['nama'] ?></td>
                        <td class="rata-kanan"><?= number_format($barang['qty'], 0, ',', '.') ?></td>
                        <td class="rata-kanan"><?= number_format($barang['ads'], 4, ',', '.') ?></td>
                        <td class="rata-kanan"><?= number_format($barang['stok'], 0, ',', '.') ?></td>
                        <td class="rata-kanan"><?= number_format($barang['sisa_hari'], 2, ',', '.') ?></td>
                    </tr>
            <?php
                    $i++;
                endforeach;
            endforeach;
            ?>
        </table>
</body>

</html>