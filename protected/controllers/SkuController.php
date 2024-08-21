<?php

class SkuController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return [
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		];
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return [
			[
				'deny', // deny guest
				'users' => ['guest'],
			],
		];
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$skuDetail = new SkuDetail('search');
		$skuDetail->unsetAttributes();
		$skuDetail->setAttribute('sku_id', '=' . $id);
		if (isset($_GET['SkuDetail'])) {
			$skuDetail->attributes = $_GET['SkuDetail'];
		}
		$this->render('view', [
			'model'       => $this->loadModel($id),
			'modelDetail' => $skuDetail,
		]);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionTambah()
	{
		$this->layout = 'box_kecil';
		$model        = new Sku;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Sku'])) {
			$model->attributes = $_POST['Sku'];
			if ($model->save()) {
				$this->redirect(['view', 'id' => $model->id]);
			}
		}

		$this->render('tambah', [
			'model' => $model,
		]);
	}

	/**
	 * Updates a particular model.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUbah($id)
	{
		$model = $this->loadModel($id);

		$skuDetail = new SkuDetail('search');
		$skuDetail->unsetAttributes();
		$skuDetail->setAttribute('sku_id', '=' . $id);
		if (isset($_GET['SkuDetail'])) {
			$skuDetail->attributes = $_GET['SkuDetail'];
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Sku'])) {
			$model->attributes = $_POST['Sku'];
			if ($model->save()) {
				$this->redirect(['view', 'id' => $id]);
			}
		}

		$this->render('ubah', [
			'model'       => $model,
			'modelDetail' => $skuDetail,
		]);
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionHapus($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['index']);
		}
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model = new Sku('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['Sku'])) {
			$model->attributes = $_GET['Sku'];
		}

		$this->render('index', [
			'model' => $model,
		]);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Sku the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Sku::model()->findByPk($id);
		if ($model === null) {
			throw new CHttpException(404, 'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Sku $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'sku-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function renderLinkToView($data)
	{
		$return = '';
		if (isset($data->nama)) {
			$return = '<a href="' .
				$this->createUrl('view', ['id' => $data->id]) . '">' .
				$data->nama . '</a>';
		}
		return $return;
	}

	public function actionTambahBarangList()
	{
		$model = new Barang('search');
		$model->unsetAttributes(); // clear any default values
		$model->setAttribute('status', Barang::STATUS_AKTIF); // default yang tampil
		if (isset($_GET['Barang'])) {
			$model->attributes = $_GET['Barang'];
		}
		$this->renderPartial('_tambahbarang_list', ['model' => $model], false, true);
	}

	public function actionTambahBarang()
	{
		$r = [];

		$id       = $_POST['id'];
		$barangId = $_POST['barangId'];

		if (empty($id) || empty($barangId)) {
			$r = [
				'sukses' => false,
				'err'    => [
					'msg' => 'modelId atau barangId kosong!'
				]
			];
		}

		$detail            = new SkuDetail();
		$detail->sku_id    = $id;
		$detail->barang_id = $barangId;
		if (!$detail->save()) {
			$r = [
				'sukses' => false,
				'err'    => [
					'msg' => 'Gagal tambah barang'
				]
			];
		}
		$r = ['sukses' => true];
		$this->renderJSON($r);
	}
}
