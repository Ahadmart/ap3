<?php

class PanelmultihjController extends Controller
{
    private $_lastProductId = null;

    public function actionIndex()
    {
        $model = new HargaJualMulti('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['HargaJualMulti'])) {
            $model->attributes = $_GET['HargaJualMulti'];
        }

        $this->render('index', [
            'model' => $model,
        ]);
    }

    protected function renderGridCell($data, $row, $dataColumn)
    {
        switch ($dataColumn->name) {
            case 'barcode':
            return $this->_lastProductId != $data->barang_id ? $data->barang->barcode : '';
                break;
            case 'namaBarang':
            return $this->_lastProductId != $data->barang_id ? CHtml::link($data->barang->nama, $data->barang_id, ['class'=>'namabarang-link']) : '';
            break;
            case 'namaSatuan':
            return $this->_lastProductId != $data->barang_id ? $data->barang->satuan->nama : '';
            break;
            case 'hargaJual':
            if ($this->_lastProductId != $data->barang_id) {
                $this->_lastProductId = $data->barang_id;
                return $data->barang->hargaJual;
            } else {
                return '';
            }
            break;
            }
    }

    public function actionFormUpdateMultiHJ($id)
    {
        $hjMultiModel = new HargaJualMulti;
        $barang = Barang::model()->findByPk($id);
        $this->renderPartial('_form_update_multihj', [
            'hjMultiModel' => $hjMultiModel,
            'barang'     => $barang,
            ]);
    }
}
