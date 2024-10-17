<?php

class SkutransferController extends Controller
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
		$this->render('view', [
			'model' => $this->loadModel($id),
		]);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionTambah()
	{
		$this->layout = '//layouts/box_kecil';

		$model = new SkuTransfer;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['SkuTransfer'])) {
			$model->attributes = $_POST['SkuTransfer'];
			if ($model->save()) {
				$this->redirect(['ubah', 'id' => $model->id]);
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['SkuTransfer'])) {
			$model->attributes = $_POST['SkuTransfer'];
			if ($model->save()) {
				$this->redirect(['view', 'id' => $id]);
			}
		}

		$this->render('ubah', [
			'model' => $model,
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
		$model = new SkuTransfer('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['SkuTransfer'])) {
			$model->attributes = $_GET['SkuTransfer'];
		}

		$this->render('index', [
			'model' => $model,
		]);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return SkuTransfer the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = SkuTransfer::model()->findByPk($id);
		if ($model === null) {
			throw new CHttpException(404, 'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param SkuTransfer $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'sku-transfer-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionCariSku($term)
	{
		$arrTerm  = explode(' ', $term);
		$wNomor   = '(';
		$wNama    = '(';
		$param    = [];
		$firstRow = true;
		$i        = 1;
		foreach ($arrTerm as $bTerm) {
			if (!$firstRow) {
				$wNomor .= ' AND ';
				$wNama .= ' AND ';
			}
			$wNomor .= "nomor like :term{$i}";
			$wNama .= "nama like :term{$i}";
			$param[":term{$i}"] = "%{$bTerm}%";
			$firstRow           = false;
			$i++;
		}
		$wNomor .= ')';
		$wNama .= ')';

		$q = new CDbCriteria();
		$q->addCondition("{$wNomor} OR {$wNama}");
		$q->params = $param;
		$skus      = Sku::model()->findAll($q);

		$r = [];
		foreach ($skus as $sku) {
			$r[] = [
				'label' => $sku->nama,
				'value' => $sku->nomor,
			];
		}

		$this->renderJSON($r);
	}

	public function actionGetDataSku()
	{
		$return = [
			'sukses' => false,
		];
		if (isset($_POST['nomor'])) {
			$nomor = $_POST['nomor'];
			$sku   = Sku::model()->find('nomor = :nomor', [
				':nomor' => $nomor,
			]);

			if (is_null($sku)) {
				$this->renderJSON(array_merge($return, ['error' => [
					'code' => '500',
					'msg'  => 'SKU tidak ditemukan',
				]]));
			}

			$return = [
				'sukses' => true,
				'skuId'  => $sku->id,
				'nomor'  => $sku->nomor,
				'nama'   => $sku->nama,
			];
		}
		if (isset($_POST['barcode'])) {
			$barcode = $_POST['barcode'];
			$barang = Barang::model()->find('barcode = :barcode', [
				':barcode' => $barcode
			]);

			if (is_null($barang)) {
				$this->renderJSON(array_merge($return, ['error' => [
					'code' => '500',
					'msg'  => 'Barang tidak ditemukan',
				]]));
			}

			$skuDetail = SkuDetail::model()->find('barang_id = :barangId', [
				':barangId' => $barang->id
			]);

			$sku = Sku::model()->findByPk($skuDetail->sku_id);



			$return = [
				'sukses' => true,
				'skuId'  => $sku->id,
				'nomor'  => $sku->nomor,
				'nama'   => $sku->nama,
			];
		}
		$this->renderJSON($return);
	}

	public function actionCariBarang($term)
	{
		$arrTerm  = explode(' ', $term);
		$wBarcode   = '(';
		$wNama    = '(';
		$param    = [];
		$firstRow = true;
		$i        = 1;
		foreach ($arrTerm as $bTerm) {
			if (!$firstRow) {
				$wBarcode .= ' AND ';
				$wNama .= ' AND ';
			}
			$wBarcode .= "barcode like :term{$i}";
			$wNama .= "nama like :term{$i}";
			$param[":term{$i}"] = "%{$bTerm}%";
			$firstRow           = false;
			$i++;
		}
		$wBarcode .= ')';
		$wNama .= ')';

		$q = new CDbCriteria();
		$q->addCondition("{$wBarcode} OR {$wNama}");
		$q->params = $param;
		$barangs      = Barang::model()->findAll($q);

		$r = [];
		foreach ($barangs as $barang) {
			$r[] = [
				'label' => $barang->nama,
				'value' => $barang->barcode,
			];
		}

		$this->renderJSON($r);
	}
}
