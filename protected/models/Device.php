<?php

/**
 * This is the model class for table "device".
 *
 * The followings are the available columns in table 'device':
 * @property string $id
 * @property integer $tipe_id
 * @property string $nama
 * @property string $keterangan
 * @property string $address
 * @property integer $lf_sebelum
 * @property integer $lf_setelah
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property User $updatedBy
 */
class Device extends CActiveRecord
{

    const TIPE_POS_CLIENT = 0;
    const TIPE_LPR = 1;
    const TIPE_TEXT_PRINTER = 2;
    const TIPE_PDF_PRINTER = 3;
    const TIPE_CSV_PRINTER = 4;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'device';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tipe_id', 'required'),
            array('tipe_id, lf_sebelum, lf_setelah', 'numerical', 'integerOnly' => true),
            array('nama, address', 'length', 'max' => 100),
            array('keterangan', 'length', 'max' => 500),
            array('updated_by', 'length', 'max' => 10),
            array('created_at, nama, updated_at, updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, tipe_id, nama, keterangan, address, updated_at, updated_by, created_at', 'safe', 'on' => 'search'),
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
            'tipe_id' => 'Tipe',
            'nama' => 'Nama',
            'keterangan' => 'Keterangan',
            'address' => 'Address',
            'lf_sebelum' => 'Baris Kosong Sebelum',
            'lf_setelah' => 'Baris Kosong Setelah',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'namaTipe' => 'Tipe'
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
        $criteria->compare('tipe_id', $this->tipe_id);
        $criteria->compare('nama', $this->nama, true);
        $criteria->compare('keterangan', $this->keterangan, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('lf_sebelum', $this->lf_sebelum);
        $criteria->compare('lf_setelah', $this->lf_setelah);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'tipe_id, nama'
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Device the static model class
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
        $this->updated_at = null; // Trigger current timestamp
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

    public function listTipe()
    {
        return array(
            Device::TIPE_POS_CLIENT => 'Client (Workstation)',
            Device::TIPE_LPR => 'Printer - LPR (Unix/Linux)',
            Device::TIPE_TEXT_PRINTER => 'Printer - Plain Text',
            Device::TIPE_PDF_PRINTER => 'Printer - PDF',
            Device::TIPE_CSV_PRINTER => 'Printer - CSV'
        );
    }

    public function getNamaTipe()
    {
        $listTipe = $this->listTipe();
        return $listTipe[$this->tipe_id];
    }

    public function listDevices($tipe = NULL)
    {
        $command = Yii::app()->db->createCommand()
                ->select('id, tipe_id, nama, keterangan, address')
                ->from($this->tableName())
                ->order('tipe_id, nama');
        if (!is_null($tipe)) {
            foreach ($tipe as $tipeId) {
                $command->orWhere("tipe_id={$tipeId}");
            }
        }
        return $command->queryAll();
    }

    public function printLpr($text)
    {
        $perintahPrinter = "-H {$this->address} -P {$this->nama}";

        $perintah = "echo \"{$text}\" |lpr {$perintahPrinter} -l";
        exec($perintah, $output);
    }

}
