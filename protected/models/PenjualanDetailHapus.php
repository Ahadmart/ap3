<?php

/**
 * This is the model class for table "penjualan_detail_h".
 *
 * The followings are the available columns in table 'penjualan_detail_h':
 * @property string $id
 * @property string $barang_id
 * @property string $barang_barcode
 * @property string $barang_nama
 * @property string $harga_beli
 * @property string $harga_jual
 * @property string $user_kasir_id
 * @property string $user_kasir_nama
 * @property string $user_admin_id
 * @property string $user_admin_nama
 * @property string $penjualan_id
 * @property integer $jenis
 * @property string $waktu
 */
class PenjualanDetailHapus extends CActiveRecord
{

    const JENIS_PER_BARANG = 0;
    const JENIS_PER_NOTA = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'penjualan_detail_h';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('barang_id, barang_barcode, barang_nama, user_kasir_id, user_kasir_nama, user_admin_id, user_admin_nama, penjualan_id', 'required'),
            array('jenis', 'numerical', 'integerOnly' => true),
            array('barang_id, user_kasir_id, user_admin_id, penjualan_id', 'length', 'max' => 10),
            array('barang_barcode', 'length', 'max' => 30),
            array('barang_nama, user_kasir_nama, user_admin_nama', 'length', 'max' => 45),
            array('harga_beli, harga_jual', 'length', 'max' => 18),
            array('waktu', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, barang_id, barang_barcode, barang_nama, harga_beli, harga_jual, user_kasir_id, user_kasir_nama, user_admin_id, user_admin_nama, penjualan_id, jenis, waktu', 'safe', 'on' => 'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'barang_id' => 'Barang',
            'barang_barcode' => 'Barang Barcode',
            'barang_nama' => 'Barang Nama',
            'harga_beli' => 'Harga Beli',
            'harga_jual' => 'Harga Jual',
            'user_kasir_id' => 'User Kasir',
            'user_kasir_nama' => 'User Kasir Nama',
            'user_admin_id' => 'User Admin',
            'user_admin_nama' => 'User Admin Nama',
            'penjualan_id' => 'Penjualan',
            'jenis' => 'Jenis',
            'waktu' => 'Waktu',
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
        $criteria->compare('barang_id', $this->barang_id, true);
        $criteria->compare('barang_barcode', $this->barang_barcode, true);
        $criteria->compare('barang_nama', $this->barang_nama, true);
        $criteria->compare('harga_beli', $this->harga_beli, true);
        $criteria->compare('harga_jual', $this->harga_jual, true);
        $criteria->compare('user_kasir_id', $this->user_kasir_id, true);
        $criteria->compare('user_kasir_nama', $this->user_kasir_nama, true);
        $criteria->compare('user_admin_id', $this->user_admin_id, true);
        $criteria->compare('user_admin_nama', $this->user_admin_nama, true);
        $criteria->compare('penjualan_id', $this->penjualan_id, true);
        $criteria->compare('jenis', $this->jenis);
        $criteria->compare('waktu', $this->waktu, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PenjualanDetailHapus the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

}
