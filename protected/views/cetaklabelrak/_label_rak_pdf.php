<html>
    <head>
        <title>Label Rak</title>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/labelrak.css" />
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
        $jumlahKarakterNamaBarang = 20;

        foreach ($barang as $labelBarang) {
            $namaBarang1 = $labelBarang->barang->nama;
            $namaBarang2 = '&nbsp;';

            $namaBarangLengkap = $labelBarang->barang->nama;
            // jika terlalu panjang nama barangnya
            if (strlen($namaBarangLengkap) > $jumlahKarakterNamaBarang) {
                $namaBarangArr = explode(' ', $namaBarangLengkap);
                $len = 0;
                $namaBarang1 = '';
                $namaBarang2 = '';
                foreach ($namaBarangArr as $namBar) {
                    $len += strlen($namBar);
                    if ($len <= $jumlahKarakterNamaBarang) {
                        $namaBarang1 .= $namBar . ' ';
                        $len++;
                    } else {
                        $namaBarang2 .= $namBar . ' ';
                    }
                }
            }
            ?>
            <div class="label-container">
                <div class="label">
                    <div class="nama-barang"><?php echo $namaBarang1; ?><br /><?php echo $namaBarang2; ?></div> 
                    <div class="harga-jual"><?php echo $labelBarang->barang->hargaJual; ?></div>
                    <div class="barcode">
                        <barcode style="margin-left: -9px;" code="<?php echo trim($labelBarang->barang->barcode); ?>" type="C128A" size="0.65" height="0.5" />
                        <?php echo $labelBarang->barang->barcode; ?>
                    </div>
                    <div class="tgl-cetak"><?php echo $tanggalCetak; ?></div>
                </div>
            </div>
            <?php
        }
        ?>
    </body>
</html>