<?php

/**
 * This is the model class for table "penjualan".
 *
 * The followings are the available columns in table 'penjualan':
 * @property string $id
 * @property string $nomor
 * @property string $tanggal
 * @property string $profil_id
 * @property string $hutang_piutang_id
 * @property integer $transfer_mode
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property HutangPiutang $hutangPiutang
 * @property Profil $profil
 * @property User $updatedBy
 * @property PenjualanDetail[] $penjualanDetails
 * @property PenjualanDiskon[] $penjualanDiskons
 * @property PenjualanMember[] $penjualanMembers
 * @property PenjualanMemberOnline[] $penjualanMemberOnlines
 * @property PenjualanMultiHarga[] $penjualanMultiHargas
 * @property PenjualanTarikTunai[] $penjualanTarikTunais
 * @property So[] $sos
 */
class Penjualan extends CActiveRecord
{
    const STATUS_DRAFT   = 0;
    const STATUS_PIUTANG = 1;
    const STATUS_LUNAS   = 2;
    /* ========== */
    const CUSTOMER_UMUM = 2; // ID di DB untuk customer UMUM (non member)
    /* ========== */
    // nilai field transfer_mode
    const JENIS_PENJUALAN = 0;
    const JENIS_TRANSFER  = 1;

    public $max; // Untuk mencari untuk nomor surat;
    public $namaProfil;
    public $nomorHutangPiutang;
    public $namaUpdatedBy;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'penjualan';
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
            ['transfer_mode, status', 'numerical', 'integerOnly' => true],
            ['nomor', 'length', 'max' => 45],
            ['profil_id, hutang_piutang_id, updated_by', 'length', 'max' => 10],
            ['created_at, updated_at, updated_by, tanggal', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, nomor, tanggal, profil_id, hutang_piutang_id, transfer_mode, status, updated_at, updated_by, created_at, namaProfil, nomorHutangPiutang, namaUpdatedBy', 'safe', 'on' => 'search'],
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
            'hutangPiutang'          => [self::BELONGS_TO, 'HutangPiutang', 'hutang_piutang_id'],
            'profil'                 => [self::BELONGS_TO, 'Profil', 'profil_id'],
            'updatedBy'              => [self::BELONGS_TO, 'User', 'updated_by'],
            'penjualanDetails'       => [self::HAS_MANY, 'PenjualanDetail', 'penjualan_id'],
            'penjualanDiskons'       => [self::HAS_MANY, 'PenjualanDiskon', 'penjualan_id'],
            'penjualanMembers'       => [self::HAS_MANY, 'PenjualanMember', 'penjualan_id'],
            'penjualanMemberOnlines' => [self::HAS_MANY, 'PenjualanMemberOnline', 'penjualan_id'],
            'penjualanMultiHargas'   => [self::HAS_MANY, 'PenjualanMultiHarga', 'penjualan_id'],
            'penjualanTarikTunais'   => [self::HAS_MANY, 'PenjualanTarikTunai', 'penjualan_id'],
            'sos'                    => [self::HAS_MANY, 'So', 'penjualan_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'                 => 'ID',
            'nomor'              => 'Nomor',
            'tanggal'            => 'Tanggal',
            'profil_id'          => 'Profil',
            'hutang_piutang_id'  => 'Hutang Piutang',
            'transfer_mode'      => 'Transfer Mode',
            'status'             => 'Status',
            'updated_at'         => 'Updated At',
            'updated_by'         => 'Updated By',
            'created_at'         => 'Created At',
            'namaProfil'         => 'Customer',
            'nomorHutangPiutang' => 'Nomor Piutang',
            'namaUpdatedBy'      => 'User',
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
    public function search($merge = null)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('t.nomor', $this->nomor, true);
        $criteria->compare("DATE_FORMAT(t.tanggal, '%d-%m-%Y')", $this->tanggal, true);
        $criteria->compare('profil_id', $this->profil_id, true);
        $criteria->compare('hutang_piutang_id', $this->hutang_piutang_id, true);
        $criteria->compare('transfer_mode', $this->transfer_mode);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('t.updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $criteria->with = ['profil', 'hutangPiutang', 'updatedBy'];
        $criteria->compare('profil.nama', $this->namaProfil, true);
        $criteria->compare('hutangPiutang.nomor', $this->nomorHutangPiutang, true);
        $criteria->compare('updatedBy.nama_lengkap', $this->namaUpdatedBy, true);

        $sort = [
            'defaultOrder' => 't.status, tanggal desc',
            'attributes'   => [
                '*',
                'namaProfil'    => [
                    'asc'  => 'profil.nama',
                    'desc' => 'profil.nama desc',
                ],
                'nomorHutangPiutang', [
                    'asc'  => 'hutangPiutang.nomor',
                    'desc' => 'hutangPiutang.nomor desc',
                ],
                'namaUpdatedBy' => [
                    'asc'  => 'updatedBy.nama_lengkap',
                    'desc' => 'updatedBy.nama_lengkap desc',
                ],
            ],
        ];
        if ($merge !== null) {
            $criteria->mergeWith($merge);
        }

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort'     => $sort,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Penjualan the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeValidate()
    {
        $this->profil_id = empty($this->profil_id) ? Profil::PROFIL_UMUM : $this->profil_id;
        return parent::beforeValidate();
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s'); /*
             * Tanggal akan diupdate jika melalui proses simpanPenjualan
             * bersamaan dengan dapat nomor
             */
            $this->tanggal = date('Y-m-d H:i:s');
        }
        $this->updated_at = date('Y-m-d H:i:s');
        $this->updated_by = Yii::app()->user->id;
        // Jika disimpan melalui proses simpan penjualan
        if ($this->scenario === 'simpanPenjualan') {
            // Status diubah jadi penjualan belum bayar (piutang)
            $this->status = Penjualan::STATUS_PIUTANG;
            // Dapat nomor dan tanggal baru
            $this->tanggal = date('Y-m-d H:i:s');
            $this->nomor   = $this->generateNomor6Seq();
        }
        return parent::beforeSave();
    }

    /**
     * Mencari jumlah barang di tabel penjualan_detail
     * @param int $barangId ID Barang
     * @return int qty / jumlah barang, FALSE jika tidak ada
     */
    public function barangAda($barangId)
    {
        $detail = Yii::app()->db->createCommand('
        select sum(qty) qty from penjualan_detail
        where penjualan_id=:penjualanId and barang_id=:barangId
            ')->bindValues([':penjualanId' => $this->id, ':barangId' => $barangId])
            ->queryRow();

        return $detail['qty'];
    }

    /**
     * Tambah barang dengan harga modal
     * @param text $barcode
     * @param int $qty
     * @param boolean $cekLimit default FALSE
     * @return array
     * @throws Exception
     */
    public function transferBarang($barcode, $qty, $cekLimit = false)
    {
        $transaction = $this->dbConnection->beginTransaction();
        try {
            $barang = Barang::model()->find('barcode=:barcode', [':barcode' => $barcode]);

            /* Jika barang tidak ada */
            if (is_null($barang)) {
                throw new Exception('Barang tidak ditemukan', 500);
            }

            $barangAda = $this->barangAda($barang->id);
            if ($barangAda) {
                $qty += $barangAda;
                PenjualanDetail::model()->deleteAll('barang_id=:barangId AND penjualan_id=:penjualanId', [
                    ':barangId'    => $barang->id,
                    ':penjualanId' => $this->id,
                ]);
            }
            $this->tambahBarangTransferDetail($barang, $qty);

            if ($cekLimit && $this->lewatLimit()) {
                throw new Exception('Gagal!! Melebihi limit penjualan!', 500);
            }

            $transaction->commit();
            return [
                'sukses' => true,
            ];
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

    /**
     * Tambah barang transfer ke tabel detail.
     * @param ActiveRecord $barang Object Barang
     * @param int $qty Qty barang transfer total yang akan ditambah
     */
    public function tambahBarangTransferDetail($barang, $qty)
    {
        $sisa              = $qty;
        $hargaBeliAwal     = InventoryBalance::model()->getHargaBeliAwal($barang->id);
        $hargaJualTerakhir = HargaJual::model()->terkini($barang->id);

        $detail                         = new PenjualanDetail;
        $detail->penjualan_id           = $this->id;
        $detail->barang_id              = $barang->id;
        $detail->qty                    = $qty;
        $detail->harga_jual             = $hargaBeliAwal;
        $detail->harga_jual_rekomendasi = $hargaJualTerakhir;

        if (!$detail->save()) {
            throw new Exception("Gagal simpan penjualan detail: penjualanId:{$this->id}, barangId:{$barang->id}, qty:{$qty}", 500);
        }
    }

    /**
     * Menambah detail penjualan untuk transfer mode, jika ternyata harga beli berbeda
     * Menyesuaikan qty detail penjualan sebelumnya
     * @param ActiveRecord $detail PenjualanDetail
     * @param ActiveRecord $hpp HargaPokokPenjualan
     */
    public function tambahDetailTransferBarang($detail, $hpp)
    {
        if ($detail->harga_jual != $hpp->harga_beli) {
            $detailBaru                         = new PenjualanDetail;
            $detailBaru->penjualan_id           = $this->id;
            $detailBaru->barang_id              = $detail->barang_id;
            $detailBaru->qty                    = $hpp->qty;
            $detailBaru->harga_jual             = $hpp->harga_beli;
            $detailBaru->harga_jual_rekomendasi = $detail->harga_jual_rekomendasi;

            if (!$detailBaru->save()) {
                throw new Exception("Gagal simpan penjualan detail: penjualanId:{$this->id}, barangId:{$detail->barang_id}, qty:{$detailBaru->qty}", 500);
            }

            if (!HargaPokokPenjualan::model()->updateByPk($hpp->id, ['penjualan_detail_id' => $detailBaru->id]) > 1) {
                throw new Exception('Gagal update hpp', 500);
            }

            /* update qty detail sebelumnya */
            $qtySebelum = $detail->qty;
            if (!PenjualanDetail::model()->updateByPk($detail->id, ['qty' => $qtySebelum - $detailBaru->qty]) > 1) {
                throw new Exception('Gagal update detail', 500);
            }
        }
    }

    /**
     * Hapus barang di penjualan_multi_harga, penjualan_detail dan penjualan_diskon
     * @param ActiveRecord $barang
     */
    public function cleanBarang($barang)
    {
        $tabelPenjualanMultiHJ = PenjualanMultiHarga::model()->tableName();
        $tabelPenjualanDiskon  = PenjualanDiskon::model()->tableName();
        $tabelPenjualanDetail  = PenjualanDetail::model()->tableName();

        Yii::app()->db->createCommand("
                    DELETE {$tabelPenjualanMultiHJ}
                    FROM {$tabelPenjualanMultiHJ}
                    INNER JOIN {$tabelPenjualanDetail} ON {$tabelPenjualanMultiHJ}.penjualan_detail_id = {$tabelPenjualanDetail}.id
                    WHERE {$tabelPenjualanDetail}.barang_id=:barangId AND {$tabelPenjualanDetail}.penjualan_id=:penjualanId
                        ")
            ->bindValues([
                ':barangId'    => $barang->id,
                ':penjualanId' => $this->id,
            ])
            ->execute();

        Yii::app()->db->createCommand("
                    DELETE {$tabelPenjualanDiskon}
                    FROM {$tabelPenjualanDiskon}
                    INNER JOIN {$tabelPenjualanDetail} ON {$tabelPenjualanDiskon}.penjualan_detail_id = {$tabelPenjualanDetail}.id
                    WHERE {$tabelPenjualanDetail}.barang_id=:barangId AND {$tabelPenjualanDetail}.penjualan_id=:penjualanId
                        ")
            ->bindValues([
                ':barangId'    => $barang->id,
                ':penjualanId' => $this->id,
            ])
            ->execute();

        PenjualanDetail::model()->deleteAll('barang_id=:barangId AND penjualan_id=:penjualanId', [
            ':barangId'    => $barang->id,
            ':penjualanId' => $this->id,
        ]);
    }

    /**
     * Tambah barang method (without transaction)
     * Agar bisa digunakan method lain
     * @param ActiveRecord $barang
     * @param int $qty
     */
    public function tambahBarangProc($barang, $qty)
    {
        $barangAda = $this->barangAda($barang->id);
        if ($barangAda) {
            $qty += $barangAda;
            $this->cleanBarang($barang);
        }
        $this->tambahBarangDetail($barang, $qty);
    }

    /**
     * Tambah barang ke penjualan
     * @param string $barcode
     * @param int $qty
     * @param boolean $cekLimit default false
     * @return array
     * @throws Exception
     */
    public function tambahBarang($barcode, $qty, $cekLimit = false)
    {
        $transaction = $this->dbConnection->beginTransaction();
        try {
            $barang = Barang::model()->find('barcode=:barcode', [':barcode' => $barcode]);

            /* Jika barang tidak ada */
            if (is_null($barang)) {
                throw new Exception('Barang tidak ditemukan', 500);
            }
            if ($barang->status == Barang::STATUS_TIDAK_AKTIF && $barang->stok <= 0) {
                throw new Exception('Barang Tidak Aktif dan Tidak Ada Stok', 500);
            }
            $this->tambahBarangProc($barang, $qty);

            if ($cekLimit && $this->lewatLimit()) {
                throw new Exception('Gagal!! Melebihi limit penjualan!', 500);
            }

            $this->cekDiskonNominalSetelahScan();

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

    /**
     * Tambah barang ke tabel detail, sekaligus cek/apply diskon
     * @param ActiveRecord $barang Object Barang
     * @param int $qty Qty barang total yang akan ditambah
     */
    public function tambahBarangDetail($barang, $qty)
    {
        $sisa = $qty;
        /*
         * Jika ini barang non aktif, maka cek stok realtime
         * Barang non aktif tidak boleh minus
         */
        // Yii::log("Barang: {$barang->barcode}, qty: {$qty}, stok: {$barang->stok}");
        if ($barang->status == Barang::STATUS_TIDAK_AKTIF && (int) $qty > (int) $barang->stok) {
            throw new Exception('Stok barang non aktif tidak cukup. Stok: ' . $barang->stok, 500);
        }

        $hargaJualNormal = HargaJual::model()->terkini($barang->id);
        // Yii::log("Tambah barang detail; barangID: ".$barang->id, "info");
        /*
         * Cek Multi Harga Jual, jika ada: Diskon diabaikan!
         * Cek Diskon, dengan prioritas seperti di bawah ini
         * Hanya bisa salah satu
         */
        if (!empty(HargaJualMulti::listAktif($barang->id))) {
            // Yii::log("Diskon Multi HJ; barangID: ".$barang->id, "info");
            //terapkan multi harga jual
            //ambil sisanya (yang tidak kena kelipatan satuan multi harga)
            $sisa = $this->aksiMultiHJ($barang->id, $qty, $hargaJualNormal);
        } elseif (!is_null($this->cekDiskon($barang->id, DiskonBarang::TIPE_PROMO_MEMBER))) {
            //terapkan diskon promo member jika member
            //ambil sisanya (yang tidak didiskon)
            $customer = Profil::model()->findByPk($this->profil_id);
            if ($customer->isMember()) {
                $sisa = $this->aksiDiskonPromoMember($barang->id, $qty, $hargaJualNormal);
            }
        } elseif (!is_null($this->cekDiskon($barang->id, DiskonBarang::TIPE_PROMO))) {
            //terapkan diskon promo
            //ambil sisanya (yang tidak didiskon)
            $sisa = $this->aksiDiskonPromo($barang->id, $qty, $hargaJualNormal);
        } elseif (!is_null($this->cekDiskon($barang->id, DiskonBarang::TIPE_PROMO_PERKATEGORI))) {
            //terapkan diskon promo
            //ambil sisanya (yang tidak didiskon)
            $sisa = $this->aksiDiskonPromoPerKategori($barang->id, $qty, $hargaJualNormal);
        } elseif (!is_null($this->cekDiskon($barang->id, DiskonBarang::TIPE_PROMO_PERSTRUKTUR))) {
            //terapkan diskon promo per struktur
            //ambil sisanya (yang tidak didiskon)
            $sisa = $this->aksiDiskonPromoPerStruktur($barang->id, $qty, $hargaJualNormal);
        } elseif (!is_null($this->cekDiskon($barang->id, DiskonBarang::TIPE_GROSIR))) {
            //terapkan diskon grosir
            //ambil sisanya (yang tidak didiskon)
            $sisa = $this->aksiDiskonGrosir($barang->id, $qty, $hargaJualNormal);
        } elseif (!is_null($this->cekDiskon($barang->id, DiskonBarang::TIPE_BANDED))) {
            //terapkan diskon banded
            //ambil sisanya (yang tidak didiskon)
            $sisa = $this->aksiDiskonBanded($barang->id, $qty, $hargaJualNormal);
        } elseif (!is_null($this->cekDiskon($barang->id, DiskonBarang::TIPE_QTY_GET_BARANG))) {
            // Yii::log("Terapkan diskon beli x dapat y; barangID: ".$barang->id, "info");
            //ambil sisanya (yang tidak didiskon)
            $sisa = $this->aksiDiskonQtyDapatBarang($barang->id, $qty, $hargaJualNormal);
        } elseif (!is_null($this->cekDiskon($barang->id, DiskonBarang::TIPE_NOMINAL_GET_BARANG))) {
            $sisa = $this->aksiDiskonNominalGetBarang($barang->id, $qty, $hargaJualNormal);
        }

        /* Jika masih ada sisa, insert ke penjulan dg harga jual normal */
        if ($sisa > 0) {
            // Yii::log("Masih ada sisa; barangID: ".$barang->id, "info");
            /* -------------- */
            $this->insertBarang($barang->id, $sisa, $hargaJualNormal);
            /* -------------- */
        }

        /* Cek Diskon Nominal dapat Barang */
    }

    public function aksiDiskonPromo($barangId, $qty, $hargaJualNormal)
    {
        $diskonPromo = DiskonBarang::model()->find([
            'condition' => 'barang_id=:barangId and status=:status and tipe_diskon_id=:tipeDiskon and dari <= now() and (sampai >= now() or sampai is null)',
            'order'     => 'id desc',
            'params'    => [
                'barangId'   => $barangId,
                'status'     => DiskonBarang::STATUS_AKTIF,
                'tipeDiskon' => DiskonBarang::TIPE_PROMO,
            ],
        ]);
        $sisa = $qty;
        if ($qty > $diskonPromo->qty_max) {
            $qtyPromo = $diskonPromo->qty_max;
            $sisa -= $diskonPromo->qty_max;
        } else {
            $qtyPromo = $qty;
            $sisa     = 0;
        }
        $hargaJualSatuan = $hargaJualNormal - $diskonPromo->nominal;
        $this->insertBarang($barangId, $qtyPromo, $hargaJualSatuan, $diskonPromo->nominal, DiskonBarang::TIPE_PROMO);
        return $sisa;
    }

    public function aksiDiskonPromoPerKategori($barangId, $qty, $hargaJualNormal)
    {
        $barang              = Barang::model()->findByPk($barangId);
        $waktu               = date('Y-m-d H:i:s');
        $diskonPromoKategori = DiskonBarang::model()->find([
            'condition' => 'barang_kategori_id=:kategoriBarangId and status=:status and tipe_diskon_id=:tipeDiskon and dari <= :waktu and (sampai >= :waktu or sampai is null)',
            'order'     => 'id desc',
            'params'    => [
                'kategoriBarangId' => $barang->kategori_id,
                'status'           => DiskonBarang::STATUS_AKTIF,
                'tipeDiskon'       => DiskonBarang::TIPE_PROMO_PERKATEGORI,
                'waktu'            => $waktu,
            ],
        ]);

        $sisa = $qty;
        if ($qty > $diskonPromoKategori->qty_max) {
            $qtyPromo = $diskonPromoKategori->qty_max;
            $sisa -= $diskonPromoKategori->qty_max;
        } else {
            $qtyPromo = $qty;
            $sisa     = 0;
        }

        $diskonNominal = $diskonPromoKategori->nominal;
        if ($diskonPromoKategori->persen > 0) {
            $diskonNominal = round(((float) $diskonPromoKategori->persen / 100) * $hargaJualNormal);
        }
        $hargaJualSatuan = $hargaJualNormal - $diskonNominal;

        $this->insertBarang($barangId, $qtyPromo, $hargaJualSatuan, $diskonNominal, DiskonBarang::TIPE_PROMO_PERKATEGORI);
        return $sisa;
    }

    public function aksiDiskonPromoPerStruktur($barangId, $qty, $hargaJualNormal)
    {
        $barang              = Barang::model()->findByPk($barangId);
        $waktu               = date('Y-m-d H:i:s');
        $diskonPromoStruktur = DiskonBarang::model()->find([
            'condition' => 'barang_struktur_id=:strukturBarangId and status=:status and tipe_diskon_id=:tipeDiskon and dari <= :waktu and (sampai >= :waktu or sampai is null)',
            'order'     => 'id desc',
            'params'    => [
                'strukturBarangId' => $barang->struktur_id,
                'status'           => DiskonBarang::STATUS_AKTIF,
                'tipeDiskon'       => DiskonBarang::TIPE_PROMO_PERSTRUKTUR,
                'waktu'            => $waktu,
            ],
        ]);

        $sisa = $qty;
        if ($qty > $diskonPromoStruktur->qty_max) {
            $qtyPromo = $diskonPromoStruktur->qty_max;
            $sisa -= $diskonPromoStruktur->qty_max;
        } else {
            $qtyPromo = $qty;
            $sisa     = 0;
        }

        $diskonNominal = $diskonPromoStruktur->nominal;
        if ($diskonPromoStruktur->persen > 0) {
            $diskonNominal = round(((float) $diskonPromoStruktur->persen / 100) * $hargaJualNormal);
        }
        $hargaJualSatuan = $hargaJualNormal - $diskonNominal;

        $this->insertBarang($barangId, $qtyPromo, $hargaJualSatuan, $diskonNominal, DiskonBarang::TIPE_PROMO_PERSTRUKTUR);
        return $sisa;
    }

    public function aksiDiskonPromoMember($barangId, $qty, $hargaJualNormal)
    {
        $diskonPromo = DiskonBarang::model()->find([
            'condition' => '(barang_id=:barangId or semua_barang=:semuaBarang) and status=:status and tipe_diskon_id=:tipeDiskon and dari <= now() and (sampai >= now() or sampai is null)',
            'order'     => 'id desc',
            'params'    => [
                'barangId'    => $barangId,
                'semuaBarang' => DiskonBarang::SEMUA_BARANG,
                'status'      => DiskonBarang::STATUS_AKTIF,
                'tipeDiskon'  => DiskonBarang::TIPE_PROMO_MEMBER,
            ],
        ]);
        $sisa = $qty;
        if ($qty > $diskonPromo->qty_max) {
            $qtyPromo = $diskonPromo->qty_max;
            $sisa -= $diskonPromo->qty_max;
        } else {
            $qtyPromo = $qty;
            $sisa     = 0;
        }
        $diskonNominal   = ($diskonPromo->nominal > 0) ? $diskonPromo->nominal : $hargaJualNormal * ((float) $diskonPromo->persen / 100);
        $hargaJualSatuan = $hargaJualNormal - $diskonNominal;
        $this->insertBarang($barangId, $qtyPromo, $hargaJualSatuan, $diskonNominal, DiskonBarang::TIPE_PROMO_MEMBER);
        return $sisa;
    }

    public function aksiDiskonGrosir($barangId, $qty, $hargaJualNormal)
    {
        $diskonGrosir = DiskonBarang::model()->find([
            'condition' => 'barang_id=:barangId and status=:status and tipe_diskon_id=:tipeDiskon and dari <= now() and (sampai >= now() or sampai is null)',
            'order'     => 'id desc',
            'params'    => [
                'barangId'   => $barangId,
                'status'     => DiskonBarang::STATUS_AKTIF,
                'tipeDiskon' => DiskonBarang::TIPE_GROSIR,
            ],
        ]);

        if ($qty >= $diskonGrosir->qty_min) {
            $diskonNominal   = $diskonGrosir->nominal;
            $hargaJualSatuan = $hargaJualNormal - $diskonNominal;
            $this->insertBarang($barangId, $qty, $hargaJualSatuan, $diskonNominal, DiskonBarang::TIPE_GROSIR);
            return 0;
        }
        return $qty;
    }

    public function aksiDiskonBanded($barangId, $qty, $hargaJualNormal)
    {
        $diskons = DiskonBarang::model()->findAll([
            'condition' => 'barang_id=:barangId and status=:status and tipe_diskon_id=:tipeDiskon and dari <= now() and (sampai >= now() or sampai is null)',
            'order'     => 'qty desc',
            'params'    => [
                'barangId'   => $barangId,
                'status'     => DiskonBarang::STATUS_AKTIF,
                'tipeDiskon' => DiskonBarang::TIPE_BANDED,
            ],
        ]);
        $sisa = $qty;
        foreach ($diskons as $banded) {
            if ($sisa >= $banded->qty) {
                $hargaJualSatuan = $hargaJualNormal - $banded->nominal;
                $qtyBanded       = floor($sisa / $banded->qty);
                $qtyTotal        = $qtyBanded * $banded->qty;
                /* -------------- */
                $this->insertBarang($barangId, $qtyTotal, $hargaJualSatuan, $banded->nominal, DiskonBarang::TIPE_BANDED);
                /* -------------- */
                $sisa = $sisa % $banded->qty;
            }
        }
        return $sisa;
    }

    public function aksiDiskonQtyDapatBarang($barangId, $qty, $hargaJualNormal)
    {
        $diskonModel = DiskonBarang::model()->find([
            'condition' => '(barang_id=:barangId or barang_bonus_id=:barangId) and status=:status and tipe_diskon_id=:tipeDiskon and dari <= now() and (sampai >= now() or sampai is null)',
            'order'     => 'id desc',
            'params'    => [
                'barangId'   => $barangId,
                'status'     => DiskonBarang::STATUS_AKTIF,
                'tipeDiskon' => DiskonBarang::TIPE_QTY_GET_BARANG,
            ],
        ]);
        $sisa = $qty;
        if ($diskonModel->barang_id == $diskonModel->barang_bonus_id) {
            $min = $diskonModel->qty + $diskonModel->barang_bonus_qty; // qty asli + bonus minimum
            $max = ($diskonModel->qty_max / $diskonModel->qty * $diskonModel->barang_bonus_qty) + $diskonModel->qty_max; // qty asli + bonus maksimum

            if ($qty >= $min) {
                /* Jika lebih besar dari $max, ambil sisanya */
                $sisaMax = 0;
                if ($qty > $max) {
                    $sisaMax = $qty - $max;
                    $qtyBagi = $max;
                } else {
                    $qtyBagi = $qty;
                }
                $qtyDiskon = $this->div0($qtyBagi, $min) * $diskonModel->barang_bonus_qty;
                $sisa      = $qtyBagi - $qtyDiskon + $sisaMax;
                $this->insertBarang($barangId, $qtyDiskon, 0, $hargaJualNormal, DiskonBarang::TIPE_QTY_GET_BARANG);
            }
        } else {
            $sisa = $this->aksiDiskonQtyDapatBarangBeda($barangId, $qty, $hargaJualNormal, $diskonModel);
        }
        return $sisa;
    }

    public function aksiDiskonQtyDapatBarangBeda($barangId, $qty, $hargaJualNormal, $diskonModel)
    {
        $sisa = $qty;
        if ($barangId == $diskonModel->barang_id) {
            /* Barang bonus sudah discan (yang discan adalah barang utama/penyebab bonus) */

            $detail = PenjualanDetail::model()->find('penjualan_id = :penjualanId and barang_id = :barangId', [
                'penjualanId' => $this->id,
                'barangId'    => $diskonModel->barang_bonus_id,
            ]);
            if ($qty >= $diskonModel->qty && !is_null($detail)) {
                // insert barang secara normal
                $this->insertBarang($barangId, $qty, $hargaJualNormal);
                $sisa = 0;

                /* Reinsert barang bonus, untuk men-trigger cek diskon */
                $barang = Barang::model()->findByPk($diskonModel->barang_bonus_id);
                $this->tambahBarangProc($barang, 0);
            }
        } elseif ($barangId == $diskonModel->barang_bonus_id) {
            /* Barang bonus discan belakangan (yang discan adalah barang bonus) */
            $detail = PenjualanDetail::model()->find('penjualan_id = :penjualanId and barang_id = :barangId', [
                'penjualanId' => $this->id,
                'barangId'    => $diskonModel->barang_id,
            ]);
            if (!is_null($detail)) {
                $qtyDiskon = 0;
                if ($detail->qty >= $diskonModel->qty) {
                    if ($detail->qty <= $diskonModel->qty_max) {
                        $kelipatanDiskon = $this->div0($detail->qty, $diskonModel->qty);
                    } else {
                        $kelipatanDiskon = $this->div0($diskonModel->qty_max, $diskonModel->qty);
                    }
                    $qtyKenaDiskon = $diskonModel->barang_bonus_qty * $kelipatanDiskon;
                    $qtyDiskon     = $qty < $qtyKenaDiskon ? $qty : $qtyKenaDiskon;
                    $sisa          = $qty - $qtyDiskon;
                    $this->insertBarang($barangId, $qtyDiskon, 0, $hargaJualNormal, DiskonBarang::TIPE_QTY_GET_BARANG);
                }
            }
        }
        return $sisa;
    }

    /* intdiv() di php 7 ?
     * Untuk di php 5.6
     */

    public function div0($a, $b)
    {
        return ($a - $a % $b) / $b;
    }

    public function aksiDiskonNominalGetBarang($barangId, $qty, $hargaJualNormal)
    {
        $diskon = DiskonBarang::model()->find([
            'condition' => 'barang_bonus_id=:barangId and status=:status and tipe_diskon_id=:tipeDiskon and dari <= now() and (sampai >= now() or sampai is null)',
            'order'     => 'id desc',
            'params'    => [
                'barangId'   => $barangId,
                'status'     => DiskonBarang::STATUS_AKTIF,
                'tipeDiskon' => DiskonBarang::TIPE_NOMINAL_GET_BARANG,
            ],
        ]);
        $sisa     = $qty;
        $total    = $this->ambilTotal();
        $qtyBonus = $diskon->barang_bonus_qty > $qty ? $qty : $diskon->barang_bonus_qty;
        //echo $total . ' ' . ($hargaJualNormal * ($qty - $qtyBonus)) . ' ' . ($hargaJualNormal * $qtyBonus) . ' ' . $qtyBonus . ' ' . $total . ' ' . $qty;

        $i          = 1;
        $dapatBonus = false;
        while ($i <= $qtyBonus && ($total + ($hargaJualNormal * ($qty - $i)) >= $diskon->nominal)) {
            $i++;
            $dapatBonus = true;
        }

        if ($dapatBonus) {
            //Dapat bonus barang ini
            $diskonNominal = !is_null($diskon->barang_bonus_diskon_nominal) ? $diskon->barang_bonus_diskon_nominal : $hargaJualNormal;
            $hargaNet      = !is_null($diskon->barang_bonus_diskon_nominal) ? $hargaJualNormal - $diskonNominal : 0;
            $this->insertBarang($barangId, $i - 1, $hargaNet, $diskonNominal, DiskonBarang::TIPE_NOMINAL_GET_BARANG);
            $sisa = $qty - ($i - 1);
        }

        return $sisa;
    }

    /**
     * Insert Penjualan Detail
     * @param int $barangId
     * @param int $qty
     * @param decimal $hargaJual
     * @param decimal $diskon
     * @param int $tipeDiskonId
     * @throws Exception
     */
    public function insertBarang($barangId, $qty, $hargaJual, $diskon = 0, $tipeDiskonId = null, $multiHJ = [])
    {
        $detail                         = new PenjualanDetail;
        $detail->penjualan_id           = $this->id;
        $detail->barang_id              = $barangId;
        $detail->qty                    = $qty;
        $detail->harga_jual             = $hargaJual;
        $detail->harga_jual_rekomendasi = HargaJualRekomendasi::model()->terkini($barangId);
        if ($diskon > 0) {
            $detail->diskon = $diskon;
        }
        if (!$detail->save()) {
            throw new Exception("Gagal simpan penjualan detail: penjualanId:{$this->id}, barangId:{$barangId}, qty:{$qty}", 500);
        }
        if ($diskon > 0) {
            $this->insertDiskon($detail, $tipeDiskonId);
        }
        if (!empty($multiHJ)) {
            $this->insertMultiHJ($detail, $multiHJ);
        }
    }

    public function insertDiskon($penjualanDetail, $tipeDiskonId)
    {
        $trxDiskon                      = new PenjualanDiskon;
        $trxDiskon->penjualan_detail_id = $penjualanDetail->id;
        $trxDiskon->penjualan_id        = $penjualanDetail->penjualan_id;
        $trxDiskon->harga               = $penjualanDetail->harga_jual;
        $trxDiskon->harga_normal        = $penjualanDetail->harga_jual + $penjualanDetail->diskon;
        $trxDiskon->tipe_diskon_id      = $tipeDiskonId;
        if (!$trxDiskon->save()) {
            throw new Exception("Gagal simpan diskon detail: penjualanDetailId:{$penjualanDetail->id}", 500);
        }
    }

    public function cekDiskon($barangId, $tipeDiskonId)
    {
        if ($tipeDiskonId == DiskonBarang::TIPE_NOMINAL_GET_BARANG) {
            return DiskonBarang::model()->find([
                'condition' => 'barang_bonus_id=:barangId and status=:status and tipe_diskon_id=:tipeDiskon and dari <= now() and (sampai >= now() or sampai is null)',
                'order'     => 'id desc',
                'params'    => [
                    'barangId'   => $barangId,
                    'status'     => DiskonBarang::STATUS_AKTIF,
                    'tipeDiskon' => $tipeDiskonId,
                ],
            ]);
        }

        if ($tipeDiskonId == DiskonBarang::TIPE_QTY_GET_BARANG) {
            return DiskonBarang::model()->find([
                'condition' => '(barang_id=:barangId or barang_bonus_id=:barangId) and status=:status and tipe_diskon_id=:tipeDiskon and dari <= now() and (sampai >= now() or sampai is null)',
                'order'     => 'id desc',
                'params'    => [
                    'barangId'   => $barangId,
                    'status'     => DiskonBarang::STATUS_AKTIF,
                    'tipeDiskon' => $tipeDiskonId,
                ],
            ]);
        }

        if ($tipeDiskonId == DiskonBarang::TIPE_PROMO_PERKATEGORI) {
            $barang = Barang::model()->findByPk($barangId);
            $waktu  = date('Y-m-d H:i:s');
            return DiskonBarang::model()->find([
                'condition' => 'barang_kategori_id=:kategoriBarangId and status=:status and tipe_diskon_id=:tipeDiskon and dari <= :waktu and (sampai >= :waktu or sampai is null)',
                'order'     => 'id desc',
                'params'    => [
                    'kategoriBarangId' => $barang->kategori_id,
                    'status'           => DiskonBarang::STATUS_AKTIF,
                    'tipeDiskon'       => DiskonBarang::TIPE_PROMO_PERKATEGORI,
                    'waktu'            => $waktu,
                ],
            ]);
        }

        /* Diskon lainnya */
        return DiskonBarang::model()->find([
            'condition' => '(barang_id=:barangId or semua_barang=:semuaBarang) and status=:status and tipe_diskon_id=:tipeDiskon and dari <= now() and (sampai >= now() or sampai is null)',
            'order'     => 'id desc',
            'params'    => [
                'barangId'    => $barangId,
                'semuaBarang' => DiskonBarang::SEMUA_BARANG,
                'status'      => DiskonBarang::STATUS_AKTIF,
                'tipeDiskon'  => $tipeDiskonId,
            ],
        ]);
    }

    public function cekDiskonNominalSetelahScan()
    {
        $diskon = DiskonBarang::model()->find([
            'condition' => 'status=:status and tipe_diskon_id=:tipeDiskon and dari <= now() and (sampai >= now() or sampai is null)',
            'order'     => 'id desc',
            'params'    => [
                'status'     => DiskonBarang::STATUS_AKTIF,
                'tipeDiskon' => DiskonBarang::TIPE_NOMINAL_GET_BARANG,
            ],
        ]);

        if (!is_null($diskon) && $this->ambilTotal() >= $diskon->nominal) {
            $detail = PenjualanDetail::model()->findAll('penjualan_id = :penjualanId and barang_id = :barangId', [
                'penjualanId' => $this->id,
                'barangId'    => $diskon->barang_bonus_id,
            ]);
            if (!empty($detail)) {
                // Periksa harga jual apakah sudah ada yang nol (0) ATAU sudah kena diskon nominal
                $ketemu = false;
                foreach ($detail as $row) {
                    if ($row->harga_jual == 0 || $row->diskon == $diskon->barang_bonus_diskon_nominal) {
                        $ketemu = true;
                    }
                }
                // Jika tidak ada yang nol (0) ATAU sudah kena diskon nominal
                if (!$ketemu) {
                    // Berarti $detail masih satu row (belum kena diskon)
                    /* Cari Sub Total yang kena diskon */
                    //print_r($detail);
                    $diskonNominal = empty($diskon->barang_bonus_diskon_nominal) ? $detail[0]->harga_jual : $diskon->barang_bonus_diskon_nominal;
                    $subTotal      = $detail[0]->qty <= $diskon->barang_bonus_qty ? $diskonNominal * $detail[0]->qty : $diskonNominal * $diskon->barang_bonus_qty;
                    if ($this->ambilTotal() - $subTotal >= $diskon->nominal) {
                        /* Berarti kena diskon
                         * Reinsert barang (pakai tambah barang)
                         */
                        $barang = Barang::model()->findByPk($diskon->barang_bonus_id);
                        $this->tambahBarangProc($barang, 0);
                    }
                }
            }
        }
    }

    /**
     * Mencari nomor untuk penomoran surat
     * @return int maksimum+1 atau 1 jika belum ada nomor untuk tahun ini
     */
    public function cariNomorTahunan()
    {
        $tahun = date('y');
        $data  = $this->find(
            [
                'select'    => 'max(substring(nomor,9)*1) as max',
                'condition' => "substring(nomor,5,2)='{$tahun}'",
            ]
        );

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
        $kodeDokumen    = KodeDokumen::PENJUALAN;
        $kodeTahunBulan = date('ym');
        $sequence       = substr('00000' . $this->cariNomorTahunan(), -6);
        return "{$kodeCabang}{$kodeDokumen}{$kodeTahunBulan}{$sequence}";
    }

    /**
     * Total Penjualan
     * @return int total dalam bentuk raw (belum terformat)
     */
    public function ambilTotal()
    {
        $detail = Yii::app()->db->createCommand()
            ->select('sum(harga_jual * qty) total')
            ->from(PenjualanDetail::model()->tableName())
            ->where('penjualan_id=:penjualanId', [':penjualanId' => $this->id])
            ->queryRow();
        return $detail['total'];
    }

    /**
     * Total penjualan
     * @return string Total dalam format 0.000
     */
    public function getTotal()
    {
        return number_format($this->ambilTotal(), 0, ',', '.');
    }

    public function ambilMargin()
    {
        $command = Yii::app()->db->createCommand();
        $command->select('sum(pd.harga_jual * hpp.qty)-sum(hpp.harga_beli * hpp.qty) margin');
        $command->from(PenjualanDetail::model()->tableName() . ' pd');
        $command->join(Penjualan::model()->tableName() . ' pj', 'pd.penjualan_id=pj.id and pj.id=' . $this->id);
        $command->join(HargaPokokPenjualan::model()->tableName() . ' hpp', 'pd.id=hpp.penjualan_detail_id');

        $penjualan = $command->queryRow();
        return $penjualan['margin'];
    }

    /**
     * Total Margin
     * @return text Total margin dalam format 0.000
     */
    public function getMargin()
    {
        return number_format($this->ambilMargin(), 0, ',', '.');
    }

    /**
     * Total Profit Margin
     * @return text Total margin dalam persen
     */
    public function getProfitMargin()
    {
        if ($this->ambilTotal() == 0) {
            return null;
        }
        return number_format($this->ambilMargin() / $this->ambilTotal() * 100, 2, ',', '.');
    }

    /**
     * Proses simpan penjualan.
     * Jika piutang, terbit nota debit (gudang)
     *
     * Simpan penjualan:
     * 1. Update status dari draft menjadi piutang.
     * 2. Update stock
     * 3. Catat harga beli dan harga jual
     * 4. Jika stok minus harga beli adalah harga beli terakhir
     * 5. Buat nota debit (piutang)
     *
     */
    public function simpanPenjualan()
    {
        if (!$this->save()) {
            throw new Exception('Gagal simpan penjualan', 500);
        }
        $details = PenjualanDetail::model()->findAll('penjualan_id=:penjualanId', [':penjualanId' => $this->id]);
        foreach ($details as $detail) {
            $inventoryTerpakai = InventoryBalance::model()->jual($detail->barang_id, $detail->qty);
            if (empty($inventoryTerpakai)) {
                throw new Exception('Gagal mengambil data inventory. Coba ulangi proses simpan!. Barang ID: ' . $detail->barang_id . '; Qty: ' . $detail->qty);
            }
            $count = 1;
            foreach ($inventoryTerpakai as $layer) {
                $hpp                      = new HargaPokokPenjualan;
                $hpp->penjualan_detail_id = $detail->id;
                $hpp->pembelian_detail_id = $layer['pembelianDetailId'];
                $hpp->qty                 = $layer['qtyTerpakai'];
                $hpp->harga_beli          = $layer['hargaBeli'];

                // Jika negatif simpan juga di harga_beli_temp
                // FIX ME, jika pembelian harga beli nya beda
                if (isset($layer['negatif']) && $layer['negatif']) {
                    $hpp->harga_beli_temp = $layer['hargaBeli'];
                }
                if (!$hpp->save()) {
                    Yii::log('Gagal simpan HPP: ' . var_export($hpp->getErrors(), true), 'info');
                    throw new Exception('Gagal simpan HPP', 500);
                }
                /* Tambahan untuk transfer mode,
                 * cek apakah harga jual masih sama dengan inventory
                 * jika beda, maka tambahkan juga detail penjualannya
                 * ctt: transfer mode, harga jual = harga beli, jadi
                 * hpp = penjualan_detail
                 */
                if ($this->transfer_mode && $count > 1) {
                    $this->tambahDetailTransferBarang($detail, HargaPokokPenjualan::model()->findByPk($hpp->id));
                }
                $count++;
            }
        }

        $jumlahPenjualan = $this->ambilTotal();
        // Buat Hutang Piutang
        $piutang                     = new HutangPiutang;
        $piutang->profil_id          = $this->profil_id;
        $piutang->jumlah             = $jumlahPenjualan;
        $piutang->tipe               = HutangPiutang::TIPE_PIUTANG;
        $piutang->asal               = HutangPiutang::DARI_PENJUALAN;
        $piutang->nomor_dokumen_asal = $this->nomor;
        if (!$piutang->save()) {
            throw new Exception('Gagal simpan piutang', 500);
        }

        /*
         * Piutang Detail
         */
        $piutangDetail                    = new HutangPiutangDetail;
        $piutangDetail->hutang_piutang_id = $piutang->id;
        $piutangDetail->keterangan        = 'Penjualan: ' . $this->nomor;
        $piutangDetail->jumlah            = $jumlahPenjualan;
        if (!$piutangDetail->save()) {
            throw new Exception('Gagal simpan piutang detail', 500);
        }

        /*
         * Simpan hutang_piutang_id ke penjualan
         */
        if (!Penjualan::model()->updateByPk($this->id, ['hutang_piutang_id' => $piutang->id]) > 1) {
            throw new Exception('Gagal simpan piutang_id', 500);
        }

        /* Simpan poin jika ada */
        if ($this->getCurPoin() > 0) {
            $penjualanMember               = new PenjualanMember;
            $penjualanMember->penjualan_id = $this->id;
            $penjualanMember->profil_id    = $this->profil_id;
            $penjualanMember->poin         = $this->getCurPoin();
            if (!$penjualanMember->save()) {
                throw new Exception('Gagal simpan poin ke penjualan', 500);
            }
        }
    }

    public function simpan()
    {
        $transaction    = $this->dbConnection->beginTransaction();
        $this->scenario = 'simpanPenjualan';
        try {
            $this->simpanPenjualan();
            $transaction->commit();
            return [
                'sukses' => true,
            ];
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

    public function listStatus()
    {
        return [
            Penjualan::STATUS_DRAFT   => 'Draft',
            Penjualan::STATUS_PIUTANG => 'Piutang',
            Penjualan::STATUS_LUNAS   => 'Lunas',
        ];
    }

    public function getNamaStatus()
    {
        $status = $this->listStatus();
        return $status[$this->status];
    }

    public function afterFind()
    {
        $this->tanggal = !is_null($this->tanggal) ? date_format(date_create_from_format('Y-m-d H:i:s', $this->tanggal), 'd-m-Y H:i:s') : '0';
        return parent::afterFind();
    }

    /**
     * Export data penjualan ke CSV, yang terdiri dari (key value):
     * 1. barcode barcode,
     * 2. idBarang barang_id,
     * 3. namaBarang nama_barang,
     * 4. jumBarang qty,
     * 5. hargaBeli harga_beli, (kosong)
     * 6. hargaJual harga_jual,
     * 7. RRP rrp,
     * 8. SatuanBarang satuan,
     * 9. KategoriBarang kategori,
     * 10. Supplier toko_ini,
     * 11. kasir updated_by,
     * @return string Text dalam bentuk csv
     */
    public function eksporCsv()
    {
        /*
         * CSV Header, dari ahad POS 2
         */
        // $csv = '"barcode","idBarang","namaBarang","jumBarang","hargaBeli","hargaJual","RRP","SatuanBarang","KategoriBarang","Supplier","kasir"';
        // tambahan struktur barang: struktur_lv1, struktur_lv2, struktur_lv3

        /* Cari nama toko ini */
        $config = Config::model()->find("nama='toko.nama'");

        /*
         * Ambil data penjualan detail, untuk diexport ke csv
         */
        $sql = "
        SELECT
            barang.barcode,
            pd.barang_id idBarang,
            barang.nama namaBarang,
            pd.qty jumBarang,
            '' hargaBeli,
            pd.harga_jual hargaJual,
            pd.harga_jual_rekomendasi RRP,
            sb.nama SatuanBarang,
            kb.nama KategoriBarang,
            '{$config->nilai}' Supplier,
            user.nama kasir,
            lv1.nama struktur_lv1,
            lv2.nama struktur_lv2,
            lv3.nama struktur_lv3
        FROM
            penjualan_detail pd
                JOIN
            barang ON barang.id = pd.barang_id
                JOIN
            barang_satuan sb ON sb.id = barang.satuan_id
                LEFT JOIN
            barang_kategori kb ON kb.id = barang.kategori_id
                JOIN
            user ON user.id = pd.updated_by
                LEFT JOIN
            barang_struktur lv3 ON lv3.id = barang.struktur_id
                LEFT JOIN
            barang_struktur lv2 ON lv2.id = lv3.parent_id
                LEFT JOIN
            barang_struktur lv1 ON lv1.id = lv2.parent_id
        WHERE
            penjualan_id = {$this->id}
        ORDER BY pd.id";

        $details = Yii::app()->db->createCommand($sql)->queryAll();
        /* Kalau perlu harga beli, tambahkan ini ke sql
         * (
        select case
        when harga_pokok_penjualan.harga_beli is null then harga_pokok_penjualan.harga_beli_temp else harga_pokok_penjualan.harga_beli
        end
        from harga_pokok_penjualan
        where harga_pokok_penjualan.penjualan_detail_id = pd.id
        limit 1
        ) as harga_beli,
         */
        return $this->array2csv($details);
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

    public function invoiceText($draft = false, $cpi = 10)
    {
        $lebarKertas = 8; //inchi
        $jumlahKolom = $cpi * $lebarKertas;
        $rowPerPage  = 59;
        $rowCount    = 0;
        $halaman     = 0;

        $configs = Config::model()->findAll();
        /*
         * Ubah config (object) jadi array
         */
        $branchConfig = [];
        foreach ($configs as $config) {
            $branchConfig[$config->nama] = $config->nilai;
        }

        $penjualanDetail = Yii::app()->db->createCommand('
         select barang.barcode, barang.nama, pd.qty, pd.harga_jual, pd.harga_jual_rekomendasi
         from penjualan_detail pd
         join barang on pd.barang_id = barang.id
         where pd.penjualan_id = :penjualanId
              ')
            ->bindValue(':penjualanId', $this->id)
            ->queryAll();

        $struk = '';

        $strNomor = 'Nomor       : ' . $this->nomor;
        if ($draft) {
            $strNomor = 'Nomor       : DRAFT';
        }
        $strTgl    = 'Tanggal     : ' . $this->toIndoDate($this->tanggal);
        $strTglDue = 'Jatuh Tempo : ' . $this->toIndoDate(date('Y-m-d', strtotime("+{$branchConfig['penjualan.jatuh_tempo']} days", strtotime(date_format(date_create_from_format('d-m-Y H:i:s', $this->tanggal), 'Y-m-d')))));
        $strKasir  = 'Kasir       : ' . ucwords($this->updatedBy->nama);
        $strTotal  = 'Total       : ' . $this->getTotal();

        $kananMaxLength = strlen($strNomor) > strlen($strTgl) ? strlen($strNomor) : strlen($strTgl);
        $kananMaxLength = $kananMaxLength > strlen($strTglDue) ? $kananMaxLength : strlen($strTglDue);
        /* Jika Nama kasir terlalu panjang, akan di truncate */
        $strKasir = strlen($strKasir) > $kananMaxLength ? substr($strKasir, 0, $kananMaxLength - 2) . '..' : $strKasir;

        $strInvoice = 'INVOICE '; //Jumlah karakter harus genap!

        $struk = str_pad($branchConfig['toko.nama'], $jumlahKolom / 2 - strlen($strInvoice) / 2, ' ')
            . $strInvoice . str_pad(str_pad($strNomor, $kananMaxLength, ' '), $jumlahKolom / 2 - strlen($strInvoice) / 2, ' ', STR_PAD_LEFT)
            . PHP_EOL;
        $struk .= str_pad($branchConfig['toko.alamat1'], $jumlahKolom - $kananMaxLength, ' ')
            . str_pad($strTgl, $kananMaxLength, ' ')
            . PHP_EOL;
        $struk .= str_pad($branchConfig['toko.alamat2'], $jumlahKolom - $kananMaxLength, ' ')
            . str_pad($strTglDue, $kananMaxLength, ' ')
            . PHP_EOL;
        $struk .= str_pad($branchConfig['toko.alamat3'], $jumlahKolom - $kananMaxLength, ' ')
            . str_pad($strKasir, $kananMaxLength, ' ')
            . PHP_EOL;
        $struk .= str_pad($strTotal, $jumlahKolom - $kananMaxLength + strlen($strTotal), ' ', STR_PAD_LEFT)
            . PHP_EOL;
        //      $struk .= PHP_EOL;

        $struk .= 'Kepada: ' . $this->profil->nama . PHP_EOL;
        $struk .= '        ' . substr($this->profil->alamat1 . ' ' . $this->profil->alamat2 . ' ' . $this->profil->alamat3, 0, $jumlahKolom - 8) . PHP_EOL;

        $struk .= str_pad('', $jumlahKolom, '-') . PHP_EOL;
        $textHeader1 = ' Barang';
        $textHeader2 = 'RRP     Harga    Qty Sub Total ';
        $textHeader  = $textHeader1 . str_pad($textHeader2, $jumlahKolom - strlen($textHeader1), ' ', STR_PAD_LEFT) . PHP_EOL;
        $struk .= $textHeader;
        $struk .= str_pad('', $jumlahKolom, '-') . PHP_EOL;
        $rowCount = 11;

        $no = 1;
        foreach ($penjualanDetail as $detail) {
            $strBarcode              = str_pad(substr($detail['barcode'], 0, 13), 13, ' '); // Barcode hanya diambil 13 char pertama
            $strBarang               = str_pad(trim(substr($detail['nama'], 0, 28)), 28, ' '); //Nama Barang hanya diambil 28 char pertama
            $strQty                  = str_pad($detail['qty'], 5, ' ', STR_PAD_LEFT);
            $strHarga                = str_pad(number_format($detail['harga_jual'], 0, ',', '.'), 8, ' ', STR_PAD_LEFT);
            $strHargaJualRekomendasi = str_pad(number_format($detail['harga_jual_rekomendasi'], 0, ',', '.'), 8, ' ', STR_PAD_LEFT);
            $strSubTotal             = str_pad(number_format($detail['harga_jual'] * $detail['qty'], 0, ',', '.'), 8, ' ', STR_PAD_LEFT);
            $row1                    = ' ' . $strBarcode . ' ' . $strBarang . ' ';
            $row2                    = $strHargaJualRekomendasi . '  ' . $strHarga . '  ' . $strQty . '  ' . $strSubTotal;
            $row                     = $row1 . str_pad($row2 . ' ', $jumlahKolom - strlen($row1), ' ', STR_PAD_LEFT) . PHP_EOL;

            /* Jika ini seharusnya halaman baru */
            /*
            if ($rowCount > $rowPerPage) {
            $halaman++;
            $halamanStr = $this->nomor . ' ' . $halaman;

            $struk .= PHP_EOL;
            $struk .= str_pad($halamanStr, $jumlahKolom, ' ', STR_PAD_LEFT) . PHP_EOL . PHP_EOL;
            $rowCount = 1; // Reset row counter
            }
             */
            $struk .= $row;
            $no++;
            $rowCount++;
        }
        /* Jika ini seharusnya halaman baru */
        /*
        if ($rowCount > $rowPerPage && $halaman > 0) {
        $halaman++;
        $halamanStr = $this->nomor . ' ' . $halaman;

        $struk .= PHP_EOL;
        $struk .= str_pad($halamanStr, $jumlahKolom, ' ', STR_PAD_LEFT) . PHP_EOL . PHP_EOL;
        $rowCount = 1; // Reset row counter
        }
         */
        $struk .= str_pad('', $jumlahKolom, '-') . PHP_EOL . PHP_EOL;
        /*
        if ($rowCount > $rowPerPage - 6) {
        $halaman++;
        $halamanStr = $this->nomor . ' ' . $halaman;

        $struk .= PHP_EOL;
        $struk .= str_pad($halamanStr, $jumlahKolom, ' ', STR_PAD_LEFT) . PHP_EOL . PHP_EOL;
        $rowCount = 1; // Reset row counter
        }
         */
        if (!$draft) {
            $signatureHead1 = '          Diterima';
            $signatureHead2 = 'a.n. ' . $branchConfig['toko.nama'];
            $signatureHead3 = 'Driver';

            $struk .= $signatureHead1 . str_pad($signatureHead2, 23 - (strlen($signatureHead2) / 2) + strlen($signatureHead2), ' ', STR_PAD_LEFT) .
                str_pad($signatureHead3, 17 - (strlen($signatureHead3) / 2) + strlen($signatureHead3), ' ', STR_PAD_LEFT) . PHP_EOL;
            $struk .= PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
            $struk .= '     (                )         (                )         (                )' . PHP_EOL;
        }
        /*
        $rowCount+=7;
        for ($index = 0; $index < $rowPerPage - $rowCount; $index++) {
        $struk .= PHP_EOL;
        }
         */
        //$halaman++;
        //$halamanStr = $this->nomor . ' ' . $halaman;

        $struk .= PHP_EOL;
        //$struk .= str_pad($halamanStr, $jumlahKolom, ' ', STR_PAD_LEFT) . PHP_EOL . PHP_EOL;
        return $struk;
    }

    public function strukText()
    {
        $jumlahKolom = 40;

        $configs = Config::model()->findAll();
        /*
         * Ubah config (object) jadi array
         */
        $branchConfig = [];
        foreach ($configs as $config) {
            $branchConfig[$config->nama] = $config->nilai;
        }

        $user   = User::model()->findByPk($this->updated_by);
        $profil = Profil::model()->findByPk($this->profil_id);

        $details = Yii::app()->db->createCommand('
            select barang.barcode, barang.nama, satuan.nama namasatuan, pd.qty, pd.harga_jual, pd.diskon, pd.harga_jual_rekomendasi
            from penjualan_detail pd
            join barang on pd.barang_id = barang.id
            join barang_satuan satuan on satuan.id = barang.satuan_id
            where pd.penjualan_id = :penjualanId
            ')
            ->bindValue(':penjualanId', $this->id)
            ->queryAll();

        $penerimaan = Yii::app()->db->createCommand('
            select penerimaan.id, penerimaan.uang_dibayar
            from penerimaan
            join penerimaan_detail pd on pd.penerimaan_id = penerimaan.id
            join penjualan on penjualan.hutang_piutang_id = pd.hutang_piutang_id
            where penjualan.id = :penjualanId
            ')
            ->bindValue(':penjualanId', $this->id)
            ->queryRow();
        $penerimaanDetail = [];
        if (!empty($penerimaan)) {
            $penerimaanDetail = Yii::app()->db->createCommand('
            SELECT
                item_id, jumlah
            FROM
                penerimaan_detail
            WHERE
                penerimaan_id = :penerimaanId
            ')->bindValue(':penerimaanId', $penerimaan['id'])->queryAll();
        }

        $tarikTunaiModel = PenjualanTarikTunai::model()->find('penjualan_id=:penjualanId', [':penjualanId' => $this->id]);

        $struk = '';
        $struk .= str_pad($branchConfig['toko.nama'], $jumlahKolom, ' ', STR_PAD_BOTH) . PHP_EOL;
        $struk .= !empty($branchConfig['struk.header1']) ? str_pad($branchConfig['struk.header1'], $jumlahKolom, ' ', STR_PAD_BOTH) . PHP_EOL : '';
        $struk .= !empty($branchConfig['struk.header2']) ? str_pad($branchConfig['struk.header2'], $jumlahKolom, ' ', STR_PAD_BOTH) . PHP_EOL : '';
        $struk .= str_pad($this->nomor . ' ' . date_format(date_create_from_format('d-m-Y H:i:s', $this->tanggal), 'dmy H:i') . ' ' . substr($user->nama_lengkap, 0, 13), $jumlahKolom, ' ', STR_PAD_BOTH) . PHP_EOL;

        $struk .= str_pad('', $jumlahKolom, '-') . PHP_EOL;

        $total       = 0;
        $totalDiskon = 0;
        foreach ($details as $detail) {
            $txtHarga = $detail['qty'] . ' ' . $detail['namasatuan'] . '  @ ' . number_format($detail['harga_jual']) . ' : ';

            /* Jika ada diskon, maka tampilkan terlebih dahulu harga sebelum didiskon */
            if (!is_null($detail['diskon'])) {
                $txtHarga = $detail['qty'] . ' ' . $detail['namasatuan'] . '  @ ' . number_format($detail['harga_jual'] + $detail['diskon']) . ' : ';
            }
            $netSubTotal = $detail['qty'] * $detail['harga_jual'];
            $txtSubTotal = str_pad(number_format($netSubTotal, 0, ',', '.'), 11, ' ', STR_PAD_LEFT);

            /* Jika ada diskon, maka tampilkan sub total sebelum diskon */
            if (!is_null($detail['diskon'])) {
                $subTotal    = $detail['qty'] * ($detail['harga_jual'] + $detail['diskon']);
                $txtSubTotal = str_pad(number_format($subTotal, 0, ',', '.'), 11, ' ', STR_PAD_LEFT);
            }

            $struk .= str_pad(' ' . $detail['nama'], $jumlahKolom, ' ') . PHP_EOL;
            $struk .= str_pad($txtHarga . $txtSubTotal, $jumlahKolom - 1, ' ', STR_PAD_LEFT) . PHP_EOL;

            /* Jika ada diskon, maka tampilkan total diskon */
            if (!is_null($detail['diskon'])) {
                $diskonText        = rtrim(rtrim(number_format($detail['diskon'], 2, ',', '.'), '0'), ',');
                $subTotalDiskon    = $detail['qty'] * $detail['diskon'];
                $txtSubTotalDiskon = str_pad('(' . number_format($subTotalDiskon, 0, ',', '.') . ')', 12, ' ', STR_PAD_LEFT);
                $struk .= str_pad('(@ ' . $diskonText . ') : ' . $txtSubTotalDiskon, $jumlahKolom, ' ', STR_PAD_LEFT) . PHP_EOL;
                $totalDiskon += $subTotalDiskon;
            }

            $total += $netSubTotal;
        }

        $struk .= str_pad('', $jumlahKolom, '-') . PHP_EOL;

        $txtTotal = 'Total : ' . str_pad(number_format($total, 0, ',', '.'), 11, ' ', STR_PAD_LEFT);

        $diskonNota  = 0;
        $koinDipakai = 0;
        $infaq       = 0;
        if (!empty($penerimaanDetail)) {
            foreach ($penerimaanDetail as $strukItem) {
                switch ($strukItem['item_id']) {
                    case ItemKeuangan::POS_DISKON_PER_NOTA:
                        $diskonNota           = $strukItem['jumlah'];
                        $txtDiskonNotaNominal = str_pad(
                            '(' . number_format($strukItem['jumlah'], 0, ',', '.') . ')',
                            12,
                            ' ',
                            STR_PAD_LEFT
                        );
                        $txtDiskonNota = str_pad('Diskon : ' . $txtDiskonNotaNominal, $jumlahKolom, ' ', STR_PAD_LEFT);
                        break;
                    case ItemKeuangan::POS_KOINCASHBACK_DIPAKAI:
                        $koinDipakai           = $strukItem['jumlah'];
                        $txtkoinDipakaiNominal = str_pad(
                            '(' . number_format($strukItem['jumlah'], 0, ',', '.') . ')',
                            12,
                            ' ',
                            STR_PAD_LEFT
                        );
                        // $txtkoinDipakai = str_pad('Cashback dipakai : ' . $txtkoinDipakaiNominal, $jumlahKolom, ' ', STR_PAD_LEFT);
                        $txtkoinDipakai = str_pad('Koin : ' . $txtkoinDipakaiNominal, $jumlahKolom, ' ', STR_PAD_LEFT);
                        break;
                    case ItemKeuangan::POS_INFAQ:
                        $infaq    = $strukItem['jumlah'];
                        $txtInfaq = 'Infak : ' . str_pad(
                            number_format($strukItem['jumlah'], 0, ',', '.'),
                            11,
                            ' ',
                            STR_PAD_LEFT
                        );
                        break;
                }
            }
        }

        $tarikTunai = is_null($tarikTunaiModel) ? 0 : $tarikTunaiModel->jumlah;

        $dibayar = empty($penerimaan['uang_dibayar']) ? null : $penerimaan['uang_dibayar'];
        if (!is_null($dibayar)) {
            $txtBayar = 'Dibayar : ' . str_pad(number_format($dibayar, 0, ',', '.'), 11, ' ', STR_PAD_LEFT);
            $txtKbali = 'Kembali : ' . str_pad(number_format($dibayar - $total + $diskonNota + $koinDipakai - $infaq - $tarikTunai, 0, ',', '.'), 11, ' ', STR_PAD_LEFT);
        }

        $struk .= str_pad($txtTotal, $jumlahKolom - 1, ' ', STR_PAD_LEFT) . PHP_EOL;
        if (isset($txtDiskonNota)) {
            $struk .= str_pad($txtDiskonNota, $jumlahKolom - 1, ' ', STR_PAD_LEFT) . PHP_EOL;
        }
        if (isset($txtkoinDipakai)) {
            $struk .= str_pad($txtkoinDipakai, $jumlahKolom - 1, ' ', STR_PAD_LEFT) . PHP_EOL;
        }
        if (isset($txtInfaq)) {
            $struk .= str_pad($txtInfaq, $jumlahKolom - 1, ' ', STR_PAD_LEFT) . PHP_EOL;
        }

        if (!is_null($tarikTunaiModel)) {
            $txtTarikTunai = 'Tarik Tunai : ' . str_pad(number_format($tarikTunaiModel->jumlah, 0, ',', '.'), 11, ' ', STR_PAD_LEFT);
            $struk .= str_pad($txtTarikTunai, $jumlahKolom - 1, ' ', STR_PAD_LEFT) . PHP_EOL;
        }

        if (!is_null($dibayar)) {
            $struk .= str_pad($txtBayar, $jumlahKolom - 1, ' ', STR_PAD_LEFT) . PHP_EOL;
            $struk .= str_pad($txtKbali, $jumlahKolom - 1, ' ', STR_PAD_LEFT) . PHP_EOL;
        }

        if ($totalDiskon > 0) {
            $txtDiskon = 'Anda Hemat : ' . str_pad(number_format($totalDiskon + $diskonNota, 0, ',', '.'), 11, ' ', STR_PAD_LEFT);
            $struk .= PHP_EOL;
            $struk .= str_pad($txtDiskon, $jumlahKolom - 1, ' ', STR_PAD_LEFT) . PHP_EOL;
        }

        if ($this->getCurPoin() > 0) {
            $txtPoin = 'Poin        : ' . str_pad(number_format($this->getCurPoin(), 0, ',', '.'), 11, ' ', STR_PAD_LEFT);
            $struk .= str_pad($txtPoin, $jumlahKolom - 1, ' ', STR_PAD_LEFT) . PHP_EOL;
        }

        if ($profil->isMember()) {
            $struk .= str_pad('', $jumlahKolom, '-') . PHP_EOL;
            $nomorNama = $profil->nomor . ' ' . $profil->nama;
            $struk .= ' ' . substr($nomorNama, 0, 38) . PHP_EOL;
            $struk .= ' Total Poin: ' . $this->getTotalPoinPeriodeBerjalan() . PHP_EOL;
        }

        if ($profil->isMemberOL()) {
            $struk .= str_pad('', $jumlahKolom, '-') . PHP_EOL;
            $dataMemberOL = PenjualanMemberOnline::model()->find('penjualan_id=:penjualanId', [':penjualanId' => $this->id]);
            $nomorNama    = 'Member: ' . $dataMemberOL->nomor_member . ', Lvl: ' . $dataMemberOL->level_nama;
            $struk .= ' ' . substr($nomorNama, 0, 38) . PHP_EOL;
            $struk .= ' Poin dari belanja ini    : ' . $dataMemberOL->poin . PHP_EOL;
            $struk .= ' Total poin anda          : ' . $dataMemberOL->total_poin . PHP_EOL;
            $struk .= ' Koin dari belanja ini    : ' . $dataMemberOL->koin . PHP_EOL;
            $struk .= ' Total koin anda          : ' . $dataMemberOL->total_koin . PHP_EOL;
        }

        $struk .= str_pad('', $jumlahKolom, '-') . PHP_EOL;
        $struk .= !empty($branchConfig['struk.footer1']) ? str_pad($branchConfig['struk.footer1'], $jumlahKolom, ' ', STR_PAD_BOTH) . PHP_EOL : '';
        $struk .= !empty($branchConfig['struk.footer2']) ? str_pad($branchConfig['struk.footer2'], $jumlahKolom, ' ', STR_PAD_BOTH) . PHP_EOL : '';
        $struk .= PHP_EOL;

        return $struk;
    }

    public function notaText($cpi = 10)
    {
        $lebarKertas = 8; //inchi
        $jumlahKolom = $cpi * $lebarKertas;
        $rowPerPage  = 59;
        $rowCount    = 0;
        $halaman     = 0;

        $configs = Config::model()->findAll();
        /*
         * Ubah config (object) jadi array
         */
        $branchConfig = [];
        foreach ($configs as $config) {
            $branchConfig[$config->nama] = $config->nilai;
        }

        $penjualanDetail = Yii::app()->db->createCommand('
            select barang.barcode, barang.nama, satuan.nama namasatuan, pd.qty, pd.harga_jual, pd.diskon
            from penjualan_detail pd
            join barang on pd.barang_id = barang.id
            join barang_satuan satuan on satuan.id = barang.satuan_id
            where pd.penjualan_id = :penjualanId
            ')
            ->bindValue(':penjualanId', $this->id)
            ->queryAll();

        $penerimaan = Yii::app()->db->createCommand('
            select penerimaan.uang_dibayar
            from penerimaan
            join penerimaan_detail pd on pd.penerimaan_id = penerimaan.id
            join penjualan on penjualan.hutang_piutang_id = pd.hutang_piutang_id
            where penjualan.id = :penjualanId
            ')
            ->bindValue(':penjualanId', $this->id)
            ->queryRow();

        $struk = '';

        $strNomor = 'Nomor : ' . $this->nomor;
        $strWaktu = 'Waktu : ' . date_format(date_create_from_format('d-m-Y H:i:s', $this->tanggal), 'd-m-Y H:i');
        $strKasir = 'Kasir : ' . ucwords($this->updatedBy->nama);

        $kananMaxLength = strlen($strNomor) > strlen($strWaktu) ? strlen($strNomor) : strlen($strWaktu);

        /* Jika Nama kasir terlalu panjang, akan di truncate */
        $strKasir = strlen($strKasir) > $kananMaxLength ? substr($strKasir, 0, $kananMaxLength - 2) . '..' : $strKasir;

        $strInvoice = 'NOTA'; //Jumlah karakter harus genap!

        $struk = str_pad($branchConfig['toko.nama'], $jumlahKolom / 2 - strlen($strInvoice) / 2, ' ')
            . $strInvoice . str_pad(str_pad($strNomor, $kananMaxLength, ' '), $jumlahKolom / 2 - strlen($strInvoice) / 2, ' ', STR_PAD_LEFT)
            . PHP_EOL;
        $struk .= str_pad($branchConfig['struk.header1'], $jumlahKolom - $kananMaxLength, ' ')
            . str_pad($strKasir, $kananMaxLength, ' ')
            . PHP_EOL;
        $struk .= str_pad($branchConfig['struk.header2'], $jumlahKolom - $kananMaxLength, ' ')
            . str_pad($strWaktu, $kananMaxLength, ' ')
            . PHP_EOL;
        $struk .= PHP_EOL;

        $struk .= 'Kepada: ' . $this->profil->nama . PHP_EOL;
        $struk .= '        ' . substr($this->profil->alamat1 . ' ' . $this->profil->alamat2 . ' ' . $this->profil->alamat3, 0, $jumlahKolom - 8) . PHP_EOL;

        $struk .= str_pad('', $jumlahKolom, '-') . PHP_EOL;
        $textHeader1 = '  No  Barang';
        $textHeader2 = 'Qty     Harga  Diskon Harga Net Sub Total ';
        $textHeader  = $textHeader1 . str_pad($textHeader2, $jumlahKolom - strlen($textHeader1), ' ', STR_PAD_LEFT) . PHP_EOL;
        $struk .= $textHeader;
        $struk .= str_pad('', $jumlahKolom, '-') . PHP_EOL;
        $rowCount = 11;

        $no          = 1;
        $total       = 0;
        $totalDiskon = 0;
        foreach ($penjualanDetail as $detail) {
            $strNo       = substr('  ' . $no . '.', -4);
            $strBarang   = str_pad(trim(substr($detail['nama'], 0, 28)), 28, ' '); //Nama Barang hanya diambil 28 char pertama
            $strQty      = str_pad($detail['qty'], 4, ' ', STR_PAD_LEFT);
            $strHarga    = str_pad(number_format($detail['harga_jual'] + $detail['diskon'], 0, ',', '.'), 8, ' ', STR_PAD_LEFT);
            $strDiskon   = str_pad(number_format($detail['diskon'], 0, ',', '.'), 6, ' ', STR_PAD_LEFT);
            $strHargaNet = str_pad(number_format($detail['harga_jual'], 0, ',', '.'), 8, ' ', STR_PAD_LEFT);

            $subTotalDiskon = $detail['qty'] * $detail['diskon'];
            $totalDiskon += $subTotalDiskon;

            $netSubTotal = $detail['qty'] * $detail['harga_jual'];
            $strSubTotal = str_pad(number_format($netSubTotal, 0, ',', '.'), 8, ' ', STR_PAD_LEFT);
            $row1        = ' ' . $strNo . ' ' . $strBarang . ' ';
            $row2        = $strQty . '  ' . $strHarga . '  ' . $strDiskon . '  ' . $strHargaNet . '  ' . $strSubTotal;
            $row         = $row1 . str_pad($row2 . ' ', $jumlahKolom - strlen($row1), ' ', STR_PAD_LEFT) . PHP_EOL;

            /* Jika ini seharusnya halaman baru */
            if ($rowCount > $rowPerPage) {
                $halaman++;
                $halamanStr = $this->nomor . ' ' . $halaman;

                $struk .= PHP_EOL;
                $struk .= str_pad($halamanStr, $jumlahKolom, ' ', STR_PAD_LEFT) . PHP_EOL . PHP_EOL;
                $rowCount = 1; // Reset row counter
            }

            $total += $netSubTotal;
            $struk .= $row;
            $no++;
            $rowCount++;
        }
        /* Jika ini seharusnya halaman baru */
        if ($rowCount > $rowPerPage && $halaman > 0) {
            $halaman++;
            $halamanStr = $this->nomor . ' ' . $halaman;

            $struk .= PHP_EOL;
            $struk .= str_pad($halamanStr, $jumlahKolom, ' ', STR_PAD_LEFT) . PHP_EOL . PHP_EOL;
            $rowCount = 1; // Reset row counter
        }

        /* ======== footer ============ */
        if ($rowCount > $rowPerPage - 4) {
            $halaman++;
            $halamanStr = $this->nomor . ' ' . $halaman;

            $struk .= PHP_EOL;
            $struk .= str_pad($halamanStr, $jumlahKolom, ' ', STR_PAD_LEFT) . PHP_EOL . PHP_EOL;
            $rowCount = 1; // Reset row counter
        }

        $struk .= str_pad('', $jumlahKolom, '-') . PHP_EOL;

        $txtTotal = 'Total      : ' . str_pad(number_format($total, 0, ',', '.'), 11, ' ', STR_PAD_LEFT);

        $dibayar = empty($penerimaan['uang_dibayar']) ? null : $penerimaan['uang_dibayar'];
        if (is_null($dibayar)) {
            $txtBayar = 'Dibayar    : ' . str_pad('', 11, ' ', STR_PAD_LEFT);
            $txtKbali = 'Kembali    : ' . str_pad('', 11, ' ', STR_PAD_LEFT);
        } else {
            $txtBayar = 'Dibayar    : ' . str_pad(number_format($dibayar, 0, ',', '.'), 11, ' ', STR_PAD_LEFT);
            $txtKbali = 'Kembali    : ' . str_pad(number_format($dibayar - $total, 0, ',', '.'), 11, ' ', STR_PAD_LEFT);
        }
        $strukFooter1 = $branchConfig['struk.footer1'];
        $strukFooter2 = $branchConfig['struk.footer2'];

        $struk .= $strukFooter1;
        $struk .= str_pad($txtTotal, $jumlahKolom - strlen($strukFooter1) - 1, ' ', STR_PAD_LEFT) . PHP_EOL;
        $struk .= $strukFooter2;
        $struk .= str_pad($txtBayar, $jumlahKolom - strlen($strukFooter2) - 1, ' ', STR_PAD_LEFT) . PHP_EOL;
        $struk .= str_pad($txtKbali, $jumlahKolom - 1, ' ', STR_PAD_LEFT) . PHP_EOL;

        if ($totalDiskon > 0) {
            $txtDiskon = 'Anda Hemat : ' . str_pad(number_format($totalDiskon, 0, ',', '.'), 11, ' ', STR_PAD_LEFT);
            $struk .= str_pad($txtDiskon, $jumlahKolom - 1, ' ', STR_PAD_LEFT) . PHP_EOL;
        }

        $struk .= str_pad('', $jumlahKolom, '-') . PHP_EOL;
        /* ================= /footer ============= */

        if ($rowCount > $rowPerPage - 6) {
            $halaman++;
            $halamanStr = $this->nomor . ' ' . $halaman;

            $struk .= PHP_EOL;
            $struk .= str_pad($halamanStr, $jumlahKolom, ' ', STR_PAD_LEFT) . PHP_EOL . PHP_EOL;
            $rowCount = 1; // Reset row counter
        }
        //$struk .= 'Barang yang sudah dibeli tidak bisa ditukar atau dikembalikan' . PHP_EOL . PHP_EOL;
        $signatureHead1 = '        Hormat Kami';
        $signatureHead2 = 'Pelanggan';

        $struk .= $signatureHead1 . str_pad($signatureHead2, 28 - (strlen($signatureHead2) / 2) + strlen($signatureHead2), ' ', STR_PAD_LEFT) . PHP_EOL;
        $struk .= PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
        $struk .= '     (                )               (                )' . PHP_EOL;
        $rowCount += 7;
        for ($index = 0; $index < $rowPerPage - $rowCount; $index++) {
            $struk .= PHP_EOL;
        }
        $halaman++;
        $halamanStr = $this->nomor . ' ' . $halaman;

        $struk .= PHP_EOL;
        $struk .= str_pad($halamanStr, $jumlahKolom, ' ', STR_PAD_LEFT) . PHP_EOL . PHP_EOL;
        return $struk;
    }

    /**
     * Mengembalikan nilai poin, jika customer adalah member dan config
     * "member.nilai_1_poin" diisi nominal > 0
     * @return int Jumlah Poin
     */
    public function getCurPoin()
    {
        $profil = Profil::model()->findByPk($this->profil_id);
        if ($profil->isMember()) {
            $penjualan = Yii::app()->db->createCommand('
            select sum(harga_jual * qty) jumlah from penjualan_detail where penjualan_id=:penjualanId
                ')->bindValues([':penjualanId' => $this->id])
                ->queryRow();

            $configMember = Yii::app()->db->createCommand('
            select nilai from config where nama=:namaConfig
                ')->bindValues([':namaConfig' => 'member.nilai_1_poin'])
                ->queryRow();

            /* Jika di config nilai = 0, berarti tidak memakai sistem poin */
            return $configMember['nilai'] > 0 ? floor($penjualan['jumlah'] / $configMember['nilai']) : 0;
        } else {
            return 0;
        }
    }

    /**
     * Ambil total poin yang sudah didapat
     * @return int total Poin Periode Berjalan
     */
    public function getTotalPoinPeriodeBerjalan()
    {
        $profil = Profil::model()->findByPk($this->profil_id);
        if ($profil->isMember()) {
            $periodePoin = MemberPeriodePoin::model()->find('awal<=month(now()) and month(now())<=akhir');
            $poin        = false;
            if (!is_null($periodePoin)) {
                $poin = Yii::app()->db->createCommand()
                    ->select('sum(poin) total')
                    ->from(PenjualanMember::model()->tableName() . ' tpm')
                    ->where('YEAR(updated_at) = YEAR(NOW()) AND MONTH(updated_at) BETWEEN :awal AND :akhir
                                AND profil_id=:profilId')
                    ->bindValues([
                        //':tahun' => 'year(' . $this->tanggal . ')',
                        ':awal'     => $periodePoin->awal,
                        ':akhir'    => $periodePoin->akhir,
                        ':profilId' => $profil->id,
                    ])
                    ->queryRow();
            } else {
                /* Berarti periode lintas tahun (awal > akhir ) */
                $periodePoinL = MemberPeriodePoin::model()->find('(awal <= month(now()) OR month(now()) <= akhir) AND awal > akhir');
                if (!is_null($periodePoinL)) {
                    $queryPoin = Yii::app()->db->createCommand()
                        ->select('sum(poin) total')
                        ->from(PenjualanMember::model()->tableName() . ' tpm')
                        ->where('profil_id=:profilId');

                    $curMonth = date('n');

                    /* Jika sekarang berada diantara awal periode dengan desember */
                    if ($curMonth >= $periodePoinL->awal) {
                        $queryPoin->andWhere('YEAR(updated_at) = YEAR(NOW()) AND MONTH(updated_at) BETWEEN :awal AND MONTH(NOW())');
                        $queryPoin->bindValues([
                            ':awal' => $periodePoinL->awal,
                        ]);
                    }

                    /* Jika sekarang berada diantara januari dengan akhir periode */
                    if ($curMonth <= $periodePoinL->akhir) {
                        $queryPoin->andWhere('((YEAR(tpm.updated_at)=YEAR(NOW()) AND MONTH(tpm.updated_at) <= MONTH(NOW())) OR '
                            . '(YEAR(tpm.updated_at)=YEAR(NOW())-1 AND MONTH(tpm.updated_at) >= :awal))');
                        $queryPoin->bindValues([
                            ':awal' => $periodePoinL->awal,
                        ]);
                    }

                    $queryPoin->bindValues([
                        ':profilId' => $profil->id,
                    ]);
                    $poin = $queryPoin->queryRow();
                }
            }
            return $poin ? $poin['total'] : 0;
        } else {
            return 0;
        }
    }

    /**
     * Update Harga Jual secara manual, dan mencatat diskonnya
     * @param ActiveRecord $penjualanDetail
     * @param int $hargaManual harga yang diinput
     */
    public function updateHargaManual($penjualanDetail, $hargaManual)
    {
        $transaction = $this->dbConnection->beginTransaction();
        try {
            $barangId  = $penjualanDetail->barang_id;
            $qty       = $penjualanDetail->qty;
            $hargaJual = $hargaManual;
            $diskon    = $penjualanDetail->harga_jual - $hargaManual;

            $barang = Barang::model()->findByPk($barangId);
            $this->cleanBarang($barang);

            $this->insertBarang($barangId, $qty, $hargaJual, $diskon, DiskonBarang::TIPE_MANUAL);
            $transaction->commit();
            return [
                'sukses' => true,
            ];
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

    public function gantiCustomer($customer)
    {
        $transaction = $this->dbConnection->beginTransaction();

        try {
            if (!$this->saveAttributes(['profil_id' => $customer->id])) {
                throw new Exception('Gagal ubah customer', 500);
            }
            $alamat1 = !empty($customer->alamat1) ? $customer->alamat1 : '';
            $alamat2 = !empty($customer->alamat2) ? '<br>' . $customer->alamat2 : '';
            $alamat3 = !empty($customer->alamat3) ? '<br>' . $customer->alamat3 : '';
            /* Ambil data detail */
            $penjualanDetails = PenjualanDetail::model()->findAll('penjualan_id=:penjualanId', [
                'penjualanId' => $this->id,
            ]);

            /* Hapus dan re-insert */

            $tabelPenjualanDiskon     = PenjualanDiskon::model()->tableName();
            $tabelPenjualanMultiHarga = PenjualanMultiHarga::model()->tableName();
            $tabelPenjualanDetail     = PenjualanDetail::model()->tableName();
            Yii::app()->db->createCommand("
                    DELETE {$tabelPenjualanDiskon}
                    FROM {$tabelPenjualanDiskon}
                    INNER JOIN {$tabelPenjualanDetail} ON {$tabelPenjualanDiskon}.penjualan_detail_id = {$tabelPenjualanDetail}.id
                    WHERE {$tabelPenjualanDetail}.penjualan_id=:penjualanId
                        ")
                ->bindValues([
                    ':penjualanId' => $this->id,
                ])
                ->execute();
            Yii::app()->db->createCommand("
                    DELETE FROM {$tabelPenjualanMultiHarga}
                    WHERE penjualan_id=:penjualanId
                        ")
                ->bindValues([
                    ':penjualanId' => $this->id,
                ])
                ->execute();

            PenjualanDetail::model()->deleteAll('penjualan_id=:penjualanId', [
                'penjualanId' => $this->id,
            ]);

            foreach ($penjualanDetails as $detail) {
                $barang = Barang::model()->findByPk($detail->barang_id);
                $this->tambahBarangProc($barang, $detail->qty);
            }

            $transaction->commit();

            return [
                'sukses'  => true,
                'nama'    => $customer->nama,
                'nomor'   => $customer->nomor,
                'address' => $alamat1 . $alamat2 . $alamat3,
            ];
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

    /**
     * Cek total penjualan, apakah sudah lewat limit
     * @return boolean true jika lewat limit
     */
    public function lewatLimit()
    {
        $limitPenjualan = Config::model()->find("nama='penjualan.limit'");
        $limit          = $limitPenjualan->nilai;
        return $limit > 0 && $this->ambilTotal() > $limit;
    }

    public function aksiMultiHJ($barangId, $qty, $hargaJualNormal)
    {
        $listHarga = HargaJualMulti::listAktif($barangId, 'desc');
        $sisa      = $qty;
        foreach ($listHarga as $satuan) {
            if ($sisa >= $satuan['qty']) {
                $jmlSatuan = floor($sisa / $satuan['qty']); // Jumlah dari satuan (pak, lsn, karton, dst)
                $qtyTotal  = $jmlSatuan * $satuan['qty']; // Jumlah qty total dari KELIPATAN satuan
                /* -------------- */
                $satuan['harga_jual_normal'] = $hargaJualNormal;
                $this->insertBarang($barangId, $qtyTotal, $satuan['harga'], 0, null, $satuan);
                /* -------------- */
                $sisa = $sisa % $satuan['qty'];
            }
        }
        return $sisa;
    }

    public function insertMultiHJ($penjualanDetail, $params)
    {
        $penjualanMultiHJ                      = new PenjualanMultiHarga;
        $penjualanMultiHJ->penjualan_detail_id = $penjualanDetail->id;
        $penjualanMultiHJ->penjualan_id        = $penjualanDetail->penjualan_id;
        $penjualanMultiHJ->qty                 = $penjualanDetail->qty;
        $penjualanMultiHJ->harga               = $penjualanDetail->harga_jual;
        $penjualanMultiHJ->harga_normal        = $params['harga_jual_normal'];
        if (!$penjualanMultiHJ->save()) {
            throw new Exception('Gagal simpan penjualan multi harga untuk detail #' . $penjualanDetail->id, 500);
        }
    }

    /**
     * Ambil nilai poin Member Online untuk penjualan ini
     *
     * @param int $nilai1Poin
     * @param int $nilai1Koin
     * @return array [poin, koin]
     */
    public function getCurPoinMOL($nilai1Poin, $nilai1Koin)
    {
        $poin = floor($this->ambilTotal() / $nilai1Poin);
        $koin = floor($this->ambilTotal() / $nilai1Koin);
        return [
            'poin' => $poin,
            'koin' => $koin,
        ];
    }

    public function ambilDetailTanpaStruktur()
    {
        return PenjualanDetail::model()->with('barang')->findAll('penjualan_id=:penjualanId AND barang.struktur_id IS NULL', [':penjualanId' => $this->id]);
    }

    public function array2csv(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen('php://output', 'w');
        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();
    }

    public function getDetailArr()
    {
        $sql = "
        SELECT 
            barang.nama,
            qty,
            FORMAT(harga_jual + IFNULL(diskon, 0), 0, 'id_ID') harga_jual,
            FORMAT(IFNULL(diskon, 0), 0, 'id_ID') diskon,
            FORMAT(harga_jual, 0, 'id_ID') net,
            FORMAT(qty * harga_jual, 0, 'id_ID') AS stotal,
            FORMAT(IFNULL(diskon, 0) * qty, 0, 'id_ID') AS stotaldiskon
        FROM
            penjualan_detail d
                JOIN
            barang ON barang.id = d.barang_id
        WHERE
            penjualan_id = :id
        ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':id', $this->id);
        return $command->queryAll();
    }
}
