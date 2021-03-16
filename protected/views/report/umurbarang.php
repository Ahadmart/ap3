<?php
/* @var $this ReportController */

$this->breadcrumbs = [
    'Laporan' => array('index'),
    'Umur Barang',
];

$this->boxHeader['small'] = 'Umur Barang';
$this->boxHeader['normal'] = '<i class="fa fa-database fa-lg"></i> Laporan Umur Barang';

$this->renderPartial('_form_umurbarang', ['model' => $model]);

if (isset($report)) {

    $this->renderPartial('_form_umurbarang_cetak', [
        'model' => $model,
        'printers' => $printers,
        'kertasPdf' => $kertasPdf
    ]);
?>
    <div class="row">
        <div class="small-12 columns">
            <table class="tabel-index responsive">
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
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($report as $baris) :
                    ?>
                        <tr>
                            <td class="rata-kanan"><?php echo $i; ?></td>
                            <td><?php echo $baris['barcode']; ?></td>
                            <td><?php echo $baris['nama']; ?></td>
                            <td><?php echo $baris['supplier']; ?></td>
                            <td class="rata-kanan"><?php echo number_format($baris['qty'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($baris['nominal'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($baris['umur_hari'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($baris['umur_bulan'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($baris['total_stok'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php
                        $i++;
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
}
