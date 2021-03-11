<?php
/* @var $this ReportController */

$this->breadcrumbs = array(
    'Laporan' => array('index'),
    'Total Stok',
);

$this->boxHeader['small'] = 'Total Stok';
$this->boxHeader['normal'] = '<i class="fa fa-database fa-lg"></i> Laporan Total Stok';
?>
<div class="row">
    <div class="small-12 columns">
        <p>
        <h1 style="text-align: right"><small>Total Stok:</small> <?php echo number_format($totalStok, 0, ',', '.'); ?></h1>
        <h2 style="text-align: right"><small>Total Stok di Retur Pembelian (posted):</small> <?php echo number_format($stokReturBeli, 0, ',', '.'); ?></h2>
        <h2 style="text-align: right"><small>Total Stok Net:</small> <?php echo number_format($stokNet, 0, ',', '.'); ?></h2>
        </p>
    </div>
</div>