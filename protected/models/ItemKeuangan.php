<?php

/**
 * This is the model class for table "item_keuangan".
 *
 * The followings are the available columns in table 'item_keuangan':
 * @property string $id
 * @property string $nama
 * @property string $parent_id
 * @property integer $jenis
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $updated_by
 *
 * The followings are the available model relations:
 * @property ItemKeuangan $parent
 * @property ItemKeuangan[] $itemKeuangans
 * @property User $updatedBy
 */
class ItemKeuangan extends CActiveRecord
{

    const ITEM_PENGELUARAN = 0;
    const ITEM_PENERIMAAN = 1;
    const ITEM_TRX_SAJA = 100; //Item keuangan ID, selain bersumber dari dokumen, dimulai dari nomor ini
    const STATUS_TIDAK_AKTIF = 0;
    const STATUS_AKTIF = 1;

    /* Item Keuangan ID untuk mencatat penjualan */
    const ITEM_PENJUALAN = 4;

    /* Item Keuangan ID untuk mencatat infaq/shodaqoh via transaksi POS */
    const POS_INFAQ = 10;

    /* Item Keuangan ID untuk mencatat DISKON PER NOTA transaksi POS */
    const POS_DISKON_PER_NOTA = 11;

    /* Item Keuangan ID untuk mencatat Pengeluaran Kas pada transaksi Tarik Tunai di POS */
    const POS_TARIK_TUNAI_PENGELUARAN = 12;
    /* Item Keuangan ID untuk mencatat Penerimaan Bank pada transaksi Tarik Tunai di POS */
    const POS_TARIK_TUNAI_PENERIMAAN = 13;

    /* Item Keuangan ID untuk mencatat koin cashback member online yang dipakai di POS */
    const POS_KOINCASHBACK_DIPAKAI = 14;
    /* Item Keuangan ID untuk mencatat voucher member online yang dipakai di POS */
    const POS_VOUCHER_MEMBERSHIP = 15;

    public $jenisTrx;
    public $namaParent;

    // public $hanyaDetail = false;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'item_keuangan';
    }

    public function scopes()
    {
        return [
            'aktif' => [
                'condition' => 't.status = ' . self::STATUS_AKTIF
            ]
        ];
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nama, jenis', 'required'),
            array('jenis, status', 'numerical', 'integerOnly' => true),
            array('nama', 'length', 'max' => 45),
            array('parent_id, updated_by', 'length', 'max' => 10),
            array('created_at, updated_at, updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, nama, parent_id, jenis, status, namaParent', 'safe', 'on' => 'search'),
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
            'parent' => array(self::BELONGS_TO, 'ItemKeuangan', 'parent_id'),
            'itemKeuangans' => array(self::HAS_MANY, 'ItemKeuangan', 'parent_id'),
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
            'nama' => 'Nama',
            'parent_id' => 'Parent',
            'jenis' => 'Jenis',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'namaParent' => 'Parent',
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

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.nama', $this->nama, true);
        $criteria->compare('t.parent_id', $this->parent_id);
        $criteria->compare('jenis', $this->jenis);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);

        $criteria->with = array('parent');
        $criteria->compare('parent.nama', $this->namaParent, true);

        if (isset($this->jenisTrx)) {
            $criteria->addCondition('t.jenis=' . $this->jenisTrx);
        }

        if ($this->scenario == 'hanyaDetail') {
            $criteria->join = "left join item_keuangan item2 on t.id = item2.parent_id";
            $criteria->addCondition("item2.parent_id is null");
        }

        $sort = array(
            'defaultOrder' => 'parent.nama, t.nama',
            'attributes' => array(
                '*',
                'namaParent' => array(
                    'asc' => 'parent.nama',
                    'desc' => 'parent.nama desc'
                ),
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
     * @return ItemKeuangan the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeValidate()
    {
        if (empty($this->parent_id)) {
            $this->parent_id = null;
        }
        return parent::beforeValidate();
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

    public function listStatus()
    {
        return [
            self::STATUS_TIDAK_AKTIF => 'Non Aktif',
            self::STATUS_AKTIF => 'Aktif'
        ];
    }

    public function getNamaStatus()
    {
        return $this->listStatus()[$this->status];
    }
}
