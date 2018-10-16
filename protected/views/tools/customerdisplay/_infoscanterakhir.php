<?php
$hargaNet      = $detailModel->harga_jual;
$diskon        = $detailModel->diskon;
$hargaBukanNet = '';
if ($diskon > 0) {
    $hargaBukanNet = number_format($detailModel->harga_jual + $diskon, 0, ',', '.');
}
$totalNet     = $hargaNet * $detailModel->qty;
$qtyText      = number_format($detailModel->qty, 0, ',', '.');
$totalNetText = number_format($totalNet, 0, ',', '.');
$hargaNetText = number_format($hargaNet, 0, ',', '.');
$hargaText    = $hargaNetText . ' x ' . $qtyText . ' = ' . $totalNetText;
?>


<h1 class="nama-barang" style="font-size: 2.5rem"><?php echo $detailModel->barang->nama; ?></h1>
<h1 style="text-align: right;font-size: 3rem" class="harga-jual">
    <span style="text-decoration: line-through; color:#b71c1c"><?php echo $hargaBukanNet; ?></span>
    <span style=""><?php echo $hargaText; ?></span>
</h1>