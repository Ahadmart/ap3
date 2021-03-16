<html>

<head>
    <title>Top Rank : <?php echo $config['toko.nama'] . ' | ' . $waktuCetak; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pdf-tabel.css" />
</head>

<body>
    <!--mpdf
            <htmlpagefooter name="footer">
                <table style="border-top:thin solid black">
                    <tr>
                        <td style="text-align:left">Top Rank | <?php
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
        <div>Top Rank <?php echo $config['toko.nama']; ?></div>
        <?php
        if (!empty($model->strukLv1)) :
            $strukLv1 = StrukturBarang::model()->findByPk($model->strukLv1);
        ?>
            <div><?= $strukLv1->nama ?>
            <?php
        endif;
        if (!empty($model->strukLv2)) :
            $strukLv2 = StrukturBarang::model()->findByPk($model->strukLv2);
            ?>
                <?= ' > ' . $strukLv2->nama ?>
            <?php
        endif;
        if (!empty($model->strukLv3)) :
            $strukLv3 = StrukturBarang::model()->findByPk($model->strukLv3);
            ?>
                <?= ' > ' . $strukLv3->nama ?>
            <?php
        endif;
            ?>
            </div>
            <br />
            <br />
            <table style="margin:0 auto" class="table-bordered bordered">
                <thead>
                    <tr>
                        <th class="rata-kanan">No</th>
                        <th>Barcode</th>
                        <th>Nama</th>
                        <th class="rata-kanan">Qty</th>
                        <th class="rata-kanan">Omset</th>
                        <th class="rata-kanan">Profit</th>
                        <th class="rata-tengah">Avg/Day</th>
                        <th class="rata-kanan">Stok</th>
                    </tr>
                </thead>
                <?php
                $i = 1;
                foreach ($report as $barang) :
                ?>
                    <tr class="<?php echo $i % 2 === 0 ? '' : 'alt'; ?>">
                        <td class="rata-kanan"><?= $i; ?></td>
                        <td><?= $barang['barcode']; ?></td>
                        <td><?= $barang['nama']; ?></td>
                        <td class="rata-kanan"><?= $barang['totalqty']; ?></td>
                        <td class="rata-kanan"><?= number_format($barang['total'], 0, ',', '.'); ?></td>
                        <td class="rata-kanan"><?= number_format($barang['margin'], 0, ',', '.'); ?></td>
                        <td class="rata-kanan"><?= number_format($barang['avgday'], 2, ',', '.'); ?></td>
                        <td class="rata-kanan"><?= number_format($barang['stok'], 0, ',', '.'); ?></td>
                    </tr>

                <?php
                    $i++;
                endforeach;
                ?>
            </table>
</body>

</html>