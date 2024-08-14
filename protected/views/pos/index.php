<?php
/* @var $this PosController */
/* @var $model Penjualan */

$this->breadcrumbs = array(
    'Penjualan' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Penjualan';
$this->boxHeader['normal'] = 'Penjualan';
?>
<script>
    $(function() {
        $("#tombol-new").focus();
    });
    setTimeout(
        function() {
            window.location.href = '<?= $this->createUrl('/pos/suspended') ?>';
        },
        20000);
</script>