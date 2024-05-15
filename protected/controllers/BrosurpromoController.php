<?php

class BrosurpromoController extends Controller
{
	// public $layout = '//layouts/box_kecil';
	public $assetsPath   = 'assets/brosurpromo/';
	public $assetsPathTh = 'assets/brosurpromo/th/';

	protected function ambilBrosur()
	{
		$imgs = [];
		foreach (glob($this->assetsPath . '*.*', GLOB_BRACE) as $filename) {
			$imgs[] = [
				'filename' => basename($filename),
				'realpath' => realpath($filename),
			];
		}
		return $imgs;
	}

	public function actionIndex()
	{
		$this->render('index', [
			'assetsPath'   => $this->assetsPath,
			'assetsPathTh' => $this->assetsPathTh,
			'imgs'         => $this->ambilBrosur(),
		]);
	}

	public function actionUploadBrosur()
	{
		require_once __DIR__ . '/../vendor/autoload.php';
		// echo ('Dari upload Brosur');
		// echo '<pre>';
		// print_r($_FILES);
		foreach ($_FILES as $brosur) {
			// print_r($brosur);
			$file = new CUploadedFile(
				$brosur['name'],
				$brosur['tmp_name'],
				$brosur['type'],
				$brosur['size'],
				$brosur['error']
			);
			$now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
			$now->setTimezone(new DateTimeZone('Asia/Jakarta'));
			$timestamp    = $now->format('m-d-YTH:i:s.uP');
			$fileName     = 'brosur-' . $timestamp;
			$fullFilePath = realpath($this->assetsPath) . '/' . $fileName . '.' . $file->extensionName;
			// echo $fullFilePath;
			// echo Yii::app()->basePath;
			if (!is_dir(realpath($this->assetsPath))) {
				mkdir($this->assetsPath);
			}
			if ($file->saveAs($fullFilePath)) {
				$imagine   = new \Imagine\Gd\Imagine();
				$image     = $imagine->open($fullFilePath);
				$thumbnail = $image->thumbnail(new \Imagine\Image\Box(200, 200));
				if (!is_dir(realpath($this->assetsPathTh))) {
					mkdir($this->assetsPathTh);
				}
				$thumbnail->save(realpath($this->assetsPathTh) . '/' . $fileName . '.' . $file->extensionName);
			} else {
				echo 'Gagal simpan ke: ' . $fullFilePath;
			}
		}
		// echo '</pre>';
	}

	public function actionLoadBrosur()
	{
		$this->renderPartial('_brosur', [
			'assetsPath'   => $this->assetsPath,
			'assetsPathTh' => $this->assetsPathTh,
			'imgs'         => $this->ambilBrosur(),
		]);
	}

	public function actionHapus()
	{
		if (isset($_POST['filename'])) {
			$fileName = $_POST['filename'];
			$r1       = $this->hapusFile(realpath($this->assetsPath) . '/' . $fileName);
			$r2       = $this->hapusFile(realpath($this->assetsPathTh) . '/' . $fileName);
		}

		if ($r1 === 0 and $r2 === 0) {
			$h = [
				'sukses' => true,
			];
		} else {
			$h = [
				'sukses' => false,
			];
		}
		$this->renderJSON($h);
	}

	protected function hapusFile($file)
	{
		if (file_exists($file)) {
			if (unlink($file)) {
				// echo $file . ' unlinked successfully';
				return 0;
			} else {
				// echo 'Unable to unlink ' . $file;
				return 1;
			}
		} else {
			// echo 'Unable to unlink: ' . $file . '. File does not exist!';
			return 2;
		}
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
