<html>
    <head>
        <title>Label Rak</title>
        <!-- <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/labelrak.css" /> -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.css" />
        <style>
            body {
                font-family: OpenSans;
                font-size: 7pt;
            }

            .label {
                border: 1px solid #000;
                height: 30mm;
                text-align: left;
                overflow: hidden;
                margin-bottom: 2mm;
            }

            .label-container {
                width: 53mm;
                page-break-inside: avoid;
                float: left;
                margin-left: 2mm;
            }

            .nama-barang {
                padding: 2px 5px 0;
                /*margin-top: 3px;*/
                text-transform: uppercase;
                /* font-weight: bold; */
                font-family: Ubuntu;
                height: 15px;
                border-bottom: 1px solid black;
            }

            .harga-jual {
                font-size: 20pt;
                padding: 0 5px;
                float: right;
                text-align: center;
                margin-top: 0;
                /* font-family: Ubuntu; */
            }

            .barcode,
            .tgl-cetak {
                font-size: 6pt;
                margin: 0;
            }

            .barcode {
                float: left;
                text-align: left;
                padding-left: 9px;
                margin-top: 2mm;
            }
            .multi-harga-jual{
                width: 100%;
                text-align: right;
                margin-top: -1mm;
            }
            .info-harga, .info-satuan{
                text-align: right;
            }
            small{
                font-size: 50%;
            }
        </style>
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
            $satuanBarang  = $labelBarang->barang->satuan->nama;
            $multiHarga    = HargaJualMulti::listAktif($labelBarang->barang_id);
            $jmlMultiHarga = count($multiHarga);
            switch ($jmlMultiHarga) {
                case 0:
                $customCSS = [
                    'hargaJualCSS'       => 'font-size:25pt; margin-top:3mm',
                    'multiHargaFontSize' => '',
                    'barcodeMarginTop'   => 'margin-top:5px',
                ];
                    break;
                case 1:
                $customCSS = [
                    'multiHjContainer'        => 'margin-top: 0;',
                    'hargaJualCSS'            => 'font-size:17pt;',
                    'hargaJualCSSsmall'       => 'font-size:10pt;',
                    'multiHargaFontSize'      => 'font-size:17pt',
                    'multiHargaFontSizesmall' => 'font-size:10pt',
                    'barcodeMarginTop'        => 'margin-top:1mm',
                ];
                    break;
                case 2:
                $customCSS = [
                    'hargaJualCSS'            => 'font-size:15pt;',
                    'hargaJualCSSsmall'       => 'font-size:10pt;',
                    'multiHargaFontSize'      => 'font-size:10pt',
                    'multiHargaFontSizesmall' => 'font-size:8pt',
                    'barcodeMarginTop'        => 'margin-top:1mm',
                ];
                    break;
                case 3:
                    $customCSS = [
                        'hargaJualCSS'            => 'font-size:12pt; margin-top:-1mm',
                        'hargaJualCSSsmall'       => 'font-size:10pt;',
                        'multiHargaFontSize'      => 'font-size:9pt',
                        'multiHargaFontSizesmall' => 'font-size:6pt',
                        'barcodeMarginTop'        => 'margin-top:0',
                    ];
                        break;
                default:
                $customCSS = [
                    'hargaJualCSS'            => 'font-size:11pt; margin-top:-1mm',
                    'hargaJualCSSsmall'       => 'font-size:9pt;',
                    'multiHargaFontSize'      => 'font-size:7pt',
                    'multiHargaFontSizesmall' => 'font-size:5pt',
                    'barcodeMarginTop'        => 'margin-top:1',
                ];
                    break;
            } ?>
            <div class="label-container">
                <div class="label">
                    <div class="nama-barang">
                        <?php echo $namaBarang1; ?>
                    </div>
                    <?php 
                    if ($jmlMultiHarga != 1) {
                        ?>
                    <div class="harga-jual" style="<?=$customCSS['hargaJualCSS']?>">
                        <small>Rp.</small> <?= $labelBarang->barang->hargaJual ?>
                    </div>
                    <?php
                    } ?>
                    <div class="multi-harga-jual" style="<?=$customCSS['multiHjContainer']?>">
                        <table align="<?= $jmlMultiHarga == 1 ? 'right' : 'center' ?>" style="border-collapse: collapse; margin-right: 2mm">
                            <?php
                            if ($jmlMultiHarga == 1) {
                                ?>
                                <tr>
                                    <td style="<?=$customCSS['hargaJualCSSsmall']?>">Rp.</td>
                                    <td class="info-harga" style="<?=$customCSS['hargaJualCSS']?>"><?= $labelBarang->barang->hargaJual ?></td>
                                    <td></td>                                 
                                    <td></td>                                 
                                </tr>
                                <?php
                            } ?>
                            <?php
                                    foreach ($multiHarga as $multi) {
                                        ?>
                                <tr>
                                    <td style="<?=$customCSS['multiHargaFontSizesmall']?>">Rp.</td>
                                    <td class="info-harga" style="<?=$customCSS['multiHargaFontSize']?>"><?= number_format($multi['qty'] * $multi['harga'], 0, ',', '.') ?></td>                                   
                                    <td class="info-satuan" style="<?=$customCSS['multiHargaFontSizesmall']?>">(<?= $multi['qty'] ?> <?= $satuanBarang ?>)</td>
                                </tr>
                                        <?php
                                    } ?>
                        </table>
                    </div>
                    <div class="barcode" style="<?=$customCSS['barcodeMarginTop']?>">
                        <barcode style="margin-left: -9px;" code="<?php echo trim($labelBarang->barang->barcode); ?>" type="C128A" size="0.65" height="0.5" />
                        <div><?php echo $labelBarang->barang->barcode; ?> <?php echo $tanggalCetak; ?></div>
                    </div>
                </div>
            </div>

            <?php
        }
        ?>
    </body>
</html>