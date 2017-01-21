<?php

/**
 * UploadCsvReturPenjualanForm class.
 * UploadCsvReturPenjualanForm is the data structure for keeping
 * upload csv file form data. It is used by the 'import' action of 'ReturPenjualanController'.
 *
 */
class UploadCsvReturPenjualanForm extends CFormModel
{

    public $csvFile;
    public $profilId;

    public function rules()
    {
        return array(
            array(
                'csvFile',
                'file',
                'types' => 'xls',
                'maxSize' => 5242880,
                'allowEmpty' => true,
                'wrongType' => 'Only csv allowed.',
                'tooLarge' => 'File too large! 5MB is the limit'
            ),
            array('profilId', 'required'),
            array('profilId', 'length', 'max' => 10),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'csvFile' => 'Upload CSV File',
            'profilId' => 'Profil'
        );
    }

    public function simpanCsvKeReturPenjualan()
    {
        $csvFileName = $this->csvFile->name;
        $namaFile = explode('_', $csvFileName);
        $refNo = $namaFile[0];
        $refTgl = substr($namaFile[2], 0, 15); // dalam format Ymd His dan diambil 15 digit timestamp (.csv tidak diambil)
        $profilId = $this->profilId;
        //echo $refNo.'-----'.$refTgl.'-----'.date_format(date_create_from_format('Ymd His', $refTgl), 'Y-m-d');

        $transaction = Yii::app()->db->beginTransaction();

        $retur = new ReturPenjualan;
        $retur->profil_id = $profilId;
        $retur->referensi = $refNo;
        $retur->tanggal_referensi = date_format(date_create_from_format('Ymd His', $refTgl), 'd-m-Y');

        try {
            if ($retur->save()) {
                $fp = fopen($this->csvFile->tempName, 'r');
                if ($fp) {
                    $line = fgetcsv($fp, 1000, ",");
                    //  print_r($line); exit;
                    do {
                        if ($line[0] == 'barcode') {
                            continue;
                        }
                        /* field csv
                         * "barcode", "nama","inv_id","ref","tgl_ref","harga_beli","qty"
                         *  0          1      2        3     4         5            6
                         */
                        $sql = "
                            SELECT 
                                d.id, d.harga_jual
                            FROM
                                penjualan_detail d
                                    JOIN
                                penjualan p ON p.id = d.penjualan_id
                                    AND p.nomor = :noPenjualan
                                    JOIN
                                barang b ON b.id = d.barang_id
                                    AND b.barcode = :barcode
                                ";
                        $adaDiPenjualan = Yii::app()->db->createCommand($sql)
                                ->bindValues([
                                    ':noPenjualan' => $line[3],
                                    ':barcode' => $line[0]
                                ])
                                ->queryRow();

                        if (!$adaDiPenjualan) {
                            throw new Exception("Barang {$line[0]} tidak ditemukan di penjualan {$line[3]}", 500);
                        }

                        $detail = new ReturPenjualanDetail;
                        $detail->retur_penjualan_id = $retur->id;
                        $detail->penjualan_detail_id = $adaDiPenjualan['id'];
                        $detail->qty = $line[6];
                        $detail->harga_jual = $adaDiPenjualan['harga_jual'];
                        if (!$detail->save()) {
                            throw new Exception('Gagal simpan detail retur penjualan', 500);
                        }
                    } while (($line = fgetcsv($fp, 2000)) != FALSE);
                }

                $transaction->commit();
                return [
                    'sukses' => true,
                    'returPenjualanId' => $retur->id
                ];
            } else {
                throw new Exception("Gagal Simpan Retur Penjualan");
            }
        } catch (Exception $ex) {
            $transaction->rollback();

            return [
                'sukses' => false,
                'error' => [
                    'msg' => $ex->getMessage(),
                    'code' => $ex->getCode(),
            ]];
        }
    }

}
