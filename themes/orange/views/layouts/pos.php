<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
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
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/modernizr.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-ui.min-ac.js"></script>
        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        <!--        <div class="off-canvas-wrap" data-offcanvas>
                    <div class="inner-wrap">
                        <nav class="tab-bar">
                            <section class="left-small">
                                <a class="left-off-canvas-toggle menu-icon" accesskey="l"><span></span></a>
                            </section>

                            <section class="middle tab-bar-section" style="text-align: left">
                                <span class="title"><a href="<?php echo Yii::app()->baseUrl; ?>"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo.png" alt="logo" /></a></span>
                                <span class="right" style="font-size: 0.9em"><i class="fa fa-user fa-fw"></i> <?php echo Yii::app()->user->namaLengkap; ?>&nbsp;&nbsp;&nbsp;<i class="fa fa-clock-o fa-fw"></i> <span id="clock">00:00:00</span></span>
                            </section>

                            <section class="right-small">
                                <a class="right-off-canvas-toggle menu-icon" accesskey="r"><span></span></a>
                            </section>
                        </nav>

                        <aside class="left-off-canvas-menu">
                            <ul class="off-canvas-list">
                                <li><label>Menu</label></li>
                                <li class="has-submenu"><a href="#">Menu1</a>
                                    <ul class="left-submenu">
                                        <li class="back"><a href="#">Back</a></li>
                                        <li><label>Level 1</label></li>
                                        <li><a href="#">Link 1</a></li>
                                        <li class="has-submenu"><a href="#">Link 2 w/ submenu</a>
                                            <ul class="left-submenu">
                                                <li class="back"><a href="#">Back</a></li>
                                                <li><label>Level 2</label></li>
                                                <li><a href="#">...</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">...</a></li>
                                    </ul>
                                </li>
                                <li><a href="<?php echo Yii::app()->baseUrl; ?>">Home</a></li>
                                <form>
                                    <div class="small-12 columns">
                                        <input type="text" accesskey="z"/>
                                    </div>
                                    <div class="small-12 columns">
                                        <input type="submit" class="bigfont tiny button" />
                                    </div>
                                </form>
                            </ul>
                        </aside>

                        <aside class="right-off-canvas-menu">
                            <ul class="off-canvas-list">
                                <li><label>Users</label></li>
                                <li><a href="#">Menu1</a></li>
                                <li class="has-submenu"><a href="#">Menu2</a>
                                    <ul class="right-submenu">
                                        <li class="back"><a href="#">Back</a></li>
                                        <li><label>Level 1</label></li>
                                        <li><a href="#">Link 1</a></li>
                                        <li class="has-submenu"><a href="#">Link 2 w/ submenu</a>
                                            <ul class="right-submenu">
                                                <li class="back"><a href="#">Back</a></li>
                                                <li><label>Level 2</label></li>
                                                <li><a href="#">...</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">...</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">...</a></li>
                            </ul>
                        </aside>

                        <section class="main-section">
        <?php // echo $content; ?>
                        </section>

                        <a class="exit-off-canvas"></a>

                    </div>
                </div>-->
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
                    ?>
                </section>
            </nav>
        </div>
        <div id="content">
            <?php echo $content; ?>
        </div>
        <?php
        /*
          // Halaman login tidak menampilkan footer
          if (Yii::app()->controller->action->id != 'login'):
          ?>
          <footer>
          Copyright &copy; <?php echo date('Y'); ?> by b.hermianto@gmail.com<br/>
          </footer>
          <?php
          endif;
         *
         */
        ?>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/foundation.min.js"></script>
        <!--<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/foundation/foundation.offcanvas.js"></script>-->
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
            function updateClock()
            {
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
                var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " ";// + timeOfDay;

                $("#clock").html(currentTimeString);
            }

            $(document).ready(function ()
            {
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
