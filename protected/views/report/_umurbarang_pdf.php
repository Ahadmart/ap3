<?php

function namaBulan($i)
{
    static $bulan = [
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember",
    ];
    return $bulan[$i - 1];
}

function toIndoDate($timeStamp)
{
    $tanggal   = date_format(date_create($timeStamp), 'j');
    $bulan     = date_format(date_create($timeStamp), 'n');
    $namabulan = namaBulan($bulan);
    $tahun     = date_format(date_create($timeStamp), 'Y');
    return $tanggal . ' ' . $namabulan . ' ' . $tahun;
}

function namaHari($timeStamp)
{
    static $hari = [
        'Ahad',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu',
    ];
    return $hari[date('w', strtotime($timeStamp))];
}
?>
<html>

<head>
    <title>Umur Barang : <?php echo $config['toko.nama'] . ' | ' . $waktuCetak; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pdf-tabel.css" />
</head>

<body>
    <!--mpdf
            <htmlpagefooter name="footer">
                <table style="border-top:thin solid black">
                    <tr>
                        <td style="text-align:left">Umur Barang | <?php
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
        <div>Laporan Umur Barang <i>(Aging Of Inventory)</i> <?php echo $config['toko.nama']; ?></div>
        <div id="tanggal"><?php echo namaHari($waktu) . ', ' . toIndoDate($waktu); ?></div>
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
    </div>
    <br />
    <br />

    <table style="margin:0 auto" class="table-bordered bordered">
        <thead>
            <tr>
                <th rowspan="2" class="rata-kanan">No</th>
                <th rowspan="2">Barcode</th>
                <th rowspan="2">Nama</th>
                <th rowspan="2">Supplier</th>
                <th rowspan="2" class="rata-kanan">Stok</th>
                <th rowspan="2" class="rata-kanan">Nilai Stok</th>
                <th colspan="2" class='rata-tengah'>Umur Stok dalam</th>
                <th rowspan="2" class="rata-kanan">Total Stok</th>
            </tr>
            <tr>
                <th class="rata-kanan">Hari</th>
                <th class="rata-kanan">Bulan</th>
            </tr>
        </thead>
        <?php
        $i = 1;
        foreach ($report as $baris) :
        ?>
            <tr class="<?php echo $i % 2 === 0 ? '' : 'alt'; ?>">
                <td class="rata-kanan"><?php echo $i; ?></td>
                <td><?= $baris['barcode']; ?></td>
                <td><?= $baris['nama']; ?></td>
                <td><?= $baris['supplier']; ?></td>
                <td class="rata-kanan"><?= number_format($baris['qty'], 0, ',', '.'); ?></td>
                <td class="rata-kanan"><?= number_format($baris['nominal'], 0, ',', '.'); ?></td>
                <td class="rata-kanan"><?= number_format($baris['umur_hari'], 0, ',', '.'); ?></td>
                <td class="rata-kanan"><?= number_format($baris['umur_bulan'], 0, ',', '.'); ?></td>
                <td class="rata-kanan"><?= number_format($baris['total_stok'], 0, ',', '.'); ?></td>
            </tr>

        <?php
            $i++;
        endforeach;
        ?>
    </table>
</body>

</html>