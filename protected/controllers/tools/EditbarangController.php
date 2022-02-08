<?php

class EditbarangController extends Controller
{

    public function actionIndex()
    {
        $model = new Barang('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Barang'])) {
            $model->attributes = $_GET['Barang'];
        }

        $lv1 = new StrukturBarang('search');
        $lv1->unsetAttributes(); // clear any default values
        $lv1->setAttribute('level', 1); // default yang tampil
        $lv1->setAttribute('status', StrukturBarang::STATUS_PUBLISH);

        $strukturDummy = new StrukturBarang('search');
        $strukturDummy->unsetAttributes(); // clear any default values
        $strukturDummy->setAttribute('level', 0);

        $this->render('index', [
            'model'         => $model,
            'lv1'           => $lv1,
            'strukturDummy' => $strukturDummy,
        ]);
    }

    public function actionSetna()
    {
        if (isset($_POST['ajaxdata']) && !empty($_POST['items'])) {
            $items = $_POST['items'];
            $this->renderJSON($this->_setStatus($items, Barang::STATUS_TIDAK_AKTIF));
        } else {
            $this->renderJSON([
                'sukses' => false,
                'error'  => [
                    'code' => 500,
                    'msg'  => 'Tidak ada data!',
                ],
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
                'error'  => [
                    'code' => 500,
                    'msg'  => 'Tidak ada data!',
                ],
            ]);
        }
    }

    private function _setStatus($items, $status)
    {
        $condition = 'id in (';
        $i         = 1;
        $params    = [];
        $pertamax  = true;
        foreach ($items as $item) {
            $key = ':item' . $i;
            if (!$pertamax) {
                $condition .= ',';
            }
            $condition .= $key;
            $params[$key] = $item;
            $pertamax     = false;
            $i++;
        }
        $condition .= ')';
        $rowAffected = Barang::model()->updateAll(['status' => $status], $condition, $params);
        return [
            'sukses'      => true,
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
                'error'  => [
                    'code' => 500,
                    'msg'  => 'Tidak ada data!',
                ],
            ]);
        }
    }

    private function _setRak($items, $rakId)
    {
        $condition = 'id in (';
        $i         = 1;
        $params    = [];
        $pertamax  = true;
        foreach ($items as $item) {
            $key = ':item' . $i;
            if (!$pertamax) {
                $condition .= ',';
            }
            $condition .= $key;
            $params[$key] = $item;
            $pertamax     = false;
            $i++;
        }
        $condition .= ')';
        $rowAffected = Barang::model()->updateAll(['rak_id' => $rakId], $condition, $params);
        $rak         = RakBarang::model()->findByPk($rakId);
        return [
            'sukses'      => true,
            'rowAffected' => $rowAffected,
            'namarak'     => $rak->nama,
        ];
    }

    public function actionFormEditSup()
    {
        $this->renderPartial('_form_edit_supplier');
    }

    public function actionTambahSup()
    {
        if (isset($_POST['ajaxsup']) && !empty($_POST['sup-id']) && !empty($_POST['items'])) {
            $items      = $_POST['items'];
            $supId      = $_POST['sup-id'];
            $setDefault = $_POST['sup-def'];
            $this->renderJSON($this->_tambahSupplier($items, $supId, $setDefault));
        } else {
            $this->renderJSON([
                'sukses' => false,
                'error'  => [
                    'code' => 500,
                    'msg'  => 'Tidak ada data!',
                ],
            ]);
        }
        $this->renderJSON([
            'sukses' => false,
            'error'  => [
                'code' => 500,
                'msg'  => 'Tidak ada data!',
            ],
        ]);
    }

    private function _tambahSupplier($items, $supplierId, $setDefault)
    {
        $profil = Profil::model()->findByPk($supplierId);

        $sql      = "INSERT IGNORE INTO `supplier_barang` (supplier_id, barang_id, updated_by, created_at) VALUES (:supId, :barangId, :userId, :waktu)";
        $params   = [];
        $sekarang = date('Y-m-d H:i:s');
        foreach ($items as $item) {
            $params[] = [':supId' => $supplierId, ':barangId' => $item, ':userId' => Yii::app()->user->id, ':waktu' => $sekarang];
        }
        $command     = Yii::app()->db->createCommand($sql);
        $rowAffected = 0;
        foreach ($params as $param) {
            $rowAffected += $command->execute($param);
        }

        if ($setDefault) {
            foreach ($items as $item) {
                $supBarang = Yii::app()->db->createCommand("select id from supplier_barang where supplier_id=:supplierId and barang_id=:barangId")
                    ->bindValues([':supplierId' => $supplierId, ':barangId' => $item])->queryRow();
                SupplierBarang::model()->assignDefaultSupplier($supBarang['id'], $item);
            }
        }

        return [
            'sukses'      => true,
            'rowAffected' => $rowAffected,
            'namasup'     => $profil->nama,
            'setDefault'  => $setDefault,
        ];
    }

    public function actionGantiSup()
    {
        if (isset($_POST['ajaxsup']) && !empty($_POST['sup-id']) && !empty($_POST['items'])) {
            $items = $_POST['items'];
            $supId = $_POST['sup-id'];
            $this->renderJSON($this->_gantiSupplier($items, $supId));
        } else {
            $this->renderJSON([
                'sukses' => false,
                'error'  => [
                    'code' => 500,
                    'msg'  => 'Tidak ada data!',
                ],
            ]);
        }
        $this->renderJSON([
            'sukses' => false,
            'error'  => [
                'code' => 500,
                'msg'  => 'Tidak ada data!',
            ],
        ]);
    }

    private function _gantiSupplier($items, $supId)
    {
        $profil = Profil::model()->findByPk($supId);

        /* Delete all barang di items */
        $condition = 'barang_id in (';
        $i         = 1;
        $params    = [];
        $pertamax  = true;
        foreach ($items as $item) {
            $key = ':item' . $i;
            if (!$pertamax) {
                $condition .= ',';
            }
            $condition .= $key;
            $params[$key] = $item;
            $pertamax     = false;
            $i++;
        }
        $condition .= ')';
        $rowDeleted = SupplierBarang::model()->deleteAll($condition, $params);

        /* insert all barang dengan supplier $supId dan default */
        $sql      = "INSERT INTO `supplier_barang` (supplier_id, barang_id, `default`, updated_by, created_at) VALUES (:supId, :barangId, :default, :userId, :waktu)";
        $params   = [];
        $sekarang = date('Y-m-d H:i:s');
        foreach ($items as $item) {
            $params[] = [
                ':supId'    => $supId,
                ':barangId' => $item,
                ':default'  => 1, // set as default!
                ':userId'   => Yii::app()->user->id,
                ':waktu'    => $sekarang,
            ];
        }
        $command     = Yii::app()->db->createCommand($sql);
        $rowAffected = 0;
        foreach ($params as $param) {
            $rowAffected += $command->execute($param);
        }
        return [
            'sukses'      => true,
            'rowDeleted'  => $rowDeleted,
            'rowAffected' => $rowAffected,
            'namasup'     => $profil->nama,
        ];
    }

    public function renderSuppliers($data)
    {
        $return = '';
        $sups   = $data->listSupplier;
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

    private function _setKat($items, $katId)
    {
        $condition = 'id in (';
        $i         = 1;
        $params    = [];
        $pertamax  = true;
        foreach ($items as $item) {
            $key = ':item' . $i;
            if (!$pertamax) {
                $condition .= ',';
            }
            $condition .= $key;
            $params[$key] = $item;
            $pertamax     = false;
            $i++;
        }
        $condition .= ')';
        $rowAffected = Barang::model()->updateAll(['kategori_id' => $katId], $condition, $params);
        $kat         = KategoriBarang::model()->findByPk($katId);
        return [
            'sukses'      => true,
            'rowAffected' => $rowAffected,
            'namakat'     => $kat->nama,
        ];
    }

    public function actionFormGantiKat()
    {
        $this->renderPartial('_form_ganti_kategori');
    }

    public function actionSetKat()
    {
        if (isset($_POST['ajaxkat']) && !empty($_POST['kat-id']) && !empty($_POST['items'])) {
            $items = $_POST['items'];
            $katId = $_POST['kat-id'];
            $this->renderJSON($this->_setKat($items, $katId));
        } else {
            $this->renderJSON([
                'sukses' => false,
                'error'  => [
                    'code' => 500,
                    'msg'  => 'Tidak ada data!',
                ],
            ]);
        }
    }

    public function actionFormGantiStruktur()
    {
        $this->renderPartial('_form_ganti_struktur', []);
    }

    private function _setStruktur($items, $strukturL3Id)
    {
        $condition = 'id in (';
        $i         = 1;
        $params    = [];
        $pertamax  = true;
        foreach ($items as $item) {
            $key = ':item' . $i;
            if (!$pertamax) {
                $condition .= ',';
            }
            $condition .= $key;
            $params[$key] = $item;
            $pertamax     = false;
            $i++;
        }
        $condition .= ')';
        $rowAffected = Barang::model()->updateAll(['struktur_id' => $strukturL3Id], $condition, $params);
        $struktur    = StrukturBarang::model()->findByPk($strukturL3Id);
        return [
            'sukses'       => true,
            'rowAffected'  => $rowAffected,
            'namaStruktur' => $struktur->nama,
        ];
    }

    public function actionGantiStruktur()
    {

        if (!empty($_POST['struktur-id']) && !empty($_POST['items'])) {
            $items        = $_POST['items'];
            $strukturL3Id = $_POST['struktur-id'];
            $this->renderJSON($this->_setStruktur($items, $strukturL3Id));
        } else {
            $this->renderJSON([
                'sukses' => false,
                'error'  => [
                    'code' => 500,
                    'msg'  => 'Tidak ada data!',
                ],
            ]);
        }
    }

    public function actionFormMinimumRestock()
    {
        $this->renderPartial('_form_set_minimum_restock');
    }

    public function actionSetMinimumRestock()
    {
        if (isset($_POST['ajaxminrestock']) && $_POST['minrestock-value']>=0 && !empty($_POST['items'])) {
            $items            = $_POST['items'];
            $minrestock_value = $_POST['minrestock-value'];
            $this->renderJSON($this->_setMinRestock($items, $minrestock_value));
        } else {
            $this->renderJSON([
                'sukses' => false,
                'error'  => [
                    'code' => 500,
                    'msg'  => 'Data salah/tidak ada!',
                ],
            ]);
        }
    }

    private function _setMinRestock($items, $value)
    {
        $condition = 'id in (';
        $i         = 1;
        $params    = [];
        $pertamax  = true;
        foreach ($items as $item) {
            $key = ':item' . $i;
            if (!$pertamax) {
                $condition .= ',';
            }
            $condition .= $key;
            $params[$key] = $item;
            $pertamax     = false;
            $i++;
        }
        $condition .= ')';
        $rowAffected = Barang::model()->updateAll(['restock_min' => $value], $condition, $params);
        return [
            'sukses'           => true,
            'rowAffected'      => $rowAffected,
            'minrestock_value' => $value,
        ];
    }
}
