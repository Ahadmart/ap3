<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>

		<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
		<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
		<?php Yii::app()->clientScript->registerCoreScript('jquery-2.1.1'); ?>
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	</head>
	<body>

		<?php
		// Jika yang diakses halaman login, top navbar tidak ditampilkan
		if (Yii::app()->controller->action->id != 'login'):

			$items = array(
				 array('label' => 'Master', 'url' => '#!'),
				 array('label' => ''.Yii::app()->user->namaLengkap.'<i class="mdi-navigation-arrow-drop-down right"></i>', 'url' => '#!',
					  'linkOptions' => array(
							'class' => 'dropdown-button',
							'data-activates' => 'dropdown-user'
					  ),
					  'items' => array(
							array('label' => 'Profile', 'url' => array('user/ubah/'.Yii::app()->user->id)),
							array('label' => 'Logout', 'url' => array('/app/logout')),
					  ),
					  'submenuOptions' => array('class' => 'dropdown-content', 'id' => 'dropdown-user'),
				 ),
			);
//Yii::app()->user->namaLengkap.'<i class="mdi-navigation-arrow-drop-down right"></i>'
			$itemsSide = array(
				 array('label' => 'Master', 'url' => '#!'),
				 array('label' => '',
					  'itemOptions' => array(
							'class' => 'no-padding'
					  ),
					  'submenuOptions' => array('class' => 'collapsible collapsible-accordion'),
					  'items' => array(
							array('label' => Yii::app()->user->namaLengkap.'<i class="mdi-navigation-arrow-drop-down right"></i>', 'url' => '#!'),
					  ),
				 ),
			);
			?>
			<div class="navbar-fixed">
				<nav class="green" role="navigation">
					<div class="nav-wrapper container">
						<a id="logo-container" href="<?php echo Yii::app()->baseUrl; ?>" class="brand-logo">AhadPOS 3</a>
						<?php
//						$this->widget('zii.widgets.CMenu', array(
//							 'encodeLabel' => false,
//							 'htmlOptions' => array('class' => 'side-nav'),
//							 'id' => 'nav-mobile',
//							 'items' => $itemsSide,
//						));
						?>
						<ul id="nav-mobile" class="side-nav">
							<li><a href="#!">Master</a></li>
							<li class="no-padding">
								<ul class="collapsible collapsible-accordion">
									<li>
										<a class="collapsible-header">Administrator</a>
										<div class="collapsible-body">
											<ul>
												<li><a href="<?php echo $this->createUrl('user/ubah/'.Yii::app()->user->id); ?>">Profile</a></li>
												<li><a href="<?php echo $this->createUrl('/app/logout'); ?>">Logout</a></li>
											</ul>
										</div>
									</li>
								</ul>
							</li>
						</ul>
						<?php
						$this->widget('zii.widgets.CMenu', array(
							 'encodeLabel' => false,
							 'htmlOptions' => array('class' => 'right hide-on-med-and-down'),
							 'id' => '',
							 'items' => $items,
						));
						?>
						<a href="#" data-activates="nav-mobile" class="right button-collapse"><i class="mdi-navigation-menu"></i></a>
					</div>
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
			<footer class="page-footer hide-on-small-only">
				<div class="container">
					<div class="footer-copyr right-align">
						Copyright &copy; <?php echo date('Y'); ?> by Abu Muhammad
					</div>
				</div>
			</footer>
			<?php
		endif;
		?>
		<!--  Scripts-->
		<!--<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-2.1.1.min.js"></script>-->
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/materialize.js"></script>
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/init.js"></script>
	</body>
</html>



<?php // if (isset($this->breadcrumbs)):     ?>
<?php
//	$this->widget('zii.widgets.CBreadcrumbs', array(
//		 'links' => $this->breadcrumbs,
//	));
?>
<?php // endif ?>



