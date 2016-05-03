<?php
/* @var $this CustomerdisplayController */

$this->breadcrumbs = array(
    'Customerdisplay',
);
?>

<img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo.png" alt="logo" style="margin: 10px"/>
<div class="info" style="padding: 10px; margin-top: 20px;">
    <h1 class="nama-barang" style="font-size: 2.5rem"><?php echo $detailModel->barang->nama; ?></h1>
    <h1 style="text-align: right;font-size: 3rem" class="harga-jual"><span style="text-decoration: line-through; color:#b71c1c"><?php echo number_format($detailModel->harga_jual, 0, ',', '.'); ?></span>
        <span style=""><?php echo number_format($detailModel->harga_jual - $detailModel->diskon, 0, ',', '.'); ?></span></h1>
    <h1 style="text-align: right" class="harga-net"></h1>
</div>

<script>

</script>