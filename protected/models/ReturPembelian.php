<?php

/**
 * This is the model class for table "retur_pembelian".
 *
 * The followings are the available columns in table 'retur_pembelian':
 * @property string $id
 * @property string $nomor
 * @property string $tanggal
 * @property string $profil_id
 * @property string $hutang_piutang_id
 * @property string $referensi
 * @property string $tanggal_referensi
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Profil $profil
 * @property User $updatedBy
 * @property ReturPembelianDetail[] $returPembelianDetails
 */
class ReturPembelian extends CActiveRecord
{
    const STATUS_DRAFT   = 0;
    const STATUS_PIUTANG = 1;
    const STATUS_LUNAS   = 2;
    const STATUS_POSTED  = 3; // Request sponsor: Proses diantara draft dan piutang. Mengurangi stok, tapi tidak mengurangi stok total.
    const STATUS_BATAL   = 4;
    /* ===================== */
    const KERTAS_LETTER = 10;
    const KERTAS_A4     = 20;
    const KERTAS_FOLIO  = 30;
    /* ===================== */
    const KERTAS_LETTER_NAMA = 'Letter';
    const KERTAS_A4_NAMA     = 'A4';
    const KERTAS_FOLIO_NAMA  = 'Folio';

    public $namaSupplier;
    public $max; // Untuk mencari untuk nomor surat;
    public $namaUpdatedBy;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'retur_pembelian';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['profil_id', 'required'],
            ['status', 'numerical', 'integerOnly' => true],
            ['nomor, referensi', 'length', 'max' => 45],
            ['profil_id, hutang_piutang_id, updated_by', 'length', 'max' => 10],
            ['tanggal_referensi, created_at, tanggal, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, nomor, tanggal, profil_id, hutang_piutang_id, status, updated_at, updated_by, created_at, namaSupplier, namaUpdatedBy', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'profil'                => [self::BELONGS_TO, 'Profil', 'profil_id'],
            'updatedBy'             => [self::BELONGS_TO, 'User', 'updated_by'],
            'returPembelianDetails' => [self::HAS_MANY, 'ReturPembelianDetail', 'retur_pembelian_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'nomor'             => 'Nomor',
            'tanggal'           => 'Tanggal',
            'profil_id'         => 'Supplier',
            'hutang_piutang_id' => 'Hutang Piutang',
            'referensi'         => 'Referensi',
            'tanggal_referensi' => 'Tanggal Referensi',
            'status'            => 'Status',
            'updated_at'        => 'Updated At',
            'updated_by'        => 'Updated By',
            'created_at'        => 'Created At',
            'namaUpdatedBy'     => 'User',
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('t.nomor', $this->nomor, true);
        $criteria->compare("DATE_FORMAT(t.tanggal, '%d-%m-%Y')", $this->tanggal, true);
        $criteria->compare('profil_id', $this->profil_id, true);
        $criteria->compare('hutang_piutang_id', $this->hutang_piutang_id, true);
        $criteria->compare('referensi', $this->referensi, true);
        $criteria->compare("DATE_FORMAT(t.tanggal_referensi, '%d-%m-%Y')", $this->tanggal_referensi, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $criteria->with = ['profil', 'updatedBy'];
        $criteria->compare('profil.nama', $this->namaSupplier, true);
        $criteria->compare('updatedBy.nama_lengkap', $this->namaUpdatedBy, true);

        $sort = [
            'defaultOrder' => 'case t.status when ' . self::STATUS_DRAFT . ' then 0 '
                . 'when ' . self::STATUS_POSTED . ' then 1 '
                . 'when ' . self::STATUS_PIUTANG . ' then 2 '
                . 'when ' . self::STATUS_LUNAS . ' then 3 '
                . 'else 4 end, t.tanggal desc',
            'attributes'   => [
                'namaSupplier'  => [
                    'asc'  => 'profil.nama',
                    'desc' => 'profil.nama desc',
                ],
                'namaUpdatedBy' => [
                    'asc'  => 'updatedBy.nama_lengkap',
                    'desc' => 'updatedBy.nama_lengkap desc',
                ],
                '*',
            ],
        ];
        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort'     => $sort,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ReturPembelian the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
            /*
             * Tanggal akan diupdate jika melalui proses simpanReturBeli
             * bersamaan dengan dapat nomor
             */
            $this->tanggal = date('Y-m-d H:i:s');
        }
        $this->updated_at = date('Y-m-d H:i:s');
        $this->updated_by = Yii::app()->user->id;

        // Jika disimpan melalui proses simpan retur pembelian
        if ($this->scenario === 'simpanReturBeli') {
            // Status diubah jadi pembelian belum terima (piutang)
            // $this->status = ReturPembelian::STATUS_PIUTANG;
            // Update per 3 Mar 2021, simpan berubah ke posted
            $this->status = ReturPembelian::STATUS_POSTED;
            // Dapat nomor dan tanggal
            /* Solusi temporer migrasi ke 6 digit seq num untuk ahadmart */
            $this->nomor   = $this->generateNomor6Seq();
            $this->tanggal = date('Y-m-d H:i:s');
        }
        return parent::beforeSave();
    }

    /**
     * Mencari nomor untuk penomoran surat
     * @return int maksimum+1 atau 1 jika belum ada nomor untuk tahun ini
     */
    public function cariNomorTahunan()
    {
        $tahun = date('y');
        $data  = $this->find([
            'select'    => 'max(substring(nomor,9)*1) as max',
            'condition' => "substring(nomor,5,2)='{$tahun}'",
        ]);

        $value = is_null($data) ? 0 : $data->max;
        return $value + 1;
    }

    /**
     * Membuat nomor surat, 6 digit sequence number
     * @return string Nomor sesuai format "[KodeCabang][kodeDokumen][Tahun][Bulan][SequenceNumber]"
     */
    public function generateNomor6Seq()
    {
        $config         = Config::model()->find("nama='toko.kode'");
        $kodeCabang     = $config->nilai;
        $kodeDokumen    = KodeDokumen::RETUR_PEMBELIAN;
        $kodeTahunBulan = date('ym');
        $sequence       = substr('00000' . $this->cariNomorTahunan(), -6);
        return "{$kodeCabang}{$kodeDokumen}{$kodeTahunBulan}{$sequence}";
    }

    public static function listStatus()
    {
        return [
            ReturPembelian::STATUS_DRAFT   => 'Draft',
            ReturPembelian::STATUS_POSTED  => 'Posted',
            ReturPembelian::STATUS_PIUTANG => 'Piutang',
            ReturPembelian::STATUS_LUNAS   => 'Lunas',
            ReturPembelian::STATUS_BATAL   => 'Batal',
        ];
    }

    public function getNamaStatus()
    {
        $status = $this->listStatus();
        return $status[$this->status];
    }

    /*
     * Mengembalikan nilai total
     */

    public function ambilTotal()
    {
        $pembelian = Yii::app()->db->createCommand('select sum(rpd.qty * invbalance.harga_beli) total
										from retur_pembelian_detail rpd
										join inventory_balance invbalance on invbalance.id = rpd.inventory_balance_id
										where retur_pembelian_id = :returPembelianId')
            ->bindValue(':returPembelianId', $this->id)
            ->queryRow();
        return $pembelian['total'];
    }

    /*
     * Mengambil nilai total retur pembelian
     */

    public function getTotal()
    {
        return number_format($this->ambilTotal(), 0, ',', '.');
    }

    /*
     * Simpan Retur Pembelian
     * 1. Ubah status jadi piutang
     * 2. Kurangi Inventory Balance
     * 3. Buat Piutang
     * Update per 3 Mar 2021
     * 1. Ubah status jadi posted
     * 2. Kurangi Inventory Balance
     */

    public function simpanReturPembelian()
    {
        $this->scenario = 'simpanReturBeli';
        $transaction    = $this->dbConnection->beginTransaction();
        try {
            /*
             * Save sekaligus mengubah status dari draft jadi posted
             */
            if ($this->save()) {
                $details = ReturPembelianDetail::model()->findAll('retur_pembelian_id=:id', [':id' => $this->id]);
                if (is_null($details)) {
                    throw new Exception('Tidak ada detail');
                }
                foreach ($details as $detail) :
                    $inventoryTerpakai = InventoryBalance::model()->returBeli($detail);
                    $count             = 1;
                    foreach ($inventoryTerpakai as $layer) {
                        if ($count > 1) {
                            $detailBaru                       = new ReturPembelianDetail;
                            $detailBaru->retur_pembelian_id   = $this->id;
                            $detailBaru->inventory_balance_id = $layer['id'];
                            $detailBaru->qty                  = $layer['qtyTerpakai'];
                            if (!$detailBaru->save()) {
                                throw new Exception('Gagal simpan retur pembelian detail (layer baru)', 500);
                            }
                        } else {
                            $detail->qty = $layer['qtyTerpakai'];
                            if (!$detail->save()) {
                                throw new Exception('Gagal simpan detail', 500);
                            }
                        }
                        $count++;
                    }
                endforeach;

                // $jumlahReturBeli = $this->ambilTotal();
                /*
                 * Create (piutang)
                 * Update per 3 Mar 2021, piutang created setelah ada aksi dari user
                 */
                // $hutang                     = new HutangPiutang;
                // $hutang->profil_id          = $this->profil_id;
                // $hutang->jumlah             = $jumlahReturBeli;
                // $hutang->tipe               = HutangPiutang::TIPE_PIUTANG;
                // $hutang->asal               = HutangPiutang::DARI_RETUR_BELI;
                // $hutang->nomor_dokumen_asal = $this->nomor;
                // if (!$hutang->save()) {
                //     throw new Exception("Gagal simpan hutang");
                // }

                /*
                 * Hutang Detail
                 */
                // $hutangDetail                    = new HutangPiutangDetail;
                // $hutangDetail->hutang_piutang_id = $hutang->id;
                // $hutangDetail->keterangan        = 'Retur Beli: ' . $this->nomor;
                // $hutangDetail->jumlah            = $jumlahReturBeli;
                // if (!$hutangDetail->save()) {
                //     throw new Exception("Gagal simpan hutang detail");
                // }

                /*
                 * Simpan hutang_id ke retur pembelian
                 */
                // if (!ReturPembelian::model()->updateByPk($this->id, ['hutang_piutang_id' => $hutang->id]) > 1) {
                //     throw new Exception("Gagal simpan hutang_id");
                // }

                $transaction->commit();

                return ['sukses' => true];
            } else {
                throw new Exception('Gagal Simpan Retur Pembelian');
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

    public function afterFind()
    {
        $this->tanggal           = !is_null($this->tanggal) ? date_format(date_create_from_format('Y-m-d H:i:s', $this->tanggal), 'd-m-Y H:i:s') : '0';
        $this->tanggal_referensi = !is_null($this->tanggal_referensi) ? date_format(date_create_from_format('Y-m-d', $this->tanggal_referensi), 'd-m-Y') : '';
        return parent::afterFind();
    }

    public function toIndoDate($timeStamp)
    {
        $tanggal   = date_format(date_create($timeStamp), 'j');
        $bulan     = date_format(date_create($timeStamp), 'n');
        $namabulan = $this->namaBulan($bulan);
        $tahun     = date_format(date_create($timeStamp), 'Y');
        return $tanggal . ' ' . $namabulan . ' ' . $tahun;
    }

    public function namaBulan($i)
    {
        static $bulan = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];
        return $bulan[$i - 1];
    }

    public function returPembelianText($draft = false, $cpi = 10)
    {
        $lebarKertas = 8; //inchi
        $jumlahKolom = $cpi * $lebarKertas;

        $configs = Config::model()->findAll();
        /*
         * Ubah config (object) jadi array
         */
        $branchConfig = [];
        foreach ($configs as $config) {
            $branchConfig[$config->nama] = $config->nilai;
        }

        $returPembelianDetail = Yii::app()->db->createCommand('
         select barang.barcode, barang.nama, pd.qty, inv.harga_beli
         from retur_pembelian_detail pd
         join inventory_balance inv on pd.inventory_balance_id = inv.id
         join barang on inv.barang_id = barang.id
         where pd.retur_pembelian_id = :returPembelianId
              ')
            ->bindValue(':returPembelianId', $this->id)
            ->queryAll();

        $nota = '';

        $strNomor = 'Nomor       : ' . $this->nomor;
        if ($draft) {
            $strNomor = 'Nomor       : DRAFT';
        }
        $strTgl   = 'Tanggal     : ' . $this->toIndoDate($this->tanggal);
        $strUser  = 'User        : ' . ucwords($this->updatedBy->nama_lengkap);
        $strTotal = 'Total       : ' . $this->getTotal();

        $kananMaxLength = strlen($strNomor) > strlen($strTgl) ? strlen($strNomor) : strlen($strTgl);
        /* Jika Nama User terlalu panjang, akan di truncate */
        $strUser = strlen($strUser) > $kananMaxLength ? substr($strUser, 0, $kananMaxLength - 2) . '..' : $strUser;

        $strInvoice = 'RETUR PEMBELIAN '; //Jumlah karakter harus genap!

        $nota = str_pad($branchConfig['toko.nama'], $jumlahKolom / 2 - strlen($strInvoice) / 2, ' ')
            . $strInvoice . str_pad(str_pad($strNomor, $kananMaxLength, ' '), $jumlahKolom / 2 - strlen($strInvoice) / 2, ' ', STR_PAD_LEFT)
            . PHP_EOL;
        $nota .= str_pad($branchConfig['toko.alamat1'], $jumlahKolom - $kananMaxLength, ' ')
            . str_pad($strTgl, $kananMaxLength, ' ')
            . PHP_EOL;
        $nota .= str_pad($branchConfig['toko.alamat2'], $jumlahKolom - $kananMaxLength, ' ')
            . str_pad($strUser, $kananMaxLength, ' ')
            . PHP_EOL;
        $nota .= str_pad($branchConfig['toko.alamat3'], $jumlahKolom - $kananMaxLength, ' ')
            . str_pad($strTotal, $kananMaxLength, ' ')
            . PHP_EOL . PHP_EOL;

        $nota .= 'Kepada: ' . $this->profil->nama . PHP_EOL;
        $nota .= '        ' . substr($this->profil->alamat1 . ' ' . $this->profil->alamat2 . ' ' . $this->profil->alamat3, 0, $jumlahKolom - 8) . PHP_EOL;
        if (isset($this->referensi) && !empty($this->referensi)) {
            $nota .= 'Ref : ' . $this->referensi . ' ';
            $nota .= isset($this->tanggal_referensi) ? $this->tanggal_referensi : '';
        }
        $nota .= PHP_EOL;

        $nota .= str_pad('', $jumlahKolom, '-') . PHP_EOL;
        $textHeader1 = ' Barang';
        $textHeader2 = 'Harga    Qty Sub Total ';
        $textHeader  = $textHeader1 . str_pad($textHeader2, $jumlahKolom - strlen($textHeader1), ' ', STR_PAD_LEFT) . PHP_EOL;
        $nota .= $textHeader;
        $nota .= str_pad('', $jumlahKolom, '-') . PHP_EOL;

        $no = 1;
        foreach ($returPembelianDetail as $detail) {
            $strBarcode   = str_pad(substr($detail['barcode'], 0, 13), 13, ' '); // Barcode hanya diambil 13 char pertama
            $strBarang    = str_pad(trim(substr($detail['nama'], 0, 28)), 28, ' '); //Nama Barang hanya diambil 28 char pertama
            $strQty       = str_pad($detail['qty'], 5, ' ', STR_PAD_LEFT);
            $strHargaBeli = str_pad(number_format($detail['harga_beli'], 0, ',', '.'), 8, ' ', STR_PAD_LEFT);
            $strSubTotal  = str_pad(number_format($detail['harga_beli'] * $detail['qty'], 0, ',', '.'), 8, ' ', STR_PAD_LEFT);
            $row1         = ' ' . $strBarcode . ' ' . $strBarang . ' ';
            $row2         = $strHargaBeli . '  ' . $strQty . '  ' . $strSubTotal;
            $row          = $row1 . str_pad($row2 . ' ', $jumlahKolom - strlen($row1), ' ', STR_PAD_LEFT) . PHP_EOL;

            $nota .= $row;
            $no++;
        }

        $nota .= str_pad('', $jumlahKolom, '-') . PHP_EOL . PHP_EOL;

        $nota .= PHP_EOL;
        return $nota;
    }

    public static function listNamaKertas()
    {
        return [
            self::KERTAS_A4     => self::KERTAS_A4_NAMA,
            self::KERTAS_LETTER => self::KERTAS_LETTER_NAMA,
            self::KERTAS_FOLIO  => self::KERTAS_FOLIO_NAMA,
        ];
    }

    public function keCsv()
    {
        $csv = '"barcode","nama","inv_id","ref","tgl_ref","harga_beli","qty"' . PHP_EOL;

        /*
         * Ambil data retur pembelian detail, untuk diexport ke csv
         */
        $details = Yii::app()->db->createCommand('
            SELECT
                barang.barcode,
                barang.nama,
                inv.id inv_id,
                p.referensi,
                p.tanggal_referensi,
                inv.harga_beli,
                pd.qty
            FROM
                retur_pembelian_detail pd
                    JOIN
                inventory_balance inv ON pd.inventory_balance_id = inv.id
                    JOIN
                barang ON inv.barang_id = barang.id
                    JOIN
                pembelian_detail d ON d.id = inv.pembelian_detail_id
                    JOIN
                pembelian p ON p.id = d.pembelian_id
            WHERE
                pd.retur_pembelian_id = :returPembelianId')
            ->bindValue(':returPembelianId', $this->id)
            ->queryAll();

        foreach ($details as $detail) :
            $csv .= "\"{$detail['barcode']}\","
                . "\"{$detail['nama']}\","
                . "\"{$detail['inv_id']}\","
                . "\"{$detail['referensi']}\","
                . "\"{$detail['tanggal_referensi']}\","
                . "\"{$detail['harga_beli']}\","
                . "\"{$detail['qty']}\""
                . PHP_EOL;
        endforeach;
        return $csv;
    }

    public function beforeValidate()
    {
        $this->tanggal_referensi = !empty($this->tanggal_referensi) ? date_format(date_create_from_format('d-m-Y', $this->tanggal_referensi), 'Y-m-d') : null;
        return parent::beforeValidate();
    }

    /**
     * Membuat piutang dari retur_pembelian posted
     * Update status menjadi Piutang dan simpan id hutang
     */
    public function terbitkanPiutang()
    {
        $transaction = $this->dbConnection->beginTransaction();
        try {
            if ($this->status != ReturPembelian::STATUS_PIUTANG) {
                // Jika status sudah piutang, tidak usah masuk sini!
                $jumlahReturBeli = $this->ambilTotal();
                /*
                 * Create (piutang)
                 * Update per 3 Mar 2021, piutang created setelah ada aksi dari user
                 */
                $hutang                     = new HutangPiutang;
                $hutang->profil_id          = $this->profil_id;
                $hutang->jumlah             = $jumlahReturBeli;
                $hutang->tipe               = HutangPiutang::TIPE_PIUTANG;
                $hutang->asal               = HutangPiutang::DARI_RETUR_BELI;
                $hutang->nomor_dokumen_asal = $this->nomor;
                if (!$hutang->save()) {
                    throw new Exception('Gagal simpan hutang', 500);
                }

                /*
                 * Hutang Detail
                 */
                $hutangDetail                    = new HutangPiutangDetail;
                $hutangDetail->hutang_piutang_id = $hutang->id;
                $hutangDetail->keterangan        = 'Retur Beli: ' . $this->nomor;
                $hutangDetail->jumlah            = $jumlahReturBeli;
                if (!$hutangDetail->save()) {
                    throw new Exception('Gagal simpan hutang detail', 500);
                }

                /*
                 * Update status menjadi piutang dan simpan hutang_id ke retur pembelian
                 */
                if (!ReturPembelian::model()->updateByPk($this->id, ['status' => self::STATUS_PIUTANG, 'hutang_piutang_id' => $hutang->id, 'updated_by' => Yii::app()->user->id]) > 1) {
                    throw new Exception('Gagal update status dan simpan hutang_id', 500);
                }

                $transaction->commit();
                return ['sukses' => true];
            } else {
                throw new Exception('Retur Pembelian ini sudah terbit piutang', 500);
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

    public function batal()
    {
        $transaction = $this->dbConnection->beginTransaction();
        try {
            // $this->status = self::STATUS_BATAL;
            // if (!($this->save())) {
            //     throw new Exception("Gagal membatalkan retur beli: " . $this->nomor);
            // }
            ReturPembelian::model()->updateByPk($this->id, ['status' => self::STATUS_BATAL]);
            $details = ReturPembelianDetail::model()->findAll('retur_pembelian_id=:id', [':id' => $this->id]);
            foreach ($details as $detail) {
                // Untuk setiap item, tambahkan qty ke inventory baru
                InventoryBalance::returBeliBatal($detail);
            }
            $transaction->commit();
            return ['sukses' => true];
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
