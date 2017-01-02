<?php

/**
 * This is the model class for table "barang_diskon".
 *
 * The followings are the available columns in table 'barang_diskon':
 * @property string $id
 * @property integer $semua_barang
 * @property string $barang_id
 * @property integer $tipe_diskon_id
 * @property string $nominal
 * @property double $persen
 * @property string $dari
 * @property string $sampai
 * @property string $qty
 * @property string $qty_min
 * @property string $qty_max
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property User $updatedBy
 */
class DiskonBarang extends CActiveRecord
{

    const TIPE_PROMO = 0;
    const TIPE_GROSIR = 1;
    const TIPE_BANDED = 2;
    const TIPE_MANUAL = 3;
    const TIPE_PROMO_MEMBER = 4;
    /* ========= */
    const SEMUA_BARANG = 1;
    /* ========= */
    const STATUS_TIDAK_AKTIF = 0;
    const STATUS_AKTIF = 1;

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
        return array(
            array('tipe_diskon_id, nominal, dari', 'required', 'message' => '{attribute} harus diisi'),
            array('semua_barang, tipe_diskon_id, status', 'numerical', 'integerOnly' => true),
            array('persen', 'numerical'),
            array('barang_id, qty, qty_min, qty_max, updated_by', 'length', 'max' => 10),
            array('nominal', 'length', 'max' => 18),
            array('sampai, created_at, updated_at, updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, semua_barang, barang_id, tipe_diskon_id, nominal, persen, dari, sampai, qty, qty_min, qty_max, status, barcode, namaBarang', 'safe', 'on' => 'search'),
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
            'barang' => array(self::BELONGS_TO, 'Barang', 'barang_id'),
            'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'semua_barang' => 'Semua Barang',
            'barang_id' => 'Barang',
            'tipe_diskon_id' => 'Tipe Diskon',
            'nominal' => 'Diskon (Nominal)',
            'persen' => 'Diskon (%)',
            'dari' => 'Dari',
            'sampai' => 'Sampai',
            'qty' => 'Qty',
            'qty_min' => 'Qty Min',
            'qty_max' => 'Qty Max',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
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

        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('semua_barang', $this->semua_barang);
        $criteria->compare('barang_id', $this->barang_id, true);
        $criteria->compare('tipe_diskon_id', $this->tipe_diskon_id);
        $criteria->compare('nominal', $this->nominal, true);
        $criteria->compare('persen', $this->persen);
        $criteria->compare('dari', $this->dari, true);
        $criteria->compare('sampai', $this->sampai, true);
        $criteria->compare('qty', $this->qty, true);
        $criteria->compare('qty_min', $this->qty_min, true);
        $criteria->compare('qty_max', $this->qty_max, true);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $criteria->with = array('barang');
        $criteria->compare('barang.nama', $this->namaBarang, true);
        $criteria->compare('barang.barcode', $this->barcode, true);

        $sort = array(
            'defaultOrder' => 't.status desc, t.id desc',
            'attributes' => array(
                '*',
                'namaBarang' => array(
                    'asc' => 'barang.nama',
                    'desc' => 'barang.nama desc'
                ),
                'barcode' => array(
                    'asc' => 'barang.barcode',
                    'desc' => 'barang.barcode desc'
                )
            )
        );

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => $sort
        ));
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
        $this->updated_at = date("Y-m-d H:i:s");
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

    public function listTipe()
    {
        return array(
            self::TIPE_PROMO => 'Promo (diskon per waktu tertentu)',
            self::TIPE_PROMO_MEMBER => 'Promo Member',
            self::TIPE_GROSIR => 'Grosir (beli banyak harga turun)',
            self::TIPE_BANDED => 'Banded (beli qty tertentu harga turun)'
        );
    }

    public function listTipeSort()
    {
        return array(
            self::TIPE_PROMO => 'Promo',
            self::TIPE_PROMO_MEMBER => 'Promo Member',
            self::TIPE_GROSIR => 'Grosir',
            self::TIPE_BANDED => 'Banded'
        );
    }

    public function listStatus()
    {
        return array(
            self::STATUS_TIDAK_AKTIF => 'Tidak Aktif',
            self::STATUS_AKTIF => 'Aktif',
        );
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
        $this->barang_id = $this->semua_barang ? NULL : $this->barang_id;
        $this->dari = !empty($this->dari) ? date_format(date_create_from_format('d-m-Y H:i', $this->dari), 'Y-m-d H:i:s') : NULL;
        $this->sampai = !empty($this->sampai) ? date_format(date_create_from_format('d-m-Y H:i', $this->sampai), 'Y-m-d H:i:s') : NULL;
        $this->qty = empty($this->qty) ? NULL : $this->qty;
        $this->qty_max = empty($this->qty_max) ? NULL : $this->qty_max;
        $this->qty_min = empty($this->qty_min) ? NULL : $this->qty_min;

        /* Fixme: Pindahkan cek validasi di bawah ini ke tempat yang seharusnya */
        switch ($this->tipe_diskon_id) {
            case self::TIPE_PROMO:
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
        }
        return parent::beforeValidate();
    }

    public function afterFind()
    {
        $this->dari = !is_null($this->dari) ? date_format(date_create_from_format('Y-m-d H:i:s', $this->dari), 'd-m-Y H:i') : '';
        $this->sampai = !is_null($this->sampai) ? date_format(date_create_from_format('Y-m-d H:i:s', $this->sampai), 'd-m-Y H:i') : '';
        return parent::afterFind();
    }

    public function autoExpire()
    {
        try {
            $rowAffected = Yii::app()->db->createCommand("UPDATE barang_diskon SET status = 0 WHERE sampai <= NOW()")->execute();
            return [
                'sukses' => true,
                'rowAffected' => $rowAffected
            ];
        } catch (Exception $ex) {
            return [
                'sukses' => false,
                'error' => [
                    'msg' => $ex->getMessage(),
                    'code' => $ex->getCode(),
            ]];
        }
    }

}
