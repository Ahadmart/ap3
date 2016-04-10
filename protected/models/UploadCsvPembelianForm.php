<?php

/**
 * UploadCsvPembelianForm class.
 * UploadCsvPembelianForm is the data structure for keeping
 * upload csv file form data. It is used by the 'import' action of 'PembelianController'.
 * 
 */
class UploadCsvPembelianForm extends CFormModel
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

    public function simpanCsvKePembelian()
    {
        $csvFileName = $this->csvFile->name;
        $namaFile = explode('-', $csvFileName);
        $refNo = $namaFile[0];
        $refTgl = "{$namaFile[4]}-{$namaFile[3]}-{$namaFile[2]}";
        $profilId = $this->profilId;

        $transaction = Yii::app()->db->beginTransaction();

        $pembelian = new Pembelian;
        $pembelian->profil_id = $profilId;
        $pembelian->referensi = $refNo;
        $pembelian->tanggal_referensi = $refTgl;

        try {
            if ($pembelian->save()) {

                $fp = fopen($this->csvFile->tempName, 'r');
                if ($fp) {
                    $line = fgetcsv($fp, 1000, ",");
                    //  print_r($line); exit;
                    do {
                        if ($line[0] == 'barcode') {
                            continue;
                        }
                        /* field csv
                         * "barcode","idBarang","namaBarang","jumBarang","hargaBeli","hargaJual","RRP","SatuanBarang","KategoriBarang","Supplier","kasir"
                         *  0         1          2            3           4           5           6     7              8                9          
                         */
                        $barangAda = Barang::model()->find('barcode=:barcode', array(':barcode' => $line[0]));

                        if (is_null($barangAda)) {
                            /* Jika belum ada barcode nya, maka buat barang baru */

                            /* Jika belum ada kategori dan satuan, buat baru */
                            $kategoriAda = KategoriBarang::model()->find('nama=:nama', array(':nama' => $line[8]));
                            if (is_null($kategoriAda)) {
                                $kategoriBaru = new KategoriBarang;
                                $kategoriBaru->nama = $line[8];
                                if ($kategoriBaru->save()) {
                                    $kategoriId = $kategoriBaru->id;
                                }
                            } else {
                                $kategoriId = $kategoriAda->id;
                            }

                            $satuanAda = KategoriBarang::model()->find('nama=:nama', array(':nama' => $line[7]));
                            if (is_null($satuanAda)) {
                                $satuanBaru = new SatuanBarang;
                                $satuanBaru->nama = $line[7];
                                if ($satuanBaru->save()) {
                                    $satuanId = $satuanBaru->id;
                                }
                            } else {
                                $satuanId = $satuanAda->id;
                            }

                            $barangBaru = new Barang;
                            $barangBaru->barcode = $line[0];
                            $barangBaru->nama = $line[2];
                            $barangBaru->kategori_id = $kategoriId;
                            $barangBaru->satuan_id = $satuanId;
                            if ($barangBaru->save()) {
                                $barangId = $barangBaru->id;
                            }
                        } else {
                            $barangId = $barangAda->id;
                        }

                        $detail = new PembelianDetail;
                        $detail->pembelian_id = $pembelian->id;
                        $detail->barang_id = $barangId;
                        $detail->qty = $line[3];
                        $detail->harga_beli = $line[5];
                        $detail->harga_jual = $line[6];
                        if (!$detail->save()) {
                            throw new Exception('Gagal simpan detail pembelian');
                        }
                    } while (($line = fgetcsv($fp, 2000)) != FALSE);
                }

                $transaction->commit();
                return array(
                    'sukses' => true,
                    'pembelianId' => $pembelian->id
                );
            } else {
                throw new Exception("Gagal Simpan Pembelian");
            }
        } catch (Exception $ex) {
            $transaction->rollback();

            return array(
                'sukses' => false,
                'error' => array(
                    'msg' => $ex->getMessage(),
                    'code' => $ex->getCode(),
            ));
        }
    }

}
