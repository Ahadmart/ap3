<?php

/**
 * This is the model class for table "label_rak_cetak".
 *
 * The followings are the available columns in table 'label_rak_cetak':
 * @property string $barang_id
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property User $updatedBy
 */
class LabelRakCetak extends CActiveRecord
{

    public $barcode;
    public $namaBarang;
    public $kategoriId;
    public $namaKategori;
    public $namaSatuan;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'label_rak_cetak';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('barang_id', 'required'),
            array('barang_id, updated_by', 'length', 'max' => 10),
            array('created_at, updated_at, updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('barang_id, updated_at, updated_by, created_at, kategoriId', 'safe', 'on' => 'search'),
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
            'barang_id' => 'Barang',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'namaBarang' => 'Nama',
            'kategoriId' => 'Kategori',
            'namaKategori' => 'Kategori',
            'namaSatuan' => 'Satuan'
        );
    }

    public function defaultScope()
    {
        return array(
            'with' => 'barang',
            'order' => 'barang.nama'
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

        $criteria->compare('barang_id', $this->barang_id, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $criteria->with = array('barang');
        $criteria->compare('barang.barcode', $this->barcode);
        $criteria->compare('barang.nama', $this->namaBarang);
        $criteria->compare('barang.kategori_id', $this->kategoriId);
        //$criteria->compare('kategori.nama', $this->namaKategori);

        $sort = array(
            'defaultOrder' => 'barang.nama',
            'attributes' => array(
                '*',
                'barcode' => array(
                    'asc' => 'barang.barcode',
                    'desc' => 'barang.barcode desc'
                ),
                'namaBarang' => array(
                    'asc' => 'barang.nama',
                    'desc' => 'barang.nama desc'
                ),
//                'namaKategori' => array(
//                    'asc' => 'kategori.nama',
//                    'desc' => 'kategori.nama desc'
//                )
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
     * @return LabelRakCetak the static model class
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

    public function filterKategori()
    {
        $kategori = Yii::app()->db->createCommand()->
                selectDistinct('kategori.id, kategori.nama')->
                from('label_rak_cetak label')->
                join('barang', 'label.barang_id = barang.id')->
                join('barang_kategori kategori', 'barang.kategori_id = kategori.id')->
                order('kategori.nama')->
                queryAll();

        return CHtml::listData($kategori, 'id', 'nama');
    }

}
