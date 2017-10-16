<?php

class EditbarangController extends Controller
{

    public function actionIndex()
    {
        $model = new Barang('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Barang']))
            $model->attributes = $_GET['Barang'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionSetna()
    {
        if (isset($_POST['ajaxdata']) && !empty($_POST['items'])) {
            $items = $_POST['items'];
            $this->renderJSON($this->_setnasql($items));
        } else {
            $this->renderJSON([
                'sukses' => false,
                'error' => [
                    'code' => 500,
                    'msg' => 'Tidak ada data!'
                ]
            ]);
        }
    }

    private function _setnasql($items)
    {
        $condition = 'id in (';
        $i = 1;
        $params = [];
        $pertamax = true;
        foreach ($items as $item) {
            $key = ':item' . $i;
            if (!$pertamax) {
                $condition .= ',';
            }
            $condition .= $key;
            $params[$key] = $item;
            $pertamax = false;
            $i++;
        }
        $condition .= ')';
        $rowAffected = Barang::model()->updateAll(['status' => Barang::STATUS_TIDAK_AKTIF], $condition, $params);
        return [
            'sukses' => true,
            'rowAffected' => $rowAffected,
        ];
    }

}
