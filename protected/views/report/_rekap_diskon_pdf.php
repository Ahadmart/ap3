<html>

<head>
    <title>Rekap Diskon : <?php echo $config['toko.nama'] . ' | ' . $waktuCetak; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pdf-tabel.css" />
</head>

<body>
    <!--mpdf
            <htmlpagefooter name="footer">
                <table style="border-top:thin solid black">
                    <tr>
                        <td style="text-align:left">Rekap Diskon | <?php
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
        <div>Rekap Diskon <?php echo $config['toko.nama']; ?></div>
        <div><?= $dari ?> s.d <?= $sampai ?></div>
        <br />
        <br />
        <table style="margin:0 auto" class="table-bordered bordered">
            <thead>
                <tr>
                    <th class="rata-kanan">No</th>
                    <th>Barcode</th>
                    <th>Nama</th>
                    <th class="rata-kanan">Harga Normal</th>
                    <th class="rata-kanan">Harga Jual</th>
                    <th class="rata-kanan">Qty</th>
                    <th class="rata-tengah">HPP</th>
                    <th class="rata-kanan">Margin</th>
                    <th class="rata-kanan">Jenis Diskon</th>
                </tr>
            </thead>
            <?php
            $i = 1;
            foreach ($report['detail'] as $barang) :
            ?>
                <tr class="<?php echo $i % 2 === 0 ? '' : 'alt'; ?>">
                    <td class="rata-kanan"><?= $i; ?></td>
                    <td><?= $barang['barcode']; ?></td>
                    <td><?= $barang['nama']; ?></td>
                    <td class="rata-kanan"><?= number_format($barang['harga_normal'], 0, ',', '.'); ?></td>
                    <td class="rata-kanan"><?= number_format($barang['harga_jual'], 0, ',', '.'); ?></td>
                    <td class="rata-kanan"><?= $barang['qty']; ?></td>
                    <td class="rata-kanan"><?= number_format($barang['hpp'], 0, ',', '.'); ?></td>
                    <td class="rata-kanan"><?= number_format($barang['margin'], 0, ',', '.'); ?></td>
                    <td class="rata-kanan"><?= DiskonBarang::namaTipeDiskon($barang['tipe_diskon_id']) ?></td>
                </tr>

            <?php
                $i++;
            endforeach;
            ?>
        </table>
</body>

</html>