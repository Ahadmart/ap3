<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo CHtml::encode($this->pageTitle); ?>
    </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/favicon-16x16.png" sizes="16x16">

    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/normalize.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/foundation.css">
    <!--<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/animate.min.css">-->
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/app.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pos.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/jquery-ui-ac.min.css">
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/modernizr.js">
    </script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-ui.min-ac.js">
    </script>
    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
</head>

<body>
    <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
    <div class="off-canvas-wrap" data-offcanvas>
        <div class="inner-wrap">
            <nav class="tab-bar">
                <section class="left-small">
                    <a class="left-off-canvas-toggle menu-icon"><span></span></a>
                </section>

                <section class="middle tab-bar-section" style="text-align: left">
                    <span class="title"><a href="<?php echo Yii::app()->baseUrl; ?>"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo.png" alt="logo" /></a></span>
                    <span class="right" style="font-size: 0.65em"><i class="fa fa-user fa-fw"></i> <?php echo Yii::app()->user->namaLengkap; ?><?php // &nbsp;&nbsp;<i class="fa fa-clock-o fa-fw"></i> <span id="clock">00:00:00</span>
                                                                                                                                                ?>
                    </span>
                </section>

                <section class="right-small">
                    <a class="right-off-canvas-toggle menu-icon"><span></span></a>
                </section>
            </nav>

            <aside class="left-off-canvas-menu">
                <ul class="stack button-group">
                    <li><a href="<?php echo $this->createUrl('tambah'); ?>" class="expand bigfont tiny secondary button" accesskey="n" id="tombol-new"><span class="ak">N</span>ew</a></li>
                    <li><a href="<?php echo $this->createUrl('suspended'); ?>" class="expand bigfont tiny secondary button" accesskey="s"><span class="ak">S</span>uspended</a></li>
                    <li><a href="<?php echo $this->createUrl('cekharga'); ?>" class="expand bigfont tiny secondary button" accesskey="h">Cek <span class="ak">H</span>arga</a></li>
                    <li><a href="<?php echo $this->createUrl('pesanan'); ?>" class="expand bigfont tiny secondary button" accesskey="p"><span class="ak">P</span>esanan (Sales
                            Order)</a></li>
                </ul>
                <?php if (!is_null($this->namaProfil)) {
                ?>
                    <?php
                    if ($this->showMember == 1) {
                    ?>
                        <form id="form-nomor-customer">
                            <div class="row collapse" id="ganti-customer" style="display: none">
                                <div class="small-9 large-10 columns">
                                    <input type="text" name="nomor-customer" id="nomor-customer" placeholder="Input nomor" accesskey="r" autocomplete="off" />
                                </div>
                                <div class="small-3 large-2 columns">
                                    <a href="#" class="button postfix" id="tombol-ganti-customer"><i class="fa fa-check"></i></a>
                                </div>
                            </div>
                        </form>
                        <span class="label" id="label-customer" accesskey="e">Custom<span class="ak">e</span>r</span>
                    <?php
                    }
                    ?>
                    <?php
                    if ($this->showMemberOL == 1) {
                    ?>
                        <form id="form-nomor-member">
                            <div class="row collapse" id="ganti-member" style="display: none">
                                <div class="small-9 large-10 columns">
                                    <input type="text" name="nomor-member" id="nomor-member" placeholder="No Member/No Telp" autocomplete="off" />
                                </div>
                                <div class="small-3 large-2 columns">
                                    <a href="#" class="button postfix" id="tombol-ganti-member"><i class="fa fa-check"></i></a>
                                </div>
                            </div>
                        </form>
                        <span class="label" id="label-member" accesskey="r">Membe<span class="ak">r</span> Online</span>
                    <?php
                    }
                    ?>
                    <?php
                    if ($this->showMember == 1) {
                    ?>
                        <div id="data-customer">
                            <nomor>Nomor: <?php echo $this->profil->nomor; ?>
                            </nomor>
                            <nama><?php echo $this->namaProfil; ?>
                            </nama>
                            <address>
                                <?php echo !empty($this->profil->alamat1) ? $this->profil->alamat1 : ''; ?>
                                <?php echo !empty($this->profil->alamat2) ? '<br>' . $this->profil->alamat2 : ''; ?>
                                <?php echo !empty($this->profil->alamat3) ? '<br>' . $this->profil->alamat3 : ''; ?>
                            </address>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if ($this->showMemberOL == 1) {
                    ?>
                        <div id="data-member">
                            <nomor>Member Online: <?= is_null($this->memberOnline) ? '' : $this->memberOnline->nomor ?> </nomor>
                            <nama><?= is_null($this->memberOnline) ? '-' : $this->memberOnline->namaLengkap ?> </nama>
                            <address><?= is_null($this->memberOnline) ? '' : $this->memberOnline->alamat ?> </address>
                            <info><?= is_null($this->memberOnline) ? '' : 'Level: ' . $this->memberOnline->level .' ('.$this->memberOnline->levelNama . ')<br />Poin: ' . $this->memberOnline->poin . ', Koin: <span id="info-koinmol">' . $this->memberOnline->koin . '</span>' ?>
                            </info>
                        </div>
                    <?php
                    }
                    ?>
                    <form id="form-admin-login">
                        <div class="row admin-input" style="display: none">
                            <div class="small-12">
                                <input type="text" name="admin-user" id="admin-user" placeholder="Nama user" autocomplete="off" />
                            </div>
                        </div>
                        <div class="row collapse admin-input" style="display: none">
                            <div class="small-9 large-10 columns">
                                <input type="password" name="admin-password" id="admin-password" placeholder="Password" autocomplete="off" />
                            </div>
                            <div class="small-3 large-2 columns">
                                <a href="#" class="button postfix" id="tombol-admin-login"><i class="fa fa-check"></i></a>
                            </div>
                        </div>
                    </form>
                    <?php
                    $posModeAdmin = Yii::app()->user->getState('posModeAdminAlwaysON');
                    if (!$posModeAdmin) {
                    ?>
                        <ul class="stack button-group">
                            <li><a href="" class="expand bigfont tiny <?php echo Yii::app()->user->getState('kasirOtorisasiAdmin') == $this->penjualanId ? 'warning' : ''; ?> button" id="tombol-admin-mode" accesskey="m">Mode Ad<span class="ak">m</span>in</a></li>
                        </ul>
                    <?php
                    } ?>
                    <form id="form-akm">
                        <div class="row collapse akm-input" style="display: none">
                            <div class="small-9 large-10 columns">
                                <input type="text" name="akm-no" id="akm-no" placeholder="No AKM" autocomplete="off" />
                            </div>
                            <div class="small-3 large-2 columns">
                                <a href="#" class="button postfix" id="tombol-akm-ok"><i class="fa fa-check"></i></a>
                            </div>
                        </div>
                    </form>
                    <ul class="stack button-group">
                        <li><a href="" class="expand bigfont tiny button" id="tombol-akm" accesskey="k">Input A<span class="ak">K</span>M</a></li>
                    </ul>
                    <form id="form-pesanan">
                        <div class="row collapse pesanan-input" style="display: none">
                            <div class="small-9 large-10 columns">
                                <input type="text" name="pesanan-no" id="pesanan-no" placeholder="No pesanan" autocomplete="off" />
                            </div>
                            <div class="small-3 large-2 columns">
                                <a href="#" class="button postfix" id="tombol-pesanan-ok"><i class="fa fa-check"></i></a>
                            </div>
                        </div>
                    </form>
                    <ul class="stack button-group">
                        <li><a href="" class="expand bigfont tiny button" id="tombol-pesanan" accesskey="i"><span class="ak">I</span>nput Pesanan</a></li>
                    </ul>
                <?php
                }
                ?>
            </aside>

            <aside class="right-off-canvas-menu">
                <ul class="off-canvas-list">
                    <li><label><?= Yii::app()->user->namaLengkap ?></label>
                    </li>
                    <li><a href="<?= $this->createUrl('/user/ubah', ['id' => Yii::app()->user->id]) ?>">Profile</a>
                    </li>
                    <li><a href="<?= $this->createUrl('/app/logout') ?>">Logout</a>
                    </li>
                    <?php
                    if ($this->showPembayaran) {
                    ?>
                        <li><label>Pembayaran</label></li>
                    <?php
                    }
                    ?>
                </ul>
                <?php
                if ($this->showPembayaran) :
                ?>
                    <div id="subtotal-belanja" style=" display: none; opacity: 0">
                        <span class="left">Sub Total</span><span class="angka"><?php echo $this->totalPenjualan; ?></span>
                    </div>
                    <div id="total-belanja">
                        <?php echo $this->totalPenjualan; ?>
                    </div>
                    <div id="kembali" class="negatif">
                        0
                    </div>
                    <div class="row collapse">
                        <?php /* Jenis Pembayaran */ ?>
                        <div class="small-3 large-2 columns">
                            <span class="prefix"><i class="fa fa-2x fa-chevron-right"></i></span>
                        </div>
                        <div class="small-6 large-7 columns">
                            <?php
                            echo CHtml::dropDownList('jenisbayar', 1, CHtml::listData(JenisTransaksi::model()->findAll(), 'id', 'nama'), [
                                'accesskey' => 'd',
                                'id'        => 'jenisbayar',
                            ]);
                            ?>
                        </div>
                        <div class="small-3 large-3 columns">
                            <span class="postfix">[D]</span>
                        </div>
                    </div>
                    <?php
                    if ($this->showDiskonPerNota) {
                    ?>
                        <div class="row collapse">
                            <div class="small-3 large-2 columns">
                                <!--<span class="prefix"><i class="fa fa-2x fa-chevron-down"></i></span>-->
                                <span class="prefix">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-tag fa-stack-2x"></i>
                                        <i class="fa fa-percent fa-inverse fa-stack-1x" style="transform: rotate(-45deg)"></i>
                                    </span>
                                </span>
                            </div>
                            <div class="small-9 large-10 columns">
                                <input type="text" id="diskon-nota" placeholder="Diskon pe[r] Nota" accesskey="r" />
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if ($this->showTarikTunai) {
                    ?>
                        <div class="row collapse input-tarik-tunai" style="display: none">
                            <?php /* Company account */ ?>
                            <div class="small-3 large-2 columns">
                                <span class="prefix"><i class="fa fa-2x fa-exchange"></i></span>
                            </div>
                            <div class="small-4 large-5 columns">
                                <?php
                                echo CHtml::dropDownList('account', 1, CHtml::listData(KasBank::model()->kecualiKas()->findAll(), 'id', 'nama'), [
                                    'accesskey' => 'a',
                                    'class'     => 'account',
                                ]); ?>
                            </div>
                            <div class="small-5 large-5 columns">
                                <input type="text" id="tarik-tunai" class="tarik-tunai" name="tarik-tunai" placeholder="Tarik Tunai" />
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <div id="uang-dibayar-master">
                        <div class="row collapse input-uang-dibayar">
                            <?php /* Company account */ ?>
                            <div class="small-3 large-2 columns">
                                <span class="prefix"><i class="fa fa-2x fa-arrow-right"></i></span>
                            </div>
                            <div class="small-4 large-5 columns">
                                <?php
                                echo CHtml::dropDownList('account', 1, CHtml::listData(KasBank::model()->findAll(), 'id', 'nama'), [
                                    'accesskey' => 'a',
                                    'class'     => 'account',
                                ]);
                                ?>
                            </div>
                            <div class="small-5 large-5 columns">
                                <input type="text" class="uang-dibayar" name="kasbank[]" placeholder="[U]ang Dibayar" accesskey="u" />
                            </div>
                        </div>
                    </div>
                    <div id="uang-dibayar-clone">
                    </div>

                    <?php
                    if ($this->showInfaq) {
                    ?>
                        <div class="row collapse">
                            <div class="small-3 large-2 columns">
                                <span class="prefix"><i class="fa fa-2x fa-chevron-up"></i></span>
                            </div>
                            <div class="small-9 large-10 columns">
                                <input type="text" id="infaq" placeholder="In[f]ak/Sedekah" accesskey="f" />
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <a href="" class="success bigfont tiny button" id="tombol-simpan" style="width: 6.5rem">Simpan</a>
                    <a href="" class="warning bigfont tiny button" id="tombol-batal" style="width: 6.5rem">Batal</a>\
                <?php
                endif;
                ?>
            </aside>

            <section class="main-section">
                <?php echo $content;
                ?>
            </section>

            <a class="exit-off-canvas"></a>

        </div>
    </div>

    ?>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/foundation.min.js">
    </script>
    <!--<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/foundation/foundation.offcanvas.js">
    </script>-->
    <script>
        $(document).foundation({
            offcanvas: {
                // Sets method in which offcanvas opens.
                // [ move | overlap_single | overlap ]
                open_method: 'move',
                // Should the menu close when a menu link is clicked?
                // [ true | false ]
                close_on_click: true
            }
        });

        function updateClock() {
            var currentTime = new Date();
            var currentHours = currentTime.getHours();
            var currentMinutes = currentTime.getMinutes();
            var currentSeconds = currentTime.getSeconds();

            // Pad the minutes and seconds with leading zeros, if required
            currentMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;
            currentSeconds = (currentSeconds < 10 ? "0" : "") + currentSeconds;

            // Choose either "AM" or "PM" as appropriate
            var timeOfDay = (currentHours < 12) ? "AM" : "PM";

            // Convert the hours component to 12-hour format if needed
            // currentHours = (currentHours > 12) ? currentHours - 12 : currentHours;

            // Convert an hours component of "0" to "12"
            currentHours = (currentHours == 0) ? 12 : currentHours;
            currentHours = (currentHours < 10 ? "0" : "") + currentHours;

            // Compose the string for display
            var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " "; // + timeOfDay;

            $("#clock").html(currentTimeString);
        }

        $(document).ready(function() {
            setInterval('updateClock()', 1000);
        });
    </script>
    <?php
    /*
<script>window.jQuery || document.write('<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.js"><\/script>')</script>
 */
    ?>
</body>

</html>