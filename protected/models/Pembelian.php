<?php

/**
 * This is the model class for table "pembelian".
 *
 * The followings are the available columns in table 'pembelian':
 * @property string $id
 * @property string $nomor
 * @property string $tanggal
 * @property string $profil_id
 * @property string $referensi
 * @property string $tanggal_referensi
 * @property string $hutang_piutang_id
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Profil $profil
 * @property HutangPiutang $hutangPiutang
 * @property User $updatedBy
 * @property PembelianDetail[] $pembelianDetails
 */
class Pembelian extends CActiveRecord
{

    const STATUS_DRAFT = 0;
    const STATUS_HUTANG = 1;
    const STATUS_LUNAS = 2;
    /* ===================== */
    const KERTAS_LETTER = 10;
    const KERTAS_A4 = 20;
    const KERTAS_FOLIO = 30;
    /* ===================== */
    const KERTAS_LETTER_NAMA = 'Letter';
    const KERTAS_A4_NAMA = 'A4';
    const KERTAS_FOLIO_NAMA = 'Folio';

    public $totalPembelian;
    public $namaSupplier;
    public $max; // Untuk mencari untuk nomor surat;
    public $nomorHutang;
    public $namaUpdatedBy;
    public $hutangBayar; // Untuk menampilkan nomor hutang ATAU nomor Pembayaran jika sudah Lunas

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'pembelian';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('profil_id', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
            array('nomor, referensi', 'length', 'max' => 45),
            array('profil_id, hutang_piutang_id, updated_by', 'length', 'max' => 10),
            array('tanggal_referensi, created_at, updated_at, updated_by, tanggal', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, nomor, tanggal, profil_id, referensi, tanggal_referensi, hutang_piutang_id, status, updated_at, updated_by, created_at, namaSupplier, nomorHutang, namaUpdatedBy, hutangBayar', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'profil' => array(self::BELONGS_TO, 'Profil', 'profil_id'),
            'hutangPiutang' => array(self::BELONGS_TO, 'HutangPiutang', 'hutang_piutang_id'),
            'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
            'pembelianDetails' => array(self::HAS_MANY, 'PembelianDetail', 'pembelian_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'nomor' => 'Nomor',
            'tanggal' => 'Tanggal',
            'profil_id' => 'Profil',
            'referensi' => 'Referensi',
            'tanggal_referensi' => 'Tanggal Referensi',
            'hutang_piutang_id' => 'Hutang Piutang',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'nomorHutang' => 'Nomor Hutang',
            'namaUpdatedBy' => 'User',
            'hutangBayar' => 'Hutang / Pembayaran'
        );
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
        //$criteria->compare("DATE_FORMAT(tanggal, '%d-%m-%Y %H:%i:%s')", $this->tanggal, true);
        $criteria->compare("DATE_FORMAT(tanggal, '%d-%m-%Y')", $this->tanggal, true);
        $criteria->compare('profil_id', $this->profil_id);
        $criteria->compare('referensi', $this->referensi, true);
        $criteria->compare("DATE_FORMAT(tanggal_referensi, '%d-%m-%Y')", $this->tanggal_referensi, true);
        $criteria->compare('hutang_piutang_id', $this->hutang_piutang_id, true);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $criteria->with = ['hutangPiutang', 'profil', 'updatedBy'];
        $criteria->compare('profil.nama', $this->namaSupplier, true);
        $criteria->compare('hutangPiutang.nomor', $this->nomorHutang, true);
        $criteria->compare('updatedBy.nama_lengkap', $this->namaUpdatedBy, true);

        $sort = [
            'defaultOrder' => 't.status, t.tanggal desc',
            'attributes' => [
                'namaSupplier' => [
                    'asc' => 'profil.nama',
                    'desc' => 'profil.nama desc'
                ],
                'nomorHutang' => [
                    'asc' => 'hutangPiutang.nomor',
                    'desc' => 'hutangPiutang.nomor desc'
                ],
                'namaUpdatedBy' => [
                    'asc' => 'updatedBy.nama_lengkap',
                    'desc' => 'updatedBy.nama_lengkap desc'
                ],
                '*'
            ]
        ];

        return new CActiveDataProvider($this, ['criteria' => $criteria,
            'sort' => $sort
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Pembelian the static model class
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
             * Tanggal akan diupdate jika melalui proses simpanPembelian
             * bersamaan dengan dapat nomor
             */
            $this->tanggal = date('Y-m-d H:i:s');
        }
        $this->updated_at = date("Y-m-d H:i:s");
        $this->updated_by = Yii::app()->user->id;

        // Jika disimpan melalui proses simpan pembelian
        if ($this->scenario === 'simpanPembelian') {
            // Status diubah jadi pembelian belum bayar (hutang)
            $this->status = Pembelian::STATUS_HUTANG;
            // Dapat nomor dan tanggal
            $this->tanggal = date('Y-m-d H:i:s');
            $this->nomor = $this->generateNomor6Seq();
        }

        return parent::beforeSave();
    }

    public function beforeValidate()
    {
        $this->tanggal_referensi = !empty($this->tanggal_referensi) ? date_format(date_create_from_format('d-m-Y', $this->tanggal_referensi), 'Y-m-d') : NULL;
        return parent::beforeValidate();
    }

    public function afterFind()
    {
        $this->tanggal = !is_null($this->tanggal) ? date_format(date_create_from_format('Y-m-d H:i:s', $this->tanggal), 'd-m-Y H:i:s') : '0';
        $this->tanggal_referensi = !is_null($this->tanggal_referensi) ? date_format(date_create_from_format('Y-m-d', $this->tanggal_referensi), 'd-m-Y') : '';
        return parent::afterFind();
    }

    /**
     * Ambil data barang. nama, barcode, satuan, harga_beli (harga beli terakhir), harga_jual (harga jual terakhir),
     * rrp (Rrp terakhir)
     * @param int $id id barang
     * @return mixed 1 row dalam array, false jika tidak ketemu
     */
    public function ambilDataBarang($id)
    {
        return Yii::app()->db->createCommand("
							select
								b.nama,
								b.barcode,
								sb.nama satuan,
								(select harga_beli from pembelian_detail where barang_id=:barangId
								order by id desc limit 1) harga_beli,
								(select harga
								from barang_harga_jual
								where barang_id =:barangId
								order by id desc limit 1) harga_jual,
								(select harga
								from barang_harga_jual_rekomendasi
								where barang_id =:barangId
								order by id desc limit 1) rrp
							from barang b
							left join barang_satuan sb on sb.id = b.satuan_id
							where b.id=:barangId
							")
                        ->bindParam(':barangId', $id)
                        ->queryRow();
    }

    /**
     * Total Pembelian
     * @return int Nilai Total
     */
    public function ambilTotal()
    {
        $pembelian = Yii::app()->db->createCommand()
                ->select('sum(harga_beli * qty) total')
                ->from(PembelianDetail::model()->tableName())
                ->where('pembelian_id=:pembelianId', array(':pembelianId' => $this->id))
                ->queryRow();
        return $pembelian['total'];
    }

    /**
     * Nilai total pembelian
     * @return text Total Pembelian dalam format ribuan
     */
    public function getTotal()
    {
        return number_format($this->ambilTotal(), 0, ',', '.');
    }

    /**
     * Simpan pembelian:
     * 1. update status dari draft menjadi pembelian.
     * 2. update stock.
     * 3. update harga jual & rrp
     * 4. create hutang.
     * 5. update stok minus. BELUM !!
     * @return boolean True jika proses berhasil
     * @throws Exception
     */
    public function simpanPembelian()
    {
        $this->scenario = 'simpanPembelian';
        $transaction = $this->dbConnection->beginTransaction();

        /* Untuk jumlah pembelian yang sangat banyak, misal: init data */
        if ($this->profil_id == 1) {
            ini_set('memory_limit', '-1');
            set_time_limit(0);
        }

        try {
            if ($this->save()) {
                /*
                 * Ambil data barang, dan data inventory terakhir/terbaru nya
                 * Jika inventory tidak ada, maka ib.* nilainya null
                 */
                $details = PembelianDetail::model()->findAll('pembelian_id=:pembelianId', array(':pembelianId' => $this->id));
                foreach ($details as $detail) {
                    /* Untuk setiap barang yang dibeli */

                    /* Sesuaikan inventory */
                    $inventoryBalance = new InventoryBalance();
                    $inventoryBalance->beli(InventoryBalance::ASAL_PEMBELIAN, $this->nomor, $detail->id, $detail->barang_id, $detail->harga_beli, $detail->qty);

                    /*
                     * Update harga jual
                     */
                    if (!HargaJual::model()->updateHarga($detail->barang_id, $detail->harga_jual)) {
                        throw new Exception("Gagal Update Harga Jual");
                    }
                    /*
                     * Update Rrp
                     */
                    if (!HargaJualRekomendasi::model()->updateHarga($detail->barang_id, $detail->harga_jual_rekomendasi)) {
                        throw new Exception("Gagal Update RRP");
                    }

                    /* Tambahkan supplier ke barang ini, jika belum ada */
                    $supplierBarangAda = SupplierBarang::model()->find("supplier_id={$this->profil_id} and barang_id = {$detail->barang_id}");
                    if (is_null($supplierBarangAda)) {
                        $supplierBarang = new SupplierBarang;
                        $supplierBarang->barang_id = $detail->barang_id;
                        $supplierBarang->supplier_id = $this->profil_id;
                        if (!$supplierBarang->save()) {
                            throw new Exception("Gagal simpan supplier barang");
                        }
                    }

                    /* Set Barang menjadi aktif */
                    Barang::model()->updateByPk($detail->barang_id, ['status'=>Barang::STATUS_AKTIF]);
                }

                // Total dari pembelian barang
                // Jika nanti ada item lain, misal: Transport, pajak, dll
                // ditambahkan di sini
                $jumlahPembelian = $this->ambilTotal();
                /*
                 * Create (hutang)
                 */
                $hutang = new HutangPiutang;
                $hutang->profil_id = $this->profil_id;
                $hutang->jumlah = $jumlahPembelian;
                $hutang->tipe = HutangPiutang::TIPE_HUTANG;
                $hutang->asal = HutangPiutang::DARI_PEMBELIAN;
                $hutang->nomor_dokumen_asal = $this->nomor;
                if (!$hutang->save()) {
                    throw new Exception("Gagal simpan hutang");
                }

                /*
                 * Hutang Detail
                 */
                $hutangDetail = new HutangPiutangDetail;
                $hutangDetail->hutang_piutang_id = $hutang->id;
                $hutangDetail->keterangan = 'Pembelian: ' . $this->nomor;
                $hutangDetail->jumlah = $jumlahPembelian;
                if (!$hutangDetail->save()) {
                    throw new Exception("Gagal simpan hutang detail");
                }

                /*
                 * Simpan hutang_id ke pembelian
                 */
                if (!Pembelian::model()->updateByPk($this->id, array('hutang_piutang_id' => $hutang->id)) > 1) {
                    throw new Exception("Gagal simpan hutang_id");
                }

                $transaction->commit();
                return array('sukses' => true);
            } else {
                throw new Exception("Gagal Simpan Pembelian");
            }
        } catch (Exception $ex) {
            $transaction->rollback();
            // throw $up;

            return array(
                'sukses' => false,
                'error' => array(
                    'msg' => $ex->getMessage(),
                    'code' => $ex->getCode(),
            ));
        }
    }

    /**
     * Mencari nomor untuk penomoran surat
     * @return int maksimum+1 atau 1 jika belum ada nomor untuk tahun ini
     */
    public function cariNomorTahunan()
    {
        $tahun = date('y');
        $data = $this->find(array(
            'select' => 'max(substring(nomor,9)*1) as max',
            'condition' => "substring(nomor,5,2)='{$tahun}'")
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
        $config = Config::model()->find("nama='toko.kode'");
        $kodeCabang = $config->nilai;
        $kodeDokumen = KodeDokumen::PEMBELIAN;
        $kodeTahunBulan = date('ym');
        $sequence = substr('00000' . $this->cariNomorTahunan(), -6);
        return "{$kodeCabang}{$kodeDokumen}{$kodeTahunBulan}{$sequence}";
    }

    public function getNamaStatus()
    {
        $status = array(
            Pembelian::STATUS_DRAFT => 'Draft',
            Pembelian::STATUS_HUTANG => 'Hutang',
            Pembelian::STATUS_LUNAS => 'Lunas'
        );
        return $status[$this->status];
    }

    public function toIndoDate($timeStamp)
    {
        $tanggal = date_format(date_create($timeStamp), 'j');
        $bulan = date_format(date_create($timeStamp), 'n');
        $namabulan = $this->namaBulan($bulan);
        $tahun = date_format(date_create($timeStamp), 'Y');
        return $tanggal . ' ' . $namabulan . ' ' . $tahun;
    }

    public function namaBulan($i)
    {
        static $bulan = array(
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        );
        return $bulan[$i - 1];
    }

    public function pembelianText($draft = false, $cpi = 10)
    {
        $lebarKertas = 8; //inchi
        $jumlahKolom = $cpi * $lebarKertas;

        $configs = Config::model()->findAll();
        /*
         * Ubah config (object) jadi array
         */
        $branchConfig = array();
        foreach ($configs as $config) {
            $branchConfig[$config->nama] = $config->nilai;
        }

        $pembelianDetail = Yii::app()->db->createCommand("
         select barang.barcode, barang.nama, pd.qty, pd.harga_beli, pd.harga_jual
         from pembelian_detail pd
         join barang on pd.barang_id = barang.id
         where pd.pembelian_id = :pembelianId
              ")
                ->bindValue(':pembelianId', $this->id)
                ->queryAll();

        $nota = '';

        $strNomor = 'Nomor       : ' . $this->nomor;
        if ($draft) {
            $strNomor = 'Nomor       : DRAFT';
        }
        $strTgl = 'Tanggal     : ' . $this->toIndoDate($this->tanggal);
        $strUser = 'User        : ' . ucwords($this->updatedBy->nama_lengkap);
        $strTotal = 'Total       : ' . $this->getTotal();

        $kananMaxLength = strlen($strNomor) > strlen($strTgl) ? strlen($strNomor) : strlen($strTgl);
        /* Jika Nama User terlalu panjang, akan di truncate */
        $strUser = strlen($strUser) > $kananMaxLength ? substr($strUser, 0, $kananMaxLength - 2) . '..' : $strUser;

        $strInvoice = 'PEMBELIAN '; //Jumlah karakter harus genap!

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

        $nota .= 'Dari: ' . $this->profil->nama . PHP_EOL;
        $nota .= '      ' . substr($this->profil->alamat1 . ' ' . $this->profil->alamat2 . ' ' . $this->profil->alamat3, 0, $jumlahKolom - 10) . PHP_EOL;
        if (isset($this->referensi) && !empty($this->referensi)) {
            $nota .= 'Ref : ' . $this->referensi . ' ';
            $nota .= isset($this->tanggal_referensi) ? $this->tanggal_referensi : '';
        }
        $nota .= PHP_EOL;

        $nota .= str_pad('', $jumlahKolom, "-") . PHP_EOL;
        $textHeader1 = ' Barang';
        $textHeader2 = 'H Beli    H Jual    Qty Sub Total ';
        $textHeader = $textHeader1 . str_pad($textHeader2, $jumlahKolom - strlen($textHeader1), ' ', STR_PAD_LEFT) . PHP_EOL;
        $nota .= $textHeader;
        $nota .= str_pad('', $jumlahKolom, "-") . PHP_EOL;

        $no = 1;
        foreach ($pembelianDetail as $detail) {
            $strBarcode = str_pad(substr($detail['barcode'], 0, 13), 13, ' '); // Barcode hanya diambil 13 char pertama
            $strBarang = str_pad(trim(substr($detail['nama'], 0, 28)), 28, ' '); //Nama Barang hanya diambil 28 char pertama
            $strQty = str_pad($detail['qty'], 5, ' ', STR_PAD_LEFT);
            $strHarga = str_pad(number_format($detail['harga_jual'], 0, ',', '.'), 8, ' ', STR_PAD_LEFT);
            $strHargaBeli = str_pad(number_format($detail['harga_beli'], 0, ',', '.'), 8, ' ', STR_PAD_LEFT);
            $strSubTotal = str_pad(number_format($detail['harga_beli'] * $detail['qty'], 0, ',', '.'), 8, ' ', STR_PAD_LEFT);
            $row1 = ' ' . $strBarcode . ' ' . $strBarang . ' ';
            $row2 = $strHargaBeli . '  ' . $strHarga . '  ' . $strQty . '  ' . $strSubTotal;
            $row = $row1 . str_pad($row2 . ' ', $jumlahKolom - strlen($row1), ' ', STR_PAD_LEFT) . PHP_EOL;

            $nota .= $row;
            $no++;
        }

        $nota .= str_pad('', $jumlahKolom, "-") . PHP_EOL . PHP_EOL;
        /*
          if (!$draft) {
          $signatureHead1 = '          Diterima';
          $signatureHead2 = 'a.n. ' . $branchConfig['toko.nama'];
          $signatureHead3 = 'Driver';

          $nota .= $signatureHead1 . str_pad($signatureHead2, 23 - (strlen($signatureHead2) / 2) + strlen($signatureHead2), ' ', STR_PAD_LEFT) .
          str_pad($signatureHead3, 17 - (strlen($signatureHead3) / 2) + strlen($signatureHead3), ' ', STR_PAD_LEFT) . PHP_EOL;
          $nota .= PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
          $nota .= '     (                )         (                )         (                )' . PHP_EOL;
          }
         *
         */
        $nota .= PHP_EOL;
        return $nota;
    }

    public static function listNamaKertas()
    {
        return array(
            self::KERTAS_A4 => self::KERTAS_A4_NAMA,
            self::KERTAS_LETTER => self::KERTAS_LETTER_NAMA,
            self::KERTAS_FOLIO => self::KERTAS_FOLIO_NAMA,
        );
    }

    public function cariByRef($profilId, $noRef, $nominal)
    {
        return Yii::app()->db->createCommand()->
                        select('*')->
                        from("
                    (SELECT
                        id, nomor, tanggal, referensi, `status`
                    FROM
                        pembelian
                    WHERE
                        profil_id = :profilId
                            AND referensi = :noRef) t1
                        ")->
                        join("
                    (SELECT
                        pembelian_id, SUM(qty * harga_beli) total
                    FROM
                        pembelian_detail
                    WHERE
                        pembelian_id IN (SELECT
                                id
                            FROM
                                pembelian
                            WHERE
                                profil_id = :profilId
                                    AND referensi = :noRef)
                    GROUP BY pembelian_id
                    HAVING SUM(qty * harga_beli) = :nominal) t2
                        ", 't1.id = t2.pembelian_id')->
                        bindValues([
                            ':profilId' => $profilId,
                            ':noRef' => $noRef,
                            ':nominal' => $nominal
                        ])
                        ->queryAll();
    }

    /**
     * Retur Current Nota
     * @param int $id Pembelian ID
     */
    public function retur()
    {
        $transaction = $this->dbConnection->beginTransaction();
        try {
            $returBeli = new ReturPembelian;
            $returBeli->profil_id = $this->profil_id;
            if (!$returBeli->save()) {
                throw new Exception("Gagal simpan Retur Pembelian");
            }

            /* Insert semua yang ada di pembelian_detail ke retur_pembelian_detail */
            $sql = "
            INSERT INTO retur_pembelian_detail 
                (retur_pembelian_id, inventory_balance_id, qty, updated_by, created_at)
            SELECT 
                :returPembelianId, ib.id, detail.qty, :user, now()
            FROM
                pembelian_detail detail
                    JOIN
                inventory_balance ib ON detail.id = ib.pembelian_detail_id
            WHERE
                pembelian_id = :pembelianId                  
                    ";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValues([
                ':returPembelianId' => $returBeli->id,
                ':pembelianId' => $this->id,
                ':user' => Yii::app()->user->id
            ]);
            $rows = $command->execute();

            $transaction->commit();
            return [
                'sukses' => true,
                'data' => [
                    'returPembelianId' => $returBeli->id,
                    'rows' => $rows
                ]
            ];
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
