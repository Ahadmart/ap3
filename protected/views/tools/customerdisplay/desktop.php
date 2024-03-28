<?php
/* @var $this CustomerdisplayController */

// $this->breadcrumbs = array(
//     'Customerdisplay',
// );
?>

<div class="row" style="height: 25vh">
    <div class="medium-8 columns box kiri_atas">
        <div id="welcome">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo.png" alt="logo" />
            <h1>Selamat datang di <?= $namaToko ?></h1>
        </div>
    </div>
    <div class="medium-4 columns box kanan_atas">
        <!-- <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo.png" alt="logo" /> -->
        <div id="info_kasir">
            <p>Anda sedang dilayani oleh</p>
            <p><?= "{$user['namaLengkap']} [#{$user['id']}]" ?></p>
            <p>Selamat berbelanja di <?= $namaToko ?></p>
        </div>
    </div>
</div>
<div class="row" style="height:75vh">
    <div class="medium-8 columns box kiri_bawah">
        <h4><?= $ipServer ?></h4>
        <p>User: <?= $user['namaLengkap'] ?> (<?= $user['id'] ?>)</p>
    </div>
    <div class="medium-4 columns box kanan_bawah">
    </div>
</div>