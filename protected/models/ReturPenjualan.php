<?php

/**
 * This is the model class for table "retur_penjualan".
 *
 * The followings are the available columns in table 'retur_penjualan':
 * @property string $id
 * @property string $nomor
 * @property string $tanggal
 * @property string $profil_id
 * @property string $hutang_piutang_id
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property HutangPiutang $hutangPiutang
 * @property Profil $profil
 * @property User $updatedBy
 * @property ReturPenjualanDetail[] $returPenjualanDetails
 */
class ReturPenjualan extends CActiveRecord
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

    public $max; // Untuk mencari untuk nomor surat;
    public $namaProfil;
    public $nomorHutangPiutang;
    public $namaUpdatedBy;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'retur_penjualan';
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
            array('nomor', 'length', 'max' => 45),
            array('profil_id, hutang_piutang_id, updated_by', 'length', 'max' => 10),
            array('tanggal, created_at, updated_at, updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, nomor, tanggal, profil_id, hutang_piutang_id, status, updated_at, updated_by, created_at, namaUpdatedBy', 'safe', 'on' => 'search'),
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
            'hutangPiutang' => array(self::BELONGS_TO, 'HutangPiutang', 'hutang_piutang_id'),
            'profil' => array(self::BELONGS_TO, 'Profil', 'profil_id'),
            'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
            'returPenjualanDetails' => array(self::HAS_MANY, 'ReturPenjualanDetail', 'retur_penjualan_id'),
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
            'hutang_piutang_id' => 'Hutang Piutang',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'namaUpdatedBy' => 'User'
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
        $criteria->compare('nomor', $this->nomor, true);
        $criteria->compare("DATE_FORMAT(t.tanggal, '%d-%m-%Y')", $this->tanggal, true);
        $criteria->compare('profil_id', $this->profil_id, true);
        $criteria->compare('hutang_piutang_id', $this->hutang_piutang_id, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $criteria->with = ['updatedBy'];
        $criteria->compare('updatedBy.nama_lengkap', $this->namaUpdatedBy, true);

        $sort = [
            'defaultOrder' => 't.status, t.tanggal desc',
            'attributes' => [
                '*',
                'namaUpdatedBy' => [
                    'asc' => 'updatedBy.nama_lengkap',
                    'desc' => 'updatedBy.nama_lengkap desc'
                ],
            ]
        ];

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort' => $sort
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ReturPenjualan the static model class
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
             * Tanggal akan diupdate jika melalui proses simpanPenjualan
             * bersamaan dengan dapat nomor
             */
            $this->tanggal = date('Y-m-d H:i:s');
        }
        $this->updated_at = null; // Trigger current timestamp
        $this->updated_by = Yii::app()->user->id;
        // Jika disimpan melalui proses simpan retur penjualan
        if ($this->scenario === 'simpan') {
            // Status diubah jadi retur penjualan belum bayar (hutang)
            $this->status = self::STATUS_HUTANG;
            // Dapat nomor dan tanggal baru
            $this->tanggal = date('Y-m-d H:i:s');
            $this->nomor = $this->generateNomor();
        }
        return parent::beforeSave();
    }

    /**
     * Mencari nomor untuk penomoran surat
     * @return int maksimum+1 atau 1 jika belum ada nomor untuk tahun ini
     */
    public function cariNomor()
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
     * Membuat nomor surat
     * @return string Nomor sesuai format "[KodeCabang][kodeDokumen][Tahun][Bulan][SequenceNumber]"
     */
    public function generateNomor()
    {
        $config = Config::model()->find("nama='toko.kode'");
        $kodeCabang = $config->nilai;
        $kodeDokumen = KodeDokumen::RETUR_PENJUALAN;
        $kodeTahunBulan = date('ym');
        $sequence = substr('0000' . $this->cariNomor(), -5);
        return "{$kodeCabang}{$kodeDokumen}{$kodeTahunBulan}{$sequence}";
    }

    public function listStatus()
    {
        return array(
            ReturPenjualan::STATUS_DRAFT => 'Draft',
            ReturPenjualan::STATUS_HUTANG => 'Hutang',
            ReturPenjualan::STATUS_LUNAS => 'Lunas'
        );
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
     * Total Retur Penjualan
     * @return int total dalam bentuk raw (belum terformat)
     */
    public function ambilTotal()
    {
        $detail = Yii::app()->db->createCommand()
                ->select('sum(pd.harga_jual * rpd.qty) total')
                ->from(ReturPenjualanDetail::model()->tableName() . ' as rpd')
                ->join(PenjualanDetail::model()->tableName() . ' as pd', 'rpd.penjualan_detail_id=pd.id')
                ->where('retur_penjualan_id=:returPenjualanId', array(':returPenjualanId' => $this->id))
                ->queryRow();

        return $detail['total'];
        /*
          $pembelian = Yii::app()->db->createCommand("select sum(rpd.qty * pd.harga_jual) total
          from retur_penjualan_detail rpd
          join penjualan_detail pd on pd.id = rpd.penjualan_detail_id
          where retur_penjualan_id = :penjualanId")
          ->bindValue(':penjualanId', $this->id)
          ->queryRow();
          return $pembelian['total'];
         *
         */
    }

    /**
     * Total retur penjualan
     * @return string Total dalam format 0.000
     */
    public function getTotal()
    {
        if ($this->scenario === 'raw') {
            return $this->ambilTotal();
        } else {
            return number_format($this->ambilTotal(), 0, ',', '.');
        }
    }

    /*
     * Simpan Retur Penjualan
     * 1. Ubah status jadi hutang
     * 2. Tambah Inventory Balance
     * 3. Buat Nota Kredit
     */

    public function simpan()
    {
        $this->scenario = 'simpan';
        $transaction = $this->dbConnection->beginTransaction();
        try {
            /*
             * Save sekaligus mengubah status dari draft jadi piutang
             */
            if ($this->save()) {
                $details = ReturPenjualanDetail::model()->findAll("retur_penjualan_id={$this->id}");
                foreach ($details as $detail) {
                    /*
                     * Untuk setiap item, tambahkan qty ke inventory baru
                     */
                    InventoryBalance::model()->returJual($detail);
                }

                // Total dari retur penjualan
                // Jika nanti ada item lain, misal: Transport, pajak, dll
                // ditambahkan di sini
                $jumlahReturJual = $this->ambilTotal();
                /*
                 * Create hutang
                 */
                $hutang = new HutangPiutang;
                $hutang->profil_id = $this->profil_id;
                $hutang->jumlah = $jumlahReturJual;
                $hutang->tipe = HutangPiutang::TIPE_HUTANG;
                $hutang->asal = HutangPiutang::DARI_RETUR_JUAL;
                $hutang->nomor_dokumen_asal = $this->nomor;
                if (!$hutang->save()) {
                    throw new Exception("Gagal simpan hutang");
                }

                /*
                 * Hutang Detail
                 */
                $hutangDetail = new HutangPiutangDetail;
                $hutangDetail->hutang_piutang_id = $hutang->id;
                $hutangDetail->keterangan = 'Retur Jual: ' . $this->nomor;
                $hutangDetail->jumlah = $jumlahReturJual;
                if (!$hutangDetail->save()) {
                    throw new Exception("Gagal simpan hutang detail");
                }

                /*
                 * Simpan hutang_id ke pembelian
                 */
                if (!ReturPenjualan::model()->updateByPk($this->id, array('hutang_piutang_id' => $hutang->id)) > 1) {
                    throw new Exception("Gagal simpan hutang_id");
                }

                $transaction->commit();
                return true;
            } else {
                throw new Exception("Gagal Simpan Retur Penjualan");
            }
        } catch (Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }

    public function beforeValidate()
    {
        $this->profil_id = empty($this->profil_id) ? Profil::PROFIL_UMUM : $this->profil_id;
        return parent::beforeValidate();
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

    public function returPenjualanText($draft = false, $cpi = 10)
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

        $returPembelianDetail = Yii::app()->db->createCommand("
         select barang.barcode, barang.nama, pd.qty, pjd.harga_jual
         from retur_penjualan_detail pd
         join penjualan_detail pjd on pd.penjualan_detail_id = pjd.id
         join barang on pjd.barang_id = barang.id
         where pd.retur_penjualan_id = :returPenjualanId
              ")
                ->bindValue(':returPenjualanId', $this->id)
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

        $strInvoice = 'RETUR PENJUALAN '; //Jumlah karakter harus genap!

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
        $nota .= '        ' . substr($this->profil->alamat1 . ' ' . $this->profil->alamat2 . ' ' . $this->profil->alamat3, 0, $jumlahKolom - 8) . PHP_EOL;
        if (isset($this->referensi) && !empty($this->referensi)) {
            $nota .= 'Ref : ' . $this->referensi . ' ';
            $nota .= isset($this->tanggal_referensi) ? $this->tanggal_referensi : '';
        }
        $nota .= PHP_EOL;

        $nota .= str_pad('', $jumlahKolom, "-") . PHP_EOL;
        $textHeader1 = ' Barang';
        $textHeader2 = 'Harga    Qty Sub Total ';
        $textHeader = $textHeader1 . str_pad($textHeader2, $jumlahKolom - strlen($textHeader1), ' ', STR_PAD_LEFT) . PHP_EOL;
        $nota .= $textHeader;
        $nota .= str_pad('', $jumlahKolom, "-") . PHP_EOL;

        $no = 1;
        foreach ($returPembelianDetail as $detail) {
            $strBarcode = str_pad(substr($detail['barcode'], 0, 13), 13, ' '); // Barcode hanya diambil 13 char pertama
            $strBarang = str_pad(trim(substr($detail['nama'], 0, 28)), 28, ' '); //Nama Barang hanya diambil 28 char pertama
            $strQty = str_pad($detail['qty'], 5, ' ', STR_PAD_LEFT);
            $strHargaJual = str_pad(number_format($detail['harga_jual'], 0, ',', '.'), 8, ' ', STR_PAD_LEFT);
            $strSubTotal = str_pad(number_format($detail['harga_jual'] * $detail['qty'], 0, ',', '.'), 8, ' ', STR_PAD_LEFT);
            $row1 = ' ' . $strBarcode . ' ' . $strBarang . ' ';
            $row2 = $strHargaJual . '  ' . $strQty . '  ' . $strSubTotal;
            $row = $row1 . str_pad($row2 . ' ', $jumlahKolom - strlen($row1), ' ', STR_PAD_LEFT) . PHP_EOL;

            $nota .= $row;
            $no++;
        }

        $nota .= str_pad('', $jumlahKolom, "-") . PHP_EOL . PHP_EOL;

        $nota .= PHP_EOL;
        return $nota;
    }

    public function listNamaKertas()
    {
        return array(
            self::KERTAS_A4 => self::KERTAS_A4_NAMA,
            self::KERTAS_LETTER => self::KERTAS_LETTER_NAMA,
            self::KERTAS_FOLIO => self::KERTAS_FOLIO_NAMA,
        );
    }

}
