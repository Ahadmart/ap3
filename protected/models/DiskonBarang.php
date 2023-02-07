<?php

/**
 * This is the model class for table "barang_diskon".
 *
 * The followings are the available columns in table 'barang_diskon':
 * @property string $id
 * @property integer $semua_barang
 * @property string $barang_id
 * @property integer $tipe_diskon_id
 * @property string $barang_kategori_id
 * @property string $barang_struktur_id
 * @property string $nominal
 * @property double $persen
 * @property string $dari
 * @property string $sampai
 * @property string $qty
 * @property string $qty_min
 * @property string $qty_max
 * @property string $barang_bonus_id
 * @property string $barang_bonus_diskon_nominal
 * @property double $barang_bonus_diskon_persen
 * @property string $barang_bonus_qty
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property Barang $barangBonus
 * @property BarangKategori $barangKategori
 * @property BarangStruktur $barangStruktur
 * @property User $updatedBy
 */
class DiskonBarang extends CActiveRecord
{
    const TIPE_PROMO              = 0;
    const TIPE_GROSIR             = 1;
    const TIPE_BANDED             = 2;
    const TIPE_MANUAL             = 3;
    const TIPE_PROMO_MEMBER       = 4;
    const TIPE_QTY_GET_BARANG     = 5;
    const TIPE_NOMINAL_GET_BARANG = 6;
    const TIPE_PROMO_PERKATEGORI  = 7;
    const TIPE_PROMO_PERSTRUKTUR  = 8;
    /* ========= */
    const SEMUA_BARANG = 1;
    /* ========= */
    const STATUS_TIDAK_AKTIF = 0;
    const STATUS_AKTIF       = 1;

    public $barcode;
    public $namaBarang;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'barang_diskon';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['tipe_diskon_id, nominal, dari', 'required', 'message' => '{attribute} harus diisi'],
            ['semua_barang, tipe_diskon_id, status', 'numerical', 'integerOnly' => true],
            ['persen, barang_bonus_diskon_persen', 'numerical'],
            ['barang_id, barang_kategori_id, barang_struktur_id, qty, qty_min, qty_max, barang_bonus_id, barang_bonus_qty, updated_by', 'length', 'max' => 10],
            ['nominal, barang_bonus_diskon_nominal', 'length', 'max' => 18],
            ['sampai, created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, semua_barang, barang_id, barang_kategori_id, barang_struktur_id, tipe_diskon_id, nominal, persen, dari, sampai, qty, qty_min, qty_max, barang_bonus_id, barang_bonus_diskon_nominal, barang_bonus_diskon_persen, barang_bonus_qty, status, barcode, namaBarang', 'safe', 'on' => 'search'],
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
            'barang'         => [self::BELONGS_TO, 'Barang', 'barang_id'],
            'barangBonus'    => [self::BELONGS_TO, 'Barang', 'barang_bonus_id'],
            'barangKategori' => [self::BELONGS_TO, 'KategoriBarang', 'barang_kategori_id'],
            'barangStruktur' => [self::BELONGS_TO, 'StrukturBarang', 'barang_struktur_id'],
            'updatedBy'      => [self::BELONGS_TO, 'User', 'updated_by'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'                          => 'ID',
            'semua_barang'                => 'Semua Barang',
            'barang_id'                   => 'Barang',
            'tipe_diskon_id'              => 'Tipe Diskon',
            'barang_kategori_id'          => 'Kategori Barang',
            'barang_struktur_id'          => 'Struktur Barang',
            'nominal'                     => 'Diskon (Nominal)',
            'persen'                      => 'Diskon (%)',
            'dari'                        => 'Dari',
            'sampai'                      => 'Sampai',
            'qty'                         => 'Qty',
            'qty_min'                     => 'Qty Min',
            'qty_max'                     => 'Qty Max',
            'barang_bonus_id'             => 'Barang Bonus',
            'barang_bonus_diskon_nominal' => 'Barang Bonus Diskon (Nominal)',
            'barang_bonus_diskon_persen'  => 'Barang Bonus Diskon (%)',
            'barang_bonus_qty'            => 'Barang Bonus Qty',
            'status'                      => 'Status',
            'updated_at'                  => 'Updated At',
            'updated_by'                  => 'Updated By',
            'created_at'                  => 'Created At',
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

        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('semua_barang', $this->semua_barang);
        $criteria->compare('barang_id', $this->barang_id, true);
        $criteria->compare('tipe_diskon_id', $this->tipe_diskon_id);
        $criteria->compare('barang_kategori_id', $this->barang_kategori_id, true);
        $criteria->compare('barang_struktur_id', $this->barang_struktur_id, true);
        $criteria->compare('nominal', $this->nominal, true);
        $criteria->compare('persen', $this->persen);
        $criteria->compare('dari', $this->dari, true);
        $criteria->compare('sampai', $this->sampai, true);
        $criteria->compare('qty', $this->qty, true);
        $criteria->compare('qty_min', $this->qty_min, true);
        $criteria->compare('qty_max', $this->qty_max, true);
        $criteria->compare('barang_bonus_id', $this->barang_bonus_id);
        $criteria->compare('barang_bonus_diskon_nominal', $this->barang_bonus_diskon_nominal, true);
        $criteria->compare('barang_bonus_diskon_persen', $this->barang_bonus_diskon_persen);
        $criteria->compare('barang_bonus_qty', $this->barang_bonus_qty, true);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $criteria->with = ['barang'];
        $criteria->compare('barang.nama', $this->namaBarang, true);
        $criteria->compare('barang.barcode', $this->barcode, true);

        $sort = [
            'defaultOrder' => 't.status desc, t.id desc',
            'attributes'   => [
                '*',
                'namaBarang' => [
                    'asc'  => 'barang.nama',
                    'desc' => 'barang.nama desc',
                ],
                'barcode'    => [
                    'asc'  => 'barang.barcode',
                    'desc' => 'barang.barcode desc',
                ],
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
     * @return DiskonBarang the static model class
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

    public function listTipe()
    {
        return [
            self::TIPE_PROMO              => 'Promo (diskon per waktu tertentu)',
            self::TIPE_PROMO_PERKATEGORI  => 'Promo per Kategori Barang',
            self::TIPE_PROMO_PERSTRUKTUR  => 'Promo per Struktur Barang',
            self::TIPE_PROMO_MEMBER       => 'Promo Member',
            self::TIPE_GROSIR             => 'Grosir (beli banyak harga turun)',
            self::TIPE_BANDED             => 'Banded (beli qty tertentu harga turun)',
            self::TIPE_QTY_GET_BARANG     => 'Beli x dapat y (Quantity tertentu dapat barang)',
            self::TIPE_NOMINAL_GET_BARANG => 'Beli Rp.x dapat y (Nominal tertentu dapat barang)',
        ];
    }

    public function listTipeSort()
    {
        return [
            self::TIPE_PROMO              => 'Promo',
            self::TIPE_PROMO_PERKATEGORI  => 'Promo per Kategori',
            self::TIPE_PROMO_PERSTRUKTUR  => 'Promo per Struktur',
            self::TIPE_PROMO_MEMBER       => 'Promo Member',
            self::TIPE_GROSIR             => 'Grosir',
            self::TIPE_BANDED             => 'Banded',
            self::TIPE_QTY_GET_BARANG     => 'Beli x dapat y',
            self::TIPE_NOMINAL_GET_BARANG => 'Beli Rp.x dapat y',
        ];
    }

    public function listStatus()
    {
        return [
            self::STATUS_TIDAK_AKTIF => 'Tidak Aktif',
            self::STATUS_AKTIF       => 'Aktif',
        ];
    }

    public function getNamaTipe()
    {
        return $this->listTipe()[$this->tipe_diskon_id];
    }

    public function getNamaTipeSort()
    {
        return $this->listTipeSort()[$this->tipe_diskon_id];
    }

    public function getNamaStatus()
    {
        return $this->listStatus()[$this->status];
    }

    public function beforeValidate()
    {
        $this->barang_id                   = $this->semua_barang ? null : $this->barang_id;
        $this->barang_id                   = $this->tipe_diskon_id == self::TIPE_PROMO_PERKATEGORI || $this->tipe_diskon_id == self::TIPE_PROMO_PERSTRUKTUR ? null : $this->barang_id;
        $this->barang_kategori_id          = empty($this->barang_kategori_id) ? null : $this->barang_kategori_id;
        $this->barang_struktur_id          = empty($this->barang_struktur_id) ? null : $this->barang_struktur_id;
        $this->dari                        = !empty($this->dari) ? date_format(date_create_from_format('d-m-Y H:i', $this->dari), 'Y-m-d H:i:s') : null;
        $this->sampai                      = !empty($this->sampai) ? date_format(date_create_from_format('d-m-Y H:i', $this->sampai), 'Y-m-d H:i:s') : null;
        $this->qty                         = empty($this->qty) ? null : $this->qty;
        $this->qty_max                     = empty($this->qty_max) ? null : $this->qty_max;
        $this->qty_min                     = empty($this->qty_min) ? null : $this->qty_min;
        $this->persen                      = $this->persen == 'Infinity' ? 0 : $this->persen;
        $this->barang_bonus_id             = empty($this->barang_bonus_id) ? null : $this->barang_bonus_id;
        $this->barang_bonus_diskon_nominal = empty($this->barang_bonus_diskon_nominal) ? null : $this->barang_bonus_diskon_nominal;
        $this->barang_bonus_diskon_persen  = empty($this->barang_bonus_diskon_persen) ? null : $this->barang_bonus_diskon_persen;
        $this->barang_bonus_qty            = empty($this->barang_bonus_qty) ? null : $this->barang_bonus_qty;

        /* Fixme: Pindahkan cek validasi di bawah ini ke tempat yang seharusnya */
        switch ($this->tipe_diskon_id) {
            case self::TIPE_PROMO:
                $this->semua_barang = 0; // Belum bisa untuk semua barang
                if (empty($this->qty_max)) {
                    return false;
                }
                break;
            case self::TIPE_GROSIR:
                if (empty($this->qty_min)) {
                    return false;
                }
                break;
            case self::TIPE_BANDED:
                if (empty($this->qty)) {
                    return false;
                }
                break;
            case self::TIPE_PROMO_MEMBER:
                if (empty($this->qty_max)) {
                    return false;
                }
                break;
            case self::TIPE_QTY_GET_BARANG:
                $this->nominal = 0;

                if (is_null($this->barang_bonus_id)) {
                    $this->barang_bonus_id = $this->barang_id;
                }
                if (is_null($this->qty_max)) {
                    $this->qty_max = $this->qty;
                }
                break;
            case self::TIPE_NOMINAL_GET_BARANG:
                $this->semua_barang = 1;
                break;
            case self::TIPE_PROMO_PERKATEGORI:
                $this->semua_barang = 1;
                if (empty($this->qty_max)) {
                    return false;
                }
                break;
            case self::TIPE_PROMO_PERSTRUKTUR:
                $this->semua_barang = 1;
                if (empty($this->qty_max)) {
                    return false;
                }
        }
        return parent::beforeValidate();
    }

    public function afterFind()
    {
        $this->dari   = !is_null($this->dari) ? date_format(date_create_from_format('Y-m-d H:i:s', $this->dari), 'd-m-Y H:i') : '';
        $this->sampai = !is_null($this->sampai) ? date_format(date_create_from_format('Y-m-d H:i:s', $this->sampai), 'd-m-Y H:i') : '';
        return parent::afterFind();
    }

    public function autoExpire()
    {
        try {
            $rowAffected = Yii::app()->db->createCommand('UPDATE barang_diskon SET status = 0 WHERE sampai <= NOW()')->execute();
            return [
                'sukses'      => true,
                'rowAffected' => $rowAffected,
            ];
        } catch (Exception $ex) {
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
     * List SEMUA Nama Tipe Diskon
     * @return array Nama Jenis Tipe Diskon
     */
    public static function listNamaTipe()
    {
        return [
            self::TIPE_PROMO              => 'Promo',
            self::TIPE_PROMO_PERKATEGORI  => 'Promo perKategori',
            self::TIPE_PROMO_PERSTRUKTUR  => 'Promo perStruktur',
            self::TIPE_PROMO_MEMBER       => 'Promo Member',
            self::TIPE_MANUAL             => 'Manual/Admin',
            self::TIPE_GROSIR             => 'Grosir',
            self::TIPE_BANDED             => 'Banded',
            self::TIPE_QTY_GET_BARANG     => 'Beli x dapat y',
            self::TIPE_NOMINAL_GET_BARANG => 'Beli Rp.x dapat y',
        ];
    }

    public static function namaTipeDiskon($tipeId)
    {
        return self::listNamaTipe()[$tipeId];
    }

    public function getStrukturFullPath()
    {
        $struktur = StrukturBarang::model()->findByPk($this->barang_struktur_id);
        if (!is_null($struktur) && $this->tipe_diskon_id == self::TIPE_PROMO_PERSTRUKTUR) {
            return $struktur->getFullPath();
        }
        return null;
    }
}
