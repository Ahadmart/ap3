<?php

function toIndoDate($timeStamp)
{
    $tanggal = date_format(date_create($timeStamp), 'j');
    $bulan = date_format(date_create($timeStamp), 'n');
    $namabulan = namaBulan($bulan);
    $tahun = date_format(date_create($timeStamp), 'Y');
    return $tanggal . ' ' . $namabulan . ' ' . $tahun;
}

function namaBulan($i)
{
    static $bulan = array(
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
        "Desember"
    );
    return $bulan[$i - 1];
}
?>
<html>
    <head>
        <title>Retur Pembelian : <?php echo $modelHeader->nomor; ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pdf.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.css" />
    </head>
    <body>
        <!--mpdf
            <htmlpagefooter name="footer">
                <table style="border-top:thin solid black">
                    <tr>
                        <td style="text-align:left">Retur Pembelian No. <?php
        echo $modelHeader->nomor == '' ? '..................' : $modelHeader->nomor;
        ?>
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
            <div class="smaller">RETUR PEMBELIAN</div>
            <barcode style="margin-left: -13px;" code="<?php echo $modelHeader->nomor; ?>" type="C128A" class="barcode" size="0.9" height="1" />
        </div>
        <div id="dari">
            <div class="nama-toko"><?php echo $branchConfig['toko.nama']; ?></div>
            <span class="alamat"><?php echo "{$branchConfig['toko.alamat1']}, {$branchConfig['toko.alamat2']}, {$branchConfig['toko.alamat3']}"; ?></span>
            <div><?php echo $branchConfig['toko.telp']; ?></div>
            <div class="email"><?php echo $branchConfig['toko.email']; ?></div>
        </div>
        <div class="garis">
        </div>
        <div id="kepada">
            Kepada<br />
            <span class="nama-customer"><?php echo $profil->nama; ?></span><br />
            <span class="alamat"><?php echo $profil->alamat1; ?><?php echo is_null($profil->alamat2) || $profil->alamat2 == '' ? '' : ", {$profil->alamat2}"; ?><?php echo is_null($profil->alamat3) || $profil->alamat3 == '' ? '' : ", {$profil->alamat3}"; ?>
            </span><br />
            <?php echo $profil->telp; ?>
        </div>
        <div id="faktur-info">
            <table>
                <tr>
                    <td>Nomor</td>
                    <td><b><?php echo $modelHeader->nomor; ?></b></td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td><b><?php echo toIndoDate($modelHeader->tanggal); ?></b></td>
                </tr>
                <?php
                if (isset($modelHeader->referensi) && !empty($modelHeader->referensi)):
                    ?>
                    <tr>
                        <td>Ref</td>
                        <td><b><?php echo $modelHeader->referensi; ?></b></td>
                    </tr>
                    <tr>
                        <td>Tgl Ref</td>
                        <td><b><?php echo toIndoDate($modelHeader->tanggal_referensi); ?></b></td>
                    </tr>
                    <?php
                endif;
                ?>
                <tr>
                    <td colspan="2" id="header-total">
                        Total<br />
                        <span id="total">IDR. <?php echo $modelHeader->total; ?></span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="clear">
            <br />
            <table id="tdetail">
                <thead>
                    <tr>
                        <th style="width:5%">
                            No
                        </th>
                        <th style="width:15%">
                            Barcode
                        </th>
                        <th style="width:40%">
                            Nama Barang
                        </th>
                        <th>
                            Harga
                        </th>
                        <th>
                            Qty
                        </th>
                        <th>
                            Sub Total
                        </th>
                    </tr>
                </thead>
                <tr>
                    <?php
                    for ($i = 1; $i <= 6; $i++) {
                        ?>
                        <td style="padding:3px 0px"></td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
                $i = 1;
                $total = 0;
                foreach ($returPembelianDetail as $row) {
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td style="text-align:left;padding-left:5px"><?php echo $row->inventoryBalance->barang->barcode; ?></td>
                        <td style="text-align:left;padding-left:5px"><?php echo $row->inventoryBalance->barang->nama; ?></td>
                        <td style="text-align:right;padding-right:5px;"><?php echo number_format($row->inventoryBalance->harga_beli, 0, ",", "."); ?></td>
                        <td style="text-align:right;padding-right:5px;"><?php echo number_format($row->qty, 0, ",", "."); ?></td>
                        <td style="text-align:right;padding-right:5px;"><?php echo number_format($row->qty * $row->inventoryBalance->harga_beli, 0, ",", "."); ?></td>
                    </tr>
                    <?php
                    $total = $total + ($row->qty * $row->inventoryBalance->harga_beli);
                    $i++;
                }
                ?>
                <tr>
                    <?php
                    for ($i = 1; $i <= 6; $i++) {
                        ?>
                        <td style="padding:3px 0px"></td>
                        <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td colspan="5" style="border: thin solid black;padding:5px 0px">
                        T O T A L :
                    </td>
                    <td style="border: thin solid black;text-align: right;padding-right: 5px;">
                        <?php echo number_format($total, 0, ",", "."); ?>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>