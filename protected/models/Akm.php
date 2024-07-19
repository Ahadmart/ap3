<?php

/**
 * This is the model class for table "akm".
 *
 * The followings are the available columns in table 'akm':
 * @property string $id
 * @property string $nomor
 * @property string $tanggal
 * @property string $profil_id
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Profil $profil
 * @property AkmDetail[] $akmDetails
 */
class Akm extends Penjualan
{

    const STATUS_DRAFT = 0;
    const STATUS_OK = 1;

    public $max; // Untuk mencari untuk nomor surat;
    public $namaProfil;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'akm';
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
            array('profil_id', 'length', 'max' => 10),
            array('created_at, updated_at, updated_by, tanggal', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, nomor, tanggal, profil_id, status, updated_at, updated_by, created_at, namaProfil', 'safe', 'on' => 'search'),
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
            'akmDetails' => array(self::HAS_MANY, 'AkmDetail', 'akm_id'),
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
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'namaProfil' => 'Customer',
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
    public function search($merge = null)
    {
// @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('t.nomor', $this->nomor, true);
        $criteria->compare("DATE_FORMAT(t.tanggal, '%d-%m-%Y')", $this->tanggal, true);
        $criteria->compare('profil_id', $this->profil_id, true);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('t.updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $criteria->with = ['profil'];
        $criteria->compare('profil.nama', $this->namaProfil, true);

        $sort = [
            'defaultOrder' => 't.status, tanggal desc',
            'attributes' => [
                '*',
                'namaProfil' => [
                    'asc' => 'profil.nama',
                    'desc' => 'profil.nama desc'
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
     * @return Penjualan the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeValidate()
    {
        $this->profil_id = empty($this->profil_id) ? Profil::PROFIL_UMUM : $this->profil_id;
//        $this->updated_by = sprintf('%u', ip2long(Yii::app()->getRequest()->getUserHostAddress()));
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
        $this->updated_at = date("Y-m-d H:i:s");
        $this->updated_by = sprintf('%u', ip2long(Yii::app()->getRequest()->getUserHostAddress()));
        // Jika disimpan melalui proses simpan akm
        if ($this->scenario === 'simpanAkm') {
            $this->status = self::STATUS_OK;
            // Dapat nomor dan tanggal baru
            $this->tanggal = date('Y-m-d H:i:s');
            $this->nomor = $this->generateNomor();
        }
        return CActiveRecord::beforeSave();
    }

    /**
     * Mencari jumlah barang di tabel akm_detail
     * @param int $barangId ID Barang
     * @return int qty / jumlah barang, FALSE jika tidak ada
     */
    public function barangAda($barangId)
    {
        $detail = Yii::app()->db->createCommand("
        select sum(qty) qty from akm_detail
        where akm_id=:akmId and barang_id=:barangId
            ")->bindValues(array(':akmId' => $this->id, ':barangId' => $barangId))
                ->queryRow();

        return $detail['qty'];
    }

    /**
     * Hapus barang di akm_detail
     * @param ActiveRecord $barang
     */
    public function cleanBarang($barang)
    {
        AkmDetail::model()->deleteAll('barang_id=:barangId AND akm_id=:akmId', array(
            ':barangId' => $barang->id,
            ':akmId' => $this->id
        ));
    }

    /**
     * Tambah barang ke akm
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
            $barang = Barang::model()->find('barcode=:barcode', array(':barcode' => $barcode));

            /* Jika barang tidak ada */
            if (is_null($barang)) {
                throw new Exception('Barang tidak ditemukan', 500);
            }
            $this->tambahBarangProc($barang, $qty);

            if ($cekLimit && $this->lewatLimit()) {
                throw new Exception('Gagal!! Melebihi limit penjualan!', 500);
            }

            $transaction->commit();
            return array(
                'sukses' => true
            );
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
        $detail = new AkmDetail;
        $detail->akm_id = $this->id;
        $detail->barang_id = $barangId;
        $detail->qty = $qty;
        $detail->harga_jual = $hargaJual;
        if ($diskon > 0) {
            $detail->diskon = $diskon;
        }
        if (!$detail->save()) {
            throw new Exception("Gagal simpan akm detail: akmId:{$this->id}, barangId:{$barangId}, qty:{$qty}", 500);
        }
        if ($diskon > 0) {
            //$this->insertDiskon($detail, $tipeDiskonId);
        }
    }

    /**
     * Mencari nomor untuk penomoran surat
     * @return int maksimum+1 atau 1 jika belum ada nomor untuk tahun ini
     */
    public function cariNomor()
    {
        $tahun = date('y');
        $data = $this->find([
            'select' => 'max(substring(nomor,9)*1) as max',
            'condition' => "substring(nomor,5,2)='{$tahun}'"]
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
        $kodeDokumen = KodeDokumen::AKM;
        $kodeTahunBulan = date('ym');
        $sequence = substr('00000' . $this->cariNomor(), -6);
        return "{$kodeCabang}{$kodeDokumen}{$kodeTahunBulan}{$sequence}";
    }

    /**
     * Total Akm
     * @return int total dalam bentuk raw (belum terformat)
     */
    public function ambilTotal()
    {
        $detail = Yii::app()->db->createCommand()
                ->select('sum(harga_jual * qty) total')
                ->from(AkmDetail::model()->tableName())
                ->where('akm_id=:akmId', array(':akmId' => $this->id))
                ->queryRow();
        return $detail['total'];
    }

    /**
     * Proses simpan AKM.
     * Simpan, Print Struk
     *
     */
    public function simpanAkm()
    {
        if (!$this->save()) {
            throw new Exception('Gagal simpan akm', 500);
        }
    }

    public function simpan()
    {
        $transaction = $this->dbConnection->beginTransaction();
        $this->scenario = 'simpanAkm';
        try {
            $this->simpanAkm();
            $transaction->commit();
            return [
                'sukses' => true
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

    public function listStatus()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_OK => 'OK',
        ];
    }

    public function getNamaStatus()
    {
        $status = $this->listStatus();
        return $status[$this->status];
    }

    /**
     * Struk AKM
     * @return text
     */
    public function strukText()
    {
        $configToko = Config::model()->find('nama=:key', [':key' => 'toko.nama']);
        $total = $this->getTotal();
        $nomor = substr($this->nomor, -6) * 1;

        $struk = chr(27) . "@"; //Init Printer
        //$struk .= chr(27) . chr(101) . chr(2); //2 reverse lf
        $struk .= chr(27) . "!" . chr(1); //font B / normal
        //$struk .= chr(27) . chr(101) . chr(2); //1 reverse lf
        $struk .= chr(27) . "a" . chr(48); //0 left
        //$struk .= chr(27) . chr(101) . chr(2); //2 reverse lf
        //$struk .= chr(27) . chr(101) . chr(2); //2 reverse lf
        $struk .= strtoupper($configToko->nilai) . "\n";
        $struk .= "Anjungan Kasir Mandiri\n";


        $struk .= chr(27) . chr(101) . chr(2); //2 reverse lf
        $struk .= chr(27) . chr(101) . chr(2); //2 reverse lf
        $struk .= chr(27) . "!" . chr(16); //font double width
        $struk .= chr(27) . "a" . chr(2); //2 right
        $struk .= "Rp. {$total}\n\n";

        $struk .= chr(27) . "!" . chr(48); //font besar
        $struk .= chr(27) . "a" . chr(1); //0 center
        $struk .="{$nomor}\n\n";
        $struk .= chr(27) . "!" . chr(1); //font Normal
        //$struk .= chr(27) . '@' . chr(29) . 'k' . chr(107) . chr(3) . $nomor . chr(0);
        $struk .= chr(27) . "a" . chr(48); //0 left

        $struk .= "Ketentuan:\n";
        $struk .= "Struk ini ";

        //    $struk .= chr(27) . "!" . chr(8); //font tebal
        $struk .= "BUKAN bukti pembayaran\n";
        $struk .= chr(27) . "!" . chr(1); //font normal
        $struk .= "Silahkan melakukan pembayaran di kasir\n"
                . "Jika ada perbedaan perhitungan,\n"
                . "Yang benar adalah ";
        //    $struk .= chr(27) . "!" . chr(8); //font tebal
        $struk .= "perhitungan kasir\n";
        $struk .= chr(27) . "!" . chr(1); //font normal
        $struk .= chr(29) . "V" . chr(66) . chr(48); //Feed paper & cut

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
            $penjualan = Yii::app()->db->createCommand("
            select sum(harga_jual * qty) jumlah from akm_detail where akm_id=:akmId
                ")->bindValues(array(':akmId' => $this->id))
                    ->queryRow();

            $configMember = Yii::app()->db->createCommand("
            select nilai from config where nama=:namaConfig
                ")->bindValues(array(':namaConfig' => 'member.nilai_1_poin'))
                    ->queryRow();

            /* Jika di config nilai = 0, berarti tidak memakai sistem poin */
            return $configMember['nilai'] > 0 ? floor($penjualan['jumlah'] / $configMember['nilai']) : 0;
        } else {
            return 0;
        }
    }

    public function gantiCustomer($customer)
    {
        $transaction = $this->dbConnection->beginTransaction();

        try {
            if (!$this->saveAttributes(array('profil_id' => $customer->id))) {
                throw new Exception('Gagal ubah customer', 500);
            }
            $alamat1 = !empty($customer->alamat1) ? $customer->alamat1 : '';
            $alamat2 = !empty($customer->alamat2) ? '<br>' . $customer->alamat2 : '';
            $alamat3 = !empty($customer->alamat3) ? '<br>' . $customer->alamat3 : '';
            /* Ambil data detail */
            $akmDetails = AkmDetail::model()->findAll('akm_id=:akmId', array(
                ':akmId' => $this->id
            ));

            /* Hapus dan re-insert */

            $tabelAkmDetail = AkmDetail::model()->tableName();

            AkmDetail::model()->deleteAll('akm_id=:akmId', array(
                'akmId' => $this->id
            ));

            foreach ($tabelAkmDetail as $detail) {
                $barang = Barang::model()->findByPk($detail->barang_id);
                $this->tambahBarangProc($barang, $detail->qty);
            }

            $transaction->commit();

            return array(
                'sukses' => true,
                'nama' => $customer->nama,
                'nomor' => $customer->nomor,
                'address' => $alamat1 . $alamat2 . $alamat3
            );
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
