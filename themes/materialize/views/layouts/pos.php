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

		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/normalize.css">
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/foundation.css">
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/animate.min.css">
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pos.css">
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.css">
		<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/modernizr.js"></script>
		<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	</head>
	<body>
		<!--[if lt IE 7]>
			 <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
		<![endif]-->

		<?php echo $content; ?>
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
		<script>
			$(document).foundation();
		</script>
		<?php
		/*
		  <script>window.jQuery || document.write('<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.js"><\/script>')</script>
		 */
		?>
	</body>
</html>



<?php // if (isset($this->breadcrumbs)): ?>
<?php
//	$this->widget('zii.widgets.CBreadcrumbs', array(
//		 'links' => $this->breadcrumbs,
//	));
?>
<?php // endif ?>



