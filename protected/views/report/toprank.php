<?php
/* @var $this ReportController */

$this->breadcrumbs = array(
    'Laporan' => array('index'),
    'Top Rank',
);

$this->boxHeader['small'] = 'Top Rank';
$this->boxHeader['normal'] = '<i class="fa fa-database fa-lg"></i> Laporan Top Rank';

$this->renderPartial('_form_toprank', array('model' => $model));

if (isset($report)):
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
                        <th class="rata-kanan">Nominal</th>
                        <th class="rata-kanan">Margin</th>
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
                            <td class="rata-kanan"><?php echo number_format($baris['margin'], 2, ',', '.'); ?></td>
                            <td class="rata-kanan"></td>
                            <td class="rata-kanan"></td>
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




endif;