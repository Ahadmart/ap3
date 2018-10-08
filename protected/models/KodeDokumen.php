<?php

/**
 * This is the model class for table "kode_dokumen".
 *
 * The followings are the available columns in table 'kode_dokumen':
 * @property integer $id
 * @property string $kode
 * @property string $nama
 * @property string $created_at
 * @property string $updated_at
 * @property string $updated_by
 */
class KodeDokumen extends CActiveRecord
{
    /*
     * Data disimpan sebagai konstanta, agar lebih cepat aksesnya
     * Tapi sudah disediakan tabel jika akan disimpan di DB (jika ingin lebih fleksibel)
     */

    const PEMBELIAN = '01';
    const RETUR_PEMBELIAN = '02';
    const PENJUALAN = '03';
    const RETUR_PENJUALAN = '04';
    const SO = '05';
    const HUTANG = '06';
    const PIUTANG = '07';
    const PENGELUARAN = '08';
    const PENERIMAAN = '09';
    const AKM = '10';
    const PO = '11'; // PURCHASE ORDER (PESANAN PEMBELIAN)
    const SALES_ORDER = '12'; // PESANAN PENJUALAN

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'kode_dokumen';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('kode, nama, updated_at, updated_by', 'required'),
            array('kode', 'length', 'max' => 2),
            array('nama', 'length', 'max' => 45),
            array('updated_by', 'length', 'max' => 10),
            array('created_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, kode, nama, created_at, updated_at, updated_by', 'safe', 'on' => 'search'),
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
            'kode' => 'Kode',
            'nama' => 'Nama',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('kode', $this->kode, true);
        $criteria->compare('nama', $this->nama, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return KodeDokumen the static model class
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

    public function listKodeNamaDokumen()
    {
        return [
            self::PEMBELIAN => 'Pembelian',
            self::RETUR_PEMBELIAN => 'Retur Pembelian',
            self::PENJUALAN => 'Penjualan',
            self::RETUR_PENJUALAN => 'Retur Penjualan',
            self::SO => 'Stock Opname'
        ];
    }

    public function getNamaDokumen($kode)
    {
        return $this->listKodeNamaDokumen()[$kode];
    }

}
