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
        return [
            [
                'csvFile',
                'file',
                'types'      => 'xls',
                'maxSize'    => 5242880,
                'allowEmpty' => true,
                'wrongType'  => 'Only csv allowed.',
                'tooLarge'   => 'File too large! 5MB is the limit',
            ],
            ['profilId', 'required'],
            ['profilId', 'length', 'max' => 10],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'csvFile'  => 'Upload CSV File',
            'profilId' => 'Profil',
        ];
    }

    public function simpanCsvKePembelian()
    {
        $csvFileName  = $this->csvFile->name;
        $namaFileArr  = explode('.', $csvFileName);
        $namaFileSaja = $namaFileArr[0];
        $namaFile     = explode('-', $namaFileSaja);
        $refNo        = $namaFile[0];
        $refTgl       = "{$namaFile[4]}-{$namaFile[3]}-{$namaFile[2]}";
        $checksum     = $namaFile[8] ?? '';

        Yii::log('Checksum: ' . $checksum);
        $csvText = '';
        $fh      = fopen($this->csvFile->tempName, 'r');
        while ($row = fgets($fh)) {
            $csvText .= $row;
        }
        fclose($fh);

        /* Bandingkan checksum --start-- */
        // todo: Dibuat wajib, untuk supplier tertentu
        $wajib = false;
        $namaFileTanpaChecksum = '';
        for ($i = 0; $i < 8; $i++) {
            if ($i == 0) {
                $namaFileTanpaChecksum .= $namaFile[$i];
                continue;
            }
            $namaFileTanpaChecksum .= '-' . $namaFile[$i];
        }
        $calculateChecksum = hash_hmac('sha256', $csvText, $namaFileTanpaChecksum, false);
        if (!empty($checksum) && $calculateChecksum != $checksum) {
            Yii::log('cs:' . $checksum . ' | calculate:' . $calculateChecksum);
            Yii::log('fileName:' . $namaFileTanpaChecksum . " \ntext:" . $csvText);
            throw new CHttpException(500, 'Data rusak! Import data tidak bisa dilakukan');
        } elseif (empty($checksum)) {
            Yii::log('Import pembelian: Checksum tidak ada');
            if ($wajib) {
                throw new CHttpException(500, 'Nama file tidak sesuai format! Import data tidak bisa dilakukan');
            }
        } elseif ($calculateChecksum == $checksum) {
            Yii::log('Import pembelian: Checksum OK, commit transaction');
        }
        /* Bandingkan checksum --finish-- */

        $profilId = $this->profilId;

        $transaction = Yii::app()->db->beginTransaction();

        $pembelian                    = new Pembelian;
        $pembelian->profil_id         = $profilId;
        $pembelian->referensi         = $refNo;
        $pembelian->tanggal_referensi = $refTgl;

        try {
            if ($pembelian->save()) {
                $fp = fopen($this->csvFile->tempName, 'r');
                if ($fp) {
                    //  print_r($line); exit;

                    $line = fgetcsv($fp, 1000, ',');
                    do {
                        if ($line[0] == 'barcode') {
                            continue;
                        }

                        /* field csv
                         * "barcode","idBarang","namaBarang","jumBarang","hargaBeli","hargaJual","RRP","SatuanBarang","KategoriBarang","Supplier","kasir"
                         *  0         1          2            3           4           5           6     7              8                9          10
                         *
                         * "struktur_lv1","struktur_lv2","struktur_lv3"
                         * 11              12             13
                         */
                        $barangAda = Barang::model()->find('barcode=:barcode', [':barcode' => $line[0]]);
                        $barangId  = null;

                        // Jika ada struktur, maka (langsung) input struktur.
                        if (!empty($line[13])) {
                            $sql = '
                                SELECT
                                    id, parent_id
                                FROM
                                    barang_struktur

                                WHERE
                                    nama = :namaStruk AND `level` = :level
                                ';

                            $strukLv1Ada = Yii::app()->db->createCommand($sql)->bindValues([':namaStruk' => $line[11], ':level' => 1])->queryRow();
                            // $strukLv1Ada = StrukturBarang::model()->find('nama=:strukLv1 AND level=1', [':strukLv1' => $line[11]]);
                            if (empty($strukLv1Ada)) {
                                $strukLv1Baru        = new StrukturBarang();
                                $strukLv1Baru->nama  = $line[11];
                                $strukLv1Baru->level = 1;
                                if (!$strukLv1Baru->save()) {
                                    throw new Exception('Gagal simpan struktur (lv1) baru', 500);
                                }
                                $strukLv1Id = $strukLv1Baru->id;
                            } else {
                                // $strukLv1Id = $strukLv1Ada->id;
                                $strukLv1Id = $strukLv1Ada['id'];
                            }

                            $sql = '
                                SELECT
                                    lv2.id, lv2.parent_id
                                FROM
                                    barang_struktur lv2
                                        JOIN
                                    barang_struktur lv1 ON lv1.id = lv2.parent_id
                                WHERE
                                    lv2.nama = :namaStruk AND lv2.`level` = :level
                                        AND lv1.nama = :namaLevel1
                                ';
                            $strukLv2Ada = Yii::app()->db->createCommand($sql)->bindValues([':namaStruk' => $line[12], ':level' => 2, ':namaLevel1' => $line[11]])->queryRow();
                            // $strukLv2Ada = StrukturBarang::model()->find('nama=:strukLv2 AND level=2', [':strukLv2' => $line[12]]);
                            if (empty($strukLv2Ada)) {
                                $strukLv2Baru            = new StrukturBarang();
                                $strukLv2Baru->nama      = $line[12];
                                $strukLv2Baru->level     = 2;
                                $strukLv2Baru->parent_id = $strukLv1Id;
                                if (!$strukLv2Baru->save()) {
                                    throw new Exception('Gagal simpan struktur (lv2) baru', 500);
                                }
                                $strukLv2Id = $strukLv2Baru->id;
                            } else {
                                // Update parent nya, jika ada perubahan maka ikuti csv
                                // $strukLv2Ada->parent_id = $strukLv1Id;
                                // $strukLv2Ada->update();
                                // $strukLv2Id = $strukLv2Ada->id;
                                Yii::app()->db->createCommand("UPDATE barang_struktur SET parent_id = {$strukLv2Ada['parent_id']} WHERE id = {$strukLv2Ada['id']}")->execute();
                                $strukLv2Id = $strukLv2Ada['id'];
                            }

                            $sql = '
                                SELECT
                                    lv3.id, lv3.parent_id
                                FROM
                                    barang_struktur lv3
                                        JOIN
                                    barang_struktur lv2 ON lv2.id = lv3.parent_id
                                        JOIN
                                    barang_struktur lv1 ON lv1.id = lv2.parent_id
                                WHERE
                                    lv3.nama = :namaStruk AND lv3.`level` = :level
                                        AND lv2.nama = :namaLevel2
                                        AND lv1.nama = :namaLevel1
                                ';
                            $strukLv3Ada = Yii::app()->db->createCommand($sql)->bindValues([':namaStruk' => $line[13], ':level' => 3, ':namaLevel2' => $line[12], ':namaLevel1' => $line[11]])->queryRow();
                            // $strukLv3Ada = StrukturBarang::model()->find('nama=:strukLv3 AND level=3', [':strukLv3' => $line[13]]);
                            if (empty($strukLv3Ada)) {
                                $strukLv3Baru            = new StrukturBarang();
                                $strukLv3Baru->nama      = $line[13];
                                $strukLv3Baru->level     = 3;
                                $strukLv3Baru->parent_id = $strukLv2Id;
                                if (!$strukLv3Baru->save()) {
                                    throw new Exception('Gagal simpan struktur (lv3) baru', 500);
                                }
                                $strukLv3Id = $strukLv3Baru->id;
                            } else {
                                // Update parent nya, jika ada perubahan maka ikuti csv
                                // $strukLv3Ada->parent_id = $strukLv2Id;
                                // $strukLv3Ada->update();
                                // $strukLv3Id = $strukLv3Ada->id;
                                Yii::app()->db->createCommand("UPDATE barang_struktur SET parent_id = {$strukLv3Ada['parent_id']} WHERE id = {$strukLv3Ada['id']}")->execute();
                                $strukLv3Id = $strukLv3Ada['id'];
                            }
                        }

                        /* Jika belum ada kategori dan satuan, buat baru */
                        if (!empty($line[8])) {
                            $kategoriAda = KategoriBarang::model()->find('nama=:nama', [':nama' => $line[8]]);
                            if (is_null($kategoriAda)) {
                                $kategoriBaru       = new KategoriBarang;
                                $kategoriBaru->nama = $line[8];
                                if (!$kategoriBaru->save()) {
                                    throw new Exception('Gagal simpan kategori baru', 500);
                                }
                                $kategoriId = $kategoriBaru->id;
                            } else {
                                $kategoriId = $kategoriAda->id;
                            }
                        }
                        $satuanAda = SatuanBarang::model()->find('nama=:nama', [':nama' => $line[7]]);
                        if (is_null($satuanAda)) {
                            $satuanBaru       = new SatuanBarang;
                            $satuanBaru->nama = $line[7];
                            if (!$satuanBaru->save()) {
                                throw new Exception('Gagal simpan satuan baru', 500);
                            }
                            $satuanId = $satuanBaru->id;
                        } else {
                            $satuanId = $satuanAda->id;
                        }

                        if (is_null($barangAda)) {
                            /* Jika belum ada barcode nya, maka buat barang baru */

                            $barangBaru            = new Barang();
                            $barangBaru->scenario  = 'import';
                            $barangBaru->barcode   = $line[0];
                            $barangBaru->nama      = $line[2];
                            $barangBaru->satuan_id = $satuanId;
                            // Jika ada kategori, maka masukkan kategori
                            if (!empty($line[8])) {
                                $barangBaru->kategori_id = $kategoriId;
                            }
                            // Jika ada struktur, maka masukkan struktur
                            if (!empty($line[13])) {
                                $barangBaru->struktur_id = $strukLv3Id;
                            }
                            // if (!$barangBaru->validate()) {
                            //     throw new Exception('Gagal validasi barang baru: ' . serialize($barangBaru->getErrors()), 500);
                            // }
                            if (!$barangBaru->save()) {
                                throw new Exception('Gagal simpan barang baru: ' . serialize($barangBaru->getErrors()), 500);
                            }
                            $barangId = $barangBaru->id;
                            /* Jadikan supplier default ke profil ini */
                            $supplierBarang              = new SupplierBarang;
                            $supplierBarang->barang_id   = $barangId;
                            $supplierBarang->supplier_id = $profilId;
                            $supplierBarang->default     = SupplierBarang::SUPPLIER_DEFAULT;
                            if (!$supplierBarang->save()) {
                                throw new Exception('Gagal simpan Supplier Barang', 500);
                            }
                        } else {
                            $barangId = $barangAda->id;

                            /* Nanti ini bisa diambil dari input (checkbox) */
                            $updateNama     = false;
                            $updateStruktur = true;

                            /* Jika nama barang beda, update dari csv */
                            if ($updateNama && $barangAda->nama != $line[2]) {
                                Barang::model()->updateByPk($barangId, ['nama' => $line[2]]);
                            }

                            /* --Jika struktur beda, update dari csv */
                            /* Update: tetap update struktur id */
                            if (
                                $updateStruktur
                                && !empty($line[13])
                                //&& (empty($barangAda->struktur_id) || (isset($barangAda->struktur) && $barangAda->struktur->nama != $line[13]))
                            ) {
                                Barang::model()->updateByPk($barangId, ['struktur_id' => $strukLv3Id]);
                            }

                            if (is_null(SupplierBarang::model()->find('supplier_id=:supplierId AND barang_id=:barangId', [':supplierId' => $profilId, ':barangId' => $barangId]))) {
                                $supplierBarang              = new SupplierBarang;
                                $supplierBarang->barang_id   = $barangId;
                                $supplierBarang->supplier_id = $profilId;
                                if (!$supplierBarang->save()) {
                                    throw new Exception('Gagal simpan ke SupplierBarang: supplier_id=' . $profilId . ', barang_id=' . $barangId, 500);
                                }
                            }

                            if (SupplierBarang::belumAdaSupDefault($barangId)) {
                                /* Jika belum ada supplier default, assign supplier ini ke default */
                                $supplierBarang = new SupplierBarang;
                                $supplierBarang->setDefaultSupplier($profilId, $barangId);
                            }
                        }

                        $detail               = new PembelianDetail;
                        $detail->pembelian_id = $pembelian->id;
                        $detail->barang_id    = $barangId;
                        $detail->qty          = $line[3];
                        $detail->harga_beli   = $line[5];
                        $detail->harga_jual   = $line[6];
                        if (!$detail->save()) {
                            throw new Exception('Gagal simpan detail pembelian' . serialize($detail->errors), 500);
                        }
                    } while (($line = fgetcsv($fp, 2000)) != false);
                }
                fclose($fp);
                $transaction->commit();
                return [
                    'sukses'      => true,
                    'pembelianId' => $pembelian->id,
                ];
            } else {
                throw new Exception('Gagal Simpan Pembelian');
            }
        } catch (Exception $ex) {
            $transaction->rollback();

            return [
                'sukses' => false,
                'error'  => [
                    'msg'  => $ex->getMessage(),
                    'code' => $ex->getCode(),
                ],
            ];
        }
    }
}
