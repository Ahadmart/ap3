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

    $this->renderPartial('_form_toprank_cetak', [
        'model' => $model,
        'kertasPdf' => $kertasPdf
    ]);
    ?>
    <div class="row">
        <div class="small-12 columns">
            <table class="tabel-index responsive">
                <thead>
                    <tr>
                        <th class="rata-kanan">No</th>
                        <th>Barcode</th>
                        <th>Nama</th>
                        <th class="rata-kanan">Qty</th>
                        <th class="rata-kanan">Omset</th>
                        <th class="rata-kanan">Profit</th>
                        <th class="rata-kanan">Avg / Day</th>
                        <th class="rata-kanan">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($report as $baris):
                        ?>
                        <tr>
                            <td class="rata-kanan"><?php echo $i; ?></td>
                            <td><?php echo $baris['barcode']; ?></td>
                            <td><?php echo $baris['nama']; ?></td>
                            <td class="rata-kanan"><?php echo number_format($baris['totalqty'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($baris['total'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($baris['margin'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($baris['avgday'], 2, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($baris['stok'], 0, ',', '.'); ?></td>
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
