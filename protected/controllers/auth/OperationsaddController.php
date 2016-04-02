<?php

class OperationsaddController extends Controller
{

    public $layout = '//layouts/box_kecil';

    public function actionIndex()
    {
        $auth = Yii::app()->authManager;
        $hasil = '';
        if (isset($_POST['operations'])) {
            $operations = explode("\n", $_POST['operations']);

            $hasil .= 'log: <br />';
            foreach ($operations as $operation) {
                if (!empty($operation) && trim($operation) != '') {
                    $auth->createOperation(trim($operation));
                    $hasil .= $operation . ' ditambahkan<br />';
                }
            }
        }
        $this->render('index', array(
            'hasil' => $hasil
        ));
    }

}
