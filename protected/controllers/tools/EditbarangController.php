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

    public function actionFormEditSup()
    {
        $this->renderPartial('_form_edit_supplier');
    }

    public function actionTambahSup()
    {
        if (isset($_POST['ajaxsup']) && !empty($_POST['sup-id']) && !empty($_POST['items'])) {
            $items = $_POST['items'];
            $supId = $_POST['sup-id'];
            $this->renderJSON($this->_tambahSupplier($items, $supId));
        } else {
            $this->renderJSON([
                'sukses' => false,
                'error' => [
                    'code' => 500,
                    'msg' => 'Tidak ada data!'
                ]
            ]);
        }
        $this->renderJSON([
            'sukses' => false,
            'error' => [
                'code' => 500,
                'msg' => 'Tidak ada data!'
            ]
        ]);
    }

    private function _tambahSupplier($items, $supplierId)
    {
        $profil = Profil::model()->findByPk($supplierId);

        $sql = "INSERT IGNORE INTO `supplier_barang` (supplier_id, barang_id, updated_by, created_at) VALUES (:supId, :barangId, :userId, :waktu)";
        $params = [];
        $sekarang = date('Y-m-d H:i:s');
        foreach ($items as $item) {
            $params[] = [':supId' => $supplierId, ':barangId' => $item, ':userId' => Yii::app()->user->id, ':waktu' => $sekarang];
        }
        $command = Yii::app()->db->createCommand($sql);
        $rowAffected = 0;
        foreach ($params as $param) {
            $rowAffected += $command->execute($param);
        }

        return [
            'sukses' => true,
            'rowAffected' => $rowAffected,
            'namasup' => $profil->nama
        ];
    }

    public function actionGantiSup()
    {
        if (isset($_POST['ajaxsup']) && !empty($_POST['sup-id']) && !empty($_POST['items'])) {
            $items = $_POST['items'];
            $supId = $_POST['sup-id'];
        } else {
            $this->renderJSON([
                'sukses' => false,
                'error' => [
                    'code' => 500,
                    'msg' => 'Tidak ada data!'
                ]
            ]);
        }
        $this->renderJSON([
            'sukses' => false,
            'error' => [
                'code' => 500,
                'msg' => 'Tidak ada data!'
            ]
        ]);
    }

    public function renderSuppliers($data)
    {
        $return = '';
        $sups = $data->listSupplier;
        if (!empty($sups)) {
            $str = "";
            foreach ($sups as $sup) {
                if ($sup['default']) {
                    $str .= '<b>' . $sup['nama'] . '</b></br>';
                } else {
                    $str .= $sup['nama'] . '</br>';
                }
            }
            $return = $str;
        }
        return $return;
    }

}
