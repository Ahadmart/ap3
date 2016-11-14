<?php
/* @var $this AppController */

$this->pageTitle = Yii::app()->name;

$this->boxHeader['small'] = $this->pageTitle;
$this->boxHeader['normal'] = $this->pageTitle;
?>
<div class="row">
    <div class="medium-8 columns">
        <div class="panel">
            <h4>Login Details</h4>
            <hr />
            <p>
                <span class="secondary label">Login</span><span class="success label"><?php echo Yii::app()->user->name; ?></span>
                <span class="secondary label">Nama</span><span class="primary label"><?php echo Yii::app()->user->namaLengkap; ?></span>
                <?php
                if (isset(Yii::app()->user->lastLogon)):
                    ?>
                    <span class="secondary label">Login terakhir</span><span class="primary label"><?php echo Yii::app()->user->lastLogon . ' dari ' . Yii::app()->user->lastIpaddress; ?></span>
                    <?php
                endif;
                ?>
                <br />
                <span class="secondary label">Hak Akses</span><?php
                $first = true;
                foreach ($roles as $role) :
                    ?><span class="alert label">
                    <?php
                    /*
                      if (!$first) {
                      echo ', ';
                      }
                     */
                    $first = false;
                    echo $role['itemname'];
                    ?>
                    </span><?php
                endforeach;
                ?>
            </p>
            <p style="padding-bottom: 10px">                
                <span class="secondary label">Powered by</span><span class="success label">Yii Framework</span>
                <span class="secondary label">Sponsored by</span><span class="warning label">Ahadmart</span>
            </p>
        </div>
    </div>
    <div class="medium-4 columns">
        <ul class="vcard right">
            <li class="fn"><?= $configToko['toko.nama']; ?></li>
            <li class="street-address"><?= $configToko['toko.alamat1']; ?></li>
            <li class="extended-address"><?= $configToko['toko.alamat2']; ?></li>
            <li><span class="region"><?= $configToko['toko.alamat3']; ?></span>. <span class="tel"><?= $configToko['toko.telp']; ?></span></li>
            <li class="email"><a href="#"><?= $configToko['toko.email']; ?></a></li>
        </ul>
    </div>
    <?php
    /* Tampilkan npls jika ada */
    if (!is_null($rekapAds)) {
        ?>
        <div class="small-12 columns">
            <div class="panel">
                <?php
                $this->renderPartial('_npls', array(
                    'model' => $rekapAds
                ))
                ?>
            </div>
        </div>
        <?php
    }
    ?>

</div>
