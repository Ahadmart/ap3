<?php

class CetaklabelbarangController extends Controller
{

    public function actionIndex()
    {
        $pembelian = new Pembelian('search');
        $pembelian->unsetAttributes();  // clear any default values
        if (isset($_GET['Pembelian'])) {
            $pembelian->attributes = $_GET['Pembelian'];
        }
        $pembelian->status = '>' . Pembelian::STATUS_DRAFT;

        $labelBarang = new LabelBarangCetak('search');
        $labelBarang->unsetAttributes();
        if (isset($_GET['LabelBarangCetak'])) {
            $labelBarang->attributes = $_GET['LabelBarangCetak'];
        }
        $labelBarang->updated_by = Yii::app()->user->id;

        // $barang = new Barang('search');

        $barang = new Barang('search');
        $barang->unsetAttributes();
        $barang->setAttribute('id', '0');

        if (isset($_GET['Barang'])) {
            $barang->unsetAttributes(['id']);
            $barang->attributes  = $_GET['Barang'];
            $criteria            = new CDbCriteria;
            $criteria->condition = 'status = :status';
            $criteria->order     = 'nama';
            $criteria->params    = [':status' => Barang::STATUS_AKTIF];
            $barang->setDbCriteria($criteria);
        }

        $this->render('index',
                [
            'pembelian'   => $pembelian,
            'labelBarang' => $labelBarang,
            'barang'      => $barang,
        ]);
    }

    public function renderPembelianLinkToView($data)
    {
        $return = '';
        if (isset($data->nomor)) {
            $return = '<a href="' .
                    $this->createUrl('/pembelian/view', ['id' => $data->id]) . '">' .
                    $data->nomor . '</a>';
        }
        return $return;
    }

    public function renderTombolTambahkan($data, $row)
    {
        return CHtml::link('<i class="fa fa-plus"><i>', Yii::app()->controller->createUrl('tambahpembelian'),
                        [
                    'data-pembelianid' => $data->id,
                    'class'            => 'tombol-tambahkan'
        ]);
    }

    public function actionTambahPembelian()
    {
        $r = [
            'sukses' => false,
            'error'  => [
                'code' => 500,
                'msg'  => 'Tidak ada data masuk'
            ]
        ];
        if (isset($_POST['pembelianId'])) {
            $r = LabelBarangCetak::tambahPembelian($_POST['pembelianId']);
        }
        $this->renderJSON($r);
    }

    public function actionHapus()
    {
        if (isset($_POST['ajaxdata']) && !empty($_POST['items'])) {
            $items = $_POST['items'];
            $this->renderJSON($this->_hapus($items));
        } else {
            $this->renderJSON([
                'sukses' => false,
                'error'  => [
                    'code' => 500,
                    'msg'  => 'Tidak ada data!'
                ]
            ]);
        }
    }

    private function _hapus($items)
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
            $condition    .= $key;
            $params[$key] = $item;
            $pertamax     = false;
            $i++;
        }
        $condition   .= ')';
        $rowAffected = LabelBarangCetak::model()->deleteAll($condition, $params);
        return [
            'sukses'      => true,
            'rowAffected' => $rowAffected,
        ];
    }

    public function actionFormPilihPrinter()
    {
        $printerLabel = Device::model()->listDevices([Device::TIPE_LABEL_ZPL]);
        $listLayout   = LabelBarangCetak::listLayout();

        $this->renderPartial('_form_pilih_printer', ['printerLabel' => $printerLabel, 'listLayout' => $listLayout]);
    }

    public function actionCetakLabel()
    {
        if (isset($_POST['ajaxprinter']) && !empty($_POST['printer-id']) && !empty($_POST['items'])) {
            $items     = $_POST['items'];
            $printerId = $_POST['printer-id'];
            $layoutId  = !empty($_POST['layout-id']) ? $_POST['layout-id'] : LabelBarangCetak::LAYOUT_DEFAULT;
            $this->renderJSON(LabelBarangCetak::cetak($items, $printerId, $layoutId));
        } else {
            $this->renderJSON([
                'sukses' => false,
                'error'  => [
                    'code' => 500,
                    'msg'  => 'Tidak ada data!'
                ]
            ]);
        }
    }

    public function renderTombolTambahkanBarang($data, $row)
    {
        return CHtml::link('<i class="fa fa-plus"><i>', Yii::app()->controller->createUrl('tambahbarang'),
                        [
                    'data-barangid' => $data->id,
                    'class'         => 'tombol-tambahkan-barang'
        ]);
    }

    public function actionTambahBarang()
    {
        $r = [
            'sukses' => false,
            'error'  => [
                'code' => 500,
                'msg'  => 'Tidak ada data masuk'
            ]
        ];
        if (isset($_POST['barangId'])) {
            $r = LabelBarangCetak::tambahBarang($_POST['barangId']);
        }
        $this->renderJSON($r);
    }

    /**
     * Update qty label via ajax
     */
    public function actionUpdateQty()
    {
        if (isset($_POST['pk'])) {
            $pk          = $_POST['pk'];
            $qty         = $_POST['value'];
            $detail      = LabelBarangCetak::model()->findByPk($pk);
            $detail->qty = $qty;

            $return = ['sukses' => false];
            if ($detail->save()) {
                $return = ['sukses' => true];
            }

            $this->renderJSON($return);
        }
    }

}
