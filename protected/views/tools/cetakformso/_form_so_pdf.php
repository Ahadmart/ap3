<html>

<head>
    <title>Form Stock Opname : <?php echo $modelForm->namaRak . ' | ' . $toko['nama']; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pdf-tabel.css" />
</head>

<body>
    <!--mpdf
            <htmlpagefooter name="footer">
                <table style="border-top:thin solid black">
                    <tr>
                        <td style="text-align:left"><?php
                                                    echo $toko['nama'];
                                                    ?> | Form Stock Opname | Rak: <?php echo $modelForm->namaRak; ?> | <?php echo $tanggalCetak; ?> 
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
        <div><?php echo $toko['nama']; ?><br />Form Stock Opname, Rak: <?php echo $modelForm->namaRak; ?>
            <?php
            if ($modelForm->kategoriId > 0) :
            ?>
                <br />Kategori: <?php echo $modelForm->namaKategori; ?>
            <?php
            endif;
            ?>
        </div>
    </div>
    <br />
    <br />
    <table style="margin:0 auto" class="table-bordered bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Barcode</th>
                <th>Nama</th>
                <th>Harga Jual</th>
                <th style="width: 11%">Qty Tercatat</th>
                <th>Qty RB</th>
                <th>Selisih</th>
            </tr>
        </thead>
        <?php
        $i = 1;
        foreach ($data as $barang) :
        ?>
            <tr class="<?= $barang['status'] == 0 ? 'alt':'' ?>">
                <td class="rata-kanan"><?= $i; ?></td>
                <td><?= $barang['barcode']; ?></td>
                <td><?= $barang['status'] == 0 ? '█':'' ?> <?= $barang['nama']; ?> <?= $barang['status'] == 0 ? '█':'' ?></td>
                <td class="rata-kanan"><?= number_format($barang['harga'], 0, ',', '.') ?></td>
                <td class="rata-kanan"><?= $barang['stok']; ?></td>
                <?php
                $barangModel = Barang::model()->find("barcode = :barcode", [':barcode' => $barang['barcode']]);
                ?>
                <td class="rata-kanan"><?= $barangModel->qtyReturBeliPosted ?></td>
                <td></td>
            </tr>

        <?php
            $i++;
        endforeach;
        ?>
    </table>
</body>

</html>