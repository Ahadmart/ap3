<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="theme-color" content="#ff5722" />

        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/normalize.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/foundation.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.css">
        <?php
        Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/app.css');
        ?>
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/apple-touch-icon-180x180.png">
        <link rel="icon" type="image/png" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/android-chrome-192x192.png" sizes="192x192">
        <link rel="icon" type="image/png" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/manifest.json">
        <meta name="msapplication-TileColor" content="#603cba">
        <meta name="msapplication-TileImage" content="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/mstile-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <?php
        /* force browser to update favicon
          <link rel="shortcut icon" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/fav/favicon.ico?v=2" />
         */
        ?>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/modernizr.js"></script>
        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    </head>
    <body>
        <!--[if lt IE 7]>
             <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <?php
        // Jika yang diakses halaman login, top navbar tidak ditampilkan
        if (Yii::app()->controller->action->id != 'login'):
            ?>
            <div class="fixed">
                <nav class="top-bar" data-options="is_hover: true" data-topbar>
                    <ul class="title-area">
                        <li class="name">
                            <h1>
                                <a href="<?php echo Yii::app()->baseUrl; ?>">
                                    <?php //echo CHtml::encode(Yii::app()->name); ?>
                                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo.png" alt="logo" />
                                </a>
                            </h1>
                        </li>
                        <li class="toggle-topbar menu-icon"><a href="#"><span></span></a></li>
                    </ul>

                    <section class="top-bar-section">

                        <?php
                        if (!Yii::app()->user->isGuest) {
                            // Jika sudah login, tampilkan menu
                            $this->widget('zii.widgets.CMenu', array(
                                'htmlOptions' => array('class' => 'left'),
                                'activateParents' => true,
                                'encodeLabel' => false,
                                'id' => '',
                                'items' => array(
                                    array('itemOptions' => array('class' => 'divider'), 'label' => ''),
                                    /*
                                      array('label' => '<i class="fa fa-bar-chart-o fa-fw"></i>'.' Dashboard', 'url' => array('/dashboard/index'),),
                                      array('itemOptions' => array('class' => 'divider'), 'label' => ''),
                                     *
                                     */
                                    array('label' => '<i class="fa fa-cogs fa-fw fa-lg"></i>' . ' Config', 'url' => '',
                                        'items' => array(
                                            array('label' => 'Barang', 'url' => '',
                                                'items' => array(
                                                    array('label' => '<i class="fa fa-barcode fa-fw"></i>' . ' Barang', 'url' => array('/barang/index')),
                                                    array('label' => '<i class="fa fa-tag fa-fw"></i>' . ' Satuan', 'url' => array('/satuanbarang/index')),
                                                    array('label' => '<i class="fa fa-tags fa-fw"></i>' . ' Kategori', 'url' => array('/kategoribarang/index')),
                                                    array('label' => '<i class="fa fa-server fa-fw"></i>' . ' Rak', 'url' => array('/rakbarang/index')),
                                                    array('label' => '<i class="fa fa-barcode fa-fw"></i>' . ' Diskon', 'url' => array('/diskonbarang/index')),
                                                ),
                                                'itemOptions' => array('class' => 'has-dropdown'),
                                                'submenuOptions' => array('class' => 'dropdown'),
                                            ),
                                            array('label' => 'Keuangan', 'url' => '',
                                                'items' => array(
                                                    array('label' => '<i class="fa fa-credit-card fa-fw"></i>' . ' Kas/Bank', 'url' => array('/kasbank/index')),
                                                    array('label' => '<i class="fa fa-credit-card fa-fw"></i>' . ' Jenis Transaksi', 'url' => array('/jenistransaksi/index')),
                                                    array('label' => '<i class="fa fa-credit-card fa-fw"></i>' . ' Kategori Pengeluaran', 'url' => array('/kategoripengeluaran/index')),
                                                    array('label' => '<i class="fa fa-credit-card fa-fw"></i>' . ' Kategori Penerimaan', 'url' => array('/kategoripenerimaan/index')),
                                                    array('itemOptions' => array('class' => 'divider'), 'label' => ''),
                                                    array('label' => '<i class="fa fa-book fa-fw"></i>' . ' Item Pengeluaran', 'url' => array('/itempengeluaran/index')),
                                                    array('label' => '<i class="fa fa-book fa-fw"></i>' . ' Item Penerimaan', 'url' => array('/itempenerimaan/index')),
                                                // array('label' => '<i class="fa fa-book fa-fw"></i>'.' Kode Akun', 'url' => ''),
                                                ),
                                                'itemOptions' => array('class' => 'has-dropdown'),
                                                'submenuOptions' => array('class' => 'dropdown'),
                                            ),
                                            array('label' => 'Akses', 'url' => '',
                                                'items' => array(
                                                    array('label' => '<i class="fa fa-user fa-fw"></i>' . ' User', 'url' => array('/user/index')),
                                                    array('label' => '<i class="fa fa-shield fa-fw"></i>' . ' Otorisasi Item', 'url' => array('/auth/item/index')),
                                                    array('label' => '<i class="fa fa-user-plus fa-fw"></i>' . ' User Assignment', 'url' => array('/auth/assignment/index')),
                                                ),
                                                'itemOptions' => array('class' => 'has-dropdown'),
                                                'submenuOptions' => array('class' => 'dropdown'),
                                            ),
                                            array('label' => '<i class="fa fa-sliders fa-fw"></i>' . ' Aplikasi', 'url' => array('/config/index')),
                                            array('label' => '<i class="fa fa-cog fa-fw"></i>' . ' Devices', 'url' => array('/device/index')),
                                            array('label' => '<i class="fa fa-user fa-fw"></i>' . ' Profil', 'url' => array('/profil/index'),
                                            /*
                                              'items' => array(
                                              array('label' => '<i class="fa fa-user fa-fw"></i>'.' Profil', 'url' => array('/profil/index')),
                                              array('label' => '<i class="fa fa-truck fa-fw"></i>'.' Supplier', 'url' => array('/supplier/index')),
                                              array('label' => '<i class="fa fa-users fa-fw"></i>'.' Customer', 'url' => array('/customer/index')),
                                              array('label' => '<i class="fa fa-tty fa-fw"></i>'.' Karyawan', 'url' => array('/karyawan/index')),
                                              ),
                                              'itemOptions' => array('class' => 'has-dropdown'),
                                              'submenuOptions' => array('class' => 'dropdown'),
                                             *
                                             */
                                            ),
                                            array('label' => '<i class="fa fa-ticket fa-fw"></i>' . ' Member', 'url' => array('/member/index')),
                                        ),
                                        'itemOptions' => array('class' => 'has-dropdown'),
                                        'submenuOptions' => array('class' => 'dropdown'),
                                    ),
                                    array('itemOptions' => array('class' => 'divider'), 'label' => ''),
                                    array('label' => '<i class="fa fa-calculator fa-fw fa-lg"></i>' . ' Transaksi', 'url' => '',
                                        'items' => array(
                                            array('label' => '<i class="fa fa-truck fa-fw"></i>' . ' Pembelian', 'url' => array('/pembelian/index')),
                                            array('label' => '<i class="fa fa-truck fa-flip-horizontal fa-fw"></i>' . ' Retur Pembelian', 'url' => array('/returpembelian/index')),
                                            array('label' => '<i class="fa fa-shopping-cart fa-fw"></i>' . ' Penjualan', 'url' => array('/penjualan/index')),
                                            array('label' => '<i class="fa fa-shopping-cart fa-flip-horizontal fa-fw"></i>' . ' Retur Penjualan', 'url' => array('/returpenjualan/index')),
                                            array('label' => '<i class="fa fa-check-square-o fa-fw"></i>' . ' Stock Opname', 'url' => array('/stockopname/index')),
                                            array('itemOptions' => array('class' => 'divider'), 'label' => ''),
                                            array('label' => '<i class="fa fa-shopping-cart fa-fw"></i>' . ' POS', 'url' => array('/pos/index')),
                                            array('label' => '<i class="fa fa-shopping-cart fa-fw"></i>' . ' Kasir', 'url' => array('/kasir/index')),
                                            array('itemOptions' => array('class' => 'divider'), 'label' => ''),
                                            array('label' => '<i class="fa fa-credit-card fa-fw"></i>' . ' Pengeluaran', 'url' => array('/pengeluaran/index')),
                                            array('label' => '<i class="fa fa-credit-card fa-fw"></i>' . ' Penerimaan', 'url' => array('/penerimaan/index')),
                                            array('itemOptions' => array('class' => 'divider'), 'label' => ''),
                                            array('label' => '<i class="fa fa-list fa-fw"></i>' . ' Data Harian', 'url' => array('/laporanharian/index')),
                                        ),
                                        'itemOptions' => array('class' => 'has-dropdown'),
                                        'submenuOptions' => array('class' => 'dropdown'),
                                    ),
                                    array('itemOptions' => array('class' => 'divider'), 'label' => ''),
                                    array('label' => '<i class="fa fa-pie-chart fa-fw fa-lg"></i>' . ' Laporan', 'url' => '',
                                        'items' => array(
                                            //array('label' => '<i class="fa fa-database fa-fw"></i>' . ' Pembelian', 'url' => array('/report/pembelian')),
                                            array('label' => '<i class="fa fa-line-chart fa-fw"></i>' . ' Penjualan', 'url' => array('/report/penjualan')),
                                            array('label' => '<i class="fa fa-database fa-fw"></i>' . ' Total Stok', 'url' => array('/report/totalstok')),
                                            array('label' => '<i class="fa fa-file fa-fw"></i>' . ' Harian Detail', 'url' => array('/report/hariandetail')),
                                            array('label' => '<i class="fa fa-file fa-fw"></i>' . ' Harian Detail (Omzet=Penjualan)', 'url' => array('/report/hariandetail2')),
                                            array('label' => '<i class="fa fa-bar-chart fa-fw"></i>' . ' Poin Member', 'url' => array('/report/poinmember')),
                                            array('label' => '<i class="fa fa-bar-chart fa-fw"></i>' . ' Top Rank', 'url' => array('/report/toprank')),
                                            array('label' => '<i class="fa fa-database fa-fw"></i>' . ' Rekap Hutang Piutang', 'url' => array('/report/rekaphutangpiutang')),
                                            array('label' => '<i class="fa fa-file fa-fw"></i>' . ' Hutang Piutang (per Profil)', 'url' => array('/report/hutangpiutang')),
                                            array('label' => '<i class="fa fa-database fa-fw"></i>' . ' Pengeluaran/Penerimaan', 'url' => array('/report/pengeluaranpenerimaan')),
                                            array('label' => '<i class="fa fa-bar-chart fa-fw"></i>' . ' Umur Barang (Aging of Inventory)', 'url' => array('/report/umurbarang')),
                                            array('label' => '<i class="fa fa-bar-chart fa-fw"></i>' . ' Potensi Lost Sales', 'url' => array('/report/pls')),
                                            array('label' => '<i class="fa fa-database fa-fw"></i>' . ' Kartu Stok', 'url' => array('/report/kartustok')),
                                            array('label' => '<i class="fa fa-file fa-fw"></i>' . ' Daftar Barang', 'url' => array('/report/daftarbarang')),
                                        //array('label' => '<i class="fa fa-file-pdf-o fa-fw"></i>' . ' Harian Rekap', 'url' => array('/report/harianrekap')),
                                        ),
                                        'itemOptions' => array('class' => 'has-dropdown'),
                                        'submenuOptions' => array('class' => 'dropdown'),
                                    ),
                                    array('itemOptions' => array('class' => 'divider'), 'label' => ''),
                                    array('label' => '<i class="fa fa-wrench fa-fw fa-lg"></i>' . ' Tools', 'url' => '',
                                        'items' => array(
                                            array('label' => '<i class="fa fa-barcode fa-fw"></i>' . ' Cetak Label Rak', 'url' => array('tools/cetaklabelrak/index')),
                                            array('label' => '<i class="fa fa-check-square-o fa-fw"></i>' . ' Cetak Form SO', 'url' => array('tools/cetakformso/index')),
                                            array('label' => '<i class="fa fa-search fa-fw"></i>' . ' Cek Harga', 'url' => array('tools/cekharga/index')),
                                            array('label' => '<i class="fa fa-tablet fa-fw"></i>' . ' Customer Display', 'url' => array('tools/customerdisplay/index')),
                                        //array('label' => '<i class="fa fa-code fa-fw"></i>' . ' Penerimaan PO', 'url' => array('')),
                                        ),
                                        'itemOptions' => array('class' => 'has-dropdown'),
                                        'submenuOptions' => array('class' => 'dropdown'),
                                    ),
                                    array('itemOptions' => array('class' => 'divider'), 'label' => ''),
                                ),
                            ));
                        }
                        ?>

                        <?php
                        if (Yii::app()->user->isGuest) {
                            // Jika belum login, tampilkan menu login
                            $this->widget('zii.widgets.CMenu', array(
                                'encodeLabel' => false,
                                'htmlOptions' => array('class' => 'right'),
                                'id' => '',
                                'items' => array(
                                    array('itemOptions' => array('class' => 'divider'), 'label' => ''),
                                    array('label' => '<i class="fa fa-unlock-alt fa-fw"></i>' . ' Login', 'url' => array('/app/login')
                                    ),
                                ),
                            ));
                        } else {
                            // Jika sudah login, tampilkan menu user
                            $this->widget('zii.widgets.CMenu', array(
                                'encodeLabel' => false,
                                'htmlOptions' => array('class' => 'right'),
                                'id' => '',
                                'items' => array(
                                    //array('itemOptions' => array('class' => 'divider'), 'label' => ''),
                                    //array('label' => '<i class="fa fa-envelope-o fa-fw getar"></i>' . ' <span class="alert label">27</span>', 'url' => array('#'),),
                                    array('itemOptions' => array('class' => 'divider'), 'label' => ''),
                                    array('label' => '<i class="fa fa-at fa-lg fa-fw"></i> ' . Yii::app()->user->namaLengkap, 'url' => '',
                                        'items' => array(
                                            array('label' => '<i class="fa fa-user fa-fw"></i>' . ' Profile', 'url' => array('user/ubah/' . Yii::app()->user->id)),
                                            array('label' => '<i class="fa fa-power-off fa-fw"></i>' . ' Logout', 'url' => array('/app/logout')),
                                        ),
                                        'itemOptions' => array('class' => 'has-dropdown'),
                                        'submenuOptions' => array('class' => 'dropdown'),
                                    ),
                                ),
                            ));
                        }
                        ?>
                    </section>
                </nav>
            </div>
            <?php
        endif;
        ?>
        <?php echo $content; ?>
        <?php
        // Halaman login tidak menampilkan footer
        if (Yii::app()->controller->action->id != 'login'):
            ?>
            <footer>
                <span class="left" id="clock" style="font-size: 0.75rem"></span>
                <span class="right" id="arabictime" style="font-size: 0.9rem"></span>
            </footer>
            <?php
        endif;
        ?>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/foundation.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/hijricalendar-kuwaiti.js"></script>
        <script>
            $(document).foundation();
            function updateClock()
            {
                var currentTime = new Date();
                var currentDate = currentTime.getDate();
                var month = currentTime.getMonth() + 1;
                currentMonth = month < 10 ? '0' + month : '' + month;
                var currentYear = currentTime.getFullYear();
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
                var currentDateString = currentDate + "-" + currentMonth + "-" + currentYear;
                var currentTimeString = currentDateString + " " + currentHours + ":" + currentMinutes + ":" + currentSeconds + " ";// + timeOfDay;

                $("#clock").html(currentTimeString);
            }

            $(document).ready(function ()
            {
                $("#arabictime").html(writeIslamicDate());
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



<?php // if (isset($this->breadcrumbs)):         ?>
<?php
//	$this->widget('zii.widgets.CBreadcrumbs', array(
//		 'links' => $this->breadcrumbs,
//	));
?>
<?php // endif ?>



