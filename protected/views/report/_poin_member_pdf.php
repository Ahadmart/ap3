<html>
    <head>
        <title>Poin Member : <?php echo $config['toko.nama'] . ' | ' . $waktuCetak; ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pdf-tabel.css" />
    </head>
    <body>
        <!--mpdf
            <htmlpagefooter name="footer">
                <table style="border-top:thin solid black">
                    <tr>
                        <td style="text-align:left">Poin Member | <?php
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
            <div>Poin Member <?php echo $config['toko.nama']; ?></div>
        </div>
        <br />
        <br />
        <table style="margin:0 auto" class="table-bordered bordered">
            <thead>
                <tr>
                    <th class="rata-kanan">#</th>
                    <th>Nomor</th>
                    <th>Nama</th>
                    <th class="rata-kanan">Jumlah Poin</th>
                    <th>Alamat</th>
                    <th>Telp</th>
                    <th>Hp</th>
                    <th>Email</th>
                    <th>ID</th>
                    <th>Tgl Lahir</th>
                </tr>
            </thead>
            <?php
            $i = 1;
            foreach ($report as $member):
                ?>
                <tr class="<?php echo $i % 2 === 0 ? '' : 'alt'; ?>">
                    <td class="rata-kanan"><?= $i; ?></td>
                    <td><?= $member['nomor']; ?></td>
                    <td><?= $member['nama']; ?></td>
                    <td class="rata-kanan"><?= $member['poin']; ?></td>
                    <td>
                        <?php echo $member['alamat1']; ?>
                        <?php echo $member['alamat2'] <> '' ? '<br />' . $member['alamat2'] : ''; ?>
                        <?php echo $member['alamat3'] <> '' ? '<br />' . $member['alamat3'] : ''; ?>
                    </td>
                    <td><?= $member['telp']; ?></td>
                    <td><?= $member['hp']; ?></td>
                    <td><?= $member['surel']; ?></td>
                    <td><?= $member['identitas']; ?></td>
                    <td><?= $member['tanggal_lahir']; ?></td>
                </tr>

                <?php
                $i++;
            endforeach;
            ?>
        </table>
    </body>
</html>