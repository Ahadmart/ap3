<?php

class BrosurpromoController extends Controller
{
	// public $layout = '//layouts/box_kecil';
	const ASSETS_PATH    = 'assets/brosurpromo/';
	const ASSETS_PATH_TH = 'assets/brosurpromo/th/';

	protected function ambilBrosur()
	{
		$imgs = [];
		foreach (glob(self::ASSETS_PATH . 'brosur*.*', GLOB_BRACE) as $filename) {
			$imgs[] = [
				'filename' => basename($filename),
				'realpath' => realpath($filename),
			];
		}
		return $imgs;
	}

	protected function ambilLogo()
	{
		$logos = [];
		foreach (glob(self::ASSETS_PATH . 'logo*.*', GLOB_BRACE) as $filename) {
			$logos[] = [
				'filename' => basename($filename),
				'realpath' => realpath($filename),
			];
		}
		return $logos;
	}

	/**
	 * Method getBrosurPromo
	 *
	 * @return array $imgs[] img's urls
	 */
	protected function getBrosurPromo()
	{
		$imgs = [];
		foreach (glob(self::ASSETS_PATH . '*.*', GLOB_BRACE) as $filename) {
			$imgs[] = $this->createUrl($filename);
		}
		return $imgs;
	}

	protected function getLogo()
	{
		$imgs = [];
		foreach (glob(self::ASSETS_PATH . 'logo*.*', GLOB_BRACE) as $filename) {
			$imgs[] = $this->createUrl($filename);
		}
		return $imgs;
	}

	public function actionIndex()
	{
		$this->render('index', [
			'assetsPath'   => self::ASSETS_PATH,
			'assetsPathTh' => self::ASSETS_PATH_TH,
			'imgs'         => $this->ambilBrosur(),
			'logos'        => $this->ambilLogo(),
		]);
	}

	public function actionUploadBrosur()
	{
		require_once __DIR__ . '/../../vendor/autoload.php';
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
			$fullFilePath = realpath(self::ASSETS_PATH) . '/' . $fileName . '.' . $file->extensionName;
			// echo $fullFilePath;
			// echo Yii::app()->basePath;
			if (!is_dir(realpath(self::ASSETS_PATH))) {
				mkdir(self::ASSETS_PATH);
			}
			if ($file->saveAs($fullFilePath)) {
				$imagine   = new \Imagine\Gd\Imagine();
				$image     = $imagine->open($fullFilePath);
				$thumbnail = $image->thumbnail(new \Imagine\Image\Box(200, 200));
				if (!is_dir(realpath(self::ASSETS_PATH_TH))) {
					mkdir(self::ASSETS_PATH_TH);
				}
				$thumbnail->save(realpath(self::ASSETS_PATH_TH) . '/' . $fileName . '.' . $file->extensionName);
			} else {
				echo 'Gagal simpan ke: ' . $fullFilePath;
			}
		}
		// echo '</pre>';

		$config         = Config::model()->find("nama='customerdisplay.pos.enable'");
		$wsClientEnable = $config->nilai;
		if ($wsClientEnable) {
			$clientWS = new AhadPosWsClient();
			$clientWS->setGlobal(true);
			$data = [
				'tipe' => AhadPosWsClient::TIPE_BROSUR_UPDATE,
				'imgs' => $this->getBrosurPromo(),
			];
			$clientWS->sendJsonEncoded($data);
		}
	}

	public function actionLoadLogo()
	{
		$this->renderPartial('_logo', [
			'assetsPath'   => self::ASSETS_PATH,
			'assetsPathTh' => self::ASSETS_PATH_TH,
			'imgs'         => $this->ambilLogo(),
		]);
	}

	public function actionLoadBrosur()
	{
		$this->renderPartial('_brosur', [
			'assetsPath'   => self::ASSETS_PATH,
			'assetsPathTh' => self::ASSETS_PATH_TH,
			'imgs'         => $this->ambilBrosur(),
		]);
	}

	public function actionHapus($type)
	{
		if (isset($_POST['filename'])) {
			$fileName = $_POST['filename'];
			$r1       = $this->hapusFile(realpath(self::ASSETS_PATH) . '/' . $fileName);
			$r2       = $this->hapusFile(realpath(self::ASSETS_PATH_TH) . '/' . $fileName);
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

		$config         = Config::model()->find("nama='customerdisplay.pos.enable'");
		$wsClientEnable = $config->nilai;
		if ($wsClientEnable) {
			$clientWS = new AhadPosWsClient();
			$clientWS->setGlobal(true);
			$data = [
				'tipe' => $type,
				'imgs' => $type == AhadPosWsClient::TIPE_BROSUR_UPDATE ? $this->getBrosurPromo() : $this->getLogo(),
			];
			$clientWS->sendJsonEncoded($data);
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

	public function actionUploadLogo()
	{
		require_once __DIR__ . '/../../vendor/autoload.php';
		// echo ('Dari upload Logo');
		// echo '<pre>';
		// print_r($_FILES);
		$fileUpload = $_FILES['gambar-logo'];
		$fileLogo   = new CUploadedFile(
			$fileUpload['name'],
			$fileUpload['tmp_name'],
			$fileUpload['type'],
			$fileUpload['size'],
			$fileUpload['error']
		);
		$fileName     = 'logo-customerdisplay';
		$fullFilePath = realpath(self::ASSETS_PATH) . '/' . $fileName . '.' . $fileLogo->extensionName;

		if (!is_dir(realpath(self::ASSETS_PATH))) {
			mkdir(self::ASSETS_PATH);
		}
		if ($fileLogo->saveAs($fullFilePath)) {
			$imagine   = new \Imagine\Gd\Imagine();
			$image     = $imagine->open($fullFilePath);
			$thumbnail = $image->thumbnail(new \Imagine\Image\Box(200, 200));
			if (!is_dir(realpath(self::ASSETS_PATH_TH))) {
				mkdir(self::ASSETS_PATH_TH);
			}
			$thumbnail->save(realpath(self::ASSETS_PATH_TH) . '/' . $fileName . '.' . $fileLogo->extensionName);
		} else {
			echo 'Gagal simpan ke: ' . $fullFilePath;
		}

		$config         = Config::model()->find("nama='customerdisplay.pos.enable'");
		$wsClientEnable = $config->nilai;
		if ($wsClientEnable) {
			$clientWS = new AhadPosWsClient();
			$clientWS->setGlobal(true);
			$data = [
				'tipe' => AhadPosWsClient::TIPE_LOGO_UPDATE,
				'imgs' => $this->getLogo(),
			];
			$clientWS->sendJsonEncoded($data);
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
