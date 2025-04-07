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
        <meta name="theme-color" content="#052848" />

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
                            /*
                             * Fungsi Rekursif render Menu dari DB
                             */
                            function markUpMenu($menus)
                            {
                                $result = [];
                                foreach ($menus as $menu) {
                                    $options = [];
                                    $item = [];

                                    /* Jika tidak punya 'items' berarti render DropDown */
                                    if (!empty($menu['items'])) {
                                        $options = [
                                            'itemOptions' => ['class' => 'has-dropdown'],
                                            'submenuOptions' => ['class' => 'dropdown'],
                                        ];
                                    }

                                    /* Render label (icon + nama) dan url */
                                    $item = [
                                        'label' => $menu['label'],
                                        'url' => $menu['url'],
                                    ];

                                    /* Jika divider: override yang di atas */
                                    if ($menu['nama'] == '-') {
                                        $options = [
                                            'itemOptions' => ['class' => 'divider'],
                                        ];
                                        $item = [
                                            'label' => '',
                                        ];
                                    }
                                    /* Masukkan ke array result */
                                    $result[$menu['id']] = $item + $options;
                                    if (!empty($menu['items'])) {
                                        /* Jika ada subMenu, render dahulu sebelum ke item berikutnya */
                                        $result[$menu['id']]['items'] = markUpMenu($menu['items']);
                                    }
                                }
                                return $result;
                            }

                            /* Ambil data Menu dari DB */
                            $menu = Menu::model()->findByPk(Yii::app()->user->menuId);
                            $mainMenu = $menu->treeListChild;

                            /* Tampilkan main menu */
                            $this->widget('zii.widgets.CMenu', [
                                'activateParents' => true,
                                'encodeLabel' => false,
                                'id' => '',
                                'items' => markUpMenu($mainMenu)
                                    ]
                            );
                        }
                        ?>

                        <?php
                        if (Yii::app()->user->isGuest) {
                            // Jika belum login, tampilkan menu login
                            $this->widget('zii.widgets.CMenu', [
                                'encodeLabel' => false,
                                'htmlOptions' => ['class' => 'right'],
                                'id' => '',
                                'items' => [
                                    [
                                        'itemOptions' => ['class' => 'divider'],
                                        'label' => ''
                                    ],
                                    [
                                        'label' => '<i class="fa fa-unlock-alt fa-fw"></i>' . ' Login',
                                        'url' => ['/app/login']
                                    ],
                                ],
                            ]);
                        } else {
                            // Jika sudah login, tampilkan menu user
                            $this->widget('zii.widgets.CMenu', [
                                'encodeLabel' => false,
                                'htmlOptions' => ['class' => 'right'],
                                'id' => '',
                                'items' => [
                                    //array('itemOptions' => array('class' => 'divider'), 'label' => ''),
                                    //array('label' => '<i class="fa fa-envelope-o fa-fw getar"></i>' . ' <span class="alert label">27</span>', 'url' => array('#'),),
                                    ['itemOptions' => ['class' => 'divider'], 'label' => ''],
                                    ['label' => '<i class="fa fa-at fa-lg fa-fw"></i> ' . Yii::app()->user->namaLengkap, 'url' => '',
                                        'items' => [
                                            [
                                                'label' => '<i class="fa fa-user fa-fw"></i>' . ' Profile',
                                                'url' => ['user/ubah/' . Yii::app()->user->id]
                                            ],
                                            [
                                                'label' => '<i class="fa fa-power-off fa-fw"></i>' . ' Logout',
                                                'url' => ['/app/logout']
                                            ],
                                        ],
                                        'itemOptions' => ['class' => 'has-dropdown'],
                                        'submenuOptions' => ['class' => 'dropdown'],
                                    ],
                                ],
                            ]);
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
                var curDate = currentTime.getDate();
                var currentDate = curDate < 10 ? '0' + curDate : '' + curDate;
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
                $("#arabictime").html(writeIslamicDate(-2));
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



<?php // if (isset($this->breadcrumbs)):          ?>
<?php
//	$this->widget('zii.widgets.CBreadcrumbs', array(
//		 'links' => $this->breadcrumbs,
//	));
?>
<?php // endif ?>



