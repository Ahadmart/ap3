<?php

class MembershipconfigController extends Controller
{
	public $layout = '//layouts/box_kecil';

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model = new MembershipConfig('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['MembershipConfig'])) {
			$model->attributes = $_GET['MembershipConfig'];
		}
		$model->visibleOnly();

		$this->render('index', [
			'model' => $model,
		]);
	}

	public function renderEditableNilai($data)
	{
		$nilai = $data->nilai;
		if ($data->nama == 'password') {
			// $nilai = '***********';
			$nilai = '● ● ● ● ● ● ●';
			// $nilai = '• • • • • • •';
		}
		
		if ($data->nama == 'bearer_token') {
			return $nilai;
		}

		return CHtml::link($nilai, '#', [
			'class'     => 'editable-nilai',
			'data-type' => 'text',
			'data-pk'   => $data->id,
			'data-url'  => Yii::app()->controller->createUrl('updatenilai')
		]);
	}

	public function actionUpdateNilai()
	{
		$return = ['sukses' => false];
		if (isset($_POST['pk'])) {
			$pk            = $_POST['pk'];
			$nilai         = $_POST['value'];
			$config        = MembershipConfig::model()->findByPk($pk);
			$config->nilai = $nilai;

			if ($config->save()) {
				$return = ['sukses' => true];
			}

			$this->renderJSON($return);
		}
	}
}
