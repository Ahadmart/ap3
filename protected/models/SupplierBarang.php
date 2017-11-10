<?php

/**
 * This is the model class for table "supplier_barang".
 *
 * The followings are the available columns in table 'supplier_barang':
 * @property string $id
 * @property string $supplier_id
 * @property string $barang_id
 * @property integer $default
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property Profil $supplier
 * @property User $updatedBy
 */
class SupplierBarang extends CActiveRecord
{

    const SUPPLIER_DEFAULT = 1;

    public $namaSupplier;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'supplier_barang';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('supplier_id, barang_id', 'required'),
            array('default', 'numerical', 'integerOnly' => true),
            array('supplier_id, barang_id, updated_by', 'length', 'max' => 10),
            array('created_at, updated_at, updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, supplier_id, barang_id, default, updated_at, updated_by, created_at, namaSupplier', 'safe', 'on' => 'search'),
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
            'supplier' => array(self::BELONGS_TO, 'Profil', 'supplier_id'),
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
            'supplier_id' => 'Supplier',
            'barang_id' => 'Barang',
            'default' => 'Default',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'namaSupplier' => 'Supplier'
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
        $criteria->compare('supplier_id', $this->supplier_id, true);
        $criteria->compare('barang_id', $this->barang_id, true);
        $criteria->compare('default', $this->default);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $criteria->with = array('supplier');
        $criteria->compare('supplier.nama', $this->namaSupplier, true);

        $sort = array(
            'defaultOrder' => 'supplier.nama',
            'attributes' => array(
                'namaSupplier' => array(
                    'asc' => 'supplier.nama',
                    'desc' => 'supplier.nama desc'
                ),
                '*'
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
     * @return SupplierBarang the static model class
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

    public function assignDefaultSupplier($id, $barangId)
    {
        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        try {

            // Update semua supplier jadi tidak default, default = 0
            $connection->createCommand()->update($this->tableName(), array('default' => 0), 'barang_id=:barangId', array(
                ':barangId' => $barangId
            ));

            // Update 1 supplier jadi default = 1
            $connection->createCommand()->update($this->tableName(), array('default' => 1), 'id=:id', array(
                ':id' => $id
            ));
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }

    public function ambilBarangBarcodePerSupplier($supplierId)
    {
        return Yii::app()->db->createCommand()
                        ->select('b.id, b.barcode, b.nama')
                        ->from($this->tableName() . ' sb')
                        ->join(Barang::model()->tableName() . ' b', 'b.id=sb.barang_id')
                        ->where('supplier_id=:supplierId and b.status=1', array(':supplierId' => $supplierId))
                        ->order('b.barcode')
                        ->queryAll();
    }

    public function ambilBarangNamaPerSupplier($supplierId)
    {
        return Yii::app()->db->createCommand()
                        ->select('b.id, b.nama, b.barcode')
                        ->from($this->tableName() . ' sb')
                        ->join(Barang::model()->tableName() . ' b', 'b.id=sb.barang_id')
                        ->where('supplier_id=:supplierId and b.status=1', array(':supplierId' => $supplierId))
                        ->order('b.nama')
                        ->queryAll();
    }

}
