<html>

<head>
    <title>Label Rak</title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/labelrak-ahad-warna.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.css" />
</head>

<body>
    <?php
    ?>
    <!--mpdf
            <htmlpagefooter name="footer"><div style="width: 100%; text-align:right">{PAGENO}{nb}</div>
            </htmlpagefooter>
            <sethtmlpagefooter name="footer" value="on" />
          mpdf-->
    <?php
    $jumlahKarakterNamaBarang = 30;

    foreach ($barang as $labelBarang) {
        $namaBarang1 = '';

        $namaBarangLengkap = trim($labelBarang->barang->nama);

        // jika terlalu panjang nama barangnya, potong saja
        if (strlen($namaBarangLengkap) > $jumlahKarakterNamaBarang) {
            $namaBarang1 = substr($namaBarangLengkap, 0, $jumlahKarakterNamaBarang) . '..';
        } else {
            $namaBarang1 = $namaBarangLengkap;
        }
    ?>
        <div class="label-container">
            <div class="label">
                <div class="nama-barang"><?php echo $namaBarang1; ?></div>
                <div class="harga-jual"><small>Rp.</small> <?php echo $labelBarang->barang->hargaJual; ?></div>
                <div class="barcode">
                    <barcode style="margin-left: -9px;" code="<?php echo trim($labelBarang->barang->barcode); ?>" type="C128A" size="0.65" height="0.5" />
                    <?php echo $labelBarang->barang->barcode; ?>
                </div>
                <div class="tgl-cetak"><?php echo $tanggalCetak; ?></div>
                <div class="footer1"></div>
                <div class="footer2"></div>
                <div class="footer3"></div>
                <img class="logo" src="<?php echo Yii::getPathOfAlias('webroot') . '/themes/' . Yii::app()->theme->name; ?>/img/ahadmart-logo.png" />
            </div>
        </div>
    <?php
    }
    ?>
</body>

</html>