<?php

class BrosurpromoController extends Controller
{
	// public $layout = '//layouts/box_kecil';
	public function actionIndex()
	{
		$assetsPath = 'assets/brosurpromo/';
		$assetsPathTh = 'assets/brosurpromo/th/';
		$imgs = [];
		foreach (glob($assetsPath . '*.{jpg,JPG,jpeg,JPEG,png,PNG}', GLOB_BRACE) as $filename) {
			$imgs[] = [
				'filename' => basename($filename),
				'realpath' => realpath($filename),
			];
		}

		// echo '<pre>';
		// print_r($imgs);
		// echo '</pre>';

		$this->render('index', [
			'assetsPath' => $assetsPath,
			'assetsPathTh' => $assetsPathTh,
			'imgs' => $imgs
		]);
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
