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
 * @property string $default_printer_id
 * @property integer $lf_sebelum
 * @property integer $lf_setelah
 * @property integer $paper_autocut
 * @property integer $cashdrawer_kick
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Device $defaultPrinter
 * @property Device[] $devices
 * @property User $updatedBy
 * @property Kasir[] $kasirs
 */
class Device extends CActiveRecord
{
    const TIPE_POS_CLIENT      = 0;
    const TIPE_LPR             = 1;
    const TIPE_TEXT_PRINTER    = 2;
    const TIPE_PDF_PRINTER     = 3;
    const TIPE_CSV_PRINTER     = 4;
    const TIPE_BROWSER_PRINTER = 5;
    const TIPE_JSON_FILE       = 7; // Samakan dengan ahad-dc
    const TIPE_LABEL_ZPL       = 8;
    const TIPE_ESC_P_9PIN      = 9;

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
        return [
            ['tipe_id', 'required'],
            ['tipe_id, lf_sebelum, lf_setelah, paper_autocut, cashdrawer_kick', 'numerical', 'integerOnly' => true],
            ['nama, address', 'length', 'max' => 100],
            ['keterangan', 'length', 'max' => 500],
            ['default_printer_id, updated_by', 'length', 'max' => 10],
            ['created_at, nama, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, tipe_id, nama, keterangan, address, updated_at, updated_by, created_at', 'safe', 'on' => 'search'],
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
            'defaultPrinter' => [self::BELONGS_TO, 'Device', 'default_printer_id'],
            'devices'        => [self::HAS_MANY, 'Device', 'default_printer_id'],
            'updatedBy'      => [self::BELONGS_TO, 'User', 'updated_by'],
            'kasirs'         => [self::HAS_MANY, 'Kasir', 'device_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'                 => 'ID',
            'tipe_id'            => 'Tipe',
            'nama'               => 'Nama',
            'keterangan'         => 'Keterangan',
            'address'            => 'Address',
            'default_printer_id' => 'Default Printer',
            'lf_sebelum'         => 'LF Sebelum',
            'lf_setelah'         => 'LF Setelah',
            'paper_autocut'      => 'Otomatis Potong Kertas',
            'cashdrawer_kick'    => 'Otomatis Buka Laci Kas',
            'updated_at'         => 'Updated At',
            'updated_by'         => 'Updated By',
            'created_at'         => 'Created At',
            'namaTipe'           => 'Tipe',
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
        $criteria->compare('nama', $this->nama, true);
        $criteria->compare('keterangan', $this->keterangan, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('default_printer_id', $this->default_printer_id, true);
        $criteria->compare('lf_sebelum', $this->lf_sebelum);
        $criteria->compare('lf_setelah', $this->lf_setelah);
        $criteria->compare('paper_autocut', $this->paper_autocut);
        $criteria->compare('cashdrawer_kick', $this->cashdrawer_kick);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort'     => [
                'defaultOrder' => 'tipe_id, nama',
            ],
        ]);
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

    public function beforeValidate()
    {
        $this->default_printer_id = empty($this->default_printer_id) ? null : $this->default_printer_id;
        return parent::beforeValidate();
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
            Device::TIPE_POS_CLIENT      => 'Client (Workstation)',
            Device::TIPE_LPR             => 'Printer - LPR (Unix/Linux)',
            Device::TIPE_TEXT_PRINTER    => 'Printer - Plain Text',
            Device::TIPE_PDF_PRINTER     => 'Printer - PDF',
            Device::TIPE_CSV_PRINTER     => 'Printer - CSV',
            Device::TIPE_BROWSER_PRINTER => 'Printer - Browser',
            Device::TIPE_LABEL_ZPL       => 'Printer - Label (LPR)',
            Device::TIPE_JSON_FILE       => 'File - JSON',
            // Device::TIPE_ESC_P_9PIN => 'Printer - DM 9PIN (LPR)'
        ];
    }

    public function listPrinter()
    {
        return CHtml::listData(Device::model()->findAll('tipe_id !=' . self::TIPE_POS_CLIENT), 'id', 'nama');
    }

    public function getNamaTipe()
    {
        $listTipe = $this->listTipe();
        return $listTipe[$this->tipe_id];
    }

    /**
     * Ambil data-data device(s) yang diperlukan
     * @param array $tipe array of tipe printer
     * @return array id, tipe_id, nama, keterangan dari device
     */
    public function listDevices($tipe = null)
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

    public function revisiText($text)
    {
        $revText = '';
        if ($this->tipe_id == self::TIPE_LPR) {
            $revText = chr(27) . '@'; //Init printer
        }
        // Tambahkan line feed, jika ada
        for ($index = 0; $index < $this->lf_sebelum; $index++) {
            $revText .= PHP_EOL;
        }
        $revText .= $text;
        for ($index1 = 0; $index1 < $this->lf_setelah; $index1++) {
            $revText .= PHP_EOL;
        }
        return $revText . $this->potongKertas();
    }

    public function potongKertas()
    {
        $r = '';
        if ($this->paper_autocut == 1) {
            $r = chr(27) . '@' . chr(29) . 'V' . chr(1);
        }
        return $r;
    }

    public function bukaLaciKas()
    {
        /**
         * Init printer, dan buka cash drawer
         */
        $command = chr(27) . '@'; //Init printer
        $command .= chr(27) . chr(112) . chr(48) . chr(60) . chr(120); // buka cash drawer
        $command .= chr(27) . chr(101) . chr(1); //1 reverse lf

        $perintahPrinter = "-H {$this->address} -P {$this->nama}";

        $perintah = "echo \"{$command}\" |lpr {$perintahPrinter} -l";
        exec($perintah, $output);
    }

    public function printLpr($text)
    {
        if ($this->cashdrawer_kick == 1) {
            $this->bukaLaciKas();
        }
        $perintahPrinter = "-H {$this->address} -P {$this->nama}";

        $perintah = "echo \"{$this->revisiText($text)}\" |lpr {$perintahPrinter} -l";
        exec($perintah, $output);
    }

    public function exportText($text)
    {
        $perintahPrinter = "-H {$this->address} -P {$this->nama}";

        $perintah = "echo \"{$this->revisiText($text)}\" |lpr {$perintahPrinter} -l";
        exec($perintah, $output);
    }
}
