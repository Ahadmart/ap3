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
        if ($data->nama == 'login.password') {
            // $nilai = '***********';
            $nilai = '● ● ● ● ● ● ●';
            // $nilai = '• • • • • • •';
            // $nilai = UnsafeCrypto::decrypt($nilai, UnsafeCrypto::AHADMEMBERSHIP_KEY, true);
        }

        if ($data->nama == 'bearer_token') {
            return substr($nilai, 0, 12) . '. . .';
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
            if ($config->nama == 'login.password') {
                $config->nilai = UnsafeCrypto::encrypt($nilai, UnsafeCrypto::AHADMEMBERSHIP_KEY, true);
            }

            if ($config->save()) {
                $return = ['sukses' => true];
            }

            $this->renderJSON($return);
        }
    }
}
