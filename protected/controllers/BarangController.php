<?php

class BarangController extends Controller {

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) {

		//$supplierBarang = SupplierBarang::model()->findAll('barang_id=' . $id);

		$supplierBarang = new SupplierBarang('search');
		$supplierBarang->unsetAttributes();
		$supplierBarang->setAttribute('barang_id', '='.$id);

		$inventoryBalance = new InventoryBalance('search');
		$inventoryBalance->unsetAttributes();
		$inventoryBalance->setAttribute('barang_id', '='.$id);
		$inventoryBalance->setAttribute('qty', '<>0');
		$inventoryBalance->scenario = 'tampil';

		$hargaJual = new HargaJual('search');
		$hargaJual->unsetAttributes();
		$hargaJual->setAttribute('barang_id', '='.$id);

		$rrp = new HargaJualRekomendasi('search');
		$rrp->unsetAttributes();
		$rrp->setAttribute('barang_id', '='.$id);

		$this->render('view', array(
			 'model' => $this->loadModel($id),
			 'supplierBarang' => $supplierBarang,
			 'inventoryBalance' => $inventoryBalance,
			 'hargaJual' => $hargaJual,
			 'rrp' => $rrp
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionTambah() {
		$model = new Barang;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Barang'])) {
			$model->attributes = $_POST['Barang'];
			if ($model->save())
				$this->redirect(array('view', 'id' => $model->id));
		}

		$this->render('tambah', array(
			 'model' => $model,
		));
	}

	/**
	 * Updates a particular model.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUbah($id) {
		$model = $this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$supplierBarang = new SupplierBarang('search');
		$supplierBarang->unsetAttributes();
		$supplierBarang->setAttribute('barang_id', '='.$id);

		$hargaJual = new HargaJual('search');
		$hargaJual->unsetAttributes();
		$hargaJual->setAttribute('barang_id', '='.$id);

		$rrp = new HargaJualRekomendasi('search');
		$rrp->unsetAttributes();
		$rrp->setAttribute('barang_id', '='.$id);

		if (isset($_POST['Barang'])) {
			$model->attributes = $_POST['Barang'];
			if ($model->save())
				$this->redirect(array('view', 'id' => $id));
		}

		$this->render('ubah', array(
			 'model' => $model,
			 'supplierBarang' => $supplierBarang,
			 'listBukanSupplier' => $this->_listBukanSupplier($id),
			 'hargaJual' => $hargaJual,
			 'rrp' => $rrp
		));
	}

	public function actionTambahSupplier($id) {
		if (isset($_POST['supplier_id'])) {
			$supplierId = $_POST['supplier_id'];
			$model = new SupplierBarang;
			$model->barang_id = $id;
			$model->supplier_id = $supplierId;
			if ($model->save()) {
				echo 'berhasil';
			} else {
				echo 'tidak berhasil';
			}
		}
	}

	public function actionListBukanSupplier($id) {
		$this->renderPartial('_supplier_opt', array(
			 'listBukanSupplier' => $this->_listBukanSupplier($id)
		));
	}

	public function _listBukanSupplier($id) {
		return Profil::model()->listSupplierYangBukan($id);
	}

	public function actionAssignDefaultSup($id, $barangId) {
		SupplierBarang::model()->assignDefaultSupplier($id, $barangId);
	}

	public function actionRemoveSupplier($id) {
		if (isset($_GET['ajax']) && $_GET['ajax'] === 'supplier-barang-grid') {
			$model = SupplierBarang::model()->deleteByPk($id);
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionHapus($id) {
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex() {
		$model = new Barang('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Barang']))
			$model->attributes = $_GET['Barang'];

		$this->render('index', array(
			 'model' => $model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Barang the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id) {
		$model = Barang::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Barang $model the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'barang-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionUpdateHargaJual($id) {
		$hargaJual = $_POST['hj'];
		if (HargaJual::model()->updateHargaJualTrx($id, $hargaJual)) {
			echo 'Sukses';
		} else {
			echo 'Fail';
		}
	}

	public function actionUpdateRrp($id) {
		$hargaJual = $_POST['rrp'];
		if (HargaJualRekomendasi::model()->updateHargaJualTrx($id, $hargaJual)) {
			echo 'Sukses';
		} else {
			echo 'Fail';
		}
	}

	public function renderInventoryDocumentLinkToView($data) {
		$inventoryBalance = InventoryBalance::model()->findByPk($data->id);
		$namaController = $inventoryBalance->namaAsalController();
		$model = $inventoryBalance->modelAsal();
		return '<a href="'.
				  $this->createUrl("{$namaController}/view", array('id' => $model->id)).'">'.
				  $data->nomor_dokumen.'</a>';
	}

   public function renderLinkToView($data) {
      $return = '';
      if (isset($data->nama)) {
         $return = '<a href="'.
                 $this->createUrl('view', array('id' => $data->id)).'">'.
                 $data->nama.'</a>';
      }
      return $return;
   }
}
