<html>
    <head>
        <title>Stock Opname : <?php echo $report['namaToko'] . ' | ' . $report['timestamp']; ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pdf-tabel.css" />
    </head>
    <body>
        <!--mpdf
            <htmlpagefooter name="footer">
                <table style="border-top:thin solid black">
                    <tr>
                        <td style="text-align:left">Stock Opname | <?= $report['namaToko'] ?> | <?= $report['dari']; ?> | <?= $report['sampai'] ?>
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
            <div>Laporan Stock Opname <?= $report['namaToko'] ?></div>
            <div><?= $report['dari'] ?> s.d <?= $report['sampai'] ?></div>
        </div>
        <br />
        <br />
        <table style="margin:0 auto" class="table-bordered bordered">
            <thead>
                <tr>
<!--                    <th rowspan="2">No</th>
                    <th rowspan="2">Nomor SO</th>
                    <th rowspan="2">Tanggal</th>
                    <th rowspan="2">Barcode</th>
                    <th rowspan="2">Nama</th>                        
                    <th colspan="2" class="rata-tengah">Tercatat</th>                    
                    <th colspan="2" class="rata-tengah">Fisik</th>               
                    <th colspan="2" class="rata-tengah">Selisih</th>-->
                    <th>No</th>
                    <th>Nomor SO</th>
                    <th>Tanggal</th>
                    <th>Barcode</th>
                    <th>Nama</th>                 
                    <?php
                    if ($nilaiDenganHargaJual) {
                        ?>
                        <th colspan="3" class="rata-tengah">Tercatat</th>  
                        <th colspan="3" class="rata-tengah">Fisik</th>   
                        <?php
                    } else {
                        ?>
                        <th colspan="2" class="rata-tengah">Tercatat</th>  
                        <th colspan="2" class="rata-tengah">Fisik</th>   
                        <?php
                    }
                    ?>
                    <th colspan="2" class="rata-tengah">Selisih</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="rata-kanan">Qty</th>
                    <th class="rata-kanan">Nilai</th>
                    <?php if ($nilaiDenganHargaJual) { ?><th class="rata-kanan">Harga Jual</th><?php } ?>
                    <th class="rata-kanan">Qty</th>
                    <th class="rata-kanan">Nilai</th>
                    <?php if ($nilaiDenganHargaJual) { ?><th class="rata-kanan">Harga Jual</th><?php } ?>
                    <th class="rata-kanan">Qty</th>
                    <th class="rata-kanan"><?php echo $nilaiDenganHargaJual ? 'Harga Jual' : 'Nilai' ?></th>
                </tr>
            </thead>
            <?php
            $i                   = 1;
            $nomorSebelum        = '';
            $totalQtySelisih     = 0;
            $totalNominalSelisih = 0;
            foreach ($report['detail'] as $baris):
                $nomor             = $baris['nomor'];
                $nominalTercatat   = $baris['qty_tercatat'] * $baris['harga_beli'];
                $nominalSebenarnya = $baris['qty_sebenarnya'] * $baris['harga_beli'];
                $qtySelisih        = $baris['qty_sebenarnya'] - $baris['qty_tercatat'];
                $nominalSelisih    = $qtySelisih * $baris['harga_beli'];
                if ($nilaiDenganHargaJual) {
                    $nominalTercatatHJ   = $baris['qty_tercatat'] * $baris['harga_jual'];
                    $nominalSebenarnyaHJ = $baris['qty_sebenarnya'] * $baris['harga_jual'];
                    $nominalSelisih      = $qtySelisih * $baris['harga_jual'];
                }
                $totalQtySelisih     += $qtySelisih;
                $totalNominalSelisih += $nominalSelisih;
                ?>
                <tr class="<?php echo $i % 2 === 0 ? '' : 'alt'; ?>">
                    <td class="rata-kanan"><?= $i; ?></td>
                    <td>
                        <?php
                        if ($nomorSebelum != $nomor) {
                            echo $baris['nomor'];
                            if (!empty($baris['keterangan'])) {
                                echo ' (' . $baris['keterangan'] . ')';
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($nomorSebelum != $nomor) {
                            echo $baris['tanggal'];
                        }
                        ?>
                    </td>
                    <td><?= $baris['barcode']; ?></td>
                    <td><?= $baris['nama']; ?> </td>
                    <td class="rata-kanan"><?php echo number_format($baris['qty_tercatat'], 0, ',', '.'); ?></td>
                    <td class="rata-kanan"><?php echo number_format($nominalTercatat, 0, ',', '.'); ?></td>
                    <?php if ($nilaiDenganHargaJual) { ?><td class="rata-kanan"><?php echo number_format($nominalTercatatHJ, 0, ',', '.'); ?></td><?php } ?>
                    <td class="rata-kanan"><?php echo number_format($baris['qty_sebenarnya'], 0, ',', '.'); ?></td>
                    <td class="rata-kanan"><?php echo number_format($nominalSebenarnya, 0, ',', '.'); ?></td>
                    <?php if ($nilaiDenganHargaJual) { ?><td class="rata-kanan"><?php echo number_format($nominalSebenarnyaHJ, 0, ',', '.'); ?></td><?php } ?>
                    <td class="rata-kanan"><?php echo number_format($qtySelisih, 0, ',', '.'); ?></td>
                    <td class="rata-kanan"><?php echo number_format($nominalSelisih, 0, ',', '.'); ?></td>
                </tr>

                <?php
                $nomorSebelum = $nomor;
                $i++;
            endforeach;
            ?>
            <tr>
                <td colspan="<?php echo $nilaiDenganHargaJual ? 11 : 9 ?>" class="rata-tengah">T O T A L</td>
                <td class="rata-kanan"><?php echo number_format($totalQtySelisih, 0, ',', '.'); ?></td>
                <td class="rata-kanan"><?php echo number_format($totalNominalSelisih, 0, ',', '.'); ?></td>
            </tr>
        </table>
    </body>
</html>