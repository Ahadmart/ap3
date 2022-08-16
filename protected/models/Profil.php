<?php

/**
 * This is the model class for table "profil".
 *
 * The followings are the available columns in table 'profil':
 * @property string $id
 * @property integer $tipe_id
 * @property string $nomor
 * @property string $identitas
 * @property string $nama
 * @property string $alamat1
 * @property string $alamat2
 * @property string $alamat3
 * @property string $telp
 * @property string $hp
 * @property integer $jenis_kelamin
 * @property string $tanggal_lahir
 * @property string $surel
 * @property string $keterangan
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property HutangPiutang[] $hutangPiutangs
 * @property Pembelian[] $pembelians
 * @property Penerimaan[] $penerimaans
 * @property Pengeluaran[] $pengeluarans
 * @property Penjualan[] $penjualans
 * @property ProfilTipe $tipe
 * @property User $updatedBy
 * @property ReturPembelian[] $returPembelians
 * @property ReturPenjualan[] $returPenjualans
 * @property SupplierBarang[] $supplierBarangs
 */
class Profil extends CActiveRecord
{
    const TIPE_SUPPLIER           = 1;
    const TIPE_CUSTOMER           = 2;
    const TIPE_KARYAWAN           = 3;
    const TIPE_MEMBER_ONLINE      = 4;
    const AWAL_ID                 = 100; // id lebih kecil & / sama dari ini, untuk keperluan khusus. Untuk trx, mulai dari 101
    const JENIS_KELAMIN_LAKI_LAKI = 0;
    const JENIS_KELAMIN_WANITA    = 1;
    const PROFIL_INIT             = 1; // Profil untuk init pembelian
    const PROFIL_UMUM             = 2; // Default profil untuk penjualan

    public $profileTipeId;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'profil';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['tipe_id, nama', 'required'],
            ['tipe_id, jenis_kelamin', 'numerical', 'integerOnly' => true],
            ['nomor', 'length', 'max' => 45],
            ['identitas, hp, surel', 'length', 'max' => 255],
            ['nama, alamat1, alamat2, alamat3', 'length', 'max' => 100],
            ['telp', 'length', 'max' => 20],
            ['keterangan', 'length', 'max' => 1000],
            ['updated_by', 'length', 'max' => 10],
            ['created_at, updated_at, updated_by, tanggal_lahir', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, tipe_id, nomor, identitas, nama, alamat1, alamat2, alamat3, telp, hp, jenis_kelamin, tanggal_lahir, surel, keterangan, updated_at, updated_by, created_at', 'safe', 'on' => 'search'],
        ];
    }

    /*
    public function defaultScope()
    {
    return array(
    'order' => 'profil.nama',
    );
    }
     */

    public function scopes()
    {
        return [
            'profilTrx'    => [
                'condition' => 'id>' . self::AWAL_ID,
            ],
            'tipeSupplier' => [
                'condition' => 'tipe_id=' . self::TIPE_SUPPLIER,
            ],
            'tipeCustomer' => [
                'condition' => 'tipe_id=' . self::TIPE_CUSTOMER,
            ],
            'orderByNama'  => [
                'order' => 'nama',
            ],
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
            'tipe'      => [self::BELONGS_TO, 'TipeProfil', 'tipe_id'],
            'updatedBy' => [self::BELONGS_TO, 'User', 'updated_by'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'tipe_id'       => 'Tipe',
            'nomor'         => 'Nomor (Member)',
            'identitas'     => 'Identitas (KTP/SIM/..)',
            'nama'          => 'Nama',
            'alamat1'       => 'Alamat1',
            'alamat2'       => 'Alamat2',
            'alamat3'       => 'Alamat3',
            'telp'          => 'Telp',
            'hp'            => 'Hp',
            'jenis_kelamin' => 'Jenis Kelamin',
            'tanggal_lahir' => 'Tanggal Lahir',
            'surel'         => 'Surel',
            'keterangan'    => 'Keterangan',
            'updated_at'    => 'Updated At',
            'updated_by'    => 'Updated By',
            'created_at'    => 'Created At',
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
        $criteria->compare('tipe_id', $this->tipe_id);
        $criteria->compare('nomor', $this->nomor, true);
        $criteria->compare('identitas', $this->identitas, true);
        $criteria->compare('nama', $this->nama, true);
        $criteria->compare('alamat1', $this->alamat1, true);
        $criteria->compare('alamat2', $this->alamat2, true);
        $criteria->compare('alamat3', $this->alamat3, true);
        $criteria->compare('telp', $this->telp, true);
        $criteria->compare('hp', $this->hp, true);
        $criteria->compare('jenis_kelamin', $this->jenis_kelamin);
        $criteria->compare('tanggal_lahir', $this->tanggal_lahir, true);
        $criteria->compare('surel', $this->surel, true);
        $criteria->compare('keterangan', $this->keterangan, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        if (isset($this->profileTipeId)) {
            $criteria->addCondition('tipe_id=' . $this->profileTipeId);
        }

        $sort = [
            'defaultOrder' => 't.nama',
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
     * @return Profil the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        $this->updated_at = date('Y-m-d H:i:s');
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

    public function listSupplierYangBukan($barangId)
    {
        return Yii::app()->db->createCommand()
            ->select('s.id, s.nama, s.alamat1, s.alamat2, s.alamat3')
            ->from($this->tableName() . ' s')
            ->where('s.tipe_id = 1 and s.id not in(select supplier_id from supplier_barang where barang_id = :barangId)', [':barangId' => $barangId])
            ->order('s.nama, s.alamat1')
            ->queryAll();
    }

    public function getNamaTipe()
    {
        $tipeProfil = TipeProfil::model()->findByPk($this->tipe_id);
        return $tipeProfil->nama;
    }

    public function listJenisKelamin()
    {
        return [
            self::JENIS_KELAMIN_LAKI_LAKI => 'Laki-laki',
            self::JENIS_KELAMIN_WANITA    => 'Perempuan',
        ];
    }

    public function listTipe()
    {
        return [
            self::TIPE_SUPPLIER => 'Supplier',
            self::TIPE_CUSTOMER => 'Customer',
            self::TIPE_KARYAWAN => 'Karyawan',
        ];
    }

    public function beforeValidate()
    {
        $this->tanggal_lahir = !empty($this->tanggal_lahir) ? date_format(date_create_from_format('d-m-Y', $this->tanggal_lahir), 'Y-m-d') : null;
        return parent::beforeValidate();
    }

    public function afterFind()
    {
        $this->tanggal_lahir = !is_null($this->tanggal_lahir) ? date_format(date_create_from_format('Y-m-d', $this->tanggal_lahir), 'd-m-Y') : '';
        return parent::afterFind();
    }

    /**
     * Memeriksa apakah profil mempunyai nomor member.
     * Fixme: Satu saat mungkin perlu status member atau bukan
     * @return boolean true jika punya nomor, false jika bukan
     */
    public function isMember()
    {
        return is_null($this->nomor) || empty($this->nomor) || $this->nomor == 0 ? false : true;
    }

    /**
     * isMemberOL function
     * Memeriksa apakah profil merupakan member online
     * @return boolean true jika member, false jika bukan
     */
    public function isMemberOL()
    {
        return $this->tipe_id == self::TIPE_MEMBER_ONLINE ? true : false;
    }
}
