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
            $this->renderJSON($this->_setStatus($items, Barang::STATUS_TIDAK_AKTIF));
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

    public function actionSeta()
    {
        if (isset($_POST['ajaxdata']) && !empty($_POST['items'])) {
            $items = $_POST['items'];
            $this->renderJSON($this->_setStatus($items, Barang::STATUS_AKTIF));
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

    private function _setStatus($items, $status)
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
        $rowAffected = Barang::model()->updateAll(['status' => $status], $condition, $params);
        return [
            'sukses' => true,
            'rowAffected' => $rowAffected,
        ];
    }

    public function actionFormGantiRak()
    {
        $this->renderPartial('_form_ganti_rak');
    }

    public function actionSetRak()
    {
        if (isset($_POST['ajaxrak']) && !empty($_POST['rak-id']) && !empty($_POST['items'])) {
            $items = $_POST['items'];
            $rakId = $_POST['rak-id'];
            $this->renderJSON($this->_setRak($items, $rakId));
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

    private function _setRak($items, $rakId)
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
        $rowAffected = Barang::model()->updateAll(['rak_id' => $rakId], $condition, $params);
        $rak = RakBarang::model()->findByPk($rakId);
        return [
            'sukses' => true,
            'rowAffected' => $rowAffected,
            'namarak' => $rak->nama
        ];
    }

}
