<?php
/* @var $this AkmController */

$this->breadcrumbs = array(
    'AKM',
);


$this->boxHeader['small'] = 'Anjungan Kasir Mandiri';
$this->boxHeader['normal'] = 'AKM';

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/akm.css');
?>

<div class="content akm index">
    <h1>Anjungan Kasir Mandiri</h1>
    <h2>Open</h2>
    <h4>Touch to start</h4>
    <h6><?= $namaToko ?></h6>
</div>
<script>
    $("body").click(function () {
        $('.content.akm.index').html('<h4>Loading..</h4>');
        window.location = "<?php echo $this->createUrl('input'); ?>";
    });
</script>