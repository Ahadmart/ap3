<?php
/* @var $this AppController */

$this->pageTitle = Yii::app()->name;

$this->boxHeader['small'] = $this->pageTitle;
$this->boxHeader['normal'] = $this->pageTitle;
?>
<div class="row">
    <div class="small-12 columns">
        <?php
        if (Yii::app()->user->isGuest) :
            ?>
            <p>
                Silahkan login untuk mengakses aplikasi
            </p>
            <?php
        else :
            ?>
            <span class="label"><h4>Login Details</h4></span>
            <br /><br />
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
//            if (!$first) {
//               echo ', ';
//            }
                    $first = false;
                    echo $role['itemname'];
                    ?>
                    </span><?php
                endforeach;
                ?>
            </p>
            <p>                
                <span class="secondary label">Powered by</span><span class="success label">Yii Framework</span>
                <span class="secondary label">Sponsored by</span><span class="warning label">Ahadmart</span>
            </p>
        <?php
        endif;
        ?>
    </div>
</div>
