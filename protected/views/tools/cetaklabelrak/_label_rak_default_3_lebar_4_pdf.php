<html>

<head>
    <title>Label Rak</title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/labelrak.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.css" />
</head>

<body style='font-size:8pt'>
    <?php
    ?>
    <!--mpdf
            <htmlpagefooter name="footer"><div style="width: 100%; text-align:right">{PAGENO}{nb}</div>
            </htmlpagefooter>
            <sethtmlpagefooter name="footer" value="on" />
          mpdf-->
    <?php
    $jumlahKarakterNamaBarang = 25;

    foreach ($barang as $labelBarang) {
        $namaBarang1 = '';
        $namaBarang2 = '&nbsp;';

        $namaBarangLengkap = $labelBarang->barang->nama;
        $namaBarangArr = explode(' ', $namaBarangLengkap);

        // jika terlalu panjang nama barangnya, jadikan 2 baris
        if (strlen($namaBarangLengkap) > $jumlahKarakterNamaBarang) {
            $len = 0;
            foreach ($namaBarangArr as $namBar) {
                $len += strlen($namBar);
                if ($len <= $jumlahKarakterNamaBarang) {
                    $namaBarang1 .= $namBar . ' ';
                    $len++;
                } else {
                    $namaBarang2 .= $namBar . ' ';
                }
            }
        } else {
            /* Jika tidak panjang, tetap jadikan 2 baris, agar tampilannya tidak kosong
                 * Tapi jika hanya tdd 1 kata, berarti tetap jadi 1 baris
                 */
            $kataPertama = true;
            foreach ($namaBarangArr as $namBar) {
                if ($kataPertama) {
                    $namaBarang1 = $namBar;
                    $kataPertama = false;
                } else {
                    $namaBarang2 .= $namBar . ' ';
                }
            }
        }
    ?>
        <div class="label-container" style="width:40mm; height:30mm; overflow: hidden;">
            <div class="label">
                <div class="nama-barang"><?php echo $namaBarang1; ?><br /><?php echo substr(trim($namaBarang2), 0, 24); ?></div>
                <div class="harga-jual"><?php echo $labelBarang->barang->hargaJual; ?></div>
                <div class="tgl-cetak" style='width:100%'><?php echo $tanggalCetak; ?></div>
            </div>
        </div>
    <?php
    }
    ?>
</body>

</html>